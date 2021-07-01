<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['userId']) && isset($_POST['action']) && $_POST['action'] === 'getUserInfo') {
            require './../model/_const.php';
            require './../model/User.php';
            $user = new UserInfo();
            $result = $user->getUserInfo($_SESSION['userId']);
            if(empty($result)) 
                throw new Exception("Invalid User ID");
            else
                $res = array("status"=>1, "status_message"=>"Success", "data"=>$result);
        }else if(isset($_SESSION['adminId'])) {
            require './../model/_const.php';
            require './../model/User.php';
            $user = new UserInfo();
            $result = $user->getUserInfo($_POST['userId']);
            if(empty($result)) 
                throw new Exception("Invalid User ID");
            else
                $res = array("status"=>1, "status_message"=>"Success", "data"=>$result);
        }else{
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>