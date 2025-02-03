<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_inventory_stock')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row align-items-end">
          <div class="col">
            <label for="filter_stock" class="form-label">Filter Stock Level</label>
            <select class="selectpicker form-control" id="filter_stock" name="filter_stock" data-live-search="true" required>
              <option value="1">In Stock</option>
              <option value="2">Low Stock</option>
              <option value="3">Out of Stock</option>
            </select>
          </div>
          <div class="col">
            <button class="btn btn-primary btn-sm text-uppercase mt-auto" id="genereReport">
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
            <h5 class="mt-1 mb-0">Inventory Stock Report</h5>
          </div>
          <div class="col">
          </div>
        </div>
      </div>
      <div class="card-body">
        <table id="stockTable" class="table table-hover table-cs-color">
          <thead>
            <tr>
              <th>Product Name</th>
              <th>Stock</th>
              <th>Status</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th colspan="1">Total Stock:</th>
              <th id="totalStock" colspan="2"></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    // Initialize DataTable
    const currentDate = '<?php echo date('M d, Y');?>';
    const stockTable = $('#stockTable').DataTable({
      processing: true,
      serverSide: false, // Adjust this if you use server-side processing
      ajax: {
        url: 'admin/process/admin_action.php', // Replace with your actual backend script
        type: 'POST',
        data: function (d) {
          return {
            action: 'getStockReport',
            stock_level: $('#filter_stock').val(), // Send the selected stock level
          };
        },
        dataSrc: function (json) {
          if (json.success) {
            return json.data; // Return the data for DataTable
          } else {
            alert(json.message); // Show error message
            return [];
          }
        },
      },
      columns: [
        { data: 'product_name', title: 'Product Name' },
        { data: 'stock', title: 'Stock' },
        { 
          "data": "status",
          "render": function(data, type, row, meta) {
            var statusText;
            var statusColor;
            switch (data) {
              case 1:
                statusText = "In Stock";
                statusColor = "#58D68D"; // Green
                break;
              case 2:
                statusText = "Low Stock";
                statusColor = "#FFAF61"; // Orange
                break;
              default:
                statusText = "Out of Stock";
                statusColor = "#EC7063"; // Red
                break;
            }
            return '<span class="badge text-white" style="background-color: ' + statusColor + ';">' + statusText + '</span>';
          },
          "title": "Status",
        },
      ],
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'copy',
          title: `Inventory Stock Report ${currentDate}`,
          exportOptions: {
            columns: ":visible:not(.noExport)"
          }
        },
        {
          extend: 'csv',
          title: `Inventory Stock Report ${currentDate}`,
          exportOptions: {
            columns: ":visible:not(.noExport)"
          }
        },
        {
          extend: 'excel',
          title: `Inventory Stock Report ${currentDate}`,
          exportOptions: {
            columns: ":visible:not(.noExport)"
          }
        },
        {
          extend: 'pdf',
          title: `Inventory Stock Report ${currentDate}`,
          exportOptions: {
            columns: ":visible:not(.noExport)"
          }
        },
        {
          extend: 'print',
          title: `Inventory Stock Report ${currentDate}`,
          exportOptions: {
            columns: ":visible:not(.noExport)"
          }
        }
      ],
      responsive: true,
      paging: true,
      searching: false,
      footerCallback: function (row, data) {
        let totalStock = 0;

        // Aggregate totals
        data.forEach(row => {
          totalStock += parseFloat(row.stock) || 0;
        });

        // Update footer with totals
        const api = this.api();
        $(api.column(1).footer()).text(totalStock.toFixed(0)); // Update total stock
      }
    });

    // Refresh table on Generate button click
    $('#genereReport').on('click', function () {
      stockTable.ajax.reload();
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