<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=withdraw");
  exit();
} 

require "./model/Stats.php";
require "./model/Withdraw.php";
$userId = $_SESSION['userId'];
$statsInfo = new StatsInfo();
$stats = $statsInfo->getStatsByUserId2($userId);
//validate bank details
if( $stats['bankName']=="" || $stats['branch']=="" || $stats['accountNumber']=="" || $stats['ifsc'] == "" || $stats['pan'] == "" || $stats['status'] == 0) {
    echo "<script>location.href='profile';</script>";
    exit(); 
}
//validate bank details
 
//* Check if user can withdraw today
    $withdraw = new WithdrawInfo();
    $res = $withdraw->getCanWithdraw();
    $todaysCount = $withdraw->getTodaysWithdrawalCount($_SESSION['userId']);
    $canWithdraw = 1;
    $titleMessage = "Withdraw Balance";

    date_default_timezone_set("Asia/Kolkata");
    $date = date("Y-m-d");
    $updatedDate = date("Y-m-d", strtotime($res['updatedAt']));
    $hours = date("H");

    if($todaysCount > 0) {
        $canWithdraw = 0;
        $titleMessage = "You can withdraw only once in month.";
    }else if( !($hours >= 9 && $hours <= 12) ) {
        $canWithdraw = 0;
        $titleMessage = "Withdraw time is only, 9 A.M. to 12 P.M.";
    }else if($res['canWithdraw'] == 0) {
        $canWithdraw = 0;
        $titleMessage = "Can't withdraw due to insuficient fund. Please increase downlines.";
    } 
//* \\Check if user can withdraw today


$totalBalance = $stats['totalBalance'];
$totalWithdrawal = $stats['totalWithdrawal'];
$remainingBalance = $totalBalance - $totalWithdrawal;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Withdraw</title>

  <?php include('includes/css.php') ?>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


  <style>
    .hidden {
        display: none;
    }
    .form-text {
        background: aliceblue;
        padding: 1rem;
        color: rgba(0,0,0,0.6);
    }
    .inline {
        margin: 0;
    }
  </style>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>



  <div class="container">
  <!-- Material form withdraw -->
