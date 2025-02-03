<?php
require_once __DIR__ . '/../../vendor/autoload.php'; // Load TCPDF

// Retrieve the grouped data from POST request
if (isset($_POST['groupedData'])) {
    $requestData = json_decode($_POST['groupedData'], true);
    $filters = isset($_POST['filters']) ? json_decode($_POST['filters'], true) : [];

    $format = $requestData['format'];
    $product = $requestData['product'];
    $sku = $requestData['sku'];
    $transactionType = $requestData['transactionType'];
    $dateFilter = $requestData['dateFilter'];
    $startDate = $requestData['startDate'];
    $endDate = $requestData['endDate'];
    $transactions = $requestData['transactions'];

    // Get today's date for the file name
    $todayDate = date('Y_m_d');

    // Decide whether to generate a PDF or Excel based on the format
    if ($format == 'pdf') {
        generatePDF($product, $dateFilter, $startDate, $endDate, $transactionType, $transactions, $todayDate);
    } elseif ($format == 'excel') {
        generateExcel($product, $dateFilter, $startDate, $endDate, $transactionType, $transactions, $todayDate);
    }
}

// Function to generate PDF
function generatePDF($product, $dateFilter, $startDate, $endDate,$transactionType, $transactions, $todayDate ) {
    // Create a new TCPDF object
    $pdf = new TCPDF('L', 'mm', 'LEGAL');
    $pdf->AddPage();

    // Set header
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Detailed Report', 0, 1, 'C');  // Header text
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Transaction Type: ' . $transactionType, 0, 1, 'C');  // Product name
    // Display date range based on the dateFilter
    if ($dateFilter == 'all') {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 5, 'Date Range: All', 0, 1, 'C');
    } elseif ($dateFilter == 'custom') {
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 5, 'Date Range: ' . date('m/d/Y', strtotime($startDate)) . ' to ' . date('m/d/Y', strtotime($endDate)), 0, 1, 'C');
    }
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Product: ' . $product, 0, 1, 'C');  // Product name

    // Add some space
    $pdf->Ln(10);

    // Set the table header
    $pdf->SetFont('helvetica', 'B', 10);
    $header = ['Type','Transaction No','Name', 'Date of Transaction', 'Product Name', 'Qty', 'U/M', 'Unit Price', 'Amount'];

    // Create array to store column widths, initialized with header widths
    $columnWidths = [];
    foreach ($header as $title) {
        $columnWidths[] = $pdf->GetStringWidth($title) + 10;  // Add padding for header
    }

    // Calculate the maximum width based on header and data for each column
    foreach ($transactions as $transaction) {
        $columnWidths[0] = max($columnWidths[0], $pdf->GetStringWidth($transaction['transaction_type']) + 10);  // Type
        $columnWidths[1] = max($columnWidths[1], $pdf->GetStringWidth($transaction['transaction_no']) + 10);  // Transaction No
        $columnWidths[2] = max($columnWidths[2], $pdf->GetStringWidth($transaction['person_name']) + 10);  // Name
        $columnWidths[3] = max($columnWidths[3], $pdf->GetStringWidth(date('m/d/Y', strtotime($transaction['created_at']))) + 10);  // Date of Transaction
        $columnWidths[4] = max($columnWidths[4], $pdf->GetStringWidth($transaction['product_name']) + 10);  // Product Name
        $columnWidths[5] = max($columnWidths[5], $pdf->GetStringWidth($transaction['item_qty']) + 10);  // Qty
        $columnWidths[6] = max($columnWidths[6], $pdf->GetStringWidth($transaction['short_name']) + 10);  // Qty
        $columnWidths[7] = max($columnWidths[7], $pdf->GetStringWidth($transaction['item_rate']) + 10);  // Unit Price
        $columnWidths[8] = max($columnWidths[8], $pdf->GetStringWidth($transaction['item_amount']) + 10);  // Amount
    }

    // Calculate total table width
    $totalWidth = array_sum($columnWidths);

    // Set the X position to center the table
    $xPosition = ($pdf->GetPageWidth() - $totalWidth) / 2;
    
    // Center the header
    $pdf->SetX($xPosition);  // Set X position to center the header

    // Set table header with dynamic column widths
    foreach ($header as $index => $title) {
        $pdf->Cell($columnWidths[$index], 10, $title, 1, 0, 'C');
    }
    $pdf->Ln();

    // Set font for data
    $pdf->SetFont('helvetica', '', 10);

    // Initialize totals
    $totalQty = 0;
    $totalAmount = 0;

    // Loop through transactions and add them as rows in the table
    foreach ($transactions as $transaction) {
        // Set X position to center each row
        $pdf->SetX($xPosition);  // Set X position to center the row

        $pdf->Cell($columnWidths[0], 10, $transaction['transaction_type'], 1, 0, 'C');
        $pdf->Cell($columnWidths[1], 10, $transaction['transaction_no'], 1, 0, 'C');
        $pdf->Cell($columnWidths[2], 10, $transaction['person_name'], 1, 0, 'C');
        // Format created_at to date (MM/DD/YYYY)
        $pdf->Cell($columnWidths[3], 10, date('m/d/Y', strtotime($transaction['created_at'])), 1, 0, 'C');
        // If product name is too long, adjust width or truncate
        $pdf->Cell($columnWidths[4], 10, $transaction['product_name'], 1, 0, 'C');
        $pdf->Cell($columnWidths[5], 10, $transaction['item_qty'], 1, 0, 'C');
        $pdf->Cell($columnWidths[6], 10, $transaction['short_name'], 1, 0, 'C');
        $pdf->Cell($columnWidths[7], 10, number_format($transaction['item_rate'], 2), 1, 0, 'C');
        $pdf->Cell($columnWidths[8], 10, number_format($transaction['item_amount'], 2), 1, 1, 'C');

        // Update totals based on transaction type
        if (in_array(strtolower($transaction['transaction_type']), ['bill', 'expense'])) {
            $totalQty += $transaction['item_qty'];
            $totalAmount += $transaction['item_amount'];
        } elseif (strtolower($transaction['transaction_type']) === 'invoice') {
            $totalQty -= $transaction['item_qty'];
            $totalAmount -= $transaction['item_amount'];
        }
    }

    // Add total row at the end of the table
    $pdf->SetX($xPosition);  // Set X position to center the total row
    $pdf->Cell($columnWidths[0], 10, 'Total', 1, 0, 'C');  // Empty cell for alignment
    $pdf->Cell($columnWidths[1], 10, '', 1, 0, 'C');  // Empty cell for alignment
    $pdf->Cell($columnWidths[2], 10, '', 1, 0, 'C');  // Total label
    $pdf->Cell($columnWidths[3], 10, '', 1, 0, 'C');  // Empty cell for alignment
    $pdf->Cell($columnWidths[4], 10, '', 1, 0, 'C');  // Empty cell for alignment
    $pdf->Cell($columnWidths[5], 10, number_format($totalQty), 1, 0, 'C');  // Total Qty
    $pdf->Cell($columnWidths[6], 10, '', 1, 0, 'C');  // Empty cell for alignment
    $pdf->Cell($columnWidths[7], 10, '', 1, 0, 'C');  // Empty cell for alignment
    $pdf->Cell($columnWidths[8], 10, number_format($totalAmount, 2), 1, 1, 'C');  // Total Amount

    // Output the PDF to the browser
    $pdf->Output('detailed_report_' . $todayDate . '.pdf', 'I');
}




