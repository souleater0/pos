<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Load TCPDF

// Check if the required POST data is available
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['summaryData'], $_POST['detailedData'], $_POST['filters'])) {
    $summaryData = json_decode($_POST['summaryData'], true);  // Summary data
    $detailedData = json_decode($_POST['detailedData'], true);  // Detailed data
    $filters = json_decode($_POST['filters'], true);  // Assuming 'filters' is passed in POST data
    $currentDate = date('F d, Y');

    // Extract filters and ensure 'dateFilter' exists
    $transactionType = $filters['transactionType'] ?? 'All';
    $dateFilter = $filters['dateFilter'] ?? 'all'; // Default to 'all' if 'dateFilter' is not set

    $dateRange = (isset($filters['dateFilter']) && $filters['dateFilter'] === 'all')
    ? 'All'
    : (!empty($filters['startDate']) && !empty($filters['endDate'])
        ? date('F d, Y', strtotime($filters['startDate'])) . ' - ' . date('F d, Y', strtotime($filters['endDate']))
        : '');

    // Create new PDF instance
    $pdf = new TCPDF('P', 'mm', 'LEGAL'); // 'L' for Landscape, 'mm' for millimeters, 'A4' for paper size
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Summary Report - ' . $currentDate);
    $pdf->AddPage();

    // Header
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Summary Report', 0, 1, 'C');

    // Add filter details
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 05, 'Transaction Type: ' . $transactionType, 0, 1, 'C');
    $pdf->Cell(0, 05, 'Date Range: ' . $dateRange, 0, 1, 'C');

    $columnHeaders = [
        'Product SKU' => 'ProductSKU',
        'Product Name' => 'ProductName',
        'Qty' => 'Qty',
        'Amount' => 'Amount',
        '% of Sales' => 'PercentageOfSales',
        'Avg Price' => 'AvgPrice',
    ];

    // The rest of the PDF content generation remains the same
    // Determine column widths dynamically for summary data
    $columnWidths = [];
    foreach ($columnHeaders as $header => $key) {
        $columnWidths[$key] = $pdf->GetStringWidth($header) + 10; // Minimum width to fit the header
    }

    // Adjust column widths based on content
    foreach ($summaryData as $categoryName => $products) {
        foreach ($products as $product) {
            foreach ($columnHeaders as $header => $key) {
                $contentWidth = $pdf->GetStringWidth($product[$key]) + 10; // Add padding
                if ($contentWidth > $columnWidths[$key]) {
                    $columnWidths[$key] = $contentWidth; // Expand width if needed
                }
            }
        }
    }

    $grandTotalAmount = 0;
    $grandTotalQty = 0;
    $grandTotalPercentage = 0;
    $grandTotalAvgPrice = 0;

    // Loop through summary data and generate the report
    foreach ($summaryData as $categoryName => $products) {
        $pdf->Ln(5); // Line break
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, $categoryName, 0, 1); // Category name

        // Table header for summary data
        $pdf->SetFont('helvetica', '', 10);
        foreach ($columnHeaders as $header => $key) {
            $pdf->Cell($columnWidths[$key], 10, $header, 1, 0, 'C');
        }
        $pdf->Ln();

        $categoryTotalAmount = 0;
        $categoryTotalQty = 0;
        $categoryTotalPercentage = 0;
        $categoryTotalAvgPrice = 0;

        // Add product data for each category
        foreach ($products as $product) {
            foreach ($columnHeaders as $header => $key) {
                $align = in_array($key, ['Qty', 'Amount', 'PercentageOfSales', 'AvgPrice']) ? 'R' : 'L';
                $pdf->Cell($columnWidths[$key], 10, $product[$key], 1, 0, $align);
            }
            $pdf->Ln();

            // Add the values to the category totals
            $categoryTotalQty += $product['Qty'];
            $categoryTotalAmount += $product['Amount'];
            $categoryTotalPercentage += $product['PercentageOfSales'];
            $categoryTotalAvgPrice += $product['AvgPrice'];
        }

        // Add subtotals for category
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(array_sum($columnWidths) - $columnWidths['Qty'] - $columnWidths['Amount'] - $columnWidths['PercentageOfSales'] - $columnWidths['AvgPrice'], 10, 'Subtotal', 1, 0, 'R');
        $pdf->Cell($columnWidths['Qty'], 10, $categoryTotalQty, 1, 0, 'R');
        $pdf->Cell($columnWidths['Amount'], 10, number_format($categoryTotalAmount, 2), 1, 0, 'R');
        $pdf->Cell($columnWidths['PercentageOfSales'], 10, number_format($categoryTotalPercentage, 2). '%', 1, 0, 'R');
        $pdf->Cell($columnWidths['AvgPrice'], 10, number_format($categoryTotalAvgPrice, 2), 1, 1, 'R');

        // Add the category totals to the grand total
        $grandTotalQty += $categoryTotalQty;
        $grandTotalAmount += $categoryTotalAmount;
        $grandTotalPercentage += $categoryTotalPercentage;
        $grandTotalAvgPrice += $categoryTotalAvgPrice;

        $pdf->Ln(2); // Space after each category
    }

    // Add the grand total
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(array_sum($columnWidths) - $columnWidths['Qty'] - $columnWidths['Amount'] - $columnWidths['PercentageOfSales'] - $columnWidths['AvgPrice'], 10, 'Grand Total', 1, 0, 'R');
    $pdf->Cell($columnWidths['Qty'], 10, $grandTotalQty, 1, 0, 'R');
    $pdf->Cell($columnWidths['Amount'], 10, number_format($grandTotalAmount, 2), 1, 0, 'R');
    $pdf->Cell($columnWidths['PercentageOfSales'], 10, number_format($grandTotalPercentage, 2). '%', 1, 0, 'R');
    $pdf->Cell($columnWidths['AvgPrice'], 10, number_format($grandTotalAvgPrice, 2), 1, 1, 'R');

    // Add a page for the detailed data
    $pdf->AddPage();

    // Header for detailed transactions
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Detailed Transaction Report', 0, 1, 'C');

    // Loop through detailed data and add transaction details for each product
    $pdf->Ln(10); // Add some space before detailed data
    $grandTotalDetailedQty = 0;
    $grandTotalDetailedAmount = 0;

    foreach ($detailedData as $productKey => $transactions) {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, $productKey, 0, 1); // Product Name with SKU
        $pdf->SetFont('helvetica', '', 10);
        
        // Determine column widths for detailed data based on headers
        $detailedColumnHeaders = [
            'Transaction Type' => 'TransactionType',
            'Transaction No' => 'TransactionNo',
            'Person Name' => 'PersonName',
            'Quantity' => 'Quantity',
            'Total Amount' => 'TotalAmount',
        ];

        $detailedColumnWidths = [];
        foreach ($detailedColumnHeaders as $header => $key) {
            $detailedColumnWidths[$key] = $pdf->GetStringWidth($header) + 10;
        }

        // Adjust column widths based on content in detailed data
        foreach ($transactions as $transaction) {
            foreach ($detailedColumnHeaders as $header => $key) {
                $contentWidth = $pdf->GetStringWidth($transaction[$key]) + 10;
                if ($contentWidth > $detailedColumnWidths[$key]) {
                    $detailedColumnWidths[$key] = $contentWidth;
                }
            }
        }

        // Table header for detailed transactions
        $pdf->Cell($detailedColumnWidths['TransactionType'], 10, 'Transaction Type', 1, 0, 'C');
        $pdf->Cell($detailedColumnWidths['TransactionNo'], 10, 'Transaction No', 1, 0, 'C');
        $pdf->Cell($detailedColumnWidths['PersonName'], 10, 'Person Name', 1, 0, 'C');
        $pdf->Cell($detailedColumnWidths['Quantity'], 10, 'Quantity', 1, 0, 'C');
        $pdf->Cell($detailedColumnWidths['TotalAmount'], 10, 'Total Amount', 1, 1, 'C');

        // Initialize totals for detailed data
        $detailedTotalQty = 0;
        $detailedTotalAmount = 0;

        // Add transaction data
        foreach ($transactions as $transaction) {
            $qtyAdjustment = 0;
            if ($transaction['TransactionType'] == 'invoice') {
                $qtyAdjustment -= $transaction['Quantity'];  // Subtract for invoice
            } else if (in_array($transaction['TransactionType'], ['bill', 'expense'])) {
                $qtyAdjustment += $transaction['Quantity'];  // Add for bill/expense
            }
        
            // Adjust the totals based on the transaction type
            $detailedTotalQty += $qtyAdjustment;
            $detailedTotalAmount += $transaction['TotalAmount'];
        
            // Add transaction details to PDF
            $pdf->Cell($detailedColumnWidths['TransactionType'], 10, $transaction['TransactionType'], 1, 0, 'C');
            $pdf->Cell($detailedColumnWidths['TransactionNo'], 10, $transaction['TransactionNo'], 1, 0, 'C');
            $pdf->Cell($detailedColumnWidths['PersonName'], 10, $transaction['PersonName'], 1, 0, 'C');
            $pdf->Cell($detailedColumnWidths['Quantity'], 10, $transaction['Quantity'], 1, 0, 'C');
            $pdf->Cell($detailedColumnWidths['TotalAmount'], 10, number_format($transaction['TotalAmount'], 2), 1, 1, 'C');
        }

        // Add subtotals for detailed transactions
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(array_sum($detailedColumnWidths) - $detailedColumnWidths['Quantity'] - $detailedColumnWidths['TotalAmount'], 10, 'Subtotal', 1, 0, 'R');
        $pdf->Cell($detailedColumnWidths['Quantity'], 10, $detailedTotalQty, 1, 0, 'R');
        $pdf->Cell($detailedColumnWidths['TotalAmount'], 10, number_format($detailedTotalAmount, 2), 1, 1, 'R');

        // Add the detailed totals to the grand total
        $grandTotalDetailedQty += $detailedTotalQty;
        $grandTotalDetailedAmount += $detailedTotalAmount;

        $pdf->Ln(2); // Space after each product
    }

    // Add the grand total for detailed transactions
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(array_sum($detailedColumnWidths) - $detailedColumnWidths['Quantity'] - $detailedColumnWidths['TotalAmount'], 10, 'Grand Total', 1, 0, 'R');
    $pdf->Cell($detailedColumnWidths['Quantity'], 10, $grandTotalDetailedQty, 1, 0, 'R');
    $pdf->Cell($detailedColumnWidths['TotalAmount'], 10, number_format($grandTotalDetailedAmount, 2), 1, 1, 'R');

    // Output PDF to browser
    $pdf->Output('report_summary.pdf', 'I');
}
?>
