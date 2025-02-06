<?php
    require_once '../../config.php';

    // Check if a table type is provided in the request
    if (isset($_GET['table_type'])) {
        $tableType = $_GET['table_type'];

        // Define SQL query based on the table type
        switch ($tableType) {
            case 'product':
                $sql = 
                'SELECT
                p.product_id,
                p.product_name,
                p.product_price,
                p.product_discount,
                c.category_id,
                c.category_name,
                p.product_image
                FROM product p
                JOIN category c ON p.category_id = c.category_id';
                break;
            case 'ingredient':
                $sql = 'SELECT ingredient.*, unit.unit_type
                        FROM ingredient
                        JOIN unit ON ingredient.unit_id = unit.unit_id';
                break;
            case 'category':
                $sql = 'SELECT * FROM category
                        ORDER BY category_name ASC';
                break;
            case 'unit':
                $sql = 'SELECT * FROM unit
                        ORDER BY unit_type ASC';
                break;
            case 'discount':
                $sql = 'SELECT * FROM discount
                        ORDER BY discount_percentage ASC';
                break;
            case 'users':
                $sql = 'SELECT
                a.id,
                a.display_name,
                a.username,
                a.role_id,
                b.role_name,
                a.isEnabled,
                CASE 
                    WHEN a.isEnabled = 1 THEN "Enabled"
                    ELSE "Disabled"
                END as status
                FROM users a
                INNER JOIN roles b ON b.id = a.role_id
                ORDER BY display_name ASC';
                break;
                case 'roles':
                    $sql = 'SELECT * FROM roles
                    ORDER BY role_name ASC';
                    break;
            // If an invalid or unsupported table type is provided, return an error
            echo json_encode(['error' => 'Unsupported table type']);
            exit;
        }
    // Execute the query
    $stmt = $pdo->query($sql);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['data' => $data]);
    }else{
        echo json_encode(['error' => 'Table type not specified']);
    }
?>
