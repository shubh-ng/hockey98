<?php
    header("Content-Type: application/json");
    try {
        if(isset($_POST['action']) && $_POST['action'] === 'auth') {
            require './../model/_const.php';
            require './../model/Auth.php';
            $auth = new AuthInfo();
            $res = $auth->login();
        }else if($_POST['action'] === 'adminAuth') {
            require './../model/Auth.php';
            $auth = new AuthInfo();
            $res = $auth->adminLogin();
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>