// Function to generate Excel
// Function to generate Excel
function generateExcel($product, $dateFilter, $startDate, $endDate, $transactionType, $transactions, $todayDate) {
    // Create a new PhpSpreadsheet object
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'Detailed Report');
    $sheet->setCellValue('A2', 'Product: ' . $product);
    if ($dateFilter == 'all') {
        $sheet->setCellValue('A3', 'Date Range: All');
    } elseif ($dateFilter == 'custom') {
        $sheet->setCellValue('A3', 'Date Range: ' . date('m/d/Y', strtotime($startDate)) . ' to ' . date('m/d/Y', strtotime($endDate)));
    }
    $sheet->setCellValue('A4', 'Transaction Type: ' . $transactionType);

    // Add a blank row
    $sheet->setCellValue('A5', '');

    // Set table headers
    $sheet->setCellValue('A6', 'Type');
    $sheet->setCellValue('B6', 'Transaction No');
    $sheet->setCellValue('C6', 'Name');
    $sheet->setCellValue('D6', 'Date of Transaction');
    $sheet->setCellValue('E6', 'Product Name');
    $sheet->setCellValue('F6', 'Qty');
    $sheet->setCellValue('G6', 'U/M');
    $sheet->setCellValue('H6', 'Unit Price');
    $sheet->setCellValue('I6', 'Amount');

    // Set row for transaction data
    $row = 7;
    $totalQty = 0;
    $totalAmount = 0;
    foreach ($transactions as $transaction) {
        $sheet->setCellValue('A' . $row, $transaction['transaction_type']);
        $sheet->setCellValue('B' . $row, $transaction['transaction_no']);
        $sheet->setCellValue('C' . $row, $transaction['person_name']);
        $sheet->setCellValue('D' . $row, date('m/d/Y', strtotime($transaction['created_at'])));
        $sheet->setCellValue('E' . $row, $transaction['product_name']);
        $sheet->setCellValue('F' . $row, $transaction['item_qty']);
        $sheet->setCellValue('G' . $row, $transaction['short_name']);
        $sheet->setCellValue('H' . $row, number_format($transaction['item_rate'], 2));
        $sheet->setCellValue('I' . $row, number_format($transaction['item_amount'], 2));

        // Update totals based on transaction type
        if (in_array(strtolower($transaction['transaction_type']), ['bill', 'expense'])) {
            $totalQty += $transaction['item_qty'];
            $totalAmount += $transaction['item_amount'];
        } elseif (strtolower($transaction['transaction_type']) === 'invoice') {
            $totalQty -= $transaction['item_qty'];
            $totalAmount -= $transaction['item_amount'];
        }
        $row++;
    }

    // Add totals row
    $sheet->setCellValue('A' . $row, 'Total');
    $sheet->setCellValue('F' . $row, $totalQty);
    $sheet->setCellValue('I' . $row, number_format($totalAmount, 2));

    // Set column widths
    foreach (range('A', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Write Excel file to output
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="detailed_report_' . $todayDate . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
}
?>
