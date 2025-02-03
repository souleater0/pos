<?php
require 'admin/session_restrict.php';
$route = $_GET['route'] ?? 'home';
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EC - Inventory System</title>
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
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseSale" aria-expanded="<?php echo ($route == 'product-management') ? 'true' : 'false'; ?>" aria-controls="collapseUser">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">pos management</span>
            </li>
            <div class="collapse show" id="collapseSale">
            <li class="sidebar-item">
              <a class="sidebar-link active" href="index.php?route=pos" aria-expanded="false">
                <iconify-icon icon="mdi:network-pos"></iconify-icon>
                <span class="hide-menu">POS</span>
              </a>
            </li>
            </div>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_category') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_brand') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_units')||userHasPermission($pdo, $_SESSION["user_id"], 'manage_tax')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseProduct" aria-expanded="<?php echo ($route == 'product-management') ? 'true' : 'false'; ?>" aria-controls="collapseProduct">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">product management</span>
            </li>
            
            <div class="collapse <?php echo ($route == 'product-management'|| $route == 'view-product' || $route == 'category-management' || $route == 'brand-management' || $route == 'unit-management' || $route == 'tax-management') ? 'show' : ''; ?>" id="collapseProduct">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'view-product' )? 'active' : ''; ?>" href="index.php?route=product-management" aria-expanded="false">
                <iconify-icon icon="mdi:cart"></iconify-icon>
                <span class="hide-menu">Products</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_category')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=category-management" aria-expanded="false">
                <iconify-icon icon="material-symbols:category"></iconify-icon>
                <span class="hide-menu">Category</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_brand')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=brand-management" aria-expanded="false">
                <iconify-icon icon="mdi:tags"></iconify-icon>
                <span class="hide-menu">Brand</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_units')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=unit-management" aria-expanded="false">
                <iconify-icon icon="mdi:scale"></iconify-icon>
                <span class="hide-menu">Units</span>
              </a>
            </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_tax')){?>
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=tax-management" aria-expanded="false">
                <iconify-icon icon="tabler:receipt-tax"></iconify-icon>
                <span class="hide-menu">Tax</span>
              </a>
            </li>
            <?php } ?>
            </div>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_costing')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseStock" aria-expanded="false" aria-controls="collapseStock">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">cost management</span>
            </li>
            <div class="collapse <?php echo ($route == 'costing') ? 'show' : ''; ?>" id="collapseStock">
            <li class="sidebar-item">
              <a class="sidebar-link" href="index.php?route=costing" aria-expanded="false">
                <iconify-icon icon="fluent:money-calculator-24-regular"></iconify-icon>
                <span class="hide-menu">Costing</span>
              </a>
            </li>
            </div>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_user') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_role')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseVendor" aria-expanded="" aria-controls="collapseVendor">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">Vendor Management</span>
            </li>
            <div class="collapse <?php echo ($route == 'manage-supplier'|| $route == 'manage-customer'|| $route == 'manage-transaction') ? 'show' : ''; ?>" id="collapseVendor">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_supplier')){?>
              <li class="sidebar-item">
                <a class="sidebar-link <?php echo ($route == 'manage-supplier' )? 'active' : ''; ?>" href="index.php?route=manage-supplier" aria-expanded="false">
                  <iconify-icon icon="carbon:scis-transparent-supply"></iconify-icon>
                  <span class="hide-menu">Supplier</span>
                </a>
              </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_customer')){?>
              <li class="sidebar-item">
                <a class="sidebar-link <?php echo ($route == 'manage-customer' )? 'active' : ''; ?>" href="index.php?route=manage-customer" aria-expanded="false">
                  <iconify-icon icon="carbon:scis-transparent-supply"></iconify-icon>
                  <span class="hide-menu">Customer</span>
                </a>
              </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_transaction')){?>
              <li class="sidebar-item">
                <a class="sidebar-link <?php echo ($route == 'manage-transaction' )? 'active' : ''; ?>" href="index.php?route=manage-transaction" aria-expanded="false">
                  <iconify-icon icon="ic:baseline-receipt-long"></iconify-icon>
                  <span class="hide-menu">Transaction</span>
                </a>
              </li>
            <?php } ?>
            </div>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_summary_report') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_inventory_stock') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_stock_valuation') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_stock_movement') || userHasPermission($pdo, $_SESSION["user_id"], 'manage_product_history')){?>
            <li>
              <span class="sidebar-divider lg"></span>
            </li>
            <li class="nav-small-cap" data-bs-toggle="collapse" data-bs-target="#collapseReport" aria-expanded="<?php echo ($route == 'product-management') ? 'true' : 'false'; ?>" aria-controls="collapseUser">
              <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
              <span class="hide-menu text-uppercase">Reports</span>
            </li>
            <div class="collapse <?php echo ($route == 'summary-report'|| $route == 'inventory-stock-report'|| $route == 'stock-valuation-report' || $route == 'stock-movement-report' || $route == 'product-history-report') ? 'show' : ''; ?>" id="collapseReport">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_summary_report')){?>
              <li class="sidebar-item">
                <a class="sidebar-link <?php echo ($route == 'summary-report' )? 'active' : ''; ?>" href="index.php?route=summary-report" aria-expanded="false">
                  <iconify-icon icon="carbon:scis-transparent-supply"></iconify-icon>
                  <span class="hide-menu">Summary Report</span>
                </a>
              </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_inventory_stock')){?>
              <li class="sidebar-item">
                <a class="sidebar-link <?php echo ($route == 'inventory-stock-report' )? 'active' : ''; ?>" href="index.php?route=inventory-stock-report" aria-expanded="false">
                  <iconify-icon icon="ic:baseline-receipt-long"></iconify-icon>
                  <span class="hide-menu">Inventory Stock Report</span>
                </a>
              </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_stock_valuation')){?>
              <li class="sidebar-item">
                <a class="sidebar-link <?php echo ($route == 'stock-valuation-report' )? 'active' : ''; ?>" href="index.php?route=stock-valuation-report" aria-expanded="false">
                  <iconify-icon icon="carbon:scis-transparent-supply"></iconify-icon>
                  <span class="hide-menu">Stock Valuation Report</span>
                </a>
              </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_stock_movement')){?>
              <li class="sidebar-item">
                <a class="sidebar-link <?php echo ($route == 'stock-movement-report' )? 'active' : ''; ?>" href="index.php?route=stock-movement-report" aria-expanded="false">
                  <iconify-icon icon="carbon:scis-transparent-supply"></iconify-icon>
                  <span class="hide-menu">Stock Movement Report</span>
                </a>
              </li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product_history')){?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'product-history-report' )? 'active' : ''; ?>" href="index.php?route=product-history-report" aria-expanded="false">
                <iconify-icon icon="carbon:scis-transparent-supply"></iconify-icon>
                <span class="hide-menu">Product History Report</span>
              </a>
            </li>
            <?php } ?>
            <li class="sidebar-item">
              <a class="sidebar-link <?php echo ($route == 'supplier-transaction' )? 'active' : ''; ?>" href="index.php?route=supplier-transaction" aria-expanded="false">
                <iconify-icon icon="carbon:scis-transparent-supply"></iconify-icon>
                <span class="hide-menu">Supplier Report</span>
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
            <li class="nav-item">
              <a class="nav-link " href="javascript:void(0)">
                <iconify-icon icon="solar:bell-linear" class="fs-6"></iconify-icon>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
            </li>
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