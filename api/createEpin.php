<?php
session_start();
    header("Content-Type: application/json");
    try {
        //
        if(isset($_SESSION['adminId'])) {
            require './../model/_const.php';
            require './../model/Epin.php';
            $epinInfo = new EpinInfo();

            $ownerId = htmlspecialchars($_POST['ownerId']);
            $cost = htmlspecialchars($_POST['cost']);
            $res = $epinInfo->createAndTransfer($ownerId, $cost);
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>