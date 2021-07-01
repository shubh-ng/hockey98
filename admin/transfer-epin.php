<?php
session_start();

if(!isset($_SESSION['adminId'])) {
  header("Location: /admin?redirectUrl=transfer-epin");
  exit();
} 

include("./../reuseables/modal.php");


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Admin | Transfer E-Pin</title>

  <?php include('css.php') ?>
   <link rel="stylesheet" href="./../css/addons/datatables.min.css">
  <style>
    #refresh:hover {
        cursor:pointer;
    }
    .status {
        display: flex;
        align-items: center;
        justify-content:center;
    }
    .status .active {
        color: white;
        border-radius: 25px;
        padding: 7px;
    }
    td {
        text-transform: capitalize;
    }
  </style>
</head>
<body>

<?php include('header.php') ?>

<div class="container mt-3">
  <!-- Material form withdraw -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Create and Transfer New E-Pin</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0 <?php echo $canWithdraw==0?'hidden':''; ?>" >

    <!-- Form -->
    <form class="needs-validation" id="epinTransferForm" novalidate style="color: #757575;">

            <!-- User ID -->
            <div class="md-form">
                <input type="text" id="ownerId" class="form-control" name="ownerId" autocomplete="off" required />
                <label for="ownerId">Enter User ID</label>
                <div class="valid-feedback">
                    Looks Good
                </div>
                <div class="invalid-feedback">
                    Please enter valid User ID
                </div>
            </div>

            
            <!-- E-Pin -->
            <div class="md-form">
                <select class="mdb-select" name="cost" required>
                        <option value="" disabled selected>Select E-Pin Amount</option>
                        <option value="60">₹ 60</option>
                </select>
                <div class="valid-feedback">
                    Looks Good
                </div>
                <div class="invalid-feedback">
                    Please select valid E-Pin Amount
                </div>
            </div>
            
            <!-- Name -->
            <div class="md-form">
                <input type="text" id="accountName" class="form-control" name="name" autocomplete="off" required readonly />
                <label for="accountName">Name</label>
            </div>


        <!-- Sign up button -->
        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Transfer</button>

    </form>
    <!-- Form -->

</div>

</div>
<!-- Material form register -->

</div>


<div class="container mt-3">
  <!-- Material table -->
<div class="card">

<h5 class="card-header primary-color white-text text-center py-4">
    <strong>Transfered E-Pins</strong>
</h5>

<!--Card content-->
<div class="card-body px-lg-5 pt-0 <?php echo $canWithdraw==0?'hidden':''; ?>" >
<table id="dtMaterialDesignExample" class="table table-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">Epin ID
      </th>
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">First Name
      </th>
      <th class="th-sm">Last Name
      </th>
      <th class="th-sm">Mobile
      </th>
        <th class="th-sm">Status
        </th>
    </tr>
  </thead>
  <tbody style="text-align:center;" id="usersTbl">
      <!-- USERS DATA WILL APPEND HERE -->
    <tr id="loader">
        <td colspan="7">
            <div class="spinner-border text-warning" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">Epin ID
      </th>
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">First Name
      </th>
      <th class="th-sm">Last Name
      </th>
      <th class="th-sm">Mobile
      </th>
        <th class="th-sm">Status
        </th>
    </tr>
  </tfoot>
</table>

</div>

</div>
<!-- Material table -->
</div>

  
  <?php include('js.php') ?>
  <script type="text/javascript" src="./../js/addons/datatables.min.js"></script>


  <script>
    mdbValidation();
    
    function titleCase(str) {
        return str
            .split(' ')
            .map((word) => word[0].toUpperCase() + word.slice(1).toLowerCase())
            .join(' ');
    }

    var name = document.querySelector("#accountName");

    //get name
    $("#ownerId").change(function(){
        var userId = this.value;
        var userInfo = new User({userId: userId});
        userInfo
        .getName()
        .then(function(data) {
            if(data.status == 1) {
                $("#accountName").val(data.name);
                $("#accountName + label").addClass("active");
            }
        })
    })
 
    // Check balance from backend
  $("#epinTransferForm").submit(function (e) {
    e.preventDefault();
    if (!e.target.checkValidity()) {
          return;
    }
    $('.loader-holder').show(); // show loader

    var data = e.target;
    var formData = new FormData();
    formData.append("ownerId", data.ownerId.value)
    formData.append("cost", data.cost.value)
    $.ajax({
        type: "POST",
        url: "./../api/createEpin",
        data: formData,
        contentType: false,
        processData: false,
        success: function (res) {
            console.log(res);
            if (res.status == 1) {
                // Withdraw request
                showModal({
                    title: "Success",
                    body: `<strong>${res.status_message}</strong>`,
                    type: "success",
                    position: "right",
                });
            } else if(res.status == 0) {
                showModal({
                    title: "Error",
                    body: res.status_message,
                    type: "danger",
                    position: "right",
                });
            }
        },
        complete: function (res) {
            console.log("Complete");
               $('.loader-holder').fadeOut();
        },
    });
  });


    async function appendDetails()  {
        const admin = new Admin();
        usersTbl = $("#usersTbl");
        res = await admin.getEpinTransferDetails();
        data = res.data;
        data.forEach(function(d, i){
            usersTbl.append(`
                <tr>
                    <td>${i+1}</td>
                    <td>${d.epinId}</td>
                    <td>${d.userId}</td>
                    <td>${d.firstName.toLowerCase()}</td>
                    <td>${d.lastName.toLowerCase()}</td>
                    <td>${d.mobile}</td>
                    <td style="text-transform:none!important;" class="status"> <span class="active ${d.status==1?'success-color':'danger-color'}">${d.status==1?"ACTIVE":"INACTIVE"}</span></td>
                </tr>
            `);
        })
        $("#loader").remove();
    }
    // Material Design example
    async function initDatatables() {
        await appendDetails();

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

    }
    
    $(document).ready(initDatatables);
    
    document.querySelector("#transfer-epin").classList.add('active'); //activating navbar link
  </script>

</body>
</html>
