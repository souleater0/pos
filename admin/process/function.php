<?php
function userHasPermission($pdo, $userId, $permissionName) {
    try {
        // Prepare the SQL query to check if the user has the specified permission
        $sql = "SELECT 1
                FROM permissions a
                JOIN role_permissions b ON b.permission_id = a.id
                JOIN roles c ON c.id = b.role_id
                JOIN users d ON d.role_id = c.id
                WHERE d.id = :user_id AND a.permission_name = :permission_name
                LIMIT 1"; // We use LIMIT 1 as we just need to check existence

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'permission_name' => $permissionName]);

        // If a result is found, return true (permission exists), otherwise false
        return $stmt->fetchColumn() !== false;

    } catch (PDOException $e) {
        // Handle database connection error and return false (as no permission found in case of error)
        error_log("Error in userHasPermission: " . $e->getMessage());
        return false; // Return false in case of an error (no permission granted)
    }
}
function getModules($pdo){
    try {
        $query = "SELECT * FROM modules";
        $stmt = $pdo->prepare($query);

        $stmt ->execute();
        $modules = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        return $modules;
    }catch(PDOException $e){
                // Handle database connection error
        echo "Error: " . $e->getMessage();
        return array(); // Return an empty array if an error occurs
    }
}
function getModulePermissions($pdo , $moduleID){
    try {
        $query = "SELECT * FROM permissions WHERE module_id = :module_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['module_id' => $moduleID]);
        $permissions = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        return $permissions;
    }catch(PDOException $e){
                // Handle database connection error
        echo "Error: " . $e->getMessage();
        return array(); // Return an empty array if an error occurs
    }
}
function getRolePermissions($pdo) {
    $roleId = $_POST['role_id'];
    try {
        // Fetch the permissions associated with the role
        $stmt = $pdo->prepare("SELECT permission_id FROM role_permissions WHERE role_id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
        $permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Fetch the role name (assuming you want to display it as well)
        $stmt = $pdo->prepare("SELECT role_name FROM roles WHERE id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
        $roleName = $stmt->fetchColumn();

        return array('success' => true, 'role_name' => $roleName, 'permissions' => $permissions);
    } catch (Exception $e) {
        return array('success' => false, 'message' => 'Failed to fetch role permissions: ' . $e->getMessage());
    }
}
function getProducts($pdo) {
    try {
        // Join the 'products' table with the 'category' table, using 'AS' to alias column names
        $stmt = $pdo->prepare(
            "SELECT 
                p.product_id AS id, 
                p.product_name AS name, 
                p.product_price AS price, 
                p.product_discount AS discount, 
                p.product_image AS image, 
                c.category_name AS category_name
            FROM product p
            JOIN category c ON p.category_id = c.category_id
            WHERE p.status = 0"  // Assuming you only want active products (status = 0)
        );
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if products exist
        if (empty($products)) {
            return json_encode(['success' => false, 'message' => 'No products found'], JSON_UNESCAPED_SLASHES);
        }

        // Format product data with discount and modify category to match your requirement
        foreach ($products as &$product) {
            $product['discount'] = (float) $product['discount'];
            // Ensure image path is correctly returned without escaping slashes
            $product['image'] = $product['image']; // No need for further manipulation
            $product['price'] = (float) $product['price'];  // Ensure price is a float

            // Adjust the category to match your example (all, main, addons)
            $product['category'] = $product['category_name'];
            
            // Remove unnecessary fields
            unset($product['category_name']);  // 'category_name' is no longer needed
        }

        // Return JSON response with proper encoding and no escaped slashes
        return ['success' => true, 'products' => $products];  // Return the data structure directly, without encoding yet
    } catch (PDOException $e) {
        return json_encode(['success' => false, 'message' => 'Error fetching products: ' . $e->getMessage()], JSON_UNESCAPED_SLASHES);
    }
}




function getCategories($pdo) {
    $stmt = $pdo->prepare("SELECT category_id, category_name FROM category");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getUnits($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM unit");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getDiscounts($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM discount");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPaymentTypes($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM payment_type");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getRole($pdo){
    try {
        $query = "SELECT * FROM roles";
        $stmt = $pdo->prepare($query);

        $stmt ->execute();
        $roles = $stmt -> fetchAll(PDO::FETCH_ASSOC);
        return $roles;
    }catch(PDOException $e){
                // Handle database connection error
        echo "Error: " . $e->getMessage();
        return array(); // Return an empty array if an error occurs
    }
}
function loginProcess($pdo) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username= ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(); 


    if(!$user) {
        return array(
            'success' => false,
            'message' => 'No User Found!'
        );
    } elseif (!$user['isEnabled']) {
        return array(
            'success' => false,
            'message' => 'Account is disabled.'
        );
    }

    if (password_verify($password, $user["password"])) {
        // Login successful
        // session_start();
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["display_name"] = $user["display_name"];
        return array(
            'success' => true,
            'message' => 'Login successful.',
            'redirectUrl' => '../index.php?route=dashboard'
        );
    } else {
        return array(
            'success' => false,
            'message' => 'Invalid Credentials!'
        );
    }
}
// PRODUCT START
function addProduct($pdo) {
    try {
        // Get the product details from POST data
        $product_name = trim($_POST['product_name'] ?? '');
        $category_id = intval($_POST['product_category'] ?? 0);
        $sell_price = floatval($_POST['sell_price'] ?? 0);
        $product_discount = floatval($_POST['product_discount'] ?? 0);
        $image = $_FILES['product_image'] ?? null;

        // Validation: Check if product name, category, and price are valid
        if (empty($product_name)) {
            return ['success' => false, 'message' => 'Product name cannot be empty'];
        }

        if (empty($category_id)) {
            return ['success' => false, 'message' => 'Category is required'];
        }

        if ($sell_price <= 0) {
            return ['success' => false, 'message' => 'Selling Price must be greater than zero'];
        }

        // Check if product already exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM product WHERE product_name = :product_name");
        $stmt_check->bindParam(':product_name', $product_name);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Product already exists'];
        }

        // Handle image upload
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            // Upload the image and set the path accordingly
            $image_path = 'assets/images/products/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], '../../'.$image_path);
        } else {
            // If no image uploaded, set the default image path
            $image_path = 'assets/images/products/image.png';
        }

        // Debugging: Log SQL query and parameters to ensure they are correct
        error_log("Inserting product: name = $product_name, price = $sell_price, discount = $product_discount, image_path = $image_path, category_id = $category_id");

        // Insert new product into the database
        $stmt = $pdo->prepare("INSERT INTO product (product_name, product_price, product_discount, product_image, category_id, status, created_at, updated_at) 
                               VALUES (:product_name, :product_price, :product_discount, :product_image, :category_id, 0, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':product_price', $sell_price);
        $stmt->bindParam(':product_discount', $product_discount);
        $stmt->bindParam(':product_image', $image_path);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Product added successfully'];
        } else {
            // Log the error message if insert fails
            $errorInfo = $stmt->errorInfo();
            error_log("SQL Error: " . implode(", ", $errorInfo));
            return ['success' => false, 'message' => 'Failed to add product'];
        }
    } catch (PDOException $e) {
        // Log PDO exception message for debugging
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}


function updateProduct($pdo) {
    try {
        // Get the product details from POST data
        $product_id = intval($_POST['update_id'] ?? 0);
        $product_name = trim($_POST['product_name'] ?? '');
        $category_id = intval($_POST['product_category'] ?? 0);
        $purchase_price = floatval($_POST['sell_price'] ?? 0);
        $product_discount = floatval($_POST['product_discount'] ?? 0);
        $image = $_FILES['product_image'] ?? null;

        // Validation: Check if fields are empty or invalid
        if (empty($product_id) || empty($product_name)) {
            return ['success' => false, 'message' => 'Product ID and name are required'];
        }

        if (empty($category_id)) {
            return ['success' => false, 'message' => 'Category is required'];
        }

        if ($purchase_price <= 0) {
            return ['success' => false, 'message' => 'Purchase price must be greater than zero'];
        }

        // Check if product exists
        $stmt_check = $pdo->prepare("SELECT * FROM product WHERE product_id = :product_id");
        $stmt_check->bindParam(':product_id', $product_id);
        $stmt_check->execute();

        $product = $stmt_check->fetch();

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        // Handle image upload if a new one is provided, otherwise keep the old one
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            // Upload the image and set the path accordingly
            $image_path = 'assets/images/products/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], '../../'.$image_path);
        } else {
            // If no image uploaded, keep the old image path
            $image_path = 'assets/images/products/image.png';
        }

        // Update product in the database
        $stmt = $pdo->prepare("UPDATE product 
                               SET product_name = :product_name, product_price = :product_price, product_discount = :product_discount, product_image = :product_image, category_id = :category_id, updated_at = CURRENT_TIMESTAMP 
                               WHERE product_id = :product_id");
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':product_price', $purchase_price);
        $stmt->bindParam(':product_discount', $product_discount);
        $stmt->bindParam(':product_image', $image_path);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':product_id', $product_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Product updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update product'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}


// Function to delete a product
function deleteProduct($pdo) {
    try {
        // Get the product ID from POST data
        $product_id = intval($_POST['product_id'] ?? 0);

        // Validation: Check if product ID is empty
        if (empty($product_id)) {
            return ['success' => false, 'message' => 'Product ID is required'];
        }

        // Check if the product exists
        $stmt_check = $pdo->prepare("SELECT * FROM product WHERE product_id = :product_id");
        $stmt_check->bindParam(':product_id', $product_id);
        $stmt_check->execute();

        $product = $stmt_check->fetch();

        if (!$product) {
            return ['success' => false, 'message' => 'Product not found'];
        }

        // Delete the product from the database
        $stmt = $pdo->prepare("DELETE FROM product WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $product_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Product deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete product'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
// PRODUCT END

// INGREDIENT START
// View Batches
function viewBatches($pdo) {
    try {
        $ingredient_id = intval($_POST['ingredient_id'] ?? 0);

        // Validation: Ensure ingredient ID is provided
        if (empty($ingredient_id)) {
            return ['success' => false, 'message' => 'Ingredient ID is required'];
        }

        // Fetch batches for the given ingredient ID
        $stmt = $pdo->prepare("SELECT * FROM ingredient_batch WHERE ingredient_id = :ingredient_id");
        $stmt->bindParam(':ingredient_id', $ingredient_id);
        $stmt->execute();

        // Fetch all batches
        $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Modify barcode value to "None" if empty
        foreach ($batches as &$batch) {
            if (empty($batch['barcode'])) {
                $batch['barcode'] = 'No Barcode';
            }
        }

        if ($batches) {
            return ['success' => true, 'data' => $batches];
        } else {
            return ['success' => false, 'message' => 'No items found for this ingredient'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}



function addIngredient($pdo) {
    try {
        // Get input values
        $ingredient_name = trim($_POST['ingredient_name'] ?? '');
        $price_per_unit = trim($_POST['price_per_unit'] ?? '');
        $warn_qty = intval($_POST['warn_qty'] ?? 0);
        $unit_id = intval($_POST['unit_type'] ?? 0);

        // Validation: Check if required fields are empty
        if (empty($ingredient_name)) {
            return ['success' => false, 'message' => 'Ingredient name is required'];
        }
        if (empty($price_per_unit)) {
            return ['success' => false, 'message' => 'Price per unit is required'];
        }
        if ($warn_qty < 0) {  // Warn quantity cannot be negative
            return ['success' => false, 'message' => 'Warning quantity cannot be negative'];
        }
        if (empty($unit_id)) {
            return ['success' => false, 'message' => 'Unit is required'];
        }

        // Check if ingredient already exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM ingredient WHERE ingredient_name = :ingredient_name");
        $stmt_check->bindParam(':ingredient_name', $ingredient_name);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Ingredient already exists'];
        }

        // Insert new ingredient
        $stmt = $pdo->prepare("INSERT INTO ingredient (ingredient_name, price_per_unit, warn_qty, unit_id) 
                               VALUES (:ingredient_name, :price_per_unit, :warn_qty, :unit_id)");
        $stmt->bindParam(':ingredient_name', $ingredient_name);
        $stmt->bindParam(':price_per_unit', $price_per_unit);
        $stmt->bindParam(':warn_qty', $warn_qty);
        $stmt->bindParam(':unit_id', $unit_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Ingredient added successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to add ingredient'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

// Update Ingredient
function updateIngredient($pdo) {
    try {
        $ingredient_id = intval($_POST['update_id'] ?? 0);
        $ingredient_name = trim($_POST['ingredient_name'] ?? '');
        $price_per_unit = trim($_POST['price_per_unit'] ?? '');
        $warn_qty = intval($_POST['warn_qty'] ?? 0);
        $unit_id = intval($_POST['unit_type'] ?? 0);

        // Validation: Check if required fields are empty
        if (empty($ingredient_id)) {
            return ['success' => false, 'message' => 'Ingredient ID is required'];
        }
        if (empty($ingredient_name)) {
            return ['success' => false, 'message' => 'Ingredient name is required'];
        }
        if (empty($price_per_unit)) {
            return ['success' => false, 'message' => 'Price per unit is required'];
        }
        if ($warn_qty < 0) {  // Warn quantity cannot be negative
            return ['success' => false, 'message' => 'Warning quantity cannot be negative'];
        }
        if (empty($unit_id)) {
            return ['success' => false, 'message' => 'Unit is required'];
        }

        // Check if ingredient exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM ingredient WHERE ingredient_id = :ingredient_id");
        $stmt_check->bindParam(':ingredient_id', $ingredient_id);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Ingredient not found'];
        }

        // Update ingredient
        $stmt = $pdo->prepare("UPDATE ingredient 
                               SET ingredient_name = :ingredient_name, price_per_unit = :price_per_unit, 
                                   warn_qty = :warn_qty, unit_id = :unit_id 
                               WHERE ingredient_id = :ingredient_id");
        $stmt->bindParam(':ingredient_name', $ingredient_name);
        $stmt->bindParam(':price_per_unit', $price_per_unit);
        $stmt->bindParam(':warn_qty', $warn_qty);
        $stmt->bindParam(':unit_id', $unit_id);
        $stmt->bindParam(':ingredient_id', $ingredient_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Ingredient updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update ingredient'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function deleteIngredient($pdo) {
    try {
        $ingredient_id = intval($_POST['update_id'] ?? 0);

        // Validation: Check if ingredient ID is provided
        if (empty($ingredient_id)) {
            return ['success' => false, 'message' => 'Ingredient ID is required'];
        }

        // Check if ingredient exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM ingredient WHERE ingredient_id = :ingredient_id");
        $stmt_check->bindParam(':ingredient_id', $ingredient_id);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Ingredient not found'];
        }

        // Delete ingredient batches first (because the ingredient is related to batches)
        $stmt_batch_delete = $pdo->prepare("DELETE FROM ingredient_batch WHERE ingredient_id = :ingredient_id");
        $stmt_batch_delete->bindParam(':ingredient_id', $ingredient_id);
        $stmt_batch_delete->execute();

        // Now delete the ingredient itself
        $stmt = $pdo->prepare("DELETE FROM ingredient WHERE ingredient_id = :ingredient_id");
        $stmt->bindParam(':ingredient_id', $ingredient_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Ingredient and related batches deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete ingredient'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
function addBatch($pdo) {
    try {
        // Check if the required quantity field (batch_qty) is present in the POST data
        if (empty($_POST['batch_qty'])) {
            return ['success' => false, 'message' => 'Batch quantity is required'];
        }

        // Optional data, use existing values or defaults
        $ingredient_id = isset($_POST['ingredient_id']) ? $_POST['ingredient_id'] : null;
        $batch_barcode = isset($_POST['batch_barcode']) ? $_POST['batch_barcode'] : null;
        $expiry_date = isset($_POST['expiry_date']) && !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : '0000-00-00'; // Default to '0000-00-00' if empty
        $batch_qty = $_POST['batch_qty']; // This is required
        $created_at = date('Y-m-d H:i:s'); // Current date-time for created_at
        $updated_at = $created_at; // Same as created_at for now

        // If ingredient_id is provided, validate that it exists
        if ($ingredient_id) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM ingredient WHERE ingredient_id = :ingredient_id");
            $stmt->bindParam(':ingredient_id', $ingredient_id, PDO::PARAM_INT);
            $stmt->execute();
            $ingredient_exists = $stmt->fetchColumn();

            if (!$ingredient_exists) {
                return ['success' => false, 'message' => 'Ingredient not found'];
            }
        }

        // If batch barcode is provided, check if it already exists in the ingredient_batch table
        if ($batch_barcode) {
            // Check if a batch with the same barcode and ingredient_id exists
            $stmt = $pdo->prepare("SELECT batch_id, quantity FROM ingredient_batch WHERE barcode = :barcode AND ingredient_id = :ingredient_id");
            $stmt->bindParam(':barcode', $batch_barcode, PDO::PARAM_STR);
            $stmt->bindParam(':ingredient_id', $ingredient_id, PDO::PARAM_INT);
            $stmt->execute();
            $existing_batch = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing_batch) {
                // If the batch exists, update the quantity by adding the new quantity to the existing one
                $new_qty = $existing_batch['quantity'] + $batch_qty;
                $stmt = $pdo->prepare("UPDATE ingredient_batch SET quantity = :quantity, updated_at = :updated_at WHERE batch_id = :batch_id");
                $stmt->bindParam(':quantity', $new_qty, PDO::PARAM_INT);
                $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
                $stmt->bindParam(':batch_id', $existing_batch['batch_id'], PDO::PARAM_INT);
                $stmt->execute();
                
                return ['success' => true, 'message' => 'Batch quantity updated successfully'];
            }
        }

        // Insert a new batch if no existing batch with the barcode is found
        $sql = "INSERT INTO ingredient_batch (ingredient_id, barcode, quantity, expiry_date, created_at, updated_at)
                VALUES (:ingredient_id, :barcode, :quantity, :expiry_date, :created_at, :updated_at)";
        
        $stmt = $pdo->prepare($sql);

        // Bind parameters, only bind non-null values
        $stmt->bindParam(':quantity', $batch_qty, PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
        $stmt->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);

        if ($ingredient_id) {
            $stmt->bindParam(':ingredient_id', $ingredient_id, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':ingredient_id', null, PDO::PARAM_NULL);
        }

        if ($batch_barcode) {
            $stmt->bindParam(':barcode', $batch_barcode, PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':barcode', null, PDO::PARAM_NULL);
        }

        // Bind the expiry date (which is either the provided date or the default '0000-00-00')
        $stmt->bindParam(':expiry_date', $expiry_date, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Return success message
        return ['success' => true, 'message' => 'Batch added successfully'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error adding batch: ' . $e->getMessage()];
    }
}


function moveToWaste($pdo) {
    try {
        // Get the batch ID, waste quantity, and reason from the POST data
        $batch_id = intval($_POST['batch_id'] ?? 0);
        $waste_qty = floatval($_POST['waste_qty'] ?? 0);
        $waste_reason = trim($_POST['waste_reason'] ?? '');

        // Ensure the session is started to access the display_name
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Get the display name from the session
        $reported_by = $_SESSION['display_name'] ?? 'Unknown';  // Default to 'Unknown' if not set

        // Validation: Ensure all required fields are provided
        if (empty($batch_id) || $waste_qty <= 0 || empty($waste_reason)) {
            return ['success' => false, 'message' => 'All fields (batch ID, waste quantity, and reason) are required'];
        }

        // Fetch the batch details from the ingredient_batch table
        $stmt = $pdo->prepare("SELECT * FROM ingredient_batch WHERE batch_id = :batch_id");
        $stmt->bindParam(':batch_id', $batch_id);
        $stmt->execute();
        $batch = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$batch) {
            return ['success' => false, 'message' => 'Batch not found'];
        }

        // Check if the waste quantity is less than or equal to the available quantity
        if ($batch['quantity'] < $waste_qty) {
            return ['success' => false, 'message' => 'Not enough quantity to waste'];
        }

        // Update the batch quantity after subtracting the waste quantity
        $new_quantity = $batch['quantity'] - $waste_qty;

        // Update the ingredient_batch table with the new quantity
        $update_stmt = $pdo->prepare("UPDATE ingredient_batch SET quantity = :quantity, updated_at = NOW() WHERE batch_id = :batch_id");
        $update_stmt->execute([
            'quantity' => $new_quantity,
            'batch_id' => $batch_id
        ]);

        // If all the quantity is wasted, delete the batch from ingredient_batch
        if ($new_quantity == 0) {
            $delete_stmt = $pdo->prepare("DELETE FROM ingredient_batch WHERE batch_id = :batch_id");
            $delete_stmt->execute(['batch_id' => $batch_id]);
        }

        // Insert a record into the ingredient_waste table, including the reported_by (display_name)
        $insert_stmt = $pdo->prepare("INSERT INTO ingredient_waste (ingredient_name, ingredient_price, quantity_wasted, units, reason, reported_by, created_at, updated_at) 
                                      SELECT i.ingredient_name, i.price_per_unit, :waste_qty, i.unit_id, :waste_reason, :reported_by, NOW(), NOW() 
                                      FROM ingredient_batch ib 
                                      JOIN ingredient i ON ib.ingredient_id = i.ingredient_id 
                                      WHERE ib.batch_id = :batch_id");
        $insert_stmt->execute([
            'waste_qty' => $waste_qty,
            'waste_reason' => $waste_reason,
            'reported_by' => $reported_by,
            'batch_id' => $batch_id
        ]);

        // Return success message
        return ['success' => true, 'message' => 'Successfully moved to waste'];
    } catch (PDOException $e) {
        // Handle any database errors
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}


// INGREDIENT END


// CATEGORY START

function addCategory($pdo) {
    try {
        $category_name = trim($_POST['category_name'] ?? '');

        // Validation: Check if category name is empty
        if (empty($category_name)) {
            return ['success' => false, 'message' => 'Category name cannot be empty'];
        }

        // Check if category already exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM category WHERE category_name = :category_name");
        $stmt_check->bindParam(':category_name', $category_name);
        $stmt_check->execute();
        
        if ($stmt_check->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Category already exists'];
        }

        // Insert new category
        $stmt = $pdo->prepare("INSERT INTO category (category_name) VALUES (:category_name)");
        $stmt->bindParam(':category_name', $category_name);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Category added successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to add category'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function updateCategory($pdo) {
    try {
        $category_id = intval($_POST['update_id'] ?? 0);
        $category_name = trim($_POST['category_name'] ?? '');

        // Validation: Check if fields are empty
        if (empty($category_id) || empty($category_name)) {
            return ['success' => false, 'message' => 'Category ID and name are required'];
        }

        // Check if category exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM category WHERE category_id = :category_id");
        $stmt_check->bindParam(':category_id', $category_id);
        $stmt_check->execute();
        
        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Category not found'];
        }

        // Update category
        $stmt = $pdo->prepare("UPDATE category SET category_name = :category_name WHERE category_id = :category_id");
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Category updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update category'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function deleteCategory($pdo) {
    try {
        $category_id = intval($_POST['category_id'] ?? 0);

        // Validation: Check if category ID is empty
        if (empty($category_id)) {
            return ['success' => false, 'message' => 'Category ID is required'];
        }

        // Check if category exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM category WHERE category_id = :category_id");
        $stmt_check->bindParam(':category_id', $category_id);
        $stmt_check->execute();
        
        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Category not found'];
        }

        // Delete category
        $stmt = $pdo->prepare("DELETE FROM category WHERE category_id = :category_id");
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Category deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete category'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

// CATEGORY END

// UNIT START
function addUnit($pdo) {
    try {
        $unit_name = trim($_POST['unit_name'] ?? '');
        $unit_prefix = trim($_POST['unit_prefix'] ?? '');

        // Validation: Check if unit name and prefix are empty
        if (empty($unit_name) || empty($unit_prefix)) {
            return ['success' => false, 'message' => 'Unit name and prefix are required'];
        }

        // Check if unit already exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM unit WHERE unit_type = :unit_name");
        $stmt_check->bindParam(':unit_name', $unit_name);
        $stmt_check->execute();
        
        if ($stmt_check->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Unit already exists'];
        }

        // Insert new unit
        $stmt = $pdo->prepare("INSERT INTO unit (unit_type, short_name) VALUES (:unit_name, :unit_prefix)");
        $stmt->bindParam(':unit_name', $unit_name);
        $stmt->bindParam(':unit_prefix', $unit_prefix);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Unit added successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to add unit'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function updateUnit($pdo) {
    try {
        $unit_id = intval($_POST['update_id'] ?? 0);
        $unit_name = trim($_POST['unit_name'] ?? '');
        $unit_prefix = trim($_POST['unit_prefix'] ?? '');

        // Validation: Check if fields are empty
        if (empty($unit_id) || empty($unit_name) || empty($unit_prefix)) {
            return ['success' => false, 'message' => 'Unit ID, name, and prefix are required'];
        }

        // Check if unit exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM unit WHERE unit_id = :unit_id");
        $stmt_check->bindParam(':unit_id', $unit_id);
        $stmt_check->execute();
        
        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Unit not found'];
        }

        // Update unit
        $stmt = $pdo->prepare("UPDATE unit SET unit_type = :unit_name, short_name = :unit_prefix WHERE unit_id = :unit_id");
        $stmt->bindParam(':unit_name', $unit_name);
        $stmt->bindParam(':unit_prefix', $unit_prefix);
        $stmt->bindParam(':unit_id', $unit_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Unit updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update unit'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function deleteUnit($pdo) {
    try {
        $unit_id = intval($_POST['unit_id'] ?? 0);

        // Validation: Check if unit ID is empty
        if (empty($unit_id)) {
            return ['success' => false, 'message' => 'Unit ID is required'];
        }

        // Check if unit exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM unit WHERE unit_id = :unit_id");
        $stmt_check->bindParam(':unit_id', $unit_id);
        $stmt_check->execute();
        
        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Unit not found'];
        }

        // Delete unit
        $stmt = $pdo->prepare("DELETE FROM unit WHERE unit_id = :unit_id");
        $stmt->bindParam(':unit_id', $unit_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Unit deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete unit'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
// UNIT END

//DISCOUNT START
function addDiscount($pdo) {
    try {
        $discount_name = trim($_POST['discount_name'] ?? '');
        $discount_percentage = intval($_POST['discount_percentage'] ?? 0);

        // Validation: Check if discount name and percentage are empty or invalid
        if (empty($discount_name) || $discount_percentage <= 0 || $discount_percentage > 100) {
            return ['success' => false, 'message' => 'Discount name is required and percentage must be between 1 and 100'];
        }

        // Check if discount already exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM discount WHERE discount_name = :discount_name");
        $stmt_check->bindParam(':discount_name', $discount_name);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Discount already exists'];
        }

        // Insert new discount
        $stmt = $pdo->prepare("INSERT INTO discount (discount_name, discount_percentage) VALUES (:discount_name, :discount_percentage)");
        $stmt->bindParam(':discount_name', $discount_name);
        $stmt->bindParam(':discount_percentage', $discount_percentage);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Discount added successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to add discount'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}
function updateDiscount($pdo) {
    try {
        $discount_id = intval($_POST['update_id'] ?? 0);
        $discount_name = trim($_POST['discount_name'] ?? '');
        $discount_percentage = intval($_POST['discount_percentage'] ?? 0);

        // Validation: Check if discount ID, name, and percentage are empty or invalid
        if (empty($discount_id) || empty($discount_name) || $discount_percentage <= 0 || $discount_percentage > 100) {
            return ['success' => false, 'message' => 'Discount ID, name, and percentage are required and percentage must be between 1 and 100'];
        }

        // Check if discount exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM discount WHERE discount_id = :discount_id");
        $stmt_check->bindParam(':discount_id', $discount_id);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Discount not found'];
        }

        // Update discount
        $stmt = $pdo->prepare("UPDATE discount SET discount_name = :discount_name, discount_percentage = :discount_percentage WHERE discount_id = :discount_id");
        $stmt->bindParam(':discount_name', $discount_name);
        $stmt->bindParam(':discount_percentage', $discount_percentage);
        $stmt->bindParam(':discount_id', $discount_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Discount updated successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to update discount'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

function deleteDiscount($pdo) {
    try {
        $discount_id = intval($_POST['discount_id'] ?? 0);

        // Validation: Check if discount ID is empty
        if (empty($discount_id)) {
            return ['success' => false, 'message' => 'Discount ID is required'];
        }

        // Check if discount exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM discount WHERE discount_id = :discount_id");
        $stmt_check->bindParam(':discount_id', $discount_id);
        $stmt_check->execute();

        if ($stmt_check->fetchColumn() == 0) {
            return ['success' => false, 'message' => 'Discount not found'];
        }

        // Delete discount
        $stmt = $pdo->prepare("DELETE FROM discount WHERE discount_id = :discount_id");
        $stmt->bindParam(':discount_id', $discount_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Discount deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete discount'];
        }
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error occurred'];
    }
}

//DISCOUNT END

//USER START
function addUser($pdo) {
    try {
        // Validate user display name
        if (empty($_POST['user_display'])) {
            return ['success' => false, 'message' => 'Display name is required.'];
        }
        $user_display = $_POST['user_display'];

        // Validate username
        if (empty($_POST['username'])) {
            return ['success' => false, 'message' => 'Username is required.'];
        }
        $username = $_POST['username'];

        // Default password (can be updated later)
        $password = 'takoadmin';  

        // Validate password length
        if (strlen($password) < 6) {  // Minimum password length validation
            return ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
        }

        // Validate user role
        if (empty($_POST['user_role']) || !is_numeric($_POST['user_role'])) {
            return ['success' => false, 'message' => 'A valid user role is required.'];
        }
        $user_role = $_POST['user_role'];

        // Validate login enabled checkbox
        $loginEnabled = isset($_POST['loginEnabled']) && $_POST['loginEnabled'] == 'on' ? '1' : '0';

        // Check if Users with the same name already exist
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR display_name = :display_name");
        
        // Use variables for binding
        $stmt_check->bindParam(':username', $username);
        $stmt_check->bindParam(':display_name', $user_display);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            return ['success' => false, 'message' => 'Username or display name already exists.'];
        }

        // Hash password before binding
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, password, display_name, role_id, isEnabled) VALUES (:username, :password, :display_name, :role_id , :isEnabled)");

        // Use variables for binding
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);  // Pass the hashed password
        $stmt->bindParam(':display_name', $user_display);
        $stmt->bindParam(':role_id', $user_role, PDO::PARAM_INT);
        $stmt->bindParam(':isEnabled', $loginEnabled, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User added successfully.'];
        } else {
            return ['success' => false, 'message' => 'Error occurred while adding the user.'];
        }
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}
function updateUser($pdo) {
    try {
        // Validate update ID (must be numeric)
        if (empty($_POST['update_id']) || !is_numeric($_POST['update_id'])) {
            return ['success' => false, 'message' => 'Invalid user ID.'];
        }
        $update_ID = $_POST['update_id'];

        // Validate user display name
        if (empty($_POST['user_display'])) {
            return ['success' => false, 'message' => 'Display name is required.'];
        }
        $user_display = $_POST['user_display'];

        // Validate username
        if (empty($_POST['username'])) {
            return ['success' => false, 'message' => 'Username is required.'];
        }
        $username = $_POST['username'];

        // Validate user role
        if (empty($_POST['user_role']) || !is_numeric($_POST['user_role'])) {
            return ['success' => false, 'message' => 'A valid user role is required.'];
        }
        $user_role = $_POST['user_role'];

        // Validate login enabled checkbox
        $loginEnabled = isset($_POST['loginEnabled']) && $_POST['loginEnabled'] == 'on' ? '1' : '0';

        // Check if users with the same username or display name already exist (excluding the current user)
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username = :username OR display_name = :display_name) AND id != :id");
        $stmt_check->bindParam(':username', $username);
        $stmt_check->bindParam(':display_name', $user_display);
        $stmt_check->bindParam(':id', $update_ID, PDO::PARAM_INT);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            return ['success' => false, 'message' => 'Username or display name already exists.'];
        }

        // Proceed to update the user details
        $stmt = $pdo->prepare("UPDATE users SET username = :username, display_name = :display_name, role_id = :role_id, isEnabled = :isEnabled WHERE id = :id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':display_name', $user_display);
        $stmt->bindParam(':role_id', $user_role, PDO::PARAM_INT);
        $stmt->bindParam(':isEnabled', $loginEnabled, PDO::PARAM_INT);
        $stmt->bindParam(':id', $update_ID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Error occurred while updating the user.'];
        }
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

function updateUserPassword($pdo) {
    try {
        // Validate update ID (must be numeric)
        if (empty($_POST['update_id']) || !is_numeric($_POST['update_id'])) {
            return ['success' => false, 'message' => 'Invalid user ID.'];
        }
        $update_ID = $_POST['update_id'];

        // Validate new password
        if (empty($_POST['user_password']) || strlen($_POST['user_password']) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
        }
        $newPassword = $_POST['user_password'];

        // Validate confirm password
        if (empty($_POST['user_conpass']) || $_POST['user_conpass'] !== $newPassword) {
            return ['success' => false, 'message' => 'Passwords do not match.'];
        }

        // Hash the new password securely
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Proceed to update the password in the database
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $update_ID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Password updated successfully.'];
        } else {
            return ['success' => false, 'message' => 'Error occurred while updating the password.'];
        }
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

function deleteUser($pdo) {
    try {
        // Validate update ID (must be numeric)
        if (empty($_POST['update_id']) || !is_numeric($_POST['update_id'])) {
            return ['success' => false, 'message' => 'Invalid user ID.'];
        }
        $update_ID = $_POST['update_id'];

        // Check if the user exists
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE id = :id");
        $stmt_check->bindParam(':id', $update_ID, PDO::PARAM_INT);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count == 0) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        // Proceed to delete the user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $update_ID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'User has been deleted successfully.'];
        } else {
            return ['success' => false, 'message' => 'Error occurred while deleting the user.'];
        }

    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

//USER END

//ROLE START
function addRole($pdo){
    $roleName = $_POST['role_name'];
    $permissions = $_POST['selected_permission'];

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Insert the new role into the roles table (if you have one)
        $stmt = $pdo->prepare("INSERT INTO roles (role_name) VALUES (:role_name)");
        $stmt->execute(['role_name' => $roleName]);

        // Get the ID of the newly inserted role
        $roleId = $pdo->lastInsertId();

        // Insert the permissions into the role_permissions table
        $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");

        foreach ($permissions as $permissionId) {
            $stmt->execute(['role_id' => $roleId, 'permission_id' => $permissionId]);
        }
        // Commit the transaction
        $pdo->commit();
        return array('success' => true, 'message' => 'Role and permissions added successfully!');
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $pdo->rollBack();
        return array('success' => false, 'message' => 'Failed to add role and permissions' . $e->getMessage());
    }
}

function updateRole($pdo) {
    $roleId = $_POST['role_id'];
    $roleName = $_POST['role_name'];
    $permissions = $_POST['selected_permission'];

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Update the role name in the roles table
        $stmt = $pdo->prepare("UPDATE roles SET role_name = :role_name WHERE id = :role_id");
        $stmt->execute(['role_name' => $roleName, 'role_id' => $roleId]);

        // Delete existing permissions for the role
        $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = :role_id");
        $stmt->execute(['role_id' => $roleId]);

        // Insert the updated permissions into the role_permissions table
        $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");

        foreach ($permissions as $permissionId) {
            $stmt->execute(['role_id' => $roleId, 'permission_id' => $permissionId]);
        }

        // Commit the transaction
        $pdo->commit();
        return array('success' => true, 'message' => 'Role and permissions updated successfully!');
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $pdo->rollBack();
        return array('success' => false, 'message' => 'Failed to update role and permissions: ' . $e->getMessage());
    }
}
function deleteRole($pdo) {
    $roleId = $_POST['role_id'];

    try {
        // Check if the role is "admin" before deletion
        $stmt = $pdo->prepare("SELECT role_name FROM roles WHERE id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($role && strtolower($role['role_name']) == 'admin') {
            return array('success' => false, 'message' => 'Cannot delete the "admin" role!');
        }

        // Start a transaction
        $pdo->beginTransaction();

        // Delete the role_permissions associated with this role
        $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = :role_id");
        $stmt->execute(['role_id' => $roleId]);

        // Delete the role from the roles table
        $stmt = $pdo->prepare("DELETE FROM roles WHERE id = :role_id");
        $stmt->execute(['role_id' => $roleId]);

        // Commit the transaction
        $pdo->commit();
        return array('success' => true, 'message' => 'Role deleted successfully!');
    } catch (Exception $e) {
        // Roll back the transaction if something failed
        $pdo->rollBack();
        return array('success' => false, 'message' => 'Failed to delete role: ' . $e->getMessage());
    }
}
//ROLE END

?>