<div class="card ">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong><?php echo $titleMessage; ?></strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0 <?php echo $canWithdraw==0?'hidden':''; ?>" >

    <!-- Form -->
    <form class="needs-validation" id="withdrawForm" novalidate style="color: #757575;">

            <!-- User ID -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormUserId" class="form-control" readonly autocomplete="off" value="<?php echo $userId; ?>" required />
                <label for="materialRegisterFormUserId">User ID </label>
            </div>
            <div class="valid-feedback">
                    Looks Good
            </div>

            <!-- Balance -->
            <div class="md-form">
                <input type="number" id="materialRegisterFormBalance" class="form-control" autocomplete="off" value="<?php echo $remainingBalance; ?>" readonly required />
                <label for="materialRegisterFormBalance">Balance</label>
            </div>
            <div class="valid-feedback">
                    Looks Good
            </div>

                <!-- Amount -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormAmount" class="form-control" name="amount" autocomplete="off" required  />
                <label for="materialRegisterFormAmount">Enter Withdrawal Amount</label>
                <div class="valid-feedback">
                    Looks Good
                </div>
                <div class="invalid-feedback">
                    Please enter valid amount
                </div>
                <div class="form-text">
                    <p class="inline">Admin Charges: 15%</p>
                    <p class="inline">TDS: 5%</p>
                    <p class="inline">Transaction Charge: ₹ 3</p>
                    <p class="inline">GST: ₹ 0.54</p>
                    <h4 class="inline bold">Payable Amount: ₹ <span id="amount-pay">0</span></h4>
                </div>
            </div>

            <!-- Name -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormName" class="form-control" name="name" value="<?php echo $stats['firstName']; ?>" autocomplete="off" required readonly  />
                <label for="materialRegisterFormName">Name</label>
            </div>

            <!-- Bank Name -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormBankName" class="form-control" name="bankName" value="<?php echo $stats['bankName']; ?>" autocomplete="off" required  readonly />
                <label for="materialRegisterFormBankName">Bank Name</label>
            </div>

            <!-- Branch -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormBranch" class="form-control" name="branch" value="<?php echo $stats['branch']; ?>" autocomplete="off" required readonly  />
                <label for="materialRegisterFormBranch">Branch</label>
            </div>

            <!-- Account Number -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormAccountNumber" class="form-control" name="accountNumber" value="<?php echo $stats['accountNumber']; ?>" autocomplete="off" required  readonly />
                <label for="materialRegisterFormAccountNumber">Account Number</label>
            </div>

            <!-- IFSC -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormifsc" class="form-control" value="<?php echo $stats['ifsc']; ?>" autocomplete="off" required  readonly name="ifsc" />
                <label for="materialRegisterFormifsc">IFSC</label>
            </div>

        <!-- Sign up button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Withdraw</button>

        <hr>

        <!-- Terms of service -->
        <p>By clicking
            <em>Withdraw</em> you agree to our
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
    balance = $("#materialRegisterFormBalance");

    var output = $("#amount-pay");
    $("#materialRegisterFormAmount").on('keyup', function(e) {
        balanceVal = parseInt(balance.val());
        amount = parseFloat($(this).val());
        if(balanceVal < amount) {
            $(this).val("");
        }
    })

    $("#materialRegisterFormAmount").on('keyup', function(){
        amount = parseFloat($(this).val());
        var adminCharge = amount * 20 / 100;
        var gst = 0.54;
        var gatewayCharge = 3;
        var realAmount = amount - adminCharge - gatewayCharge - gst;
        if(isNaN(realAmount) || realAmount < 0) {
            output.text(0);
        }else {
            output.text(realAmount.toFixed(2));
        }
    })

    // Check balance from backend
  $("#withdrawForm").submit(function (e) {
    e.preventDefault();
    var amount = parseFloat($("#materialRegisterFormAmount").val());
    if(!amount) return;

     $('.loader-holder').show(); // show loader

    var adminCharge = amount * 20 / 100;
    var gst = 0.54;
    var gatewayCharge = 3;
    var realAmount = amount - adminCharge - gatewayCharge - gst;
 
    var data = e.target;
    console.log(data);
    var formData = new FormData();
    formData.append("amount", amount)
    formData.append("name", data.name.value)
    formData.append("account", data.accountNumber.value)
    formData.append("ifsc", data.ifsc.value)
    formData.append("amount", data.amount.value)
    $.ajax({
        type: "POST",
        url: "api/withdraw",
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.status == 1) {
                // Withdraw request
                showModal({
                    title: "Success",
                    body: res.status_message,
                    type: "success",
                    position: "right",
                });
            } else if(res.status == 0) {
                showModal({
                    title: "Error",
                    body: res.status_message,
                    type: "danger",
                    position: "right",
                });
            } else if(res.status == 2) {
                showModal({
                    title: "Processed",
                    body: res.status_message,
                    type: "info",
                    position: "right",
                });
            }
        },
        complete: function (res) {
            console.log("Complete");
               $('.loader-holder').fadeOut();
        },
    });
  });

//   function withdraw(data) {
//       console.log(data.amount.value)
//     var formData = new FormData();
//     formData.append("name", data.name.value)
//     formData.append("account", data.accountNumber.value)
//     formData.append("ifsc", data.ifsc.value)
//     formData.append("amount", data.amount.value)
//     $.ajax({
//         type: "POST",
//         url: "withdraw_helper",
//         data: formData,
//         contentType: false,
//         processData: false,
//         success: function (res) {
//             res = JSON.parse(res);
//             // console.log(res);
//             if (res.success == true) {
//                 showModal({
//                     title: "Success",
//                     body: "Successfully transfered",
//                     type: "danger",
//                     position: "right",
//                 });
//             } else {
//                 showModal({
//                     title: "Error",
//                     body: res.message,
//                     type: "danger",
//                     position: "right",
//                 });
//             }
//         },
//         complete: function (res) {
//             console.log("Complete");
//         },
//     });
//   }
  </script>
</body>
</html>
