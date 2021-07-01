<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=product");
  exit();
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Grow Me Always Products</title>

  <?php include('includes/css.php') ?>


</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

<div class="container mt-3">
    <h3>Our Products</h3>
    <hr>
    <div class="jumbotron">
      <div class="row">
        <div class="col-lg-4">
          <div class="card">
            <img
              src="img/products/1.jpeg"
              class="card-img-top"
              alt="All in one liquid"
              style="height: 300px; object-fit: contain;"
            />
            <div class="card-body">
              <h5 class="card-title">All in one Liquid</h5>
              <p class="card-text">
                Amount: 148 Rs.
              </p>
              <a href="buy-epins?product=0" class="btn btn-primary">Buy Now
              <i class="fas fa-shopping-bag ml-2"></i>
              </a>
            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <img
              src="img/products/2.jpeg"
              class="card-img-top"
              alt="All in one liquid"
              style="height: 300px; object-fit: contain;"

            />
            <div class="card-body">
              <h5 class="card-title">Sanitary Pad</h5>
              <p class="card-text">
                Amount: 148 Rs.
              </p>
              <a href="buy-epins?product=1" class="btn btn-primary">Buy Now
              <i class="fas fa-shopping-bag ml-2"></i>
              
              </a>
            </div>
          </div> 
        </div>
      </div>
    </div>
</div>
  <?php include('includes/js.php'); ?>

</body>
</html>
