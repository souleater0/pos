<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_ingredient')){ ?>
<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Ingredients</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_ingredient')){ ?>
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

<!-- Ingredient Modal -->
<div class="modal fade" id="ingredientModal" tabindex="-1" aria-labelledby="ingredientModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="ingredientForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ingredientModalLabel">Ingredient Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <!-- Ingredient Fields -->
          <div class="col-lg-12">
            <label for="ingredient_name" class="form-label">Ingredient Name</label>
            <input type="text" class="form-control" id="ingredient_name" name="ingredient_name" required>
          </div>
          <div class="col-lg-12">
            <label for="price_per_unit" class="form-label">Price Per Unit</label>
            <input type="number" class="form-control" id="price_per_unit" name="price_per_unit" required>
          </div>
          <div class="col-lg-12">
            <label for="warn_qty" class="form-label">Warning Quantity</label>
            <input type="number" class="form-control" id="warn_qty" name="warn_qty" step="1">
          </div>
          <div class="col-lg-12">
            <label for="unit_type" class="form-label">Unit</label>
            <select class="selectpicker form-control" id="unit_type" name="unit_type" data-live-search="true" required>
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

<!-- Ingredient Batch Modal (for adding batches to ingredient) -->
<div class="modal fade" id="ingredientBatchModal" tabindex="-1" aria-labelledby="ingredientBatchModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="ingredientBatchForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="ingredientBatchModalLabel">Ingredient Batch</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="batch_barcode" class="form-label">Barcode</label>
            <input type="text" class="form-control" id="batch_barcode" name="batch_barcode" required>
          </div>
          <div class="col-lg-12">
            <label for="expiry_date" class="form-label">Expiry Date</label>
            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
          </div>
          <div class="col-lg-12">
            <label for="batch_qty" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="batch_qty" name="batch_qty" step="1" required>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addbatch">ADD ITEM</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- Ingredient Batch Modal (View Batches) -->
<div class="modal fade" id="viewBatchesModal" tabindex="-1" aria-labelledby="viewBatchesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="viewBatchesModalLabel">Ingredient Items</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Barcode</th>
              <th>Quantity</th>
              <th>Expiry Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="batchListBody">
            <!-- Batch items will be populated here -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Waste Confirmation Modal -->
<div class="modal fade" id="wasteModal" tabindex="-1" aria-labelledby="wasteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="wasteForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="wasteModalLabel">Move to Waste</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="waste_qty" class="form-label">Quantity to Waste</label>
            <input type="number" class="form-control" id="waste_qty" name="waste_qty" required>
          </div>
          <div class="col-lg-12">
            <label for="waste_reason" class="form-label">Reason for Waste</label>
            <textarea class="form-control" id="waste_reason" name="waste_reason" rows="3" required></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="moveToWasteBtn">Move to Waste</button>
      </div>
    </form>
    </div>
  </div>
</div>

<script>
  
