<?php
session_start();

if(!isset($_SESSION['adminId'])) {
  header("Location: login?redirectUrl=dashboard");
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
  <title>Grow Me Always | Users</title>

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
  </style>
</head>
<body>

<?php include('header.php') ?>

<div class="container jumbotron mt-3">
  <h3>Users <i class="fas fa-sync-alt ml-2 text-warning" id="refresh"></i> </h3>

  <hr>
  <table id="dtMaterialDesignExample" class="table table-responsive" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th class="th-sm">Sr. No.
      </th>
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">Lead ID
      </th>
      <th class="th-sm">First Name
      </th>
      <th class="th-sm">Last Name
      </th>
      <th class="th-sm">Mobile
      </th>
      <th class="th-sm">Password
      </th>
            <th class="th-sm">Registered At
      </th>

        <th class="th-sm">Status
        </th>
      <th class="th-sm">Update
      </th>
      <th class="th-sm">Delete
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
      <th class="th-sm">User ID
      </th>
      <th class="th-sm">Lead ID
      </th>
      <th class="th-sm">First Name
      </th>
      <th class="th-sm">Last Name
      </th>
      <th class="th-sm">Mobile
      </th>
      <th class="th-sm">Password
      </th>
      <th class="th-sm">Registered At
      </th>
      <th class="th-sm">Status
      </th>
      <th class="th-sm">Update
      </th>
      <th class="th-sm">Delete
      </th>
    </tr>
  </tfoot>
</table>
  </div>


<!-- UPDATE FORM -->
<!-- Modal -->
<div class="modal fade" id="elegantModalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <!--Content-->
    <div class="modal-content form-elegant">
      <!--Header-->
      <div class="modal-header text-center">
        <h3 class="modal-title w-100 dark-grey-text font-weight-bold my-3" id="myModalLabel"><strong>Update <span class="text-info" id="userId">H09690</span></strong></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!--Body-->
      <div class="modal-body mx-4">
      <form id="updateFormByAdmin">
        <!--Body-->
        <h4>Personal Details</h4> <hr>
        <input type="hidden" name="userId" class="form-control validate">

        <div class="md-form">
          <input type="text" id="firstName" name="firstName" class="form-control validate">
          <label data-error="wrong" data-success="right" for="firstName">First Name</label>
        </div>

        <div class="md-form">
          <input type="text" id="lastName" name="lastName" class="form-control validate">
          <label data-error="wrong" data-success="right" for="lastName">Last Name</label>
        </div>

        <div class="md-form">
          <input type="text" id="mobile" name="mobile" class="form-control validate">
          <label data-error="wrong" data-success="right" for="mobile">Mobile</label>
        </div>

        <h4>Bank Details</h4> <hr>

        <div class="md-form">
          <input type="text" id="bankName" name="bankName" class="form-control validate">
          <label data-error="wrong" data-success="right" for="bankName">Bank Name</label>
        </div>

        <div class="md-form">
          <input type="text" id="branch" name="branch" class="form-control validate">
          <label data-error="wrong" data-success="right" for="branch">Branch</label>
        </div>

        <div class="md-form">
          <input type="text" id="accountNumber" name="accountNumber" class="form-control validate">
          <label data-error="wrong" data-success="right" for="accountNumber">Account Number</label>
        </div>

        <div class="md-form">
          <input type="text" id="ifsc" name="ifsc" class="form-control validate">
          <label data-error="wrong" data-success="right" for="ifsc">IFSC</label>
        </div>

        <h4>KYC Details</h4> <hr>
        <div class="md-form">
          <input type="text" id="pan" name="pan" class="form-control validate">
          <label data-error="wrong" data-success="right" for="pan">Pan</label>
        </div>

        <div class="text-center mb-3">
          <button type="submit" class="btn blue-gradient btn-block btn-rounded z-depth-1a">Update</button>
        </div>

      </form>
      </div>
    </div>
    <!--/.Content-->
  </div>
</div>
<!-- Modal -->
<!-- /UPDATE FORM -->

<!-- <div class="myloader text-gray" style="display:none;">
    <div class="spinner-border text-warning large" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div> -->

  <?php include('js.php') ?>

  <script type="text/javascript" src="./../js/addons/datatables.min.js"></script>

  <script type="text/javascript">
    async function appendUsers()  {
        const user = new User();
        usersTbl = $("#usersTbl");
        data = await user.getUsers();
        data.forEach(function(d, i){
            if(d.userId != 'HOCKEY98')
            usersTbl.append(`
                <tr id='user-${d.userId}'>
                    <td>${i+1}</td>
                    <td>${d.userId}</td>
                    <td>${d.clientCode || "Not Available"}</td>
                    <td>${d.firstName}</td>
                    <td>${d.lastName}</td>
                    <td>${d.mobile}</td>
                    <td style="text-transform:none!important;">${d.password}</td>
                    <td style="text-transform:none!important;">${d.createdAt}</td>
                    <td style="text-transform:none!important;" class="status"> <span class="active ${d.status==1?'success-color':'danger-color'}">${d.status==1?"ACTIVE":"INACTIVE"}</span></td>
                    <td><button class="btn btn-info btn-sm btn-rounded" onclick="openUpdateForm('${d.userId}')" >Update</button></td>
                    <td><button class="btn btn-danger btn-sm btn-rounded" onclick="deleteUser('${d.userId}')" >Delete</button></td>
                </tr>
            `);
        })
        $("#loader").remove();
    }
    // Material Design example
    async function initDatatables() {
        await appendUsers();

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

    function openUpdateForm(userId) {
        $("#userId").text(userId);
        $("input[name=userId]").val(userId);
        const user = new User({userId: userId});
        user.getUserInfo().then(function(res){
            formData = res.data;
            for(const [key, value] of Object.entries(formData)) {
                $(`input[name=${key}]`)?.val(value);
                $(`input[name=${key}] + label`).addClass("active");
            }
            $("#elegantModalForm").modal('show');
        });
    }

    function deleteUser(userId) {
        const user = new User({userId: userId});
        user.deleteUser().then(function(res){
            if(res.status == 1) {
                toastr["success"](`User ${userId} deleted successfully.`);
                $(`#user-${userId}`).remove();
            }else {
                toastr["danger"](`Problem in deleting user ${userId}`);
            }
        });
    }

    $("#refresh").click(function(){
      location.reload();
    });
    
    document.querySelector("#users").classList.add('active'); //activating navbar link
  </script>

</body>
</html>
