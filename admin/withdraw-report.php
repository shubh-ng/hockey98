<?php
session_start();

if(!isset($_SESSION['adminId'])) {
  header("Location: admin?redirectUrl=withdraw-report");
  exit();
} 

require "./../model/Snapshot.php";
$snapshot = new SnapshotInfo();
$report = $snapshot->getAllSnapshots($_SESSION['adminId']);

if(isset($report['data'])) {
    $report = $report['data'];
}
// print_r($report);
// exit();

function withdrawReport() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://hockey98.com/api/_withdrawStatus',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET'
    ));
    
    $response = curl_exec($curl);
    
    curl_close($curl);

}

withdrawReport();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Admin | Withdraw</title>

  <?php include('css.php') ?>
   <link rel="stylesheet" href="./../css/addons/datatables.min.css">
  <style>
  </style>
</head>
<body>

  <?php include('header.php') ?>

  <div class="container jumbotron mt-3">
  <h3>Withdraw Report</h3>
  <hr>
  <table id="dtMaterialDesignExample" class="table table-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
        <th class="th-sm">Withdraw Status
      </th>
      <th class="th-sm">Withdraw Amount
      </th>
      <th class="th-sm">Date
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i=1;
  foreach($report as $r) {
    $createdAt = $r['createdAt'];
    $amount = $r['amount'];
    $status = $r['status'];
    echo "<tr>
    <td> $i </td>
    <td>$status</td>
    <td>$amount</td>
    <td>$createdAt</td>
  </tr>";
    $i++;
  }
  ?>
  </tbody>
  <tfoot>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
        <th class="th-sm">Withdraw Status
      </th>
      <th class="th-sm">Withdraw Amount
      </th>
      <th class="th-sm">Date
      </th>
    </tr>
  </tfoot>
</table>
  </div>

  <?php include('js.php') ?>
  <script type="text/javascript" src="./../js/addons/datatables.min.js"></script>

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

    document.querySelector("#withdraw-report").classList.add('active'); //activating navbar link

  </script>

</body>
</html>