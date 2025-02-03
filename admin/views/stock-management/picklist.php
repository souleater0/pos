// picklist.php
<?php
$products = getProduct($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_stockout')){?>
<div class="body-wrapper-inner">
<div class="container-fluid" style="max-width: 100% !important;">
    <div class="col-lg-6">
        <div class="d-flex">
            <div class="d-flex justify-content-center align-items-center">
                <a href="index.php?route=stock-out" class="text-uppercase text-primary fw-bold" aria-current="page">Pick List</a>
            </div>
            <div class="d-flex justify-content-center align-items-center px-2">
                /
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <a href="index.php?route=validate-stockout" class="text-uppercase">Stock Out</a>
            </div>
        </div>
    </div>
</div>

  <div class="container-fluid">
      <div class="card shadow-sm">
        <div class="card-header bg-transparent border-bottom">
          <div class="row">
            <div class="col">
              <h5 class="mt-1 mb-0">Choose Pick List</h5>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div id="productCards">
            <div class="row gy-3 product-card">
              <div class="col-lg-4">
                <label for="product_select" class="form-label">Select Product</label>
                <select class="selectpicker form-control" data-live-search="true">
                  <option value="" selected disabled>None</option>
                  <?php foreach ($products as $product):?>
                    <option value="<?php echo $product['product_sku'];?>" data-tokens="<?php echo $product['product_name'];?>"><?php echo $product['product_name'];?></option>
                  <?php endforeach;?>
                </select>
              </div>
              <div class="col-lg-4">
                <label for="product_name" class="form-label">SKU</label>
                <input type="text" class="form-control bg-secondary-subtle" name="product_name[]" placeholder="" readonly>
              </div>
              <div class="col-lg-3">
                <label for="qty" class="form-label">Quantity</label>
                <div class="input-group">
                  <input type="number" class="form-control" name="qty[]" autocomplete="false">
                  <span class="input-group-text" id="basic-addon2">Qty</span>
                </div>
              </div>
              <div class="col align-content-end">
                <button class="btn btn-danger btn-lg remove-product-btn float-end"><i class="fa-solid fa-trash"></i></button>
              </div>
            </div>
          </div>      
        </div>
      </div>
      <div class="col-lg-12">
        <button class="btn btn-primary" id="addStockBTN"><i class="fa-solid fa-plus"></i>New Product</button>
        <button class="btn btn-primary" id="saveDataBtn"><i class="fa-solid fa-plus"></i>Proceed</button>
      </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();

        function updateSelectedProducts() {
            var selectedProductSKUs = [];

            // Gather all selected product SKUs
            $('.product-card').each(function() {
                var selectedSKU = $(this).find('.selectpicker').val();
                if (selectedSKU !== null && selectedSKU !== '') {
                    selectedProductSKUs.push(selectedSKU);
                }
            });

            // Disable selected options in all product select pickers
            $('.selectpicker').each(function() {
                var currentSelect = $(this);
                var currentSelectValue = currentSelect.val();

                currentSelect.find('option').each(function() {
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

        $(document).on('input', 'input[type="number"]', function() {
            var value = $(this).val();
            value = value.replace(/[^0-9]/g, '');
            var intValue = parseInt(value);

            if (isNaN(intValue) || intValue < 1) {
                $(this).val('1');
            } else {
                $(this).val(intValue);
            }
        });

        $(document).on('change', '.selectpicker', function () {
            var product_sku = $(this).val();
            $(this).closest('.row').find('input[name="product_name[]"]').val(product_sku);
            updateSelectedProducts();
        });

        $(document).on('click', '.remove-product-btn', function() {
            var productCard = $(this).closest('.product-card');
            if ($('.product-card').length > 1) {
                productCard.remove();
                updateSelectedProducts();
            } else {
                toastr.error("Can't remove last form!");
            }
        });

        $('#addStockBTN').click(function() {
            var productCard = `
                <div class="row gy-3 product-card">
                    <div class="col-lg-4">
                        <label for="product_select" class="form-label">Select Product</label>
                        <select class="selectpicker form-control" data-live-search="true">
                            <option value="" selected disabled>None</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['product_sku']; ?>" data-tokens="<?php echo $product['product_name']; ?>"><?php echo $product['product_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label for="product_name" class="form-label">SKU</label>
                        <input type="text" class="form-control bg-secondary-subtle" name="product_name[]" placeholder="" readonly>
                    </div>
                    <div class="col-lg-3">
                        <label for="qty" class="form-label">Quantity</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="qty[]" autocomplete="false">
                            <span class="input-group-text" id="basic-addon2">Qty</span>
                        </div>
                    </div>
                    <div class="col align-content-end">
                        <button class="btn btn-danger btn-lg remove-product-btn float-end"><i class="fa-solid fa-trash"></i></button>
                    </div>
                </div>`;
                
            $('#productCards').append(productCard);
            $('#productCards').find('.selectpicker').last().selectpicker();
            updateSelectedProducts();
        });

        $('#saveDataBtn').click(function() {
            var productsData = [];
            $('.product-card').each(function() {
                var productData = {};
                productData.product_name = $(this).find("option:selected").text();
                productData.product_sku = $(this).find('select').val();
                productData.product_qty = $(this).find('input[name="qty[]"]').val();
                productsData.push(productData);
            });
            console.log(productsData);

            $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: {
                data: JSON.stringify(productsData),
                action: "sendpickList"
            },
            dataType: "json",
            success: function(response) {
                if (response.success == true) {
                  window.location.href = 'index.php?route=validate-stockout';
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