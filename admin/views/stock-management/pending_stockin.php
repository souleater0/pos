<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_pending_stockin')){?>
<div class="body-wrapper-inner">
        <div class="container-fluid">
              <div class="card shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                  <div class="row">
                    <div class="col">
                    <h5 class="mt-1 mb-0">Pending Stock In History</h5>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                <table id="pendingProductTable" class="table table-hover table-cs-color">
                </table>
                </div>
        </div>
  </div>
</div>
<div class="modal fade modal-xl" id="pendingModal" tabindex="-1" aria-labelledby="pendingModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <form id="brandForm">
      <div class="modal-header">
      <div class="row align-items-center">
              <div class="col">
                <h5 class="mt-1 mb-0">Stock In Number</h5>
              </div>
              <div class="col">
                <input type="text" class="form-control bg-secondary-subtle" id="stockInNumber" value="" readonly>
              </div>
            </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <table id="itemDetailsTable" class="table table-hover table-cs-color">
        <tfoot>
        <tr>
            <th colspan="4"></th>
            <th colspan="1">Total:</th>
            <th></th>
            <th colspan="1">Average:</th>
            <th></th>
        </tr>
        </tfoot>
        </table>
      </div>
      <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'approve_pending_stockin')){?>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary" id="addInventory" update-id="">ADD TO INVENTORY</button>
        <button type="button" class="btn bg-secondary-subtle" id="stInventory" disabled>Already Added</button>
      </div>
      <?php } ?>
      </form>
    </div>
  </div>
</div>
<script>
$(document).ready( function () {
  $("#stInventory").hide();
  // let table = new DataTable('#myTable');
  var table = $('#pendingProductTable').DataTable({  
      responsive: true,
      select: true,
      autoWidth: false,
      order: [
        [1, "desc"]
      ],
      ajax:{
        url: 'admin/process/table.php?table_type=pending-stockin',
        dataSrc: 'data'
      },
      columns:[
        {data: 'id', visible: false },
        {data: 'series_number', title: 'Series Number'},
        {data: 'date', title: 'Date',className: 'text-start'},
        { 
                "data": "isAdded",
                "render": function(data, type, row, meta) {
                    var statusText;
                    var statusColor;
                    switch (data) {
                        case 0:
                            statusText = "Pending";
                            statusColor = "#FFAF61"; // Orange
                            break;
                        case 1:
                            statusText = "Already Added";
                            statusColor = "#58D68D"; // Green
                            break;
                        default:
                            statusText = "Pending";
                            statusColor = "#FFAF61"; // Orange
                            break;
                    }
                    return '<span class="badge text-white" style="background-color: ' + statusColor + ';">' + statusText + '</span>';
                },
                "title": "Status",
        }
        <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'show_pending_stockin') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_pending_stockin') ){?>
        ,{ 
            "data": null, 
            "title": "Action",
            "className":"text-center",
            "render": function(data, type, row) {
                return '<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'show_pending_stockin')){ ?><button class="btn btn-info btn-sm btn-show"><i class="fa-solid fa-eye"></i></button>&nbsp;<?php } ?><?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_pending_stockin')){ ?><button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button><?php } ?>';
            } 
        }
        <?php } ?>
      ]
  });
  function LoadTable(){
      $.ajax({
          url: 'admin/process/table.php?table_type=pending-stockin',
          dataType: 'json',
          success: function(data) {
            table.clear().rows.add(data.data).draw(false); // Update data without redrawing
          
            // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
            setTimeout(function() {
                table.ajax.reload(null, false); // Reload the DataTable without resetting current page
            }, 1000); // Adjust delay as needed
          },
          error: function () {
              alert('Failed to retrieve stocks.');
          }
      });
  }
  $('#pendingProductTable').on('click', 'button.btn-show', function () {
      var data = table.row($(this).parents('tr')).data();
      if(data.isAdded == 1){
        $("#addInventory").hide();
        $("#stInventory").show();
        
      }else{
        $("#addInventory").show();
        $("#stInventory").hide();
      }
      $("#stockInNumber").val(data.series_number);
      $("#addInventory").attr("update-id", data.series_number);
	  var currentdate = new Date();
		var datetime = "Now: " + currentdate.getDate() + "/"
					+ (currentdate.getMonth()+1)  + "/"
					+ currentdate.getFullYear() + " @ " 
					+ currentdate.getHours() + ":" 
					+ currentdate.getMinutes() + ":"
					+ currentdate.getSeconds();
      var itemDetailsTable = $('#itemDetailsTable').DataTable({
            destroy: true,
            responsive: true,
            autoWidth: false,
            order: [
              [3, "asc"]
            ],
            ajax: {
                url: 'admin/process/table.php?table_type=item-details&series_number=' + data.series_number,
                dataSrc: 'data'
            },
            columns: [
                { data: 'product_name', title: 'Product Name' },
                { data: 'item_barcode', title: 'Barcode' ,className: 'text-center' },
                { data: 'product_pp', title: 'Purchase Price' ,className: 'text-center' },
                { data: 'product_sp', title: 'Selling Price' ,className: 'text-center' },
                { data: 'quantity', title: 'Qty',className: 'text-center'},
                { data: 'total_cost', title: 'Total Cost',className: 'text-start'},
                { data: 'item_expiry', title: 'Expiry Date' ,className: 'text-start'},
                { data: 'created_at', title: 'Date Added',className: 'text-start'}
            ],
						        dom: 'Bfrtip',
        buttons: [
        {
            extend: 'copy',
			title: 'EC-Inventory-'+data.series_number,
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'csv',
			title: 'EC-Inventory-'+data.series_number,
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'excel',
			title: 'EC-Inventory-'+data.series_number,
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'pdf',
			title: 'EC-Inventory-'+data.series_number,
            exportOptions: {
            columns: ":visible:not(.noExport)"
                            }
            },
        {
            extend: 'print',
			title: 'EC-Inventory-'+data.series_number,
            exportOptions: {
            columns: ":visible:not(.noExport)"
            }
        }
    ]
    ,drawCallback: function () {
        // Compute the total for the 'Total Cost' column
        var columnData = this.api().column(5, { page: 'current' }).data();
        var total = columnData.reduce(function (a, b) {
            return a + parseFloat(b) || 0; // handle non-numeric values
        }, 0);

        // Calculate the average
        var average = total / columnData.length;

        // Set the computed total in the footer
        $('#itemDetailsTable tfoot th').eq(2).html(total.toFixed(2));

        // Set the computed average in the footer
        $('#itemDetailsTable tfoot th').eq(4).html(average.toFixed(2));
    }
        });
      $('#pendingModal').modal('show');
  });
  $('#addInventory').click(function(){
      var update_id = $(this).attr("update-id");
      //alert(update_id);    
      $.ajax({
            url: "admin/process/admin_action.php",
            method: "POST",
            data: {
              action: "addtoInventory",
              series_number: update_id
            },
            dataType: "json",
            success: function(response) {
                if(response.success==true){
                    LoadTable();
                    $('#pendingModal').modal('hide');
                    toastr.success(response.message);
                }else{
                    toastr.error(response.message);
                }
            }
        });
    });
  // addtoInventory
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