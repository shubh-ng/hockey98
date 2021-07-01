<?php
require_once('_database.php');

class ProfileInfo {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function getProfile($userId) {
        try {
            $query = "SELECT userId, mobile, firstName, lastName, pan, bankName, branch, accountNumber, ifsc, updatedAt,  FROM user WHERE userId = :userId;";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":userId", $userId);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = $res;
        }catch(Exception $e) {
            $response = array("status"=>0, "status_message"=>"Problem in fetching profile");
        }
        return $response;
    }
}
?>