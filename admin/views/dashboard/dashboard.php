<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_dashboard')){?>
<div class="body-wrapper-inner">
    <div class="container-fluid mw-100">
        <!-- Date Filter -->
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6">
                <!-- Custom Date Range Picker -->
                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row">
            <!-- Total Sales -->
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="round-48 d-flex align-items-center justify-content-center rounded bg-secondary-subtle">
                                <iconify-icon icon="mdi:cart" class="fs-6 text-primary"></iconify-icon>
                            </span>
                            <h6 class="mb-0 fs-5 text-uppercase">Total Sales</h6>
                        </div>
                        <h4 id="totalSales">₱0.00</h4>
                    </div>
                </div>
            </div>

            <!-- Product Waste Cost -->
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="round-48 d-flex align-items-center justify-content-center rounded bg-secondary-subtle">
                                <iconify-icon icon="vaadin:stock" class="fs-6 text-primary"></iconify-icon>
                            </span>
                            <h6 class="mb-0 fs-5 text-uppercase">Product Waste Cost</h6>
                        </div>
                        <h4 id="productWasteCost">₱0.00</h4>
                    </div>
                </div>
            </div>

            <!-- Ingredient Waste Cost -->
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span class="round-48 d-flex align-items-center justify-content-center rounded bg-secondary-subtle">
                                <iconify-icon icon="healthicons:rdt-result-out-stock" class="fs-6 text-primary"></iconify-icon>
                            </span>
                            <h6 class="mb-0 fs-5 text-uppercase">Ingredient Waste Cost</h6>
                        </div>
                        <h4 id="ingredientWasteCost">₱0.00</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <!-- Sales Over Time (Bar Chart) -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sales Over Time</h5>
                        <div id="salesChart"></div>
                    </div>
                </div>
            </div>

            <!-- Top-Selling Products (Pie Chart) -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Top-Selling Products</h5>
                        <div id="topProductsChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Waste Breakdown (Pie Chart) -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Waste Breakdown</h5>
                        <div id="wasteChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Payment Breakdown</h5>
                        <div id="paymentTypeChart"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Initialize the custom date range picker
$(function() {
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);
    
    // Trigger the 'apply' event on load after a small delay
    setTimeout(function() {
        $('#reportrange').data('daterangepicker').setStartDate(start);
        $('#reportrange').data('daterangepicker').setEndDate(end);

        // Manually trigger the apply event after setting the dates
        $('#reportrange').trigger('apply.daterangepicker', { startDate: start, endDate: end });
    }, 100);

    // Declare the chart variables globally so they can be accessed for destruction
    var salesChart, topProductsChart, wasteChart, paymentTypeChart;

    // Trigger the AJAX call when the date range is applied
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

        // Send an AJAX request to fetch data based on selected date range
        $.ajax({
            url: 'admin/process/admin_action.php',
            type: 'POST',
            data: {
                action: 'fetchDashboardData',
                startDate: startDate,
                endDate: endDate,
                csrf_token: csrfToken
            },
            success: function(response) {
                var data = JSON.parse(response);

                // Ensure values exist before using toFixed()
                $('#totalSales').text('₱' + (data.totalSales ? parseFloat(data.totalSales).toFixed(2) : '0.00'));
                $('#productWasteCost').text('₱' + (data.productWasteCost ? parseFloat(data.productWasteCost).toFixed(2) : '0.00'));
                $('#ingredientWasteCost').text('₱' + (data.ingredientWasteCost ? parseFloat(data.ingredientWasteCost).toFixed(2) : '0.00'));

                // Destroy existing charts if they exist
                if (salesChart) salesChart.destroy();
                if (topProductsChart) topProductsChart.destroy();
                if (wasteChart) wasteChart.destroy();
                if (paymentTypeChart) paymentTypeChart.destroy();

                // Update Sales Over Time Chart (Bar Chart)
                salesChart = new ApexCharts(document.querySelector("#salesChart"), {
                    chart: { type: 'bar', height: 350 },
                    series: [{
                        name: 'Sales',
                        data: data.salesData.map(function(value) { return parseFloat(value); }) // Convert string to number
                    }],
                    xaxis: { categories: data.salesDates }
                });
                salesChart.render();

                // Update Top-Selling Products Chart (Pie Chart)
                topProductsChart = new ApexCharts(document.querySelector("#topProductsChart"), {
                    chart: { type: 'pie', height: 350 },
                    series: data.topSellingQuantities.map(function(value) { return parseInt(value); }), // Convert to number
                    labels: data.topSellingProducts
                });
                topProductsChart.render();

                // Update Waste Breakdown Chart (Pie Chart)
                wasteChart = new ApexCharts(document.querySelector("#wasteChart"), {
                    chart: { type: 'pie', height: 350 },
                    series: [
                        parseFloat(data.productWasteCost),
                        parseFloat(data.ingredientWasteCost)
                    ],
                    labels: ['Product Waste', 'Ingredient Waste']
                });
                wasteChart.render();

                // Update Payment Type Chart (Bar Chart)
                paymentTypeChart = new ApexCharts(document.querySelector("#paymentTypeChart"), {
                    chart: { type: 'bar', height: 350 },
                    series: [{
                        name: 'Sales by Payment Type',
                        data: data.salesPaymentData.map(function(value) { return parseFloat(value); }) // Convert to number
                    }],
                    xaxis: { categories: data.salesPaymentTypes }
                });
                paymentTypeChart.render();
            },
            error: function() {
                console.error("Error fetching dashboard data.");
            }
        });
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
