<?php

require "./../model/Snapshot.php";
require "./../model/_const.php";
require_once('./../model/_database.php');


class WithdrawHelper {
    private $key = "BD250E1D4F";
    private $salt = "6C0B39EA69";
    private $beneficiaryType;
    private $beneficiaryName;
    private $accountNumber;
    private $ifsc;
    private $paymentMode;
    private $uniqueRequestNumber;
    private $amount;

    function __construct()
    { 
    }
    // *SETTERS
    public function setBeneficiaryType($beneficiaryType) {
        $this->beneficiaryType = $beneficiaryType;
    }

    public function setBeneficiaryName($beneficiaryName) {
        $this->beneficiaryName = $beneficiaryName;
    }

    public function setAccountNumber($accountNumber) {
        $this->accountNumber = $accountNumber;
    }

    public function setIfsc($ifsc) {
        $this->ifsc = $ifsc;
    }

    public function setPaymentMode($paymentMode) {
        $this->paymentMode = $paymentMode;
    }

    public function setUniqueRequestNumber($uniqueRequestNumber) {
        $this->uniqueRequestNumber = $uniqueRequestNumber;
    }

    public function setAmount($amount) {
        $this->amount = round($amount, 2);
    }
    // *\ SETTERS

    public function generateHash() {
        $payload = "$this->key"."|"."$this->accountNumber"."|"."$this->ifsc"."||"."$this->uniqueRequestNumber"."|"."$this->amount"."|"."$this->salt";
        return hash("sha512", $payload);
    }

    // * Initiate Quick Transfer 
    public function initiateQuickTransfer() {
        $hash = $this->generateHash();

        $postData = [
            key => $this->key,
            beneficiary_type => $this->beneficiaryType,
            beneficiary_name => $this->beneficiaryName,
            account_number => $this->accountNumber,
            ifsc => $this->ifsc,
            unique_request_number => $this->uniqueRequestNumber,
            payment_mode => $this->paymentMode,
            amount => $this->amount
        ];

        $postData = json_encode($postData);
        $headers = [
            "Authorization: $hash",
            "Content-Type: application/json"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wire.easebuzz.in/api/v1/quick_transfers/initiate/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        header("Content-Type: application/json");
        return json_decode($response);
    }
    
    // Retrieve
    function retrieve($urn) {
        
        $hash = hash('sha512', "$this->key|$urn|$this->salt");        
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://wire.easebuzz.in/api/v1/transfers/$urn/?key=$this->key",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            "Authorization: $hash"
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return json_decode($response);
    }
}


class Withdraw {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
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
    
    public function withdraw() {
        try {

            $urn = rand(10000, 100000000); // unique no
            $amount = htmlspecialchars($_POST['amount']);

            
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
            $result = $snapshot->createSnapshot($urn, "HOCKEY98", $amount);
            
            if($result['status'] == 1) {
                $result = $withdrawHelper->initiateQuickTransfer();
            }
    
            if($result->success) {
                $data = $result->data; // to be stored into db
                $status = $data->transfer_request->status;
                $urn = $data->transfer_request->unique_request_number;
                $msg = $this->takeMsgByStatus($status);
            
                // store json to db
                $result = $snapshot->updateJson(
                    $urn,
                    json_encode($data) 
                );
            
                if($status == "success") { // SUCCESS
                    $res = $this->deductAmount($amount);
                    $res = ["status"=>1, "status_message"=>"Congratulations, Amount Successfully transfered to your account."];
                }else if($status == "rejected" || $status == "failure") {
                    throw new Exception($msg);  
                }else { // some pending status
                    $this->deductAmount($amount);
                    $res = ["status"=>2, "status_message"=>"Amount has been processed. Check your transactions."];
                }
            }else { // failure
                throw new Exception($result->message);
            }            
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>$e->getMessage()];
        }
        return $res;
    }
    
    public function deductAmount($amount) {
        try {
            $query = "UPDATE admin SET amount = amount - :amount WHERE adminId = :adminId;";
            
            $stmt = $this->conn->prepare($query);
            
            $adminId = ADMIN_ID;
            
            $stmt->bindParam(":adminId", $adminId);
            $stmt->bindParam(":amount", $amount);
            
            $stmt->execute();
            
            if($stmt->rowCount()) {
                $res = ["status"=>1, "status_message"=>"Done"];
            }else {
                throw new Exception("Some problem happened");
            }
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>$e->getMessage()];
        }
        return $res;
    }
}

$withdraw = new Withdraw();
$res = $withdraw->withdraw();

header("Content-Type: application/json");
echo json_encode($res);


?>