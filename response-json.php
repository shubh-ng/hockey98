<?php
    session_start();
    
    if(isset($_SESSION['txnId']) && $_SESSION['txnId'] == $_POST['ORDER_ID']) { // revisiting
        echo "<script>location.href=\"dashboard\" </script>";
        exit();
    }else {
        $_SESSION['txnId'] = $_POST['ORDER_ID'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grow Me Always</title>

    <?php include('includes/css.php') ?>

    <style>
        .container {
            display: flex;
            justify-content: center;
            padding-top: 25px;
        }
        .card {
            min-width: 270px;
            max-width: 400px;
        }
    </style>
</head>
<body>

<div class="container">
<?php
include_once __DIR__.'/bppg_helper.php';
$pg_transaction = new BPPGModule;
$valid = $pg_transaction->validateResponse($_REQUEST);
$response = array('POST' => $_POST, 'GET' => $_GET, 'IS_VALID' => $valid);


    // * MY EPIN CREATION SCRIPT
    require './model/_const.php';    
    require './model/Epin.php';
    require './model/Transaction.php';
    $epinInfo = new EpinInfo();
    
    if($_POST['RESPONSE_CODE'] == "000" && $_POST['STATUS'] == "Captured") {
        try {
            // if($result->data->status != "success") {
                //     throw new Exception("Transaction failed");
                // }
                
            $res = $epinInfo->addEpin($_POST);
            if($res['status'] == 1) {
                ?>

                <div class="card">
                <img
                    src="img/success.jpg"
                    class="card-img-top"
                    alt="Response"
                />
                <div class="card-body"> 
                    <h5 class="card-title text-success">Payment Successfull</h5>
                    <strong class="card-text">
                    Your <?php echo $_POST['udf3']; ?> Product has been added. You will be notified regarding shipping.
                    </strong>

                    <br> 
                    <b>
                        TRANSACTION ID : <?php echo $_POST['ORDER_ID']; ?> <br>
                        AMOUNT : <?php echo $_POST['AMOUNT']/100; ?> <br>
                    </b>
                    <a href="dashboard" class="btn btn-primary">Login</a>
                </div>
                </div>

                <?php
            }else {
                echo "
                    <script>
                        location.href=\"dashboard\";
                    </script>
                ";
            }
            // echo json_encode($res);
        }catch(Exception $e) {
            $r = $epinInfo->updateTransaction($_POST['ORDER_ID'], $_POST['STATUS']?$_POST['STATUS']:"PROBLEM");
            // echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
            ?>
            <div class="card">
                <img
                src="img/Transaction-Failed.png"
                class="card-img-top"
                alt="Response"

            />
            <div class="card-body">
                <h5 class="card-title red-text">Payment <?php echo $_POST['STATUS']; ?></h5>
                <p class="card-text ">
                Problem in processing payment. Note down below transaction id for reporting.
                </p>
                <br>
                    <b>
                        TRANSACTION ID : <?php echo $_POST['ORDER_ID']; ?> <br>
                        AMOUNT : <?php echo $_POST['AMOUNT']/100; ?> <br>
                    </b>
                <a href="dashboard" class="btn btn-primary">Login</a>
            </div>
            </div>
        <?php            
        }
    }else {
        $status = $_POST['STATUS']?$_POST['STATUS']:"PROBLEM";
        $status = strtoupper($status);
        $r = $epinInfo->updateTransaction($_POST['ORDER_ID'], $status);

        ?>
            <div class="card">
                    <img
                src="img/Transaction-Failed.png"
                class="card-img-top"
                alt="Response"

            />
            <div class="card-body">
            <h5 class="card-title red-text">Payment <?php echo $_POST['STATUS']; ?></h5>
                <p class="card-text ">
                Problem in processing payment. Note down below transaction id for reporting.
                </p>
                <br>
                    <b>
                        TRANSACTION ID : <?php echo $_POST['ORDER_ID']; ?> <br>
                        AMOUNT : <?php echo $_POST['AMOUNT']/100; ?> <br>
                    </b>
                <a href="dashboard" class="btn btn-primary">Login</a>
            </div>
            </div>
        <?php
    }
// header('Content-Type: application/json');
// echo json_encode($response, JSON_PRETTY_PRINT);
?>
</div>
</body>
</html>