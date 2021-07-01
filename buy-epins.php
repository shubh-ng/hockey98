<?php
session_start();

// Card Number: 5123456789012346
// EXP: 07/2021
// CVV: 123
// key: DPN6KIX55Z
// Salt: 0ER3A0W7E1

$prodIndex = isset($_GET['product']) ? $_GET['product'] : 2;

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=buy-epins?product=$prodIndex");
  exit();
} 
require "model/_const.php";
require "model/User.php"; 

$userInfo = new UserInfo();
$user = $userInfo->getUserInfo($_SESSION['userId']);
$name = $user['firstName']." ".$user['lastName'];
$email = $user['email'];
$mobile = $user['mobile'];

$transactionId = "HC".rand(100, 100000000)."TXN";
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https:":"http:")."//$_SERVER[HTTP_HOST]";

if(strpos($baseUrl, "localhost") !== false) {
    $baseUrl = $baseUrl."/hockey98";
}
$surl = $baseUrl."/response"; 
$furl = $baseUrl."/response";

// * Get product name 
$productName = PRODUCTS[$prodIndex][0];
$amount = PRODUCTS[$prodIndex][1];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Buy Products</title>

  <?php include('includes/css.php') ?>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container mt-3">


  <!-- Material form register -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Buy ₹ <?php echo $amount; ?> E-Pin Now</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

<input type="hidden" name="amountPerItem" value="<?php echo $amount; ?>">
    <!-- Form -->
    <form class="needs-validation"  method="POST" action="payit.php?payment_check" novalidate style="color: #757575;">

        <div class="form-row">
            <div class="col">
                <!-- No. of E-Pins -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormEPins" name="udf3" class="form-control" autocomplete="off" pattern="[1-9]{1}[0-9]*" required>
                    <label for="materialRegisterFormEPins">No. Of Products</label>
                    <div class="invalid-feedback">
                        Please valid number of Items.
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>
            </div>
            <div class="col">
                <!-- Free E-Pins -->
                <div class="md-form">
                    <input type="number" id="materialRegisterFormFreeEpins" class="form-control" autocomplete="off" readonly>
                    <label for="materialRegisterFormFreeEpins">Free Products</label>
                </div>
            </div>
        </div>

        <!-- Total E-Pins -->
        <div class="md-form mt-0">
            <input type="number" id="materialRegisterFormTotalEPins"  class="form-control" autocomplete="off" readonly>
            <label for="materialRegisterFormTotalEPins">Total Products</label>
        </div>

        <!-- Total Amount -->
        <div class="md-form">
            <input type="number" id="materialRegisterFormAmount" class="form-control" name="AMOUNT" readonly >
            <label for="materialRegisterFormAmount">Total Amount</label>
        </div>

        <!-- Transaction/Order ID -->
        <div class="md-form">
            <input type="text" id="materialRegisterFormOrderId" class="form-control" value="<?php echo $transactionId; ?>" name="ORDER_ID" readonly>
            <label for="materialRegisterFormOrderId">Transaction/Order ID</label>
        </div>

        <div class="form-row">
            <div class="col">
                
            <!-- Select E-Pin Type -->
            <div class="md-form">
            <input type="text" id="materialRegisterFormProductName" value="<?php echo $productName; ?>" class="form-control" autocomplete="off" name="PRODUCT_DESC" required readonly>
                <label for="materialRegisterFormProductName">Product Name</label>
                    <div class="invalid-feedback">
                        Please select valid Product.
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>

            </div>
            <div class="col">
                <!-- Name -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormName" value="<?php echo $name; ?>" class="form-control" autocomplete="off" required name="CUST_NAME">
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
                <input type="email" class="form-control" name="CUST_EMAIL" id="materialRegisterFormEmail" value="<?php echo $email; ?>" autocomplete="off" required>
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



        <div class="form-row">
           
            <div class="col">
                <!-- Locality -->
                <div class="md-form">
                    <input type="text" id="materialRegisterLocality" name="locality" class="form-control" required autocomplete="off">
                    <label for="materialRegisterLocality">Locality</label>
                    <div class="invalid-feedback">
                        Please enter valid Locality
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>
            </div>
            <div class="col">
                <!-- Landmark -->
                <div class="md-form">
                    <input type="text" id="materialRegisterLandmark" name="landmark" class="form-control" required autocomplete="off">
                    <label for="materialRegisterLandmark">Landmark</label>
                    <div class="invalid-feedback">
                        Please enter valid Landmark
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>
            </div>
            <div class="col">
                
                <!-- City Info -->
                <div class="md-form">
                    <input type="text" class="form-control" name="city" id="materialRegisterFormCity" required autocomplete="off" >
                    <label for="materialRegisterFormCity">City</label>
                    <div class="invalid-feedback">
                            Please enter valid City
                        </div>
                        <div class="valid-feedback">
                            Looks Good
                        </div>
                </div>
                </div>
        </div>



        <div class="form-row">
           
            <div class="col">
                <!-- Pin Code -->
                <div class="md-form">
                    <input type="text" id="materialRegisterpincode" name="zipcode" required pattern="[0-9]{6}" class="form-control" autocomplete="off">
                    <label for="materialRegisterpincode">Pincode</label>
                    <div class="invalid-feedback">
                        Please enter valid Pincode
                    </div>
                    <div class="valid-feedback">
                        Looks Good
                    </div>
                </div>
            </div>
            <div class="col">
                <!-- State -->
                <div class="md-form">
                    <input type="text" id="materialRegisterState" name="state" required class="form-control" autocomplete="off">
                    <label for="materialRegisterState">State</label>
                    <div class="invalid-feedback">
                        Please enter valid State
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
        <input type="hidden" name="country" value="India" >
        <input type="hidden" name="productinfo" value="<?php echo $productName; ?>" >
 
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

    var amountPerPin = document.querySelector("[name=amountPerItem]").value;
    var extrapinOn = 25;
    $("#materialRegisterFormEPins").on('keyup', function() {
        value = parseInt($(this).val());
        freeEpins = Math.floor(value/extrapinOn);
        total = value + freeEpins;
        amount = parseFloat(amountPerPin * value).toFixed(2);
        $("#materialRegisterFormFreeEpins").val(freeEpins);
        $("#materialRegisterFormFreeEpins + label").addClass("active");

        $("#materialRegisterFormTotalEPins").val(total);
        $("#materialRegisterFormTotalEPins + label").addClass("active");        

        $("#materialRegisterFormAmount").val(amount);
        $("#materialRegisterFormAmount + label").addClass("active");        
    })

    
  </script>

</body>
</html>
