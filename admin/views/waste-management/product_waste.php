<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_product_waste')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Product Waste</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_product_waste')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addWasteBTN" data-bs-toggle="modal" data-bs-target="#wasteModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Waste</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="wasteTable" class="table table-hover table-cs-color"></table>
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
          <h1 class="modal-title fs-5" id="wasteModalLabel">Waste Details</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body border">
          <div class="row gy-2">
            <div class="col-lg-12">
              <label for="product_id" class="form-label">Select Product</label>
              <select class="selectpicker form-control" id="product_id" name="product_id" data-live-search="true" required>
              <?php 
                $products = getProductOptions($pdo);
                foreach ($products as $product) {
                  echo "<option value='{$product['product_id']}'>{$product['product_name']}</option>";
                }
              ?>
              </select>
            </div>
            <div class="col-lg-12">
              <label for="quantity_wasted" class="form-label">Quantity</label>
              <input type="number" class="form-control" id="quantity_wasted" name="quantity_wasted" placeholder="Enter quantity" required>
            </div>
            <div class="col-lg-12">
              <label for="waste_reason" class="form-label">Reason for Waste</label>
              <textarea class="form-control" id="waste_reason" name="waste_reason" placeholder="Enter reason" required></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="addWaste">ADD</button>
          <button type="button" class="btn btn-primary" id="updateWaste" update-id="">UPDATE</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->

<script>
$(document).ready(function() {
    var table = $('#wasteTable').DataTable({
        responsive: true,
        select: false,
        autoWidth: false,
        ajax: {
            url: 'admin/process/table.php?table_type=product_waste',
            dataSrc: 'data'
        },
        columnDefs: [
            // Set width for 'Product Name' column as percentage
            { targets: 1, width: '20%' }, // Adjust width for 'Product Name' column (25%)
            // Set width for 'Quantity' column as percentage
            { targets: 2, width: '10%' },
            { targets: 3, width: '10%' },
            // Set width for 'Reason' column as percentage and add word wrap to prevent overflow
            { 
                targets: 4, 
                width: '40%', // Adjust width for 'Reason' column (40%)
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).css('word-wrap', 'break-word');
                }
            },
            // Set width for 'Reported By' column as percentage
            { targets: 5, width: '10%' }, // Adjust width for 'Reported By' column (20%)
            { targets: 6, width: '10%' },
        ],
        columns: [
            { data: 'waste_id', visible: false },
            { data: 'product_name', title: 'Product Name',className: 'text-start'},
            { data: 'product_price', title: 'Price',className: 'text-start'},
            { data: 'quantity_wasted', title: 'Quantity',className: 'text-start'},
            { data: 'reason', title: 'Reason' },
            { data: 'reported_by', title: 'Reported By',className: 'text-start'},
            { data: 'created_at', title: 'Date Reported',className: 'text-start'},
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_product_waste') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_product_waste')){?>
            {
                data: null,
                className: "text-start",
                title: 'Action',
                defaultContent: `<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_product_waste')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_product_waste')){ ?><button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button><?php } ?>`
            }
            <?php } ?>
        ]
    });

    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";
    var displayName = "<?php echo $_SESSION['display_name']; ?>"; // Get the logged-in user's name

    function LoadTable() {
        table.ajax.reload(null, false);
    }

    $('#addWasteBTN').click(function() {
        $('#product_id').selectpicker('refresh');
        $('#quantity_wasted').val('');
        $('#waste_reason').val('');
        $('#addWaste').show();
        $('#updateWaste').hide();
    });

    $('#addWaste').click(function() {
        var formData = $('#wasteForm').serialize() + "&action=addProductWaste&csrf_token=" + csrfToken + "&reported_by=" + displayName;
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    LoadTable();
                    $('#wasteModal').modal('hide');
                    swal.fire('Success', response.message, 'success');
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    $('#updateWaste').click(function() {
        var formData = $('#wasteForm').serialize() + "&action=updateProductWaste&update_id=" + $(this).attr("update-id") + "&csrf_token=" + csrfToken + "&reported_by=" + displayName;
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    LoadTable();
                    $('#wasteModal').modal('hide');
                    swal.fire('Success', response.message, 'success');
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    $('#wasteTable').on('click', 'button.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();

        // Find the product_id based on product_name
        var productId = $("select#product_id option").filter(function() {
            return $(this).text() === data.product_name;
        }).val();

        $('#product_id').val(productId).selectpicker('refresh');
        $('#quantity_wasted').val(data.quantity_wasted);
        $('#waste_reason').val(data.reason);
        $('#addWaste').hide();
        $('#updateWaste').show();
        $('#wasteModal').modal('show');
        $("#updateWaste").attr("update-id", data.waste_id);
    });

    $('#wasteTable').on('click', 'button.btn-delete', function() {
        var data = table.row($(this).parents('tr')).data();
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
                    data: { action: 'deleteProductWaste', waste_id: data.waste_id, csrf_token: csrfToken },
                    dataType: 'json',
                    success: function(response) {
                        LoadTable();
                        swal.fire('Deleted!', response.message, 'success');
                    }
                });
            }
        });
    });
});
</script>
<?php } ?>
