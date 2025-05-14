<?php
session_start();
// admin login to dashboard
if (!isset($_SESSION['admin'])) {
    header("Location: AdminLogin.php");
    exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - Resident Records</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body { 
      margin: 0; 
      font-family: Arial, sans-serif; 
    }
    .dashboardMainContainer { display: flex; height: 100vh; }
    .dashboard_sidebar { width: 30%; background-color: #f0f0f0; padding: 20px; }
    .dashboard_sidebar_user img { width: 80px; height: 80px; border-radius: 50%; }
    .dashboard_sidebar_user span { display: block; margin-top: 10px; font-weight: bold; }
    .dashboard_menu_lists { list-style: none; padding: 0; margin-top: 20px; }
    .dashboard_menu_lists li { margin: 10px 0; background-color: #fff; border: 2px solid #0000FF;
        border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
    .dashboard_menu_lists li a { display: block; padding: 15px; text-decoration: none;
        color: #333; font-weight: bold; font-size: 16px; border-radius: 10px; }
    .dashboard_content_container { width: 70%; padding: 20px; }
    .dashboard_topNav { display: flex; justify-content: space-between;
        background-color: #0000FF; padding: 10px 20px; color: white; }
    .dashboard_topNav a { color: white; text-decoration: none; margin-left: 10px; }
    .dashboard_content_main { margin-top: 20px; }
    .dashboard_logo { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      color: #fff;
      font-weight: bold;
    }

    .success {
      background-color: #28a745; /* Green */
    }
    .error {
      background-color: #dc3545; /* Red */
    }

  </style>
</head>
<body>
  <div class="dashboardMainContainer">
    <div class="dashboard_sidebar">
      <h3 class="dashboard_logo">Resident Records</h3>
      <div class="dashboard_sidebar_user">
        <i class="fa-solid fa-user-circle fa-6x"></i>
        <span><?php echo $_SESSION['admin']; ?></span>
      </div>
      <div class="dashboard_sidebar_menu">
        <ul class="dashboard_menu_lists">
          <li><a href="dashboard.php?page=pending_residents"><i class="fa fa-clock"></i> Pending Entries</a></li>
          <li><a href="dashboard.php?page=approved_residents"><i class="fa fa-check"></i> Approved Residents</a></li>
          <li><a href="dashboard.php?page=manage_residents"><i class="fa fa-users"></i> Manage Dependents</a></li>
          <li><a href="dashboard.php?page=brgy"><i class="fa fa-map-marker-alt"></i> Manage Barangays</a></li>
          <li><a href="dashboard.php?page=view_household"><i class="fa fa-home"></i> View Households</a></li>
        </ul>
      </div>
    </div>

    <div class="dashboard_content_container">
      <div class="dashboard_topNav">
        <a href="#"><i class="fa fa-bars"></i></a>
        <a href="logout.php"><i class="fa fa-power-off"></i> Log-out</a>
      </div>
      <div class="dashboard_content">
        <div class="dashboard_content_main">
          <?php
            $allowed_pages = [
              'pending_residents', 'approved_residents',
              'manage_residents', 'brgy',
              'view_household', 'edit'
            ];

            if ($page === 'home') {
              echo "<h2>Welcome to the Resident Records Dashboard</h2>";
            } elseif (in_array($page, $allowed_pages)) {
              include $page . '.php';
            } else {
              echo "<h2>Page not found</h2>";
            }
          ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
