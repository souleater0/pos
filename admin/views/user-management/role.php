<?php 
$modules = getModules($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_role')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Role</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_role')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addRoleBTN" data-bs-toggle="modal" data-bs-target="#roleModal"><i class="fa-solid fa-plus"></i>&nbsp;Add
              Role</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="roleTable" class="table table-hover table-cs-color">
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade modal-lg" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="roleModalLabel">Role Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="role_name" class="form-label">Role Name</label>
            <input type="text" class="form-control" id="role_name" name="role_name" placeholder="Ex. Admin">
          </div>
          <input type="hidden" id="csrf_token" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
          <span class="text-black fw-bold">Assign Permissions to Role</span>
          <div class="col-lg-12">
          <table class="table">
            <thead>
              <tr>
              <th><input type="checkbox" class="align-middle checkbox_middle form-check-input" id="checkall_global" onclick="checkAllGlobal(this)"></th>
                <th>Module</th>
                <th>Permissions</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($modules as $module):?>
        <tr>
          <td><input type="checkbox" class="form-check-input module-checkbox" data-module-id="<?= $module['id']; ?>"></td>
          <td class="text-dark fw-semibold"><?= htmlspecialchars($module['module_name']); ?></td>
          <td>
            <div class="row permissions-container" data-module-id="<?= $module['id']; ?>">
              <?php
              $permissions = getModulePermissions($pdo, $module['id']);
              ?>
              <?php foreach ($permissions as $permission): ?>
                  <div class="col-md-3">
                      <div class="form-check form-check-inline">
                          <input class="form-check-input permission-checkbox" id="permission<?= $permission['id']; ?>" name="permissions[]" type="checkbox" value="<?= $permission['id']; ?>" data-module-id="<?= $module['id']; ?>">
                          <label for="permission<?= $permission['id']; ?>" class="form-check-label text-dark fw-semibold user-select-none cursor-pointer"><?= htmlspecialchars($permission['description']); ?></label>
                      </div>
                  </div>
              <?php endforeach; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
            </tbody>
          </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="updateRole" update-id="">UPDATE</button>
        <button type="button" class="btn btn-primary" id="addRole">ADD</button>
      </div>
    </div>
  </div>
