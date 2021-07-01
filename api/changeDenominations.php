<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['adminId'], $_POST['denominations'])) {
            require './../model/Withdraw.php';
            $withdraw = new WithdrawInfo();
            $res = $withdraw->changeActiveDenominations(htmlspecialchars($_POST['denominations']));
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>