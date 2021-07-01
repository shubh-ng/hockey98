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

          .container::-webkit-scrollbar {
  width: 1em;
}
 
.container::-webkit-scrollbar-track {
  box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}
 
.container::-webkit-scrollbar-thumb {
  background-color: darkgrey;
  outline: 1px solid slategrey;
}

li {
    text-align: justify;
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


  
  <!-- Full Page Intro -->
  <div class="view full-page-intro" 
  style="background: #616161; 
background: -webkit-linear-gradient(to right, #9bc5c3, #616161);  
background: linear-gradient(to right, #9bc5c3, #616161); 
"
  >
 
 
<div class="container"  style="margin-top:5rem;overflow: auto;height: 100vh;">
<div class="jumbotron">
<h4>Legal Document</h4>
<img src="./img/certificate.jpg" alt="Our Certificate">
</div>  

  </div>
  <!-- Full Page Intro -->

  <?php include('includes/js.php') ?>

</body>
</html>
