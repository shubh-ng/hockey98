<?php
require_once('_database.php');

class WithdrawInfo {
    var $conn;
    var $validDenominations;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function getTodaysWithdrawalCount($userId) {
        try {
            $query = "SELECT count(*) as count FROM withdrawreport WHERE userId = :userId AND date(createdAt) = CURDATE();";

            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":userId", $userId);

            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['count'];
        } catch(Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function checkDateValidity() {
        $validDates = [ 15,16,17 ];
        date_default_timezone_set("Asia/Kolkata");
        $date = date("d");
        foreach($validDates as $validDate) {
            if($validDate == $date) return 1;
        }
        return 0;
    }

    public function checkDenomination($amount) {
        $query = "SELECT activeDenominations from admin WHERE adminId='HOCKEY98';";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC)['activeDenominations'];

        $this->validDenominations = explode(",", $res);
        foreach($this->validDenominations as $denomination) {
            if($amount == $denomination) return 1;
        }
        return 0;
    }

    public function takeMsgByStatus($status) {
        switch($status) {
            case "success":
                return "Money is successfully credited to your account.";
            case "accepted":
            case "in_process":
            case "pending":
            case "on_hold":
                return "Money has been processed. Please check transaction history for more details.";
            default:
                return "Transfer request could not be processed. Please try again after sometime.";
        }
    }

