<?php
require_once('_database.php');

class EpinInfo {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function addEpin($responseData) {
        try {$response = [];
            $this->conn->beginTransaction();

            $txnId = $responseData['ORDER_ID'];
            $transaction = new TransactionInfo();

            $data = $transaction->getOneTxn($txnId)['data'];
            
            
            $freePinAt = 25;
            $ownerId = $data['userId'];
            $noOfEpins = $data['quantity'];
            $amountPerEpin = $data['amount'] / $data['quantity'];
            $noOfEpins += floor($data['quantity']/$freePinAt);
            $cost = $amountPerEpin;

            $status = 1;
            $data = [];
            $totalCost = 0;
            for($i=0; $i<$noOfEpins; $i++) {
                $query = "INSERT INTO epin (epinId, ownerId, cost) VALUES (:epinId, :ownerId, :cost);";
                $epinId = "EP".$this->getEpinCount().rand(1, 100000);

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(":epinId", $epinId);
                $stmt->bindParam(":ownerId", $ownerId);
                $stmt->bindParam(":cost", $cost);

                $result = $stmt->execute();

                if($result) { 
                    array_push($data, $epinId);
                    $totalCost += $cost;
                }else {
                    throw new Exception("Error in creating E-Pin");
                }
            }

            $r = $this->updateTransaction($txnId, "SUCCESS");
            if($r) {
                $this->conn->commit();
                $response = ["status"=>1, "status_message"=>"Done"];
            }else {
                $this->conn->rollBack();
            }
            // $response = $this->initiateTransaction($ownerId, $txnId, $totalCost, $status, "PAY", $data);
            // if($response['status'] == 1) {
            //     $this->updateTransaction($txnId, "SUCCESS");
            // }
        }catch(Exception $e) {
            $this->conn->rollBack();
            $response=["status"=>0,"status_message"=>$e->getMessage()];
        }
        return $response;
    }

