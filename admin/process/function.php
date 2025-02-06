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
function addIngredient($pdo) {
    try {
        $ingredient_name = trim($_POST['ingredient_name'] ?? '');
        $price_per_unit = trim($_POST['price_per_unit'] ?? '');
        $ingredient_qty = intval($_POST['ingredient_qty'] ?? 0);
        $warn_qty = intval($_POST['warn_qty'] ?? 0);
        $unit_id = intval($_POST['unit_type'] ?? 0);

        // Validation: Check if required fields are empty
        if (empty($ingredient_name)) {
            return ['success' => false, 'message' => 'Ingredient name is required'];
        }
        if (empty($price_per_unit)) {
            return ['success' => false, 'message' => 'Price per unit is required'];
        }
        if ($ingredient_qty === 0) {
            return ['success' => false, 'message' => 'Quantity is required and cannot be zero'];
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
        $stmt = $pdo->prepare("INSERT INTO ingredient (ingredient_name, price_per_unit, ingredient_qty, warn_qty, unit_id) 
                               VALUES (:ingredient_name, :price_per_unit, :ingredient_qty, :warn_qty, :unit_id)");
        $stmt->bindParam(':ingredient_name', $ingredient_name);
        $stmt->bindParam(':price_per_unit', $price_per_unit);
        $stmt->bindParam(':ingredient_qty', $ingredient_qty);
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

function updateIngredient($pdo) {
    try {
        $ingredient_id = intval($_POST['update_id'] ?? 0);
        $ingredient_name = trim($_POST['ingredient_name'] ?? '');
        $price_per_unit = trim($_POST['price_per_unit'] ?? '');
        $ingredient_qty = intval($_POST['ingredient_qty'] ?? 0);
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
        if ($ingredient_qty === 0) {
            return ['success' => false, 'message' => 'Quantity is required and cannot be zero'];
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
                               SET ingredient_name = :ingredient_name, price_per_unit = :price_per_unit, ingredient_qty = :ingredient_qty, 
                                   warn_qty = :warn_qty, unit_id = :unit_id 
                               WHERE ingredient_id = :ingredient_id");
        $stmt->bindParam(':ingredient_name', $ingredient_name);
        $stmt->bindParam(':price_per_unit', $price_per_unit);
        $stmt->bindParam(':ingredient_qty', $ingredient_qty);
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
        $ingredient_id = intval($_POST['ingredient_id'] ?? 0);

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

        // Delete ingredient
        $stmt = $pdo->prepare("DELETE FROM ingredient WHERE ingredient_id = :ingredient_id");
        $stmt->bindParam(':ingredient_id', $ingredient_id);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Ingredient deleted successfully'];
        } else {
            return ['success' => false, 'message' => 'Failed to delete ingredient'];
        }
    } catch (PDOException $e) {
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
?>
