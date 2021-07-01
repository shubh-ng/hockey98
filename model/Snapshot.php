<?php
require_once('_database.php');

class SnapshotInfo {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function getAllSnapshots($userId) {
        try {
            $query = "SELECT * FROM _snapshot WHERE userId = :userId";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }

    public function getOne($urn) {
        try {
            $query = "SELECT * FROM _snapshot WHERE urn = :urn";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":urn", $urn);

            $stmt->execute();

            $result = $stmt->fetch(FETCH_ASSOC);

            $res = ["status"=>1, "status_message"=>"Transaction fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;        
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM _snapshot;";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }    
    
    public function getAllPending() {
        try {
            $query = "SELECT * FROM _snapshot WHERE status NOT IN ('SUCCESS', 'FAILURE', 'REJECTED');";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }       

    public function getAllSuccess($userId) {
        try {
            $query = "SELECT * FROM _snapshot WHERE userId = :userId AND status = 'DONE';";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $res = ["status"=>1, "status_message"=>"Transactions fetched" ,"data"=>$result];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in fetching transaction: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }      



    public function createSnapshot($urn, $userId, $amount) {
        try {
            $query = "INSERT INTO _snapshot (urn, userId, amount) VALUES (:urn, :userId, :amount)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":urn", $urn);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":amount", $amount);

            $stmt->execute();

            $res = ["status"=>1, "status_message"=>"Transactions processed"];
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in withdrawal. Please try after some time "];
        }
        return $res;
    }

    public function updateStatus($urn, $status) {
        try {
            $query = "UPDATE _snapshot SET status = :status WHERE urn = :urn";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":urn", $urn);
            $stmt->bindParam(":status", $status);
            $stmt->execute();

            if($stmt->rowCount()) {
                $res = ["status"=>1, "status_message"=>"Transaction status updated"];
            }else {
                $res = ["status"=>0, "status_message"=>"No changes done OR Invalid URN no."];
            }
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in saving details: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }

    public function updateJson($urn, $data) {
        try {
            $query = "UPDATE _snapshot SET jsonResponse = :data WHERE urn = :urn";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":urn", $urn);
            $stmt->bindParam(":data", $data);
            $stmt->execute();

            if($stmt->rowCount()) {
                $res = ["status"=>1, "status_message"=>"Response captured"];
            }else {
                $res = ["status"=>0, "status_message"=>"No changes done OR Invalid URN no."];
            }
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in saving details: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }
    

    public function updateStatusAndJson($urn, $status, $json) {
        try {
            $query = "UPDATE _snapshot SET status = :status, jsonResponse = :json WHERE urn = :urn";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":urn", $urn);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":json", $json);
            $stmt->execute();

            if($stmt->rowCount()) {
                $res = ["status"=>1, "status_message"=>"Response captured"];
            }else {
                $res = ["status"=>0, "status_message"=>"No changes done OR Invalid URN no."];
            }
        }catch(Exception $e) {
            $res = ["status"=>0, "status_message"=>"Problem in saving details: ".$e->getMessage() ,"data"=>[]];
        }
        return $res;
    }
    
    public function purge() {
        try {
            $query = "DELETE FROM _snapshot WHERE status = 'FAILURE'";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $res = ["status"=>1, "status_message"=>"Data purged"];

        }catch(Exception $e) {
            $res = ["status"=>1, "status_message"=>"Problem in purging"];
        }
        
        return $res;
    }
}


?>