<?php if (userHasPermission($pdo, $_SESSION["user_id"], 'manage_report')) { ?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">LOGS</h5>
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
        <table id="auditTable" class="table table-hover table-cs-color">
        </table>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  // Initialize DataTable
  var table = $('#auditTable').DataTable({
    responsive: true,
    select: true,
    autoWidth: false,
    footer: false,
    dom: 'Bfrtip',
    order: [[0, 'desc']],
    buttons: [
      {
        extend: 'copy',
        title: 'USER LOGS',
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'csv',
        title: 'USER LOGS',
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'excel',
        title: 'USER LOGS',
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'pdf',
        title: 'USER LOGS',
        exportOptions: { columns: ":visible:not(.noExport)" }
      },
      {
        extend: 'print',
        title: 'USER LOGS',
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
      url: 'admin/process/table.php?table_type=trail_logs',
      dataSrc: 'data'
    },
    columns: [
      { data: 'id', visible: false },
      { data: 'user_name', title: 'User', className: 'text-start' },
      { data: 'user_action', title: 'Action', className: 'text-start' },
      { data: 'user_module', title: 'Module', className: 'text-start' },
      { data: 'user_detail', title: 'Details', className: 'text-start' },
      { data: 'created_at', title: 'Timestamp', className: 'text-start' }
    ]
  });

  function exportToWord() {
      var table = document.getElementById("auditTable");

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
          <h2 style="text-align: center;">Logs Report</h2>
          <table>
              ${table.innerHTML}
          </table>
      </body>
      </html>`;

      var blob = new Blob(['\ufeff', html], { type: 'application/msword' });
      var url = URL.createObjectURL(blob);
      var a = document.createElement("a");
      a.href = url;
      a.download = "Logs_Report.doc";
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
    let createdAt = moment(data[5], "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss"); // Ensure we parse correctly

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
