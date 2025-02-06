<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_category')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Category</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_category')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addcategoryBTN" data-bs-toggle="modal" data-bs-target="#categoryModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Category</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="categoryTable" class="table table-hover table-cs-color"></table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="categoryForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="categoryModalLabel">Category Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="category_name" class="form-label">Category Name</label>
            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Ex. Dish" required>
          </div>          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addcategory">ADD</button>
        <button type="button" class="btn btn-primary" id="updatecategory" update-id="">UPDATE</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- END -->

<script>
$(document).ready(function() {
    var table = $('#categoryTable').DataTable({
        responsive: true,
        select: true,
        autoWidth: false,
        ajax: {
            url: 'admin/process/table.php?table_type=category',
            dataSrc: 'data'
        },
        columns: [
            { data: 'category_id', visible: false },
            { data: 'category_name', title: 'Category Name' },
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_category') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_category')){?>
            {
                data: null,
                className: "text-center",
                title: 'Action',
                defaultContent: `<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_category')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_category')){ ?><button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button><?php } ?>`
            }
            <?php } ?>
        ]
    });

    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

    function LoadTable() {
        $.ajax({
            url: 'admin/process/table.php?table_type=category',
            dataType: 'json',
            success: function(data) {
                table.clear().rows.add(data.data).draw(false);
                setTimeout(function() {
                    table.ajax.reload(null, false);
                }, 1000);
            },
            error: function () {
                swal.fire('Error', 'Failed to retrieve categories.', 'error');
            }
        });
    }

    $('#categoryForm').on('submit', function(event) {
        event.preventDefault();
    });

    $('#addcategoryBTN').click(function() {
        $('#category_name').val('');
        $('#addcategory').show();
        $('#updatecategory').hide();
    });

    $('#addcategory').click(function() {
        var formData = $('#categoryForm').serialize() + "&action=addCategory&csrf_token=" + csrfToken;
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    LoadTable();
                    $('#categoryModal').modal('hide');
                    swal.fire('Success', response.message, 'success');
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    $('#updatecategory').click(function() {
        var formData = $('#categoryForm').serialize() + "&action=updateCategory&update_id=" + $(this).attr("update-id") + "&csrf_token=" + csrfToken;
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    LoadTable();
                    $('#categoryModal').modal('hide');
                    swal.fire('Success', response.message, 'success');
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    $('#categoryTable').on('click', 'button.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();
        $('#category_name').val(data.category_name);
        $('#addcategory').hide();
        $('#updatecategory').show();
        $('#categoryModal').modal('show');
        $("#updatecategory").attr("update-id", data.category_id);
    });

    $('#categoryTable').on('click', 'button.btn-delete', function() {
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
                    data: { action: 'deleteCategory', category_id: data.category_id, csrf_token: csrfToken },
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
