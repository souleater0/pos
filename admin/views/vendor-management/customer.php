<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_customer')){?>
<div class="body-wrapper-inner">
    <div class="container-fluid mw-100">
        <div class="card shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <div class="row">
                    <div class="col">
                        <h5 class="mt-1 mb-0">Manage Customer</h5>
                    </div>
                    <div class="col">
                        <button class="btn btn-sm float-end" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <iconify-icon icon="rivet-icons:filter" width="20" height="20"></iconify-icon>
                        </button>
                        <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_customer')){?>
                        <button class="btn btn-primary btn-sm float-end" id="addCustomerBTN" data-bs-toggle="modal" data-bs-target="#customerModal">
                            <i class="fa-solid fa-plus"></i>&nbsp;Add Customer
                        </button>
                        <?php } ?>
                        <ul class="dropdown-menu p-3">
                            <div class="fw-bolder text-dark">Hide Column:</div>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input toggle-vis" type="checkbox" value="" id="flexCheckDefault0" data-column="0">
                                    <label class="form-check-label text-dark" for="flexCheckDefault0">ID</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input toggle-vis" type="checkbox" value="" id="flexCheckDefault1" data-column="1" checked>
                                    <label class="form-check-label text-dark" for="flexCheckDefault1">Customer</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input toggle-vis" type="checkbox" value="" id="flexCheckDefault2" data-column="2" checked>
                                    <label class="form-check-label text-dark" for="flexCheckDefault2">Email</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input toggle-vis" type="checkbox" value="" id="flexCheckDefault3" data-column="3" checked>
                                    <label class="form-check-label text-dark" for="flexCheckDefault3">Company</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input toggle-vis" type="checkbox" value="" id="flexCheckDefault4" data-column="4" checked>
                                    <label class="form-check-label text-dark" for="flexCheckDefault4">Phone No</label>
                                </div>
                            </li>
                            <li>
                                <div class="form-check">
                                    <input class="form-check-input toggle-vis" type="checkbox" value="" id="flexCheckDefault5" data-column="5">
                                    <label class="form-check-label text-dark" for="flexCheckDefault5">Date Created</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="customerTable" class="table table-hover table-cs-color">
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="customerForm">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="customerModalLabel">Customer Information</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body border">
          <div class="row gy-2">
            <div class="col-lg-12">
              <label for="customer_name" class="form-label">Customer Name</label>
              <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Ex. John Doe" required>
            </div>
            <div class="col-lg-12">
              <label for="customer_email" class="form-label">Customer Email</label>
              <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="exmaple@gmail.com" required>
            </div>
            <div class="col-lg-12">
              <label for="company_name" class="form-label">Company Name (Optional)</label>
              <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Ex. Company A">
            </div>
            <div class="col-lg-12">
              <label for="customer_phone_no" class="form-label">Phone Number</label>
              <input type="tel" class="form-control" id="customer_phone_no" name="customer_phone_no" placeholder="e.g., 09123456789" minlength="11" maxlength="11">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="updateCustomer" update-id="">UPDATE</button>
          <button type="button" class="btn btn-primary" id="addCustomer">ADD</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- END -->
<script>
$(document).ready(function () {
    var table = $('#customerTable').DataTable({
        responsive: true,
        select: true,
        autoWidth: false,
        ajax: {
            url: 'admin/process/table.php?table_type=customer-list',
            dataSrc: 'data'
        },
        columns: [
            {data: 'id', visible: false},
            {data: 'customer_name', title: 'Customer Name', className: 'text-start'},
            {data: 'customer_email', title: 'Email', className: 'text-start',
                render: function(data, type, row, meta) {
                    if (data) {
                        return `${data} <a href="mailto:${data}">
                                    <iconify-icon icon="ic:outline-email" width="20" height="20"></iconify-icon>
                                </a>`;
                    } else {
                        return ''; // Handle case when email is not available
                    }
                }
            },
            {data: 'company_name', title: 'Company Name', className: 'text-start'},
            {data: 'customer_phone_no', title: 'Phone Number', className: 'text-start'},
            {
                "data": "created_at", 
                "title": "Date Created", 
                "className": "text-start"
            }
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_customer') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_customer') ){?>
            ,{"data": null,"className": "text-center noExport", title: 'Action', "defaultContent": "<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_customer')){ ?><button class='btn btn-primary btn-sm btn-edit'><i class='fa-regular fa-pen-to-square'></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_customer')){ ?><button class='btn btn-danger btn-sm'><i class='fa-solid fa-trash'></i></button><?php } ?>"}
            <?php } ?>
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                title: 'Customer List',
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'csv',
                title: 'Customer List',
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'excel',
                title: 'Customer List',
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'pdf',
                title: 'Customer List',
                exportOptions: { columns: ":visible:not(.noExport)" }
            },
            {
                extend: 'print',
                title: 'Customer List',
                exportOptions: { columns: ":visible:not(.noExport)" }
            }
        ]
    });

    function updateColumnVisibility() {
        $('input.toggle-vis').each(function() {
            var column = table.column($(this).data('column'));
            column.visible($(this).is(':checked'));
        });
    }

    // Initial column visibility update based on checkbox states
    updateColumnVisibility();

    // Attach event listener for the checkbox state change
    $('input.toggle-vis').on('change', function() {
        updateColumnVisibility();
    });

    function LoadTable() {
        $.ajax({
            url: 'admin/process/table.php?table_type=customer-list',
            dataType: 'json',
            success: function(data) {
                table.clear().rows.add(data.data).draw(false); // Update data without redrawing

                // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
                setTimeout(function() {
                    table.ajax.reload(null, false); // Reload the DataTable without resetting current page
                }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve customer list.');
            }
        });
    }

    $('#addCustomer').click(function() {
        var formData = $('#customerForm').serialize();
        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData + "&action=addCustomer",
            dataType: "json",
            success: function(response) {
                if (response.success == true) {
                    LoadTable();
                    $('#customerModal').modal('hide');
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    $('#customerTable').on('click', 'button.btn-edit', function() {
    var data = table.row($(this).parents('tr')).data();
    
    // Populate modal with the data
    $('#customer_name').val(data.customer_name);
    $('#customer_email').val(data.customer_email);
    $('#company_name').val(data.company_name);
    $('#customer_phone_no').val(data.customer_phone_no);

    // Show the update button and hide the add button
    $('#updateCustomer').show();
    $('#addCustomer').hide();
    
    // Open the modal
    $('#customerModal').modal('show');
    
    // Set the data-id for the update button
    $("#updateCustomer").attr("update-id", data.id);
});


    $('#addCustomerBTN').click(function() {
        $('#addCustomer').show();
        $('#updateCustomer').hide();
    });

    $('#updateCustomer').click(function() {
        var formData = $('#customerForm').serialize();
        var update_id = $(this).attr("update-id");

        $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: formData + "&action=updateCustomer&update_id=" + update_id,
            dataType: "json",
            success: function(response) {
                if (response.success == true) {
                    LoadTable();
                    $('#customerModal').modal('hide');
                    toastr.success(response.message);
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
}
?>