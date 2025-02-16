<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link">
    <span class="brand-text font-weight-light">MY IoT</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block"><?php echo $_SESSION["fullname"] ?></a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>
    
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="index.php" class="nav-link">
            <i class="nav-icon fas fa-chart-bar"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?page=dataSensor" class="nav-link">
            <i class="nav-icon fas fa-database "></i>
            <p>Data Sensor</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?page=dataActuator" class="nav-link">
            <i class="nav-icon fas fa-database"></i>
            <p>Data Aktuator</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?page=device" class="nav-link">
            <i class="nav-icon fas fa-mobile-alt"></i>
            <p>Devices</p>
          </a>
        </li>
        <?php if($_SESSION["role"] == "Admin"){ ?>
          <li class="nav-item">
            <a href="?page=user" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Users</p>
            </a>
          </li>
        <?php } ?>
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Log out</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>