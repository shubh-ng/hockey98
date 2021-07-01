<?php
session_start();

if(!isset($_SESSION['poId'])) {
  header("Location: /admin?redirectUrl=product");
  exit();
} 
include("./../reuseables/modal.php"); 

require "./../model/Transaction.php";
$epinInfo = new TransactionInfo();
$result = $epinInfo->getAllSuccess();
if($result['status'] == 1) {
  $result = $result['data'];
}else {
  $result = [];
}
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

<?php include('header2.php') ?>


<div class="p-4 m-5 z-depth-2">
  <h3>Transactions</h3>
  <hr>
  <table id="dtMaterialDesignExample" class="table table-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">Name
      </th>
      <th class="th-sm">Mobile
      </th>

      <th class="th-sm">Quantity
      </th>
      <th class="th-sm">Product
      </th>
      <th class="th-sm">Address
      </th>
      <th class="th-sm">Date
      </th>
      <th class="th-sm">Courier Service
      </th>
      <th class="th-sm">LR No.
      </th>
      <th class="th-sm">Save
      </th>
      <th class="th-sm">Print
      </th>
    </tr>
  </thead>
  <tbody>
  <?php
  $i = 1;
  foreach ($result as $transaction) {
    $color = "success-color";
    if(strpos(strtoupper($transaction['status']), "PAY") !== false) {
      $color = "info-color";
    }
  
  ?>
    <tr>
  <form class="assignCourier" action="">
      <td><?php echo $i; ?></td>
      <td><?php echo $transaction['userId']; ?></td>
      <td><?php echo $transaction['name']; ?></td>
      <td><?php echo $transaction['mobile']; ?></td>
      <!-- <td> <span class="<?php echo $color; ?>"><?php echo $transaction['status']; ?></span> </td> -->
      <td><?php echo $transaction['quantity']; ?></td>
      <td><?php echo $transaction['product']; ?></td>
      <td><?php echo $transaction['address']; ?></td>
      <td><?php echo $transaction['createdAt']; ?></td>
      <td>
      <!-- TXN ID HIDDEN -->
      <input type="hidden" value="<?php echo $transaction['txnId']; ?>" name="txnId">
        <div class="md-form">
            <input type="text" id="courier" name="service" value="<?php echo $transaction['courier']; ?>" class="form-control" autocomplete="off"  required>
            <label for="courier">Enter courier service</label>
        </div>
      </td>
      <td>

        <div class="md-form">
            <input type="text" id="lrno" name="lrno" value="<?php echo $transaction['lrno']; ?>" class="form-control" autocomplete="off"  required>
            <label for="lrno">Enter LR No</label>
        </div>
      </td>
      <td style="padding-top:2rem;">
        <button class="btn btn-sm btn-primary" type="submit">Save</button>
      </td>
      <td style="display:flex; 
    justify-content: center;
      align-items:center;">
        <a class="btn btn-warning btn-floating" target="_blank" href="product-single?txn=<?php echo $transaction['txnId']; ?>">
          <i class="fas fa-print"></i>
        </a>
      </td>
    </form>
    </tr>
  <?php
  $i++;
  }
  ?>
  </tbody>
  <tfoot>
  <tr>
      <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">Name
      </th>
      <th class="th-sm">Mobile
      </th>
      <th class="th-sm">Quantity
      </th>
      <th class="th-sm">Product
      </th>
      <th class="th-sm">Address
      </th>
      <th class="th-sm">Date
      </th>
      <th class="th-sm">Courier Service
      </th>
      <th class="th-sm">LR No.
      </th>
      <th class="th-sm">Save
      </th>
      <th class="th-sm">Print
      </th>

    </tr>
  </tfoot>
</table>
</div>


  <?php include('js.php') ?>
  <script type="text/javascript" src="./../js/addons/datatables.min.js"></script>

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


  </script>

</body>
</html>
