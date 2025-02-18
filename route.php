<?php
$route = $_GET['route'] ?? 'dashboard';
switch ($route){
    case "dashboard":
        require 'admin/views/dashboard/dashboard.php';
        break;
    case "pos":
        require 'admin/views/pos-management/pos.php';
        break;
    case "receipt":
        require 'admin/views/pos-management/receipt.php';
        break;
    case "product-management":
        require 'admin/views/product-management/product.php';
        break;
    case "ingredient":
        require 'admin/views/product-management/ingredient.php';
        break;
    case "category":
        require 'admin/views/product-management/category.php';
        break;
    case "unit-management":
        require 'admin/views/product-management/unit.php';
        break;
    case "discount":
        require 'admin/views/product-management/discount.php';
        break;
    case "product-waste":
        require 'admin/views/waste-management/product_waste.php';
        break;
    case "ingredient-waste":
        require 'admin/views/waste-management/ingredient_waste.php';
        break;
    case "report":
        require 'admin/views/report/report.php';
        break;
    case "audit-trail":
        require 'admin/views/report/audit.php';
        break;
    case "user-management":
        require 'admin/views/user-management/users.php';
        break;
    case "role-management":
        require 'admin/views/user-management/role.php';
        break;
    case "settings":
        require 'admin/views/user-configuration/settings.php';
        break;
    default:
        require 'admin/views/dashboard/dashboard.php';
}
?>
