<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=lvl-bonus-report");
  exit();
} 
require "./model/BonusIncome.php";
$bi = new BonusIncome();
$report = $bi->getBonusIncomeReports($_SESSION['userId'], 'SB');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Sponser Bonus Report</title>

  <?php include('includes/css.php') ?>
  <link rel="stylesheet" href="css/addons/datatables.min.css">
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container jumbotron mt-3">
  <h3>Sponser Bonus Report</h3>
  <hr>
  <table id="dtMaterialDesignExample" class="table table-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Transaction ID
      </th>
      <th class="th-sm">Date
      </th>
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">Income (In ₹)
      </th>
    </tr>
  </thead>
  <tbody>
  <?php
  foreach($report as $r) {
    $id = $r['id'];
    $createdAt = $r['createdAt'];
    $userId = $r['userId'];
    $bonusLevel = $r['bonusLevel'];
    $amount = $r['amount'];
    echo "<tr>
    <td> $id </td>
    <td>$createdAt</td>
    <td>$userId</td>
    <td>$amount</td>
  </tr>";
  }
  ?>
  </tbody>
  <tfoot>
    <tr>
    <th class="th-sm">Transaction ID
      </th>
      <th class="th-sm">Date
      </th>
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">Income (In ₹)
      </th>
    </tr>
  </tfoot>
</table>
  </div>

  <?php include('includes/js.php') ?>
  <script type="text/javascript" src="js/addons/datatables.min.js"></script>

  <script type="text/javascript">
    // Material Design example
    $(document).ready(function () {
      $('#dtMaterialDesignExample').DataTable();
      $('#dtMaterialDesignExample_wrapper').find('label').each(function () {
        $(this).parent().append($(this).children());
      });
      $('#dtMaterialDesignExample_wrapper .dataTables_filter').find('input').each(function () {
        const $this = $(this);
        $this.attr("placeholder", "Search");
        $this.removeClass('form-control-sm');
      });
      $('#dtMaterialDesignExample_wrapper .dataTables_length').addClass('d-flex flex-row');
      $('#dtMaterialDesignExample_wrapper .dataTables_filter').addClass('md-form');
      $('#dtMaterialDesignExample_wrapper select').removeClass('custom-select custom-select-sm form-control form-control-sm');
      $('#dtMaterialDesignExample_wrapper select').addClass('mdb-select');
      $('#dtMaterialDesignExample_wrapper .mdb-select').materialSelect();
      $('#dtMaterialDesignExample_wrapper .dataTables_filter').find('label').remove();
    });
  </script>

</body>
</html>