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
?>
<div class="body-wrapper-inner open-sans-regular">
    <div class="container-fluid" style="max-width: 100% !important;">
        <div class="card shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="mt-1 mb-0">Product Details</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-stretch gy-5">
                        <div class="col-lg-3 col-md-6">
                            <span class="text-dark fw-semibold ">Product Name: </span><br><span class="text-dark">Bear Brand</span>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <span class="text-dark fw-semibold ">Brand: </span><br><span class="text-dark">Nestle</span>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <span class="text-dark fw-semibold ">Category / Subcategory: </span><br><span class="text-dark">Powdered Milk / Bebe powder</span>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <span class="text-dark fw-semibold ">SKU: </span><br><?php echo "<img src='data:image/png;base64," . base64_encode(generateBarcode("P0000004")) . "' width='180'>";?><br><span class="text-dark">P0000004</span>
                        </div>
                    </div>
                    <div class="row align-items-stretch gy-5 mt-0">
                        <div class="col-lg-2 col-md-6">
                            <span class="text-dark fw-semibold ">Purchasing Price: </span><br><span class="text-dark">₱ 21</span>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <span class="text-dark fw-semibold ">Selling Price: </span><br><span class="text-dark">₱ 25</span>
                        </div>
                        <div class="col-lg-2 col-md-6 col-6">
                            <span class="text-dark fw-semibold ">Unit: </span><br><span class="text-dark">pcs</span>
                        </div>
                        <div class="col-lg-2 col-md-6 col-6">
                            <span class="text-dark fw-semibold ">Tax: </span><br><span class="text-dark">No Tax</span>
                        </div>
                        <div class="col-lg-2 col-md-6 col-6">
                            <span class="text-dark fw-semibold ">Stock: </span><br><span class="text-dark">10</span>
                        </div>
                        <div class="col-lg-2 col-md-6 col-6">
                            <span class="text-dark fw-semibold ">Status: </span><br><span class="text-dark"><span class="badge text-white" style="background-color: #FFAF61;">In Stock</span></span>
                        </div>
                    </div>
                </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mt-1 mb-0">Product Description</h5>
            </div>
            <div class="card-body">
                <p class="text-dark text-break">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="mt-1 mb-0">Item List</h5>
            </div>
            <div class="card-body">
            <table id="myTable" class="table table-hover table-cs-color">
                    <thead>
                        <tr>
                            <th></th>
                            <th>SKU</th>
                            <th class="text-start">Product Barcode</th>
                            <th class="text-center">Qty</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td><span>ITM001</span></td>
                            <td class="text-start"><span><?php echo "<img src='data:image/png;base64," . base64_encode(generateBarcode("ITM001")) . "' width='180'>";?></span></td>
                            <td class="text-center"><span class="btn btn-secondary btn-sm">5</span></td>
                            <td>2024-April-4</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><span>ITM002</span></td>
                            <td class="text-start"><span><?php echo "<img src='data:image/png;base64," . base64_encode(generateBarcode("ITM002")) . "' width='180'>";?></span></td>
                            <td class="text-center"><span class="btn btn-secondary btn-sm">5</span></td>
                            <td>2024-April-4</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
  </div>
</div>
<script>
  $(document).ready( function () {
    // let table = new DataTable('#myTable');
    $('#myTable').DataTable( {
      columnDefs: [
        {
            orderable: false,
            render: DataTable.render.select(),
            targets: 0
        }
    ],
    order: [[1, 'asc']],
    paging: true,
    scrollCollapse: true,
    scrollX: true,
    scrollY: 200,
    select: {
        style: 'multi',
        selector: 'td:first-child'
    },
    responsive: true,
    autoWidth: false,
    footer: false
  });
  });
</script>