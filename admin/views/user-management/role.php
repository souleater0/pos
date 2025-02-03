<?php 
$modules = getModules($pdo);
//$permissions = getModulePermissions($pdom ,$moduleId);
// function getModulePermissions($permissions, $moduleId) {
//   return array_filter($permissions, function($permission) use ($moduleId) {
//       return $permission['module_id'] == $moduleId;
//   });
// }
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
              // Fetch permissions for the current module from your database
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


    // Initial check to ensure "Check All" reflects the state of all checkboxes on page load
    $(document).ready(function () {
        updateCheckAllGlobal();

        // Add Role
        $('#addRole').click(function () {
          $('#updateRole').hide();
          $('#addRole').show();
            var roleName = $('#role_name').val();
            var selectedPermissions = [];
            $('.permission-checkbox:checked').each(function () {
                selectedPermissions.push($(this).val());
            });

            // Ajax Request
            $.ajax({
                url: "admin/process/admin_action.php",
                method: "POST",
                data: {
                    role_name: roleName,
                    selected_permission: selectedPermissions,
                    action: "addRole"
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        LoadTable();
                        $('#roleModal').modal('hide');
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        });
        $('#updateRole').click(function() {
            var roleId = $(this).attr('update-id'); // Get the role ID from the button's attribute
            var roleName = $('#role_name').val();
            var selectedPermissions = [];
            
            // Gather selected permissions
            $('.permission-checkbox:checked').each(function() {
                selectedPermissions.push($(this).val());
            });
            
            // Send AJAX request
            $.ajax({
                url: "admin/process/admin_action.php", // URL to the server-side script
                method: "POST",
                data: {
                    role_id: roleId,
                    role_name: roleName,
                    selected_permission: selectedPermissions,
                    action: "updateRole" // Action parameter to identify the request
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Handle success response
                        LoadTable(); // Refresh the table
                        $('#roleModal').modal('hide'); // Close the modal
                        toastr.success(response.message); // Show success message
                    } else {
                        // Handle error response
                        toastr.error(response.message); // Show error message
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating the role.'); // Handle AJAX error
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
            table.ajax.reload(null, false); // Reload the DataTable without resetting current page
        }

        // Handle module checkbox changes
        $('.module-checkbox').on('change', function () {
            var moduleId = $(this).data('module-id');
            var isChecked = $(this).is(':checked');
            // Check/uncheck all permissions associated with the module
            $(`.permissions-container[data-module-id="${moduleId}"] .permission-checkbox`).prop('checked', isChecked);
        });

        // Function to check/uncheck module checkboxes based on permissions
        function updateModuleCheckbox(moduleId) {
            var allPermissionsChecked = $(`.permissions-container[data-module-id="${moduleId}"] .permission-checkbox`).length === $(`.permissions-container[data-module-id="${moduleId}"] .permission-checkbox:checked`).length;
            $(`.module-checkbox[data-module-id="${moduleId}"]`).prop('checked', allPermissionsChecked);
        }

        // Event handler for when any permission checkbox is changed
        $(document).on('change', '.permission-checkbox', function () {
            var moduleId = $(this).data('module-id');
            updateModuleCheckbox(moduleId);
        });

        // Initial check to ensure modules reflect the state of permissions on page load
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
                    action: "getUserPermissionbyID"
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        $('#role_name').val(response.role_name); // Set the role name
                        // Uncheck all checkboxes first
                        $('.permission-checkbox').prop('checked', false);
                        // Check the checkboxes that match the role's permissions
                        response.permissions.forEach(function (permissionId) {
                            $('#permission' + permissionId).prop('checked', true);
                        });
                        // Update the module checkboxes based on the selected permissions
                        $('.module-checkbox').each(function () {
                            var moduleId = $(this).data('module-id');
                            updateModuleCheckbox(moduleId);
                        });
                        // Set the update-id attribute for the update button
                        $('#updateRole').attr('update-id', data.id);
                        // Show the modal
                        $("#role_name").val(data.role_name);
                        updateCheckAllGlobal();
                        $('#addRole').hide();
                        $('#updateRole').show();
                        $('#roleModal').modal('show');
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