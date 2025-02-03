<?php
require '../../vendor/autoload.php'; // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['summaryData'], $_POST['detailedData'], $_POST['filters'])) {
    $summaryData = json_decode($_POST['summaryData'], true);
    $detailedData = json_decode($_POST['detailedData'], true);
    $filters = json_decode($_POST['filters'], true);
    $currentDate = date('F d, Y');

    $spreadsheet = new Spreadsheet();

    // Create Summary Sheet
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Summary');
    $row = 1;

    // Set document title/header
    $sheet->mergeCells("A$row:F$row");
    $sheet->setCellValue("A$row", "Summary Report");
    $sheet->getStyle("A$row")->applyFromArray([
        'font' => ['bold' => true, 'size' => 16],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $row++;

    // Add filters as sub-header
    $sheet->mergeCells("A$row:F$row");
    $filterDetails = "Filters - Transaction Type: " . $filters['transactionType'] . 
        ", Date Range: " . ($filters['dateFilter'] === "custom" ? 
        $filters['startDate'] . " to " . $filters['endDate'] : $filters['dateFilter']);
    $sheet->setCellValue("A$row", $filterDetails);
    $sheet->getStyle("A$row")->applyFromArray([
        'font' => ['italic' => true, 'size' => 12],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $row += 2;

    $grandTotalAmount = 0;
    $grandTotalQty = 0;
    $grandTotalPercentage = 0;
    $grandTotalAvgPrice = 0;

    foreach ($summaryData as $categoryName => $products) {
        // Add category name as a header
        $sheet->setCellValue("A$row", $categoryName);
        $sheet->getStyle("A$row")->getFont()->setBold(true);
        $row++;

        // Add table headers with borders
        $sheet->setCellValue("A$row", "Product SKU")
              ->setCellValue("B$row", "Product Name")
              ->setCellValue("C$row", "Qty")
              ->setCellValue("D$row", "Amount")
              ->setCellValue("E$row", "% of Sales")
              ->setCellValue("F$row", "Avg Price");

        // Apply border to the header row
        $sheet->getStyle("A$row:F$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;

        $categoryTotalAmount = 0;
        $categoryTotalQty = 0;
        $categoryTotalPercentage = 0;
        $categoryTotalAvgPrice = 0;

        // Add product data
        foreach ($products as $product) {
            $sheet->setCellValue("A$row", $product['ProductSKU'])
                  ->setCellValue("B$row", $product['ProductName'])
                  ->setCellValue("C$row", $product['Qty'])
                  ->setCellValue("D$row", number_format($product['Amount'], 2))  // This ensures .00 is added
                  ->setCellValue("E$row", number_format($product['PercentageOfSales'], 2) . '%')  // Add '%' symbol
                  ->setCellValue("F$row", number_format($product['AvgPrice'], 2));

            // Apply borders to each product row
            $sheet->getStyle("A$row:F$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;

            // Add the values to the category totals
            $categoryTotalQty += $product['Qty'];
            $categoryTotalAmount += $product['Amount'];
            $categoryTotalPercentage += $product['PercentageOfSales'];
            $categoryTotalAvgPrice += $product['AvgPrice'];
        }

        // Add subtotals for each column
        $sheet->setCellValue("A$row", "Subtotal")
              ->setCellValue("C$row", $categoryTotalQty)
              ->setCellValue("D$row", number_format($categoryTotalAmount, 2))
              ->setCellValue("E$row", number_format($categoryTotalPercentage, 2). '%')
              ->setCellValue("F$row", number_format($categoryTotalAvgPrice, 2));
        $sheet->getStyle("A$row:F$row")->getFont()->setBold(true);
        $sheet->getStyle("A$row:F$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;

        // Add the category totals to the grand total
        $grandTotalQty += $categoryTotalQty;
        $grandTotalAmount += $categoryTotalAmount;
        $grandTotalPercentage += $categoryTotalPercentage;
        $grandTotalAvgPrice += $categoryTotalAvgPrice;

        $row++; // Add space after each category
    }

    // Add the grand total
    $sheet->setCellValue("A$row", "Grand Total")
          ->setCellValue("C$row", $grandTotalQty)
          ->setCellValue("D$row", number_format($grandTotalAmount, 2))
          ->setCellValue("E$row", number_format($grandTotalPercentage, 2). '%')
          ->setCellValue("F$row", number_format($grandTotalAvgPrice, 2));
    $sheet->getStyle("A$row:F$row")->getFont()->setBold(true);
    $sheet->getStyle("A$row:F$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Create Detailed Sheet
    $spreadsheet->createSheet();
    $sheet = $spreadsheet->setActiveSheetIndex(1);
    $sheet->setTitle('Detailed');
    $row = 1;

    // Set document title/header
    $sheet->mergeCells("A$row:I$row");
    $sheet->setCellValue("A$row", "Detailed Report");
    $sheet->getStyle("A$row")->applyFromArray([
        'font' => ['bold' => true, 'size' => 16],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $row++;

    // Add filters as sub-header
    $sheet->mergeCells("A$row:I$row");
    $sheet->setCellValue("A$row", $filterDetails);
    $sheet->getStyle("A$row")->applyFromArray([
        'font' => ['italic' => true, 'size' => 12],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);
    $row += 2;

    // Initialize grand totals for the detailed section
    $grandQty = 0;
    $grandTotalAmountDetail = 0;

    // Add product data from detailedData
    foreach ($detailedData as $productKey => $transactions) {
        list($productSKU, $productName) = explode(" (", rtrim($productKey, ")"));

        // Add category header above each table
        $sheet->setCellValue("A$row", $productSKU . " (" . $productName . ")");
        $sheet->getStyle("A$row")->getFont()->setBold(true);
        $row++;

        // Add table headers with borders
        $sheet->setCellValue("A$row", "Product SKU")
              ->setCellValue("B$row", "Product Name")
              ->setCellValue("C$row", "Transaction Type")
              ->setCellValue("D$row", "Transaction Date")
              ->setCellValue("E$row", "Transaction No.")
              ->setCellValue("F$row", "Person Name")
              ->setCellValue("G$row", "Quantity")
              ->setCellValue("H$row", "Unit Price")
              ->setCellValue("I$row", "Total Amount");

        // Apply border to the header row
        $sheet->getStyle("A$row:I$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;

        $categoryQty = 0;
        $categoryTotalAmount = 0;

        // Add product data for each transaction
        foreach ($transactions as $transaction) {
            // Adjust quantity based on transaction type
            $adjustedQty = 0;
            if ($transaction['TransactionType'] == 'invoice') {
                $adjustedQty = -$transaction['Quantity']; // Subtract for invoice
            } elseif (in_array($transaction['TransactionType'], ['bill', 'expense'])) {
                $adjustedQty = $transaction['Quantity']; // Add for bill/expense
            }

            $sheet->setCellValue("A$row", $productSKU)
                ->setCellValue("B$row", $productName)
                ->setCellValue("C$row", $transaction['TransactionType'])
                ->setCellValue("D$row", $transaction['TransactionDate'])
                ->setCellValue("E$row", $transaction['TransactionNo'])
                ->setCellValue("F$row", $transaction['PersonName'])
                ->setCellValue("G$row", $adjustedQty) // Use adjusted quantity
                ->setCellValue("H$row", number_format($transaction['UnitPrice'], 2))
                ->setCellValue("I$row", number_format($transaction['TotalAmount'], 2));

            // Apply borders to each product row
            $sheet->getStyle("A$row:I$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;

            // Add to category totals
            $categoryQty += $adjustedQty; // Use adjusted quantity for totals
            $categoryTotalAmount += $transaction['TotalAmount'];
        }


        // Add subtotals for each table (Qty, Total, Amount)
        $sheet->setCellValue("A$row", "Subtotal")
              ->setCellValue("G$row", $categoryQty)
              ->setCellValue("I$row", number_format($categoryTotalAmount, 2));
        $sheet->getStyle("A$row:I$row")->getFont()->setBold(true);
        $sheet->getStyle("A$row:I$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;

        // Add to grand totals
        $grandQty += $categoryQty;
        $grandTotalAmountDetail += $categoryTotalAmount;

        $row++; // Add space between each product category table
    }

    // Add the grand total for detailed section
    $sheet->setCellValue("A$row", "Grand Total")
          ->setCellValue("G$row", $grandQty)
          ->setCellValue("I$row", number_format($grandTotalAmountDetail, 2));
    $sheet->getStyle("A$row:I$row")->getFont()->setBold(true);
    $sheet->getStyle("A$row:I$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Set headers for download
    $fileName = 'Sales_Report_' . date('Ymd');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} else {
    echo "Invalid request.";
    exit;
}
?>
