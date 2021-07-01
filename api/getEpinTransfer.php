<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['adminId'])) {
            require './../model/_const.php';
            require './../model/Epin.php';
            $epin = new EpinInfo();
            $res = $epin->getEpinTransfer();
            if(empty($res)) 
                throw new Exception("Invalid Activate User ID");
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>