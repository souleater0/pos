<?php if (userHasPermission($pdo, $_SESSION["user_id"], 'manage_report')) { ?>
<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="mt-1 mb-0">Generate Company Report</h5>
      </div>
      <div class="card-body">
        <form id="reportForm">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          <input type="hidden" name="action" value="generate_report">

          <div class="row gy-2">
            <div class="col-md-6">
              <label for="report_type" class="form-label">Report Type</label>
              <select id="report_type" class="form-control" name="report_type" required>
                <option value="single">Single Date</option>
                <option value="custom">Custom Date Range</option>
              </select>
            </div>

            <div class="col-md-6" id="dateInputContainer">
              <div id="singleDateInput">
                <label for="date_input" class="form-label">Select Date</label>
                <input type="date" id="date_input" class="form-control" name="date_input" required />
              </div>
              <div id="date_range_inputs" style="display: none;">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" id="from_date" class="form-control" name="from_date" />
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" id="to_date" class="form-control" name="to_date" />
              </div>
            </div>
          </div>

          <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Generate Report</button>
          </div>
        </form>

        <div id="reportResults" class="mt-4" style="display: none;">
          <div class="card shadow-sm">
            <div class="card-body">
              <h5>Sales Report</h5>
              <table id="salesTable" class="table table-striped display nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>Transaction Date</th>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount Amt</th>
                    <th>Subtotal</th>
                    <th>Discount</th>
                    <th>Grand Total</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Product Name</th>
                        <th id="totalItemsSold">0</th>  <!-- Total Qty -->
                        <th>Price</th>
                        <th id="totalItemDiscount">0.00</th>  <!-- Total Discount Amt -->
                        <th id="totalSale">0.00</th>  <!-- Total Subtotal -->
                        <th id="totalDiscount">0.00</th>  <!-- Total Discount -->
                        <th id="totalGrandTotal">0.00</th>  <!-- Grand Total -->
                    </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
      // Function to get the date in Philippine time (UTC+8)
      function getPhilippineDate(date = new Date()) {
        return new Intl.DateTimeFormat('en-CA', { // en-CA format ensures YYYY-MM-DD
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        }).format(date).replace(/\//g, "-"); // Convert YYYY/MM/DD to YYYY-MM-DD
    }

    let todayFormatted = getPhilippineDate();
    let firstDay = new Date();
    firstDay.setDate(1);
    let firstDayFormatted = getPhilippineDate(firstDay);

    $("#date_input").val(todayFormatted);
    $("#from_date").val(firstDayFormatted);
    $("#to_date").val(todayFormatted);

    $("#report_type").change(function () {
        let isCustom = $(this).val() === "custom";
        $("#singleDateInput").toggle(!isCustom);
        $("#date_range_inputs").toggle(isCustom);
        $("#date_input").prop("required", !isCustom);
        $("#from_date, #to_date").prop("required", isCustom);
        if (isCustom) {
            $("#from_date").val(firstDayFormatted);
            $("#to_date").val(todayFormatted);
        } else {
            $("#date_input").val(todayFormatted);
        }
    });

    var table = $("#salesTable").DataTable({
        responsive: true,
        autoWidth: false,
        dom: "Bfrtip",
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
        data: [],
        columns: [
            { data: "transaction_date", title: "Transaction Date", className: 'text-start text-dark'},
            { data: "item_name", title: "Product Name", className: 'text-start text-dark'},
            { data: "item_qty", title: "Qty", className: 'text-start text-dark'},
            { data: "item_price", title: "Price", className: 'text-start text-dark'},
            { data: "item_discount_amt", title: "Discount Amt", className: 'text-start text-dark'},
            { data: "transaction_subtotal", title: "Subtotal", className: 'text-start text-dark'},
            { data: "transaction_discount", title: "Discount", className: 'text-start text-dark'},
            { data: "transaction_grandtotal", title: "Grand Total", className: 'text-start text-dark'}
        ],
        footerCallback: function (row, data, start, end, display) {
          var api = this.api();

          // Function to sum all column values (full dataset, not just displayed page)
          function sumColumn(index) {
              return api
                  .column(index, { search: "applied" }) // Use full dataset
                  .data()
                  .reduce((a, b) => (parseFloat(a) || 0) + (parseFloat(b) || 0), 0);
          }

          // Column order based on table structure:
          let totalItemsSold = sumColumn(2);  // Qty Column Index
          let totalItemDiscount = sumColumn(4);  // Discount Amt Column Index
          let totalSale = sumColumn(5);  // Subtotal Column Index
          let totalDiscount = sumColumn(6);  // Discount Column Index
          let grandTotal = sumColumn(7);  // Grand Total Column Index

          // Update footer text
          $("#totalItemsSold").text(totalItemsSold);
          $("#totalItemDiscount").text(totalItemDiscount.toFixed(2));
          $("#totalSale").text(totalSale.toFixed(2));
          $("#totalDiscount").text(totalDiscount.toFixed(2));
          $("#totalGrandTotal").text(grandTotal.toFixed(2)); // Added Grand Total
      }
    });

    function exportToWord() {
      var table = document.getElementById("salesTable");

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
          <h2 style="text-align: center;">Sales Report</h2>
          <table>
              ${table.innerHTML}
          </table>
      </body>
      </html>`;

      var blob = new Blob(['\ufeff', html], { type: 'application/msword' });
      var url = URL.createObjectURL(blob);
      var a = document.createElement("a");
      a.href = url;
      a.download = "Sales_Report.doc";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
  }

    $('#reportForm').submit(function (event) {
        event.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: 'admin/process/admin_action.php',
            method: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function () {
                Swal.fire({ title: 'Generating Report...', didOpen: () => Swal.showLoading() });
            },
            success: function (response) {
                Swal.close();
                if (response.success && Array.isArray(response.data)) {
                    $('#reportResults').show();
                    // Clear and update table data
                            table.clear().rows.add(response.data).draw();

                    // Force DataTable to recalculate and refresh footer
                    table.columns.adjust().draw();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Failed to generate report.', 'error');
            }
        });
    });

    $('.buttons-excel, .buttons-csv, .buttons-pdf, .buttons-print').click(function (e) {
        if (table.rows().count() === 0) {
            e.preventDefault();
            Swal.fire("No Data", "There is no data to export!", "warning");
        }
    });

    function getExportTitle() {
      let baseTitle = "Takoyame Sales Report";
      let reportType = $("#report_type").val();
      let singleDate = $("#date_input").val();
      let fromDate = $("#from_date").val();
      let toDate = $("#to_date").val();

      if (reportType === "single" && singleDate) {
          return `${baseTitle} (${singleDate})`;
      } else if (reportType === "custom" && fromDate && toDate) {
          return `${baseTitle} (${fromDate} - ${toDate})`;
      } else if (reportType === "custom" && fromDate) {
          return `${baseTitle} (From ${fromDate})`;
      } else if (reportType === "custom" && toDate) {
          return `${baseTitle} (Until ${toDate})`;
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