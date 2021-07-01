<?php
require_once('_database.php');

class TransactionInfo {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function getAllTransactions($userId) {
        try {
            $query = "SELECT * FROM pretransaction WHERE userId = :userId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }

    public function getOne($txnId) {
        try {
            $query = "SELECT CONCAT(u.firstName, ' ', u.lastName) as name, u.mobile, pt.product, CONCAT(pt.locality,', ',pt.landmark, ', ', pt.city, ', ', pt.state, ', ', pt.country, ', ', pt.zipcode) as address FROM pretransaction pt INNER JOIN user u ON u.userId = pt.userId WHERE pt.txnId = :txnId AND pt.status = 'SUCCESS';";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":txnId", $txnId);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transaction fetched" ,"data"=>$result[0]];
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;        
    }

    public function getOneTxn($txnId) {
        try {
            $query = "SELECT * FROM pretransaction WHERE txnId = :txnId";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(":txnId", $txnId);
            
            $stmt->execute();
            
            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transaction fetched" ,"data"=>$result[0]];
            
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;        
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM pretransaction;";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }    

    public function getAllSuccess() {
        try {
            $query = "SELECT  pt.txnId, CONCAT(u.firstName, ' ', u.lastName) as name, u.userId, u.mobile, pt.status, pt.quantity, pt.product, CONCAT(pt.locality,', ',pt.landmark, ', ', pt.city, ', ', pt.state, ', ', pt.country, ', ', pt.zipcode) as address, pt.courier, pt.lrno FROM pretransaction pt INNER JOIN user u ON u.userId = pt.userId WHERE pt.status = 'SUCCESS' AND (pt.amount % 148) = 0;";
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }      


    public function getTransactionsByType($userId, $type) {
        try {
            $query = "SELECT * FROM pretransaction WHERE userId = :userId AND status=:type";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":type", $type);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in fetching transaction by type: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }   

    public function createPretransaction() {
        try {
            $query = "INSERT INTO pretransaction (txnId, userId, amount, product, quantity, locality, landmark, city, state, zipcode, country) VALUES (:txnId, :userId, :amount, :product, :quantity, :locality, :landmark, :city, :state, :zipcode, :country)";

            $stmt = $this->conn->prepare($query);

            $freePinAt = 25;
            $txnId = $_POST['ORDER_ID'];
            $userId = $_POST['udf1'];
            $amount = $_POST['AMOUNT'];
            $quantity = $_POST['udf3'];
            $quantity += floor($_POST['udf3']/$freePinAt);

            $stmt->bindParam(":txnId", $txnId);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":product", $_POST['productinfo']);
            $stmt->bindParam(":quantity", $quantity);

            $stmt->bindParam(":locality", $_POST['locality']);
            $stmt->bindParam(":landmark", $_POST['landmark']);
            $stmt->bindParam(":city", $_POST['city']);
            $stmt->bindParam(":state", $_POST['city']);
            $stmt->bindParam(":zipcode", $_POST['zipcode']);
            $stmt->bindParam(":country", $_POST['country']);

            $stmt->execute();

            $res = ["status"=>1, "status_message"=>"Transactions processed"];
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in processing transaction by type: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }

    public function assignCourier($txnId, $service, $lrno) {
        try {
            $query = "UPDATE pretransaction SET courier = :service, lrno = :lrno WHERE txnId = :txnId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":txnId", $txnId);
            $stmt->bindParam(":service", $service);
            $stmt->bindParam(":lrno", $lrno);

            $stmt->execute();

            if($stmt->rowCount()) {
                $res = ["status"=>1, "status_message"=>"Courier details saved"];
            }else {
                $res = ["status"=>0, "status_message"=>"No changes done OR Invalid transaction ID"];
            }
        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in saving details: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }
}


?>