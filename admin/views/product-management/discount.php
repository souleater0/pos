<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_discount')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Discount</h5>
          </div>
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_discount')){?>
          <div class="col">
            <button class="btn btn-primary btn-sm float-end" id="addDiscountBTN" data-bs-toggle="modal" data-bs-target="#discountModal"><i class="fa-solid fa-plus"></i>&nbsp;Add Discount</button>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="card-body">
        <table id="discountTable" class="table table-hover table-cs-color"></table>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Discount -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="discountForm">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="discountModalLabel">Discount Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <div class="row gy-2">
          <div class="col-lg-12">
            <label for="discount_name" class="form-label">Discount Name</label>
            <input type="text" class="form-control" id="discount_name" name="discount_name" placeholder="Ex. 10% Off" required>
          </div>
          <div class="col-lg-12">
            <label for="discount_percentage" class="form-label">Discount Percentage</label>
            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" placeholder="Ex. 10" required>
          </div>          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addDiscount">ADD</button>
        <button type="button" class="btn btn-primary" id="updateDiscount" update-id="">UPDATE</button>
      </div>
    </form>
    </div>
  </div>
</div>
<!-- END -->

<script>
$(document).ready(function() {
  $('#discount_percentage').on('input', function() {
      var value = $(this).val();

      // Remove all non-numeric characters
      value = value.replace(/\D/g, '');

      // Prevent leading zeros by trimming them if the value is more than one digit
      if (value.length > 1 && value.startsWith('0')) {
          value = value.substring(1);
      }

      // If the value exceeds 100, set it to 100
      if (parseInt(value) > 100) {
          value = '100';
      }

      // Set the value back to the input field
      $(this).val(value);
  });
    var table = $('#discountTable').DataTable({
        responsive: true,
        select: true,
        autoWidth: false,
        ajax: {
            url: 'admin/process/table.php?table_type=discount',
            dataSrc: 'data'
        },
        columns: [
            { data: 'discount_id', visible: false },
            { data: 'discount_name', title: 'Discount Name', className: 'text-start'},
            { data: 'discount_percentage', title: 'Discount Percentage', className: 'text-start'},
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_discount') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_discount')){?>
            {
                data: null,
                className: "text-center",
                title: 'Action',
                defaultContent: `<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_discount')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_discount')){ ?><button class='btn btn-danger btn-sm btn-delete'><i class='fa-solid fa-trash'></i></button><?php } ?>`
            }
            <?php } ?>
        ]
    });

    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

    function LoadTable() {
        $.ajax({
            url: 'admin/process/table.php?table_type=discount',
            dataType: 'json',
            success: function(data) {
                table.clear().rows.add(data.data).draw(false);
                setTimeout(function() {
                    table.ajax.reload(null, false);
                }, 1000);
            },
            error: function () {
                swal.fire('Error', 'Failed to retrieve discounts.', 'error');
            }
        });
    }

    $('#discountForm').on('submit', function(event) {
        event.preventDefault();
    });

    $('#addDiscountBTN').click(function() {
        $('#discount_name').val('');
        $('#discount_percentage').val('');
        $('#addDiscount').show();
        $('#updateDiscount').hide();
    });

    $('#addDiscount').click(function() {
        var formData = $('#discountForm').serialize() + "&action=addDiscount&csrf_token=" + csrfToken;
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    LoadTable();
                    $('#discountModal').modal('hide');
                    swal.fire('Success', response.message, 'success');
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    $('#updateDiscount').click(function() {
        var formData = $('#discountForm').serialize() + "&action=updateDiscount&update_id=" + $(this).attr("update-id") + "&csrf_token=" + csrfToken;
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    LoadTable();
                    $('#discountModal').modal('hide');
                    swal.fire('Success', response.message, 'success');
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            }
        });
    });

    $('#discountTable').on('click', 'button.btn-edit', function() {
        var data = table.row($(this).parents('tr')).data();
        $('#discount_name').val(data.discount_name);
        $('#discount_percentage').val(data.discount_percentage);
        $('#addDiscount').hide();
        $('#updateDiscount').show();
        $('#discountModal').modal('show');
        $("#updateDiscount").attr("update-id", data.discount_id);
    });

    $('#discountTable').on('click', 'button.btn-delete', function() {
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
                    data: { action: 'deleteDiscount', discount_id: data.discount_id, csrf_token: csrfToken },
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
