<?php
  $categorys = getCategory($pdo);
?>
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
            <button class="btn btn-primary btn-sm float-end" id="addCategoryBTN" data-bs-toggle="modal" data-bs-target="#categoryModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Category</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="categoryTable" class="table table-hover table-cs-color">
        </table>
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
        <h1 class="modal-title fs-5" id="categoryModalLabel">Category</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
          <label for="mainCategory" class="form-label">Category Name</label>
          <input type="text" class="form-control" id="mainCategory" name="category_name" placeholder="Ex. Food">
          </div>
          <div class="col-lg-12">
          <label for="category_prefix" class="form-label">Prefix</label>
            <input type="text" class="form-control" id="category_prefix" name="category_prefix" placeholder="Ex. FD">
          </div>
          <div class="col-lg-12">
            <label for="subCategory" class="form-label">Parent Category</label>
            <select class="selectpicker form-control" id="subCategory" name="p_category_id" data-live-search="true">
            <option value="">None</option>
              <?php foreach ($categorys as $category):?>
                <option value="<?php echo $category['category_id'];?>"><?php echo $category['category_name'];?></option>
              <?php endforeach;?>
            </select>
          </div>
          
        </div>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="updateCategory" update-id="">UPDATE</button>
        <button type="button" class="btn btn-primary" id="addCategory">ADD</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->
<script>
  $(document).ready(function () {
    var table = $('#categoryTable').DataTable({
        responsive: true,
        select: true,
        autoWidth: false,
        ajax:{
          url: 'admin/process/table.php?table_type=category',
          dataSrc: 'data'
        },
        columns:[
          {data: 'category_id', visible: false},
          {data: 'category_name', title: 'Category Name'},
          {data: 'category_prefix', visible: false},
          {data: 'parent_category_id', visible: false},
          {data: 'parent_category_name', title: 'Parent Category Name'}
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_category') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_category') ){?>
          ,{"data": null,"className": "text-center", title: 'Action', "defaultContent": "<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_category')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_category')){ ?><button class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button><?php } ?>"}
          <?php } ?>
        ]
    });
    function LoadTable(){
        $.ajax({
            url: 'admin/process/table.php?table_type=category',
            dataType: 'json',
            success: function(data) {
              table.clear().rows.add(data.data).draw(false); // Update data without redrawing
            
              // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
              setTimeout(function() {
                  table.ajax.reload(null, false); // Reload the DataTable without resetting current page
              }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve categories.');
            }
        });
    }
    setInterval(LoadTable, 15000);
    $('.selectpicker').selectpicker();

    $('#addCategoryBTN').click(function(){
      $('#mainCategory').val('');
      $('#subCategory').val('');
      $('#subCategory').selectpicker('refresh');
      $('#addCategory').show();
      $('#updateCategory').hide();
    });
    

    $('#addCategory').click(function(){
        var formData = $('#categoryForm').serialize();
        //alert(formData);
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=addCategory",
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#categoryModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#updateCategory').click(function(){
      var formData = $('#categoryForm').serialize();
      var update_id = $(this).attr("update-id");
      $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData+"&action=updateCategory&update_id="+update_id,
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#categoryModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
    $('#categoryTable').on('click', 'button.btn-edit', function () {
      var data = table.row($(this).parents('tr')).data();
      // // Populate modal with data
      $('#mainCategory').val(data.category_name);
      $('#category_prefix').val(data.category_prefix);
      $('#subCategory').val(data.parent_category_id);
      $('#subCategory').selectpicker('refresh');
      $('#addCategory').hide();
      $('#updateCategory').show();
      $('#categoryModal').modal('show');
      // var update_id = $(this).attr("update-id");
      $("#updateCategory").attr("update-id", data.category_id);
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