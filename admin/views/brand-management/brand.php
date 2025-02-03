<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_brand')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Brand</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_brand')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addBrandBTN" data-bs-toggle="modal" data-bs-target="#brandModal"><i class="fa-solid fa-plus"></i>&nbsp;Add
              Brand</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="brandTable" class="table table-hover table-cs-color">
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="brandModal" tabindex="-1" aria-labelledby="brandModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="brandForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="brandModalLabel">Brand Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
          <label for="brand_name" class="form-label">Brand Name</label>
          <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Ex. Nescafe">
          </div>          
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="updateBrand" update-id="">UPDATE</button>
        <button type="button" class="btn btn-primary" id="addBrand">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->
<script>
  $(document).ready( function () {
    // let table = new DataTable('#myTable');
  var table = $('#brandTable').DataTable({
        responsive: true,
        select: true,
        autoWidth: false,
        ajax:{
          url: 'admin/process/table.php?table_type=brand',
          dataSrc: 'data'
        },
        columns:[
          {data: 'brand_id', visible: false},
          {data: 'brand_name', title: 'Brand Name'}
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_brand') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_brand') ){?>
          ,{"data": null,"className": "text-center", title: 'Action', "defaultContent": "<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_brand')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_brand')){ ?><button class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button><?php } ?>"}
          <?php } ?>
        ]
    });
    function LoadTable(){
        $.ajax({
            url: 'admin/process/table.php?table_type=brand',
            dataType: 'json',
            success: function(data) {
              table.clear().rows.add(data.data).draw(false); // Update data without redrawing
            
              // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
              setTimeout(function() {
                  table.ajax.reload(null, false); // Reload the DataTable without resetting current page
              }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve brands.');
            }
        });
    }
    $('#brandForm').on('submit', function(event){
      event.preventDefault();
    });
    $('#addBrandBTN').click(function(){
      $('#brand_name').val('');
      $('#addBrand').show();
      $('#updateBrand').hide();
    });
    $('#addBrand').click(function(){
        var formData = $('#brandForm').serialize();
        //alert(formData);
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=addBrand",
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#brandModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#updateBrand').click(function(){
      var formData = $('#brandForm').serialize();
      var update_id = $(this).attr("update-id");
      $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=updateBrand&update_id="+update_id,
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#brandModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#brandTable').on('click', 'button.btn-edit', function () {
      var data = table.row($(this).parents('tr')).data();
      // // Populate modal with data
      $('#brand_name').val(data.brand_name);
      $('#addBrand').hide();
      $('#updateBrand').show();
      $('#brandModal').modal('show');
      // var update_id = $(this).attr("update-id");
      $("#updateBrand").attr("update-id", data.brand_id);
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
}
?>