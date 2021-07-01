<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=transfer-epin");
  exit();
} 
require "./model/_const.php";
require "./model/Epin.php";
$epinInfo = new EpinInfo();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Transfer E-Pin</title>

  <?php include('includes/css.php') ?>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container mt-3">
  <!-- Material form transfering EPIN -->
<div class="card ">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Transfer E-Pin</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="needs-validation" style="color: #757575;" novalidate id="transferEpinForm"> 

        <div class="form-row">
            <div class="col">
                 <!-- Entry Fee -->
                 <select class="mdb-select md-form" required name="epinType" >
                    <option value="" disabled selected>Choose E-Pin Type</option>
                    <option value="98">₹ 98 : Entry</option>
                    <option value="148">₹ 148 : Entry</option>
                    <?php
                    foreach(LEVEL_STATS as $ls) {
                        if($ls[4])
                        echo "<option value='$ls[4]'>₹ $ls[4] : $ls[5]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <div class="md-form">
                    <input type="text" id="materialRegisterFormAvailablePins" class="form-control" value="0" autocomplete="off" readonly required pattern="[1-9]{1}[0-9]*">
                    <label for="materialRegisterFormAvailablePins">Available E-Pins</label>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <!-- Name -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormHowManyPins" class="form-control" autocomplete="off" pattern="[1-9]{1}[0-9]*" name="noOfEpins" required>
                    <label for="materialRegisterFormHowManyPins">No. Of E-Pins Transfer?</label>
                </div>
            </div>
            <div class="col">
               <!-- To User ID -->
               <div class="md-form">
                    <input type="text" id="materialRegisterFormToUserId" class="form-control" name="toId" autocomplete="off" required>
                    <label for="materialRegisterFormToUserId">To User ID</label>
                </div>
            </div>
        </div>  

        <!-- Activate Button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Transfer</button>

        <hr>

        <!-- Terms of service -->
        <p>By clicking
            <em>Transfer</em> you agree to our
            <a href="" target="_blank">terms of service</a>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form register -->

</div>
  <?php include('includes/js.php') ?>

  <script>
    epins = $("#materialRegisterFormAvailablePins");

    $("select").on('change',function(e) {
        optionSelected = $("option:selected", this);
        valueSelected = this.value;

        epin = new Epin({cost: valueSelected});
        epin.getEpins().then(function(res){
            if(res.status == 1) {
                epins.val(res.data.length);
            }else {
                epins.val(0);
            }
        })
    })

    $("#materialRegisterFormHowManyPins").on('keyup', function(e) {
        if(epins.val() < $(this).val()) {
            $(this).val("");
        }
    })

    mdbValidation();
  </script>
</body>
</html>
