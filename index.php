<?php 
session_start();

include("reuseables/modal.php"); ?>

<?php
$brand = "Grow Me Always";

$sponserId = isset($_GET['sponserId']) ? $_GET['sponserId']: "";
$name = isset($_SESSION['fname']) ? $_SESSION['fname']: "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo $brand; ?></title>

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
<body style="background-color:#B2B2B2;">

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
        <strong><?php echo $brand; ?></strong>
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
          <?php
            if($name) {
               echo "<a href='dashboard' class='nav-link border border-light rounded'>
               <i class='fas fa-sign-in-alt mr-2'></i>Welcome $name
               </a>";
            }else {
              echo "<a href='#' class='nav-link border border-light rounded'
              data-toggle='modal' data-target='#modalLoginAvatar' >
              <i class='fas fa-sign-in-alt mr-2'></i>Login
              </a>";
            }
          ?>
          </li> 
        </ul>

      </div>

    </div>
  </nav>
  <!-- Navbar -->


  <input type="hidden" name="isLogin" value="<?php echo $name?1:0; ?>" />

  <!-- Full Page Intro -->
  <div class="view full-page-intro" style="background-image: url('img/growth.jpg?v=1.0.1'); background-repeat: no-repeat; background-size: cover; background-attachment:fixed;">
 
    <!-- Mask & flexbox options-->
    <div class="mask rgba-black-light d-flex justify-content-center align-items-center">

      <!-- Content -->
      <div class="container pt-5">

        <!--Grid row-->
        <div class="row wow fadeIn">

          <!--Grid column-->
          <div class="col-md-6 mb-5 white-text text-center text-md-left">

            <h1 class="display-4 font-weight-bold"><?php echo $brand; ?></h1>

            <hr class="hr-light">

            <p style="font-size: 2rem;">
              <strong>Where all your dreams fulfills.</strong>
            </p>

            <a href='#' class='btn btn-orange btn-lg'
              data-toggle='modal' data-target='#modalLoginAvatar' >
              <i class='fas fa-sign-in-alt mr-2'></i>Login
              </a>
            <a href="register" class="btn btn-indigo btn-lg">Register
              <i class="fas fa-user ml-2"></i>
            </a>

          </div>
          <!--Grid column-->

        </div>
        <!--Grid row-->

      </div>
      <!-- Content -->

    </div>
    <!-- Mask & flexbox options-->

  </div>


  <!-- PRODUCTS LIST -->
  <!--
   <div class="p-4 mt-3">
    <h3 class="primary-color z-depth-1 p-2 text-white" style="font-size:1.5rem;"><i class="fas fa-shopping-bag ml-2 mr-2"></i> Buy Products</h3>
      <div class="row">
        <div class="col-lg-4">
          <div class="card m-2">
            <img
              src="img/products/1.jpeg"
              class="card-img-top"
              alt="All in one liquid"
              style="height: 300px; object-fit: contain;"
            />
            <div class="card-body">
              <h5 class="card-title">All in one Liquid</h5>
              <p class="card-text">
                Amount: 149 Rs.
              </p>
              <a href="buy-epins?product=0" class="btn btn-primary">Buy Now
              <i class="fas fa-shopping-bag ml-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
</div> 
-->


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
