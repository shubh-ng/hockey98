<?php 
include("reuseables/modal.php"); ?>

<?php

$sponserId = isset($_GET['sponserId']) ? $_GET['sponserId']: "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Hockey98</title>

  <?php include('includes/css.php') ?>

  <style type="text/css">
    html,
    body,
    header,
    .view {
      height: 100%;
    }

    @media (max-width: 740px) {
      html,
      body,
      header,
      .view {
        height: 1000px;
      }
    }

    @media (min-width: 800px) and (max-width: 850px) {
      html,
      body,
      header,
      .view {
        height: 650px;
      }
    }
    @media (min-width: 800px) and (max-width: 850px) {
              .navbar:not(.top-nav-collapse) {
                  background: #1C2331!important;
              }
          }
  </style>
</head>
<body>


<!--Modal: Login with Avatar Form-->
<div class="modal fade" id="modalLoginAvatar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog cascading-modal modal-avatar modal-sm" role="document">
  <form autocomplete="off" id="loginForm" class="needs-validation" method="POST" novalidate>
    <!--Content-->
    <div class="modal-content">

      <!--Header-->
      <div class="modal-header">
        <img src="img/user.png" alt="avatar" class="rounded-circle img-responsive">
      </div>
      <!--Body-->
      <div class="modal-body text-center mb-1">

        <h5 class="mt-1 mb-2">Login</h5>

        <div class="md-form ml-0 mr-0">
          <input type="text" type="text" id="userId" name="userId" class="form-control form-control-sm validate ml-0" autocomplete="false"  required>
          <label data-error="wrong" data-success="right" for="userId" class="ml-0">Enter User ID</label>
          <div class="invalid-feedback">
            Please Enter User ID.
          </div>
        </div>

        <div class="md-form ml-0 mr-0">
          <input type="password" type="text" id="password" name="password" class="form-control form-control-sm validate ml-0" autocomplete="off"  required>
          <label data-error="wrong" data-success="right" for="password" class="ml-0">Enter password</label>
          <div class="invalid-feedback">
            Please Enter Password.
          </div>
        </div>

        <div class="text-center mt-4">
          <button class="btn btn-cyan mt-1">Login  <i class="fas fa-sign-in-alt ml-2"></i></button>
        </div>
      </div>

    </div>
    <!--/.Content-->
    </form>
  </div>
</div>
<!--Modal: Login with Avatar Form-->


  <!-- Navbar -->
  <nav class="navbar fixed-top navbar-expand-lg navbar-dark scrolling-navbar">
    <div class="container">

      <!-- Brand -->
      <a class="navbar-brand" href="index" >
        <strong>Hockey98</strong>
      </a>

      <!-- Collapse -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Links -->
      <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <!-- Left -->
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="index">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about" >About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="legal" >Legal</a>
          </li>
        </ul>

        <!-- Right -->
        <ul class="navbar-nav nav-flex-icons">
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fab fa-facebook-f"></i>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fab fa-twitter"></i>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link border border-light rounded"
            data-toggle="modal" data-target="#modalLoginAvatar" >
            <i class="fas fa-sign-in-alt mr-2"></i>Login
            </a>
          </li>
        </ul>

      </div>

    </div>
  </nav>
  <!-- Navbar -->


  
  <!-- Full Page Intro -->
  <div class="view full-page-intro" style="background-image: url('img/hockey3.jpg'); background-repeat: no-repeat; background-size: cover;">
          <!--Card-->
          <div class="container card" style="margin-top: 7rem;">

<!--Card content-->
<div class="card-body">

  <!-- Form -->
  <form name="" autocomplete="off" class="needs-validation" novalidate id="registerForm" method="POST">
    <!-- Heading -->
    <h3 class="dark-grey-text text-center">
      <strong>Register</strong>
    </h3>
    <hr>

   <div class="form-row">
   <div class="col">
    <div class="md-form">
      <i class="fas fa-user-circle prefix mt-2 grey-text"></i>
      <input type="text" id="sponserId" name="parentId" class="form-control" autocomplete="off" value="<?php echo $sponserId; ?>" required>
      <label for="sponserId">Reference Number</label>
      <div class="invalid-feedback">
        Please Enter Sponser ID.
      </div>
    </div>
    </div>
    <div class="col">
    <div class="md-form">
      <i class="fas fa-mobile-alt prefix mt-2 grey-text"></i>
      <input type="text" id="mobile" name="mobile" class="form-control" autocomplete="off" pattern="[6-9]{1}[0-9]{9}" required>
      <label for="mobile">Your mobile</label>
      <div class="invalid-feedback">
        Please Enter Valid Mobile No.
      </div>
    </div>
    </div>
   </div>

    <div class="form-row">
      <div class="col">
        <div class="md-form">
        <i class="fas fa-user prefix mt-2 grey-text"></i>
        <input type="text" id="firstName" name="fname" class="form-control" required>
        <label for="firstName" >Your first name</label>
        <div class="invalid-feedback">
          Please Enter First Name.
        </div>
      </div>
      </div>
      <div class="col">
      <div class="md-form">
      <i class="fas fa-user prefix mt-2 grey-text"></i>
      <input type="text" id="lastName" name="lname" class="form-control" required>
      <label for="lastName" >Your last name</label>
      <div class="invalid-feedback">
          Please Enter Last Name.
      </div>
    </div>
      </div>
    </div>

    <div class="form-row">
      <div class="col">
      <div class="md-form">
      <i class="fas fa-lock prefix mt-2 grey-text"></i>
      <input type="password" id="RegisterPassword" name="password" class="form-control" autocomplete="off" required />
      <label for="RegisterPassword">Your password</label>
      <div class="invalid-feedback">
          Please Enter Password.
      </div>
    </div>
      </div>
      <div class="col">
      <div class="md-form">
      <i class="fas fa-lock prefix mt-2 grey-text"></i>
      <input type="password" id="confirmPassword" class="form-control" autocomplete="off" required />
      <label for="confirmPassword">Confirm password</label>
    </div>
      </div>
    </div>

    <div class="form-row">
    <div class="col">
        <div class="md-form">
        <i class="fas fa-user prefix mt-2 grey-text"></i>
        <input type="text" id="panNumber" name="panNumber" class="form-control" required>
        <label for="panNumber" >Your Pan Number</label>
        <div class="invalid-feedback">
          Please Enter Pan Number.
        </div>
      </div>
    </div>
    </div>
    <div class="text-center">
      <button class="btn btn-indigo">Register <i class="fas fa-angle-double-right ml-2"></i></button>
      <hr>
      <fieldset class="form-check"><p>By clicking
<em>Join</em> you agree to our
<a href="#">Terms of service</a>
      </fieldset>
    </div>

  </form>
  <!-- Form -->

</div>

</div>
<!--/.Card-->

  </div>
  <!-- Full Page Intro -->

  <?php include('includes/js.php') ?>

  <script>
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
        }, false);
    });

  </script>
</body>
</html>
