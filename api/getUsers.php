<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['adminId']) && isset($_GET['action']) && $_GET['action'] === 'getUsers') {
            require './../model/_const.php';
            require './../model/User.php';
            $user = new UserInfo();
            $result = $user->getAllUsers();
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($result);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>