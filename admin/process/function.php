<?php
function getProductList($pdo) {
    try {
        $query = 
        "SELECT 
                p.product_id, 
                p.product_name, 
                p.product_description, 
                p.brand_id, 
                p.category_id, 
                p.status_id, 
                p.product_sku, 
                p.product_pp, 
                p.product_sp, 
                p.product_min, 
                p.product_max, 
                p.unit_id, 
                p.tax_id, 
                p.expiry_notice, 
                p.created_at, 
                p.updated_at, 
                CAST(ROUND(p.product_sp * (1 + t.tax_percentage / 100), 2) AS DECIMAL(10, 2)) AS product_sp_with_tax
            FROM 
                product p
            LEFT JOIN 
                tax t ON p.tax_id = t.tax_id
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        
        // Fetch the result as an associative array
        $productList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $productList;
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Error: " . $e->getMessage();
        return array(); // Return an empty array if an error occurs
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
    function getPaymentAccount($pdo){
        try {
            $query = "SELECT * FROM payment_account";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $payment_account = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $payment_account;
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
    function userHasPermission ($pdo, $userId, $permissionName){
        try {
        $sql = "SELECT
        a.permission_name
        FROM permissions a
        JOIN role_permissions b ON b.permission_id = a.id
        JOIN roles c ON c.id = b.role_id
        JOIN users d ON d.role_id = c.id
        WHERE d.id = :user_id AND a.permission_name = :permission_name";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['user_id'=> $userId, 'permission_name'=>$permissionName]);
        return $stmt->fetch() !==false;
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
    function addUser($pdo){
        try {
            $user_display = $_POST['user_display'];
            $username = $_POST['username'];
            $password = password_hash('ecsadmin', PASSWORD_BCRYPT);
            $user_role = $_POST['user_role'];
            $loginEnabled = !empty($_POST['loginEnabled']) ? '1' : '0';

            // Check if Users with the same name already exists
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR display_name = :display_name");
            $stmt_check->bindParam(':username', $username);
            $stmt_check->bindParam(':display_name', $user_display);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                // Users already exists, return false
                return false;
            }

            $stmt = $pdo ->prepare("INSERT INTO users (username, password, display_name, role_id, isEnabled) VALUES (:username, :password, :display_name, :role_id , :isEnabled)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':display_name', $user_display);
            $stmt->bindParam(':role_id', $user_role, PDO::PARAM_INT);
            $stmt->bindParam(':isEnabled', $loginEnabled, PDO::PARAM_INT);
            if ($stmt->execute()){
                // Users added successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function updateUser($pdo) {
        try {
            $pdo->beginTransaction();
    
            $update_ID = $_POST['update_id'];
            $user_display = $_POST['user_display'];
            $username = $_POST['username'];
            $user_role = $_POST['user_role'];
            $loginEnabled = !empty($_POST['loginEnabled']) ? '1' : '0';
    
            // Check if users with the same username or display name already exist (excluding the current user)
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username = :username OR display_name = :display_name) AND id != :id");
            $stmt_check->bindParam(':username', $username);
            $stmt_check->bindParam(':display_name', $user_display);
            $stmt_check->bindParam(':id', $update_ID, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();
    
            if ($count > 0) {
                // User with the same username or display name already exists, return false
                $pdo->rollBack();
                return array('success' => false, 'message' => 'Username or display name already exists.');
            }
    
            // Update user details without password
            $stmt = $pdo->prepare("UPDATE users SET username = :username, display_name = :display_name, role_id = :role_id, isEnabled = :isEnabled WHERE id = :id");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':display_name', $user_display);
            $stmt->bindParam(':role_id', $user_role, PDO::PARAM_INT);
            $stmt->bindParam(':isEnabled', $loginEnabled, PDO::PARAM_INT);
            $stmt->bindParam(':id', $update_ID, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                // User updated successfully
                $pdo->commit();
                return array('success' => true, 'message' => 'User updated successfully.');
            } else {
                // Error occurred
                $pdo->rollBack();
                return array('success' => false, 'message' => 'Error updating user.');
            }
        } catch(PDOException $e) {
            // Handle database connection error
            $pdo->rollBack();
            return array('success' => false, 'message' => 'Database error: ' . $e->getMessage());
        }
    }
    
    function updateUserPassword($pdo){
        try {
            $update_ID = $_POST['update_id'];
            $password = password_hash($_POST['c_password'], PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
            //bind parameters
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':id', $update_ID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getPermissionList($pdo){

    }
    function getSupplierList($pdo){
        try {
            $query = "SELECT * FROM supplier";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $supplier = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $supplier;
        }catch(PDOException $e){
                    // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getCustomerList($pdo){
        try {
            $query = "SELECT * FROM customer";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $customer = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $customer;
        }catch(PDOException $e){
                    // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getCategory($pdo){
        // require_once 'config.php';
        try {
            $query = "SELECT * FROM category";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $category = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $category;
        }catch(PDOException $e){
                    // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
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
    function AddCategory($pdo){
        // require_once 'config.php';
        try {
            $category_name = $_POST['category_name'];
            $category_prefix = $_POST['category_prefix'];
            // Check if category with the same name already exists
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM category WHERE category_name = :category_name OR category_prefix = :category_prefix");
            $stmt_check->bindParam(':category_name', $category_name);
            $stmt_check->bindParam(':category_prefix', $category_prefix);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                // Category already exists, return false
                return false;
            }
            $parent_category_id = !empty($_POST['p_category_id']) ? $_POST['p_category_id'] : null;
            $stmt = $pdo ->prepare("INSERT INTO category (parent_category_id, category_name, category_prefix) VALUES (:parent_category_id, :category_name, :category_prefix)");
            //bind parameters
            $stmt->bindParam(':parent_category_id', $parent_category_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_name', $category_name);
            $stmt->bindParam(':category_prefix', $category_prefix);
            if ($stmt->execute()) {
                // Category added successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function updateCategory($pdo){
        try {
            $category_name = $_POST['category_name'];
            $parent_category_id = !empty($_POST['p_category_id']) ? $_POST['p_category_id'] : null;
            $category_prefix = $_POST['category_prefix'];
            $update_ID = $_POST['update_id'];

            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM category WHERE (category_name = :category_name OR category_prefix = :category_prefix) AND category_id != :category_id");
            $stmt_check->bindParam(':category_name', $category_name);
            $stmt_check->bindParam(':category_id', $update_ID, PDO::PARAM_INT);
            $stmt_check->bindParam(':category_prefix', $category_prefix);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                // Category already exists, return false
                return false;
            }

            $stmt = $pdo->prepare("UPDATE category SET parent_category_id = :parent_category_id, category_name = :category_name, category_prefix = :category_prefix WHERE category_id = :category_id");
            //bind parameters
            $stmt->bindParam(':parent_category_id', $parent_category_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_name', $category_name);
            $stmt->bindParam(':category_prefix', $category_prefix);
            $stmt->bindParam(':category_id', $update_ID, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            }else{
                return false;
            }
        } catch(PDOException $e){  
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function AddBrand($pdo){
        // require_once 'config.php';
        try {
            $brand_name = $_POST['brand_name'];
            // Check if category with the same name already exists
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM brand WHERE brand_name = :brand_name");
            $stmt_check->bindParam(':brand_name', $brand_name);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();
            if ($count > 0) {
                // Brand already exists, return false
                return false;
            }
            $stmt = $pdo ->prepare("INSERT INTO brand (brand_name) VALUES (:brand_name)");
            //bind parameters
            $stmt->bindParam(':brand_name', $brand_name);
            if ($stmt->execute()) {
                // Brand added successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function updateBrand($pdo){
        // require_once 'config.php';
        try {
            $brand_name = $_POST['brand_name'];
            $update_ID = $_POST['update_id'];
            // Check if category with the same name already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM brand WHERE brand_name = :brand_name AND brand_id != :brand_id");
            $stmt->bindParam(':brand_name', $brand_name);
            $stmt->bindParam(':brand_id', $update_ID, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                // Brand already exists, return false
                return false;
            }
            $stmt = $pdo->prepare("UPDATE brand SET brand_name = :brand_name WHERE brand_id = :brand_id");
            //bind parameters
            $stmt->bindParam(':brand_name', $brand_name);
            $stmt->bindParam(':brand_id', $update_ID, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // Brand Update successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function addUnit($pdo){
        try {
            $unit_name = $_POST['unit_name'];
            $unit_prefix = $_POST['unit_prefix'];
            // Check if category with the same name already exists
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM unit WHERE unit_type = :unit_type OR short_name = :short_name");
            $stmt_check->bindParam(':unit_type', $unit_name);
            $stmt_check->bindParam(':short_name', $unit_prefix);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();
            if ($count > 0) {
                // Unit already exists, return false
                return false;
            }
            $stmt = $pdo ->prepare("INSERT INTO unit (unit_type, short_name) VALUES (:unit_type, :short_name)");
            //bind parameters
            $stmt->bindParam(':unit_type', $unit_name);
            $stmt->bindParam(':short_name', $unit_prefix);
            if ($stmt->execute()) {
                // Unit added successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function updateUnit($pdo){
        try {
            $unit_name = $_POST['unit_name'];
            $unit_prefix = $_POST['unit_prefix'];
            $update_ID = $_POST['update_id'];
            // Check if category with the same name already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM unit WHERE (unit_type = :unit_type OR short_name = :short_name) AND unit_id != :unit_id");
            $stmt->bindParam(':unit_type', $unit_name);
            $stmt->bindParam(':short_name', $unit_prefix);
            $stmt->bindParam(':unit_id', $update_ID, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                // Unit already exists, return false
                return false;
            }
            $stmt = $pdo->prepare("UPDATE unit SET unit_type = :unit_type, short_name = :short_name WHERE unit_id = :unit_id");
            //bind parameters
            $stmt->bindParam(':unit_type', $unit_name);
            $stmt->bindParam(':short_name', $unit_prefix);
            $stmt->bindParam(':unit_id', $update_ID, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // Unit Update successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function addTax($pdo){
        try {
            $tax_name = $_POST['tax_name'];
            $tax_percentage = $_POST['tax_percentage'];
            // Check if tax with the same name already exists
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM tax WHERE tax_name = :tax_name OR tax_percentage = :tax_percentage");
            $stmt_check->bindParam(':tax_name', $tax_name);
            $stmt_check->bindParam(':tax_percentage', $tax_percentage, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();
            if ($count > 0) {
                // Tax already exists, return false
                return false;
            }
            $stmt = $pdo ->prepare("INSERT INTO tax (tax_name, tax_percentage) VALUES (:tax_name, :tax_percentage)");
            //bind parameters
            $stmt->bindParam(':tax_name', $tax_name);
            $stmt->bindParam(':tax_percentage', $tax_percentage, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // Tax added successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function updateTax($pdo){
        try {
            $tax_name = $_POST['tax_name'];
            $tax_percentage = $_POST['tax_percentage'];
            $update_ID = $_POST['update_id'];
            // Check if tax with the same name already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM tax WHERE (tax_name = :tax_name OR tax_percentage = :tax_percentage) AND tax_id != :tax_id");
            $stmt->bindParam(':tax_name', $tax_name);
            $stmt->bindParam(':tax_percentage', $tax_percentage, PDO::PARAM_INT);
            $stmt->bindParam(':tax_id', $update_ID, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if ($count > 0) {
                // Tax already exists, return false
                return false;
            }
            $stmt = $pdo->prepare("UPDATE tax SET tax_name = :tax_name, tax_percentage = :tax_percentage WHERE tax_id = :tax_id");
            //bind parameters
            $stmt->bindParam(':tax_name', $tax_name);
            $stmt->bindParam(':tax_percentage', $tax_percentage, PDO::PARAM_INT);
            $stmt->bindParam(':tax_id', $update_ID, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // Tax Update successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getSKUID($pdo, $category_id){
        try {
            // Get category_id from the AJAX request
            if (!empty($category_id)) {
                // Fetch the category prefix
                $stmt = $pdo->prepare("SELECT category_prefix FROM category WHERE category_id = ?");
                $stmt->execute([$category_id]);
                $category_prefix = $stmt->fetchColumn();
            } else {
                // Use default category prefix for products without a category
                $category_prefix = "UNC";
            }
    
            // // Fetch the latest SKU for the category
            $query = "SELECT product_sku FROM product WHERE category_id = ? ORDER BY product_sku DESC LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$category_id]);
    
            // If a SKU exists for the category, increment the last SKU number
            if ($stmt->rowCount() > 0) {
                $last_sku = $stmt->fetchColumn();
                $last_number = intval(substr($last_sku, strlen($category_prefix)));
                $next_sku = $category_prefix . sprintf('%05d', ++$last_number);
            } else {
                // If no SKU exists for the category, start from 1
                $next_sku = $category_prefix . '00001';
            }
            return $next_sku;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getStockInNumber($pdo) {
        try {
            $query = "SELECT series_number FROM stockin_history ORDER BY series_number DESC LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            
            // Check if there are any rows
            if ($stmt->rowCount() > 0) {
                $last_series_number = $stmt->fetchColumn();
                
                // Extract numeric part from series number (assuming format is "STK_IN00001")
                $numeric_part = intval(substr($last_series_number, 7)) + 1;
                
                // Format new series number with leading zeros
                $new_series_number = 'STK_IN' . str_pad($numeric_part, 5, '0', STR_PAD_LEFT);
            } else {
                // If no rows, start with the first series number
                $new_series_number = 'STK_IN00001';
            }
    
            return $new_series_number;
    
        } catch(PDOException $e) {
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return null; // Return null if an error occurs
        }
    }
    function getStockOutNumber($pdo) {
        try {
            $query = "SELECT series_number FROM stockout_history ORDER BY series_number DESC LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            
            // Check if there are any rows
            if ($stmt->rowCount() > 0) {
                $last_series_number = $stmt->fetchColumn();
                
                // Extract numeric part from series number (assuming format is "STK_IN00001")
                $numeric_part = intval(substr($last_series_number, 7)) + 1;
                
                // Format new series number with leading zeros
                $new_series_number = 'STK_OUT' . str_pad($numeric_part, 5, '0', STR_PAD_LEFT);
            } else {
                // If no rows, start with the first series number
                $new_series_number = 'STK_OUT00001';
            }
    
            return $new_series_number;
    
        } catch(PDOException $e) {
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return null; // Return null if an error occurs
        }
    }
    
    function getUnits($pdo){
        try {
            $query = "SELECT * FROM unit";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $units = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $units;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getTaxs($pdo){
        try {
            $query = "SELECT * FROM tax
                        ORDER BY 
                CAST(SUBSTRING_INDEX(tax_name, ' ', 1) AS UNSIGNED) ASC";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $taxs = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $taxs;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getBrand($pdo){
        try {
            $query = "SELECT * FROM brand";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $brands = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $brands;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getCount_TotalProduct($pdo) {
        try {
            $query = "SELECT COUNT(*) AS total_products FROM product";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_products'];
        } catch (PDOException $e) {
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 if an error occurs
        }
    }
    
    function getCount_TotalItems($pdo) {
        try {
            $query = "SELECT SUM(item_qty) AS total_items FROM item";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_items'];
        } catch (PDOException $e) {
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 if an error occurs
        }
    }
    function getCount_OutofStock($pdo){
        try {
            $query = "SELECT COUNT(*) AS out_of_stock_count
            FROM (
                SELECT 
                    a.product_sku,
                    a.product_min,
                    COALESCE(SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END)
                            - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0) AS stocks
                FROM 
                    product a
                LEFT JOIN 
                    trans_item g ON g.product_sku = a.product_sku
                GROUP BY 
                    a.product_sku, a.product_min
                HAVING 
                    stocks = 0
            ) AS out_of_stock_count";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['out_of_stock_count'];
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getCount_LowofStock($pdo) {
        try {
            $query = "SELECT COUNT(*) AS low_stock_count
            FROM (
                SELECT 
                    a.product_sku,
                    a.product_min,
                    COALESCE(SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END)
                            - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0) AS stocks
                FROM 
                    product a
                LEFT JOIN 
                    trans_item g ON g.product_sku = a.product_sku
                GROUP BY 
                    a.product_sku, a.product_min
                HAVING 
                    stocks < a.product_min 
                    AND stocks > 0
            ) AS low_stock_count";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['low_stock_count'];
        } catch (PDOException $e) {
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return 0; // Return 0 if an error occurs
        }
    }

    function getProduct($pdo){
        try {
            $query = "SELECT * FROM product";
            $stmt = $pdo->prepare($query);
    
            $stmt ->execute();
            $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);
            return $products;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getProductSummary($product_no, $pdo){
        try {
            $query = "SELECT
                a.product_name,
                a.product_description,
                c.brand_name,
                CASE
                    WHEN b2.category_name IS NULL THEN b.category_name
                    ELSE CONCAT(b2.category_name, '/', b.category_name)
                END AS category,
                a.product_sku,
                a.product_pp,
                a.product_sp,
                CASE
                    WHEN COALESCE(
                        SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END)
                        - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0
                    ) >= a.product_min THEN 1
                    WHEN COALESCE(
                        SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END)
                        - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0
                    ) < a.product_min AND COALESCE(
                        SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END)
                        - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0
                    ) != 0 THEN 2
                    ELSE 3
                END AS status_id,
                f.tax_name,
                a.product_min,
                a.product_max,
                COALESCE(
                    SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END)
                    - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0
                ) AS stocks,
                e.short_name AS unit
            FROM 
                product a
            INNER JOIN 
                category b ON b.category_id = a.category_id
            LEFT JOIN 
                category b2 ON b.parent_category_id = b2.category_id
            INNER JOIN 
                brand c ON c.brand_id = a.brand_id
            INNER JOIN 
                unit e ON e.unit_id = a.unit_id
            INNER JOIN 
                tax f ON f.tax_id = a.tax_id
            LEFT JOIN 
                trans_item g ON g.product_sku = a.product_sku
            WHERE 
                a.product_sku = :product_no
            GROUP BY 
                a.product_sku";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':product_no', $product_no);
    
            $stmt ->execute();
            $product = $stmt -> fetch(PDO::FETCH_ASSOC);
            return $product;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getLowofStock($pdo){
        try {
            $query = "SELECT
                a.product_id,
                a.product_name,
                CASE
                    WHEN b2.category_name IS NULL THEN b.category_name
                    ELSE CONCAT(b2.category_name, ' / ', b.category_name)
                END AS category,
                a.product_sku,
                a.product_min,
                a.product_max,
                COALESCE(SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END) 
                            - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0) AS stocks,
                e.short_name AS unit
                FROM 
                    product a
                INNER JOIN 
                    category b ON b.category_id = a.category_id
                LEFT JOIN 
                    category b2 ON b.parent_category_id = b2.category_id
                INNER JOIN 
                    unit e ON e.unit_id = a.unit_id
                LEFT JOIN 
                    trans_item g ON g.product_sku = a.product_sku
                GROUP BY 
                    a.product_sku
                HAVING stocks < a.product_min AND stocks > 0";
            $stmt = $pdo->prepare($query);
            $stmt ->execute();
            $lowStock = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return $lowStock;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getOutofStock($pdo){
        try {
            $query = "SELECT
                a.product_id,
                a.product_name,
                CASE
                    WHEN b2.category_name IS NULL THEN b.category_name
                    ELSE CONCAT(b2.category_name, ' / ', b.category_name)
                END AS category,
                a.product_sku,
                a.product_min,
                a.product_max,
                COALESCE(SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END) 
                            - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END), 0) AS stocks,
                e.short_name AS unit
                FROM 
                    product a
                INNER JOIN 
                    category b ON b.category_id = a.category_id
                LEFT JOIN 
                    category b2 ON b.parent_category_id = b2.category_id
                INNER JOIN 
                    unit e ON e.unit_id = a.unit_id
                LEFT JOIN 
                    trans_item g ON g.product_sku = a.product_sku
                GROUP BY 
                    a.product_sku
                HAVING stocks = 0";
            $stmt = $pdo->prepare($query);
            $stmt ->execute();
            $outOfStock = $stmt ->fetchAll(PDO::FETCH_ASSOC);
            return $outOfStock;
        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function getItembyID($product_no, $pdo) {
        try {
            $query = 
            "SELECT
                a.product_sku, 
                a.item_barcode, 
                a.item_expiry, 
                SUM(CASE 
                        WHEN a.transaction_type IN ('bill', 'expense') THEN a.item_qty 
                        WHEN a.transaction_type = 'invoice' THEN -a.item_qty 
                        ELSE 0 
                    END) AS available_qty,
                b.product_id,
                b.product_name,
                b.expiry_notice,
                DATEDIFF(a.item_expiry, NOW()) + 1 AS days_to_expiry
            FROM 
                trans_item a
            INNER JOIN 
                product b ON b.product_sku = a.product_sku
            WHERE 
                b.product_sku = :product_no
            GROUP BY 
                a.product_sku, a.item_barcode, a.item_expiry, b.product_id, b.product_name, b.expiry_notice
            HAVING 
                available_qty > 0
            ORDER BY 
                a.item_expiry ASC, a.item_barcode ASC";
            
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':product_no', $product_no, PDO::PARAM_STR);
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $items;
        } catch (PDOException $e) {
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    
    function addProduct($pdo){
        try {
            $sku_id = $_POST['sku_id'];
            $product_name = $_POST['product_name'];
            $purchase_price = $_POST['purchase_price'];
            $min_qty = $_POST['min_qty'];
            $max_qty = $_POST['max_qty'];
            
            
            //check product if exist
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM product WHERE product_name = :product_name");
            $stmt_check->bindParam(':product_name', $product_name);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                // Category already exists, return false
                return false;
            }

            //if not empty category and units
            $product_desc = !empty($_POST['product_desc']) ? $_POST['product_desc'] : null;
            $brand_id = !empty($_POST['brand_id']) ? $_POST['brand_id'] : null;
            $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
            $unit_id = !empty($_POST['unit_id']) ? $_POST['unit_id'] : null;
            $tax_id = !empty($_POST['tax_id']) ? $_POST['tax_id'] : null;
            $expiry_notice = !empty($_POST['exp_notice']) ? $_POST['exp_notice'] : 30;

            $stmt = $pdo ->prepare("INSERT INTO product (product_name,product_description,brand_id,category_id,product_sku,product_pp,product_min,product_max,unit_id,tax_id,expiry_notice) VALUES (:product_name, :product_description, :brand_id, :category_id, :product_sku, :product_pp, :product_min, :product_max, :unit_id, :tax_id,:expiry_notice)");

            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':product_description', $product_desc);
            $stmt->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_sku', $sku_id);
            $stmt->bindParam(':product_pp', $purchase_price, PDO::PARAM_INT);
            $stmt->bindParam(':product_min', $min_qty, PDO::PARAM_INT);
            $stmt->bindParam(':product_max', $max_qty, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
            $stmt->bindParam(':tax_id', $tax_id, PDO::PARAM_INT);
            $stmt->bindParam(':expiry_notice', $expiry_notice, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                // Product added successfully
                return true;
            } else {
                // Error occurred
                return false;
            }

        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }
    function updateProduct($pdo){
        try {
            $sku_id = $_POST['sku_id'];
            $product_name = $_POST['product_name'];
            $purchase_price = $_POST['purchase_price'];
            $min_qty = $_POST['min_qty'];
            $max_qty = $_POST['max_qty'];
            $update_ID = $_POST['update_id'];
            //check product if exist
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM product WHERE product_name = :product_name AND product_id != :product_id");
            $stmt_check->bindParam(':product_name', $product_name);
            $stmt_check->bindParam(':product_id', $update_ID, PDO::PARAM_INT);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn();

            if ($count > 0) {
                // Category already exists, return false
                return false;
            }

            //if not empty category and units
            $product_desc = !empty($_POST['product_desc']) ? $_POST['product_desc'] : null;
            $brand_id = !empty($_POST['brand_id']) ? $_POST['brand_id'] : null;
            $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
            $unit_id = !empty($_POST['unit_id']) ? $_POST['unit_id'] : null;
            $tax_id = !empty($_POST['tax_id']) ? $_POST['tax_id'] : null;
            $expiry_notice = !empty($_POST['exp_notice']) ? $_POST['exp_notice'] : 30;

            $stmt = $pdo ->prepare("UPDATE product SET product_name = :product_name,product_description =:product_description ,brand_id =:brand_id ,category_id =:category_id ,product_sku =:product_sku ,product_pp =:product_pp ,product_min =:product_min ,product_max = :product_max ,unit_id = :unit_id ,tax_id = :tax_id, expiry_notice = :expiry_notice WHERE product_id = :product_id");
            
            $stmt->bindParam(':product_id', $update_ID, PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':product_description', $product_desc);
            $stmt->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_sku', $sku_id);
            $stmt->bindParam(':product_pp', $purchase_price, PDO::PARAM_INT);
            $stmt->bindParam(':product_min', $min_qty, PDO::PARAM_INT);
            $stmt->bindParam(':product_max', $max_qty, PDO::PARAM_INT);
            $stmt->bindParam(':unit_id', $unit_id, PDO::PARAM_INT);
            $stmt->bindParam(':tax_id', $tax_id, PDO::PARAM_INT);
            $stmt->bindParam(':expiry_notice', $expiry_notice, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Product updated successfully
                return true;
            } else {
                // Error occurred
                return false;
            }

        }catch(PDOException $e){
            // Handle database connection error
            echo "Error: " . $e->getMessage();
            return array(); // Return an empty array if an error occurs
        }
    }

    function getCurrentInventoryCount($pdo, $product_sku) {
        $stmt = $pdo->prepare("SELECT SUM(item_qty) as total_qty FROM item WHERE product_sku = :product_sku");
        $stmt->execute([':product_sku' => $product_sku]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total_qty'] : 0;
    }
    function stockIN($pdo, $postData) {
        try {
            if (is_array($postData)) {
                $postData = json_encode($postData);
            }
    
            $data = json_decode($postData, true);
    
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'success' => false,
                    'message' => 'Invalid JSON data!'
                ];
            }
    
            // Begin the transaction
            $pdo->beginTransaction();
    
            $series_number = $data['stockin_number'];
            $stmt_stockin = $pdo->prepare("INSERT INTO stockin_history (series_number) VALUES (:series_number)");
            $insert_stockin_history = $stmt_stockin->execute([':series_number' => $series_number]);
    
            if (!$insert_stockin_history) {
                $pdo->rollBack();
                return [
                    'success' => false,
                    'message' => 'Failed to insert into stockin_history!'
                ];
            }
    
            $stmt = $pdo->prepare("
                INSERT INTO pending_item (
                    series_number,product_name, item_barcode, item_qty, item_expiry, product_sku, product_pp, product_sp, created_at
                ) VALUES (
                    :series_number,:product_name, :item_barcode, :item_qty, :item_expiry, :product_sku, :product_pp, :product_sp, :created_at
                )
            ");
    
            foreach ($data['items'] as $product) {
                $product_sku = $product['product_sku'];
    
                // Fetch product details including purchase and selling price
                $stmt_product = $pdo->prepare("SELECT product_name, product_min, product_max, product_pp, product_sp FROM product WHERE product_sku = :product_sku");
                $stmt_product->execute([':product_sku' => $product_sku]);
                $product_info = $stmt_product->fetch(PDO::FETCH_ASSOC);
    
                if (!$product_info) {
                    $pdo->rollBack();
                    return [
                        'success' => false,
                        'message' => "Product with SKU $product_sku not found."
                    ];
                }
    
                $product_max = (int)$product_info['product_max'];
                $current_count = getCurrentInventoryCount($pdo, $product_sku);
    
                foreach ($product['items'] as $item) {
                    $new_total_count = $current_count + $item['qty'];
    
                    if ($new_total_count > $product_max) {
                        $pdo->rollBack();
                        return [
                            'success' => false,
                            'message' => "Adding item with barcode {$item['barcode']} exceeds the allowed maximum of $product_max for product SKU $product_sku."
                        ];
                    }
    
                    $current_count = $new_total_count;
    
                    // Insert the item into the pending_item table including product_pp and product_sp
                    $stmt->execute([
                        ':series_number' => $series_number,
                        ':product_name' => $product_info['product_name'],
                        ':item_barcode' => $item['barcode'],
                        ':item_qty' => $item['qty'],
                        ':item_expiry' => $item['expiry'],
                        ':product_sku' => $product_sku,
                        ':product_pp' => $product_info['product_pp'],
                        ':product_sp' => $product_info['product_sp'],
                        ':created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
    
            // Commit the transaction
            $pdo->commit();
    
            return ['success' => true, 'message' => 'Stock has been recorded.'];
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $pdo->rollBack();
            return ['success' => false, 'message' => 'Failed to add stocks.'];
        }
    }
    
    // function stockIN($pdo, $postData) {
    //     try {
    //         if (is_array($postData)) {
    //             $postData = json_encode($postData);
    //         }
    
    //         $data = json_decode($postData, true);
    
    //         if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    //             return [
    //                 'success' => false,
    //                 'message' => 'Invalid JSON data!'
    //             ];
    //         }
    
    //         // Begin the transaction
    //         $pdo->beginTransaction();
    
    //         $series_number = $data['stockin_number'];
    //         $stmt_stockin = $pdo->prepare("INSERT INTO stockin_history (series_number) VALUES (:series_number)");
    //         $insert_stockin_history = $stmt_stockin->execute([':series_number' => $series_number]);
    
    //         if (!$insert_stockin_history) {
    //             $pdo->rollBack();
    //             return [
    //                 'success' => false,
    //                 'message' => 'Failed to insert into stockin_history!'
    //             ];
    //         }
    
    //         $stmt = $pdo->prepare("INSERT INTO pending_item (series_number, item_barcode, item_qty, item_expiry, product_sku, created_at) VALUES (:series_number, :item_barcode, :item_qty, :item_expiry, :product_sku, :created_at)");
    
    //         foreach ($data['items'] as $product) {
    //             $product_sku = $product['product_sku'];
    
    //             // Fetch product limits
    //             $stmt_product = $pdo->prepare("SELECT product_min, product_max FROM product WHERE product_sku = :product_sku");
    //             $stmt_product->execute([':product_sku' => $product_sku]);
    //             $product_limits = $stmt_product->fetch(PDO::FETCH_ASSOC);
    
    //             if (!$product_limits) {
    //                 $pdo->rollBack();
    //                 return [
    //                     'success' => false,
    //                     'message' => "Product with SKU $product_sku not found."
    //                 ];
    //             }
    
    //             $product_max = (int)$product_limits['product_max'];
    //             $current_count = getCurrentInventoryCount($pdo, $product_sku);
    
    //             foreach ($product['items'] as $item) {
    //                 // Calculate the new total count if the item is added
    //                 $new_total_count = $current_count + $item['qty'];
    
    //                 // Validate the item's quantity against the max limit
    //                 if ($new_total_count > $product_max) {
    //                     $pdo->rollBack();
    //                     return [
    //                         'success' => false,
    //                         'message' => "Adding item with barcode {$item['barcode']} exceeds the allowed maximum of $product_max for product SKU $product_sku."
    //                     ];
    //                 }
    
    //                 // Update the current count for the next iteration
    //                 $current_count = $new_total_count;
    
    //                 // Insert the item into the pending_item table
    //                 $stmt->execute([
    //                     ':series_number' => $series_number,
    //                     ':item_barcode' => $item['barcode'],
    //                     ':item_qty' => $item['qty'],
    //                     ':item_expiry' => $item['expiry'],
    //                     ':product_sku' => $product_sku,
    //                     ':created_at' => date('Y-m-d H:i:s')
    //                 ]);
    //             }
    //         }
    
    //         // Commit the transaction
    //         $pdo->commit();
    
    //         return ['success' => true, 'message' => 'Stock has been recorded.'];
    //     } catch (PDOException $e) {
    //         // Rollback the transaction in case of an error
    //         $pdo->rollBack();
    //         return ['success' => false, 'message' => 'Failed to add stocks.'];
    //     }
    // }
    function stockOut($pdo, $postData) {
        try {
            // Step 1: Handle input
            if (is_array($postData)) {
                $postData = json_encode($postData);
            }
    
            // Step 2: Decode JSON
            $data = json_decode($postData, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                return ['success' => false, 'message' => 'Invalid JSON data!'];
            }
    
            // Step 3: Begin transaction
            $pdo->beginTransaction();
    
            // Step 4: Insert into stockout_history
            $stockoutNumber = $data['stockout_number'];
            $stmt_stockout = $pdo->prepare("INSERT INTO stockout_history (series_number, date, isAdded) VALUES (:series_number, NOW(), 1)");
            if (!$stmt_stockout->execute([':series_number' => $stockoutNumber])) {
                $pdo->rollBack();
                return ['success' => false, 'message' => 'Failed to insert into stockout_history!'];
            }
    
            // Step 5: Prepare pending_stock_out insert
            $stmt_pending = $pdo->prepare("INSERT INTO pending_stock_out (series_number, product_name, item_barcode, item_qty, product_pp, product_sp, item_expiry, product_sku, created_at) VALUES (:series_number, :product_name, :item_barcode, :item_qty, :product_pp, :product_sp, :item_expiry, :product_sku, NOW())");
    
            // Step 6: Loop through items
            foreach ($data['items'] as $item) {
                $productSku = $item['product_sku'];
                $product_name = $item['product_name'];
                $itemBarcode = $item['barcode'];
                $product_pp = $item['product_pp'];
                $product_sp = $item['product_sp'];
                $itemQty = $item['qty'];
                $itemExpiry = $item['expiry'];
    
                // Insert the item into pending_stock_out table
                $stmt_pending->execute([
                    ':series_number' => $stockoutNumber,
                    ':product_name' => $product_name,
                    ':item_barcode' => $itemBarcode,
                    ':product_pp' => $product_pp,
                    ':product_sp' => $product_sp,
                    ':item_qty' => $itemQty,
                    ':item_expiry' => $itemExpiry,
                    ':product_sku' => $productSku
                ]);
    
                // Decrement the item in the item table
                $stmt_decrement = $pdo->prepare("UPDATE item SET item_qty = item_qty - :item_qty WHERE item_barcode = :item_barcode");
                $stmt_decrement->execute([
                    ':item_qty' => $itemQty,
                    ':item_barcode' => $itemBarcode
                ]);
    
                // Check if the item quantity is now zero and delete it if so
                $stmt_check = $pdo->prepare("SELECT item_qty FROM item WHERE item_barcode = :item_barcode");
                $stmt_check->execute([':item_barcode' => $itemBarcode]);
                $currentQty = $stmt_check->fetchColumn();
    
                // If quantity is zero, delete the item
                if ($currentQty === '0' || $currentQty === 0) {
                    $stmt_delete = $pdo->prepare("DELETE FROM item WHERE item_barcode = :item_barcode");
                    $stmt_delete->execute([':item_barcode' => $itemBarcode]);
                }
            }
    
            // Step 7: Commit transaction
            $pdo->commit();
            unset($_SESSION['picklist']);
            return ['success' => true, 'message' => 'Stock-out recorded successfully.'];
        } catch (PDOException $e) {
            // Rollback in case of error
            $pdo->rollBack();
            return ['success' => false, 'message' => 'Failed to process stock-out: ' . $e->getMessage()];
        }
    }
    
    
    // function stockOut($pdo, $postData) {
    //     try {
    //         // Step 1: Handle input
    //         if (is_array($postData)) {
    //             $postData = json_encode($postData);
    //         }
    
    //         // Step 2: Decode JSON
    //         $data = json_decode($postData, true);
    //         if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    //             return ['success' => false, 'message' => 'Invalid JSON data!'];
    //         }
    
    //         // Step 3: Begin transaction
    //         $pdo->beginTransaction();
    
    //         // Step 4: Insert into stockout_history
    //         $stockoutNumber = $data['stockout_number'];
    //         $stmt_stockout = $pdo->prepare("INSERT INTO stockout_history (series_number, date, isAdded) VALUES (:series_number, NOW(), 1)");
    //         if (!$stmt_stockout->execute([':series_number' => $stockoutNumber])) {
    //             $pdo->rollBack();
    //             return ['success' => false, 'message' => 'Failed to insert into stockout_history!'];
    //         }
    
    //         // Step 5: Prepare pending_stock_out insert
    //         $stmt_pending = $pdo->prepare("INSERT INTO pending_stock_out (series_number, item_barcode, item_qty, item_expiry, product_sku, created_at) VALUES (:series_number, :item_barcode, :item_qty, :item_expiry, :product_sku, NOW())");
    
    //         // Step 6: Loop through items
    //         foreach ($data['items'] as $item) {
    //             $productSku = $item['product_sku'];
    //             $itemBarcode = $item['barcode'];
    //             $itemQty = $item['qty'];
    //             $itemExpiry = $item['expiry'];

    //             // Insert the item into pending_stock_out table
    //             $stmt_pending->execute([
    //                 ':series_number' => $stockoutNumber,
    //                 ':item_barcode' => $itemBarcode,
    //                 ':item_qty' => $itemQty,
    //                 ':item_expiry' => $itemExpiry,
    //                 ':product_sku' => $productSku
    //             ]);

    //             //Decrement the item in the item table
    //             $stmt_decrement = $pdo->prepare("UPDATE item SET item_qty = item_qty - :item_qty WHERE item_barcode = :item_barcode");
    //             $stmt_decrement->execute([
    //                 ':item_qty' => $itemQty,
    //                 ':item_barcode' => $itemBarcode
    //             ]);
    //         }
    
    //         // Step 7: Commit transaction
    //         $pdo->commit();
    //         unset($_SESSION['picklist']);
    //         return ['success' => true, 'message' => 'Stock-out recorded successfully.'];
    //     } catch (PDOException $e) {
    //         // Rollback in case of error
    //         $pdo->rollBack();
    //         return ['success' => false, 'message' => 'Failed to process stock-out: ' . $e->getMessage()];
    //     }
    // }

    function updateCost($pdo){
        try{
            $selling_price = $_POST['selling_price'];
            $tax_id = !empty($_POST['tax_id']) ? $_POST['tax_id'] : null;
            $update_ID = $_POST['update_id'];

            $stmt = $pdo ->prepare("UPDATE product SET product_sp =:product_sp, tax_id=:tax_id WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $update_ID, PDO::PARAM_INT);
            $stmt->bindParam(':product_sp', $selling_price, PDO::PARAM_INT);
            $stmt->bindParam(':tax_id', $tax_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                // Cost updated successfully
                return true;
            } else {
                // Error occurred
                return false;
            }
        } catch (PDOException $e) {
            // Log or handle the database connection error
            return false; // Return false in case of error
        }
    }
    function addtoInventory($pdo, $series_number){
        try {
            $query_pendingItem = "SELECT * FROM pending_item WHERE series_number = :series_number";
            $stmt_pendingItem = $pdo->prepare($query_pendingItem);
            $stmt_pendingItem->bindParam(':series_number', $series_number);
            $check_pendingItem = $stmt_pendingItem->execute();
    
            if (!$check_pendingItem) {
                return false;
            }
    
            function getNextSku($pdo, $prefix) {
                $stmt = $pdo->prepare("SELECT item_sku FROM item WHERE item_sku LIKE ? ORDER BY item_sku DESC LIMIT 1");
                $stmt->execute([$prefix . '%']);
        
                if ($stmt->rowCount() > 0) {
                    $last_sku = $stmt->fetchColumn();
                    $last_number = intval(substr($last_sku, strlen($prefix)));
                    return $prefix . sprintf('%05d', ++$last_number);
                } else {
                    return $prefix . '00001';
                }
            }
    
            $pendingItems = $stmt_pendingItem->fetchAll(PDO::FETCH_ASSOC);
    
            if ($pendingItems) {
                $insertQuery = "INSERT INTO item (item_sku, item_barcode, item_qty, item_expiry, product_sku, created_at) 
                                VALUES (:item_sku, :item_barcode, :item_qty, :item_expiry, :product_sku, :created_at)";
                $stmtInsert = $pdo->prepare($insertQuery);
    
                // Start the transaction
                $pdo->beginTransaction();
                $allSuccessful = true;
    
                foreach ($pendingItems as $pendingItem) {
                    $product_sku = $pendingItem['product_sku'];
                    
                    // Fetch product limits
                    $stmt_product = $pdo->prepare("SELECT product_min, product_max FROM product WHERE product_sku = :product_sku");
                    $stmt_product->execute([':product_sku' => $product_sku]);
                    $product_limits = $stmt_product->fetch(PDO::FETCH_ASSOC);
    
                    if (!$product_limits) {
                        $pdo->rollBack();
                        return [
                            'success' => false,
                            'message' => "Product with SKU $product_sku not found."
                        ];
                    }
    
                    $product_max = (int)$product_limits['product_max'];
                    $current_count = getCurrentInventoryCount($pdo, $product_sku);
    
                    $new_total_count = $current_count + $pendingItem['item_qty'];
    
                    if ($new_total_count > $product_max) {
                        $pdo->rollBack();
                        return [
                            'success' => false,
                            'message' => "Adding item with barcode {$pendingItem['item_barcode']} exceeds the allowed maximum of $product_max for product SKU $product_sku."
                        ];
                    }
    
                    $current_count = $new_total_count;
                    $prefix = 'ITM';
                    $item_sku = getNextSku($pdo, $prefix);
                    
                    // Convert the barcode to uppercase
                    $uppercaseBarcode = strtoupper($pendingItem['item_barcode']);
                    
                    $stmtInsert->bindParam(':item_sku', $item_sku);
                    $stmtInsert->bindParam(':item_barcode', $uppercaseBarcode);
                    $stmtInsert->bindParam(':item_qty', $pendingItem['item_qty']);
                    $stmtInsert->bindParam(':item_expiry', $pendingItem['item_expiry']);
                    $stmtInsert->bindParam(':product_sku', $pendingItem['product_sku']);
                    $stmtInsert->bindParam(':created_at', $pendingItem['created_at']);
    
                    if (!$stmtInsert->execute()) {
                        $allSuccessful = false;
                        break;
                    }
                }
    
                if ($allSuccessful) {
                    $pdo->commit();
                    $updateStockStatus = $pdo->prepare("UPDATE stockin_history SET isAdded = :isAdded WHERE series_number = :series_number");
                    $updateStockStatus->execute([':isAdded' => 1, ':series_number' => $series_number]);
                    return true;
                } else {
                    $pdo->rollBack();
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }
    function getSuggestedbySystem($pdo, $picklist){
        // Placeholder for suggested items
        $suggestedItems = [];
    
        // Prepare placeholders for picklist product SKUs and quantities
        $picklistProductSKUs = [];
        $picklistQuantities = [];
        foreach ($picklist as $item) {
            $picklistProductSKUs[] = $item['product_sku'];
            $picklistQuantities[$item['product_sku']] = $item['product_qty'];
        }
    
        // Construct SQL query to fetch suggested items
        $sql = "SELECT
                p.product_sku,
                i.item_sku,
                i.item_barcode,
                p.product_name,
                i.item_qty,
                i.item_expiry,
                p.product_pp,
                p.product_sp,
                DATEDIFF(i.item_expiry, NOW()) + 1 AS days_to_expiry
            FROM
                item i
            JOIN
                product p ON i.product_sku = p.product_sku
            WHERE
                p.product_sku IN ('" . implode("','", $picklistProductSKUs) . "') 
                AND i.item_expiry IS NOT NULL
            ORDER BY
                i.item_expiry ASC;
        ";
    
        // Prepare statement
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    
        // Fetch suggested items
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product_sku = $row['product_sku'];
    
            // Calculate suggested quantity based on picklist and available quantity
            if (isset($picklistQuantities[$product_sku]) && $picklistQuantities[$product_sku] > 0) {
                $picklist_qty = $picklistQuantities[$product_sku];
                $available_qty = $row['item_qty'];
    
                // Determine suggested quantity
                $suggested_qty = min($picklist_qty, $available_qty);
    
                // Prepare suggested item
                $suggestedItems[] = [
                    'product_name' => $row['product_name'],
                    'product_sku' => $row['product_sku'],
                    'barcode' => $row['item_barcode'],
                    'product_pp' => $row['product_pp'],
                    'product_sp' => $row['product_sp'],
                    'expiry' => $row['item_expiry'],
                    'qty' => $suggested_qty
                ];
    
                // Adjust picklist quantity based on what's suggested
                $picklistQuantities[$product_sku] -= $suggested_qty;
            }
        }
    
        // Return array of suggested items
        return $suggestedItems;
    }
    function movetoWaste($pdo) {
        try {
            $pdo->beginTransaction();
            
            $void_card_input = $_POST['void_card'];
            $product_sku = $_POST['product_sku'];
            $product_barcode = $_POST['product_barcode'];
            $product_desc = $_POST['product_desc'];
            $qty = intval($_POST['qty']);
            $created_at = date('Y-m-d H:i:s');
    
            // Check if the void_card matches the value in the system option table
            $sql_check_void_card = "SELECT option_value FROM system_option WHERE option_name = 'void_card'";
            $stmt_check_void_card = $pdo->prepare($sql_check_void_card);
            $stmt_check_void_card->execute();
            $system_option = $stmt_check_void_card->fetch(PDO::FETCH_ASSOC);
    
            if ($system_option && $system_option['option_value'] === $void_card_input) {
                // Retrieve necessary information
                $sql = "
                    SELECT
                        p.product_sku,
                        p.product_name,
                        p.product_pp,
                        c.category_name,
                        i.item_barcode,
                        i.item_qty,
                        i.item_expiry
                    FROM
                        item i
                    JOIN
                        product p ON i.product_sku = p.product_sku
                    JOIN
                        category c ON p.category_id = c.category_id
                    WHERE
                        i.item_barcode = :item_barcode AND p.product_sku = :product_sku;
                ";
        
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':item_barcode', $product_barcode);
                $stmt->bindParam(':product_sku', $product_sku);
                $stmt->execute();
                $item = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($item) {
                    // Decrement or remove items from the item table
                    $remaining_qty = $item['item_qty'] - $qty;
                    if ($remaining_qty <= 0) {
                        $stmt_delete = $pdo->prepare("DELETE FROM item WHERE item_barcode = :item_barcode");
                        $stmt_delete->bindParam(':item_barcode', $product_barcode);
                        $stmt_delete->execute();
                    } else {
                        $stmt_update = $pdo->prepare("UPDATE item SET item_qty = :item_qty WHERE item_barcode = :item_barcode");
                        $stmt_update->bindParam(':item_qty', $remaining_qty, PDO::PARAM_INT);
                        $stmt_update->bindParam(':item_barcode', $product_barcode);
                        $stmt_update->execute();
                    }
        
                    // Insert a new waste entry
                    $stmt_insert_waste = $pdo->prepare("
                        INSERT INTO waste (product_sku, product_name, product_pp, category_name, item_barcode, item_qty, item_expiry, waste_reason, created_at)
                        VALUES (:product_sku, :product_name, :product_pp, :category_name, :item_barcode, :item_qty, :item_expiry, :waste_reason, :created_at)
                    ");
                    $stmt_insert_waste->bindParam(':product_sku', $item['product_sku']);
                    $stmt_insert_waste->bindParam(':product_name', $item['product_name']);
                    $stmt_insert_waste->bindParam(':product_pp', $item['product_pp']);
                    $stmt_insert_waste->bindParam(':category_name', $item['category_name']);
                    $stmt_insert_waste->bindParam(':item_barcode', $item['item_barcode']);
                    $stmt_insert_waste->bindParam(':item_qty', $qty, PDO::PARAM_INT);
                    $stmt_insert_waste->bindParam(':item_expiry', $item['item_expiry']);
                    $stmt_insert_waste->bindParam(':waste_reason', $product_desc);
                    $stmt_insert_waste->bindParam(':created_at', $created_at);
                    $stmt_insert_waste->execute();
        
                    $pdo->commit();
                    return array('success' => true, 'message' => 'Item moved to waste successfully.');
                } else {
                    $pdo->rollBack();
                    return array('success' => false, 'message' => 'Item not found.');
                }
            } else {
                $pdo->rollBack();
                return array('success' => false, 'message' => 'Void card does not match.');
            }
        } catch (PDOException $e) {
            // Handle database connection error
            $pdo->rollBack();
            return array('success' => false, 'message' => 'Database error: ' . $e->getMessage());
        }
    }    
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
    function addSupplier($pdo) {
        $supplier_id = !empty($_POST['supplier_id']) ? $_POST['supplier_id'] : null;
        $companyName = !empty($_POST['company_name']) ? $_POST['company_name'] : null;
        $supplierName = !empty($_POST['supplier_name']) ? $_POST['supplier_name'] : null;
        $supplierAddress = !empty($_POST['supplier_address']) ? $_POST['supplier_address'] : null;
        $supplierContact = !empty($_POST['supplier_contact']) ? $_POST['supplier_contact'] : null;
        $supplierEmail = !empty($_POST['supplier_email']) ? $_POST['supplier_email'] : null;
        $isActive = !empty($_POST['isActive']) ? '1' : '0';
    
        try {
            // Start a transaction
            $pdo->beginTransaction();
    
            // Insert the new supplier into the suppliers table
            $stmt = $pdo->prepare("
                INSERT INTO supplier (vendor_company, vendor_name, vendor_address, vendor_contact, vendor_email, status, created_at)
                VALUES (:vendor_company, :vendor_name, :vendor_address, :vendor_contact, :vendor_email, :status, NOW())
            ");
            $stmt->execute([
                'vendor_company' => $companyName,
                'vendor_name' => $supplierName,
                'vendor_address' => $supplierAddress,
                'vendor_contact' => $supplierContact,
                'vendor_email' => $supplierEmail,
                'status' => $isActive
            ]);
    
            // Commit the transaction
            $pdo->commit();
            return array('success' => true, 'message' => 'Supplier added successfully!');
        } catch (Exception $e) {
            // Roll back the transaction if something failed
            $pdo->rollBack();
            return array('success' => false, 'message' => 'Failed to add supplier: ' . $e->getMessage());
        }
    }
    function updateSupplier($pdo) {
        // Get the supplier ID for the update
        $supplierId = !empty($_POST['update_id']) ? $_POST['update_id'] : null;
    
        // If no supplier ID is provided, return an error message
        if (is_null($supplierId)) {
            return array('success' => false, 'message' => 'Supplier ID is required for updating.');
        }
    
        $companyName = !empty($_POST['company_name']) ? $_POST['company_name'] : null;
        $supplierName = !empty($_POST['supplier_name']) ? $_POST['supplier_name'] : null;
        $supplierAddress = !empty($_POST['supplier_address']) ? $_POST['supplier_address'] : null;
        $supplierContact = !empty($_POST['supplier_contact']) ? $_POST['supplier_contact'] : null;
        $supplierEmail = !empty($_POST['supplier_email']) ? $_POST['supplier_email'] : null;
        $isActive = !empty($_POST['isActive']) ? '1' : '0';
    
        try {
            // Start a transaction
            $pdo->beginTransaction();
    
            // Update the existing supplier in the supplier table
            $stmt = $pdo->prepare("
                UPDATE supplier 
                SET vendor_company = :vendor_company, 
                    vendor_name = :vendor_name, 
                    vendor_address = :vendor_address, 
                    vendor_contact = :vendor_contact, 
                    vendor_email = :vendor_email, 
                    status = :status,
                    updated_at = NOW()
                WHERE id = :supplier_id
            ");
            $stmt->execute([
                'vendor_company' => $companyName,
                'vendor_name' => $supplierName,
                'vendor_address' => $supplierAddress,
                'vendor_contact' => $supplierContact,
                'vendor_email' => $supplierEmail,
                'status' => $isActive,
                'supplier_id' => $supplierId
            ]);
    
            // Commit the transaction
            $pdo->commit();
            return array('success' => true, 'message' => 'Supplier updated successfully!');
        } catch (Exception $e) {
            // Roll back the transaction if something failed
            $pdo->rollBack();
            return array('success' => false, 'message' => 'Failed to update supplier: ' . $e->getMessage());
        }
    }
    function addCustomer($pdo) {
        $customerName = !empty($_POST['customer_name']) ? $_POST['customer_name'] : null;
        $customerEmail = !empty($_POST['customer_email']) ? $_POST['customer_email'] : null;
        $companyName = !empty($_POST['company_name']) ? $_POST['company_name'] : null;
        $customerPhoneNo = !empty($_POST['customer_phone_no']) ? $_POST['customer_phone_no'] : null;
    
        try {
            // Start a transaction
            $pdo->beginTransaction();
    
            // Insert the new customer into the customer table
            $stmt = $pdo->prepare("
                INSERT INTO customer (customer_name, customer_email, company_name, customer_phone_no, created_at)
                VALUES (:customer_name, :customer_email, :company_name, :customer_phone_no, NOW())
            ");
            $stmt->execute([
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'company_name' => $companyName,
                'customer_phone_no' => $customerPhoneNo,
            ]);
    
            // Commit the transaction
            $pdo->commit();
            return array('success' => true, 'message' => 'Customer added successfully!');
        } catch (Exception $e) {
            // Roll back the transaction if something failed
            $pdo->rollBack();
            return array('success' => false, 'message' => 'Failed to add customer: ' . $e->getMessage());
        }
    }
    
    function updateCustomer($pdo) {
        // Get the customer ID for the update
        $customerId = !empty($_POST['update_id']) ? $_POST['update_id'] : null;
    
        // If no customer ID is provided, return an error message
        if (is_null($customerId)) {
            return array('success' => false, 'message' => 'Customer ID is required for updating.');
        }
    
        $customerName = !empty($_POST['customer_name']) ? $_POST['customer_name'] : null;
        $customerEmail = !empty($_POST['customer_email']) ? $_POST['customer_email'] : null;
        $companyName = !empty($_POST['company_name']) ? $_POST['company_name'] : null;
        $customerPhoneNo = !empty($_POST['customer_phone_no']) ? $_POST['customer_phone_no'] : null;
    
        try {
            // Start a transaction
            $pdo->beginTransaction();
    
            // Update the existing customer in the customer table
            $stmt = $pdo->prepare("
                UPDATE customer 
                SET customer_name = :customer_name, 
                    customer_email = :customer_email, 
                    company_name = :company_name, 
                    customer_phone_no = :customer_phone_no, 
                    updated_at = NOW()
                WHERE id = :customer_id
            ");
            $stmt->execute([
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'company_name' => $companyName,
                'customer_phone_no' => $customerPhoneNo,
                'customer_id' => $customerId
            ]);
    
            // Commit the transaction
            $pdo->commit();
            return array('success' => true, 'message' => 'Customer updated successfully!');
        } catch (Exception $e) {
            // Roll back the transaction if something failed
            $pdo->rollBack();
            return array('success' => false, 'message' => 'Failed to update customer: ' . $e->getMessage());
        }
    }
    function getProductDetailsbyName($pdo) {
        $product_name = !empty($_POST['product_name']) ? $_POST['product_name'] : null;
    
        if (!$product_name) {
            return array('success' => false, 'message' => 'Product name is required.');
        }
    
        try {
            // Start a transaction
            $pdo->beginTransaction();
    
            // Prepare the SQL query to retrieve product_sku and product_pp by product_name
            $sql = "SELECT product_sku, product_pp FROM product WHERE product_name = :product_name LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':product_name', $product_name);
            
            // Execute the query
            $stmt->execute();
    
            // Fetch the product details
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($product) {
                // Commit the transaction
                $pdo->commit();
                return array('success' => true, 'data' => $product, 'message' => 'Product record has been retrieved!');
            } else {
                // Roll back the transaction if no product found
                $pdo->rollBack();
                return array('success' => false, 'message' => 'Product not found.');
            }
        } catch (Exception $e) {
            // Roll back the transaction if something failed
            $pdo->rollBack();
            return array('success' => false, 'message' => 'Failed to retrieve product: ' . $e->getMessage());
        }
    }

    function addTransaction($pdo) {
        // Get form data from $_POST
        $formType = !empty($_POST['formType']) ? $_POST['formType'] : null;
        $items = !empty($_POST['items']) ? json_decode($_POST['items'], true) : [];
    
        // Validate that items are provided
        if (empty($items)) {
            return array('success' => false, 'message' => 'No items provided for this transaction.');
        }
    
        try {
            // Start a PDO transaction
            $pdo->beginTransaction();
    
            // Determine transaction details based on formType
            switch ($formType) {
                case 'bill':
                    $supplierID = !empty($_POST['billSupplier']) ? $_POST['billSupplier'] : null;
                    $address = !empty($_POST['billAddress']) ? $_POST['billAddress'] : null;
                    $date = !empty($_POST['billDate']) ? $_POST['billDate'] : null;
                    $dueDate = !empty($_POST['billdueDate']) ? $_POST['billdueDate'] : null;
                    $subTotal = !empty($_POST['sub_total']) ? $_POST['sub_total'] : null;
                    $total_tax = !empty($_POST['total_tax']) ? $_POST['total_tax'] : null;
                    $grand_total = !empty($_POST['grand_total']) ? $_POST['grand_total'] : null;
                    $transactionNo = generateTransactionNo($pdo, 'bill');
                    $tax_type = !empty($_POST['tax_type']) ? $_POST['tax_type'] : null;
                    $remarks = !empty($_POST['remarks']) ? $_POST['remarks'] : null;
    
                    // Insert into the trans_bill table
                    $sql = "
                        INSERT INTO trans_bill (supplier_id, bill_address, bill_date, bill_due_date, bill_no, total_amount, sales_tax, grand_total, transaction_no , tax_type, remarks) 
                        VALUES (:supplier_id, :bill_address, :bill_date, :bill_due_date, :bill_no, :total_amount, :sales_tax, :grand_total, :transaction_no, :tax_type, :remarks)
                    ";
                    $stmtParams = [
                        'supplier_id' => $supplierID,
                        'bill_address' => $address,
                        'bill_date' => $date,
                        'bill_due_date' => $dueDate,
                        'bill_no' => !empty($_POST['billNo']) ? $_POST['billNo'] : null,
                        'total_amount' => $subTotal,
                        'sales_tax' => $total_tax,
                        'grand_total' => $grand_total,
                        'transaction_no' => $transactionNo,
                        'tax_type' => $tax_type,
                        'remarks' => $remarks
                    ];
                    break;
    
                case 'expense':
                    $supplierID = !empty($_POST['payee_id']) ? $_POST['payee_id'] : null;
                    $date = !empty($_POST['expenseDate']) ? $_POST['expenseDate'] : null;
                    $paymentMethod = !empty($_POST['expense_payment_method']) ? $_POST['expense_payment_method'] : null;
                    $expenseNo = !empty($_POST['expenseNo']) ? $_POST['expenseNo'] : null;
                    $subTotal = !empty($_POST['sub_total']) ? $_POST['sub_total'] : 0;
                    $totalTax = !empty($_POST['total_tax']) ? $_POST['total_tax'] : 0;
                    $grandTotal = !empty($_POST['grand_total']) ? $_POST['grand_total'] : 0;
                    $transactionNo = generateTransactionNo($pdo, 'expense');
                    $tax_type = !empty($_POST['tax_type']) ? $_POST['tax_type'] : null;
                    $remarks = !empty($_POST['remarks']) ? $_POST['remarks'] : null;
    
                    // Insert into the trans_expense table
                    $sql = "
                        INSERT INTO trans_expense (payee_id, expense_date, expense_payment_method, expense_no, transaction_no, tax_type, total_amount, sales_tax, grand_total, remarks) 
                        VALUES (:payee_id, :expense_date, :expense_payment_method, :expense_no, :transaction_no, :tax_type, :total_amount, :sales_tax, :grand_total, :remarks)
                    ";
                    $stmtParams = [
                        'payee_id' => $supplierID,
                        'expense_date' => $date,
                        'expense_payment_method' => $paymentMethod,
                        'expense_no' => $expenseNo,
                        'transaction_no' => $transactionNo,
                        'tax_type' => $tax_type,
                        'total_amount' => $subTotal,
                        'sales_tax' => $totalTax,
                        'grand_total' => $grandTotal,
                        'remarks' => $remarks
                    ];
                    break;
    
                case 'invoice':
                    $customerID = !empty($_POST['customer_id']) ? $_POST['customer_id'] : null;
                    $customerEmail = !empty($_POST['customer_email']) ? $_POST['customer_email'] : null;
                    $billingAddress = !empty($_POST['invoice_bill_address']) ? $_POST['invoice_bill_address'] : null;
                    $invoiceDate = !empty($_POST['invoice_date']) ? $_POST['invoice_date'] : null;
                    $dueDate = !empty($_POST['invoice_duedate']) ? $_POST['invoice_duedate'] : null;
                    $shippingAddress = !empty($_POST['invoice_ship_address']) ? $_POST['invoice_ship_address'] : null;
                    $shipVia = !empty($_POST['invoice_ship_via']) ? $_POST['invoice_ship_via'] : null;
                    $shipDate = !empty($_POST['invoice_ship_date']) ? $_POST['invoice_ship_date'] : null;
                    $trackNo = !empty($_POST['invoice_track_no']) ? $_POST['invoice_track_no'] : null;
                    $subTotal = !empty($_POST['sub_total']) ? $_POST['sub_total'] : null;
                    $totalTax = !empty($_POST['total_tax']) ? $_POST['total_tax'] : null;
                    $grandTotal = !empty($_POST['grand_total']) ? $_POST['grand_total'] : null;
                    $transactionNo = generateTransactionNo($pdo, 'invoice');
                    $tax_type = !empty($_POST['tax_type']) ? $_POST['tax_type'] : null;
                    $remarks = !empty($_POST['remarks']) ? $_POST['remarks'] : null;
                
                    // Insert into trans_invoice table
                    $sql = "
                        INSERT INTO trans_invoice (customer_id, customer_email, invoice_bill_address, invoice_date, invoice_duedate, invoice_shipping_address, 
                                                    invoice_ship_via, invoice_ship_date, invoice_track_no, total_amount, sales_tax, grand_total, transaction_no, tax_type, remarks) 
                        VALUES (:customer_id, :customer_email, :invoice_bill_address, :invoice_date, :invoice_duedate, :invoice_shipping_address, 
                                :invoice_ship_via, :invoice_ship_date, :invoice_track_no, :total_amount, :sales_tax, :grand_total, :transaction_no , :tax_type, :remarks)
                    ";
                    $stmtParams = [
                        'customer_id' => $customerID,
                        'customer_email' => $customerEmail,
                        'invoice_bill_address' => $billingAddress,
                        'invoice_date' => $invoiceDate,
                        'invoice_duedate' => $dueDate,
                        'invoice_shipping_address' => $shippingAddress,
                        'invoice_ship_via' => $shipVia,
                        'invoice_ship_date' => $shipDate,
                        'invoice_track_no' => $trackNo,
                        'total_amount' => $subTotal,
                        'sales_tax' => $totalTax,
                        'grand_total' => $grandTotal,
                        'transaction_no' => $transactionNo,
                        'tax_type' => $tax_type,
                        'remarks' => $remarks
                    ];
                    break;
    
                default:
                    throw new Exception('Invalid form type');
            }
    
            // Prepare and execute the main transaction SQL
            $stmt = $pdo->prepare($sql);
            $stmt->execute($stmtParams);

            if($formType == 'invoice'){
                $itemSQL = "
                    INSERT INTO trans_item (
                        transaction_no, product_sku, item_barcode, item_qty, 
                        item_rate, item_tax, item_amount, transaction_type, 
                        item_expiry, created_at
                    ) 
                    VALUES (
                        :transaction_no, :product_sku, :item_barcode, :item_qty, 
                        :item_rate, :item_tax, :item_amount, :transaction_type, 
                        :item_expiry, NOW()
                    )
                ";
                $itemStmt = $pdo->prepare($itemSQL);
                // Handle each item in the invoice
                foreach ($items as $item) {
                    $sku = $item['sku'];
                    $requestedQty = $item['qty'];
                    $rate = $item['rate'];
                    $amount = $item['amount'];
                    $tax = $item['tax'] ?? null;
    
                    // Fetch available items prioritized by expiry
                    $availabilitySQL = "
                        SELECT product_sku, item_barcode, item_expiry, 
                               SUM(CASE 
                                   WHEN transaction_type IN ('bill', 'expense') THEN item_qty 
                                   WHEN transaction_type = 'invoice' THEN -item_qty 
                                   ELSE 0 
                               END) AS available_qty
                        FROM trans_item
                        WHERE product_sku = :sku
                        GROUP BY product_sku, item_barcode, item_expiry
                        HAVING available_qty > 0
                        ORDER BY item_expiry ASC, item_barcode ASC
                    ";
                    $availabilityStmt = $pdo->prepare($availabilitySQL);
                    $availabilityStmt->execute(['sku' => $sku]);
                    $availableItems = $availabilityStmt->fetchAll(PDO::FETCH_ASSOC);
    
                    $remainingQty = $requestedQty;
    
                    // Suggest items from available stock
                    foreach ($availableItems as $stock) {
                        if ($remainingQty <= 0) {
                            break;
                        }
    
                        $suggestedQty = min($remainingQty, $stock['available_qty']);
                        $itemStmt->execute([
                            'transaction_no' => $transactionNo,
                            'product_sku' => $sku,
                            'item_barcode' => $stock['item_barcode'],
                            'item_qty' => $suggestedQty,
                            'item_rate' => $rate,
                            'item_tax' => $tax,
                            'item_amount' => ($rate * $suggestedQty),
                            'transaction_type' => $formType,
                            'item_expiry' => $stock['item_expiry'],
                        ]);
    
                        $remainingQty -= $suggestedQty;
                    }
    
                    // Handle case where requested quantity exceeds available stock
                    if ($remainingQty > 0) {
                        throw new Exception("Insufficient stock for product SKU: $sku. Requested: $requestedQty, Available: " . ($requestedQty - $remainingQty));
                    }
                }
            }else{
                // Prepare the SQL for inserting items into the trans_item table
                    $itemSql = "
                    INSERT INTO trans_item 
                    (transaction_no, product_sku, item_barcode, item_qty, item_rate, item_tax, item_amount, transaction_type, item_expiry, created_at) 
                    VALUES (:transaction_no, :product_sku, :item_barcode, :item_qty, :item_rate, :item_tax, :item_amount, :transaction_type, :item_expiry, NOW())
                ";
                $itemStmt = $pdo->prepare($itemSql);

                // Loop through each item and insert it
                foreach ($items as $item) {
                    if (!empty($item['sku']) && !empty($item['qty']) && !empty($item['rate']) && !empty($item['amount'])) {
                        $itemStmt->execute([
                            'transaction_no' => $transactionNo,
                            'product_sku' => $item['sku'],
                            'item_barcode' => $item['barcode'] ?? null,
                            'item_qty' => $item['qty'],
                            'item_rate' => $item['rate'],
                            'item_tax' => $item['tax'] ?? null,
                            'item_amount' => $item['amount'],
                            'transaction_type' => $formType,
                            'item_expiry' => $item['expiry'] ?? null  // Optional expiry date
                        ]);
                    } else {
                        // Log or handle invalid item case if necessary
                    }
                }
            }
            if (!empty($_FILES['attachments'])) {
                $fileUploadResults = handleFileUpload($_FILES['attachments'], $transactionNo);
                
                // Modified SQL to match your actual table structure
                $fileSql = "INSERT INTO file_upload 
                            (transaction_no, file_name, file_path, created_at) 
                            VALUES (:transaction_no, :file_name, :file_path, NOW())";
                $fileStmt = $pdo->prepare($fileSql);
                
                foreach ($fileUploadResults as $result) {
                    if ($result['success']) {
                        try {
                            $fileStmt->execute([
                                'transaction_no' => $transactionNo,
                                'file_name' => $result['fileName'],  // Original filename
                                'file_path' => $result['filePath']   // Path where file is stored
                            ]);
            
                            if ($fileStmt->rowCount() === 0) {
                                error_log("No rows were inserted for file: " . $result['fileName']);
                            }
                        } catch (Exception $e) {
                            error_log("Failed to insert file record: " . $e->getMessage());
                            // You might want to add this to see the actual SQL error
                            error_log("SQL Error: " . json_encode($fileStmt->errorInfo()));
                        }
                    } else {
                        error_log("File upload failed: " . $result['message']);
                    }
                }
            }
            // Commit the transaction
            $pdo->commit();
            return array('success' => true, 'message' => 'Transaction and items added successfully.');
    
        } catch (Exception $e) {
            // Roll back the transaction if any error occurs
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return array('success' => false, 'message' => 'Failed to add transaction: ' . $e->getMessage());
        }
}

function updateTransaction($pdo) {
    // Get form data from $_POST
    $formType = !empty($_POST['formType']) ? $_POST['formType'] : null;
    $items = !empty($_POST['items']) ? json_decode($_POST['items'], true) : [];
    $transactionNo = !empty($_POST['transacNo']) ? $_POST['transacNo'] : null;

    // Validate that items and transaction number are provided
    if (empty($items)) {
        return array('success' => false, 'message' => 'No items provided for this transaction.');
    }

    if (empty($transactionNo)) {
        return array('success' => false, 'message' => 'Transaction number is required.');
    }

    try {
        // Start a PDO transaction
        $pdo->beginTransaction();

        // Delete the existing transaction and associated items
        $deleteItemsSQL = "DELETE FROM trans_item WHERE transaction_no = :transaction_no";
        $deleteTransactionSQL = "DELETE FROM " . ($formType === 'bill' ? 'trans_bill' : ($formType === 'expense' ? 'trans_expense' : 'trans_invoice')) . " WHERE transaction_no = :transaction_no";
        $pdo->prepare($deleteItemsSQL)->execute(['transaction_no' => $transactionNo]);
        $pdo->prepare($deleteTransactionSQL)->execute(['transaction_no' => $transactionNo]);

        // Insert the new transaction
        switch ($formType) {
            case 'bill':
                $insertTransactionSQL = "
                    INSERT INTO trans_bill (
                        transaction_no, supplier_id, bill_address, bill_date, bill_due_date, bill_no, 
                        total_amount, sales_tax, grand_total, tax_type, remarks
                    ) VALUES (
                        :transaction_no, :supplier_id, :bill_address, :bill_date, :bill_due_date, :bill_no, 
                        :total_amount, :sales_tax, :grand_total, :tax_type, :remarks
                    )";
                $stmtParams = [
                    'transaction_no' => $transactionNo,
                    'supplier_id' => $_POST['billSupplier'] ?? null,
                    'bill_address' => $_POST['billAddress'] ?? null,
                    'bill_date' => $_POST['billDate'] ?? null,
                    'bill_due_date' => $_POST['billdueDate'] ?? null,
                    'bill_no' => !empty($_POST['billNo']) ? $_POST['billNo'] : null,
                    'total_amount' => $_POST['sub_total'] ?? 0,
                    'sales_tax' => $_POST['total_tax'] ?? 0,
                    'grand_total' => $_POST['grand_total'] ?? 0,
                    'tax_type' => $_POST['tax_type'] ?? null,
                    'remarks' => $_POST['remarks'] ?? null,
                ];
                break;

            case 'expense':
                $insertTransactionSQL = "
                    INSERT INTO trans_expense (
                        transaction_no, payee_id, expense_date, expense_payment_method, expense_no, 
                        total_amount, sales_tax, grand_total, tax_type, remarks
                    ) VALUES (
                        :transaction_no, :payee_id, :expense_date, :expense_payment_method, :expense_no, 
                        :total_amount, :sales_tax, :grand_total, :tax_type, :remarks
                    )";
                $stmtParams = [
                    'transaction_no' => $transactionNo,
                    'payee_id' => $_POST['payee_id'] ?? null,
                    'expense_date' => $_POST['expenseDate'] ?? null,
                    'expense_payment_method' => $_POST['expense_payment_method'] ?? null,
                    'expense_no' => $_POST['expenseNo'] ?? null,
                    'total_amount' => $_POST['sub_total'] ?? 0,
                    'sales_tax' => $_POST['total_tax'] ?? 0,
                    'grand_total' => $_POST['grand_total'] ?? 0,
                    'tax_type' => $_POST['tax_type'] ?? null,
                    'remarks' => $_POST['remarks'] ?? null,
                ];
                break;

            case 'invoice':
                $insertTransactionSQL = "
                    INSERT INTO trans_invoice (
                        transaction_no, customer_id, customer_email, invoice_bill_address, invoice_date, 
                        invoice_duedate, invoice_shipping_address, invoice_ship_via, invoice_ship_date, 
                        invoice_track_no, total_amount, sales_tax, grand_total, tax_type, remarks
                    ) VALUES (
                        :transaction_no, :customer_id, :customer_email, :invoice_bill_address, :invoice_date, 
                        :invoice_duedate, :invoice_shipping_address, :invoice_ship_via, :invoice_ship_date, 
                        :invoice_track_no, :total_amount, :sales_tax, :grand_total, :tax_type, :remarks
                    )";
                $stmtParams = [
                    'transaction_no' => $transactionNo,
                    'customer_id' => $_POST['customer_id'] ?? null,
                    'customer_email' => $_POST['customer_email'] ?? null,
                    'invoice_bill_address' => $_POST['invoice_bill_address'] ?? null,
                    'invoice_date' => $_POST['invoice_date'] ?? null,
                    'invoice_duedate' => $_POST['invoice_duedate'] ?? null,
                    'invoice_shipping_address' => $_POST['invoice_ship_address'] ?? null,
                    'invoice_ship_via' => $_POST['invoice_ship_via'] ?? null,
                    'invoice_ship_date' => $_POST['invoice_ship_date'] ?? null,
                    'invoice_track_no' => $_POST['invoice_track_no'] ?? null,
                    'total_amount' => $_POST['sub_total'] ?? 0,
                    'sales_tax' => $_POST['total_tax'] ?? 0,
                    'grand_total' => $_POST['grand_total'] ?? 0,
                    'tax_type' => $_POST['tax_type'] ?? null,
                    'remarks' => $_POST['remarks'] ?? null,
                ];
                break;

            default:
                throw new Exception('Invalid form type.');
        }

        $stmt = $pdo->prepare($insertTransactionSQL);
        $stmt->execute($stmtParams);

        // Insert the associated items
        $itemSQL = "
            INSERT INTO trans_item (
                transaction_no, product_sku, item_barcode, item_qty, item_rate, 
                item_tax, item_amount, transaction_type, item_expiry
            ) VALUES (
                :transaction_no, :product_sku, :item_barcode, :item_qty, :item_rate, 
                :item_tax, :item_amount, :transaction_type, :item_expiry
            )";
        $itemStmt = $pdo->prepare($itemSQL);

        foreach ($items as $item) {
            $itemStmt->execute([
                'transaction_no' => $transactionNo,
                'product_sku' => $item['sku'],
                'item_barcode' => $item['barcode'] ?? null,
                'item_qty' => $item['qty'],
                'item_rate' => $item['rate'],
                'item_tax' => $item['tax'] ?? null,
                'item_amount' => $item['amount'],
                'transaction_type' => $formType,
                'item_expiry' => $item['expiry'] ?? null,
            ]);
        }

        // Handle file attachments
        if (!empty($_FILES['attachments'])) {
            $fileUploadResults = handleFileUpload($_FILES['attachments'], $transactionNo);
            
            // Modified SQL to match your actual table structure
            $fileSql = "INSERT INTO file_upload 
                        (transaction_no, file_name, file_path, created_at) 
                        VALUES (:transaction_no, :file_name, :file_path, NOW())";
            $fileStmt = $pdo->prepare($fileSql);
            
            foreach ($fileUploadResults as $result) {
                if ($result['success']) {
                    try {
                        $fileStmt->execute([
                            'transaction_no' => $transactionNo,
                            'file_name' => $result['fileName'],  // Original filename
                            'file_path' => $result['filePath']   // Path where file is stored
                        ]);
        
                        if ($fileStmt->rowCount() === 0) {
                            error_log("No rows were inserted for file: " . $result['fileName']);
                        }
                    } catch (Exception $e) {
                        error_log("Failed to insert file record: " . $e->getMessage());
                        // You might want to add this to see the actual SQL error
                        error_log("SQL Error: " . json_encode($fileStmt->errorInfo()));
                    }
                } else {
                    error_log("File upload failed: " . $result['message']);
                }
            }
        }

        // Commit the transaction
        $pdo->commit();
        return array('success' => true, 'message' => 'Transaction successfully replaced.');

    } catch (Exception $e) {
        // Roll back the transaction in case of an error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return array('success' => false, 'message' => 'Error: ' . $e->getMessage());
    }
}




function generateTransactionNo($pdo, $transactionType) {
    // Define the table and prefix for each transaction type
    $table = '';
    $prefix = '';
    
    switch ($transactionType) {
        case 'bill':
            $table = 'trans_bill';
            $prefix = 'BILL';
            break;
        case 'expense':
            $table = 'trans_expense';
            $prefix = 'EXP';
            break;
        case 'invoice':
            $table = 'trans_invoice';
            $prefix = 'INV';
            break;
        default:
            throw new Exception('Invalid transaction type');
    }

    // Get current date in the format YYYYMMDD
    $currentDate = date('Ymd');

    // Fetch the last transaction_no for the same type and day
    $sql = "SELECT transaction_no FROM $table WHERE transaction_no LIKE :transaction_no ORDER BY transaction_no DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['transaction_no' => "$prefix-$currentDate%"]);
    $lastTransactionNo = $stmt->fetchColumn();

    // If no previous transaction is found, start with 001
    if (!$lastTransactionNo) {
        $newTransactionNo = $prefix . '-' . $currentDate . '-001';
    } else {
        // Extract the last counter and increment it
        $lastCounter = (int)substr($lastTransactionNo, -3);
        $newCounter = str_pad($lastCounter + 1, 3, '0', STR_PAD_LEFT);
        $newTransactionNo = $prefix . '-' . $currentDate . '-' . $newCounter;
    }

    return $newTransactionNo;
}

    
function handleFileUpload($fileInput, $transactionNo) {
    // Configuration
    $config = [
        'allowedTypes' => [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'application/pdf' => 'pdf'
        ],
        'maxFileSize' => 5 * 1024 * 1024, // 5MB
        'uploadDir' => __DIR__ . '/../../uploads/',
        'maxFilenameLength' => 255,
        'maxFiles' => 10 // Maximum number of files allowed per transaction
    ];

    // Initialize response array
    $responses = [];
    
    // Validate upload directory
    if (!validateUploadDirectory($config['uploadDir'])) {
        return [['success' => false, 'message' => 'Server configuration error: Upload directory not accessible']];
    }

    // Validate number of files
    if (count($fileInput['name']) > $config['maxFiles']) {
        return [['success' => false, 'message' => "Maximum {$config['maxFiles']} files allowed per transaction"]];
    }

    // Process each file
    foreach ($fileInput['name'] as $index => $name) {
        $file = [
            'name' => $name,
            'type' => $fileInput['type'][$index],
            'tmp_name' => $fileInput['tmp_name'][$index],
            'error' => $fileInput['error'][$index],
            'size' => $fileInput['size'][$index],
        ];

        // Initial validation
        $validationResult = validateFile($file, $config);
        if (!$validationResult['success']) {
            $responses[] = [
                'success' => false,
                'message' => $validationResult['message'],
                'fileName' => $file['name']
            ];
            continue;
        }

        // Generate secure filename
        $fileInfo = generateSecureFilename($file, $transactionNo, $config['allowedTypes'][$file['type']]);
        
        // Create final filepath
        $yearMonth = date('Y/m');
        $uploadPath = $config['uploadDir'] . $yearMonth . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $filePath = $uploadPath . $fileInfo['filename'];

        // Process upload
        try {
            if (processFileUpload($file['tmp_name'], $filePath)) {
                $responses[] = [
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'fileName' => $fileInfo['originalName'],
                    'filePath' => $yearMonth . '/' . $fileInfo['filename'],
                    'fileType' => $file['type'],
                    'fileSize' => $file['size']
                ];
            } else {
                throw new Exception('Failed to move uploaded file');
            }
        } catch (Exception $e) {
            $responses[] = [
                'success' => false,
                'message' => 'File upload failed: ' . $e->getMessage(),
                'fileName' => $file['name']
            ];
        }
    }

    return $responses;
}

function validateUploadDirectory($uploadDir) {
    if (!is_dir($uploadDir)) {
        try {
            mkdir($uploadDir, 0755, true);
        } catch (Exception $e) {
            error_log("Failed to create upload directory: " . $e->getMessage());
            return false;
        }
    }
    return is_writable($uploadDir);
}

function validateFile($file, $config) {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessage = getUploadErrorMessage($file['error']);
        return ['success' => false, 'message' => $errorMessage];
    }

    // Validate file existence and readability
    if (!is_uploaded_file($file['tmp_name']) || !is_readable($file['tmp_name'])) {
        return ['success' => false, 'message' => 'Invalid upload or file not readable'];
    }

    // Check file type
    if (!isset($config['allowedTypes'][$file['type']])) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }

    // Verify MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    if (!isset($config['allowedTypes'][$mimeType])) {
        return ['success' => false, 'message' => 'File type verification failed'];
    }

    // Check file size
    if ($file['size'] > $config['maxFileSize']) {
        return ['success' => false, 'message' => 'File size exceeds limit'];
    }

    // Validate filename length
    if (strlen($file['name']) > $config['maxFilenameLength']) {
        return ['success' => false, 'message' => 'Filename too long'];
    }

    return ['success' => true];
}

function generateSecureFilename($file, $transactionNo, $extension) {
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    // Sanitize original filename
    $sanitizedName = preg_replace('/[^a-zA-Z0-9-_.]/', '', $originalName);
    
    // Create unique filename
    $uniqueId = uniqid('', true);
    $hash = substr(hash('sha256', $file['tmp_name'] . $uniqueId), 0, 8);
    
    return [
        'filename' => sprintf('%s_%s_%s.%s', 
            $transactionNo,
            $hash,
            $uniqueId,
            $extension
        ),
        'originalName' => $file['name']
    ];
}

function processFileUpload($tempFile, $destination) {
    // Ensure atomic file operations
    if (!copy($tempFile, $destination . '.part')) {
        return false;
    }

    if (!rename($destination . '.part', $destination)) {
        unlink($destination . '.part');
        return false;
    }

    // Set proper file permissions
    chmod($destination, 0644);
    
    return true;
}

function getUploadErrorMessage($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds PHP maximum file size limit',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds form maximum file size limit',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
    ];
    
    return isset($errors[$errorCode]) 
        ? $errors[$errorCode] 
        : 'Unknown upload error';
}


function RetrieveBarcodeDetails(){
    
}

// Define the generatePaymentRefNo function
function generatePaymentRefNo($pdo) {
    $prefix = 'PAY';
    $currentDate = date('Ymd');

    $sql = "SELECT payment_refno FROM payments WHERE payment_refno LIKE :payment_refno ORDER BY payment_refno DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['payment_refno' => "$prefix-$currentDate%"]);
    $lastPaymentRefNo = $stmt->fetchColumn();

    if (!$lastPaymentRefNo) {
        $newPaymentRefNo = $prefix . '-' . $currentDate . '-001';
    } else {
        $lastCounter = (int)substr($lastPaymentRefNo, -3);
        $newCounter = str_pad($lastCounter + 1, 3, '0', STR_PAD_LEFT);
        $newPaymentRefNo = $prefix . '-' . $currentDate . '-' . $newCounter;
    }

    return $newPaymentRefNo;
}

// function getTransactionDetails($pdo) {
//     try {
//         $transactionType = !empty($_POST['transactionType']) ? $_POST['transactionType'] : null;
//         $transactionNo = !empty($_POST['transactionNo']) ? $_POST['transactionNo'] : null;
//         // Initialize an empty array to hold the transaction details
//         $transactionDetails = [];

//         // Determine which tables to query based on the transaction type
//         switch ($transactionType) {
//             case 'bill':
//                 $transactionTable = 'trans_bill';
//                 $transactionQuery = "SELECT * FROM $transactionTable WHERE transaction_no = :transactionNo";
//                 break;

//             case 'expense':
//                 $transactionTable = 'trans_expense';
//                 $transactionQuery = "SELECT * FROM $transactionTable WHERE transaction_no = :transactionNo";
//                 break;

//             case 'invoice':
//                 $transactionTable = 'trans_invoice';
//                 $transactionQuery = "SELECT * FROM $transactionTable WHERE transaction_no = :transactionNo";
//                 break;

//             default:
//                 throw new Exception("Invalid transaction type");
//         }

//         // Prepare and execute the main transaction query
//         $stmt = $pdo->prepare($transactionQuery);
//         $stmt->execute(['transactionNo' => $transactionNo]);
//         $transactionDetails['transaction'] = $stmt->fetch(PDO::FETCH_ASSOC);

//         // If no transaction was found, return an empty result
//         if (!$transactionDetails['transaction']) {
//             return array("message" => "Transaction not found.");
//         }

//         // Retrieve associated items from trans_item table
//         $itemsQuery = "
//             SELECT ti.*, p.product_name 
//             FROM trans_item AS ti
//             JOIN product AS p ON ti.product_sku = p.product_sku
//             WHERE ti.transaction_no = :transactionNo
//         ";
//         $stmt = $pdo->prepare($itemsQuery);
//         $stmt->execute(['transactionNo' => $transactionNo]);
//         $transactionDetails['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         return $transactionDetails;

//     } catch (PDOException $e) {
//         // Handle database errors
//         echo "Error: " . $e->getMessage();
//         return array(); // Return an empty array if an error occurs
//     }
// }
function getTransactionDetails($pdo) {
    try {
        $transactionType = !empty($_POST['transactionType']) ? $_POST['transactionType'] : null;
        $transactionNo = !empty($_POST['transactionNo']) ? $_POST['transactionNo'] : null;

        // Initialize an empty array to hold the transaction details
        $transactionDetails = [];

        // Determine which tables to query based on the transaction type
        switch ($transactionType) {
            case 'bill':
                $transactionTable = 'trans_bill';
                $transactionQuery = "SELECT * FROM $transactionTable WHERE transaction_no = :transactionNo";
                break;

            case 'expense':
                $transactionTable = 'trans_expense';
                $transactionQuery = "SELECT * FROM $transactionTable WHERE transaction_no = :transactionNo";
                break;

            case 'invoice':
                $transactionTable = 'trans_invoice';
                $transactionQuery = "SELECT * FROM $transactionTable WHERE transaction_no = :transactionNo";
                break;

            default:
                throw new Exception("Invalid transaction type");
        }

        // Prepare and execute the main transaction query
        $stmt = $pdo->prepare($transactionQuery);
        $stmt->execute(['transactionNo' => $transactionNo]);
        $transactionDetails['transaction'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no transaction was found, return an empty result
        if (!$transactionDetails['transaction']) {
            return array("message" => "Transaction not found.");
        }

        // Retrieve associated items from the trans_item table
        $itemsQuery = "
            SELECT ti.*, p.product_name 
            FROM trans_item AS ti
            JOIN product AS p ON ti.product_sku = p.product_sku
            WHERE ti.transaction_no = :transactionNo
        ";
        $stmt = $pdo->prepare($itemsQuery);
        $stmt->execute(['transactionNo' => $transactionNo]);
        $transactionDetails['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retrieve associated files from the file_upload table
        $filesQuery = "
            SELECT file_name, file_path, created_at 
            FROM file_upload 
            WHERE transaction_no = :transactionNo
        ";
        $stmt = $pdo->prepare($filesQuery);
        $stmt->execute(['transactionNo' => $transactionNo]);
        $transactionDetails['files'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $transactionDetails;

    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
        return array(); // Return an empty array if an error occurs
    } catch (Exception $e) {
        // Handle other exceptions
        echo "Error: " . $e->getMessage();
        return array();
    }
}

function generatePaymentReference($pdo) {
    try {
        // Define the prefix with current date in "YYYYMMDD" format
        $prefix = "P" . date("Ymd");
        
        // Query to find the highest reference number for today's date
        $query = "SELECT MAX(payment_refno) AS max_reference FROM payments WHERE payment_refno LIKE :prefix";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':prefix' => $prefix . '%']);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Determine the new reference number
        if ($result && $result['max_reference']) {
            // Extract the last numeric part and increment by 1
            $lastNumber = (int)substr($result['max_reference'], -4) + 1;
        } else {
            $lastNumber = 1; // Start from 1 if no references exist for today
        }
        
        // Pad the number to ensure it’s always four digits (e.g., "0001")
        $reference = $prefix . str_pad($lastNumber, 4, "0", STR_PAD_LEFT);
        
        return $reference;
    } catch (PDOException $e) {
        // Handle database connection error
        echo "Error: " . $e->getMessage();
        return ""; // Return an empty string if an error occurs
    }
}
// function createPaymentTransaction($pdo) {
//     try {
//         // Retrieve POST data
//         $paymentRefNo = $_POST['payment_ref_no'];
//         $paymentAccount = $_POST['payment_account'];
//         $paymentDate = $_POST['payment_date'];
//         $paymentAmounts = $_POST['payment_amounts']; // Array of payment details

//         // Start a transaction
//         $pdo->beginTransaction();

//         // Prepare the SQL statement for inserting payment transactions
//         $stmt = $pdo->prepare("INSERT INTO payments (transaction_no, payment_refno, payment_account, payment_amount, payment_date) 
//                                VALUES (:transaction_no, :payment_refno, :payment_account, :payment_amount, :payment_date)");

//         // Prepare the SQL statement for updating the payment status in trans_bill
//         $updateStatusStmt = $pdo->prepare("UPDATE trans_bill SET payment_status = :payment_status WHERE transaction_no = :transaction_no");

//         foreach ($paymentAmounts as $payment) {
//             $transactionNo = $payment['transaction_no'];
//             $amount = $payment['amount'];

//             // Insert payment transaction
//             $stmt->execute([
//                 'transaction_no' => $transactionNo,
//                 'payment_refno' => $paymentRefNo,
//                 'payment_account' => $paymentAccount,
//                 'payment_amount' => $amount,
//                 'payment_date' => $paymentDate
//             ]);

//             // Calculate the total payments made for the transaction
//             $totalPaymentsStmt = $pdo->prepare("SELECT COALESCE(SUM(payment_amount), 0) AS total_payments 
//                                                 FROM payments WHERE transaction_no = :transaction_no");
//             $totalPaymentsStmt->execute(['transaction_no' => $transactionNo]);
//             $totalPayments = $totalPaymentsStmt->fetchColumn();

//             // Retrieve the grand total for the bill
//             $grandTotalStmt = $pdo->prepare("SELECT grand_total FROM trans_bill WHERE transaction_no = :transaction_no");
//             $grandTotalStmt->execute(['transaction_no' => $transactionNo]);
//             $grandTotal = $grandTotalStmt->fetchColumn();

//             // Determine the new payment status
//             $paymentStatus = ($totalPayments >= $grandTotal) ? 'paid' : 'partial';

//             // Update the payment status in trans_bill
//             $updateStatusStmt->execute([
//                 'payment_status' => $paymentStatus,
//                 'transaction_no' => $transactionNo
//             ]);
//         }

//         // Commit the transaction
//         $pdo->commit();

//         return array('success' => true, 'message' => 'Payment transaction created and bill updated successfully!');
//     } catch (Exception $e) {
//         // Roll back the transaction if an error occurs
//         $pdo->rollBack();
//         return array('success' => false, 'message' => 'Error: ' . $e->getMessage());
//     }
// }
function createPaymentTransaction($pdo) {
    try {
        // Retrieve POST data
        $paymentRefNo = $_POST['payment_ref_no'];
        $paymentAccount = $_POST['payment_account'];
        $paymentDate = $_POST['payment_date'];
        $paymentAmounts = $_POST['payment_amounts']; // Array of payment details

        // Start a transaction
        $pdo->beginTransaction();

        // Prepare the SQL statement for inserting payment transactions
        $stmt = $pdo->prepare("INSERT INTO payments (transaction_no, payment_refno, payment_account, payment_amount, payment_date) 
                               VALUES (:transaction_no, :payment_refno, :payment_account, :payment_amount, :payment_date)");

        // Prepare the SQL statement for updating the payment status in trans_bill
        $updateStatusStmt = $pdo->prepare("UPDATE trans_bill SET payment_status = :payment_status WHERE transaction_no = :transaction_no");

        foreach ($paymentAmounts as $payment) {
            $transactionNo = $payment['transaction_no'];
            $amount = $payment['amount'];

            // Calculate the total payments made for the transaction
            $totalPaymentsStmt = $pdo->prepare("SELECT COALESCE(SUM(payment_amount), 0) AS total_payments 
                                                FROM payments WHERE transaction_no = :transaction_no");
            $totalPaymentsStmt->execute(['transaction_no' => $transactionNo]);
            $totalPayments = $totalPaymentsStmt->fetchColumn();

            // Retrieve the grand total for the bill
            $grandTotalStmt = $pdo->prepare("SELECT grand_total FROM trans_bill WHERE transaction_no = :transaction_no");
            $grandTotalStmt->execute(['transaction_no' => $transactionNo]);
            $grandTotal = $grandTotalStmt->fetchColumn();

            // Calculate remaining balance
            $remainingBalance = $grandTotal - $totalPayments;

            // Check if payment exceeds the remaining balance
            if ($amount > $remainingBalance) {
                throw new Exception("Payment amount ($amount) exceeds the remaining balance ($remainingBalance) for transaction $transactionNo.");
            }

            // Insert payment transaction
            $stmt->execute([
                'transaction_no' => $transactionNo,
                'payment_refno' => $paymentRefNo,
                'payment_account' => $paymentAccount,
                'payment_amount' => $amount,
                'payment_date' => $paymentDate
            ]);

            // Update the payment status in trans_bill
            $totalPayments += $amount;
            $paymentStatus = ($totalPayments >= $grandTotal) ? 'paid' : 'partial';

            $updateStatusStmt->execute([
                'payment_status' => $paymentStatus,
                'transaction_no' => $transactionNo
            ]);
        }

        // Commit the transaction
        $pdo->commit();

        return array('success' => true, 'message' => 'Payment transaction created and bill updated successfully!');
    } catch (Exception $e) {
        // Roll back the transaction if an error occurs
        $pdo->rollBack();
        return array('success' => false, 'message' => 'Error: ' . $e->getMessage());
    }
}
function generateInventoryStockReport($pdo) {
    try {
        // Get the stock level from the POST request
        $stockLevel = !empty($_POST['stock_level']) ? (int)$_POST['stock_level'] : null;

        // Ensure stock level is valid
        if (!in_array($stockLevel, [1, 2, 3])) {
            return array('success' => false, 'message' => 'Invalid stock level provided.');
        }

        $query = "
            SELECT
                a.product_name,
                COALESCE(
                    SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END) 
                    - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END),
                    0
                ) AS stock,
                CASE
                    WHEN COALESCE(
                        SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END) 
                        - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END),
                        0
                    ) >= a.product_min THEN 1
                    WHEN COALESCE(
                        SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END) 
                        - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END),
                        0
                    ) < a.product_min
                    AND COALESCE(
                        SUM(CASE WHEN g.transaction_type IN ('bill', 'expense') THEN g.item_qty ELSE 0 END) 
                        - SUM(CASE WHEN g.transaction_type = 'invoice' THEN g.item_qty ELSE 0 END),
                        0
                    ) > 0 THEN 2
                    ELSE 3
                END AS status
            FROM product a
            LEFT JOIN trans_item g ON g.product_sku = a.product_sku
            GROUP BY a.product_id, a.product_name
            HAVING status = :stockLevel;
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['stockLevel' => $stockLevel]);

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Commit the transaction
        return array(
            'success' => true,
            'message' => 'Report has been generated!',
            'data' => $products
        );
    } catch (Exception $e) {
        // Roll back the transaction if an error occurs
        return array(
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        );
    }
}
// function generateValuationStockReport($pdo) {
//     try {
//         // Query to retrieve stock report details
//         $query = "
//             SELECT 
//                 p.product_sku,
//                 p.product_name,
//                 p.product_pp AS purchase_price,
//                 COALESCE(
//                     SUM(CASE 
//                         WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty 
//                         WHEN t.transaction_type = 'invoice' THEN -t.item_qty 
//                         ELSE 0 
//                     END), 
//                     0
//                 ) AS stock,
//                 COALESCE(
//                     SUM(CASE 
//                         WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty * t.item_rate 
//                         ELSE 0 
//                     END), 
//                     0
//                 ) AS total_value_based_on_item_rate,
//                 COALESCE(
//                     SUM(CASE 
//                         WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty 
//                         ELSE 0 
//                     END) * p.product_pp, 
//                     0
//                 ) AS total_value_based_on_product_pp
//             FROM product p
//             LEFT JOIN trans_item t ON t.product_sku = p.product_sku
//             GROUP BY p.product_sku, p.product_name, p.product_pp;
//         ";

//         $stmt = $pdo->prepare($query);
//         $stmt->execute();

//         $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         // Return success and the data
//         return array(
//             'success' => true,
//             'message' => 'Stock report generated successfully.',
//             'data' => $products
//         );
//     } catch (Exception $e) {
//         // Return error message
//         return array(
//             'success' => false,
//             'message' => 'Error: ' . $e->getMessage()
//         );
//     }
// }
function generateValuationStockReport($pdo, $dateFilter, $startDate, $endDate) {
    try {
        // Set initial date filter condition
        $dateCondition = '';
        
        if ($dateFilter == 'custom' && !empty($startDate) && !empty($endDate)) {
            // If custom range is selected, validate and parse the date range
            $startDateFormatted = date('Y-m-d', strtotime($startDate));
            $endDateFormatted = date('Y-m-d', strtotime($endDate));
            $dateCondition = "AND (b.bill_date BETWEEN :startDate AND :endDate OR e.expense_date BETWEEN :startDate AND :endDate OR i.invoice_date BETWEEN :startDate AND :endDate)";
        }

        // Query to retrieve stock report details
        $query = "
            SELECT 
                p.product_sku,
                p.product_name,
                p.product_pp AS purchase_price,
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty 
                        WHEN t.transaction_type = 'invoice' THEN -t.item_qty 
                        ELSE 0 
                    END), 
                    0
                ) AS stock,
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty * t.item_rate 
                        ELSE 0 
                    END), 
                    0
                ) AS total_value_based_on_item_rate,
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty 
                        ELSE 0 
                    END) * p.product_pp, 
                    0
                ) AS total_value_based_on_product_pp
            FROM product p
            LEFT JOIN trans_item t ON t.product_sku = p.product_sku
            LEFT JOIN trans_bill b ON t.transaction_no = b.transaction_no
            LEFT JOIN trans_expense e ON t.transaction_no = e.transaction_no
            LEFT JOIN trans_invoice i ON t.transaction_no = i.transaction_no
            WHERE 1 = 1
            $dateCondition
            GROUP BY p.product_sku, p.product_name, p.product_pp;
        ";

        $stmt = $pdo->prepare($query);

        // Bind parameters if custom date range is applied
        if ($dateCondition) {
            $stmt->bindParam(':startDate', $startDateFormatted);
            $stmt->bindParam(':endDate', $endDateFormatted);
        }

        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return success and the data
        return array(
            'success' => true,
            'message' => 'Stock report generated successfully.',
            'data' => $products
        );
    } catch (Exception $e) {
        // Return error message
        return array(
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        );
    }
}
function generateStockMovementReport($pdo, $dateFilter, $startDate, $endDate) {
    try {
        // Set initial date filter condition
        $dateCondition = '';
        
        if ($dateFilter == 'custom' && !empty($startDate) && !empty($endDate)) {
            // If custom range is selected, validate and parse the date range
            $startDateFormatted = date('Y-m-d', strtotime($startDate));
            $endDateFormatted = date('Y-m-d', strtotime($endDate));
            $dateCondition = "AND (b.bill_date BETWEEN :startDate AND :endDate OR e.expense_date BETWEEN :startDate AND :endDate OR i.invoice_date BETWEEN :startDate AND :endDate)";
        }

        // Query to retrieve stock movement report details
        $query = "SELECT 
                p.product_sku,
                p.product_name,
                
                -- Total quantity in (bill + expense)
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty 
                        ELSE 0 
                    END), 
                    0
                ) AS quantity_in,
                
                -- Total quantity out (invoice)
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type = 'invoice' THEN -t.item_qty 
                        ELSE 0 
                    END), 
                    0
                ) AS quantity_out,
                
                -- Total purchase value (based on item rate for bills and expenses)
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty * t.item_rate 
                        ELSE 0 
                    END), 
                    0
                ) AS total_purchase_value,
                
                -- Total sale value (based on item rate for invoices)
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type = 'invoice' THEN t.item_qty * t.item_rate 
                        ELSE 0 
                    END), 
                    0
                ) AS total_sale_value,
                
                -- Total purchase value (based on product purchase price (PP) for bills and expenses)
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type IN ('bill', 'expense') THEN t.item_qty * p.product_pp
                        ELSE 0 
                    END), 
                    0
                ) AS total_purchase_value_pp,
                
                -- Total sale value (based on product purchase price (PP) for invoices)
                COALESCE(
                    SUM(CASE 
                        WHEN t.transaction_type = 'invoice' THEN t.item_qty * p.product_pp
                        ELSE 0 
                    END), 
                    0
                ) AS total_sale_value_pp
            FROM product p
            LEFT JOIN trans_item t ON t.product_sku = p.product_sku
            LEFT JOIN trans_bill b ON t.transaction_no = b.transaction_no
            LEFT JOIN trans_expense e ON t.transaction_no = e.transaction_no
            LEFT JOIN trans_invoice i ON t.transaction_no = i.transaction_no
            WHERE 1 = 1
            $dateCondition
            GROUP BY p.product_sku, p.product_name;";

        $stmt = $pdo->prepare($query);

        // Bind parameters if custom date range is applied
        if ($dateCondition) {
            $stmt->bindParam(':startDate', $startDateFormatted);
            $stmt->bindParam(':endDate', $endDateFormatted);
        }

        $stmt->execute();

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return success and the data
        return array(
            'success' => true,
            'message' => 'Stock movement report generated successfully.',
            'data' => $products
        );
    } catch (Exception $e) {
        // Return error message
        return array(
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        );
    }
}
function getProductTransactionsReport($pdo) {
    try {
        $product_sku = !empty($_POST['product_sku']) ? $_POST['product_sku'] : null;
        $dateFilter = !empty($_POST['dateFilter']) ? $_POST['dateFilter'] : null;
        $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        // Base SQL query
        $sql = "SELECT 
                    t.transaction_no,
                    t.transaction_type,
                    CASE
                        WHEN t.transaction_type IN ('bill', 'expense') THEN s.vendor_name
                        WHEN t.transaction_type = 'invoice' THEN c.customer_name
                        ELSE 'Unknown'
                    END AS p_name,
                    t.created_at,
                    t.item_qty,
                    t.item_rate,
                    (t.item_qty * t.item_rate) AS total_amount
                FROM trans_item t
                LEFT JOIN trans_bill b ON t.transaction_no = b.transaction_no AND t.transaction_type = 'bill'
                LEFT JOIN trans_expense e ON t.transaction_no = e.transaction_no AND t.transaction_type = 'expense'
                LEFT JOIN trans_invoice i ON t.transaction_no = i.transaction_no AND t.transaction_type = 'invoice'
                LEFT JOIN supplier s ON (b.supplier_id = s.id OR e.payee_id = s.id)  -- Supplier for Bill and Expense
                LEFT JOIN customer c ON i.customer_id = c.id                         -- Customer for Invoice
                WHERE t.product_sku = :product_sku";

        // Add date filter if it's 'custom' and both start_date and end_date are provided
        if ($dateFilter === 'custom' && !empty($start_date) && !empty($end_date)) {
            // Format dates to 'Y-m-d' if not already in that format
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = date('Y-m-d', strtotime($end_date));

            // Append date condition to SQL query
            $sql .= " AND DATE(t.created_at) BETWEEN :start_date AND :end_date";
        }

        // Order results by creation date
        $sql .= " ORDER BY t.created_at DESC";

        // Prepare the SQL query
        $stmt = $pdo->prepare($sql);

        // Bind product_sku parameter
        $stmt->bindParam(':product_sku', $product_sku, PDO::PARAM_STR);

        // If date filter is custom, bind the start_date and end_date parameters
        if ($dateFilter === 'custom' && !empty($start_date) && !empty($end_date)) {
            $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        }

        // Debugging: Log SQL query and parameters
        error_log("SQL Query: $sql");
        error_log("Parameters: " . json_encode([
            'product_sku' => $product_sku,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]));

        // Execute the query
        $stmt->execute();

        // Return results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if results are empty
        if (empty($results)) {
            return ['message' => 'No data available for the selected filter.'];
        }

        return $results;
        
    } catch (PDOException $e) {
        // Log error and return an error message
        error_log("Error in getProductTransactionsReport: " . $e->getMessage());
        return ['error' => 'Error retrieving data: ' . $e->getMessage()];
    }
}

function voidTransaction($pdo) {
    // Retrieve transaction type and number from POST request
    $transac_type = $_POST['transac_type'] ?? null;
    $transac_no = $_POST['transac_no'] ?? null;

    if (!$transac_type || !$transac_no) {
        return ['success' => false, 'message' => 'Invalid transaction type or number'];
    }

    try {
        // Determine the table and query to update based on transaction type
        if ($transac_type === 'expense') {
            $query = "UPDATE trans_expense SET isVoid = 1 WHERE transaction_no = :transaction_no";
        } elseif ($transac_type === 'invoice') {
            $query = "UPDATE trans_invoice SET isVoid = 1 WHERE transaction_no = :transaction_no";
        } else {
            return ['success' => false, 'message' => 'Unknown transaction type'];
        }

        // Prepare and execute the query
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':transaction_no', $transac_no, PDO::PARAM_STR);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            return [
                'success' => true,
                'message' => "Transaction No: $transac_no\nType: $transac_type\nhas been voided!"
            ];
        } else {
            return [
                'success' => false,
                'message' => "No matching transaction found or already voided"
            ];
        }
    } catch (Exception $e) {
        // Handle exceptions
        return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
    }
}

// function deleteTransaction($pdo) {
//     // Retrieve transaction type and number from POST request
//     $transac_type = $_POST['transac_type'] ?? null;
//     $transac_no = $_POST['transac_no'] ?? null;

//     if (!$transac_type || !$transac_no) {
//         return ['success' => false, 'message' => 'Invalid transaction type or number'];
//     }

//     try {
//         // Begin transaction
//         $pdo->beginTransaction();

//         // Determine the main table query based on transaction type
//         if ($transac_type === 'bill') {
//             $mainQuery = "DELETE FROM trans_bill WHERE transaction_no = :transaction_no";
//         } elseif ($transac_type === 'expense') {
//             $mainQuery = "DELETE FROM trans_expense WHERE transaction_no = :transaction_no";
//         } elseif ($transac_type === 'invoice') {
//             $mainQuery = "DELETE FROM trans_invoice WHERE transaction_no = :transaction_no";
//         } else {
//             return ['success' => false, 'message' => 'Unknown transaction type'];
//         }

//         // Delete from main transaction table
//         $stmt = $pdo->prepare($mainQuery);
//         $stmt->bindParam(':transaction_no', $transac_no, PDO::PARAM_STR);
//         $stmt->execute();

//         // Check if the main transaction was found and deleted
//         if ($stmt->rowCount() === 0) {
//             $pdo->rollBack();
//             return [
//                 'success' => false,
//                 'message' => "Transaction No: $transac_no of type $transac_type not found or already deleted."
//             ];
//         }

//         // Delete associated items from `trans_item`
//         $itemQuery = "DELETE FROM trans_item WHERE transaction_no = :transaction_no";
//         $itemStmt = $pdo->prepare($itemQuery);
//         $itemStmt->bindParam(':transaction_no', $transac_no, PDO::PARAM_STR);
//         $itemStmt->execute();

//         // Commit transaction
//         $pdo->commit();

//         return [
//             'success' => true,
//             'message' => "Transaction No: $transac_no of type $transac_type and all associated items have been successfully deleted!"
//         ];
//     } catch (Exception $e) {
//         // Roll back transaction in case of error
//         $pdo->rollBack();
//         return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
//     }
// }

function deleteTransaction($pdo) {
    // Retrieve transaction type and number from POST request
    $transac_type = $_POST['transac_type'] ?? null;
    $transac_no = $_POST['transac_no'] ?? null;

    if (!$transac_type || !$transac_no) {
        return ['success' => false, 'message' => 'Invalid transaction type or number'];
    }

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Determine the main table query based on transaction type
        if ($transac_type === 'bill') {
            $mainQuery = "DELETE FROM trans_bill WHERE transaction_no = :transaction_no";
        } elseif ($transac_type === 'expense') {
            $mainQuery = "DELETE FROM trans_expense WHERE transaction_no = :transaction_no";
        } elseif ($transac_type === 'invoice') {
            $mainQuery = "DELETE FROM trans_invoice WHERE transaction_no = :transaction_no";
        } else {
            return ['success' => false, 'message' => 'Unknown transaction type'];
        }

        // Delete from main transaction table
        $stmt = $pdo->prepare($mainQuery);
        $stmt->bindParam(':transaction_no', $transac_no, PDO::PARAM_STR);
        $stmt->execute();

        // Check if the main transaction was found and deleted
        if ($stmt->rowCount() === 0) {
            $pdo->rollBack();
            return [
                'success' => false,
                'message' => "Transaction No: $transac_no of type $transac_type not found or already deleted."
            ];
        }

        // Delete associated items from `trans_item`
        $itemQuery = "DELETE FROM trans_item WHERE transaction_no = :transaction_no";
        $itemStmt = $pdo->prepare($itemQuery);
        $itemStmt->bindParam(':transaction_no', $transac_no, PDO::PARAM_STR);
        $itemStmt->execute();

        // Handle file deletion
        $fileQuery = "SELECT file_path FROM file_upload WHERE transaction_no = :transaction_no";
        $fileStmt = $pdo->prepare($fileQuery);
        $fileStmt->bindParam(':transaction_no', $transac_no, PDO::PARAM_STR);
        $fileStmt->execute();
        $files = $fileStmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($files as $file) {
            $fullPath = __DIR__ . '/../../uploads/' . $file; // Adjust the base path if needed
            if (file_exists($fullPath)) {
                unlink($fullPath); // Delete the file from the filesystem
            }
        }

        // Delete file records from `file_upload`
        $deleteFilesQuery = "DELETE FROM file_upload WHERE transaction_no = :transaction_no";
        $deleteFilesStmt = $pdo->prepare($deleteFilesQuery);
        $deleteFilesStmt->bindParam(':transaction_no', $transac_no, PDO::PARAM_STR);
        $deleteFilesStmt->execute();

        // Commit transaction
        $pdo->commit();

        return [
            'success' => true,
            'message' => "Transaction No: $transac_no of type $transac_type, associated items, and files have been successfully deleted!"
        ];
    } catch (Exception $e) {
        // Roll back transaction in case of error
        $pdo->rollBack();
        return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
    }
}


// function generateSalesReport($pdo, $filters) {
//     try {
//         // Build the base query
//         $query = "
//             SELECT 
//                 c.category_name AS CategoryName, 
//                 p.product_sku AS ProductSKU, 
//                 p.product_name AS ProductName, 
//                 SUM(ti.item_qty) AS Qty, 
//                 SUM(ti.item_amount) AS Amount, 
//                 ROUND(SUM(ti.item_amount) * 100.0 / 
//                       (SELECT SUM(item_amount) FROM trans_item WHERE transaction_type = 'invoice'), 2) AS PercentageOfSales, 
//                 ROUND(AVG(ti.item_rate), 2) AS AvgPrice 
//             FROM 
//                 trans_item ti 
//             LEFT JOIN product p ON ti.product_sku = p.product_sku 
//             LEFT JOIN category c ON p.category_id = c.category_id 
//             WHERE 1=1
//         ";

//         // Add transaction type filter if provided
//         if (!empty($filters['transactionType']) && $filters['transactionType'] !== 'none') {
//             $query .= " AND ti.transaction_type = :transactionType";
//         }

//         // Add date range filter if provided
//         if (!empty($filters['dateFilter']) && $filters['dateFilter'] === 'custom') {
//             $query .= " AND DATE(ti.created_at) BETWEEN :startDate AND :endDate";
//         }

//         // Group by category and product
//         $query .= " GROUP BY c.category_name, p.product_sku ORDER BY c.category_name, p.product_name";

//         // Prepare the query
//         $stmt = $pdo->prepare($query);

//         // Bind transaction type filter
//         if (!empty($filters['transactionType']) && $filters['transactionType'] !== 'none') {
//             $stmt->bindParam(':transactionType', $filters['transactionType'], PDO::PARAM_STR);
//         }

//         // Bind date range filters
//         if (!empty($filters['dateFilter']) && $filters['dateFilter'] === 'custom') {
//             $stmt->bindParam(':startDate', $filters['startDate'], PDO::PARAM_STR);
//             $stmt->bindParam(':endDate', $filters['endDate'], PDO::PARAM_STR);
//         }

//         // Execute the query
//         $stmt->execute();

//         // Fetch the data
//         $reportData = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         // Group data by category
//         $groupedData = [];
//         foreach ($reportData as $row) {
//             $groupedData[$row['CategoryName']][] = $row;
//         }

//         return ['success' => true, 'summary' => $groupedData];
//     } catch (Exception $e) {
//         return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
//     }
// }


function generateSalesReport($pdo, $filters) {
    try {
        // Base query for summary
        $query = "
            SELECT 
                c.category_name AS CategoryName, 
                p.product_sku AS ProductSKU, 
                p.product_name AS ProductName, 
                SUM(
                    CASE 
                        WHEN ti.transaction_type = 'invoice' THEN -ti.item_qty 
                        WHEN ti.transaction_type IN ('bill', 'expense') THEN ti.item_qty 
                        ELSE 0 
                    END
                ) AS Qty, 
                SUM(ti.item_amount) AS Amount, 
                ROUND(
                    (
                        SUM(CASE WHEN ti.transaction_type = 'invoice' THEN ti.item_qty ELSE 0 END) * 100.0 /
                        NULLIF(SUM(CASE WHEN ti.transaction_type IN ('bill', 'expense') THEN ti.item_qty ELSE 0 END), 0)
                    ), 2
                ) AS PercentageOfSales, 
                ROUND(AVG(ti.item_rate), 2) AS AvgPrice
            FROM 
                trans_item ti 
            LEFT JOIN product p ON ti.product_sku = p.product_sku 
            LEFT JOIN category c ON p.category_id = c.category_id 
            WHERE 1=1
        ";

        // Add filters
        if (!empty($filters['transactionType']) && $filters['transactionType'] !== 'none') {
            $query .= " AND ti.transaction_type = :transactionType";
        }
        if (!empty($filters['dateFilter']) && $filters['dateFilter'] === 'custom') {
            $query .= " AND DATE(ti.created_at) BETWEEN :startDate AND :endDate";
        }

        // Group and order by category and product
        $query .= " GROUP BY c.category_name, p.product_sku ORDER BY p.product_sku";

        $stmt = $pdo->prepare($query);

        // Bind filters
        if (!empty($filters['transactionType']) && $filters['transactionType'] !== 'none') {
            $stmt->bindParam(':transactionType', $filters['transactionType'], PDO::PARAM_STR);
        }
        if (!empty($filters['dateFilter']) && $filters['dateFilter'] === 'custom') {
            $stmt->bindParam(':startDate', $filters['startDate'], PDO::PARAM_STR);
            $stmt->bindParam(':endDate', $filters['endDate'], PDO::PARAM_STR);
        }

        // Execute and fetch grouped summary data
        $stmt->execute();
        $summaryData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group summary data by category
        $groupedData = [];
        $productSKUs = []; // Track SKUs for ordering in detailed report
        foreach ($summaryData as $row) {
            $groupedData[$row['CategoryName']][] = $row;
            $productSKUs[] = $row['ProductSKU'];
        }

        // Query for detailed data
        $detailedQuery = "
            SELECT 
                ti.transaction_type AS TransactionType,
                COALESCE(b.bill_date, e.expense_date, i.invoice_date) AS TransactionDate,
                ti.transaction_no AS TransactionNo,
                CASE 
                    WHEN b.transaction_no IS NOT NULL THEN s.vendor_name
                    WHEN e.transaction_no IS NOT NULL THEN s.vendor_name
                    WHEN i.transaction_no IS NOT NULL THEN c.customer_name
                    ELSE NULL
                END AS PersonName,
                p.product_name AS ProductName,
                ti.item_qty AS Quantity,
                u.short_name AS Unit,
                ti.item_rate AS UnitPrice,
                ti.item_amount AS TotalAmount,
                ti.product_sku AS ProductSKU
            FROM 
                trans_item ti
            LEFT JOIN product p ON ti.product_sku = p.product_sku
            LEFT JOIN unit u ON u.unit_id = p.unit_id
            LEFT JOIN trans_bill b ON b.transaction_no = ti.transaction_no
            LEFT JOIN trans_expense e ON e.transaction_no = ti.transaction_no
            LEFT JOIN trans_invoice i ON i.transaction_no = ti.transaction_no
            LEFT JOIN supplier s ON s.id = b.supplier_id OR s.id = e.payee_id
            LEFT JOIN customer c ON c.id = i.customer_id
            WHERE 1=1
        ";

        // Reuse filters for detailed query
        if (!empty($filters['transactionType']) && $filters['transactionType'] !== 'none') {
            $detailedQuery .= " AND ti.transaction_type = :transactionType";
        }
        if (!empty($filters['dateFilter']) && $filters['dateFilter'] === 'custom') {
            $detailedQuery .= " AND DATE(ti.created_at) BETWEEN :startDate AND :endDate";
        }

        $detailedQuery .= " ORDER BY FIELD(ti.product_sku, " . implode(',', array_map(function($sku) {
            return "'" . $sku . "'";
        }, $productSKUs)) . "), ti.transaction_no";

        $detailedStmt = $pdo->prepare($detailedQuery);

        // Bind filters for detailed query
        if (!empty($filters['transactionType']) && $filters['transactionType'] !== 'none') {
            $detailedStmt->bindParam(':transactionType', $filters['transactionType'], PDO::PARAM_STR);
        }
        if (!empty($filters['dateFilter']) && $filters['dateFilter'] === 'custom') {
            $detailedStmt->bindParam(':startDate', $filters['startDate'], PDO::PARAM_STR);
            $detailedStmt->bindParam(':endDate', $filters['endDate'], PDO::PARAM_STR);
        }

        // Execute and fetch detailed data
        $detailedStmt->execute();
        $detailedDataRaw = $detailedStmt->fetchAll(PDO::FETCH_ASSOC);

        // Organize detailed data by SKU
        $detailedData = [];
        foreach ($detailedDataRaw as $row) {
            $key = "{$row['ProductSKU']} ({$row['ProductName']})";
            $detailedData[$key][] = $row;
        }

        return ['success' => true, 'summary' => $groupedData, 'detailed' => $detailedData];
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()];
    }
}



function getProductAndTransactions($pdo, $sku, $transactionType, $dateFilter, $startDate, $endDate) {
    // Fetch product data based on SKU
    $stmt = $pdo->prepare("SELECT * FROM product WHERE product_sku = :sku");
    $stmt->execute(['sku' => $sku]);
    $product = $stmt->fetch();

    if (!$product) {
        return ['product' => null, 'transactions' => []]; // No product found
    }

    // Build the base query for fetching transactions
    $query = "SELECT ti.*, p.product_name, u.short_name,
                     b.bill_date, e.expense_date, i.invoice_date,
                     CASE 
                         WHEN b.transaction_no IS NOT NULL THEN s.vendor_name
                         WHEN e.transaction_no IS NOT NULL THEN s.vendor_name
                         WHEN i.transaction_no IS NOT NULL THEN c.customer_name
                         ELSE NULL
                     END AS person_name
              FROM trans_item ti
              LEFT JOIN product p ON p.product_sku = ti.product_sku
              LEFT JOIN unit u ON u.unit_id = p.unit_id
              LEFT JOIN trans_bill b ON b.transaction_no = ti.transaction_no
              LEFT JOIN trans_expense e ON e.transaction_no = ti.transaction_no
              LEFT JOIN trans_invoice i ON i.transaction_no = ti.transaction_no
              LEFT JOIN supplier s ON s.id = b.supplier_id OR s.id = e.payee_id
              LEFT JOIN customer c ON c.id = i.customer_id
              WHERE ti.product_sku = :sku";

    // Add transaction type filter
    if ($transactionType !== 'none') {
        $query .= " AND ti.transaction_type = :transactionType";
    }

    // Add date range filter if dateFilter is custom
    if ($dateFilter === 'custom' && !empty($startDate) && !empty($endDate)) {
        $query .= " AND (
                        (b.bill_date BETWEEN :startDate AND :endDate) OR
                        (e.expense_date BETWEEN :startDate AND :endDate) OR
                        (i.invoice_date BETWEEN :startDate AND :endDate)
                    )";
    }

    // Prepare and execute the transaction query
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':sku', $sku);
    if ($transactionType !== 'none') {
        $stmt->bindParam(':transactionType', $transactionType);
    }
    if ($dateFilter === 'custom' && !empty($startDate) && !empty($endDate)) {
        $stmt->bindParam(':startDate', $startDate);
        $stmt->bindParam(':endDate', $endDate);
    }

    $stmt->execute();
    $transactions = $stmt->fetchAll();

    return ['product' => $product, 'transactions' => $transactions];
}


?>
