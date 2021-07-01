<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['userId']) && isset($_GET['action']) && $_GET['action'] === 'getEpins') {
            require './../model/Epin.php';
            $epin = new EpinInfo();
            $res = $epin->getEpins($_SESSION['userId'], 0);
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>