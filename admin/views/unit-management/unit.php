<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_units')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Unit</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_units')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addUnitBTN" data-bs-toggle="modal" data-bs-target="#unitModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Unit</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="unitTable" class="table table-hover table-cs-color">
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="unitForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="unitModalLabel">Unit Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
          <label for="unit_name" class="form-label">Unit Name</label>
          <input type="text" class="form-control" id="unit_name" name="unit_name" placeholder="Ex. Pieces">
          </div>          
        </div>
        <div class="row gy-2">
          <div class="col-lg-12">
          <label for="unit_prefix" class="form-label">Prefix</label>
          <input type="text" class="form-control" id="unit_prefix" name="unit_prefix" placeholder="Ex. pcs">
          </div>          
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="updateUnit" update-id="">UPDATE</button>
        <button type="button" class="btn btn-primary" id="addUnit">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->
<script>
  $(document).ready( function () {
    var table = $('#unitTable').DataTable({
        responsive: true,
        select: true,
        autoWidth: false,
        ajax:{
          url: 'admin/process/table.php?table_type=unit',
          dataSrc: 'data'
        },
        columns:[
          {data: 'unit_id', visible: false},
          {data: 'unit_type', title: 'Unit Name'},
          {data: 'short_name', title: 'Prefix'}
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_units') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_units') ){?>
          ,{"data": null,"className": "text-center", title: 'Action', "defaultContent": "<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_units')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_units')){ ?><button class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button><?php } ?>"}
          <?php } ?>
        ]
    });
  function LoadTable(){
        $.ajax({
            url: 'admin/process/table.php?table_type=unit',
            dataType: 'json',
            success: function(data) {
              table.clear().rows.add(data.data).draw(false); // Update data without redrawing
            
              // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
              setTimeout(function() {
                  table.ajax.reload(null, false); // Reload the DataTable without resetting current page
              }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve units.');
            }
        });
  }
  $('#addUnitBTN').click(function(){
      $('#unit_name').val('');
      $('#unit_prefix').val('');
      $('#addUnit').show();
      $('#updateUnit').hide();
  });
  $('#addUnit').click(function(){
        var formData = $('#unitForm').serialize();
        //alert(formData);
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=addUnit",
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#unitModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#updateUnit').click(function(){
      var formData = $('#unitForm').serialize();
      var update_id = $(this).attr("update-id");
      $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=updateUnit&update_id="+update_id,
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#unitModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#unitTable').on('click', 'button.btn-edit', function () {
      var data = table.row($(this).parents('tr')).data();
      // // Populate modal with data
      $('#unit_name').val(data.unit_type);
      $('#unit_prefix').val(data.short_name);
      $('#addUnit').hide();
      $('#updateUnit').show();
      $('#unitModal').modal('show');

      $("#updateUnit").attr("update-id", data.unit_id);
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