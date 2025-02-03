<?php
  $brands = getBrand($pdo);
  $categorys = getCategory($pdo);
  $units = getUnits($pdo);
  $taxs = getTaxs($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product')){?>
<div class="body-wrapper-inner">
        <div class="container-fluid" style="max-width: 100% !important;">
              <div class="card shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                  <div class="row">
                    <div class="col">
                    <h5 class="mt-1 mb-0">Manage Product</h5>
                    </div>
                  <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_product')){?>
                  <div class="col">
                  <button class="btn btn-primary btn-sm float-end" id="addProductBTN" data-bs-toggle="modal" data-bs-target="#productModal"><i class="fa-solid fa-plus"></i>Add Product</button>
                  <button class="btn btn-primary btn-sm float-end mx-2" id="adjustBtn" data-bs-toggle="modal" data-bs-target="#adjustModal"><i class="fa-solid fa-plus"></i>Adjust Qty</button>
                  </div>
                  <?php } ?>
                  </div>
                </div>
                <div class="card-body">
                <table id="productTable" class="table table-hover table-cs-color">
                </table>
                </div>
        </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="productForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="productModalLabel">Product Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="sku_id" class="form-label">SKU</label>
            <input type="text" class="form-control bg-secondary-subtle" id="sku_id" name="sku_id" value="" readonly>
          </div>
          <div class="col-lg-12">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Ex. Food">
          </div>
          <div class="col-lg-12">
            <label for="product_desc" class="form-label">Product Description</label>
            <textarea type="text" class="form-control" id="product_desc" name="product_desc" placeholder="Ex. Food"></textarea>
          </div>
          <div class="col-lg-6">
            <label for="brand_id" class="form-label">Brand</label>
            <select class="selectpicker form-control" id="brand_id" name="brand_id" data-live-search="true">
              <?php foreach ($brands as $brand):?>
                <option value="<?php echo $brand['brand_id'];?>"><?php echo $brand['brand_name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-lg-6">
            <label for="category_id" class="form-label">Category</label>
            <select class="selectpicker form-control" id="category_id" name="category_id" data-live-search="true">
              <?php foreach ($categorys as $category):?>
                <option value="<?php echo $category['category_id'];?>"><?php echo $category['category_name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-lg-6">
            <label for="purchase_price" class="form-label">Purchase Price</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text rounded-0 ">₱</div>
              </div>
              <input type="text" class="form-control" id="purchase_price" name="purchase_price" placeholder="5" pattern="[0-9]*">
            </div>
          </div>
          <div class="col-lg-6">
            <label for="tax_id" class="form-label">Taxs</label>
            <select class="selectpicker form-control" id="tax_id" name="tax_id" data-live-search="true">
              <?php foreach ($taxs as $tax):?>
                <option value="<?php echo $tax['tax_id'];?>"><?php echo $tax['tax_name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-lg-6">
            <label for="min_qty" class="form-label">Min Quantity</label>
            <input type="text" class="form-control" id="min_qty" name="min_qty" placeholder="Ex. 20" pattern="[0-9]*">
          </div>
          <div class="col-lg-6">
            <label for="max_qty" class="form-label">Max Quantity</label>
            <input type="text" class="form-control" id="max_qty" name="max_qty" placeholder="Ex. 50" pattern="[0-9]*">
          </div>
          <div class="col-lg-6">
            <label for="unit_id" class="form-label">Units</label>
            <select class="selectpicker form-control" id="unit_id" name="unit_id" data-live-search="true">
              <?php foreach ($units as $unit):?>
                <option value="<?php echo $unit['unit_id'];?>"><?php echo $unit['short_name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-lg-6">
            <label for="exp_notice" class="form-label">Expiry Notice (Days)</label>
            <input type="text" class="form-control" id="exp_notice" name="exp_notice" placeholder="Ex. 1" pattern="[0-9]*">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="updateProduct" update-id="">UPDATE</button>
        <button type="button" class="btn btn-primary" id="addProduct">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->
<!-- MODAL START -->
<div class="modal fade" id="adjustModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="adjustModal">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bolder" id="adjustModal">Inventory Adjustment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
      <button type="button" class="btn btn-primary" id="submitForm">Save & Close</button>
      </div>
    </div>
  </div>
</div>
<!-- END -->
<script>
$(document).ready( function () {

  $('#purchase_price, #min_qty,#max_qty,#exp_notice ').on('input', function(){
      $(this).val($(this).val().replace(/\D/g,''));
  });

  // Function to handle the AJAX request
  function fetchSKU(category_id) {
    $.ajax({
      url: 'admin/process/admin_action.php',
      method: 'POST',
      data: { category_id: category_id, action: 'getSKUID' },
      success: function (response) {
        $('#sku_id').val(response);
      },
      error: function (xhr, status, error) {
        alert(error);
      }
    });
  }

  // Trigger on load
  var initialCategoryId = $('#category_id').val();
  if (initialCategoryId) {
    fetchSKU(initialCategoryId);
  }

  // Trigger on change
  $('#category_id').change(function () {
    var category_id = $(this).val();
    fetchSKU(category_id);
  });
  var table = $('#productTable').DataTable({
        order: [[1, 'desc']],
        paging: true,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 450,
        responsive: true,
        pageLength: 20, // Default number of entries per page
        autoWidth: false,
        ajax:{
          url: 'admin/process/table.php?table_type=product',
          dataSrc: 'data'
        },
        columns:[
          {data: 'product_id', visible: false},
          {data: 'product_name', title: 'Product Name'},
          {data: 'product_description', visible: false},
          {data: 'brand_id', visible: false},
          {data: 'brand_name', visible: false},
          {data: 'category_id', visible: false},
          {data: 'category', title: 'Category'},
          {data: 'product_sku', title: 'SKU'},
          {data: 'product_pp', title: 'Purchase Price', className: 'text-center'},
          {data: 'tax_id', visible: false},
          { 
                "data": "product_sp",
                "render": function(data, type, row, meta) {
                  if (data === null || data === 0 || data <= 0) {
                      return '<span class="badge bg-danger">Pending Costing</span>';
                  } else {
                      return data;
                  }
                },
                "title": "Selling Price",
                "className": "text-center"
          },
          { 
                "data": "status_id",
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
          {data: 'product_min', title: 'Min', className: 'text-center'},
          {data: 'product_max', title: 'Max', className: 'text-center'},
          { 
                "data": "stocks",
                "render": function(data, type, row, meta) {
                    return '<span class="badge bg-secondary">' + data + '</span>';
                },
                "title": "Stocks",
                "className": "text-center"
          },
          {data: 'unit_id', visible: false},
          {data: 'unit', title: 'Unit'},
          {data: 'expiry_notice', visible: false}
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'show_product') || userHasPermission($pdo, $_SESSION["user_id"], 'update_product') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_product')){?>
          ,{ 
            "data": null, 
            "title": "Action",
            "className" : "text-center noExport",
            "render": function(data, type, row) {
                return '<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'show_product')){?><a class="btn btn-info btn-sm" href="index.php?route=view-product&product=' + row.product_sku + '"><i class="fa-solid fa-eye"></i></a>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_product')){?><button class="btn btn-primary btn-sm btn-edit"><i class="fa-regular fa-pen-to-square"></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_product')){?><button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button><?php } ?>';
            } 
          }
          <?php } ?>
        ],
        dom: 'Bfrtip',
        buttons: [
        {
            extend: 'copy',
			title: 'EC-Inventory Product List',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'csv',
			title: 'EC-Inventory Product List',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'excel',
			title: 'EC-Inventory Product List',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'pdf',
			title: 'EC-Inventory Product List',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'print',
			title: 'EC-Inventory Product List',
            exportOptions: {
            columns: ":visible:not(.noExport)"
            }
        }
    ]
    });
    function LoadTable(){
        $.ajax({
            url: 'admin/process/table.php?table_type=product',
            dataType: 'json',
            success: function(data) {
              table.clear().rows.add(data.data).draw(false); // Update data without redrawing
            
              // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
              setTimeout(function() {
                  table.ajax.reload(null, false); // Reload the DataTable without resetting current page
              }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve products.');
            }
        });
    }
    setInterval(LoadTable, 15000);
    $('#addProductBTN').click(function(){
      var initialCategoryId = $('#category_id').val();
      if (initialCategoryId) {
        fetchSKU(initialCategoryId);
      }
      $('#sku_id').val('');
      $('#product_name').val('');
      $('#product_desc').val('');
      $('#purchase_price').val('');
      $('#min_qty').val('');
      $('#max_qty').val('');
      $('#exp_notice').val('');
      $('#addProduct').show();
      $('#brand_id, #category_id, #tax_id, #unit_id').each(function() {
        $(this).val($(this).find('option:first').val()).selectpicker('refresh');
      });
      $('#updateProduct').hide();
    });
    $('#addProduct').click(function(){
        var formData = $('#productForm').serialize();
        //alert(formData);
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=addProduct",
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#productModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#updateProduct').click(function(){
      var formData = $('#productForm').serialize();
      var update_id = $(this).attr("update-id");
      $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=updateProduct&update_id="+update_id,
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#productModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#productTable').on('click', 'button.btn-edit', function () {
      var data = table.row($(this).parents('tr')).data();
      // // Populate modal with data
      
      $('#sku_id').val(data.product_sku);
      $('#product_name').val(data.product_name);
      $('#product_desc').val(data.product_description);
      $('#brand_id').val(data.brand_id);
      $('#brand_id').selectpicker('refresh');
      $('#purchase_price').val(data.product_pp);
      $('#category_id').val(data.category_id);
      $('#category_id').selectpicker('refresh');
      $('#tax_id').val(data.tax_id);
      $('#tax_id').selectpicker('refresh');
      $('#min_qty').val(data.product_min);
      $('#max_qty').val(data.product_max);
      $('#exp_notice').val(data.expiry_notice);
      $('#unit_id').val(data.unit_id);
      $('#unit_id').selectpicker('refresh');

      $('#addProduct').hide();
      $('#updateProduct').show();
      $('#productModal').modal('show');
      // var update_id = $(this).attr("update-id");
      $("#updateProduct").attr("update-id", data.product_id);
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
}
?>