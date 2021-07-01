<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=dashboard");
  exit();
} 
// WMUM69
require "./brand.php";
require "model/_const.php";
require "model/Stats.php";
require "model/Epin.php";
require "model/User.php";
$statsInfo = new StatsInfo();
$stats = $statsInfo->getStatsByUserId($_SESSION['userId']);
$stats['slBonus'] = $stats['slBonus'];
$stats['sponserBonus'] = $stats['sponserBonus'];
$stats['totalWithdrawal'] = $stats['totalWithdrawal'];
$noOfDownlines = $stats['noOfDownlines'];
$total = $stats['slBonus'] + $stats['sponserBonus'];

$epinInfo = new EpinInfo();
$result = $epinInfo->getEpins($_SESSION['userId'], 0);
$epinCount = 0;
if(isset($result['data']))
  $epinCount = count($result['data']);

$userInfo = new UserInfo();
$directUsersCount = $userInfo->getDirectUsersCount($_SESSION['userId'])[0]['count'];
$userData = $userInfo->getUserInfo($_SESSION['userId']);
$clientCode = $userData['clientCode'];
$mobile = $userData['mobile'];

$amountStats = $statsInfo->getLevelIncomeCountsByUserId($_SESSION['userId']);

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https:":"http:")."//$_SERVER[HTTP_HOST]";
if(strpos($baseUrl, "localhost") != false) {
  $baseUrl .= "/hockey98";
}


