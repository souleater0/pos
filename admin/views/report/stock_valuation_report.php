<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_stock_valuation')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
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
            <button class="btn btn-primary btn-sm text-uppercase mt-auto" id="generateStockValuationBTN">
              <iconify-icon icon="line-md:search" width="15" height="15"></iconify-icon>&nbsp; Generate
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Stock Valuation Report</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table id="stockTable" class="table table-hover table-cs-color">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Stock</th>
              <th>Total Value (Item Rate)</th>
              <th>Total Value (Product PP)</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Totals</th>
              <th id="totalStock"></th>
              <th id="totalItemRate"></th>
              <th id="totalProductPP"></th>
            </tr>
          </tfoot>
          <tbody>
            <!-- Dynamic data will be inserted here -->
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

    // Trigger the stock valuation report generation
    const currentDate = '<?php echo date('M d, Y');?>';
    const stockTable = $('#stockTable').DataTable({
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
                    action: 'getStockValuationReport',
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
            { data: 'product_name', title: 'Product Name' },
            { data: 'stock', title: 'Stock' },
            { data: 'total_value_based_on_item_rate', title: 'Total Value (Item Rate)' },
            { data: 'total_value_based_on_product_pp', title: 'Total Value (Product PP)' },
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                title: `Stock Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'csv',
                title: `Stock Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'excel',
                title: `Stock Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'pdf',
                title: `Stock Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'print',
                title: `Stock Report ${currentDate}`,
                exportOptions: { columns: ":visible:not(.noExport)" }
            }
        ],
        responsive: true,
        paging: true,
        searching: false,
        footerCallback: function (row, data) {
            let totalStock = 0, totalItemRate = 0, totalProductPP = 0;

            // Calculate totals
            data.forEach(row => {
                totalStock += parseFloat(row.stock) || 0;
                totalItemRate += parseFloat(row.total_value_based_on_item_rate) || 0;
                totalProductPP += parseFloat(row.total_value_based_on_product_pp) || 0;
            });

            // Update footer with totals
            const api = this.api();
            $(api.column(1).footer()).text(totalStock.toFixed(2));
            $(api.column(2).footer()).text(totalItemRate.toFixed(2));
            $(api.column(3).footer()).text(totalProductPP.toFixed(2));
        }
    });

    // Refresh table based on filter criteria (e.g., date range) when "Generate" button is clicked
    $('#generateStockValuationBTN').on('click', function () {
        stockTable.ajax.reload();  // Reload the DataTable with new filter data
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