</div>
<!-- END -->
<script>
  
    // Function to update the "Check All" checkbox based on individual checkboxes
    function updateCheckAllGlobal() {
        var totalCheckboxes = $('input[type="checkbox"]').not('#checkall_global').length;
        var checkedCheckboxes = $('input[type="checkbox"]:checked').not('#checkall_global').length;
        $('#checkall_global').prop('checked', totalCheckboxes === checkedCheckboxes);
    }

    // Handle checking/unchecking all checkboxes globally
    function checkAllGlobal(source) {
        var isChecked = $(source).is(':checked');
        $('input[type="checkbox"]').not('#checkall_global').prop('checked', isChecked);
    }

    // Attach the "Check All" event handler
    $('#checkall_global').on('change', function () {
        checkAllGlobal(this);
    });

    // Update the "Check All" checkbox when any individual checkbox changes
    $(document).on('change', 'input[type="checkbox"]', function () {
        if ($(this).attr('id') !== 'checkall_global') {
            updateCheckAllGlobal();
        }
    });

    $(document).ready(function () {
        updateCheckAllGlobal();
        
        $('#addRoleBTN').click(function () {
            // Uncheck all checkboxes
            $('input[type="checkbox"]').prop('checked', false);
            
            // Hide the "Update" button and show the "Add" button
            $('#updateRole').hide();
            $('#addRole').show();

            // Clear the role name input field
            $('#role_name').val('');
        });

        // Add Role
        $('#addRole').click(function () {
          $('#updateRole').hide();
          $('#addRole').show();
            var roleName = $('#role_name').val();
            var selectedPermissions = [];
            $('.permission-checkbox:checked').each(function () {
                selectedPermissions.push($(this).val());
            });
            var csrfToken = $('#csrf_token').val();  // Get CSRF token

            // Ajax Request
            $.ajax({
                url: "admin/process/admin_action.php",
                method: "POST",
                data: {
                    role_name: roleName,
                    selected_permission: selectedPermissions,
                    csrf_token: csrfToken,  // Send CSRF token
                    action: "addRole"
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        LoadTable();
                        $('#roleModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        });

        $('#updateRole').click(function() {
            var roleId = $(this).attr('update-id');
            var roleName = $('#role_name').val();
            var selectedPermissions = [];
            var csrfToken = $('#csrf_token').val();  // Get CSRF token

            $('.permission-checkbox:checked').each(function() {
                selectedPermissions.push($(this).val());
            });
            
            $.ajax({
                url: "admin/process/admin_action.php",
                method: "POST",
                data: {
                    role_id: roleId,
                    role_name: roleName,
                    selected_permission: selectedPermissions,
                    csrf_token: csrfToken,  // Send CSRF token
                    action: "updateRole"
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        LoadTable();
                        $('#roleModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'An error occurred while updating the role.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });

        // Initialize DataTable
        var table = $('#roleTable').DataTable({
            responsive: true,
            select: true,
            autoWidth: false,
            ajax: {
                url: 'admin/process/table.php?table_type=roles',
                dataSrc: 'data'
            },
            columns: [
                { data: 'id', visible: false },
                { data: 'role_name', title: 'Role Name' }
                <?php if (userHasPermission($pdo, $_SESSION["user_id"], 'update_role') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_role')) { ?>
                    , { "data": null, "className": "text-center", title: 'Action', "defaultContent": "<?php if (userHasPermission($pdo, $_SESSION["user_id"], 'update_role')) { ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if (userHasPermission($pdo, $_SESSION["user_id"], 'delete_role')) { ?><button class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button><?php } ?>" }
                <?php } ?>
            ]
        });

        // Function to reload DataTable
        function LoadTable() {
            table.ajax.reload(null, false);
        }

        // Handle module checkbox changes
        $('.module-checkbox').on('change', function () {
            var moduleId = $(this).data('module-id');
            var isChecked = $(this).is(':checked');
            $(`.permissions-container[data-module-id="${moduleId}"] .permission-checkbox`).prop('checked', isChecked);
        });

        function updateModuleCheckbox(moduleId) {
            var allPermissionsChecked = $(`.permissions-container[data-module-id="${moduleId}"] .permission-checkbox`).length === $(`.permissions-container[data-module-id="${moduleId}"] .permission-checkbox:checked`).length;
            $(`.module-checkbox[data-module-id="${moduleId}"]`).prop('checked', allPermissionsChecked);
        }

        $(document).on('change', '.permission-checkbox', function () {
            var moduleId = $(this).data('module-id');
            updateModuleCheckbox(moduleId);
        });

        $('.module-checkbox').each(function () {
            var moduleId = $(this).data('module-id');
            updateModuleCheckbox(moduleId);
        });

        // Handle role edit button click
        $('#roleTable').on('click', 'button.btn-edit', function () {
            var data = table.row($(this).parents('tr')).data();
            $.ajax({
                url: "admin/process/admin_action.php",
                method: "POST",
                data: {
                    role_id: data.id,
                    csrf_token: $('#csrf_token').val(), // Send CSRF token
                    action: "getUserPermissionbyID"
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $('#role_name').val(response.role_name);
                        $('.permission-checkbox').prop('checked', false);
                        response.permissions.forEach(function (permissionId) {
                            $('#permission' + permissionId).prop('checked', true);
                        });
                        $('.module-checkbox').each(function () {
                            var moduleId = $(this).data('module-id');
                            updateModuleCheckbox(moduleId);
                        });
                        $('#updateRole').attr('update-id', data.id);
                        updateCheckAllGlobal();
                        $('#addRole').hide();
                        $('#updateRole').show();
                        $('#roleModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        });
        $('#roleTable').on('click', 'button.btn-danger', function () {
          var data = table.row($(this).parents('tr')).data();

          // Show SweetAlert for confirmation
          Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
              if (result.isConfirmed) {
                  // If confirmed, proceed with the deletion
                  $.ajax({
                      url: "admin/process/admin_action.php",
                      method: "POST",
                      data: {
                          role_id: data.id,
                          csrf_token: $('#csrf_token').val(),
                          action: "deleteRole"
                      },
                      dataType: "json",
                      success: function (response) {
                          if (response.success) {
                              LoadTable();
                              Swal.fire({
                                  icon: 'success',
                                  title: response.message,
                                  showConfirmButton: false,
                                  timer: 1500
                              });
                          } else {
                              Swal.fire({
                                  icon: 'error',
                                  title: response.message,
                                  showConfirmButton: false,
                                  timer: 1500
                              });
                          }
                      },
                      error: function() {
                          Swal.fire({
                              icon: 'error',
                              title: 'An error occurred while deleting the role.',
                              showConfirmButton: false,
                              timer: 1500
                          });
                      }
                  });
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
