<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=profile");
  exit();
} 

require "model/User.php";
$userInfo = new UserInfo();
$userId = $_SESSION['userId'];
$user = $userInfo->getUserInfo($userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Profile</title>

  <?php include('includes/css.php') ?>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container mt-3">
  <!-- Material form Profile -->

  <div class="alert alert-danger alert-dismissible jumbotron danger-color text-white" role="alert" id="user-inactive">
  <h4 class="alert-heading font-weight-bold"><i class="fas fa-info"></i> Important!</h4>
  <p>Bank Details</p>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <hr>
  <p class="mb-0">
    <ul >
      <li>
        KYC Details can not be changed once updated. Contact Admin to update.
      </li>
      <li>
        KYC Details are mandatory to withdraw money from wallet.
      </li>
    </ul>
  </p>
</div>

<div class="card ">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Profile</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="needs-validation" style="color: #757575;" novalidate method="POST" id="updateForm">

        <!-- User ID -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormUserId" class="form-control" autocomplete="off" required readonly value="<?php echo $userId;  ?>">
          <label for="materialRegisterFormUserId">User ID*</label>
        </div>
        <!-- First Name -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormFirstName" class="form-control" autocomplete="off" required name="fname" value="<?php echo $user['firstName']; ?>">
          <label for="materialRegisterFormFirstName">First Name*</label>
          <div class="invalid-feedback">
            Please Enter First Name
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
        <!-- Last Name -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormLastName" class="form-control" autocomplete="off" required name="lname" value="<?php echo $user['lastName']; ?>">
          <label for="materialRegisterFormLastName">Last Name*</label>
          <div class="invalid-feedback">
            Please Enter Last Name
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
        <!-- Mobile No. -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormMobile" class="form-control" autocomplete="off" required required="mobile" value="<?php echo $user['mobile']; ?>" name="mobile" pattern="[6-9]{1}[0-9]{9}">
          <label for="materialRegisterFormMobile">Mobile No*</label>
          <div class="invalid-feedback">
            Please Enter Valid Mobile No
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
        <!-- PAN -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormPan" class="form-control" autocomplete="off" required name="pan" value="<?php echo $user['pan']; ?>" 
          <?php 
            if($user['pan'])
              echo "readonly"
            ?>  />
          <label for="materialRegisterFormPan">Pan*</label>
          <div class="invalid-feedback">
            Please Enter Pan Number
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
        <!-- Bank Name -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormBankName" class="form-control" autocomplete="off" required name="bankName" value="<?php echo $user['bankName']; ?>"
          <?php 
            if($user['bankName'])
              echo "readonly"
          ?> 
          />
          <label for="materialRegisterFormBankName">Bank Name*</label>
          <div class="invalid-feedback">
            Please Enter Bank Name
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
        <!-- Branch -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormBranch" class="form-control" autocomplete="off" required name="branch" value="<?php echo $user['branch']; ?>"
          <?php 
            if($user['branch'])
              echo "readonly"
          ?> 
          />
          <label for="materialRegisterFormBranch">Branch*</label>
          <div class="invalid-feedback">
            Please Enter Branch Name
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
        <!-- Account No. -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormAccount" class="form-control" autocomplete="off" name="accountNumber" value="<?php echo $user['accountNumber']; ?>" required
          <?php 
            if($user['accountNumber'])
              echo "readonly"
          ?> 

          />
          <label for="materialRegisterFormAccount">Account No.*</label>
          <div class="invalid-feedback">
            Please Enter Account No
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>
        <!-- IFSC -->
        <div class="md-form">
          <input type="text" id="materialRegisterFormIFSC" class="form-control" autocomplete="off" required name="ifsc" value="<?php echo $user['ifsc']; ?>" 
          <?php 
            if($user['ifsc'])
              echo "readonly"
          ?> 

          />
          <label for="materialRegisterFormIFSC">IFSC*</label>
          <div class="invalid-feedback">
            Please Enter IFSC
          </div>
          <div class="valid-feedback">
            Looks good!
          </div>
        </div>

        <!-- Sign up button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Update</button>

        <hr>

        <!-- Terms of service -->
        <p>By clicking
            <em>Update</em> you agree to our
            <a href="" target="_blank">terms of service</a>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form profile -->


  <!-- Material form change password -->
  <div class="card mt-3 mb-5" id="change">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Change Password</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="needs-validation" style="color: #757575;" id="changePasswordForm" novalidate>

        <!-- User ID -->
        <div class="md-form">
          <input type="text" id="materialChangeFormUserId" class="form-control" autocomplete="off" required value="<?php echo $_SESSION['userId']; ?>">
          <label for="materialChangeFormUserId">User ID*</label>
        </div>
        <!-- Old Password -->
        <div class="md-form">
          <input type="password" id="materialChangeFormOldPassword" class="form-control" autocomplete="off" required name="oldPassword">
          <label for="materialChangeFormOldPassword">Old Password*</label>
          <div class="invalid-feedback">
            Please Enter Old Password
          </div>
        </div>
        <!-- New Password -->
        <div class="md-form">
          <input type="password" id="materialChangeFormNewPassword" class="form-control" autocomplete="off" required name="newPassword">
          <label for="materialChangeFormNewPassword">New Password*</label>
          <div class="invalid-feedback">
            Please Enter New Password
          </div>
        </div>
        <!-- Confirm Password -->
        <div class="md-form">
          <input type="password" id="materialChangeFormConfirmPassword" class="form-control" autocomplete="off" required>
          <label for="materialChangeFormConfirmPassword">Confirm Password*</label>
        </div>

        <!-- Sign up button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Change Password</button>

        <hr>

        <!-- Terms of service -->
        <p>By clicking
            <em>Change Password</em> you agree to our
            <a href="" target="_blank">terms of service</a>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form change password -->
</div>
  <?php include('includes/js.php') ?>

<script>
  mdbValidation();
</script>
</body>
</html>
