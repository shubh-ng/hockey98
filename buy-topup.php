<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=buy-topup");
  exit();
} 
require "model/_const.php";
require "model/User.php";

$userInfo = new UserInfo();
$user = $userInfo->getUserInfo($_SESSION['userId']);
$name = $user['firstName']." ".$user['lastName'];
$email = $user['email'];
$mobile = $user['mobile'];

$transactionId = "HC".rand(100, 100000000)."TXNT";
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https:":"http:")."//$_SERVER[HTTP_HOST]";

if(strpos($baseUrl, "localhost") !== false) {
    $baseUrl = $baseUrl."/hockey98";
}

$surl = $baseUrl."/responseTopup"; 
$furl = $baseUrl."/responseTopup";


require "model/Epin.php";
$epinInfo = new EpinInfo();
$topups = $epinInfo->getValidTopups($_SESSION['userId']);


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Buy Top Up</title>

  <?php include('includes/css.php') ?>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container mt-3">
  <!-- Material form register -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Pay Online & Get Top Up</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="needs-validation" action="payit.php?payment_check" novalidate style="color: #757575;" method="POST" >
        <!-- No of Top Up Pins -->
        <div class="md-form">
            <input type="number" name="udf3" id="materialRegisterFormTopup" class="form-control" value="1" readonly >
            <label for="materialRegisterFormTopup" >Top Ups</label>
        </div>
        
        <!-- Select E-Pin Type -->
        <div class="md-form">
                    <select class="mdb-select" name="udf2" required>
                        <option value="" disabled selected>Choose Top Up Type</option>
                        <?php foreach($topups as $type) { ?>
                        <option value="<?php echo $type ?>">₹ <?php echo $type ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        Please select valid Top Up Type.
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>

        <!-- Transaction/Order ID -->
        <div class="md-form">
            <input type="text" id="materialRegisterFormOrderId" class="form-control" value="<?php echo $transactionId; ?>" name="ORDER_ID" readonly>
            <label for="materialRegisterFormOrderId">Transaction/Order ID</label>
        </div>

        <div class="form-row">
            <div class="col">
                <!-- Total Amount -->
        <div class="md-form">
            <input type="number" id="materialRegisterFormAmount" class="form-control" name="AMOUNT" readonly >
            <label for="materialRegisterFormAmount">Total Amount</label>
        </div>
            

            </div>
            <div class="col">
                <!-- Name -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormName" value="<?php echo $name; ?>" class="form-control" autocomplete="off" name="firstname" required>
                <label for="materialRegisterFormName">Name</label>
                    <div class="invalid-feedback">
                        Please Enter Valid Name.
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                
            <!-- Product Info -->
            <div class="md-form">
                <input type="email" class="form-control" name="CUST_EMAIL" id="materialRegisterFormEmail" value="<?php echo $email; ?>"  autocomplete="off" required>
                <label for="materialRegisterFormEmail">Email</label>
                <div class="invalid-feedback">
                        Please enter valid E-mail.
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
            </div>
            </div>
            <div class="col">
                <!-- Name -->
                <div class="md-form">
                    <input type="text" id="materialRegisterMobile" name="CUST_PHONE" class="form-control" value="<?php echo $mobile; ?>" autocomplete="off">
                    <label for="materialRegisterMobile">Mobile</label>
                    <div class="invalid-feedback">
                        Please enter valid Mobile number.
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>
            </div>
        </div>

        <!-- other info for payment -->
        <input type="hidden" name="surl" value='<?php echo $surl; ?>' />
        <input type="hidden" name="furl" value='<?php echo $furl; ?>' >
        <input type="hidden" name="udf1" value="<?php echo $_SESSION['userId']; ?>" >
        <input type="hidden" name="address1" value="aaaaa" >
        <input type="hidden" name="address2" value="aaaaa" >
        <input type="hidden" name="city" value="aaaaa" >
        <input type="hidden" name="country" value="India" >
        <input type="hidden" name="zipcode" value="453001" >
        <input type="hidden" name="productinfo" value="topup" >
 

        <!-- Sign up button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Pay Now</button>

        <hr>

        <!-- Terms of service -->
        <p>By clicking
            <em>Pay now</em> you agree to our
            <a href="" target="_blank">terms of service</a>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form register -->

</div>
  <?php include('includes/js.php') ?>
  

  <script>
    mdbValidation();

    var amountPerPin = 98;
    $("#materialRegisterFormEPins").on('keyup', function() {
        value = parseInt($(this).val());
        freeEpins = Math.floor(value/10);
        total = value + freeEpins;
        amount = parseFloat(amountPerPin * value).toFixed(2);

        $("#materialRegisterFormFreeEpins").val(freeEpins);
        $("#materialRegisterFormFreeEpins + label").addClass("active");

        $("#materialRegisterFormTotalEPins").val(total);
        $("#materialRegisterFormTotalEPins + label").addClass("active");        

        $("#materialRegisterFormAmount").val(amount);
        $("#materialRegisterFormAmount + label").addClass("active");        
    })

    totalAmount = $("#materialRegisterFormAmount");
    lbl = $("#materialRegisterFormAmount + label");
    $("select").on('change',function(e) {
        optionSelected = $("option:selected", this);
        valueSelected = parseFloat(this.value).toFixed(2);

        totalAmount.val(valueSelected);
        lbl.addClass("active");
    })
  </script>

</body>
</html>
