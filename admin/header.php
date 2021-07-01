<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark primary-color">

  <!-- Navbar brand -->
  <a class="navbar-brand" href="#">Admin | <?php echo $_SESSION['firstName'] ?></a>

  <!-- Collapse button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
    aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Collapsible content -->
  <div class="collapse navbar-collapse" id="basicExampleNav">

    <!-- Links -->
    <ul class="navbar-nav mr-auto">
      <li class="nav-item" id="dashboard">
        <a class="nav-link" href="dashboard">Dashboard
        </a>
      </li>
      <li class="nav-item" id="users">
        <a class="nav-link" href="users">Users</a>
      </li>
      <!-- <li class="nav-item" id="transfer-epin">
        <a class="nav-link" href="transfer-epin">Transfer E-Pin</a>
      </li> -->
      <li class="nav-item" id="withdraw">
        <a class="nav-link" href="withdraw">Withdraw</a>
      </li>
      <li class="nav-item" id="withdraw-report">
        <a class="nav-link" href="withdraw-report">Withdraw Report</a>
      </li>
    </ul>
    <!-- Links -->

    <form class="form-inline" action="logout">
      <div class="md-form my-0">
        <button class="btn btn-danger btn-sm">Logout</button>
      </div>
    </form>
  </div>
  <!-- Collapsible content -->

</nav>
<!--/.Navbar-->
