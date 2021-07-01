<?php
session_start();

if(isset($_SESSION['poId'])) {
  header("Location: product");
  exit();
}
if(!isset($_SESSION['adminId'])) {
  header("Location: /admin?redirectUrl=dashboard");
  exit();
}
require "./../model/_const.php";
require "./../model/Stats.php";
require "./../model/Epin.php";
require "./../model/Withdraw.php";
$statsInfo = new StatsInfo();
$stats = $statsInfo->getTodaysUsers();// print_r($stats[0]);
$epinInfo = new EpinInfo();
$epinCount = $epinInfo->getTodaysEpinCount();

$withdraw = new WithdrawInfo();
$result = $withdraw->getCanWithdraw();
$status = $result['canWithdraw'];
$income = $result['amount'];
$dResponse = $withdraw->getActiveDenominations()['activeDenominations'];
$allDenominations = [500,1000,3000,5000,10000];
$denominations = explode(",", $dResponse);
// sort($denominations);

$total = 0;
$active = 0;
$nonactive = 0;
if(!empty($stats)) {
  if(isset($stats[0], $stats[1]) ) {
    $active = isset($stats[1])?$stats[1]['count']:0;
    $nonactive = isset($stats[0])?$stats[0]['count']:0;  
  }else if(isset($stats[0])) {
    if($stats[0]['level'] == 0) {
      $nonactive = isset($stats[0])?$stats[0]['count']:0;    
    }else {
      $active = isset($stats[0])?$stats[0]['count']:0;
    }
  }
  $total = $active + $nonactive;
}



include("./../reuseables/modal.php"); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Grow Me Always | Dashboard</title>

  <?php include('css.php') ?>

  <style>
    .total-users {
      padding: 8px;
      background: #4285f4;
      border-radius: 25px;
    }
  </style>
  

</head>
<body>

<?php include('header.php') ?>

<div class="container mt-3">
  <div class="row info-container">
    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
        <div class="info jumbotron primary-color">
            <span class="info-desc">Today's Joining</span>
            <h1 class="info-amount"><?php echo $total; ?></h1>
        </div>
        <i class="fas fa-users info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
        <div class="info jumbotron success-color">
            <span class="info-desc">Today's Active Users</span>
            <h1 class="info-amount"><?php echo $active; ?></h1>
        </div>
        <i class="fas fa-user-check info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
        <div class="info jumbotron danger-color">
            <span class="info-desc">Today's Non Active Users</span>
            <h1 class="info-amount"><?php echo $nonactive; ?></h1>
        </div>
        <i class="fas fa-user-times info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
        <div class="info jumbotron secondary-color">
            <span class="info-desc">Today's E-Pin Count</span>
            <h1 class="info-amount"><?php echo $epinCount; ?></h1>
        </div>
        <i class="fas fa-map-pin info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
        <div class="info jumbotron secondary-color">
            <span class="info-desc">Balance</span>
            <h1 class="info-amount"><?php echo number_format($income,2); ?></h1>
        </div>
    <i class="fas fa-rupee-sign info-box-icon"></i>
    </div>

    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 info-box">
        <div class="info jumbotron warning-color">
            <span class="info-desc">Withdraw Option</span>
            <!-- Checked switch -->
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="toggleWithdraw" <?php echo $status==1?"checked":"" ?> />
              <label class="form-check-label" for="toggleWithdraw"
                >Can Withdraw ?</label
              >
            </div>
        </div>
    </div> 
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 info-box">
          <div class="info jumbotron warning-color">
              <span class="info-desc">Active Denominations</span>

              <?php foreach ($allDenominations as $denomination) {
                $isChecked = false;
                if(array_search($denomination, $denominations) !== false) {
                  $isChecked = true;
                }
                ?>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" value="<?php echo $denomination ?>" name="denominations" id="<?php echo "d-".$denomination ?>" <?php echo $isChecked==1?"checked":"" ?> />
                  <label class="form-check-label" for="<?php echo "d-".$denomination ?>"
                    >Rs. <?php echo $denomination; ?></label
                  >
                </div>
                <?php
              } ?>
          </div>
      </div> 
  </div>


  <!-- table -->
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
      <th scope="col">Total Users</th>
    </tr>
  </thead>
  <tbody id="statsTbl">
    <?php
    $i = 1;
    foreach(LEVEL_STATS2 as $ls) {
      $teams = number_format($ls[0]);
      $total = number_format($ls[2]*$ls[3]);
      echo "<tr class='warning-color text-white' >
              <td>$i</td>
              <td>$ls[5]</td>
              <td>$teams +</td>
              <td>$ls[1]</td>
              <td>$ls[2] <i class='fas fa-times'></i> $ls[3] Days</td>
              <td>$total</td>
              <td ><span id='$i-users' class='total-users'>0</span></td>
            </tr>";
      $i++;
    }
    ?>
  </tbody>
</table>
</div>
</div>

  <?php include('js.php') ?>

    <script>
      const stats = new Stats();
      stats
      .getAllUsersStats()
      .then(function(res){
        console.log(res);
        res.forEach(function(user, i){
          if(i > 0)
          $(`#${i}-users`).text(user.count);
        })
      });
      
     document.querySelector("#dashboard").classList.add('active'); //activating navbar link
    </script>

</body>
</html>
