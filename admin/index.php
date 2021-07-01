<?php include("./../reuseables/modal.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Hockey99</title>

  <?php include('css.php') ?>

</head>
<body>
    <div class="container p-5">
    <!-- Material form login -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
  <strong>Admin Login</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0" >

  <!-- Form -->
  <form class="needs-validation" novalidate id="adminLoginForm" style="color: #757575;" >

    <!-- Email -->
    <div class="md-form">
      <input type="text" id="materialLoginFormEmail" name="adminId" required class="form-control">
      <label for="materialLoginFormEmail">Admin ID</label>
      <div class="invalid-feedback">
        Please Enter Admin ID
      </div>
      <div class="valid-feedback">
        Looks Good!
      </div>
    </div>

    <!-- Password -->
    <div class="md-form">
      <input type="password" id="materialLoginFormPassword" required name="password" class="form-control">
      <label for="materialLoginFormPassword">Password</label>
      <div class="invalid-feedback">
        Please Enter Password
      </div>
      <div class="valid-feedback">
        Looks Good!
      </div>
    </div>

    <div class="d-flex justify-content-around">
      <div>
        <!-- Remember me -->
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="materialLoginFormRemember">
          <label class="form-check-label" for="materialLoginFormRemember">Remember me</label>
        </div>
      </div>
      <div>
        <!-- Forgot password -->
        <a href="">Forgot password?</a>
      </div>
    </div>

    <!-- Sign in button -->
    <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Login</button>

  </form>
  <!-- Form -->

</div>

</div>
<!-- Material form login -->
    </div>
  <?php include('js.php') ?>

  <script>
    mdbValidation();
  </script>
</body>
</html>