function calculateIncome($level) {
  if($level < 1 || $level > count(LEVEL_STATS2)) {
    return 0;
  }
  global $amountStats;
  $amount = 0;
  foreach($amountStats as $st) {
    $bonusLevel = $st['bonusLevel'];
    $count = $st['count'];
    if($bonusLevel == $level) {
      $amount = $count * LEVEL_STATS[$bonusLevel][2];
      break;
    }
  }
  return number_format($amount);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php echo BRAND_NAME; ?></title>

  <?php include('includes/css.php') ?>

  <style>
    .card {
      box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2)!important;
      margin: 1rem;
    }
    .info-amount {
        font-size: 1.5rem;
    }
    .buybtn {
        font-size: 1rem;
        font-weight: bold;
        letter-spacing: 1px;
    }
    .contact-cards {
        display: flex;
        justify-content: center;
    }
  </style>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <input type="hidden" name="mobile_number" value="<?php echo $mobile; ?>">
  <!--IF USER IS NOT ACTIVE, ASK TO ACTIVATE-->
  <?php
    $status = $userData['status'];
    if($status == 0 && $epinCount == 0) {
        $_SESSION['userStatus'] = 0;
        ?>

        <?php if(!$clientCode) { ?>        
            <!-- DEACTIVATE MESSAGE -->
            <div class="alert alert-danger alert-dismissible jumbotron danger-color text-white" role="alert" id="user-inactive" style="display:none">
              <h4 class="alert-heading font-weight-bold"><i class="fas fa-lock"></i> Deactivated!</h4>
              <p>Your ID is not active. 
              <br>
              <strong>Step 1:</strong>
              <a href="<?php echo ALICE_BLUE_REFERENCE_LINK; ?>" class="btn btn-success btn-md btn-rounded buybtn">Create Account <i class="fas fa-check"></i></a> 
              </p>
              <strong>Important: Your ID will be deleted automatically in 120 hours, if not activated.</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <hr>
              <p class="mb-0">User ID: <span><?php echo $userData['userId']; ?></span>, Name: <span ><?php echo $userData['firstName']; ?></span></p>
            </div>
            
<h4 class="text-center" style="font-weight:800">Help : Contact below to create account</h4>
            <div class="row text-center contact-cards">
                <div class="col-lg-3 card">
                  <div class="card-body">
                    <h5 class="card-title">Mr. Sagar</h5>
                    <p class="card-text">
                      Contact : +91 9175532765
                    </p>
                    <button type="button" class="btn btn-primary"><a href="tel:9175532765" class="text-white">Call</a></button>
                  </div>
                </div>
            <div class="col-lg-3 card">
              <div class="card-body">
                <h5 class="card-title">Ms. Bhagyashree</h5>
                <p class="card-text">
                  Contact : +91 7304103991
                </p>
                <button type="button" class="btn btn-primary"><a href="tel:7304103991" class="text-white">Call</a></button>
              </div>
              </div>
            <div class="col-lg-3 card">
              <div class="card-body">
                <h5 class="card-title">Mr. Chirag</h5>
                <p class="card-text">
                  Contact : +91 9322961366
                </p>
                <button type="button" class="btn btn-primary"><a href="tel:9322961366" class="text-white">Call</a></button>
              </div>
            </div>
            <div class="col-lg-3 card">
              <div class="card-body">
                <h5 class="card-title">Ms. Swati</h5>
                <p class="card-text">
                  Contact : +91 9096893141
                </p>
                <button type="button" class="btn btn-primary"><a href="tel:9096893141" class="text-white">Call</a></button>
              </div>
            </div>            
            <!-- Form -->
            <form class="needs-validation container" id="activate_account_form" method="POST" novalidate style="color: #757575;">
                <h5 class="card-header primary-color white-text text-center py-2">
                    <strong>Step 2: Activate Your Account</strong>
                </h5>
                <div class="form-row">
                    <div class="col">
                        <!-- No. of E-Pins -->
                        <div class="md-form">
                            <input type="text" id="materialRegisterFormEPins" style="text-transform:none!important;" name="clientCode" class="form-control" autocomplete="off" required>
                            <label for="materialRegisterFormEPins">Mobile number.</label>
                            <div class="invalid-feedback">
                                Please valid mobile number..
                            </div>
                            <div class="valid-feedback">
                                Looks Good
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sign up button -->
                <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Activate Now</button>

                <hr>

                <!-- Terms of service -->
                <p>By clicking
                    <em>Activate Now</em> you can start adding directs and start earning.
                </p>
            </form>
            <!-- Form -->
            
        </div>
          <?php } else { ?>
            <div class="alert alert-danger alert-dismissible jumbotron info-color text-white" role="alert" id="user-inactive" style="display:none">
              <h4 class="alert-heading font-weight-bold"><i class="fas fa-clock"></i> Pending!</h4>
              <p>Your ID is not active. It is under process. Contact Admin for further process. 
              </p>
              <strong>Important: Your ID will be deleted automatically in 120 hours, if not activated.</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <hr>
              <p class="mb-0">User ID: <span><?php echo $userData['userId']; ?></span>, Name: <span ><?php echo $userData['firstName']; ?></span></p>
            </div>
            <?php } ?>
        <?php
    }else {
  ?>
  <!--\IF USER IS NOT ACTIVE, ASK TO ACTIVATE-->
  
  <?php include('reuseables/sidebar.php') ?>

    <div class="container mt-3">
    <!-- ACTIVATE MESSAGE -->
  <div class="alert alert-success alert-dismissible jumbotron success-color text-white" role="alert" id="user-active" style="display:none">
  <h4 class="alert-heading font-weight-bold"><i class="fas fa-check"></i> Activated!</h4>
  <p>Your ID is Successfully Activated</p>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <hr>
  <p class="mb-0">User ID: <span data-userid></span>, Name: <span data-name></span></p>
  </div>

    <!-- DEACTIVATE MESSAGE -->
    <div class="alert alert-danger alert-dismissible jumbotron danger-color text-white" role="alert" id="user-inactive" style="display:none">
  <h4 class="alert-heading font-weight-bold"><i class="fas fa-lock"></i> Deactivated!</h4>
  <p>Your ID is Not Active. <a href="activate-id" class="btn btn-success btn-sm btn-rounded">Activate Now <i class="fas fa-check"></i></a> </p>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <hr>
  <p class="mb-0">User ID: <span data-userid></span>, Name: <span data-name></span></p>
</div>

<!-- !PRODUCTS -->
<!--
<div class="jumbotron">
<h5 class="card-title primary-color p-3 text-white">

<i class="fas fa-shopping-bag mr-2"></i>
  Buy Our Products</h5>
      <div class="row">
        <div class="col-lg-12">
          <div class="card  z-depth-0">
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
<!-- /PRODUCTS -->

    <!-- REFER LINK -->
      <div class="card">
        <div class="card-body">
          <h5 class="card-title primary-color p-3 text-white">Refer Link</h5>
          <p class="card-text">
          <?php echo $baseUrl."/register?sponserId=".$_SESSION['userId']; ?>
          </p>
          <button class="card-link btn btn-primary btn-md" onclick="myFunction()">Copy Link</button>
          <a class="card-link btn btn-default btn-md" href="<?php echo $baseUrl."/register?sponserId=".$_SESSION['userId']; ?>">Open Link</a>
          <input type="text" value="<?php echo $baseUrl."/register?sponserId=".$_SESSION['userId']; ?>" id="myInput" style="opacity:0;text-transform:none!important;" />

        </div>
      </div>


    <!-- DETAILS -->
    <div class="row mt-3 info-container" style="padding:10px;">

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box black-text">
    <div class="info jumbotron">
        <span class="info-desc">Total Downlines</span>
        <h1 class="info-amount"><?php echo number_format($noOfDownlines); ?></h1>
    </div>
    <i class="fas fa-users info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box black-text">
    <div class="info jumbotron">
        <span class="info-desc">Total Directs</span>
        <h1 class="info-amount"><?php echo $directUsersCount; ?></h1>
    </div>
    <i class="fas fa-users info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box black-text">
   <div class="info jumbotron">
        <span class="info-desc">Level/Ranks</span>
        <h1 class="info-amount"><?php echo $stats['level']; ?></h1>
    </div>
    <i class="fas fa-chart-line info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box black-text">
    <div class="info jumbotron">
        <span class="info-desc">Available E-Pins</span>
        <h1 class="info-amount"><?php echo $epinCount; ?></h1>
    </div>
    <i class="fas fa-map-pin info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box black-text">
    <div class="info jumbotron secondary-color">
        <span class="info-desc">Total Income</span>
        
        <h1 class="info-amount"><?php echo number_format($total, 2); ?></h1>
    </div>
    <i class="fas fa-rupee-sign info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
    <div class="info jumbotron primary-color">
        <span class="info-desc">Balance</span>
        <h1 class="info-amount"><?php echo number_format($stats['slBonus'] + $stats['sponserBonus'] - $stats['totalWithdrawal'],2); ?></h1>
    </div>
    <i class="fas fa-rupee-sign info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
    <div class="info jumbotron secondary-color">
        <span class="info-desc">Total Withdrawal</span>
        <h1 class="info-amount"><?php echo $stats['totalWithdrawal']; ?></h1>
    </div>
    <i class="fas fa-wallet info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box ">
    <div class="info jumbotron danger-color">
        <span class="info-desc">Single Leg Bonus</span>
        <h1 class="info-amount"><?php echo number_format($stats['slBonus'], 2); ?></h1>
    </div>
    <i class="fas fa-plus-circle info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box ">
    <div class="info jumbotron warning-color">
        <span class="info-desc">Direct Bonus</span>
        <h1 class="info-amount"><?php echo $stats['sponserBonus']; ?></h1>
    </div>
    <i class="fas fa-plus-circle info-box-icon"></i>
    </div>

    </div>

    <br>

<!-- TABLE -->
<div class="table-container">
    <table class="table table-responsive" style="width: 100%;text-align:center">
  <thead class="black white-text">
    <tr>
      <th scope="col">#Rank ID</th>
      <th scope="col">Rank</th>
      <th scope="col">Team</th>
      <th scope="col">Direct</th>
      <th scope="col">Income</th>
      <th scope="col">Total Income</th>
      <th scope="col">Income</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i = 1;
    foreach(LEVEL_STATS2 as $ls) {
      $teams = number_format($ls[0]);
      $total = number_format($ls[2]*$ls[3]);
      $income = calculateIncome($i);
      // RUNNING
      if($i == $stats['level']) {
        echo "<tr class='warning-color customAnimation text-white'><td>$i</td><td>$ls[5]</td><td>$teams +</td><td>$ls[1]</td><td>$ls[2] X $ls[3] Days</td><td>$total</td><td>₹ $income</td><td>Running</td>";
      }
      // COMPLETED
      else if($i < $stats['level']) {
        echo "<tr class='success-color text-white'><td>$i</td><td>$ls[5]</td><td>$teams +</td><td>$ls[1]</td><td>$ls[2] X $ls[3] Days</td><td>$total</td><td class='animated flash infinite slow'>₹ $income</td><td>Completed</td>";
      }else {
        echo "<tr class='info-color text-white'><td>$i</td><td>$ls[5]</td><td>$teams +</td><td>$ls[1]</td><td>$ls[2] X $ls[3] Days</td><td>$total</td><td>₹ $income</td><td>Upcoming</td>";
      }
      $i++;
    }
    ?>
  </tbody>
</table>
</div>
</div>

<?php
} // close else
?>
  <?php include('includes/js.php') ?>

  <script>
    mdbValidation();
  $(document).ready(function(){
      // User info
      const user = new User({});
      user.getUserInfo()
      .then(res => res.data)
      .then(data => {
        if(data.status == 0) {
            $(".sidebar-toggler").hide();
          $("#user-inactive").fadeIn();
          $("#user-status").html(`<i class="fas fa-times"></i>`)
        }else {
          $("#user-active").fadeIn();
          $("#user-status").html(`<i class="fas fa-check"></i>`)
        }
        $("[data-userid]").text(data.userId);
        $("[data-name]").text(data.firstName+" "+data.lastName);
      });
      
    })
    

    function myFunction() {
          /* Get the text field */
          var copyText = document.getElementById("myInput");
        
          /* Select the text field */
          copyText.select();
          copyText.setSelectionRange(0, 99999); /* For mobile devices */
        
          /* Copy the text inside the text field */
          document.execCommand("copy");
        
          /* Alert the copied text */
          
        showModal({
          title: "Link Copied",
          body: `Link Copied: <strong>${copyText.value}</strong>`,
          type: "success",
          position: "right"
        });
//          alert("Link Copied: " + copyText.value);
    }

  </script>
</body>
</html>
