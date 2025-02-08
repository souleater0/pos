<?php 
$roles = getRole($pdo);
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_user')){ ?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Users</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_user')){ ?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addUserBTN" data-bs-toggle="modal" data-bs-target="#userModal"><i class="fa-solid fa-plus"></i>&nbsp;Add User</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="userTable" class="table table-hover table-cs-color"></table>
      </div>
    </div>
  </div>
</div>

<!-- Modal: User Details -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="userForm">
        <div class="modal-header">
          <h1 class="modal-title fs-5 user-select-none" id="userModalLabel">User Details</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body border">
          <div class="row gy-2">
            <div class="col-lg-12">
              <label for="user_display" class="form-label user-select-none">Display Name</label>
              <input type="text" class="form-control" id="user_display" name="user_display" placeholder="Ex. Juan Dela Cruz">
            </div>  
            <div class="col-lg-12">
              <label for="username" class="form-label user-select-none">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Ex. Juan">
            </div>
            <div class="col-lg-12">
              <label for="user_role" class="form-label user-select-none">Roles</label>
              <select class="selectpicker form-control" id="user_role" name="user_role" data-live-search="true">
                <option value="" disabled>Select Role</option>
                <?php foreach ($roles as $role): ?>
                  <option value="<?php echo $role['id']; ?>"><?php echo $role['role_name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-lg-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="loginEnabled" name="loginEnabled">
                <label class="form-check-label user-select-none" for="loginEnabled">Login is enabled</label>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="updateUser" update-id="">UPDATE</button>
          <button type="button" class="btn btn-primary" id="addUser">ADD</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal: Password Details -->
<div class="modal fade" id="passModal" tabindex="-1" aria-labelledby="passModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="passModalLabel">Password Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="user_password" class="form-label user-select-none">New Password</label>
            <div class="d-flex">
              <input type="password" class="form-control" id="user_password" name="user_password" autocomplete="password">
              <button type="button" class="btn btn-info" id="show_Pass"><i class="fa-solid fa-eye"></i></button>
            </div>
          </div>
          <div class="col-lg-12">
            <label for="user_conpass" class="form-label user-select-none">Confirm Password</label>
            <div class="d-flex">
              <input type="password" class="form-control" id="user_conpass" name="user_conpass" autocomplete="new-password">
              <button type="button" class="btn btn-info" id="show_Pass2"><i class="fa-solid fa-eye"></i></button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="updatePassword" update-id="">Update Password</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function() {
  // DataTable Initialization
  var table = $('#userTable').DataTable({
    responsive: true,
    select: true,
    autoWidth: false,
    ajax: {
      url: 'admin/process/table.php?table_type=users',
      dataSrc: 'data'
    },
    columns: [
      {data: 'id', visible: false},
      {data: 'display_name', title: 'Display Name'},
      {data: 'username', title: 'Username'},
      {data: 'role_id', visible: false},
      {data: 'role_name', title: 'Role'},
      {data: 'isEnabled', visible: false},
      {data: 'status', title: 'Status'}
      <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_user') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_user')){ ?>
      , {
        "data": null,
        "className": "text-center",
        title: 'Action',
        "defaultContent": "<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_user')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<button class='btn btn-secondary btn-sm btn-pass'><i class='fa-solid fa-key'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_user')){ ?><button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button><?php } ?>"
      }
      <?php } ?>
    ]
  });

  // Refresh the table data
  function LoadTable() {
    $.ajax({
      url: 'admin/process/table.php?table_type=users',
      dataType: 'json',
      success: function(data) {
        table.clear().rows.add(data.data).draw(false);
      },
      error: function() {
        alert('Failed to retrieve users.');
      }
    });
  }

  // Add User Modal
  $('#addUserBTN').click(function(){
    $('#user_display').val('');
    $('#username').val('');
    $('#user_role').val('1').selectpicker('refresh');
    $("#loginEnabled").prop('checked', false);
    $('#addUser').show();
    $('#updateUser').hide();
  });

  // Add User
  $('#addUser').click(function(){
    var formData = $('#userForm').serialize() + "&action=addUser&csrf_token=<?php echo $_SESSION['csrf_token']; ?>";
    $.ajax({
      url: "admin/process/admin_action.php",
      method: "POST",
      data: formData,
      dataType: "json",
      success: function(response) {
        console.log(response);
        if(response.success){
          LoadTable();
          $('#userModal').modal('hide');
          Swal.fire({
            title: 'Success!',
            text: response.message,
            icon: 'success',
            confirmButtonText: 'OK'
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: response.message,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      }
    });
  });

    //show pass
  $("#show_Pass").click(function (event) {
    event.preventDefault();
    var icon = $("#show_Pass svg");
    if($("#user_password").attr("type")=="password")
    {
      //$("#show_Pass").text('HIDE');
      $("#user_password").attr('type','text');
      icon.removeClass("fa-eye").addClass("fa-eye-slash");
    }else{
      //$("#show_Pass").text('SHOW');
      $("#user_password").attr('type','password');
      icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  });

  $("#show_Pass2").click(function (event) {
    event.preventDefault();
    var icon = $("#show_Pass2 svg");
    if($("#user_conpass").attr("type")=="password")
    {
      //$("#show_Pass2").text('HIDE');
      $("#user_conpass").attr('type','text');
      icon.removeClass("fa-eye").addClass("fa-eye-slash");
    }else{
      //$("#show_Pass2").text('SHOW');
      $("#user_conpass").attr('type','password');
      icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
  });

  $('#userTable').on('click', 'button.btn-pass', function () {
    var data = table.row($(this).parents('tr')).data();
    $('#passModal').modal('show');
    var update_id = $(this).attr("update-id");
    $("#updatePassword").attr("update-id", data.id);
  });

  // Edit User
  $('#userTable').on('click', 'button.btn-edit', function () {
    var data = table.row($(this).parents('tr')).data();
    $('#user_display').val(data.display_name);
    $('#username').val(data.username);
    $('#user_role').val(data.role_id).selectpicker('refresh');
    $("#loginEnabled").prop('checked', data.isEnabled == "1");
    $('#addUser').hide();
    $('#updateUser').show();
    $('#userModal').modal('show');
    $("#updateUser").attr("update-id", data.id);
  });

  // Update User
  $('#updateUser').click(function(){
    var formData = $('#userForm').serialize() + "&action=updateUser&update_id=" + $(this).attr("update-id") + "&csrf_token=<?php echo $_SESSION['csrf_token']; ?>";
    $.ajax({
      url: "admin/process/admin_action.php",
      method: "POST",
      data: formData,
      dataType: "json",
      success: function(response) {
        if(response.success){
          LoadTable();
          $('#userModal').modal('hide');
          Swal.fire({
            title: 'Success!',
            text: response.message,
            icon: 'success',
            confirmButtonText: 'OK'
          });
        } else {
          Swal.fire({
            title: 'Error!',
            text: response.message,
            icon: 'error',
            confirmButtonText: 'OK'
          });
        }
      }
    });
  });
// Submit password update
$('#updatePassword').click(function() {
    var password = $("#user_password").val().trim();
    var con_password = $("#user_conpass").val().trim();
    var update_id = $(this).attr("update-id");

    if (password === "" || con_password === "") {
        Swal.fire("Error!", "Password fields cannot be empty.", "error");
        return;
    }

    if (password !== con_password) {
        Swal.fire("Error!", "Passwords do not match.", "error");
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to update this user's password?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, update it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "admin/process/admin_action.php",
                method: "POST",
                data: {
                    user_password: password,
                    user_conpass: con_password,
                    update_id: update_id,
                    action: "updateUserPassword",
                    csrf_token: '<?php echo $_SESSION["csrf_token"]; ?>' // CSRF Token added
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire("Success!", response.message, "success");
                        $('#passModal').modal('hide');
                        LoadTable(); // Refresh DataTable
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function() {
                    Swal.fire("Error!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    });
});

  // Delete User
  $('#userTable').on('click', 'button.btn-delete', function () {
    var data = table.row($(this).parents('tr')).data();
    var update_id = data.id;
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'No, cancel!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'admin/process/admin_action.php',
          method: 'POST',
          data: {
            action: 'deleteUser',
            update_id: update_id,
            csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
          },
          dataType: 'json',
          success: function(response) {
            if(response.success) {
              LoadTable();
              Swal.fire('Deleted!', response.message, 'success');
            } else {
              Swal.fire('Error!', response.message, 'error');
            }
          }
        });
      }
    });
  });
});
</script>
<?php } ?>
