<?php
require 'admin/session_restrict.php';
$route = $_GET['route'] ?? 'home';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS-Takoyame Takoyaki</title>
  <link rel="shortcut icon" type="image/png" href="assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link rel="stylesheet" href="assets/css/table.css">
  <style>
    .nav-small-cap{
      cursor: pointer;
    }
    @media print {
        body {
            margin: 0;
            padding: 0;
        }
        #printArea {
            page-break-inside: avoid;
        }
    }
  </style>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/sidebarmenu.js"></script>
  <script src="assets/js/app.min.js"></script>
  <script src="assets/libs/apexcharts/dist/apexcharts.min.js"></script>
  <script src="assets/libs/simplebar/dist/simplebar.js"></script>
  <!-- <script src="assets/js/dashboard.js"></script> -->
  <!-- fontawesome -->
  <!-- <link rel="stylesheet" href="assets/css/fontawesome.min.css"> -->
  <link rel="stylesheet" href="assets/css/all.min.css">
  <script src="assets/js/all.min.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <!-- DATATABLE -->
  <link href="assets/js/datatables.min.css" rel="stylesheet">
  <script src="assets/js/datatables.min.js"></script>
  <!-- bootstrap select -->
    <!-- Latest BS-Select compiled and minified CSS/JS -->
    <link rel="stylesheet" href="assets/libs/bootstrap-select/dist/css/bootstrap-select.min.css">
    <script src="assets/libs/bootstrap-select/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <!-- TOASTR -->
  <link rel="stylesheet" href="assets/libs/toastr/css/toastr.min.css">
  <script src="assets/libs/toastr/js/toastr.min.js"></script>
  <script src="assets/js/all.js"></script>

  <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
  <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
  <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

  <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
  <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
  <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
  <!-- SWEETALERT -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="./" class="text-nowrap logo-img">
            <img src="assets/images/logos/takome_logo.png" height="150" width="200" alt="" />
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_dashboard')){?>
            <li class="nav-small-cap">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu">Home</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=dashboard" aria-expanded="false">
                <iconify-icon icon="solar:widget-add-line-duotone"></iconify-icon>
                <span class="hide-menu">Dashboard</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_pos') ||userHasPermission($pdo, $_SESSION["user_id"], 'manage_receipt')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseSale" aria-expanded="<?php echo ($route == 'pos') ? 'true' : 'false'; ?>" aria-controls="collapseUser">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">pos management</span>
            </li>
            <div class="collapse <?php echo ($route == 'pos'|| $route == 'receipt')? 'show' : ''; ?>" " id="collapseSale">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_pos')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'pos' )? 'active' : ''; ?>" " href="index.php?route=pos" aria-expanded="false">
                <iconify-icon icon="mdi:network-pos"></iconify-icon>
                <span class="hide-menu">POS</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_receipt')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'receipt' )? 'active' : ''; ?>" " href="index.php?route=receipt" aria-expanded="false">
                <iconify-icon icon="lucide:receipt"></iconify-icon>
                <span class="hide-menu">RECEIPT</span>
              </a>
            </li>
            <?php } ?>
            </div>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_category') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_unit')||userHasPermission($pdo, $_SESSION["user_id"], 'manage_discount')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseProduct" aria-expanded="<?php echo ($route == 'product-management'|| $route == 'ingredient' || $route == 'category' || $route == 'unit-management' || $route == 'discount') ? 'true' : 'false'; ?>" aria-controls="collapseProduct">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">product management</span>
            </li>
            <div class="collapse <?php echo ($route == 'product-management'|| $route == 'ingredient' || $route == 'category' || $route == 'unit-management' || $route == 'discount') ? 'show' : ''; ?>" id="collapseProduct">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'view-product' )? 'active' : ''; ?>" href="index.php?route=product-management" aria-expanded="false">
                <iconify-icon icon="mdi:food-turkey"></iconify-icon>
                <span class="hide-menu">Products</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_ingredient')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=ingredient" aria-expanded="false">
                <iconify-icon icon="fluent:bowl-salad-20-filled"></iconify-icon>
                <span class="hide-menu">Ingredients</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_category')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=category" aria-expanded="false">
                <iconify-icon icon="material-symbols:category"></iconify-icon>
                <span class="hide-menu">Categories</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_unit')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=unit-management" aria-expanded="false">
                <iconify-icon icon="mdi:scale"></iconify-icon>
                <span class="hide-menu">Units</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_discount')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=discount" aria-expanded="false">
                <iconify-icon icon="hugeicons:discount"></iconify-icon>
                <span class="hide-menu">Discounts</span>
              </a>
            </li>
            <?php } ?>
            </div>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product_waste') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_ingredient_waste')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseWaste" aria-expanded="<?php echo ($route == 'ingredient-waste') ? 'true' : 'false'; ?>" aria-controls="collapseUser">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">waste management</span>
            </li>
            <div class="collapse <?php echo ($route == 'product-waste' || $route == 'ingredient-waste')? 'show' : ''; ?>" " id="collapseWaste">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product_waste')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'product-waste' )? 'active' : ''; ?>" " href="index.php?route=product-waste" aria-expanded="false">
                <iconify-icon icon="hugeicons:waste-restore"></iconify-icon>
                <span class="hide-menu">Product Waste</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_ingredient_waste')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'ingredient-waste' )? 'active' : ''; ?>" " href="index.php?route=ingredient-waste" aria-expanded="false">
                <iconify-icon icon="guidance:waste"></iconify-icon>
                <span class="hide-menu">Ingredient Waste</span>
              </a>
            </li>
            <?php } ?>
            </div>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_report')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseReport" aria-expanded="<?php echo ($route == 'ingredient-waste') ? 'true' : 'false'; ?>" aria-controls="collapseUser">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">report management</span>
            </li>
            <div class="collapse <?php echo ($route == 'report')? 'show' : ''; ?>" " id="collapseReport">
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'report' )? 'active' : ''; ?>" " href="index.php?route=report" aria-expanded="false">
                <iconify-icon icon="fluent-mdl2:c-r-m-report"></iconify-icon>
                <span class="hide-menu">Report</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'audit-trail' )? 'active' : ''; ?>" " href="index.php?route=audit-trail" aria-expanded="false">
                <iconify-icon icon="fluent-mdl2:c-r-m-report"></iconify-icon>
                <span class="hide-menu">Audit Trail</span>
              </a>
            </li>
            </div>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_user') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_role')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseUser" aria-expanded="<?php echo ($route == 'product-management') ? 'true' : 'false'; ?>" aria-controls="collapseUser">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">user management</span>
            </li>
            <div class="collapse <?php echo ($route == 'user-management'|| $route == 'role-management') ? 'show' : ''; ?>" id="collapseUser">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_user')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'user-management' )? 'active' : ''; ?>" href="index.php?route=user-management" aria-expanded="false">
                <iconify-icon icon="mdi:cart"></iconify-icon>
                <span class="hide-menu">User</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_role')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=role-management" aria-expanded="false">
                <iconify-icon icon="material-symbols:category"></iconify-icon>
                <span class="hide-menu">Role</span>
              </a>
            </li>
            <?php } ?>
            </div>
            <?php } ?>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link " href="javascript:void(0)">
                <iconify-icon icon="solar:bell-linear" class="fs-6"></iconify-icon>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li> -->
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              <li class="nav-item dropdown">
                <a class="nav-link " href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-user fs-6"></i>
                      <p class="mb-0 fs-3">My Profile</p>
                    </a>
                    <a href="index.php?route=settings" class="d-flex align-items-center gap-2 dropdown-item">
                      <i class="ti ti-mail fs-6"></i>
                      <p class="mb-0 fs-3">Settings</p>
                    </a>
                    <a href="admin/process/logout.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->