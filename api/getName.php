<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if((isset($_SESSION['userId']) || isset($_SESSION['adminId'])) && isset($_POST['action']) && $_POST['action'] === 'getName') {
            require './../model/_const.php';
            require './../model/User.php';
            $user = new UserInfo();
            $result = $user->getUserInfo(htmlspecialchars($_POST['userId']));
            if(empty($result)) 
                throw new Exception("Invalid Activate User ID");
            else
                $res = array("status"=>1, "status_message"=>"Success", "name"=>$result['firstName']);
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>