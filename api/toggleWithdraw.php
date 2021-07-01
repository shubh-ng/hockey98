<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['adminId'], $_POST['status'])) {
            require './../model/Withdraw.php';
            $withdraw = new WithdrawInfo();
            $res = $withdraw->toggleWithdraw($_POST['status']);
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>