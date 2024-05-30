<?php
error_reporting(~E_NOTICE);
session_start();
if (empty($_COOKIE['email'])) {
    // URL sistem yang diinginkan
  $system_url = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/../system/";
  header("Location: " . $system_url);
  exit();
}


$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ZManagement</title>
  <link rel="shortcut icon" type="image/png" href="./assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="./assets/css/styles.min.css" />
  <link rel="stylesheet" href="./assets/libs/jquery/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="./assets/css/animate.min.css" />
</head>
<style type="text/css">
  .image-container {
    max-width: 100%; /* Ensure the container doesn't exceed its parent's width */
    height: auto; /* Maintain the aspect ratio */
  }

  .image-container img {
    max-width: 100%; /* Make the image responsive within its container */
    height: 150px; /* Maintain the aspect ratio */
    border-radius: 10px;
  }

  .text-primary .badge {
    font-size: 12px;
  }

  /* Gaya untuk mengurangi ukuran font */
  .fullname {
    font-size: 14px; /* Sesuaikan dengan ukuran yang diinginkan */
  }
</style>

<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
        <?php if ($currentPage == 'order.php'): ?>
          <a class="text-primary position-relative cart" href="#" onclick="showCartPanel()">
            <i class="ti ti-shopping-cart" style="font-size: 20px;"></i>
            <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle" style="z-index: -1;" id="total-items">0</span>
          </a>
        <?php endif; ?>
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="card-title fw-semibold fullname" style="padding: 7px;"><?php echo $_COOKIE['username']; ?> <small>(<?php echo $_COOKIE['level']; ?>)</small></span>
            <?php
            $photoPath = '';
            if ($_COOKIE['level'] == 'driver') {
              $photoPath = empty($_COOKIE['photo']) ? 'assets/images/profile/user-1.jpg' : 'assets/images/profile_driver/' . $_COOKIE['photo'];
            } else {
              $photoPath = empty($_COOKIE['photo']) ? 'assets/images/profile/user-1.jpg' : 'assets/images/profile/' . $_COOKIE['photo'];
            }

  // Check if the file exists
            if (!file_exists($photoPath)) {
    $photoPath = $_COOKIE['level'] == 'driver' ? 'assets/images/profile/user-1.jpg' : 'assets/images/profile/user-1.jpg'; // Use default if not found
  }
  ?>
  <img src="<?php echo $photoPath; ?>" width="35" height="35" class="rounded-circle">
</a>



<div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
  <div class="message-body">
    <a href="setting_profile.php" class="d-flex align-items-center gap-2 dropdown-item">
      <p class="mb-0 fs-3">
        <i class="ti ti-user fs-6"></i>
        My Profile
      </p>
    </a>
    <a href="../server/ajax_register.php?act=logout" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
  </div>
</div>

</li>
</ul>
</div>
</nav>
</header>
