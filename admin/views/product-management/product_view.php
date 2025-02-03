<?php
// Include the PHP barcode library
require 'vendor/autoload.php';

use Picqer\Barcode\BarcodeGeneratorPNG;

// $text = "";
// $generator = new BarcodeGeneratorPNG();
// $barcode_image = $generator->getBarcode($text, $generator::TYPE_CODE_128);

function generateBarcode($text){
    $generator = new BarcodeGeneratorPNG();
    $barcode_image = $generator->getBarcode($text, $generator::TYPE_CODE_128);
    return $barcode_image;
}
$product_no = $_GET['product'];
$product = getProductSummary($product_no, $pdo);
// $items = getItembyID($product_no, $pdo);
$items = getItembyID($product_no, $pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'show_product')){?>
<div class="body-wrapper-inner open-sans-regular">
    <div class="container-fluid" style="max-width: 100% !important;">
        <div class="card shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mt-1 mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-product-tab" data-bs-toggle="tab" data-bs-target="#nav-product" type="button" role="tab" aria-controls="nav-product" aria-selected="true">Product</button>
                            <button class="nav-link" id="nav-pricing-tab" data-bs-toggle="tab" data-bs-target="#nav-pricing" type="button" role="tab" aria-controls="nav-pricing" aria-selected="false">Pricing</button>
                            <button class="nav-link" id="nav-stock-tab" data-bs-toggle="tab" data-bs-target="#nav-stock" type="button" role="tab" aria-controls="nav-stock" aria-selected="false">Stock</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Description</button>
                            
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-product" role="tabpanel" aria-labelledby="nav-product-tab">
                            <!-- FIRST -->
                            <div class="mt-3">
                                <div class="row align-items-stretch gy-5">
                                    <div class="col-lg-3 col-md-6">
                                        <span class="text-dark fw-semibold ">Product Name: </span><br><span class="text-dark"><?php echo !empty($product['product_name']) ? $product['product_name'] : 'none' ?></span>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <span class="text-dark fw-semibold ">Brand: </span><br><span class="text-dark"><?php echo !empty($product['brand_name']) ? $product['brand_name'] : 'none' ?></span>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <span class="text-dark fw-semibold ">Category / Subcategory: </span><br><span class="text-dark"><?php echo !empty($product['category']) ? $product['category'] : 'none' ?></span>
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <span class="text-dark fw-semibold ">SKU: </span><br><?php echo "<img src='data:image/png;base64," . base64_encode(generateBarcode(!empty($product['product_sku']) ? $product['product_sku'] : 'none')) . "' width='180'>";?><br><span class="text-dark"><?php echo !empty($product['product_sku']) ? $product['product_sku'] : 'none' ?></span>
                                    </div>
                                </div>
                            </div>
                            <!-- FIRST -->
                        </div>
                        <div class="tab-pane fade" id="nav-pricing" role="tabpanel" aria-labelledby="nav-pricing-tab">
                            <div class="mt-3">
                                <div class="row align-items-stretch gy-5">
                                    <div class="col-lg col-md-6">
                                        <span class="text-dark fw-semibold ">Purchasing Price: </span><br><span class="text-dark"><?php echo !empty($product['product_pp']) ? '₱'.$product['product_pp'] : 'none' ?></span>
                                    </div>
                                    <div class="col-lg col-md-6">
                                        <span class="text-dark fw-semibold ">Selling Price: </span><br><span class="text-dark"><?php echo !empty($product['product_sp']) ? '₱'.$product['product_sp'] : 'none' ?></span>
                                    </div>
                                    <div class="col-lg col-md-6 col-6">
                                        <span class="text-dark fw-semibold ">Tax: </span><br><span class="text-dark"><?php echo !empty($product['tax_name']) ? $product['tax_name'] : 'none' ?></span>
                                    </div>
                                    <div class="col-lg col-md-6 col-6">
                                        <span class="text-dark fw-semibold ">Total Purchase Price: </span><br><span class="text-dark"><?php echo !empty($product['product_pp']) ? '₱'.$product['product_pp']*$product['stocks'] : 'none' ?></span>
                                    </div>
                                    <div class="col-lg col-md-6 col-6">
                                        <span class="text-dark fw-semibold ">Total Selling Price: </span><br><span class="text-dark"><?php echo !empty($product['product_sp']) ? '₱'.$product['product_sp']*$product['stocks'] : 'none' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="py-3">
                            <p class="text-dark text-break"><?php echo !empty($product['product_description']) ? $product['product_description'] : 'none' ?></p>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-stock" role="tabpanel" aria-labelledby="nav-stock-tab">
                            <div class="mt-3">
                                    <div class="row align-items-stretch gy-5">
                                        <div class="col-lg col-md-6 col-6">
                                            <span class="text-dark fw-semibold ">Current Stock: </span><br><span class="text-dark"><?php echo !empty($product['stocks']) ? '<span class="badge text-white" style="background-color: #6c757d;">'.$product['stocks'].'</span>' : '<span class="badge text-white" style="background-color: #6c757d;">None</span>' ?></span>
                                        </div>
                                        <div class="col-lg col-md-6 col-6">
                                            <span class="text-dark fw-semibold ">Unit: </span><br><span class="text-dark"><?php echo !empty($product['unit']) ? $product['unit'] : 'none' ?></span>
                                        </div>
                                        <div class="col-lg col-md-6 col-6">
                                            <span class="text-dark fw-semibold ">Minimum Qty: </span><br><span class="text-dark"><?php echo !empty($product['product_min']) ? $product['product_min'] : 'none' ?></span>
                                        </div>
                                        <div class="col-lg col-md-6 col-6">
                                            <span class="text-dark fw-semibold ">Maximum Qty: </span><br><span class="text-dark"><?php echo !empty($product['product_max']) ? $product['product_max'] : 'none' ?></span>
                                        </div>
                                        <div class="col-lg col-md-6 col-6">
                                        <span class="text-dark fw-semibold ">Status: </span><br><span class="text-dark">
                                            <?php
                                            if(!empty($product['status_id']) && $product['status_id'] == 1){
                                                echo '<span class="badge text-white" style="background-color: #58D68D;">In Stock</span>';
                                            }
                                            if(!empty($product['status_id']) && $product['status_id'] == 2){
                                                echo '<span class="badge text-white" style="background-color: #FFAF61;">Low Stock</span>';
                                            }
                                            if(!empty($product['status_id']) && $product['status_id'] == 3){
                                                echo '<span class="badge text-white" style="background-color: #EC7063;">Out of Stock</span>';
                                            }
                                            ?>
                                        </span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mt-1 mb-0">Item List</h5>
            </div>
            <div class="card-body">
            <table id="viewProductTable" class="table table-hover table-cs-color">
                    <thead>
                        <tr>
                            <th class="text-start">Product Barcode</th>
                            <th class="text-center">Qty</th>
                            <th>Expiry Date</th>
                            <th class="text-center">Remaining Days</th>
                            <th class="text-center noExport">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                            <?php foreach($items as $item):?>
                            <tr>
                            <td class="text-start"><span><?php echo "<img src='data:image/png;base64," . base64_encode(generateBarcode(!empty($item['item_barcode']) ? $item['item_barcode'] : 'none')) . "' width='180'>";?></span><br><span><?php echo !empty($item['item_barcode']) ? $item['item_barcode'] : 'none';?></span></td>
                            <td class="text-center"><span class="btn btn-secondary btn-sm"><?php echo $item['available_qty'];?></span></td>
                            <td><?php echo (!empty($item['item_expiry']) && $item['item_expiry'] !== '0000-00-00') ? $item['item_expiry'] : '<span class="badge bg-warning text-dark">None</span>'; ?></td>
                            <td class="text-center">
                                <?php 
                                    $days_to_expiry = (int)$item['days_to_expiry'];

                                    if (is_null($item['days_to_expiry'])) {
                                        echo '<span class="badge bg-warning text-dark">None</span>';
                                    } elseif ($days_to_expiry <= 0) {
                                        echo '<span class="badge bg-danger">Expired</span>';
                                    } else {
                                        echo '<span class="badge bg-secondary">' . $days_to_expiry . '</span>';
                                    }
                                ?>
                            </td>
                            <td class="text-center"><button class="btn btn-danger btn-sm btn-waste" data-sku="<?php echo !empty($product['product_sku']) ? $product['product_sku'] : 'none' ?>" data-item-barcode="<?php echo !empty($item['item_barcode']) ? $item['item_barcode'] : 'none';?>" data-item-qty="<?php echo $item['available_qty']; ?>">Move to Waste</button></td>
                            </tr>
                            <?php endforeach;?>
                        
                    </tbody>
                </table>
            </div>
        </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="wasteModal" tabindex="-1" aria-labelledby="wasteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="wasteForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="wasteModalLabel">Waste Product</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="void_card" class="form-label">Scan Waste Card</label>
            <input type="text" class="form-control" id="void_card" name="void_card" placeholder="Scan Barcode Here.." autocomplete="off">
          </div>  
          <div class="col-lg-12">
            <label for="product_desc" class="form-label">Reason</label>
            <input type="hidden" class="form-control" id="product_sku" name="product_sku">
            <input type="hidden" class="form-control" id="product_barcode" name="product_barcode">
            <textarea type="text" class="form-control" id="product_desc" name="product_desc" placeholder="Ex. Food"></textarea>
          </div>
          <div class="col-lg-6">
            <label for="min_qty" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="qty" name="qty" value="1" min="1" placeholder="Ex. 20" pattern="[0-9]*">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="moveWaste" update-id="">Move to Waste</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->
<script>
$(document).ready( function () {
    // let table = new DataTable('#viewProductTable');
    $('#viewProductTable').DataTable( {
    order: [[3, 'asc']],
    paging: true,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 200,
    responsive: true,
    autoWidth: false,
    footer: false,
	dom: 'Bfrtip',
        buttons: [
        {
            extend: 'copy',
			title: '<?php echo !empty($product['product_name']) ? $product['product_name'] : 'none' ?>',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'csv',
			title: '<?php echo !empty($product['product_name']) ? $product['product_name'] : 'none' ?>',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'excel',
			title: '<?php echo !empty($product['product_name']) ? $product['product_name'] : 'none' ?>',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'pdf',
			title: '<?php echo !empty($product['product_name']) ? $product['product_name'] : 'none' ?>',
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'print',
			title: '<?php echo !empty($product['product_name']) ? $product['product_name'] : 'none' ?>',
            exportOptions: {
            columns: ":visible:not(.noExport)"
            }
        }
    ]
    });
    $('#qty').on('input', function(){
        let value = $(this).val().replace(/\D/g, ''); // Remove non-digit characters
        let maxQty = $(this).attr('max'); // Get the max attribute value
        value = Math.max(1, Math.min(maxQty, value)); // Ensure the value is between 1 and maxQty
        $(this).val(value);
    });
    $('#moveWaste').click(function(){
        var formData = $('#wasteForm').serializeArray();
        formData.push({ name: 'action', value: 'moveWaste'});
        //console.log(formData);
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: $.param(formData),
            dataType: "json",
            success: function(response) {
                if(response.success) {
                    $('#wasteModal').modal('hide');
                    toastr.success(response.message);
                    // You can also clear the form or take other actions here
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX error: ', textStatus, errorThrown);
            }
        });
    });
    
    $('#viewProductTable').on('click', 'button.btn-waste', function () {
        var itemQty = $(this).data('item-qty');
        var product_sku = $(this).data('sku');
        var product_barcode = $(this).data('item-barcode');
        $('#product_sku').val(product_sku);
        $('#product_barcode').val(product_barcode);
        // console.log(itemQty);
        $('#qty').attr('max', itemQty);
        $('#wasteModal').modal('show');
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