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
<pre>

<strong class="h3">Free Free Free</strong>

<strong class="h5">This plan is absolutely free, no money or fees are charged here.</strong>

<strong class="h5">Joining Amount Zero</strong>
<strong class="h5">Income 10 lack</strong>
<strong class="h5">Join Hockey98 and get full 1 million</strong>
<strong class="h5">You don't have to spend any money on Hockey98, it's absolutely free</strong>

* Full Plan- *
* 💢 Hockey 98🌱 *
 _ * OJOIN
 * 0 / * - in money

🌫️🌫️🌫️🌫️🌫️🌫️🌫️
Just work and make money
Direct all levels May 3
Earn Daily.👇🏻 * _

_ * 👉🏻 Star 3 Direct ₹ 10 / - will get full 20 days * _
_ * 👉🏻 Silver 3 Direct ₹ 15 / - will get full 20 days * _
_ * 👉🏻 Gold 3 Direct ₹ 25 / -will get 30 days * _
_ * 👉🏻 Platinum 3 Direct ₹ 50 / -will get 30 days * _
_ * 👉🏻 Ruby 3 Direct ₹ 100 / - will get full 30 days * _
_ * 👉🏻 Pearl 3 Direct ₹ 200 / - for full 30 days * _
_ * 👉🏻 Diamond 3 Direct ₹ 300 / - will get full 30 days * _
_ * 👉🏻 Sapphire 3 Direct ₹ 500 / - will get full 30 days * _
_ * 👉🏻 Crown 3 Direct ₹ 1000 / - will get full 30 days * _
_ * 👉🏻 Ambassador 3 Direct ₹ 2000 / - will get full 30 days * _
_ * 👉🏻 Super 3 Direct ₹ 3000 / - will get full 30 days * _
_ * 👉🏻 Super + 3 Direct ₹ 4000 / - will get full 30 days * _
_ * 👉🏻 Gold + 3 Direct ₹ 5000 / - will get full 30 days * _
_ * 👉🏻 Diamond + 3 Direct ₹ 8000 / - will get full 30 days * _
_ * 👉🏻 Crown Diamond 3 Direct ₹ 10000 / - will get full 30 days * _


👇🏻👇🏻👇🏻👇🏻👇🏻👇🏻👇🏻

Yes
👉Friends, from home, you can earn Rs. 10 to 10 lakhs from your mobile every day. For this, just join the hockeyy98 by taking free membership once in a lifetime and get a chance to earn 10 lakhs daily.

✳️ Nor is there anything to buy in it.
✳️ Nor is there anything to sell in it.
✳️ People just have to refer.
✳️ That too in mere zero
शानदार Make great money every day with "hockey99".
✳️
🌐🌐🌐🌐🌐🌐🌐🌐🌐🌐🌐
Package
    ID Amount Zero
  
1️⃣ Daily Income calculate to 365 days , Calculate please.
 

👉Friends will not get such an opportunity again, so join today immediately.
 
Ho "hocky98" is the only platform in the world that gives the most income and that too absolutely free every day

Contact soon on WhatsApp and calling number today to join👇

For more information
Contact ..
AllCall: - 7276687707
Whats App: - Available


1️⃣ Daily Income calculate to 365 days , Calculate please.
                                                                                                 
👉🏻 Social Media Promotion

👉Friends will not get such an opportunity again
</pre>
</div>  

  </div>
  <!-- Full Page Intro -->

  <?php include('includes/js.php') ?>

</body>
</html>
