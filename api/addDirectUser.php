<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['userId']) && isset($_POST['action']) && $_POST['action'] === 'addUser') {
            require './../model/_const.php';
            require './../model/User.php';
            require './../model/Epin.php';
            $user = new UserInfo();
            $res = $user->addDirectUser();
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>