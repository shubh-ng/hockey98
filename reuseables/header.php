<?php include("modal.php"); ?>

<!--Navbar -->
<nav class="mb-1 navbar navbar-expand-lg navbar-dark primary-color lighten-1" style="position: sticky;
    top: 0;
    z-index: 1000;">
  <a class="navbar-brand" href="#"><img src="img/hockey.png?v=1.0.1" style="width: 40px;" alt=""></a>
  <button class="navbar-toggler z-depth-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555"
    aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent-555">
      <?php 
        if(!(isset($_SESSION['userStatus']) && $_SESSION['userStatus'] == 0)) {
      ?>
    <ul class="navbar-nav mr-auto sidebar-toggler">
      <li class="nav-item active">
        <!-- SideNav slide-out button -->
<a href="#" data-activates="slide-out" class="btn btn-primary button-collapse z-depth-0"><i
    class="fas fa-bars"></i></a>
      </li>
    </ul> 
    <?php } ?>
    <ul class="navbar-nav ml-auto nav-flex-icons">
      <li class="nav-item">
        <a class="nav-link waves-effect waves-light" id="user-status">
          <i class="fas fa-envelope"></i>
        </a>
      </li>
      <li class="nav-item avatar dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <img src="img/user.png" class="rounded-circle z-depth-0"
            alt="avatar image">
        </a>
        <div class="dropdown-menu dropdown-menu-lg-right dropdown-warning"
          aria-labelledby="navbarDropdownMenuLink-55">
          <a class="dropdown-item" href="#"><?php echo $_SESSION['fname']; ?></a>
          <a class="dropdown-item" href="profile"><i class="fas fa-edit mr-3"></i> Profile</a>
          <a class="dropdown-item" href="profile#change"><i class="fas fa-lock mr-3"></i> Change Password</a>
          <a class="dropdown-item logoutBtn" href="#"><i class="fas fa-sign-out-alt mr-3"></i> Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<!--/.Navbar -->