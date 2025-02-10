<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_report')){ ?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Generate Company Report</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="reportForm">
          <!-- CSRF Token -->
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

          <!-- Action for the form -->
          <input type="hidden" name="action" value="generate_report">

          <div class="row gy-2">
            <div class="col-md-6">
              <label for="report_type" class="form-label">Report Type</label>
              <select id="report_type" class="form-control" name="report_type" required>
                <option value="single">Single Date</option>
                <option value="custom">Custom Date Range</option>
              </select>
            </div>

            <!-- This div will toggle between Single Date and Custom Date Range -->
            <div class="col-md-6" id="dateInputContainer">
              <div id="singleDateInput" style="display: block;">
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
            <div class="card-body" id="salesPanel">
              <!-- Sales and waste data will be displayed here -->
              <div id="salesSection">
                <h5>Sales Report</h5>
                <!-- Sales data will be injected here -->
              </div>
              <div id="wasteSection" class="mt-4">
                <h5>Waste Report</h5>
                <!-- Waste data will be injected here -->
              </div>
            </div>
          </div>
        </div>

        <div id="printSection" class="text-center mt-4" style="display: none;">
          <button id="printReport" class="btn btn-secondary">Print Report</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Toggle visibility based on selected report type (single or custom date range)
  $('#report_type').change(function() {
    var reportType = $(this).val();
    
    if (reportType === 'custom') {
      // Show date range inputs and hide the single date input
      $('#singleDateInput').hide(); // Hide the single date input section
      $('#date_range_inputs').show(); // Show the date range inputs

      // Remove required attribute from date_input when custom is selected
      $('#date_input').removeAttr('required');
      // Add required attribute to from_date and to_date when custom is selected
      $('#from_date, #to_date').attr('required', true);
    } else {
      // Show single date input and hide the date range inputs
      $('#singleDateInput').show(); // Show the single date input section
      $('#date_range_inputs').hide(); // Hide the date range inputs

      // Add required attribute to date_input when single is selected
      $('#date_input').attr('required', true);
      // Remove required attribute from from_date and to_date
      $('#from_date, #to_date').removeAttr('required');
    }
  });

  // Initialize form submission
  $('#reportForm').submit(function(event) {
    event.preventDefault();
    
    // Collect form data, including CSRF token and action
    var formData = $(this).serialize();
    
    $.ajax({
      url: 'admin/process/admin_action.php',
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function(response) {
        console.log(response); // Debugging response to ensure data is correct
        
        if (response.success) {
          // Show the report panel
          $('#reportResults').show();
          
          // Ensure the HTML content is inserted into the correct sections
          if (response.sales_html) {
            $('#salesSection').html(response.sales_html);  // Inject sales report table
          } else {
            $('#salesSection').html('<p>No sales data available.</p>');
          }

          if (response.waste_html) {
            $('#wasteSection').html(response.waste_html);  // Inject waste report message
          } else {
            $('#wasteSection').html('<p>No waste data available for the selected date range.</p>');
          }

          // Show the print section
          $('#printSection').show();
        } else {
          swal.fire('Error', response.message, 'error');
        }
      },
      error: function() {
        swal.fire('Error', 'Failed to generate report.', 'error');
      }
    });
  });

  // Print report functionality
  $('#printReport').click(function() {
    var printContent = document.getElementById('reportResults').innerHTML;
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Report</title></head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  });
});
</script>

<?php } else { ?>
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
<?php } ?>
