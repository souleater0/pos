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
    // if (isset($_POST['action']) && $_POST['action'] !== 'login') {
    //     // Validate CSRF Token for sensitive actions
    //     if (!isset($_POST['csrf_token']) || !isValidCSRFToken($_POST['csrf_token'])) {
    //         echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    //         exit();
    //     }
    // }

    // Action handler
    switch ($_POST['action']) {
        case 'login':
            $loginResult = loginProcess($pdo);
            echo json_encode($loginResult);
            break;

        case 'fetchProducts':
            // Fetch products using the function
            $result = getProducts($pdo);
            echo json_encode($result, JSON_UNESCAPED_SLASHES);
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
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }

    exit();
}
