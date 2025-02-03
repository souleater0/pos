<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row align-items-end">
          <div class="col">
            <label for="trans_type" class="form-label">Transaction Type</label>
            <select class="selectpicker form-control" id="trans_type" name="trans_type" data-live-search="true">
              <option value="none">All</option>
              <option value="bill">Bill</option>
              <option value="expense">Expense</option>
              <option value="invoice">Invoice</option>
            </select>
          </div>
          <div class="col">
            <label for="dateFilter" class="form-label">Filter Date</label>
            <select id="dateFilter" class="form-control">
              <option value="all">All Date</option>
              <option value="custom">Custom Range</option>
            </select>
          </div>
          <div id="dateRangeFields" class="col d-none">
            <div class="row">
              <div class="col">
                <input type="date" id="start_date" class="form-control mb-1" value="<?php echo date('Y-m-d'); ?>" placeholder="Start Date" />
              </div>
              <div class="col">
                <input type="date" id="end_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+1 days')); ?>" placeholder="End Date" />
              </div>
            </div>
          </div>
          <div class="col">
            <button class="btn btn-primary btn-sm text-uppercase mt-auto" id="generateReportBtn">
              <iconify-icon icon="line-md:search" width="15" height="15"></iconify-icon>&nbsp; Generate
            </button>
            <button class="btn btn-primary btn-sm text-uppercase mt-auto" id="generatePDF">
              <iconify-icon icon="teenyicons:pdf-outline" width="15" height="15"></iconify-icon>&nbsp; PDF
            </button>
            <button class="btn btn-success btn-sm text-uppercase mt-auto" id="exportToExcel">
              <iconify-icon icon="mdi:file-excel-outline" width="15" height="15"></iconify-icon>&nbsp; EXCEL
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Summary Report</h5>
          </div>
          <div class="col">
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="printArea">
        </div>
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

    $("#generateReportBtn").click(function () {
        // Get filter values
        const transactionType = $("#trans_type").val();
        const dateFilter = $("#dateFilter").val();
        const startDate = $("#start_date").val();
        const endDate = $("#end_date").val();

        // Validate custom date range if selected
        if (dateFilter === "custom" && (!startDate || !endDate)) {
            alert("Please select both start and end dates.");
            return;
        }

        $.ajax({
            url: "admin/process/admin_action.php", 
            method: "POST",
            data: {
                action: "generateSalesReport",
                transactionType: transactionType,
                dateFilter: dateFilter,
                startDate: startDate,
                endDate: endDate,
            },
            success: function (response) {
                if (response.success) {
                    const groupedData = response.summary;
                    let reportHTML = "";
                    let grandTotal = 0; // Initialize grand total

                    // Loop through categories
                    for (const categoryName in groupedData) {
                        const products = groupedData[categoryName];
                        let qtyTotal = 0;
                        let amountTotal = 0;
                        let percentTotal = 0;
                        let avgTotal = 0;

                        // Add category heading
                        reportHTML += `<h3>${categoryName}</h3>`;
                        reportHTML += `
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product SKU</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                        <th>% of Sales</th>
                                        <th>Avg Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        // Add product rows
                        products.forEach(product => {
                            qtyTotal += parseFloat(product.Qty);
                            amountTotal += parseFloat(product.Amount);
                            percentTotal += parseFloat(product.PercentageOfSales);
                            avgTotal += parseFloat(product.AvgPrice);
                            grandTotal += parseFloat(product.Amount);

                            reportHTML += `
                                <tr>
                                    <td>
                                        <a href="index.php?route=detailed-report&sku=${product.ProductSKU}&transactionType=${transactionType}&dateFilter=${dateFilter}&startDate=${startDate}&endDate=${endDate}" 
                                          class="text-primary" target="_blank" rel="noopener noreferrer">
                                            ${product.ProductSKU}
                                        </a>
                                    </td>
                                    <td class="text-dark">${product.ProductName}</td>
                                    <td class="text-dark">${product.Qty}</td>
                                    <td class="text-dark">${product.Amount}</td>
                                    <td class="text-dark">${product.PercentageOfSales}</td>
                                    <td class="text-dark">${product.AvgPrice}</td>
                                </tr>
                            `;
                        });

                        // Add grand total for the category
                        reportHTML += `
                                <tr>
                                    <td colspan="2" class="text-dark text-right"><strong>Subtotal</strong></td>
                                    <td class="text-dark"><strong>${qtyTotal.toFixed(2)}</strong></td>
                                    <td class="text-dark"><strong>${amountTotal.toFixed(2)}</strong></td>
                                    <td class="text-dark"><strong>${percentTotal.toFixed(2)}</strong></td>
                                    <td class="text-dark"><strong>${avgTotal.toFixed(2)}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        `;
                    }

                    // Add the grand total for all categories
                    reportHTML += `
                        <div class="row">
                            <div class="col-6 text-right">
                                <strong class="text-dark">Grand Total</strong>
                            </div>
                            <div class="col-6">
                                <strong class="text-dark">${grandTotal.toFixed(2)}</strong>
                            </div>
                        </div>
                    `;

                    // Insert the report into the print area
                    $("#printArea").html(reportHTML);
                } else {
                    console.error("Error generating report:", response.message);
                }
            },
            error: function (error) {
                console.error("AJAX error:", error);
            }
        });
    });

    $("#exportToExcel").click(function () {
    // Get the same filter values used in the report generation
    const transactionType = $("#trans_type").val();
    const dateFilter = $("#dateFilter").val();
    const startDate = $("#start_date").val();
    const endDate = $("#end_date").val();

    // Validate custom date range if selected
    if (dateFilter === "custom" && (!startDate || !endDate)) {
        alert("Please select both start and end dates.");
        return;
    }

    // Display a loading indicator (optional)
    const loader = $('<div class="loader">Exporting...</div>');
    $('body').append(loader);

    $.ajax({
        url: "admin/process/admin_action.php",
        method: "POST",
        data: {
            action: "generateSalesReport",
            transactionType: transactionType,
            dateFilter: dateFilter,
            startDate: startDate,
            endDate: endDate,
        },
        dataType: "json", // Ensure the response is parsed as JSON
        success: function (response) {
            // Remove loader
            loader.remove();

            if (response.success) {
                const summaryData = response.summary; // Summary data from the response
                const detailedData = response.detailed; // Detailed data from the response
                const filters = {
                    transactionType: transactionType,
                    dateFilter: dateFilter,
                    startDate: startDate,
                    endDate: endDate
                };

                // Trigger export to Excel via export_excel.php
                const form = $('<form>', {
                    action: 'admin/process/export_excel.php',
                    method: 'POST'
                }).append($('<input>', {
                    type: 'hidden',
                    name: 'summaryData',
                    value: JSON.stringify(summaryData)
                })).append($('<input>', {
                    type: 'hidden',
                    name: 'detailedData',
                    value: JSON.stringify(detailedData)
                })).append($('<input>', {
                    type: 'hidden',
                    name: 'filters',
                    value: JSON.stringify(filters)
                }));

                // Submit the form to trigger download
                $('body').append(form);
                form.submit();
                form.remove();
            } else {
                alert(response.message || "Failed to generate report for export.");
            }
        },
        error: function (xhr, status, error) {
            // Remove loader
            loader.remove();

            console.error("AJAX error:", error);
            alert("An error occurred while generating the report. Please try again.");
        }
    });
});

$("#generatePDF").click(function () {
    // Get filter values used for report generation
    const transactionType = $("#trans_type").val();
    const dateFilter = $("#dateFilter").val();
    const startDate = $("#start_date").val();
    const endDate = $("#end_date").val();

    // Validate custom date range if selected
    if (dateFilter === "custom" && (!startDate || !endDate)) {
        alert("Please select both start and end dates for the custom date range.");
        return;
    }

    // Prepare data for the AJAX request
    let requestData = {
        action: "generateSalesReport",
        transactionType: transactionType,
        dateFilter: dateFilter, // Pass the dateFilter as 'all' or 'custom'
    };

    // Add startDate and endDate if custom filter is selected
    if (dateFilter === "custom") {
        requestData.startDate = startDate;
        requestData.endDate = endDate;
    }

    // Fetch the report data via AJAX
    $.ajax({
        url: "admin/process/admin_action.php", // Backend endpoint
        method: "POST",
        data: requestData,
        success: function (response) {
            // Parse the response
            if (response.success) {
                const summaryData = response.summary; // Summary data from the response
                const detailedData = response.detailed; // Detailed data from the response

                // Create a form dynamically for PDF export
                const form = $('<form>', {
                    action: 'admin/process/export_pdf.php',
                    method: 'POST',
                    target: '_blank', // Open the PDF in a new tab
                }).append($('<input>', {
                    type: 'hidden',
                    name: 'summaryData',
                    value: JSON.stringify(summaryData),
                })).append($('<input>', {
                    type: 'hidden',
                    name: 'detailedData',
                    value: JSON.stringify(detailedData),
                })).append($('<input>', {
                    type: 'hidden',
                    name: 'filters',
                    value: JSON.stringify({ 
                        transactionType: transactionType, 
                        dateFilter: dateFilter,  // Include dateFilter in the PDF filters
                        startDate: startDate, 
                        endDate: endDate 
                    }),
                }));

                // Append, submit, and remove the form
                $('body').append(form);
                form.submit();
                form.remove();
            } else {
                alert(response.message || "Failed to generate report for PDF export.");
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("An error occurred while generating the report. Please try again.");
        },
    });
});





});
</script>
