<?php
$route = $_GET['route'] ?? 'dashboard';
switch ($route){
    case "dashboard":
        require 'admin/views/dashboard/dashboard.php';
        break;
    case "pos":
        require 'admin/views/pos-management/pos.php';
        break;
    case "product-management":
        require 'admin/views/product-management/product_list.php';
        break;
    case "view-product":
        require 'admin/views/product-management/product_view.php';
        break;
    case "category-management":
        require 'admin/views/category-management/category.php';
        break;
    case "brand-management":
        require 'admin/views/brand-management/brand.php';
        break;
    case "unit-management":
        require 'admin/views/unit-management/unit.php';
        break;
    case "tax-management":
        require 'admin/views/tax-management/tax.php';
        break;
    case "product-list":
        require 'admin/views/tax-management/product_list.php';
        break;
    case "stock-in":
        require 'admin/views/stock-management/stockin.php';
        break;
    case "stock-out":
        require 'admin/views/stock-management/picklist.php';
        break;
    case "validate-stockout":
        require 'admin/views/stock-management/validate_stockout.php';
        break;
    case "pending-stockin":
        require 'admin/views/stock-management/pending_stockin.php';
        break;
    case "pending-stockout":
        require 'admin/views/stock-management/pending_stockout.php';
        break;
    case "costing":
        require 'admin/views/stock-management/costing.php';
        break;
    case "waste":
        require 'admin/views/stock-management/waste.php';
        break;
    case "manage-supplier":
        require 'admin/views/vendor-management/supplier.php';
        break;
    case "manage-customer":
        require 'admin/views/vendor-management/customer.php';
        break;
    case "manage-transaction":
        require 'admin/views/vendor-management/receipt.php';
        break;
    case "inventory-stock-report":
        require 'admin/views/report/inventory_stock_report.php';
        break;
    case "stock-valuation-report":
        require 'admin/views/report/stock_valuation_report.php';
        break;
    case "stock-movement-report":
        require 'admin/views/report/stock_movement_report.php';
        break;
    case "product-history-report":
        require 'admin/views/report/product_transaction_report.php';
        break;
    case "supplier-transaction":
        require 'admin/views/report/supplier_transaction.php';
        break;
    case "customer-transaction":
        require 'admin/views/report/customer_transaction.php';
        break;
    case "summary-report":
        require 'admin/views/report/summary_report.php';
        break;
    case "detailed-report":
        require 'admin/views/report/detailed_report.php';
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
