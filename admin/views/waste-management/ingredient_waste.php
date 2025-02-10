<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_ingredient_waste')){ ?>
<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Ingredient Waste</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_ingredient_waste')){ ?>
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
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="reported_by" value="<?php echo $_SESSION['display_name']; ?>">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="wasteModalLabel">Waste Details</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body border">
          <div class="row gy-2">
            <div class="col-lg-12">
              <label for="ingredient_id" class="form-label">Ingredient Name</label>
              <select class="form-control selectpicker" id="ingredient_id" name="ingredient_id" data-live-search="true" required>
                <?php
                  $stmt = $pdo->query("SELECT ingredient_id, ingredient_name FROM ingredient ORDER BY ingredient_name");
                  while ($row = $stmt->fetch()) {
                      echo "<option value='" . $row['ingredient_id'] . "'>" . $row['ingredient_name'] . "</option>";
                  }
                ?>
              </select>
            </div>
            <!-- Quantity field should be editable only for adding waste -->
            <div class="col-lg-12">
              <label for="quantity_wasted" class="form-label">Quantity</label>
              <input type="number" class="form-control" id="quantity_wasted" name="quantity_wasted" placeholder="Enter quantity" step=".01" min=".01">
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
<!-- END Modal -->

<script>
$(document).ready(function() {

    var table = $('#wasteTable').DataTable({
        responsive: true,
        autoWidth: false,
        ajax: {
            url: 'admin/process/table.php?table_type=ingredient_waste',
            dataSrc: 'data'
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'waste_id', visible: false },
            { data: 'ingredient_barcode', title: 'Barcode' },
            { data: 'ingredient_name', title: 'Ingredient Name' },
            { data: 'quantity_wasted', title: 'Quantity' },
            { data: 'reason', title: 'Reason' },
            { data: 'reported_by', title: 'Reported By' },
            { data: 'created_at', title: 'Date Reported' },
            <?php if (userHasPermission($pdo, $_SESSION["user_id"], 'update_ingredient_waste') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_ingredient_waste')) { ?>
                {
                    data: null,
                    className: "text-center",
                    title: 'Action',
                    defaultContent: `<?php if (userHasPermission($pdo, $_SESSION["user_id"], 'update_ingredient_waste')) { ?>
                                        <button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;
                                    <?php } ?>
                                    <?php if (userHasPermission($pdo, $_SESSION["user_id"], 'delete_ingredient_waste')) { ?>
                                        <button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button>
                                    <?php } ?>`
                }
                <?php } ?>
            ]
    });

    $('#addWasteBTN').click(function() {
        // Show the selectpicker for adding waste and allow quantity input
        $('#ingredient_id').prop('disabled', false).selectpicker('refresh');
        $('#quantity_wasted').prop('disabled', false).val('1'); // Enable and clear quantity input
        $('#waste_reason').val('');
        $('#addWaste').show();
        $('#updateWaste').hide();
    });

    $('#addWaste').click(function() {
        var formData = $('#wasteForm').serialize() + "&action=addIngredientWaste";
        $.post("admin/process/admin_action.php", formData, function(response) {
            table.ajax.reload();
            $('#wasteModal').modal('hide');
            Swal.fire({
                title: 'Success',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }, "json");
    });

    $('#wasteTable').on('click', 'button.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();
        
        // Disable selectpicker and show only reason field for update
        $('#ingredient_id').prop('disabled', true).selectpicker('refresh');
        $('#quantity_wasted').val(data.quantity_wasted).prop('disabled', true); // Quantity is shown but disabled
        $('#waste_reason').val(data.reason);
        $('#addWaste').hide();
        $('#updateWaste').show().attr("update-id", data.waste_id);
        $('#wasteModal').modal('show');
    });

    $('#updateWaste').click(function() {
        var updateId = $(this).attr("update-id");
        var formData = $('#wasteForm').serialize() + "&action=updateIngredientWaste&update_id=" + updateId + "&reason=" + $('#waste_reason').val();
        $.post("admin/process/admin_action.php", formData, function(response) {
            table.ajax.reload();
            $('#wasteModal').modal('hide');
            Swal.fire({
                title: 'Success',
                text: response.message,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        }, "json");
    });

    $('#wasteTable').on('click', 'button.btn-delete', function() {
      var data = table.row($(this).parents('tr')).data();
      var csrfToken = $("input[name='csrf_token']").val();

      Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to delete this waste record?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'Cancel'
      }).then((result) => {
          if (result.isConfirmed) {
              $.post("admin/process/admin_action.php", {
                  action: 'deleteIngredientWaste',
                  waste_id: data.waste_id,
                  csrf_token: csrfToken
              }, function(response) {
                  table.ajax.reload();
                  Swal.fire({
                      title: 'Deleted!',
                      text: response.message,
                      icon: 'success',
                      confirmButtonText: 'OK'
                  });
              }, "json");
          }
      });
    });
});
</script>
<?php } ?>
