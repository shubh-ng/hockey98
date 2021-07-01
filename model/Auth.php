<?php
session_start();
require_once('_database.php');

class AuthInfo {
    var $conn;

    public function __construct() {
        $db = new Database;
        $this->conn = $db->getConnection();
    }

    public function login() {
        try {
            $query = "SELECT * FROM user WHERE userId = :userId AND password = :password;";

            $stmt = $this->conn->prepare($query);

            $userId = htmlspecialchars($_POST['userId']);
            $password = htmlspecialchars($_POST['password']);

            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($res)) {
                throw new Exception("Invalid Username or Password");
            }
            $_SESSION['userId'] = $res['userId'];
            $_SESSION['fname'] = $res['firstName'];
            $_SESSION['lname'] = $res['lastName'];
            $response=array("status"=>1,"status_message"=>"User Logged In!", "data"=> array("userId"=>$userId));
        }catch(PDOException $e) {
            $response=array("status"=>0,"status_message"=>$e->getMessage());
        }catch(Exception $e) {
            $response=array("status"=>2,"status_message"=>$e->getMessage());
        }
        return $response;
    }

        public function adminLogin() {
        try {
            $query = "SELECT * FROM admin WHERE adminId = :adminId AND password = :password;";

            $stmt = $this->conn->prepare($query);

            $adminId = htmlspecialchars($_POST['adminId']);
            $password = htmlspecialchars($_POST['password']);

            $stmt->bindParam(':adminId', $adminId);
            $stmt->bindParam(':password', $password);

            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);

            if(empty($res)) {
                throw new Exception("Invalid Username or Password");
            }

            if($res['adminId'] != "HOCKEY98") {
                $_SESSION['poId'] = $res['adminId'];
                $_SESSION['firstName'] = $res['firstName'];
                $_SESSION['lastName'] = $res['lastName'];
            }else {
                $_SESSION['adminId'] = $res['adminId'];
                $_SESSION['firstName'] = $res['firstName'];
                $_SESSION['lastName'] = $res['lastName'];
            }
            $response=array("status"=>1,"status_message"=>"Admin Logged In!", "data"=> array("adminId"=>$res['adminId']));
        }catch(PDOException $e) {
            $response=array("status"=>0,"status_message"=>$e->getMessage());
        }catch(Exception $e) {
            $response=array("status"=>2,"status_message"=>$e->getMessage());
        }
        return $response;
    }

    public function logout() {
        try {
            session_start();
            session_destroy();
            $response = array("status"=>1, "status_message"=>"Logout success");
        }catch(Exception $e) {
            $response = array("status"=>0, "status_message"=>"Problem in logout");
        }
        return $response;
    }
}
?>