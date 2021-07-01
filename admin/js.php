<div class="loader-holder">
  <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
</div>

  <!-- jQuery -->
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="../js/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="../js/mdb.min.js"></script>

  <!-- Your custom scripts (optional) -->
  <script type="text/javascript">

  // ENVIRONMENT VARIABLES
  origin = location.origin;
  if(origin.includes("localhost")) {
    origin += "/hockey98";
  }
  var baseUrl = origin+"/api";
 
  $(document).ready(function() {
    // Material Select Initialization
    $('.mdb-select').materialSelect();
    // $('.mdb-select.select-wrapper .select-dropdown').val("").removeAttr('readonly').attr("placeholder",
    // "Choose").prop('required', true).addClass('form-control').css('background-color', '#fff');
  });

  function mdbValidation() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
        }, false);
    });
  }

  // * SHOW LOADER ON LOAD
  $(window).on('load', function(){
   // PAGE IS FULLY LOADED  
   // FADE OUT YOUR OVERLAYING DIV
   $('.loader-holder').fadeOut();
  });

</script>


  <!-- SERVICES -->
  <script src="../services/auth.js"></script>
  <script src="../services/user.js?v=0.0.0"></script>
  <script src="../services/epin.js"></script>
  <script src="../services/stats.js"></script>
  <script src="../services/admin.js"></script>

  <!-- JS -->
  <script src="../uijs/util.js"></script>
  <script src="../uijs/api.js"></script>


