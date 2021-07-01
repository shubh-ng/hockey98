<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['adminId']) && isset($_POST['action']) && $_POST['action'] === 'deleteUser') {
            require './../model/_const.php';
            require './../model/User.php';
            $user = new UserInfo();
            $userId = htmlspecialchars($_POST['userId']);
            $res = $user->deleteUser($userId);
        }else{
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>