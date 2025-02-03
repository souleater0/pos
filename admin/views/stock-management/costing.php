<?php
  $brands = getBrand($pdo);
  $categorys = getCategory($pdo);
  $units = getUnits($pdo);
  $taxs = getTaxs($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_costing')){?>
<div class="body-wrapper-inner">
        <div class="container-fluid" style="max-width: 100% !important;">
              <div class="card shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                  <div class="row">
                    <div class="col">
                    <h5 class="mt-1 mb-0">Manage Costing</h5>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                <table id="costingTable" class="table table-hover table-cs-color">
                </table>
                </div>
        </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="costForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="productModalLabel">Cost Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-6">
            <label for="selling_price" class="form-label">Selling Price</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text rounded-0 ">₱</div>
              </div>
              <input type="text" class="form-control" id="selling_price" name="selling_price" placeholder="5" pattern="[0-9]*">
            </div>
          </div>
          <div class="col-lg-6">
            <label for="tax_id" class="form-label">Taxs</label>
            <select class="selectpicker form-control" id="tax_id" name="tax_id" data-live-search="true">
            <option value="" disabled>None</option>
              <?php foreach ($taxs as $tax):?>
                <option value="<?php echo $tax['tax_id'];?>"><?php echo $tax['tax_name'];?></option>
              <?php endforeach;?>
            </select>
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
<script>
$(document).ready( function () {
  $('#selling_price').on('input', function(){
      $(this).val($(this).val().replace(/\D/g,''));
  });
  $('#costForm').on('submit', function(event){
      event.preventDefault();
  });
  var table = $('#costingTable').DataTable({
        order: [[4, 'asc']],
        paging: true,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 300,
        responsive: true,
        autoWidth: false,
        ajax:{
          url: 'admin/process/table.php?table_type=costing',
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
          {data: 'product_pp', title: 'Purchase Price',className: 'text-center'},
          {data: 'tax_id', visible: false},
          {
              "data": "product_sp",
              "render": function(data, type, row, meta) {
                  if (data === null || parseFloat(data) <= 0) {
                      return '<span class="badge bg-danger">Need Costing</span>';
                  } else {
                      return data;
                  }
              },
              "title": "Selling Price",
              "className": "text-center"
          },
          {data: 'tax_name', title: 'Tax'},
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
                visible: false
            },
          {data: 'product_min', title: 'Min', className: 'text-center', visible: false},
          {data: 'product_max', title: 'Max', className: 'text-center', visible: false},
          { 
                "data": "stocks",
                "render": function(data, type, row, meta) {
                    return '<span class="badge bg-secondary">' + data + '</span>';
                },
                "title": "Stocks",
                "className": "text-center"
          },
          {data: 'unit_id', visible: false},
          {data: 'unit', title: 'Unit'}
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_costing')){?>
          ,{ 
            "data": null, 
            "title": "Action",
            "className": "text-center noExport",
            "render": function(data, type, row) {
                return '<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_costing')){ ?><button class="btn btn-primary btn-sm btn-edit"><i class="fa-regular fa-pen-to-square"></i></button>&nbsp;<?php } ?>';
            } 
          }
          <?php } ?>
        ],
		        dom: 'Bfrtip',
        buttons: [
        {
            extend: 'copy',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'csv',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'excel',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'pdf',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'print',
            exportOptions: {
            columns: ":visible:not(.noExport)"
            }
        }
    ]
    });
    function LoadTable(){
        $.ajax({
            url: 'admin/process/table.php?table_type=costing',
            dataType: 'json',
            success: function(data) {
              table.clear().rows.add(data.data).draw(false); // Update data without redrawing
            
              // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
              setTimeout(function() {
                  table.ajax.reload(null, false); // Reload the DataTable without resetting current page
              }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve categories.');
            }
        });
    }
    setInterval(LoadTable, 15000);
    $('#addProductBTN').click(function(){
      $('#sku_id').val('');
      $('#product_name').val('');
      $('#product_desc').val('');
      $('#brand_id').val('');
      $('#brand_id').selectpicker('refresh');
      $('#purchase_price').val('');
      $('#category_id').val('');
      $('#category_id').selectpicker('refresh');
      $('#tax_id').val('');
      $('#tax_id').selectpicker('refresh');
      $('#min_qty').val('');
      $('#max_qty').val('');
      $('#unit_id').val('');
      $('#unit_id').selectpicker('refresh');
      $('#addProduct').show();
      $('#updateProduct').hide();
    });
    $('#updateProduct').click(function(){
      var formData = $('#costForm').serialize();
      var update_id = $(this).attr("update-id");
      $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=updateCost&update_id="+update_id,
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
    $('#costingTable').on('click', 'button.btn-edit', function () {
      var data = table.row($(this).parents('tr')).data();
      // // Populate modal with data
      
      $('#selling_price').val(data.product_sp);
      $('#tax_id').val(data.tax_id);
      $('#tax_id').selectpicker('refresh');

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
}?>
