<?php if (userHasPermission($pdo, $_SESSION["user_id"], 'manage_report')) { ?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Ingredient Waste Report</h5>
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
        <table id="ingredientWasteRep" class="table table-hover table-cs-color">
        <tfoot>
          <tr>
            <th colspan="1"></th>
            <th>Total:</th>
            <th></th>
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
  var table = $('#ingredientWasteRep').DataTable({
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
    },
    {
            text: "Export to Word",
            action: function(e, dt, node, config) {
                exportToWord();
            }
    }
  ],
  ajax: {
    url: 'admin/process/table.php?table_type=ing_waste_report',
    dataSrc: 'data'
  },
  columns: [
    { data: 'waste_id', visible: false },
    { data: 'ingredient_name', title: 'Product Name', className: 'text-start' },
    { data: 'ingredient_price', title: 'Price', className: 'text-start', render: $.fn.dataTable.render.number(',', '.', 2, '₱') },
    { data: 'quantity_wasted', title: 'Qty', className: 'text-start' },
    { 
      data: null, 
      title: 'Subtotal', 
      className: 'text-start', 
      render: function(data, type, row) {
        return '₱' + (parseFloat(row.ingredient_price) * parseInt(row.quantity_wasted)).toFixed(2);
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
      return typeof i === 'string'
        ? i.replace(/[\$,]/g, '') * 1
        : typeof i === 'number'
        ? i
        : 0;
    };

    // Compute the total price, quantity, and subtotal
    var totalPrice = api
      .column(2, { page: 'current' })
      .data()
      .reduce((a, b) => intVal(a) + intVal(b), 0);

    var totalQty = api
      .column(3, { page: 'current' })
      .data()
      .reduce((a, b) => intVal(a) + intVal(b), 0);

    var totalSubtotal = api
      .column(4, { page: 'current' })
      .data()
      .reduce((a, b) => intVal(a) + intVal(b.ingredient_price * b.quantity_wasted), 0);

    // Update footer
    $(api.column(2).footer()).html('₱' + totalPrice.toFixed(2));
    $(api.column(3).footer()).html(totalQty);
    $(api.column(4).footer()).html('₱' + totalSubtotal.toFixed(2));
  }
});


function exportToWord() {
      var table = document.getElementById("ingredientWasteRep");

      var html = `
      <html xmlns:o='urn:schemas-microsoft-com:office:office' 
            xmlns:w='urn:schemas-microsoft-com:office:word' 
            xmlns='http://www.w3.org/TR/REC-html40'>
      <head>
          <meta charset='utf-8'>
          <style>
              body {
                  font-family: Arial, sans-serif;
              }
              table {
                  width: 100%;
                  border-collapse: collapse;
                  margin: 20px 0;
                  font-size: 14px;
                  text-align: left;
              }
              th, td {
                  border: 1px solid #000;
                  padding: 8px 12px;
              }
              th {
                  background-color: #f2f2f2;
                  font-weight: bold;
                  text-align: center;
              }
              td {
                  text-align: right;
              }
          </style>
      </head>
      <body>
          <h2 style="text-align: center;">Ingredient Waste Report</h2>
          <table>
              ${table.innerHTML}
          </table>
      </body>
      </html>`;

      var blob = new Blob(['\ufeff', html], { type: 'application/msword' });
      var url = URL.createObjectURL(blob);
      var a = document.createElement("a");
      a.href = url;
      a.download = "Ingredient_Waste_Report.doc";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
  }

  // Initialize DateTime picker for min and max date inputs
  let minDate = new DateTime('#min', { format: 'MMMM Do YYYY h:mm A' });
  let maxDate = new DateTime('#max', { format: 'MMMM Do YYYY h:mm A' });

  // Custom filtering function for the date range
  $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    let min = $('#min').val(); // Get the raw value from the input field
    let max = $('#max').val(); // Get the raw value from the input field

    // Log the selected min and max values (raw input values)
    console.log("Min Date:", min);
    console.log("Max Date:", max);

    // Parse the min and max dates and convert them to the format "YYYY-MM-DD HH:mm:ss"
    let minFormatted = min ? moment(min, "MMMM Do YYYY h:mm A").format("YYYY-MM-DD HH:mm:ss") : null;
    let maxFormatted = max ? moment(max, "MMMM Do YYYY h:mm A").format("YYYY-MM-DD HH:mm:ss") : null;

    // Get the created_at timestamp from the table and format it to the same "YYYY-MM-DD HH:mm:ss" format
    let createdAt = moment(data[7], "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss"); // Ensure we parse correctly

    // Log the formatted created date
    console.log("Created Date (formatted):", createdAt);

    // Check if the created date falls between min and max
    let isValid = true;
    if (minFormatted && createdAt < minFormatted) isValid = false;
    if (maxFormatted && createdAt > maxFormatted) isValid = false;

    return isValid;
  });

  // Refilter the table when the date inputs change
  document.querySelectorAll('#min, #max').forEach((el) => {
    el.addEventListener('change', () => {
      // Get the raw values from the min and max date inputs
      let minVal = $('#min').val();  // Directly access the input value using jQuery
      let maxVal = $('#max').val();  // Directly access the input value using jQuery

      // Log the exact values selected by the user
      console.log("Formatted Min Date:", minVal);
      console.log("Formatted Max Date:", maxVal);

      // Redraw the table with the new filter criteria
      table.draw();
    });
  });
  function getExportTitle() {
    let baseTitle = "Ingredient Waste Report";
    let minDate = $('#min').val();
    let maxDate = $('#max').val();

    if (minDate && maxDate) {
      return `${baseTitle} (${minDate} - ${maxDate})`;
    } else if (minDate) {
      return `${baseTitle} (From ${minDate})`;
    } else if (maxDate) {
      return `${baseTitle} (Until ${maxDate})`;
    } else {
      return baseTitle;
    }
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