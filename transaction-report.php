<?php
session_start();

if(!isset($_SESSION['userId'])) {
  header("Location: index?redirectUrl=epins");
  exit();
} 

require "model/Transaction.php";
$epinInfo = new TransactionInfo();
$result = $epinInfo->getAllTransactions($_SESSION['userId']);
if($result['status'] == 1) {
  $result = $result['data'];
}else {
  $result = [];
}

function httpGet($order_id) {
  $merchant_pay_id='4363510114112852';
$url = "https://merchant.bhartipay.com/crm/services/paymentServices/getStatusAPI?PAY_ID=$merchant_pay_id&ORDER_ID=$order_id";
$ch = curl_init();
curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
curl_setopt( $ch, CURLOPT_URL, $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
$response = curl_exec($ch);
$response = json_decode($response);
// echo $json_data = json_encode($response);
// $json_data= json_decode($json_data);
//access via this
// $json_data->status;
curl_close($ch);
return $response->status;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Grow me Always | Transactions</title>

  <?php include('includes/css.php') ?>
  <link rel="stylesheet" href="css/addons/datatables.min.css">

  <style>
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

  <?php include('reuseables/header.php') ?>
  <?php include('reuseables/sidebar.php') ?>

  <div class="container jumbotron mt-3">
  <h3>Transactions</h3>
  <hr>
  <table id="dtMaterialDesignExample" class="table table-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">Transaction ID
      </th>
      <th class="th-sm">Live Status
      </th>
      <th class="th-sm">Payment Status
      </th>
      <th class="th-sm">Amount
      </th>
      <th class="th-sm">Courier Service
      </th>
      <th class="th-sm">LR No.
      </th>
      <th class="th-sm">Date
      </th>
    </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach ($result as $transaction) {
    $color = "success-color";
    if(strpos(strtoupper($transaction['status']), "P") !== false) {
      $color = "info-color";
    }
    if($transaction['status'] == "Cancelled") {
      $color = "danger-color";
    }
    $liveStatus = httpGet($transaction['txnId']);
    $color2 = "success-color";
    if(strpos($liveStatus, "P") !== false) {
      $color2 = "info-color";
    }
    if($liveStatus == "Cancelled") {
      $color2 = "danger-color";
    }
?>
  <form action="easebuzz?api_name=transaction">
    <tr>
      <td><?php echo $i; ?></td>
      <td><?php echo $transaction['txnId']; ?></td>
      <td> <span class="<?php echo $color2; ?>" style="text-transform:uppercase;"><?php echo $liveStatus; ?></span> </td>
      <td> <span class="<?php echo $color; ?>" style="text-transform:uppercase;"><?php echo $transaction['status']; ?></span> </td>
      <td><?php echo $transaction['amount']; ?></td>
      <td><?php echo $transaction['courier'] ? $transaction['courier'] : "<span class='info-color'>Pending</span>" ?></td>
      <td><?php echo $transaction['lrno'] ? $transaction['lrno'] : "<span class='info-color'>Pending</span>" ?></td>
      <td><?php echo $transaction['createdAt']; ?></td>
    </tr>
    </form>
  <?php
  $i++;
  }
  ?>
  </tbody>
  <tfoot>
    <tr>
    <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">Transaction ID
      </th>
      <th class="th-sm">Live Status
      </th>
      <th class="th-sm">Payment Status
      </th>
      <th class="th-sm">Amount
      </th>
      <th class="th-sm">Courier Service
      </th>
      <th class="th-sm">LR No.
      </th>
      <th class="th-sm">Date
      </th>
    </tr>
  </tfoot>
</table>
</div>

  <?php include('includes/js.php') ?>
  <script type="text/javascript" src="js/addons/datatables.min.js"></script>

  <script>
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

    function checkStatus(txnId) {
      id = "live-status-"+txnId;
      payid = "4363510114112852";

      
      fetch(`https://merchant.bhartipay.com/crm/services/paymentServices/getStatusAPI?PAY_ID=${payid}&ORDER_ID=${txnId}`, {
        method: 'GET',
        headers: {
          "User-Agent": "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1"
        }
      })
      .then(function(data){
        console.log(data);
      }).catch(function(err){
        console.log(err);
      })
    }

  </script>
</body>
</html>