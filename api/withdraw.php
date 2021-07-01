<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['userId'])) {
            require './../model/_const.php';
            require './../model/Stats.php';
            require './../model/Withdraw.php';
            require './../model/Snapshot.php';
            require './../withdraw_helper.php';

            $withdrawInfo = new WithdrawInfo();

            $res = $withdrawInfo->tryToWithdraw();
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>