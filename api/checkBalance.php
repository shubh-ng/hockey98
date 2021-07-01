<?php
    session_start();
    header("Content-Type: application/json");
    try {
        if(isset($_SESSION['userId'], $_POST['amount'])) {
            require './../model/Stats.php';
            $stats = new StatsInfo();
            $res = $stats->checkBalance($_POST['amount']);

            if($res == true) {
                $res = ["status"=>1, "status_message"=>"Amount validated"];
            }else {
                throw new Exception("Invalid amount, Exactly Rs. 500 Withdrawal is allowed per day.");
            }
        }else {
            throw new Exception("Invalid Request, Please Login");
        }
        echo json_encode($res);
    }catch(Exception $e) {
        echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
    }
?>