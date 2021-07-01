<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=epins");
  exit();
} 

require "model/Epin.php";
$epinInfo = new EpinInfo();
$result = $epinInfo->getAllEpins($_SESSION['userId']);
$epins =  array();
if($result['status'] == 1) {
  $epins = $result['data'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Products</title>

  <?php include('includes/css.php') ?>
  <link rel="stylesheet" href="css/addons/datatables.min.css">
</head>
<body>

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container jumbotron mt-3">
  <h3>E-Pins</h3>
  <hr>
  <table id="dtMaterialDesignExample" class="table table-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">E-Pin ID
      </th>
      <th class="th-sm">Status
      </th>
    </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach ($epins as $epin) {
    foreach($epin as $e) {
  ?>
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $e['epinId']; ?></td>
      <td><?php echo isset($e['fromId']) || $e['status']?"Used":"Available"; ?></td>
    </tr>
  <?php
  $i++;
    }
  }
  ?>
  </tbody>
  <tfoot>
    <tr>
    <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">Product ID
      </th>
      <th class="th-sm">Status
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