<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Product</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_product')){ ?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addProductBTN" data-bs-toggle="modal" data-bs-target="#productModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Product</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="ProductTable" class="table table-hover table-cs-color"></table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="productForm" enctype="multipart/form-data">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="productModalLabel">Product Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Ex. Takoyaki">
          </div>
          <div class="col-lg-12">
            <label for="sell_price" class="form-label">Selling Price</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <div class="input-group-text rounded-0">₱</div>
              </div>
              <input type="text" class="form-control" id="sell_price" name="sell_price" placeholder="5" pattern="[0-9]*">
            </div>
          </div>

          <!-- Discount Dropdown -->
          <div class="col-lg-12">
            <label for="product_discount" class="form-label">Discount</label>
            <select class="selectpicker form-control" id="product_discount" name="product_discount" data-live-search="true">
              <!-- Populate discounts dynamically -->
              <?php 
              $discounts = getDiscounts($pdo); // Function to fetch discounts from the database
              foreach ($discounts as $discount) {
                echo "<option value='{$discount['discount_percentage']}'>{$discount['discount_percentage']}%</option>";
              }
              ?>
            </select>
          </div>

          <!-- Category Dropdown -->
          <div class="col-lg-12">
            <label for="product_category" class="form-label">Category</label>
            <select class="selectpicker form-control" id="product_category" name="product_category" data-live-search="true">
              <!-- Populate categories dynamically -->
              <?php 
              $categories = getCategories($pdo); // Function to fetch categories from the database
              foreach ($categories as $category) {
                echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
              }
              ?>
            </select>
          </div>

          <!-- Image Upload Section -->
          <div class="col-lg-12">
            <label for="product_image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
            <div id="imagePreviewWrapper" class="mt-2" style="display:none;">
              <img id="imagePreview" src="" alt="Image Preview" class="img-fluid" style="max-height: 200px; margin-bottom: 10px;">
              <button type="button" id="removeImage" class="btn btn-danger btn-sm">Remove Image</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="updateProduct" update-id="">UPDATE</button>
        <button type="button" class="btn btn-primary" id="addProduct">ADD</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- END -->
