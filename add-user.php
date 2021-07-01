<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=add-user");
  exit();
} 

require 'model/Epin.php';
$epinInfo = new EpinInfo();
$epins = $epinInfo->getEpins($_SESSION['userId'], 0);
if($epins['status']) {
    $epins = $epins['data'];
}else {
    $epins = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Add User</title>

  <?php include('includes/css.php') ?>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container mt-3">
  <!-- Material form register -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Add Direct User</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="needs-validation" style="color: #757575;" novalidate id="addDirectUserForm">

        <div class="form-row">
            <div class="col">
                <!-- Pan number -->
                <div class="md-form">
                    <input type="text" id="panCard" class="form-control" autocomplete="off" value="" required name="panNumber">
                    <label for="panCard">Pan Card Number</label>
                </div>
            </div>
            <div class="col">
                <!-- Sponser Id -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormSponserId" class="form-control" autocomplete="off" value="<?php echo $_SESSION['userId']; ?>" readonly required name="parentId">
                    <label for="materialRegisterFormSponserId">Sponser ID</label>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <!-- First Name -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormFirstName" class="form-control" autocomplete="off" required name="fname">
                    <label for="materialRegisterFormFirstName">First Name*</label>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please provide first name.
                    </div>
                </div>
            </div>
            <div class="col">
                <!-- Last Name -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormLastName" class="form-control" autocomplete="off" required name="lname">
                    <label for="materialRegisterFormLastName">Last Name*</label>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please provide last name.
                    </div>
                </div>
            </div>
        </div>  

        <!-- Mobile -->
        <div class="md-form mt-0">
            <input type="text" id="materialRegisterFormMobile" class="form-control" autocomplete="off" pattern="[6-9]{1}[0-9]{9}" required name="mobile">
            <label for="materialRegisterFormMobile">Mobile No.*</label>
            <div class="valid-feedback">
                Looks good!
            </div>
            <div class="invalid-feedback">
                Please provide valid mobile number.
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <!-- Password -->
                <div class="md-form">
                    <input type="password" id="materialRegisterFormPassword" class="form-control" autocomplete="off" pattern=".{8,}" name="password" required>
                    <label for="materialRegisterFormPassword">Password (At least 8 character)*</label>
                </div>
                <div class="valid-feedback">
                        Looks good!
                </div>
                <div class="invalid-feedback">
                    Password should be 8 or more characters
                </div>
            </div>
            <div class="col">
                <!-- Password -->
                <div class="md-form">
                    <input type="password" id="materialRegisterFormConfirmPassword" class="form-control" autocomplete="off" required>
                    <label for="materialRegisterFormConfirmPassword">Confirm Password*</label>
                </div>
            </div>
        </div>      

        <!-- Sign up button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Add User</button>

        <hr>

        <!-- Terms of service -->
        <p>By clicking
            <em>Add User</em> you agree to our
            <a href="" target="_blank">terms of service</a>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form register -->

</div>
  <?php include('includes/js.php') ?>
    <script>
    (function() {
        'use strict';
        window.addEventListener('load', mdbValidation, false);
    })();
    </script>
</body>
</html>
