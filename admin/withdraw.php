<?php
session_start();

if(!isset($_SESSION['adminId'])) {
  header("Location: /admin?redirectUrl=withdraw");
  exit();
} 

include("./../reuseables/modal.php");

require "./../model/Withdraw.php";

$withdraw = new WithdrawInfo();
$result = $withdraw->getCanWithdraw();
$income = $result['amount'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Admin | Withdraw</title>

  <?php include('css.php') ?>
  <style>
  </style>
</head>
<body>

<?php include('header.php') ?>

<div class="container mt-3">
  <!-- Material form withdraw -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Withdraw Money</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0 <?php echo $canWithdraw==0?'hidden':''; ?>" >

    <!-- Form -->
    <form class="needs-validation" id="withdrawForm" novalidate style="color: #757575;">

            <!-- Balance -->
            <div class="md-form">
                <input type="number" id="materialRegisterFormBalance" class="form-control" autocomplete="off" value="<?php echo $income; ?>" readonly required />
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
                    <h4 class="inline bold">Payable Amount: ₹ <span id="amount-pay">0</span></h4>
                </div>
            </div>

            <!-- Name -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormName" class="form-control" name="name" value="Rajesh Bagul" autocomplete="off" required readonly  />
                <label for="materialRegisterFormName">Name</label>
            </div>

            <!-- Bank Name -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormBankName" class="form-control" name="bankName" value="ICICI" autocomplete="off" required  readonly />
                <label for="materialRegisterFormBankName">Bank Name</label>
            </div>

            <!-- Branch -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormBranch" class="form-control" name="branch" value="KANNAD" autocomplete="off" required readonly  />
                <label for="materialRegisterFormBranch">Branch</label>
            </div>

            <!-- Account Number -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormAccountNumber" class="form-control" name="accountNumber" value="375401002441" autocomplete="off" required  readonly />
                <label for="materialRegisterFormAccountNumber">Account Number</label>
            </div>

            <!-- IFSC -->
            <div class="md-form">
                <input type="text" id="materialRegisterFormifsc" class="form-control" value="ICIC0003754" autocomplete="off" required  readonly name="ifsc" />
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

  <?php include('js.php') ?>


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
        var realAmount = amount;
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

    var realAmount = amount;
 
    var data = e.target;
    var formData = new FormData();
    formData.append("amount", amount)
    formData.append("name", data.name.value)
    formData.append("account", data.accountNumber.value)
    formData.append("ifsc", data.ifsc.value)
    formData.append("amount", data.amount.value)
    $.ajax({
        type: "POST",
        url: "withdraw_admin",
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
            res = JSON.parse(res);
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

    document.querySelector("#withdraw").classList.add('active'); //activating navbar link
  </script>

</body>
</html>
