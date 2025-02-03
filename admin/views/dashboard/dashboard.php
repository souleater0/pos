<?php
 $totalProduct_Count = getCount_TotalProduct($pdo);
 $outstock_Count = getCount_OutofStock($pdo);
 $lowstock_Count = getCount_LowofStock($pdo);
 $outOfStockList = getOutofStock($pdo);
 $lowStockList = getLowofStock($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_dashboard')){?>
<div class="body-wrapper-inner"> 
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <!-- card -->
                <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center gap-6 mb-4 pb-3">
                        <span
                          class="round-48 d-flex align-items-center justify-content-center rounded bg-secondary-subtle">
                          <iconify-icon icon="mdi:cart" class="fs-6 text-primary"> </iconify-icon>
                        </span>
                        <h6 class="mb-0 fs-4 text-uppercase">total products</h6>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h4><?php echo $totalProduct_Count = !empty($totalProduct_Count) ? $totalProduct_Count : 0; ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                <!-- card-end -->
            </div>
            <div class="col-lg-4">
                <!-- card -->
                <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center gap-6 mb-4 pb-3">
                        <span
                          class="round-48 d-flex align-items-center justify-content-center rounded bg-secondary-subtle">
                          <iconify-icon icon="vaadin:stock" class="fs-6 text-primary"> </iconify-icon>
                        </span>
                        <h6 class="mb-0 fs-4 text-uppercase">low on stock</h6>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h4><?php echo $lowstock_Count = !empty($lowstock_Count) ? $lowstock_Count : 0; ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                <!-- card-end -->
            </div>
            <div class="col-lg-4">
                <!-- card -->
                <div class="card">
                    <div class="card-body">
                      <div class="d-flex align-items-center gap-6 mb-4 pb-3">
                        <span
                          class="round-48 d-flex align-items-center justify-content-center rounded bg-secondary-subtle">
                          <iconify-icon icon="healthicons:rdt-result-out-stock" class="fs-6 text-primary"> </iconify-icon>
                        </span>
                        <h6 class="mb-0 fs-4 text-uppercase">out of stock</h6>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h4><?php echo $outstock_Count = !empty($outstock_Count) ? $outstock_Count : 0; ?></h4>
                        </div>
                      </div>
                    </div>
                  </div>
                <!-- card-end -->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 d-flex align-items-stretch">
              <div class="card w-100">
                <div class="card-body p-4">
                  <h5 class="card-title fw-semibold mb-4">Expiring Soon</h5>
                  <div class="table-responsive" data-simplebar>
                  <table id="productExpiringTable" class="table table-hover table-cs-color">
                  </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-stretch">
              <div class="card w-100">
              <div class="card-body p-4">
                  <h5 class="card-title fw-semibold mb-4">Low of Stock</h5>
                  <div class="table-responsive" data-simplebar>
                    <table class="table text-nowrap align-middle table-custom mb-0" id="lowstockTable" class="display" style="width:100%">
                      <thead>
                        <tr>
                          <th scope="col" class="text-dark fw-normal ps-0">Product Name
                          </th>
                          <th scope="col" class="text-dark fw-normal noExport">Status</th>
                          <th scope="col" class="text-dark fw-normal">Qty</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($lowStockList as $row): ?>
                            <tr onclick="location.href='index.php?route=view-product&amp;product=<?php echo $row['product_sku']?>'" style="cursor: pointer;">
                                <td>
                                <div class="d-flex align-items-center gap-6">
                                  <div>
                                    <h6 class="mb-0 text-primary"><?php echo htmlspecialchars($row['product_name']); ?></h6>
                                    <span><?php echo htmlspecialchars($row['category'])?></span>
                                  </div>
                                </div>
                                </td>
                                <td><span class="badge text-white" style="background-color: #FFAF61;">Low Stock</span></td>
                                <td><?php echo htmlspecialchars($row['stocks']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-stretch">
              <div class="card w-100">
                <div class="card-body p-4">
                  <h5 class="card-title fw-semibold mb-4">Out of Stock</h5>
                  <div class="table-responsive" data-simplebar>
                    <table class="table text-nowrap align-middle table-custom mb-0" id="outstockTable" class="display" style="width:100%">
                      <thead>
                        <tr>
                          <th scope="col" class="text-dark fw-normal ps-0">Product Name
                          </th>
                          <th scope="col" class="text-dark fw-normal noExport">Status</th>
                          <th scope="col" class="text-dark fw-normal">Qty</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($outOfStockList as $row): ?>
                            <tr onclick="location.href='index.php?route=view-product&amp;product=<?php echo $row['product_sku']?>'" style="cursor: pointer;">
                                <td>
                                <div class="d-flex align-items-center gap-6">
                                  <div>
                                    <h6 class="mb-0 text-primary"><?php echo htmlspecialchars($row['product_name']); ?></h6>
                                    <span><?php echo htmlspecialchars($row['category'])?></span>
                                  </div>
                                </div>
                                </td>
                                <td><span class="badge text-white" style="background-color: #EC7063;">Out of Stock</span></td>
                                <td><?php echo htmlspecialchars($row['stocks']); ?></td>
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
    </div>
</div>
<script>
  $(document).ready( function () {
    var table = $('#productExpiringTable').DataTable({
      layout: {
                topStart: {
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                }
            },
        order: [[3, 'asc']],
        paging: true,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 300,
        responsive: true,
        autoWidth: false,
        ajax:{
          url: 'admin/process/table.php?table_type=expiring_soon',
          dataSrc: 'data'
        },
        columns:[
          {data: 'product_sku', visible: false, className: 'noExport'},
          {
              data: 'item_barcode',
              title: 'Barcode',
              className: '',
              render: function(data) {
                  return data ? data : 'No Barcode';
              }
          },
          {data: 'product_name', title: 'Product Name'},
          {data: 'item_expiry', title: 'Expiry Date', className: 'text-center'},
          {data: 'expiry_notice', title: 'Expiry Notice', className: 'text-center'},
          {
                "data": "days_to_expiry",
                "render": function(data, type, row, meta) {
                  if (data <= 0) {
                      return '<span class="badge bg-danger">Expired</span>';
                  } else {
                      return '<span class="badge bg-secondary">' + data + '</span>';
                  }
                },
                "title": "Remaining Days",
                "className": "text-center"
          },
          { 
                "data": "available_qty",
                "render": function(data, type, row, meta) {
                    return '<span class="badge bg-dark">' + data + '</span>';
                },
                "title": "QTY",
                "className": "text-center"
          },
          { 
            "data": null, 
            "title": "Action",
            "className": "noExport",
            "render": function(data, type, row) {
                return '<a class="btn btn-info btn-sm" href="index.php?route=view-product&product=' + row.product_sku + '"><i class="fa-solid fa-eye"></i></a>';
            } 
          }
        ],
        dom: 'Bfrtip',
        buttons: [
        {
            extend: 'copy',
            title: 'List of Product Expiring Soon',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'csv',
            title: 'List of Product Expiring Soon',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'excel',
            title: 'List of Product Expiring Soon',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'pdf',
            title: 'List of Product Expiring Soon',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'print',
            title: 'List of Product Expiring Soon',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
        }
    ]
    });
    $('#lowstockTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
        {
            extend: 'copy',
			title: 'List of Product - Low Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'csv',
			title: 'List of Product - Low Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'excel',
			title: 'List of Product - Low Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'pdf',
			title: 'List of Product - Low Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'print',
			title: 'List of Product - Low Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
            }
        }
    ],
        order: [[2, 'asc']],
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
    $('#outstockTable').DataTable({
      dom: 'Bfrtip',
        buttons: [
        {
            extend: 'copy',
			title: 'List of Product - Out of Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'csv',
			title: 'List of Product - Out of Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'excel',
			title: 'List of Product - Out of Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'pdf',
			title: 'List of Product - Out of Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'print',
			title: 'List of Product - Out of Stock',
            exportOptions: {
            columns: ":visible:not(.noExport)"
            }
        }
    ],
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
    function getExpiringSoon(){
      $.ajax({
            url: 'admin/process/table.php?table_type=expiring_soon',
            dataType: 'json',
            success: function(data) {
              table.clear().rows.add(data.data).draw(false); // Update data without redrawing
            
              // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
              setTimeout(function() {
                  table.ajax.reload(null, false); // Reload the DataTable without resetting current page
              }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve expiring products.');
            }
        });
    }
    getExpiringSoon();
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
}
?>