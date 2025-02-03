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
                    a.product_id,
                    a.product_name,
                    a.product_description,
                    c.brand_id,
                    c.brand_name,
                    COALESCE(b2.category_id, b.category_id) AS category_id,
                    COALESCE(CONCAT(b2.category_name, " / ", b.category_name), b.category_name) AS category,
                    a.product_sku,
                    a.product_pp,
                    a.product_sp,
                    CASE
                        WHEN COALESCE(
                                SUM(CASE 
                                    WHEN g.transaction_type IN ("bill", "expense", "inventory_adjustment") THEN g.item_qty 
                                    ELSE 0 
                                END) 
                                - SUM(CASE 
                                    WHEN g.transaction_type = "invoice" THEN g.item_qty 
                                    ELSE 0 
                                END), 0
                            ) >= a.product_min THEN 1
                        WHEN COALESCE(
                                SUM(CASE 
                                    WHEN g.transaction_type IN ("bill", "expense", "inventory_adjustment") THEN g.item_qty 
                                    ELSE 0 
                                END) 
                                - SUM(CASE 
                                    WHEN g.transaction_type = "invoice" THEN g.item_qty 
                                    ELSE 0 
                                END), 0
                            ) < a.product_min 
                            AND COALESCE(
                                SUM(CASE 
                                    WHEN g.transaction_type IN ("bill", "expense", "inventory_adjustment") THEN g.item_qty 
                                    ELSE 0 
                                END) 
                                - SUM(CASE 
                                    WHEN g.transaction_type = "invoice" THEN g.item_qty 
                                    ELSE 0 
                                END), 0
                            ) > 0 THEN 2
                        ELSE 3
                    END AS status_id,
                    a.product_min,
                    a.product_max,
                    a.tax_id,
                    a.expiry_notice,
                    a.unit_id,
                    COALESCE(
                        SUM(CASE 
                            WHEN g.transaction_type = "bill" AND (tb.isVoid = 0 OR tb.isVoid IS NULL) THEN g.item_qty 
                            ELSE 0 
                        END), 0
                    ) 
                    + COALESCE(
                        SUM(CASE 
                            WHEN g.transaction_type = "expense" AND (te.isVoid = 0 OR te.isVoid IS NULL) THEN g.item_qty 
                            ELSE 0 
                        END), 0
                    )
                    + COALESCE(
                        SUM(CASE 
                            WHEN g.transaction_type = "inventory_adjustment" AND (te.isVoid = 0 OR te.isVoid IS NULL) THEN g.item_qty 
                            ELSE 0 
                        END), 0
                    ) 
                    - COALESCE(
                        SUM(CASE 
                            WHEN g.transaction_type = "invoice" AND (ti.isVoid = 0 OR ti.isVoid IS NULL) THEN g.item_qty 
                            ELSE 0 
                        END), 0
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
                LEFT JOIN 
                    trans_bill tb ON g.transaction_no = tb.transaction_no AND g.transaction_type = "bill"
                LEFT JOIN 
                    trans_expense te ON g.transaction_no = te.transaction_no AND g.transaction_type = "expense"
                LEFT JOIN 
                    trans_invoice ti ON g.transaction_no = ti.transaction_no AND g.transaction_type = "invoice"
                GROUP BY 
                    a.product_id, 
                    a.product_name, 
                    a.product_description, 
                    c.brand_id, 
                    c.brand_name, 
                    b.category_id, 
                    b2.category_id, 
                    a.product_sku, 
                    a.product_pp, 
                    a.product_sp, 
                    a.product_min, 
                    a.product_max, 
                    a.tax_id, 
                    a.expiry_notice, 
                    a.unit_id, 
                    e.short_name;';
                break;
            case 'category':
                $sql = 'SELECT c.category_id, c.category_name AS category_name, c.category_prefix, c.parent_category_id, p.category_name AS parent_category_name
                        FROM category c
                        LEFT JOIN category p ON c.parent_category_id = p.category_id
                        ORDER BY c.category_id';
                break;
            case 'brand':
                $sql = 'SELECT * FROM brand
                        ORDER BY brand_name ASC';
                break;
            case 'unit':
                $sql = 'SELECT * FROM unit
                        ORDER BY unit_type ASC';
                break;
            case 'tax':
                $sql = 'SELECT * FROM tax
                        ORDER BY tax_percentage ASC';
                break;
            case 'costing':
                $sql = 'SELECT
                a.product_id,
                a.product_name,
                a.product_description,
                c.brand_id,
                c.brand_name,
                CASE
                    WHEN b2.category_name IS NULL then b.category_id
                    ELSE b2.category_id
                END AS category_id,
                CASE
                    WHEN b2.category_name IS NULL then b.category_name
                    ELSE CONCAT(b2.category_name," / ", b.category_name)
                END AS category,
                a.product_sku,
                a.product_pp,
                CAST(ROUND(a.product_sp * (1 + f.tax_percentage / 100), 2) AS DECIMAL(10, 2)) as product_sp,
                CASE
                        WHEN COALESCE(SUM(g.item_qty), 0) >= a.product_min THEN 1
                        WHEN COALESCE(SUM(g.item_qty), 0) < a.product_min AND COALESCE(SUM(g.item_qty), 0) !=0  THEN 2
                        ELSE 3
                END AS status_id,
                a.product_min,
                a.product_max,
                a.tax_id,
                f.tax_name,
                a.unit_id,
                COALESCE(SUM(g.item_qty), 0) AS stocks,
                e.short_name AS unit
                FROM product a
                INNER JOIN category b ON b.category_id = a.category_id
                LEFT JOIN category b2 ON b.parent_category_id = b2.category_id
                INNER JOIN brand c ON c.brand_id = a.brand_id
                -- INNER JOIN status d
                INNER JOIN unit e ON e.unit_id = a.unit_id
                INNER JOIN tax f ON f.tax_id = a.tax_id
                LEFT JOIN trans_item g ON g.product_sku = a.product_sku
                GROUP BY a.product_sku';
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
            case 'pending-stockin':
                $sql = 'SELECT
                a.id,
                a.series_number,
                DATE_FORMAT(a.date, "%M %d, %Y %h:%i %p") as date,
                a.isAdded
                FROM stockin_history a';
                break;
            case 'item-details':
                if (isset($_GET['series_number'])) {
                    $seriesNumber = $_GET['series_number'];
                    $sql = 'SELECT
                                a.product_name,
                                a.product_pp,
                                a.product_sp,
                                a.item_qty AS quantity,
                                a.item_barcode,
                                a.item_expiry,
                                DATE(a.created_at) AS created_at,
                                (a.product_pp * a.item_qty) AS total_cost
                            FROM pending_item a
                            WHERE a.series_number = :series_number';
                    
                    // Prepare and execute the statement
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['series_number' => $seriesNumber]);
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode(['data' => $data]);
                    exit;
                } else {
                    echo json_encode(['error' => 'Series number not specified']);
                    exit;
                }
                break;
            case 'item-details-stockout':
                if (isset($_GET['series_number'])) {
                    $seriesNumber = $_GET['series_number'];
                    $sql = 'SELECT
                    b.product_name,
                    a.item_qty AS quantity,
                    a.item_barcode,
                    a.product_pp,
                    a.product_sp,
                    a.item_expiry,
                    DATE(a.created_at) AS created_at,
                    (a.product_pp * a.item_qty) AS total_cost
                FROM pending_stock_out a
                INNER JOIN product b ON b.product_sku = a.product_sku
                WHERE a.series_number = :series_number';
        
                    
                    // Prepare and execute the statement
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['series_number' => $seriesNumber]);
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode(['data' => $data]);
                    exit;
                } else {
                    echo json_encode(['error' => 'Series number not specified']);
                    exit;
                }
                break;
            case 'pending-stockout':
                $sql = 'SELECT
                a.id,
                a.series_number,
                DATE_FORMAT(a.date, "%M %d, %Y %h:%i %p") as date,
                a.isAdded
                FROM stockout_history a';
                break;
            case 'item-details':
                if (isset($_GET['series_number'])) {
                    $seriesNumber = $_GET['series_number'];
                    $sql = 'SELECT
                                b.product_name,
                                a.item_qty AS quantity,
                                a.item_barcode,
                                a.item_expiry
                            FROM pending_item a
                            INNER JOIN product b ON b.product_sku = a.product_sku
                            WHERE a.series_number = :series_number';
                    
                    // Prepare and execute the statement
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute(['series_number' => $seriesNumber]);
                    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    echo json_encode(['data' => $data]);
                    exit;
                } else {
                    echo json_encode(['error' => 'Series number not specified']);
                    exit;
                }
                break;
            case 'expiring_soon':
                $sql = 'SELECT
                p.product_id,
                p.product_name,
                t.product_sku, 
                t.item_barcode, 
                t.item_expiry, 
                p.expiry_notice, 
                DATEDIFF(t.item_expiry, NOW()) + 1 AS days_to_expiry, 
                SUM(CASE 
                        WHEN t.transaction_type IN ("bill", "expense") THEN t.item_qty 
                        WHEN t.transaction_type = "invoice" THEN -t.item_qty 
                        ELSE 0 
                    END) AS available_qty
            FROM 
                trans_item t
            JOIN 
                product p ON t.product_sku = p.product_sku
            WHERE 
                t.item_expiry IS NOT NULL AND t.item_expiry != "0000-00-00"
            GROUP BY 
                t.product_sku, t.item_barcode, t.item_expiry, p.expiry_notice
            HAVING 
                available_qty > 0
            ORDER BY 
                t.item_expiry ASC, t.item_barcode ASC;';
                break;
            case 'supplier-list':
                $sql = 'SELECT * FROM supplier
                        ORDER BY vendor_name ASC';
                break;
            case 'customer-list':
                    $sql = 'SELECT * FROM customer
                            ORDER BY customer_name ASC';
                    break;
            case 'waste':
                $sql ='SELECT * FROM waste
                ORDER BY created_at ASC';
                break;
            case 'transaction-list':
                $sql='SELECT 
                        bill_date AS Date,
                        "bill" AS Type,
                        transaction_no AS `Transaction No`,
                        bill_no AS No,
                        s.vendor_name AS Payee,
                        total_amount AS `Total Before Sales`,
                        sales_tax AS `Sales Tax`,
                        grand_total AS Total,
                        payment_status AS Status
                    FROM trans_bill
                    JOIN supplier s ON trans_bill.supplier_id = s.id

                    UNION ALL

                    SELECT 
                        expense_date AS Date,
                        "expense" AS Type,
                        transaction_no AS `Transaction No`,
                        expense_no AS No,
                        s.vendor_name AS Payee,
                        total_amount AS `Total Before Sales`,
                        sales_tax AS `Sales Tax`,
                        grand_total AS Total,
                        NULL AS Status -- Status not applicable for expenses
                    FROM trans_expense
                    JOIN supplier s ON trans_expense.payee_id = s.id

                    UNION ALL

                    SELECT 
                        invoice_date AS Date,
                        "invoice" AS Type,
                        transaction_no AS `Transaction No`,
                        invoice_no AS No,
                        c.customer_name AS Payee,
                        total_amount AS `Total Before Sales`,
                        sales_tax AS `Sales Tax`,
                        grand_total AS Total,
                        payment_status AS Status
                    FROM trans_invoice
                    JOIN customer c ON trans_invoice.customer_id = c.id;
                    ;
                ';
                break;
            case 'paybill-table':
                $sql=
                "SELECT
                    s.vendor_name AS payee,   -- Replace payee_id with vendor_name from supplier table
                    tb.transaction_no as transaction_no,
                    tb.bill_no AS ref_no,
                    tb.bill_due_date AS due_date,
                    tb.grand_total AS bill_amount,
                    (tb.grand_total - COALESCE(SUM(p.payment_amount), 0)) AS open_balance,
                    COALESCE(SUM(p.payment_amount), 0) AS total_payments,
                    tb.payment_status AS status
                FROM 
                    trans_bill tb
                LEFT JOIN 
                    payments p ON tb.transaction_no = p.transaction_no
                LEFT JOIN 
                    supplier s ON tb.supplier_id = s.id
                WHERE
                    isVoid = 0 AND payment_status != 'paid'
                GROUP BY 
                    tb.transaction_no, s.vendor_name
                ";
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
