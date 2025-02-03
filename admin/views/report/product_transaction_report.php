<?php
// Fetch the product list from the database
$productlists = getProductList($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product_history')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row align-items-start">
          <div class="col">
          <label for="product_sku" class="form-label">Select Product</label>
          <select class="selectpicker form-control" id="product_sku" name="product_sku" data-live-search="true">
              <?php foreach ($productlists as $product): ?>
                <option value="<?php echo $product['product_sku']; ?>">
                  <?php echo $product['product_name']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col">
            <label for="dateFilter" class="form-label">Filter Date</label>
            <select id="dateFilter" class="form-control">
                <option value="all">All Date</option>
                <option value="custom">Custom Range</option>
            </select>
            <div id="dateRangeFields" class="d-none mt-2">
              <input type="date" id="start_date" class="form-control mb-1" value="<?php echo date('Y-m-d'); ?>" placeholder="Start Date" />
              <input type="date" id="end_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+1 days')); ?>" placeholder="End Date" />
            </div>
          </div>
          <div class="col">
            <button class="btn btn-primary btn-sm text-uppercase mt-auto" id="generateReportBtn">
              <iconify-icon icon="line-md:search" width="15" height="15"></iconify-icon>&nbsp; Retrieve Transactions
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Transactions Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Transaction Details</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table id="transactionsTable" class="table table-hover table-cs-color">
        <tbody>
        <!-- Table content will be dynamically populated -->
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" class="text-right">Totals:</th>
            <th id="total_qty">0</th>
            <th id="total_rate">0</th>
            <th id="total_amount">0</th>
        </tr>
        </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    // Toggle custom date fields based on filter selection
    $('#dateFilter').on('change', function () {
        const isCustom = $(this).val() === 'custom';
        $('#dateRangeFields').toggleClass('d-none', !isCustom);
    });

    const transactionsTable = $('#transactionsTable').DataTable({
    processing: true,
    serverSide: false,
    ajax: {
        url: 'admin/process/admin_action.php',
        type: 'POST',
        data: function () {
            const dateFilter = $('#dateFilter').val();
            return {
                action: 'getProductTransactions',
                product_sku: $('#product_sku').val(),
                dateFilter: dateFilter,
                start_date: dateFilter === 'custom' ? $('#start_date').val() : null,
                end_date: dateFilter === 'custom' ? $('#end_date').val() : null
            };
        },
        dataSrc: function (response) {
            console.log(response);
            if (response.success) {
                return response.data;
            } else {
                alert(response.message || "Failed to load transactions.");
                return [];
            }
        },
        error: function (xhr, status, error) {
            console.error("Error response:", xhr.responseText);
            alert(`Error: Unable to retrieve data. ${error}`);
        }
    },
    columns: [
        { data: 'transaction_no', title: 'Transaction No' },
        { data: 'transaction_type', title: 'Type' },
        { data: 'p_name', title: 'Name' },
        { data: 'created_at', title: 'Date' },
        { data: 'item_qty', title: 'Qty' },
        { data: 'item_rate', title: 'Rate' },
        { data: 'total_amount', title: 'Total' }
    ],
    dom: 'Bfrtip',
    buttons: [
        {
            extend: 'copy',
            title: `Inventory Stock Report ${new Date().toLocaleDateString()}`,
            messageTop: function () {
                    const selectedText = $('#product_sku option:selected').text(); // Get selected text
                    return `Product: ${selectedText}`;
            },
            exportOptions: {
                columns: ':visible:not(.noExport)'
            }
        },
        {
            extend: 'csv',
            title: `Inventory Stock Report ${new Date().toLocaleDateString()}`,
            messageTop: function () {
                    const selectedText = $('#product_sku option:selected').text(); // Get selected text
                    return `Product: ${selectedText}`;
            },
            exportOptions: {
                columns: ':visible:not(.noExport)'
            }
        },
        {
            extend: 'excel',
            title: `Inventory Stock Report ${new Date().toLocaleDateString()}`,
            messageTop: function () {
                    const selectedText = $('#product_sku option:selected').text(); // Get selected text
                    return `Product: ${selectedText}`;
            },
            exportOptions: {
                columns: ':visible:not(.noExport)'
            }
        },
        {
            extend: 'pdf',
            title: `Inventory Stock Report ${new Date().toLocaleDateString()}`,
            messageTop: function () {
                    const selectedText = $('#product_sku option:selected').text(); // Get selected text
                    return `Product: ${selectedText}`;
            },
            exportOptions: {
                columns: ':visible:not(.noExport)'
            }
        },
        {
            extend: 'print',
            title: `Inventory Stock Report ${new Date().toLocaleDateString()}`,
            messageTop: function () {
                    const selectedText = $('#product_sku option:selected').text(); // Get selected text
                    return `Product: ${selectedText}`;
            },
            exportOptions: {
                columns: ':visible:not(.noExport)'
            }
        }
    ],
    responsive: true,
    searching: false,
    paging: true,
    footerCallback: function (row, data) {
        let totalQty = 0, totalRate = 0, totalAmount = 0;

        // Aggregate totals
        data.forEach(row => {
            totalQty += parseFloat(row.item_qty) || 0;
            totalRate += parseFloat(row.item_rate) || 0;
            totalAmount += parseFloat(row.total_amount) || 0;
        });

        // Update footer with totals
        const api = this.api();
        $(api.column(4).footer()).text(totalQty.toFixed(0));
        $(api.column(5).footer()).text(totalRate.toFixed(2));
        $(api.column(6).footer()).text(totalAmount.toFixed(2));
    }
});


    $('#generateReportBtn').on('click', function () {
        transactionsTable.ajax.reload();  // Reload the DataTable with new filter data
    });

});
</script>
<?php }else{
  echo '
  <div class="d-flex justify-content-center align-items-center vh-100">
  <div class="container">
      <div class="row">
          <div class="col text-center">
              <iconify-icon icon="maki:caution" width="50" height="50"></iconify-icon>
              <h2 class="fw-bolder">User does not have permission!</h2>
              <p>We are sorry, your account does not have permission to access this page.</p>
          </div>
      </div>
  </div>
</div>
  ';
}?>