    public function payToAdmin($amount) {
        $res = false;
        try {
            $query = "UPDATE admin SET amount = amount + :amount WHERE adminId = :adminId;";

            $stmt = $this->conn->prepare($query);
            
            $adminId = ADMIN_ID;

            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":adminId", $adminId);

            $stmt->execute();

            if($stmt->rowCount()) {
                $res = true;
            }
        }catch(Exception $e) {
            $res = false;
        }
        return $res;
    }

    public function tryToWithdraw() {
        $response = [];
        try {
            $this->conn->beginTransaction();

            $userId = $_SESSION['userId'];
            $amount = htmlspecialchars($_POST['amount']);
              
                
            if(!$this->checkDateValidity()) {
                throw new Exception("You can withdraw only at 15th, 16th or 17th.");
            }

            if(!$this->checkDenomination($amount)) {
                $validRs = join(", ", $this->validDenominations);
                throw new Exception("Invalid Amount Entered, Allowed Denominations Rs. $validRs only");
            }

            $count = $this->getTodaysWithdrawalCount($userId);
            if($count >= DAILY_WITHDRAW_LIMIT) {
                throw new Exception("Daily Withdraw Limit Exceeded!");
            }

            $statsInfo = new StatsInfo();
            $stats = $statsInfo->getStatsByUserId($userId);
            $remainingBalance = $stats['totalBalance'] - $stats['totalWithdrawal'];
            
            if($amount <= $remainingBalance) { // initiate withdraw
                $deductions = $amount * 20.0 / 100.0; // admin charges + tds
                $amount -= $deductions;
                $amount -= 3.54; // easebuzz + gst
                $result = $this->onlineWithdraw($amount);
               // print_r($result);
 
                if(is_array($result) && isset($result['status'])) { // transaction not stored in db
                    throw new Exception($result['status_message']);
                }

                if($result->success) {
                    $data = $result->data; // to be stored into db
                    $status = $data->transfer_request->status;
                    $urn = $data->transfer_request->unique_request_number;
                    $msg = $this->takeMsgByStatus($status);

                    $initialAmount = $amount + $deductions + 3.54;
                    // store json to db
                    $snapshot = new SnapshotInfo();
                    $result = $snapshot->updateJson(
                        $urn,
                        json_encode($data) 
                    );

                    if($status == "success") { // SUCCESS, give to admin
                        $response = $this->withdraw($stats, $initialAmount);
                        if($response['status'] == 1) {
                            $response = ["status"=>1, "status_message"=>"Amount $amount Withdrawn Successfully"];
                            $this->conn->commit();
                        }else {
                            throw new Exception("There was some problem. Please see transaction history.");
                        }
                        $this->payToAdmin($deductions);
                    }else if($status == "rejected" || $status == "failure") {
                        throw new Exception($msg);  
                    }else { // some pending status
                        $response = $this->withdraw($stats, $initialAmount);
                        if($response['status'] == 1) {
                            $response = ["status"=>2, "status_message"=>$msg];
                            $this->conn->commit();
                        }else {
                            throw new Exception("There was some problem. Please see transaction history.");
                        }
                    }
                }else { // failure
                    throw new Exception($result->message);
                }
            }else { 
                throw new Exception("Insufficient Balance in your wallet.");
            }
        }catch(Exception $e) {
            $response =  ["status"=>0, "status_message"=>$e->getMessage()];
            $this->conn->rollback();
        }
        return $response;
    }

    public function onlineWithdraw($amount) {
        $urn = rand(10000, 100000000); // unique no

        $withdrawHelper = new WithdrawHelper();
        $withdrawHelper->setBeneficiaryType("bank_account");
        $withdrawHelper->setBeneficiaryName(htmlspecialchars($_POST['name']));
        $withdrawHelper->setAccountNumber(htmlspecialchars($_POST['account']));
        $withdrawHelper->setIfsc(htmlspecialchars($_POST['ifsc']));
        $withdrawHelper->setPaymentMode("IMPS");
        $withdrawHelper->setUniqueRequestNumber("$urn");
        $withdrawHelper->setAmount($amount);

        // save initiate transaction snapshot
        $snapshot = new SnapshotInfo();
        $result = $snapshot->createSnapshot($urn, $_SESSION['userId'], $amount);

        if($result['status'])
            return $withdrawHelper->initiateQuickTransfer();
        return $result;
    }

    public function withdraw($stats, $amount) {
        try {
            $query = "UPDATE stats SET totalWithdrawal = totalWithdrawal + :amount WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $id = $stats['id'];

            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":id", $id);

            $stmt->execute();
            if($stmt->rowCount()) {
                $this->generateWithdrawReport($stats['userId'], $amount, 'SL');
                $response =  ["status"=>1];
            }else {
                $response =  ["status"=>0];
            }
        }catch(Exception $e) {
            $response =  ["status"=>0, "status_message"=>$e->getMessage()];
        }
        return $response;
    }

    public function generateWithdrawReport($userId, $amount, $withdrawType) {
        try {
            $query = "INSERT INTO withdrawreport (userId, amount, withdrawType) VALUES (:userId, :amount, :withdrawType);";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":withdrawType", $withdrawType);

            $stmt->execute();

            $response = ["status"=>1];
        }catch(Exception $e) {
            $response = ["status"=>0];
        }

        return $response;
    }
    

    public function getWithdrawReport($userId) {
        try {
            $query = "SELECT * FROM withdrawreport WHERE userId = :userId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            
            $stmt->execute();

            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch(Exception $e) {
            echo $e->getMessage();
            return [];
        }
        return $res;
    }

    public function toggleWithdraw($status) {
        try {
            $query = "UPDATE admin SET canWithdraw = :status";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":status", $status);
            
            $stmt->execute();

            $status = $status==1?"ON":"OFF";

            if($stmt->rowCount()) {
                $res = ["status"=>1, "status_message"=>"Withdraw Status: $status"];
            }else {
                $res = [];
            }
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Toggle Withdraw: ".$e->getMessage()];
        }
        return $res;
    }

    public function getCanWithdraw() {
        try {
            $query = "SELECT amount,canWithdraw, updatedAt FROM admin where adminId='HOCKEY98'";

            $stmt = $this->conn->prepare($query);
            
            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e) {
            return ["status"=>0, "status_message"=>"Problem in fetching"];
        }
        return $res;
    }    

    public function getActiveDenominations() {
        try {
            $query = "SELECT activeDenominations FROM admin where adminId='HOCKEY98'";

            $stmt = $this->conn->prepare($query);
            
            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e) {
            return ["status"=>0, "status_message"=>"Problem in fetching"];
        }
        return $res;
    }    

    public function changeActiveDenominations($activeDenominations) {
        try {
            $query = "UPDATE admin SET activeDenominations=:activeDenominations";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":activeDenominations", $activeDenominations);
            
            $stmt->execute();

            if($stmt->rowCount()) {
                $res = ["status"=>1, "status_message"=>"Active Denominations: $activeDenominations"];
            }else {
                $res = [];
            }
        }catch(Exception $e) {
            return ["status"=>0, "status_message"=>"Problem in fetching"];
        }
        return $res;
    }    

}


?>