<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_stock_movement')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <!-- Filter Section -->
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row align-items-end">
          <div class="col">
            <label for="dateFilter" class="form-label">Filter Date</label>
            <select id="dateFilter" class="form-control">
                <option value="all">All Date</option>
                <option value="custom">Custom Range</option>
            </select>
            <div id="dateRangeFields" class="d-none">
              <input type="date" id="start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" placeholder="Start Date" />
              <input type="date" id="end_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+1 days')); ?>" placeholder="End Date" />
            </div>
          </div>
          <div class="col">
            <button class="btn btn-primary btn-sm text-uppercase mt-auto" id="generateReportBTN">
              <iconify-icon icon="line-md:search" width="15" height="15"></iconify-icon>&nbsp; Generate
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Stock Movement Report Section -->
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Stock Movement Report</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <!-- Table to display the stock movement report -->
        <table id="stockMovementTable" class="table table-hover table-cs-color">
          <thead>
            <tr>
              <th>Product SKU</th>
              <th>Product Name</th>
              <th>Quantity In</th>
              <th>Quantity Out</th>
              <th>Total Purchase Value</th>
              <th>Total Sale Value</th>
              <th>Total Generalized Purchase</th>
              <th>Total Generalized Sale</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th colspan="2">Totals</th>
              <th id="totalQtyIn"></th>
              <th id="totalQtyOut"></th>
              <th id="totalPurchase"></th>
              <th id="totalSale"></th>
              <th id="totalGeneralPurchase"></th>
              <th id="totalGeneralSale"></th>
            </tr>
          </tfoot>
          <tbody>
            <!-- Dynamic report data will be inserted here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    // Show/hide date range inputs based on the selected filter option
    $('#dateFilter').on('change', function () {
        const selectedFilter = $(this).val();
        if (selectedFilter === 'custom') {
            $('#dateRangeFields').removeClass('d-none');  // Show date range fields
        } else {
            $('#dateRangeFields').addClass('d-none');  // Hide date range fields
        }
    });

    // Initialize DataTable
    const currentDate = '<?php echo date('M d, Y');?>';
    const stockMovementTable = $('#stockMovementTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: 'admin/process/admin_action.php',
            type: 'POST',
            data: function (d) {
                const dateFilter = $('#dateFilter').val();  // Get selected date filter
                const startDate = $('#start_date').val();  // Get start date
                const endDate = $('#end_date').val();  // Get end date
                return {
                    action: 'getStockMovementReport',
                    dateFilter: dateFilter,
                    startDate: startDate,
                    endDate: endDate
                };
            },
            dataSrc: function (json) {
                if (json.success) {
                    return json.data;  // Return data if the request is successful
                } else {
                    alert(json.message);  // Alert if there's an error
                    return [];
                }
            },
        },
        columns: [
            { data: 'product_sku', title: 'Product SKU' },
            { data: 'product_name', title: 'Product Name' },
            { data: 'quantity_in', title: 'Quantity In' },
            { data: 'quantity_out', title: 'Quantity Out' },
            { data: 'total_purchase_value', title: 'Total Purchase Value' },
            { data: 'total_sale_value', title: 'Total Sale Value' },
            { data: 'total_purchase_value_pp', title: 'Total Generalized Purchase' },
            { data: 'total_sale_value_pp', title: 'Total Generalized Sale' },
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                title: `Stock Movement Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'csv',
                title: `Stock Movement Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'excel',
                title: `Stock Movement Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'pdf',
                title: `Stock Movement Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'print',
                title: `Stock Movement Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            }
        ],
        responsive: true,
        paging: true,
        searching: false,
        footerCallback: function (row, data) {
            let totalQtyIn = 0, totalQtyOut = 0, totalPurchase = 0, totalSale = 0, totalGeneralPurchase = 0, totalGeneralSale = 0;

            // Aggregate totals
            data.forEach(row => {
                totalQtyIn += parseFloat(row.quantity_in) || 0;
                totalQtyOut += parseFloat(row.quantity_out) || 0;
                totalPurchase += parseFloat(row.total_purchase_value) || 0;
                totalSale += parseFloat(row.total_sale_value) || 0;
                totalGeneralPurchase += parseFloat(row.total_purchase_value_pp) || 0;
                totalGeneralSale += parseFloat(row.total_sale_value_pp) || 0;
            });

            // Update footer with totals
            const api = this.api();
            $(api.column(2).footer()).text(totalQtyIn.toFixed(2));
            $(api.column(3).footer()).text(totalQtyOut.toFixed(2));
            $(api.column(4).footer()).text(totalPurchase.toFixed(2));
            $(api.column(5).footer()).text(totalSale.toFixed(2));
            $(api.column(6).footer()).text(totalGeneralPurchase.toFixed(2));
            $(api.column(7).footer()).text(totalGeneralSale.toFixed(2));
        },
    });

    // Refresh table based on filter criteria (e.g., date range) when "Generate" button is clicked
    $('#generateReportBTN').on('click', function () {
        stockMovementTable.ajax.reload();  // Reload the DataTable with new filter data
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