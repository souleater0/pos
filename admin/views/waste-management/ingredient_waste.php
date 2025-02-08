<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_waste')){ ?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Waste</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_waste')){ ?>
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
            <label for="ingredient_name" class="form-label">Ingredient Name</label>
            <input type="text" class="form-control" id="ingredient_name" name="ingredient_name" placeholder="Ex. Batter Flour" required>
          </div>
          <div class="col-lg-12">
            <label for="quantity_wasted" class="form-label">Quantity Wasted</label>
            <input type="number" class="form-control" id="quantity_wasted" name="quantity_wasted" placeholder="Ex. 10" required>
          </div>
          <div class="col-lg-12">
            <label for="reason" class="form-label">Reason</label>
            <input type="text" class="form-control" id="reason" name="reason" placeholder="Ex. Overcooked" required>
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
        select: true,
        autoWidth: false,
        ajax: {
            url: 'admin/process/table.php?table_type=ing_waste',
            dataSrc: 'data'
        },
        columns: [
            { data: 'waste_id', visible: false },
            { data: 'ingredient_name', title: 'Ingredient Name' },
            { data: 'quantity_wasted', title: 'Quantity Wasted' },
            { data: 'reason', title: 'Reason' },
            { data: 'reported_by', title: 'Reported By' },
            { data: 'created_at', title: 'Created At', className: 'text-start' },
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_waste') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_waste')){?>
            {
                data: null,
                className: "text-center",
                title: 'Action',
                defaultContent: `<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_waste')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_waste')){ ?><button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button><?php } ?>`
            }
            <?php } ?>
        ]
    });

    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

    function LoadTable() {
        $.ajax({
            url: 'admin/process/table.php?table_type=waste',
            dataType: 'json',
            success: function(data) {
                table.clear().rows.add(data.data).draw(false);
                setTimeout(function() {
                    table.ajax.reload(null, false);
                }, 1000);
            },
            error: function () {
                swal.fire('Error', 'Failed to retrieve waste records.', 'error');
            }
        });
    }

    $('#wasteForm').on('submit', function(event) {
        event.preventDefault();
    });

    $('#addWasteBTN').click(function() {
        $('#ingredient_name').val('');
        $('#quantity_wasted').val('');
        $('#reason').val('');
        $('#addWaste').show();
        $('#updateWaste').hide();
    });

    $('#addWaste').click(function() {
        var formData = $('#wasteForm').serialize() + "&action=addWaste&csrf_token=" + csrfToken;
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
        var formData = $('#wasteForm').serialize() + "&action=updateWaste&update_id=" + $(this).attr("update-id") + "&csrf_token=" + csrfToken;
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
        $('#ingredient_name').val(data.ingredient_name);
        $('#quantity_wasted').val(data.quantity_wasted);
        $('#reason').val(data.reason);
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
                    data: { action: 'deleteWaste', waste_id: data.waste_id, csrf_token: csrfToken },
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
