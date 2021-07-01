<?php
session_start();

if(!isset($_SESSION['poId'])) {
  header("Location: /admin?redirectUrl=product");
  exit();
} 
if(!isset($_GET['txn'])) {
    echo "<h1>Invalid request. </h1>";
    exit();
}
include("./../reuseables/modal.php"); 

require "./../model/Transaction.php";
$epinInfo = new TransactionInfo();
$result = $epinInfo->getOne($_GET['txn']);
if($result['status'] == 1) {
  $result = $result['data'];
}else {
  $result = [];
}
// print_r($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Grow Me Always | Dashboard</title>

  <?php include('css.php') ?>
  <link rel="stylesheet" href="./../css/addons/datatables.min.css">

  <style>
    .total-users {
      padding: 8px;
      background: #4285f4;
      border-radius: 25px;
    }

    td span {
      color: white;
      padding: 5px 20px;
      border-radius: 20px;
    }
    th, td {
      text-align: center;
    }
  </style>
</head>
<body>



<div class="p-4 m-5 z-depth-2">
<button class="btn warning-color" onclick="printDiv()">Print</button>
<table class="table" id="print" >
 
  <tbody>
    <tr>
      <th scope="row">Name</th>
      <td><?php echo $result['name']; ?> </td>
    </tr>
    <tr>
      <th scope="row">Mobile</th>
      <td><?php echo $result['mobile']; ?> </td>
    </tr>
    <tr>
      <th scope="row">Product</th>
      <td><?php echo $result['product']; ?> </td>
    </tr>
    <tr>
      <th scope="row">Address</th>
      <td><?php echo $result['address']; ?> </td>
    </tr>
  </tbody>
</table>
</div>


  <?php include('js.php') ?>
  <script type="text/javascript" src="./../js/addons/datatables.min.js"></script>

  <script>
    function printDiv() {
        $(".btn").hide();
        window.print();
        $(".btn").show();
    }
  </script>

</body>
</html>
