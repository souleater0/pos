<?php
session_start();
require_once '../../config.php';
require_once 'function.php';

// CSRF Token Check (only for actions that need it)
function isValidCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

// Permission Check for Specific Actions
function checkPermission($pdo, $action) {
    if (!isset($_SESSION['user_id']) || !userHasPermission($pdo, $_SESSION['user_id'], $action)) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }
}

// Add CSRF token to session if it's not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));  // Generate a new token if not set
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    
    // Skip CSRF token validation on login or actions that don't require it
    if (isset($_POST['action']) && $_POST['action'] !== 'login') {
        // Validate CSRF Token for sensitive actions
        if (!isset($_POST['csrf_token']) || !isValidCSRFToken($_POST['csrf_token'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
            exit();
        }
    }

    // Action handler
    switch ($_POST['action']) {
        case 'login':
            $loginResult = loginProcess($pdo);
            echo json_encode($loginResult);
            break;

        case 'fetchDashboardData':    
            // Call the function to get the dashboard data based on the provided date range
            $result = fetchDashboardData($pdo);
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
            break;
            
        case 'fetchProducts':
            // Fetch products using the function
            $result = getProducts($pdo);
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
            break;

        case 'fetchInvoiceNo':
            // Fetch products using the function
            $result = getNextInvoiceNumber($pdo);
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
            break;

        case 'addTransaction':
            // Fetch products using the function
            $result = addTransaction($pdo);
            echo json_encode($result);
            break;
        
        case 'viewReceipt':
            checkPermission($pdo, 'manage_receipt');
            // Fetch products using the function
            $result = viewReceipt($pdo);
            echo json_encode($result);
            break;

        case 'voidReceipt':
            checkPermission($pdo, 'void_receipt');
            // Fetch products using the function
            $result = voidReceipt($pdo);
            echo json_encode($result);
            break;

        case 'addProduct':
            checkPermission($pdo, 'create_product');
            $result = addProduct($pdo);  // The function handles validation
            echo json_encode($result);
            break;

        case 'updateProduct':
            checkPermission($pdo, 'update_product');
            $result = updateProduct($pdo);  // The function handles validation
            echo json_encode($result);
            break;

        case 'deleteProduct':
            checkPermission($pdo, 'delete_product');
            $result = deleteProduct($pdo);  // The function handles validation
            echo json_encode($result);
            break;

        case 'viewBatches':
            checkPermission($pdo, 'manage_ingredient');
            $result = viewBatches($pdo);
            echo json_encode($result);
            break;            

        case 'addIngredient':
            checkPermission($pdo, 'create_ingredient');
            $result = AddIngredient($pdo);
            echo json_encode($result);
            break;

        case 'updateIngredient':
            checkPermission($pdo, 'update_ingredient');
            $result = updateIngredient($pdo);
            echo json_encode($result);
            break;

        case 'deleteIngredient':
            checkPermission($pdo, 'delete_ingredient');
            $result = deleteIngredient($pdo);
            echo json_encode($result);
            break;

        case 'addBatch':
            checkPermission($pdo, 'create_ingredient'); // Check for the permission to add a batch
            $result = addBatch($pdo);
            echo json_encode($result);
            break;
        case 'moveToWaste':
            checkPermission($pdo, 'create_ingredient_waste'); // Ensure user has permission to manage ingredients
            $result = moveToWaste($pdo);
            echo json_encode($result);
            break;
            
        case 'addCategory':
            checkPermission($pdo, 'create_category');
            $result = AddCategory($pdo);
            echo json_encode($result);
            break;

        case 'updateCategory':
            checkPermission($pdo, 'update_category');
            $result = updateCategory($pdo);
            echo json_encode($result);
            break;

        case 'deleteCategory':
            checkPermission($pdo, 'delete_category');
            $result = deleteCategory($pdo);
            echo json_encode($result);
            break;

        case 'addUnit':
            checkPermission($pdo, 'create_unit');
            $result = addUnit($pdo);
            echo json_encode($result);
            break;

        case 'updateUnit':
            checkPermission($pdo, 'update_unit');
            $result = updateUnit($pdo);
            echo json_encode($result);
            break;

        case 'deleteUnit':
            checkPermission($pdo, 'delete_unit');
            $result = deleteUnit($pdo);
            echo json_encode($result);
            break;
        case 'addDiscount':
            checkPermission($pdo, 'create_discount');
            $result = addDiscount($pdo);
            echo json_encode($result);
            break;

        case 'updateDiscount':
            checkPermission($pdo, 'update_discount');
            $result = updateDiscount($pdo);
            echo json_encode($result);
            break;

        case 'deleteDiscount':
            checkPermission($pdo, 'delete_discount');
            $result = deleteDiscount($pdo);
            echo json_encode($result);
            break;
            
        case 'getUserPermissionbyID':
            // Fetch products using the function
            $result = getRolePermissions($pdo);
            echo json_encode($result);
            break;

        case 'addProductWaste':
            checkPermission($pdo, 'create_product_waste');
            $result = addProductWaste($pdo);
            echo json_encode($result);
            break;
        
        case 'updateProductWaste':
            checkPermission($pdo, 'update_product_waste');
            $result = updateProductWaste($pdo);
            echo json_encode($result);
            break;
        
        case 'deleteProductWaste':
            checkPermission($pdo, 'delete_product_waste');
            $result = deleteProductWaste($pdo);
            echo json_encode($result);
            break;

        case 'addIngredientWaste':
            checkPermission($pdo, 'create_ingredient_waste');
            $result = addIngredientWaste($pdo);
            echo json_encode($result);
            break;
        
        case 'updateIngredientWaste':
            checkPermission($pdo, 'update_ingredient_waste');
            $result = updateIngredientWaste($pdo);
            echo json_encode($result);
            break;
        
        case 'deleteIngredientWaste':
            checkPermission($pdo, 'delete_ingredient_waste');
            $result = deleteIngredientWaste($pdo);
            echo json_encode($result);
            break;

        case 'generate_report':
            checkPermission($pdo, 'manage_report');
            // Call the function to generate the report, it will now handle POST data internally
            $result = generateReport($pdo);
            // Return the result (HTML content of the report)
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
            break;
        
        case 'addUser':
            checkPermission($pdo, 'create_user');
            $result = addUser($pdo);  
            echo json_encode($result);
            break;

        case 'updateUser':
            checkPermission($pdo, 'update_user');
            $result = updateUser($pdo);  
            echo json_encode($result);
            break;

        case 'updateUserPassword':
            checkPermission($pdo, 'update_user');
            $result = updateUserPassword($pdo);  
            echo json_encode($result);
            break;
    
        case 'deleteUser':
            checkPermission($pdo, 'delete_user');
            $result = deleteUser($pdo); 
            echo json_encode($result);
            break;

        case 'addRole':
            checkPermission($pdo, 'create_role');
            $result = addRole($pdo); 
            echo json_encode($result);
            break;

        case 'updateRole':
            checkPermission($pdo, 'update_role');
            $result = updateRole($pdo);  // Your existing updateRole function
            echo json_encode($result);
            break;
    
        case 'deleteRole':
            checkPermission($pdo, 'delete_role');
            $result = deleteRole($pdo);  // Your existing deleteRole function
            echo json_encode($result);
            break;
            

        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }

    exit();
}
