<?php
// Retrieve and sanitize the filter parameters from the URL
$sku = isset($_GET['sku']) ? htmlspecialchars($_GET['sku']) : '';
$transactionType = isset($_GET['transactionType']) ? htmlspecialchars($_GET['transactionType']) : 'none';
$dateFilter = isset($_GET['dateFilter']) ? htmlspecialchars($_GET['dateFilter']) : '';
$startDate = isset($_GET['startDate']) ? htmlspecialchars($_GET['startDate']) : '';
$endDate = isset($_GET['endDate']) ? htmlspecialchars($_GET['endDate']) : '';

// Call the function to retrieve product and transactions
$data = getProductAndTransactions($pdo, $sku, $transactionType, $dateFilter, $startDate, $endDate);
$product = $data['product'];
$transactionResult = $data['transactions'];
?>

<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Detailed Report</h5>
          </div>
          <div class="col text-end">
            <?php if ($product && $transactionResult): ?>
              <button id="exportPdf" class="btn btn-danger">Export to PDF</button>
              <button id="exportExcel" class="btn btn-success">Export to Excel</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <?php if ($product): ?>
          <h3 id="productText"><?php echo htmlspecialchars($product['product_name']); ?></h3>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Transaction Type</th>
                <th>Date of Transaction</th>
                <th>Transaction No</th>
                <th>Name</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>U/M</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($transactionResult): ?>
                <?php foreach ($transactionResult as $transaction): ?>
                  <tr>
                    <td class="text-dark"><?php echo ucfirst(htmlspecialchars($transaction['transaction_type'])); ?></td>
                    <td class="text-dark"><?php echo date('Y-m-d', strtotime($transaction['created_at'])); ?></td>
                    <td class="text-dark"><?php echo htmlspecialchars($transaction['transaction_no']); ?></td>
                    <td class="text-dark"><?php echo htmlspecialchars($transaction['person_name']); ?></td>
                    <td class="text-dark"><?php echo htmlspecialchars($transaction['product_name']); ?></td>
                    <td class="text-dark"><?php echo htmlspecialchars($transaction['item_qty']); ?></td>
                    <td class="text-dark"><?php echo htmlspecialchars($transaction['short_name']); ?></td>
                    <td class="text-dark"><?php echo number_format($transaction['item_rate'], 2); ?></td>
                    <td class="text-dark"><?php echo number_format($transaction['item_amount'], 2); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7">No transactions found for the given criteria.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No product found with the given SKU.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Trigger the export for PDF or Excel
  $('#exportPdf').click(function() {
    exportData('pdf');
  });

  $('#exportExcel').click(function() {
    exportData('excel');
  });

// Function to handle data export
function exportData(format) {
    const requestData = {
        format: format,
        product: '<?php echo htmlspecialchars($product['product_name']); ?>', // Dynamic product name
        sku: '<?php echo htmlspecialchars($sku); ?>', // Dynamic SKU
        transactionType: '<?php echo htmlspecialchars($transactionType); ?>', // Dynamic transaction type
        dateFilter: '<?php echo htmlspecialchars($dateFilter); ?>', // Dynamic date filter
        startDate: '<?php echo htmlspecialchars($startDate); ?>', // Dynamic start date
        endDate: '<?php echo htmlspecialchars($endDate); ?>', // Dynamic end date
        transactions: <?php echo json_encode($transactionResult); ?> // Dynamic transaction data
    };

    console.log(requestData); // Log the request data for debugging

    const form = $('<form>', {
        action: 'admin/process/export_detail.php',
        method: 'POST',
        target: '_blank', // Open the PDF in a new tab
    }).append($('<input>', {
        type: 'hidden',
        name: 'groupedData',
        value: JSON.stringify(requestData),
    })).append($('<input>', {
        type: 'hidden',
        name: 'filters',
        value: JSON.stringify({ 
            transactionType: requestData.transactionType, 
            dateFilter: requestData.dateFilter,  // Include dateFilter in the PDF filters
            startDate: requestData.startDate, 
            endDate: requestData.endDate 
        }),
    }));

    // Append, submit, and remove the form
    $('body').append(form);
    form.submit();
    form.remove();
}
});
</script>
