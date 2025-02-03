<?php
$products = getProduct($pdo);
$last_series_number = getStockInNumber($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_stockin')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm w-50">
          <div class="card-header bg-transparent border-bottom">
            <div class="row align-items-center">
              <div class="col">
                <h5 class="mt-1 mb-0">Stock In Number</h5>
              </div>
              <div class="col">
                <input type="text" class="form-control bg-secondary-subtle" id="stockInNumber" value="<?php echo $last_series_number;?>" readonly>
              </div>
            </div>
          </div>
        </div>
  <div id="productCards">
      <div class="card shadow-sm product-card">
        <div class="card-header bg-transparent border-bottom">
          <div class="row">
            <div class="col">
              <h5 class="mt-1 mb-0">Product</h5>
            </div>
            <div class="col align-content-end ">
            <button class="btn btn-danger btn-sm remove-product-btn float-end "><i class="fa-solid fa-trash"></i> Delete Product</button>
            </div>
          </div>
        </div>
        <div class="card-body">
        <div class="row gy-3">
          <div class="col-lg-6">
            <label for="product_select" class="form-label">Select Product</label>
            <select class="selectpicker form-control" data-live-search="true">
              <option value="" selected disabled>None</option>
              <?php foreach ($products as $product):?>
                <option value="<?php echo $product['product_sku'];?>" data-tokens="<?php echo $product['product_name'];?>"><?php echo $product['product_name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          <div class="col-lg-6">
              <label for="product_name" class="form-label">SKU</label>
              <input type="text" class="form-control bg-secondary-subtle" name="product_name[]" placeholder="" readonly>
          </div>
          <div class="row barcode-qty-expiry m-0 p-0">

          </div>
          <div class="col-lg-12">
          <button class="btn btn-primary btn-sm add-item-btn"><i class="fa-solid fa-plus"></i>Add Item</button>
          </div>
        </div>      
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <button class="btn btn-primary" id="addStockBTN"><i class="fa-solid fa-plus"></i>New Product</button>
      <button class="btn btn-primary" id="saveDataBtn"><i class="fa-solid fa-plus"></i>Save</button>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Initialize selectpicker
    $('.selectpicker').selectpicker();

    // Function to update selected products
    function updateSelectedProducts() {
        var selectedProductSKUs = [];
        console.log( selectedProductSKUs);
        // Gather all selected product SKUs
        $('.product-card').each(function() {
            var selectedSKU = $(this).find('.selectpicker').val();
            if (selectedSKU !== null && selectedSKU !== '') {
                selectedProductSKUs.push(selectedSKU);
            }
        });

        // Disable selected options in all product select pickers
        $('.product-card').each(function() {
            var currentCard = $(this);
            var currentSelect = currentCard.find('.selectpicker');
            var currentSelectValue = currentSelect.val();

            currentCard.find('.selectpicker option').each(function() {
                var optionSKU = $(this).val();

                if (selectedProductSKUs.includes(optionSKU) && optionSKU !== currentSelectValue) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            });

            // Refresh the selectpicker to reflect the changes
            currentSelect.selectpicker('refresh');
        });
    }

    // Initial call to updateSelectedProducts
    updateSelectedProducts();

    // Event listener for change in selectpicker
    $(document).on('change', '.selectpicker', function() {
        // Update the SKU field with selected product SKU
        var product_sku = $(this).val();
        $(this).closest('.product-card').find('input[name="product_name[]"]').val(product_sku);

    });

    // Add item button click event
    $(document).on('click', '.add-item-btn', function() {
        var barcodeQtyExpiry =
            '<div class="input-group appended-item mt-2">' +
            '<input type="text" class="form-control" name="barcode[]" placeholder="Scan Barcode" required>' +
            '<input type="number" class="form-control" name="qty[]" placeholder="Quantity" required>' +
            '<input type="date" class="form-control" name="expiry[]" placeholder="Expiry Date" required>' +
            '<button type="button" class="btn btn-danger remove-item-btn"><i class="fa-solid fa-trash"></i></button>' +
            '</div>';
        $(this).closest('.card-body').find('.barcode-qty-expiry').append(barcodeQtyExpiry);
    });

    // Remove item button click event
    $(document).on('click', '.remove-item-btn', function() {
        $(this).closest('.appended-item').remove();
    });

    // Remove product button click event
    $(document).on('click', '.remove-product-btn', function() {
        var productCard = $(this).closest('.product-card');
        if ($('.product-card').length > 1) {
            productCard.remove();
            // Update disabled options in select pickers after removing a product card
            updateSelectedProducts();
        } else {
            toastr.error("Can't remove the last form!");
        }
    });

    // Add stock button click event
    $('#addStockBTN').click(function() {
        var productCard = '<div class="card shadow-sm product-card">' +
            '<div class="card-header bg-transparent border-bottom">' +
            '<div class="row">' +
            '<div class="col">' +
            '<h5 class="mt-1 mb-0">Product</h5>' +
            '</div>' +
            '<div class="col align-content-end ">' +
            '<button class="btn btn-danger btn-sm remove-product-btn float-end "><i class="fa-solid fa-trash"></i> Delete Product</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="card-body">' +
            '<div class="row gy-3">' +
            '<div class="col-lg-6">' +
            '<label for="product_select" class="form-label">Select Product</label>' +
            '<select class="selectpicker form-control" data-live-search="true">' +
            '<option value="" selected disabled>None</option>' +
            '<?php foreach ($products as $product):?>' +
            '<option value="<?php echo $product['product_sku'];?>" data-tokens="<?php echo $product['product_name'];?>"><?php echo $product['product_name'];?></option>' +
            '<?php endforeach;?>' +
            '</select>' +
            '</div>' +
            '<div class="col-lg-6">' +
            '<label for="product_name" class="form-label">SKU</label>' +
            '<input type="text" class="form-control bg-secondary-subtle" name="product_name[]" placeholder="" readonly>' +
            '</div>' +
            '<div class="row barcode-qty-expiry gy-3">' +
            '</div>' +
            '<div class="col-lg-12">' +
            '<button class="btn btn-primary btn-sm add-item-btn"><i class="fa-solid fa-plus"></i>Add Item</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
        $('#productCards').append(productCard);

        // Initialize selectpicker for the newly appended product card
        $('#productCards').find('.selectpicker').last().selectpicker();
        updateSelectedProducts(); // Update disabled options
    });

    // Save data button click event
    $('#saveDataBtn').click(function() {
        // Collect data from appended product cards
        var productsData = {
            "stockin_number": $("#stockInNumber").val(),
            "items": []
        };

        $('.product-card').each(function() {
            var productData = {
                "product_sku": $(this).find('.selectpicker').val(),
                "items": []
            };
            $(this).find('.appended-item').each(function() {
                var itemData = {
                    "series_number": $("#stockInNumber").val(), // Assign series_number to each item
                    "barcode": $(this).find('input[name="barcode[]"]').val(),
                    "qty": $(this).find('input[name="qty[]"]').val(),
                    "expiry": $(this).find('input[name="expiry[]"]').val()
                };
                productData.items.push(itemData);
            });
            productsData.items.push(productData);
        });

        console.log(productsData);

        // Perform AJAX request to save data
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: {
                data: JSON.stringify(productsData),
                action: "stockInItems"
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