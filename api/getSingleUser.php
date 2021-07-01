<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['userId']) && isset($_GET['action']) && $_GET['action'] === 'getSingleUser') {
            require './../model/_const.php';
            require './../model/User.php';
            $user = new UserInfo();
            $result = $user->getUserInfo($_GET['userId']);
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($result);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>