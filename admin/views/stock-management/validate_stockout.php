<?php
// Check if picklist data is stored in session
if (!isset($_SESSION['picklist']) || empty($_SESSION['picklist'])) {
    echo 'No picklist data found';
    exit;
}
// Get picklist data from session
$picklist = $_SESSION['picklist'];
$last_series_number = getStockOutNumber($pdo);
$suggestedItems = getSuggestedbySystem($pdo, $picklist);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_stockout')){?>
<div class="body-wrapper-inner">
<div class="container-fluid" style="max-width: 100% !important;">
    <div class="col-lg-6">
        <div class="d-flex">
          <div class="d-flex justify-content-center align-items-center">
            <a href="index.php?route=stock-out" class="text-uppercase">Pick List</iconify-icon></a>
          </div>
          <div class="d-flex justify-content-center align-items-center px-2">
            /
          </div>
          <div class="d-flex justify-content-center align-items-center">
            <a href="index.php?route=validate-stockout" class="text-uppercase text-primary fw-bold">Stock Out</a>
          </div>
        </div>
    </div>
  </div>
  <div class="container-fluid" style="max-width: 100% !important;">
    <div class="card shadow-sm w-50">
          <div class="card-header bg-transparent border-bottom">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="mt-1 mb-0">Stock Out Number</h5>
              </div>
              <div class="col">
                <input type="text" class="form-control bg-secondary-subtle" id="stockOutNumber" value="<?php echo $last_series_number;?>" readonly>
              </div>
            </div>
          </div>
    </div>
    <div class="row">
    <div class="col-lg-4">
        <div class="card shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <div class="row">
            <div class="col">
                <h5 class="mt-1 mb-0">Pick List</h5>
            </div>
            </div>
        </div>
        <div class="card-body">
                <table id="picklistTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($picklist as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['product_sku']); ?></td>
                                <td><?php echo htmlspecialchars($item['product_qty']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <div class="row">
            <div class="col">
                <h5 class="mt-1 mb-0">Suggested by System</h5>
            </div>
            </div>
        </div>
        <div class="card-body">
            <table id="suggestedItemsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Barcode</th>
                        <th>Expiry</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suggestedItems as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_sku']); ?></td>
                            <td><?php echo htmlspecialchars($item['barcode']); ?></td>
                            <td><?php echo htmlspecialchars($item['expiry']); ?></td>
                            <td><?php echo htmlspecialchars($item['qty']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <div class="row">
                <div class="col">
                    <h5 class="mt-1 mb-0">Validate Items</h5>
                </div>
                <div class="col">
                    <button class="btn btn-primary btn-sm float-end fs-3" id="proceedStockOut"><iconify-icon icon="healthicons:stock-out" width="20" height="20"></iconify-icon>&nbsp;Proceed Stock Out</button>
                </div>
                </div>
            </div>
            <div class="card-body">
                <input type="text" id="barcodeInput" placeholder="Scan Barcode" class="form-control mb-3">
                <div id="validationResult"></div>
                <table id="validateItemTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>Barcode</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Expiry</th>
                            <th>Quantity</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suggestedItems as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['product_sku']); ?></td>
                                <td><?php echo htmlspecialchars($item['barcode']); ?></td>
                                <td><?php echo htmlspecialchars($item['product_pp']); ?></td>
                                <td><?php echo htmlspecialchars($item['product_sp']); ?></td>
                                <td><?php echo htmlspecialchars($item['expiry']); ?></td>
                                <td><?php echo htmlspecialchars($item['qty']); ?></td>
                                <td class="status" data-sku="<?php echo htmlspecialchars($item['barcode']); ?>" data-required-qty="<?php echo htmlspecialchars($item['qty']); ?>">
                                    <span class="current-qty">0</span>/<?php echo htmlspecialchars($item['qty']); ?> <i class="fas fa-times text-danger"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    </div>
  </div>
</div>

    <script>
        $(document).ready(function() {
            $('#picklistTable').DataTable({
                order: [[1, 'asc']],
                paging: true,
                scrollCollapse: true,
                scrollX: true,
                scrollY: 300,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    {
                        targets: 0, // First column
                        className: 'text-dark text-start' // Add your class name here
                    },
                    {
                        targets: 1, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                    {
                        targets: 2, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                ]
            });
            
            $('#suggestedItemsTable').DataTable({
                order: [[1, 'asc']],
                paging: true,
                scrollCollapse: true,
                scrollX: true,
                scrollY: 300,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    {
                        targets: 0, // First column
                        className: 'text-dark text-start' // Add your class name here
                    },
                    {
                        targets: 1, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                    {
                        targets: 2, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                    {
                        targets: 3, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                    {
                        targets: 4, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                ]
            });
            $('#validateItemTable').DataTable({
                order: [[1, 'asc']],
                paging: true,
                scrollCollapse: true,
                scrollX: true,
                scrollY: 300,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    {
                        targets: 0, // First column
                        className: 'text-dark text-start' // Add your class name here
                    },
                    {
                        targets: 1, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                    {
                        targets: 2, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                    {
                        targets: 3, // Second column
                        className: 'text-dark text-center' // Add another class name if needed
                    },
                    {
                        targets: 4, // Second column
                        className: 'text-dark text-center' // Add another class name if needed
                    },
                    {
                        targets: 5, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                    {
                        targets: 6, // Second column
                        className: 'text-dark text-start' // Add another class name if needed
                    },
                ]
            });
// Handle barcode scanning
let scannedQuantities = {};

$('#barcodeInput').on('input', function() {
    let barcode = $(this).val().trim();
    let found = false;

    console.log("Scanning barcode: ", barcode); // Debug log

    if (barcode !== '') {
        // Get the DataTable instance
        let table = $('#validateItemTable').DataTable();

        $('#validateItemTable tbody tr').each(function() {
            // Get the data for the current row
            let rowData = table.row($(this)).data();
            let productName = rowData[0]; // Product Name
            let rowBarcode = rowData[2]; // Barcode
            let requiredQty = parseInt($(this).find('.status').data('required-qty'));
            let currentQty = parseInt($(this).find('.current-qty').text());

            console.log("Checking row with barcode: ", rowBarcode); // Debug log

            if (rowBarcode === barcode) {
                found = true;
                console.log("Found matching barcode: ", rowBarcode); // Debug log

                if (!scannedQuantities[barcode]) {
                    scannedQuantities[barcode] = 0;
                }

                if (scannedQuantities[barcode] < requiredQty) {
                    scannedQuantities[barcode]++;
                    toastr.clear();
                    toastr.success("Product Name: " + productName + "<br>Barcode: " + barcode + "<br>QTY: " + scannedQuantities[barcode]);
                    console.log("Updated scanned quantity for " + barcode + ": " + scannedQuantities[barcode]); // Debug log
                    
                    // Update current quantity
                    $(this).find('.current-qty').text(scannedQuantities[barcode]);

                    if (scannedQuantities[barcode] >= requiredQty) {
                        $(this).find('.status').html('<span class="current-qty">' + scannedQuantities[barcode] + '</span>/' + requiredQty + ' <i class="fas fa-check text-success"></i>');
                    } else {
                        $(this).find('.status').html('<span class="current-qty">' + scannedQuantities[barcode] + '</span>/' + requiredQty + ' <i class="fas fa-times text-danger"></i>');
                    }
                } else {
                    toastr.clear();
                    toastr.error("Quantity already matched required quantity for " + barcode);
                }

                // Clear the input field
                $('#barcodeInput').val('');
                return false; // exit each loop
            }
        });

        if (!found) {
            $('#validationResult').html('<div class="alert alert-danger">Barcode not found in suggested items</div>');
        } else {
            $('#validationResult').html('');
        }
    }
});


$('#proceedStockOut').on('click', function() {
    let itemsToStockOut = [];
    let allMatched = true; // Flag to check if all items are matched

    $('#validateItemTable tbody tr').each(function() {
        // Get the DataTable instance
        let table = $('#validateItemTable').DataTable();
        // Get the data for the current row
        let rowData = table.row($(this)).data();

        let currentQty = parseInt($(this).find('.current-qty').text());
        let requiredQty = parseInt($(this).find('.status').data('required-qty'));

        if (currentQty < requiredQty) {
            allMatched = false; // Set flag to false if any quantity is unmatched
        } else {
            itemsToStockOut.push({
                product_name: rowData[0],
                barcode: rowData[2], // Barcode from the row data
                product_pp: rowData[3], // Barcode from the row data
                product_sp: rowData[4], // Barcode from the row data
                expiry: rowData[5], // Expiry from the row data
                qty: currentQty,
                product_sku: rowData[1] // Product SKU from the row data
            });
        }
    });

    if (allMatched) {
        // Proceed with stock out logic, e.g., sending data to the server
        let stockoutData = {
            stockout_number: $("#stockOutNumber").val(),
            items: itemsToStockOut
        };
        console.log(stockoutData);

        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: {
                data: JSON.stringify(stockoutData),
                action: "stockOutItems"
            },
            dataType: "json",
            success: function(response) {
                if (response.success == true) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    toastr.error(response.message);
                }
            }
        });

        toastr.success("All items validated. Proceeding with stock out.");
    } else {
        toastr.error("Some items do not match the required quantities. Please check the validation.");
    }
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