    public function transferEpins($userId) {
        try {

            $this->conn->beginTransaction();

            $toId = htmlspecialchars($_POST['toId']);
            $noOfEpins = htmlspecialchars($_POST['noOfEpins']);
            $epinType = htmlspecialchars($_POST['epinType']);

            if($userId == $toId) {
                throw new Exception("You can not transfer pin to yourself.");
            }

            $epins = $this->getParticularEpins($userId, $epinType);


            if(count($epins) < $noOfEpins) {
                return ["status"=>0, "status_message"=>"Insufficient Epins"];
            }

            // SELECT AVAILABLE EPINS FOR GENERATING REPORT
            $selectEpins = "SELECT * FROM epin WHERE ownerId = :userId AND status = 0 AND cost = :epinType ORDER BY id LIMIT :noOfEpins;";

            $stmt = $this->conn->prepare($selectEpins);

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":epinType", $epinType);      
            $stmt->bindValue(":noOfEpins", (int)$noOfEpins, PDO::PARAM_INT);      

            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // UPDATE ALL EPINS THAT ARE BEING TRANSFERED
            $query = "UPDATE epin SET ownerId = :toId WHERE ownerId = :userId AND status = 0 AND cost = :epinType ORDER BY id LIMIT :noOfEpins;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":toId", $toId);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":epinType", $epinType);
            $stmt->bindValue(":noOfEpins", (int)$noOfEpins, PDO::PARAM_INT);

            $stmt->execute();

            if($stmt->rowCount()) {
                // GENERATE EPIN REPORTS
                foreach($result as $r) {
                    $found = $this->generateEpinReport($r['epinId'], $userId, $toId, 0);
                    if(!$found) {
                        throw new Exception("Problem in transfering E-Pin");
                    }
                }
                $this->conn->commit();
                $res = ["status"=>1, "status_message"=>"E-Pin transferred Successfully"];
            }else {
                throw new Exception("Problem in transfering E-Pin");
            }
        }catch(Exception $e) {
            $code = $e->getCode();
            if($code == 23000) {
               $res = ["status"=>0, "status_message"=>"Please enter valid User ID"];
            }else {
                $res = ["status"=>0, "status_message"=>$e->getMessage()];
            }
            $this->conn->rollBack();
        }
        return $res;
    }

    public function getParticularEpins($ownerId, $epinType) {
        try {
            $query = "SELECT * FROM epin WHERE ownerId = :ownerId AND cost = :epinType;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":ownerId", $ownerId);
            $stmt->bindParam(":epinType", $epinType);

            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        }catch(Exception $e) {
            echo "getParticularEpins: ".$e->getMessage();
        }
        return $res;
    }

    public function transferEpin($epinId, $fromId, $toId, $status) {
        try {
            if(!$this->verifyEpin($epinId, $fromId)) {
                throw new Exception("Sorry! Selected E-Pin is used!");
            }

            $query = "UPDATE epin SET ownerId = :toId, status = :status WHERE epinId = :epinId AND ownerId = :fromId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":toId", $toId);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":epinId", $epinId);
            $stmt->bindParam(":fromId", $fromId);

            $result = $stmt->execute();

            if($result && $this->generateEpinReport($epinId, $fromId, $toId, $status)) {
                // If ID is activating along with transfering epin
                if($status == 1) {
                    // Update no of downlines count
                    $statsInfo = new StatsInfo();
                    $statsInfo->updateNoOfDownlines($toId);
                    // Process after activating id
                    $statsInfo->processAfterActivatingID($toId);
                }
                $response = array("status"=>1, "status_message"=>"Pin transfered", "data"=> array("epinId"=>$epinId, "fromId"=>$fromId, "toId"=>$toId, "userId"=>$toId));
            }else {
                throw new Exception("Error in transfering E-Pin");
            }
        }catch(PDOException $e) {
            echo "transferEpin: ".$e->getMessage();
        }catch(Exception $e) {
            $response = array("status"=>0, "status_message"=>$e->getMessage());
        }
        return $response;
    }

    public function generateEpinReport($epinId, $fromId, $toId, $status) {
        try {
            $query = "INSERT INTO epinReport (epinId, fromId, toId, status) VALUES (:epinId, :fromId, :toId, :status);";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":epinId", $epinId);
            $stmt->bindParam(":fromId", $fromId);
            $stmt->bindParam(":toId", $toId);
            $stmt->bindParam(":status", $status);

            $result = $stmt->execute();

            if($result) {
                return 1;
            }else {
                throw new Exception("Error in generating report.");
            }
        }catch(PDOException $e) {
            echo "PDO Error: ".$e->getMessage();
            return 0;
        }catch(Exception $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    public function getEpinCount() {
        try {
            $query = "SELECT count(*) as count FROM epin";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['count'];
        }catch(PDOException $e) {
            echo "Error: ".$e->getMessage();
            return 0;
        }catch(Exception $e) {
            return 0;
        }
    }    

    public function getTodaysEpinCount() {
        try {
            $query = "SELECT count(*) as count FROM epin WHERE date(createdAt) = CURDATE();";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            return $res['count'];
        }catch(PDOException $e) {
            echo "getTodaysEpinCount: ".$e->getMessage();
            return 0;
        }catch(Exception $e) {
            return 0;
        }
    }    

    public function verifyEpin($epin, $epinOwner) {
        try {
            $query = "SELECT id FROM epin WHERE epinId = :epin AND ownerId = :epinOwner AND status = 0";

            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":epin", $epin);
            $stmt->bindParam(":epinOwner", $epinOwner);

            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if(empty($res)) throw new Exception();
            return 1;
        }catch(PDOException $e) {
            echo "Error: ".$e->getMessage();
            return 0;
        }catch(Exception $e) {
            return 0;
        }
    } 

    public function getEpins($ownerId, $status) {
        try {
            $query = "SELECT id, epinId FROM epin WHERE ownerId = :ownerId AND status = :status AND cost < 200";

            $stmt = $this->conn->prepare($query);
            
            // $cost = 98;
            // if(isset($_GET['cost']))
            //     $cost = htmlspecialchars($_GET['cost']);

            $stmt->bindParam(":ownerId", $ownerId);
            $stmt->bindParam(":status", $status);
            //$stmt->bindParam(":cost", $cost);

            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($res)) throw new Exception();
            return array("status"=>1, "status_message"=>"E-Pin Available", "data" => $res);
        }catch(PDOException $e) {
            echo "Error: ".$e->getMessage();
            return array("status"=>0, "status_message"=>"Problem in fetching E-Pin");
        }catch(Exception $e) {
            return array("status"=>0, "status_message"=>"No E-Pins available");
        }        
    }   

    public function getAllEpins($ownerId) {
        try {
            $query = "SELECT * FROM epin WHERE ownerId = :ownerId;";

            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":ownerId", $ownerId);

            $stmt->execute();
            $res1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $query = "SELECT * FROM epinReport WHERE fromId = :ownerId;";

            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":ownerId", $ownerId);

            $stmt->execute();
            $res2 = $stmt->fetchAll(PDO::FETCH_ASSOC);      

            $res = [$res1, $res2];

            if(empty($res)) throw new Exception();
            return ["status"=>1, "status_message"=>"E-Pin Available", "data" => $res];
        }catch(PDOException $e) {
            echo "getAllEpins: ".$e->getMessage();
            return ["status"=>0, "status_message"=>"Problem in fetching E-Pin"];
        }catch(Exception $e) {
            return ["status"=>0, "status_message"=>"No E-Pins available"];
        }        
    }      

    public function getValidTopups($userId) {
        try {
            $query = "SELECT topupNeededLevel FROM stats WHERE userId = :userId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':userId', $userId);
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $level = $result['topupNeededLevel'];
            $data = [];
            $i=1;
            foreach(TOPUP_TYPES as $tt) {
                if($i > $level) break;
                if($tt != 0)
                array_push($data, $tt);
                $i++;
            }
            $res = $data;
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"getValidTopups: ".$e->getMessage()];
        }
        return $res;
    }

    public function getTopups($ownerId, $status) {
        try {
            $query = "SELECT id, epinId, cost FROM epin WHERE ownerId = :ownerId AND status = :status AND cost != 98;";

            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":ownerId", $ownerId);
            $stmt->bindParam(":status", $status);
            
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(empty($res)) throw new Exception();
            return ["status"=>1, "status_message"=>"Top up Available", "data" => $res];
        }catch(PDOException $e) {
            echo "Error: ".$e->getMessage();
            return ["status"=>0, "status_message"=>"Problem in fetching Top up"];
        }catch(Exception $e) {
            return ["status"=>0, "status_message"=>"No Top up available"];
        }
    }

    public function getEpinById($epinId) {
        try {
            $query="SELECT * FROM epin WHERE epinId=:epinId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":epinId", $epinId);

            $stmt->execute();

            $res = $stmt->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e) {
            echo "getEpinById:".$e->getMessage();
            $res = [];
        }
        return $res;
    }

    public function initiateTransaction($userId, $txnId, $amount, $status, $type, $data) {
        try {
            $query = "INSERT INTO txn_detail (userId, txnId, txnType, amount, status) VALUES (:userId, :txnId, :type, :amount, :status);";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":txnId", $txnId);
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":status", $status);
    

            $stmt->execute();
            $res = ["status"=>1, "status_message"=>"Transaction Successfull", "results" => ["userId"=>$userId, "txnId"=>$txnId,"amount" => $amount, "status" => $status], "data"=>$data];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in transaction: ".$e->getMessage()];
        }
        return $res;
    }
    
    public function createAndTransfer($ownerId, $cost) {
        try {
            $query = "INSERT INTO epin (epinId, ownerId, cost, createdBy) VALUES (:epinId, UPPER(:ownerId), :cost, 'ADMIN');";

            $stmt = $this->conn->prepare($query);

            $epinId = "EP".$this->getEpinCount().rand(1, 100000);

            $stmt->bindParam(":epinId", $epinId);
            $stmt->bindParam(":ownerId", $ownerId);
            $stmt->bindParam(":cost", $cost);
            $stmt->execute();

            $res = ["status"=>1, "status_message"=>"E-Pin $epinId Transfered"];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in transfering E-PIN ID"];
        }
        return $res;
    }
    
    public function getEpinTransfer() {
        try {
            $query="SELECT e.epinId, u.userId, u.firstName, u.lastName, u.mobile, u.status FROM epin e INNER JOIN user u ON u.userId = e.ownerId WHERE createdBy = 'ADMIN';";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $res = $stmt->fetchAll();
            
            $res = ["status"=>1, "status_message"=>"Epin Details fetched", "data"=>$res];
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in fetching E-pin details", "data"=>[]];
        }
        return $res;        
    }

    public function updateTransaction($txnId, $status) {
        try {
            $query = "UPDATE pretransaction SET status = :status WHERE txnId=:txnId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":txnId", $txnId);
            $stmt->execute();

            $res = 1;
        }catch(Exception $e) {
            $res = 0;
        }
        return $res;
    }
}
?>