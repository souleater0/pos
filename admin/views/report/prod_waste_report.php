<?php if (userHasPermission($pdo, $_SESSION["user_id"], 'manage_report')) { ?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Product Waste Report</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table class="mb-3" cellspacing="5" cellpadding="5">
          <tbody>
            <tr>
              <td>Minimum date:</td>
              <td><input type="text" id="min" name="min" class="datetimepicker"></td>
            </tr>
            <tr>
              <td>Maximum date:</td>
              <td><input type="text" id="max" name="max" class="datetimepicker"></td>
            </tr>
          </tbody>
        </table>
        <table id="productWasteRep" class="table table-hover table-cs-color">
        <tfoot>
          <tr>
            <th colspan="1"></th>
            <th>Total:</th>
            <th></th>
            <th></th>
            <th colspan="4"></th>
          </tr>
        </tfoot>
      </table>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  // Initialize DataTable
  var table = $('#productWasteRep').DataTable({
    responsive: true,
    select: false,
    autoWidth: false,
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'copy',
        title: function() {
          return getExportTitle();
        },
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'csv',
        title: function() {
          return getExportTitle();
        },
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'excel',
        title: function() {
          return getExportTitle();
        },
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'pdf',
        title: function() {
          return getExportTitle();
        },
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'print',
        title: function() {
          return getExportTitle();
        },
        exportOptions: { columns: ":visible:not(.noExport)" }
      }
    ],
    ajax: {
      url: 'admin/process/table.php?table_type=prod_waste_report',
      dataSrc: 'data'
    },
    columns: [
      { data: 'waste_id', visible: false },
      { data: 'product_name', title: 'Product Name', className: 'text-start' },
      { data: 'product_price', title: 'Price', className: 'text-start', render: $.fn.dataTable.render.number(',', '.', 2, '₱') },
      { data: 'quantity_wasted', title: 'Qty', className: 'text-start' },
      { 
        data: null, 
        title: 'Subtotal', 
        className: 'text-start', 
        render: function(data, type, row) {
          return '₱' + (parseFloat(row.product_price) * parseInt(row.quantity_wasted)).toFixed(2);
        }
      },
      { data: 'reason', title: 'Reason', className: 'text-start' },
      { data: 'reported_by', title: 'Reported By', className: 'text-start' },
      { data: 'created_at', title: 'Date', className: 'text-start' }
    ],
    footerCallback: function (row, data, start, end, display) {
      var api = this.api();

      // Function to get the total of a column
      var intVal = function (i) {
        return typeof i === 'string' ? i.replace(/\₱|,/g, '') * 1 :
               typeof i === 'number' ? i : 0;
      };

      // Compute the total quantity and subtotal
      var totalQty = api.column(3, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
      var totalSubtotal = api.column(4, { page: 'current' }).data().reduce((a, b) => intVal(a) + (b.product_price * b.quantity_wasted), 0);

      // Update footer
      $(api.column(3).footer()).html(totalQty);
      $(api.column(4).footer()).html('₱' + totalSubtotal.toFixed(2));
    }
  });

  // Initialize DateTime picker for min and max date inputs
  let minDate = new DateTime('#min', { format: 'MMMM Do YYYY h:mm A' });
  let maxDate = new DateTime('#max', { format: 'MMMM Do YYYY h:mm A' });

  // Custom filtering function for the date range
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    let min = $('#min').val();
    let max = $('#max').val();
    let minFormatted = min ? moment(min, "MMMM Do YYYY h:mm A").format("YYYY-MM-DD HH:mm:ss") : null;
    let maxFormatted = max ? moment(max, "MMMM Do YYYY h:mm A").format("YYYY-MM-DD HH:mm:ss") : null;
    let createdAt = moment(data[7], "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss");

    return (!minFormatted || createdAt >= minFormatted) && (!maxFormatted || createdAt <= maxFormatted);
  });

  // Refilter the table when the date inputs change
  document.querySelectorAll('#min, #max').forEach((el) => {
    el.addEventListener('change', () => table.draw());
  });

  function getExportTitle() {
    let baseTitle = "Product Waste Report";
    let minDate = $('#min').val();
    let maxDate = $('#max').val();
    if (minDate && maxDate) return `${baseTitle} (${minDate} - ${maxDate})`;
    if (minDate) return `${baseTitle} (From ${minDate})`;
    if (maxDate) return `${baseTitle} (Until ${maxDate})`;
    return baseTitle;
  }
});
</script>
<?php } else { ?>
<div class="d-flex justify-content-center align-items-center vh-100">
  <div class="container text-center">
    <iconify-icon icon="maki:caution" width="50" height="50"></iconify-icon>
    <h2 class="fw-bolder">User does not have permission!</h2>
    <p>We are sorry, your account does not have permission to access this page.</p>
  </div>
</div>
<?php } ?>