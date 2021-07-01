<?php
session_start();
    header("Content-Type: application/json");
    try {
        //
        if(  isset($_SESSION['poId'], $_POST['action']) && $_POST['action'] === 'assignCourier') {
            require './../model/_const.php';
            require './../model/Transaction.php';
            $info = new TransactionInfo();

            $txnId = htmlspecialchars($_POST['txnId']);
            $service = htmlspecialchars($_POST['service']);
            $lrno = htmlspecialchars($_POST['lrno']);
            $res = $info->assignCourier($txnId, $service, $lrno);
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>