$(document).ready(function() {
    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";
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
            {
                data: 'ingredient_qty',
                title: 'Qty',
                className: "text-start",
                render: function(data, type, row) {
                    // Customize badge color based on quantity
                    var badgeClass = 'badge bg-success'; // Default badge color

                    // If quantity is less than the warning quantity, change to warning color
                    if (data == 0) {
                        badgeClass = 'badge bg-danger';  // Out of Stock
                    } else if (data < row.warn_qty) {
                        badgeClass = 'badge bg-warning';  // Low Stock
                    } else {
                        badgeClass = 'badge bg-success'; // In Stock
                    }

                    // Return the badge HTML
                    return `<span class="${badgeClass}">${data}</span>`;
                }
            },
            { data: 'warn_qty', visible: false },  // To keep the warning quantity column hidden
            {
                data: null,
                className: "text-center",
                title: 'Status',
                render: function(data, type, row) {
                    var status = "";
                    
                    // Use similar logic to match the status
                    if (row.ingredient_qty == 0) {
                        status = '<span class="badge bg-danger">Out of Stock</span>';
                    } else if (row.ingredient_qty < row.warn_qty) {
                        status = '<span class="badge bg-warning">Low Stock</span>';
                    } else {
                        status = '<span class="badge bg-success">In Stock</span>';
                    }
                    return status;
                }
            },
            { data: 'unit_type', title: 'Unit/MS', className: "text-start"},
            {
                data: null,
                className: "text-center",
                title: 'Action',
                defaultContent: `<button class='btn btn-primary btn-sm btn-addbatch'><i class='fa-solid fa-plus'></i>&nbsp;Add</button>
                                <button class='btn btn-info btn-sm btn-viewbatch'><i class='fa-solid fa-eye'></i>&nbsp;View</button>
                                <button class='btn btn-warning btn-sm btn-updateingredient'><i class='fa-solid fa-edit'></i>&nbsp;Edit</button>
                                <button class='btn btn-danger btn-sm btn-deleteingredient'><i class='fa-solid fa-trash'></i>&nbsp;Delete</button>`
            }
        ]
    });

    // Load table
    function LoadTable() {
        table.ajax.reload(null, false);
    }

    // Add Batch (Open Modal)
    $('#ingredientTable').on('click', 'button.btn-addbatch', function() {
        var data = table.row($(this).parents('tr')).data();
        var ingredient_id = data.ingredient_id;
        var ingredient_name = data.ingredient_name;  // Get the ingredient name

        // Set ingredient_id to the modal's hidden field
        $('#ingredientBatchModal').attr('data-ingredient-id', ingredient_id);
        $('#ingredientBatchForm')[0].reset();  // Clear the form fields

        // Change the modal title dynamically based on the ingredient name
        $('#ingredientBatchModalLabel').text(ingredient_name);  // Update the modal title

        $('#ingredientBatchModal').modal('show');
    });


    // Add Batch (Submit Form)
    $('#addbatch').click(function() {
        var formData = $('#ingredientBatchForm').serialize() + "&action=addBatch&csrf_token=" + csrfToken + "&ingredient_id=" + $('#ingredientBatchModal').attr('data-ingredient-id');

        $.post("admin/process/admin_action.php", formData, function(response) {
            if (response.success) {
                LoadTable();
                $('#ingredientBatchModal').modal('hide');
                swal.fire('Success', response.message, 'success');
            } else {
                swal.fire('Error', response.message, 'error');
            }
        }, "json");
    });

    // Add Ingredient
    $('#addingredientBTN').click(function() {
        $('#ingredientForm')[0].reset();
        $('#unit_type').selectpicker('refresh');
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

    // Edit Ingredient
    $('#ingredientTable').on('click', 'button.btn-updateingredient', function() {
        var data = table.row($(this).parents('tr')).data();
        var ingredient_id = data.ingredient_id;

        // Populate the modal with the ingredient details
        $('#ingredient_name').val(data.ingredient_name);
        $('#price_per_unit').val(data.price_per_unit);
        $('#warn_qty').val(data.warn_qty);
        $('#unit_type').val(data.unit_id).selectpicker('refresh');
        $('#ingredient_qty').prop('disabled', true); // Disable quantity field as it's handled in batches

        $('#updateingredient').show().attr('update-id', ingredient_id);
        $('#addingredient').hide();

        $('#ingredientModal').modal('show');
    });

    // Update Ingredient
    $('#updateingredient').click(function() {
        var formData = $('#ingredientForm').serialize() + "&action=updateIngredient&update_id=" + $(this).attr('update-id') + "&csrf_token=" + csrfToken;
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

    // Delete Ingredient
    $('#ingredientTable').on('click', 'button.btn-deleteingredient', function() {
        var data = table.row($(this).parents('tr')).data();
        var ingredient_id = data.ingredient_id;

        // Confirm delete action
        swal.fire({
            title: 'Are you sure?',
            text: 'This action will permanently delete the ingredient.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'admin/process/admin_action.php',
                    type: 'POST',
                    data: {
                        action: 'deleteIngredient',
                        update_id: ingredient_id,
                        csrf_token: csrfToken
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            LoadTable();
                            swal.fire('Deleted!', response.message, 'success');
                        } else {
                            swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        swal.fire('Error', 'An error occurred while deleting the ingredient.', 'error');
                    }
                });
            }
        });
    });

    // View Batches and Move to Waste
    $('#ingredientTable').on('click', 'button.btn-viewbatch', function() {
        var data = table.row($(this).parents('tr')).data();
        var ingredient_id = data.ingredient_id;
        var ingredient_name = data.ingredient_name;

        $.ajax({
            url: 'admin/process/admin_action.php',
            type: 'POST',
            data: {
                action: 'viewBatches',
                ingredient_id: ingredient_id,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#viewBatchesModal .modal-title').text(ingredient_name);

                    $('#batchListBody').html('');
                    $.each(response.data, function(i, batch) {
                        $('#batchListBody').append(
                            `<tr>
                                <td class="text-dark">${batch.barcode}</td>
                                <td class="text-dark">${batch.quantity}</td>
                                <td class="text-dark">${batch.expiry_date}</td>
                                <td class="text-start">
                                    <button class='btn btn-danger btn-sm btn-movetowaste' data-batch-id='${batch.batch_id}' data-quantity='${batch.quantity}' data-barcode='${batch.barcode}'>
                                        <i class='fa-solid fa-trash'></i>&nbsp;Move to Waste
                                    </button>
                                </td>
                            </tr>`
                        );
                    });
                    $('#viewBatchesModal').modal('show');
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                swal.fire('Error', 'An error occurred while loading batch details.', 'error');
            }
        });
    });

  // Open the Waste Modal when Move to Waste button is clicked
  $('#viewBatchesModal').on('click', 'button.btn-movetowaste', function() {
      var batch_id = $(this).data('batch-id');  // Retrieve batch ID from button data
      var batch_quantity = $(this).data('quantity');
      var batch_barcode = $(this).data('barcode');

      console.log(batch_id, batch_quantity, batch_barcode);

      // Set the batch ID and other details in the modal
      $('#wasteModal').find('#waste_qty').val(batch_quantity); // Set the default quantity
      $('#wasteModal').find('#waste_reason').val(''); // Clear reason input
      $('#wasteModal').attr('data-batch-id', batch_id);  // Store batch ID in the modal

      // Show the Waste Modal
      $('#wasteModal').modal('show');
  });

  // Handle the submission of the waste form
  $('#moveToWasteBtn').click(function() {
      var batch_id = $('#wasteModal').attr('data-batch-id');
      var waste_qty = $('#waste_qty').val();
      var waste_reason = $('#waste_reason').val();
      var displayName = "<?php echo $_SESSION['display_name']; ?>";  // Get the display_name from session

      console.log(batch_id, waste_qty, waste_reason, displayName);

      // Make sure quantity and reason are provided
      if (waste_qty && waste_reason) {
          var formData = {
              action: 'moveToWaste',
              batch_id: batch_id,
              waste_qty: waste_qty,
              waste_reason: waste_reason,
              csrf_token: csrfToken,
              reported_by: displayName  // Include the display name in the request
          };

          $.post("admin/process/admin_action.php", formData, function(response) {
              if (response.success) {
                  // Update the table and close the modal
                  LoadTable();
                  $('#viewBatchesModal').modal('hide');
                  $('#wasteModal').modal('hide');
                  swal.fire('Success', response.message, 'success');
              } else {
                  swal.fire('Error', response.message, 'error');
              }
          }, "json");
      } else {
          swal.fire('Error', 'Please provide quantity and reason.', 'error');
      }
  });

});
</script>
<?php } ?>
