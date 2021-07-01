<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=hockey");
  exit();
} 

require "model/Epin.php";

$epinInfo = new EpinInfo;
$epins = $epinInfo->getEpins($_SESSION['userId'], 1);

if(isset($epins['data'])) {
    $epins = $epins['data'];
}else {
    $epins = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Hockey Videos</title>

  <?php include('includes/css.php') ?>
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

<div class="container mt-3">
<div class="jumbotron">
    <h3>Hockey Videos</h3>
    <hr>
    
    <?php
        foreach($epins as $e) {
            ?>
            <iframe class="z-depth-1" width="853" height="480" src="https://www.youtube.com/embed/JP2jo285Q-s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

            <?php
        }
        
        if(!$epins) {
            echo "No videos available. <a href='buy-epins'>Buy Now</a>";
        }
    ?>
 



</div>
</div>
  <?php include('includes/js.php'); ?>

</body>
</html>