<script>
$(document).ready(function() {
  $('#sell_price').on('input', function() {
    var value = $(this).val();

    // Remove all non-numeric characters except for the decimal point
    value = value.replace(/[^0-9.]/g, '');

    // Ensure there is only one decimal point in the value
    var decimalCount = (value.match(/\./g) || []).length;
    if (decimalCount > 1) {
        // If more than one decimal point exists, remove the last one
        value = value.slice(0, value.lastIndexOf('.')) + value.slice(value.lastIndexOf('.') + 1);
    }

    // Ensure only two decimal places if a decimal point exists
    var decimalIndex = value.indexOf('.');
    if (decimalIndex !== -1) {
        var beforeDecimal = value.slice(0, decimalIndex);
        var afterDecimal = value.slice(decimalIndex + 1, decimalIndex + 3); // Take only 2 digits after the decimal
        value = beforeDecimal + '.' + afterDecimal;
    }

    $(this).val(value);
});

  $('#addProductBTN').click(function(){
    $('#addProduct').show();
    $('#updateProduct').hide();
    $('#imagePreviewWrapper').hide(); // Hide the image preview on modal open

    // Clear all inputs in the form
    $('#productForm')[0].reset(); // This will reset all the form fields

    // Reset the selectpicker for discount and category
    $('#product_discount').selectpicker('val', '');
    $('#product_category').selectpicker('val', '');
    
    // Hide image preview and reset image input
    $('#product_image').val('');
    $('#imagePreviewWrapper').hide();
  });

  var table = $('#ProductTable').DataTable({
    responsive: true,
    select: true,
    autoWidth: false,
    ajax:{
      url: 'admin/process/table.php?table_type=product',
      dataSrc: 'data'
    },
    columns:[
      {data: 'product_id', visible: false},
      {data: 'product_name', title: 'Product Name', className: 'text-start'},
      {data: 'product_price', title: 'Product Price', className: 'text-start'},
      {data: 'product_discount', title: 'Discount', className: 'text-start'},
      {data: 'category_id', visible: false},
      {data: 'category_name', title: 'Category'}
      <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_product') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_product')){?>
      ,{
        data: null,
        className: "text-center",
        title: 'Action',
        defaultContent: `
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_product')){ ?>
          <button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;
          <?php } ?>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_product')){ ?>
          <button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button>
          <?php } ?>
        `
      }
      <?php } ?>
    ]
  });

  var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

  function LoadTable(){
    $.ajax({
      url: 'admin/process/table.php?table_type=product',
      dataType: 'json',
      success: function(data) {
        table.clear().rows.add(data.data).draw(false);
        setTimeout(function() {
          table.ajax.reload(null, false);
        }, 1000);
      },
      error: function () {
        alert('Failed to retrieve products.');
      }
    });
  }

  // Handle Edit Button
  $('#ProductTable').on('click', '.btn-edit', function(){
    var rowData = table.row($(this).parents('tr')).data();
    $('#product_name').val(rowData.product_name);
    $('#sell_price').val(rowData.product_price);
    $('#product_discount').val(rowData.product_discount).selectpicker('refresh');  // Update the discount selectpicker
    $('#product_category').val(rowData.category_id).selectpicker('refresh');  // Update the category selectpicker
    $('#updateProduct').show();
    $('#addProduct').hide();
    $('#productModal').modal('show');
    $('#updateProduct').attr('update-id', rowData.product_id);

    // Show image preview if product has image
    if (rowData.product_image) {
      $('#imagePreview').attr('src', rowData.product_image);
      $('#imagePreviewWrapper').show();
    } else {
      $('#imagePreviewWrapper').hide();
    }
  });

  // Handle Delete Button
  $('#ProductTable').on('click', '.btn-delete', function(){
    var rowData = table.row($(this).parents('tr')).data();
    swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'admin/process/admin_action.php',
          method: 'POST',
          data: { action: 'deleteProduct', product_id: rowData.product_id, csrf_token: csrfToken },
          dataType: 'json',
          success: function(response) {
            if (response.success) {
              LoadTable();
              swal.fire('Deleted!', response.message, 'success');
            } else {
              swal.fire('Error', response.message, 'error');
            }
          }
        });
      }
    });
  });

  // Image Preview
  $('#product_image').change(function(e) {
    var reader = new FileReader();
    reader.onload = function(event) {
      $('#imagePreview').attr('src', event.target.result);
      $('#imagePreviewWrapper').show(); // Show preview
    };
    reader.readAsDataURL(this.files[0]);
  });

  // Remove Image
  $('#removeImage').click(function() {
    $('#product_image').val(''); // Clear the file input
    $('#imagePreviewWrapper').hide(); // Hide the image preview
  });

  // Add New Product
  $('#addProduct').click(function() {
    var formData = new FormData($('#productForm')[0]);
    formData.append('action', 'addProduct');
    formData.append('csrf_token', csrfToken);

    $.ajax({
      url: 'admin/process/admin_action.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          LoadTable();
          $('#productModal').modal('hide');
          swal.fire('Success', response.message, 'success');
        } else {
          swal.fire('Error', response.message, 'error');
        }
      }
    });
  });

  // Update Existing Product
  $('#updateProduct').click(function() {
    var formData = new FormData($('#productForm')[0]);
    formData.append('action', 'updateProduct');
    formData.append('update_id', $(this).attr("update-id"));
    formData.append('csrf_token', csrfToken);

    $.ajax({
      url: 'admin/process/admin_action.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          LoadTable();
          $('#productModal').modal('hide');
          swal.fire('Success', response.message, 'success');
        } else {
          swal.fire('Error', response.message, 'error');
        }
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
