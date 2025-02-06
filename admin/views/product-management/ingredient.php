<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_ingredient')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Ingredients</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_ingredient')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addingredientBTN" data-bs-toggle="modal" data-bs-target="#ingredientModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Ingredient</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="ingredientTable" class="table table-hover table-cs-color"></table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="ingredientForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ingredientModalLabel">Ingredient Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="ingredient_name" class="form-label">Ingredient Name</label>
            <input type="text" class="form-control" id="ingredient_name" name="ingredient_name" required>
          </div>
          <div class="col-lg-12">
            <label for="price_per_unit" class="form-label">Price Per Unit</label>
            <input type="number" class="form-control" id="price_per_unit" name="price_per_unit" required>
          </div>
          <div class="col-lg-12">
            <label for="ingredient_qty" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="ingredient_qty" name="ingredient_qty" step="1" required>
          </div>
          <div class="col-lg-12">
            <label for="warn_qty" class="form-label">Warning Quantity</label>
            <input type="number" class="form-control" id="warn_qty" name="warn_qty" step="1">
          </div>
          <div class="col-lg-12">
            <label for="unit_type" class="form-label">Unit</label>
            <select class="selectpicker form-control" id="unit_type" name="unit_type" data-live-search="true" required>
              <option value="">Select Unit</option>
            <?php 
              $units = getUnits($pdo);
              foreach ($units as $unit) {
                echo "<option value='{$unit['unit_id']}'>{$unit['unit_type']}</option>";
              }
            ?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addingredient">ADD</button>
        <button type="button" class="btn btn-primary" id="updateingredient" update-id="">UPDATE</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- END -->

<script>
$(document).ready(function() {
    var table = $('#ingredientTable').DataTable({
        responsive: true,
        select: true,
        autoWidth: false,
        ajax: {
            url: 'admin/process/table.php?table_type=ingredient',
            dataSrc: 'data'
        },
        columns: [
            { data: 'ingredient_id', visible: false },
            { data: 'ingredient_name', title: 'Ingredient Name', className: "text-start"},
            { data: 'price_per_unit', title: 'Price Per Unit', className: "text-start"},
            { data: 'ingredient_qty', title: 'Quantity', className: "text-start"},
            { data: 'warn_qty', title: 'Warning Quantity', className: "text-start"},
            { data: 'unit_id', visible: false },
            { data: 'unit_type', title: 'Unit/MS', className: "text-start"},
            {
                data: null,
                className: "text-center",
                title: 'Status',
                render: function(data, type, row) {
                    var status = "";
                    if (row.ingredient_qty <= row.warn_qty && row.ingredient_qty > 0) {
                        status = '<span class="badge bg-warning">Low Stock</span>';
                    } else if (row.ingredient_qty == 0) {
                        status = '<span class="badge bg-danger">Out of Stock</span>';
                    } else {
                        status = '<span class="badge bg-success">In Stock</span>';
                    }
                    return status;
                }
            },
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_ingredient') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_ingredient')){?>
            {
                data: null,
                className: "text-center",
                title: 'Action',
                defaultContent: `<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_ingredient')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_ingredient')){ ?><button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button><?php } ?>`
            }
            <?php } ?>
        ]
    });

    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

    function LoadTable() {
        table.ajax.reload(null, false);
    }

    $('#addingredientBTN').click(function() {
        $('#ingredientForm')[0].reset();
        $('#unit_type').selectpicker('refresh'); // Reset selectpicker
        $('#addingredient').show();
        $('#updateingredient').hide();
    });

    $('#addingredient').click(function() {
        var formData = $('#ingredientForm').serialize() + "&action=addIngredient&csrf_token=" + csrfToken;
        $.post("admin/process/admin_action.php", formData, function(response) {
            if (response.success) {
                LoadTable();
                $('#ingredientModal').modal('hide');
                swal.fire('Success', response.message, 'success');
            } else {
                swal.fire('Error', response.message, 'error');
            }
        }, "json");
    });

    $('#updateingredient').click(function() {
        var formData = $('#ingredientForm').serialize() + "&action=updateIngredient&update_id=" + $(this).attr("update-id") + "&csrf_token=" + csrfToken;
        $.post("admin/process/admin_action.php", formData, function(response) {
            if (response.success) {
                LoadTable();
                $('#ingredientModal').modal('hide');
                swal.fire('Success', response.message, 'success');
            } else {
                swal.fire('Error', response.message, 'error');
            }
        }, "json");
    });

    $('#ingredientTable').on('click', 'button.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();
        $('#ingredient_name').val(data.ingredient_name);
        $('#price_per_unit').val(data.price_per_unit);
        $('#ingredient_qty').val(data.ingredient_qty);
        $('#warn_qty').val(data.warn_qty);
        $('#unit_type').val(data.unit_id).selectpicker('refresh'); 
        $('#addingredient').hide();
        $('#updateingredient').show().attr("update-id", data.ingredient_id);
        $('#ingredientModal').modal('show');
    });

    $('#ingredientTable').on('click', 'button.btn-delete', function() {
        var data = table.row($(this).parents('tr')).data();
        var ingredient_id = data.ingredient_id;

        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("admin/process/admin_action.php", {action: 'deleteIngredient', ingredient_id: ingredient_id, csrf_token: csrfToken}, function(response) {
                    if (response.success) {
                        LoadTable();
                        swal.fire('Deleted!', response.message, 'success');
                    } else {
                        swal.fire('Error', response.message, 'error');
                    }
                }, "json");
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
        <h2 class="fw-bolder">User does not have permission!</h2>
      </div>
    </div>
  </div>
</div>
<?php } ?>
