<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=activate-id");
  exit();
} 

require 'model/Epin.php';
$epinInfo = new EpinInfo();
$epins = $epinInfo->getEpins($_SESSION['userId'], 0);
if($epins['status']) {
    $epins = $epins['data'];
}else {
    $epins = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Activate ID</title>

  <?php include('includes/css.php') ?>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container mt-3">
  <!-- Material form activate id -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Activate ID</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0">

    <!-- Form -->
    <form class="needs-validation" style="color: #757575;" novalidate id="activateUserForm" >

        <div class="form-row">
            <div class="col">
                <!-- User ID -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormUserId" class="form-control" autocomplete="off" value="<?php echo $_SESSION['userId']; ?>" name="sessionId" readonly required>
                    <label for="materialRegisterFormUserId">User ID*</label>
                    <div class="invalid-feedback">
                        Your User ID is required.
                    </div>
                </div>
            </div>
            <div class="col">
                <!-- Activate User ID -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormActivateUserId" class="form-control" autocomplete="off" name="userId" required>
                    <label for="materialRegisterFormActivateUserId">Activate User ID*</label>
                    <div class="invalid-feedback">
                        Please enter valid user ID.
                    </div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="col">
                <!-- Name -->
                <div class="md-form">
                    <input type="text" id="materialRegisterFormName" class="form-control" autocomplete="off" readonly required>
                    <label for="materialRegisterFormName">Name*</label>
                </div>
                <div class="invalid-feedback">
                        Could not find name
                    </div>
            </div>
            <div class="col">
                <!-- E-Pin -->
                <select class="mdb-select md-form" name="epinId">
                    <?php
                        foreach ($epins as $epin) {
                            ?>
                            <option value="<?php echo $epin['epinId']; ?>"><?php echo $epin['epinId']; ?></option>
                            <?php
                        }
                        if(empty($epins)) {
                            ?>
                            <option value="" disabled>NO E-PINS AVAILABLE</option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>  

        <!-- Activate Button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Activate</button>

        <hr>

        <!-- Terms of service -->
        <p>By clicking
            <em>Activate</em> you agree to our
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
  </script>
</body>
</html>
