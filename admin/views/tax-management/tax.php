<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_tax')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Tax</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_tax')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addTaxBTN" data-bs-toggle="modal" data-bs-target="#taxModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Tax</button>
          </div>
          <?php } ?>
        </div>

      </div>
      <div class="card-body">
        <table id="taxTable" class="table table-hover table-cs-color">
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="taxModal" tabindex="-1" aria-labelledby="taxModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="taxForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="taxModalLabel">Tax Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-6">
          <label for="tax_name" class="form-label">Tax Type</label>
          <input type="text" class="form-control" id="tax_name" name="tax_name" placeholder="Ex. 1% Tax">
          </div>
          <div class="col-lg-6">
            <label for="tax_percentage" class="form-label">Tax Percentage</label>
            <div class="input-group">
              <input type="text" class="form-control" id="tax_percentage" name="tax_percentage" placeholder="1" pattern="[0-9]*">
              <div class="input-group-prepend">
                <div class="input-group-text rounded-0 ">%</div>
              </div>
            </div>
          </div>           
        </div>      
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="updateTax" update-id="">UPDATE</button>
        <button type="button" class="btn btn-primary" id="addTax">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->
<script>
  $('#tax_percentage').on('input', function(){
      $(this).val($(this).val().replace(/\D/g,''));
  });
$(document).ready( function () {
    var table = $('#taxTable').DataTable({
        responsive: true,
        select: true,
        order: [[2, 'asc']],
        autoWidth: false,
        ajax:{
          url: 'admin/process/table.php?table_type=tax',
          dataSrc: 'data'
        },
        columns:[
          {data: 'tax_id', visible: false},
          {data: 'tax_name', title: 'Tax Type'},
          {data: 'tax_percentage', title: 'Percentage', className: 'text-center'}
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_tax') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_tax') ){?>
          ,{"data": null,"className": "text-center", title: 'Action', "defaultContent": "<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_tax')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_tax')){ ?><button class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button><?php } ?>"}
          <?php } ?>
        ]
    });
    function LoadTable(){
        $.ajax({
            url: 'admin/process/table.php?table_type=tax',
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
    $('#addTaxBTN').click(function(){
      $('#tax_name').val('');
      $('#tax_percentage').val('');
      $('#addTax').show();
      $('#updateTax').hide();
    });

    $('#addTax').click(function(){
        var formData = $('#taxForm').serialize();
        //alert(formData);
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=addTax",
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#taxModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#updateTax').click(function(){
      var formData = $('#taxForm').serialize();
      var update_id = $(this).attr("update-id");
      $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=updateTax&update_id="+update_id,
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#taxModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#taxTable').on('click', 'button.btn-edit', function () {
      var data = table.row($(this).parents('tr')).data();
      // // Populate modal with data
      $('#tax_name').val(data.tax_name);
      $('#tax_percentage').val(data.tax_percentage);
      $('#addTax').hide();
      $('#updateTax').show();
      $('#taxModal').modal('show');

      $("#updateTax").attr("update-id", data.tax_id);
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