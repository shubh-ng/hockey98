<?php
    session_start();

    // include file
    include_once('easebuzz-lib/easebuzz_payment_gateway.php');

    // salt for testing env
    $SALT = "0ER3A0W7E1";

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

    /*
    * Get the API response and verify response is correct or not.
    *
    * params string $easebuzzObj - holds the object of Easebuzz class.
    * params array $_POST - holds the API response array.
    * params string $SALT - holds the merchant salt key.
    * params array $result - holds the API response array after valification of API response.
    *
    * ##Return values
    *
    * - return array $result - hoids API response after varification.
    * 
    * @params string $easebuzzObj - holds the object of Easebuzz class.
    * @params array $_POST - holds the API response array.
    * @params string $SALT - holds the merchant salt key.
    * @params array $result - holds the API response array after valification of API response.
    *
    * @return array $result - hoids API response after varification.
    *
    */
    $easebuzzObj = new Easebuzz($MERCHANT_KEY = null, $SALT, $ENV = null);
    
    $result = $easebuzzObj->easebuzzResponse( $_POST );

    // print_r($result);

    $result = json_decode($result);

    // print_r($result);

    // * MY EPIN CREATION SCRIPT
    if($result->status == 1) {
        try {
            require './model/_const.php';
            require './model/Epin.php';

            if($result->data->status != "success") {
                throw new Exception("Transaction failed");
            }

            $epinInfo = new epinInfo();
            $res = $epinInfo->addEpin($result->data);
            // print_r($res);
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
                        TRANSACTION ID : <?php echo $_POST['txnid']; ?> <br>
                        AMOUNT : <?php echo $_POST['amount']; ?> <br>
                        USER ID : <?php echo $_POST['udf1']; ?> <br>
                        NAME : <?php echo $_POST['firstname']; ?> <br>
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
            // echo json_encode(array("status"=>0, "status_message"=> $e->getMessage()));
            ?>
            <div class="card">
                <img
                src="img/Transaction-Failed.png"
                class="card-img-top"
                alt="Response"

            />
            <div class="card-body">
                <h5 class="card-title red-text">Payment Unsuccessful</h5>
                <p class="card-text ">
                Problem in processing payment. Note down below transaction id for reporting.
                </p>
                <br>
                    <b>
                        TRANSACTION ID : <?php echo $_POST['txnid']; ?> <br>
                        AMOUNT : <?php echo $_POST['amount']; ?> <br>
                        USER ID : <?php echo $_POST['udf1']; ?> <br>
                        NAME : <?php echo $_POST['firstname']; ?> <br>
                    </b>
                <a href="dashboard" class="btn btn-primary">Login</a>
            </div>
            </div>
        <?php            
        }
    }else {
        ?>
            <div class="card">
                    <img
                src="img/Transaction-Failed.png"
                class="card-img-top"
                alt="Response"

            />
            <div class="card-body">
                <h5 class="card-title red-text">Payment Unsuccessful</h5>
                <p class="card-text ">
                Problem in processing payment. Note down below transaction id for reporting.
                </p>
                <br>
                    <b>
                        TRANSACTION ID : <?php echo $_POST['txnid']; ?> <br>
                        AMOUNT : <?php echo $_POST['amount']; ?> <br>
                        USER ID : <?php echo $_POST['udf1']; ?> <br>
                        NAME : <?php echo $_POST['firstname']; ?> <br>
                    </b>
                <a href="dashboard" class="btn btn-primary">Login</a>
            </div>
            </div>
        <?php
    }
?>
</div>
</body>

</html>

