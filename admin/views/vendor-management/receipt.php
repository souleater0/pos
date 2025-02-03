<?php 
$productlists = getProductList($pdo);
$supplierlists = getSupplierList($pdo);
$customerlists = getCustomerList($pdo);
$paymentaccountlists = getPaymentAccount($pdo);
$payment_ref = generatePaymentReference($pdo);
$taxes = getTaxs($pdo);
$selectProduct = '';
foreach ($productlists as $productlist) {
  $selectProduct .= '<option value="' . htmlspecialchars($productlist['product_name']) . '" data-sku="' . htmlspecialchars($productlist['product_sku']) . '" data-rate-purchase="' . htmlspecialchars($productlist['product_pp']) . '" data-rate-sell="' . (floatval($productlist['product_sp_with_tax']) == 0.00 ? htmlspecialchars($productlist['product_pp'] * 1.3 + 2) : htmlspecialchars($productlist['product_sp_with_tax'])) . '">'.htmlspecialchars($productlist['product_name']) . '</option>';
}
?>
<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_transaction')){?>
<div class="body-wrapper-inner">
  <div class="container-fluid mw-100">
    <div class="row py-3">
      <div class="col">
        <h3>Transaction</h3>
      </div>
      <div class="col">
        <div class="dropdown float-end">
          <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'pay_bills')){?>
          <button class="btn btn-light btn btn-outline-dark" id="payBillbtn" type="button" data-bs-toggle="modal" data-bs-target="#payModal">Pay Bills</button>
          <?php } ?>
          <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            New Transaction
          </button>
          <ul class="dropdown-menu">
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_bill')){?>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transactionModal" data-form="bill">Bill</a></li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_expense')){?>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transactionModal" data-form="expense">Expense</a></li>
            <?php } ?>
            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_invoice')){?>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transactionModal" data-form="invoice">Invoice</a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Transaction</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table id="transactionTable" class="table table-hover table-cs-color"></table>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="transactionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="transactionModalLabel">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5 fw-bolder" id="transactionModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
        <!-- Placeholder for dynamic form -->
        <div id="dynamicFormContent">
          <!-- Bill form -->
          <form id="billForm" class="dynamic-form" style="display:none;">
            <div class="row">
              <div class="col-2">
                <label for="supplier" class="form-label">Supplier</label >
                <select class="selectpicker form-control" id="bill_supplier" name="bill_supplier" data-live-search="true" required>
                  <?php foreach ($supplierlists as $suplierlist):?>
                    <option value="<?php echo $suplierlist['id'];?>"><?php echo $suplierlist['vendor_name'];?></option>
                  <?php endforeach;?>
                </select>
              </div>
              <div class="row py-3">
                <div class="col-2">
                  <div>
                    <label for="exampleFormControlTextarea1" class="form-label">Mailing Address</label>
                    <textarea class="form-control" id="bill_address" name="bill_address" rows="3"></textarea>
                  </div>
                </div>
                <div class="col-2">
                  <div>
                  <label for="bill_start_date" class="form-label">Bill Date</label>
                  <input id="bill_start_date" name="bill_start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" type="date" required/>
                  </div>
                </div>
                <div class="col-2">
                  <div>
                  <label for="bill_end_date" class="form-label">Due Date</label>
                  <input id="bill_end_date" name="bill_end_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" type="date" required/>
                  </div>
                </div>
                <div class="col-2">
                  <div>
                    <label for="billNo" class="form-label">Bill No.</label>
                    <input type="text" class="form-control" id="billNo" required>
                  </div>
                </div>
              </div>
            </div>
            </form>
            <form id="expenseForm" class="dynamic-form" style="display:none;">
              <div class="row mb-3">
                <div class="col-2">
                  <label for="supplier" class="form-label">Payee</label >
                  <select class="selectpicker form-control" id="expense_supplier" name="expense_supplier" data-live-search="true">
                    <?php foreach ($supplierlists as $suplierlist):?>
                      <option value="<?php echo $suplierlist['id'];?>"><?php echo $suplierlist['vendor_name'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-2">
                    <div>
                    <label for="expense_date" class="form-label">Payment Date</label>
                    <input id="expense_date" name="expense_date" class="form-control" type="date" />
                    </div>
                </div>
                <div class="col-2">
                  <label for="expense_payment" class="form-label">Payment Method</label >
                  <select class="selectpicker form-control" id="expense_payment" name="expense_payment" data-live-search="true">
                    <option value="CASH">CASH</option>
                    <option value="GCASH">GCASH</option>
                    <option value="CREDIT CARD">CREDIT CARD</option>
                  </select>
                </div>
                <div class="col-2">
                  <div>
                    <label for="expense_ref_no" class="form-label">Ref No.</label>
                    <input type="text" class="form-control" id="expense_ref_no" name="expense_ref_no">
                  </div>
                </div>
              </div>
            </form>
            <form id="invoiceForm" class="dynamic-form" style="display:none;">
              <div class="row">
                <div class="col-6">
                  <div class="row mb-3">
                  <div class="col-3">
                      <label for="invoice_customer" class="form-label">Customer</label >
                      <select class="select-customer selectpicker form-control" id="invoice_customer" name="invoice_customer" data-live-search="true" required>
                        <?php foreach ($customerlists as $customerlist):?>
                          <option value="<?php echo $customerlist['id'];?>" data-customer-email="<?php echo $customerlist['customer_email'];?>"><?php echo $customerlist['customer_name'];?></option>
                        <?php endforeach;?>
                      </select>
                  </div>
                  <div class="col-3">
                      <label for="invoice_customer_email" class="form-label">Customer Email</label>
                      <input type="text" class="form-control" id="invoice_customer_email" required>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-3">
                    <label for="invoice_bill_address" class="form-label">Billing Address</label>
                    <textarea class="form-control" id="invoice_bill_address" name="invoice_bill_address" rows="3"></textarea>
                  </div>
                  <div class="col-3">
                    <label for="invoice_date" class="form-label">Invoice Date</label>
                    <input id="invoice_date" name="invoice_date" class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>"/>
                  </div>
                  <div class="col-3">
                    <label for="invoice_duedate" class="form-label">Due Date</label>
                    <input id="invoice_duedate" name="invoice_duedate" class="form-control" type="date" value="<?php echo date('Y-m-d', strtotime('+1 days')); ?>"/>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-3">
                    <label for="invoice_ship_address" class="form-label">Shipping to</label>
                    <textarea class="form-control" id="invoice_ship_address" name="invoice_ship_address" rows="3"></textarea>
                  </div>
                  <div class="col-3">
                      <label for="invoice_via" class="form-label">Ship Via</label>
                      <input type="text" class="form-control" id="invoice_via" required>
                  </div>
                  <div class="col-3">
                    <label for="invoice_ship_date" class="form-label">Shipping Date</label>
                    <input id="invoice_ship_date" name="invoice_ship_date" class="form-control" type="date" value="<?php echo date('Y-m-d', strtotime('+3 days')); ?>" />
                  </div>
                  <div class="col-3">
                      <label for="invoice_track_no" class="form-label">Tracking No.</label>
                      <input type="text" class="form-control" id="invoice_track_no" required>
                  </div>
                </div>
                </div>
              </div>
            </form>
            <!-- Table for items -->
             <!-- Dropdown for Tax Options -->
          <select id="taxOption">
            <option value="1">Exclusive of Tax</option>
            <option value="2">Inclusive of Tax</option>
            <option value="3" selected>Out of Scope of Tax</option>
          </select>
            <table id="editableTable" class="table table-hover table-bordered w-100">
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>SKU</th>
                  <th>Barcode</th>
                  <th>Expiry Date</th>
                  <th>Qty</th>
                  <th>Rate</th>
                  <th>Amount</th>
                  <th>Tax</th>
                  <th>Customer</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot>
              <tr>
                <td colspan="9" class="text-dark text-end fw-bolder">Sub Total:</td>
                <td id="lblSubAmount" class="text-dark fw-bold" data-sub-total="0.00">0.00</td>
              </tr>
              <tr>
                <td colspan="9" class="text-dark text-end fw-bolder">Tax:</td>
                <td id="lblTaxAmount" class="text-dark fw-bold" data-total-tax="0.00">0.00</td>
              </tr>
              <tr>
                <td colspan="9" class="text-dark text-end fw-bolder">Total:</td>
                <td id="lblAmount" class="text-dark fw-bold" data-grand-total="0.00">0.00</td>
              </tr>
              <input type="hidden" id="totalSubAmount" value="0">
              <input type="hidden" id="totalTaxAmount" value="0">
              <input type="hidden" id="totalAmount" value="0">

            </tfoot>
            </table>
            <!-- Buttons to add and clear rows -->
            <button id="addRow" type="button" class="btn btn-success btn-sm">Add Row</button>
            <button id="clearRows" type="button" class="btn btn-danger btn-sm">Clear All</button>

            <div class="row">
              <div class="col-2">
                <div>
                  <label for="attach_Remarks" class="form-label">Remarks</label>
                  <textarea class="form-control" id="attach_Remarks" rows="3"></textarea>
                </div>
              </div>
              <div class="col-3">
                <label for="attach_File" class="form-label">Attachment</label>
                <input class="attachment_file" type="file" id="attach_File"/>
              </div>
              <div class="col-3">
                <span class="text-dark fw-bold">Current Attachment</span>
                <div id="file_attached" class="container">
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
        <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'create_transaction')){?>
        <button type="button" class="btn btn-primary" id="submitForm">Save & Close</button>
        <?php } ?>
        <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'update_transaction')){?>
        <button type="button" class="btn btn-danger" id="updateForm">Save & Close</button>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<!-- Modal END-->

<!-- Modal START-->
<div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="payModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="payModalLabel">Pay Bills</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body border">
      <div class="row">
        <div class="d-flex">
          <div class="col-3 px-2">
          <label for="payment_account" class="form-label">Payment Accounts</label >
          <select class="selectpicker form-control" id="payment_account" name="payment_account" data-live-search="true" required>
            <?php foreach ($paymentaccountlists as $accountlist):?>
              <option value="<?php echo $accountlist['id'];?>"><?php echo $accountlist['pay_account_name'];?></option>
            <?php endforeach;?>
          </select>
          </div>
          <div class="col-1 px-2">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input id="payment_date" name="payment_date" class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>"/>
          </div>
          <div class="col-1 px-2">
            <label for="payment_ref_no" class="form-label">Ref No.</label>
            <input type="text" class="form-control" id="payment_ref_no" readonly>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-12">
          <table id="payBillsTable" class="table table-hover table-bordered w-100">
          </table>
        </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="savePayBill" class="btn btn-primary">Save Changes & Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal END-->
<script>
$(document).ready(function() {
  // Register FilePond plugins
  FilePond.registerPlugin(FilePondPluginImagePreview);
  FilePond.registerPlugin(FilePondPluginFileValidateType);
  // Initialize FilePond
  $('.attachment_file').filepond({
    allowMultiple: true,                // Allow multiple file uploads
    maxFiles: 20,                       // Max files limit
    imagePreviewHeight: 100,            // Set the preview height for images (optional)
    imagePreviewMaxHeight: 200,        // Maximum height of the image preview (optional)
    imagePreviewMaxWidth: 200,         // Maximum width of the image preview (optional)
    allowImagePreview: true,           // Allow image preview (enabled by default)
    allowFileTypeValidation: true,     // Allow file type validation
    acceptedFileTypes: ['image/jpeg', 'image/png', 'application/pdf'], // Allow only JPG, PNG, and PDF
    fileValidateTypeDetectType: true,  // Automatically detect file types
    labelFileTypeNotAllowed: 'This file type is not allowed', // Custom error message
  });


  // Manually set the 'accept' attribute after FilePond initializes
  $('.attachment_file').attr('accept', 'image/jpeg', 'image/png','application/pdf');

  // Listen for addfile event
  $('.attachment_file').on('FilePond:addfile', function (e) {
      console.log('file added event', e);
  });

  $('#payBillsTable').on('input', '.payment-input', function() {
        let value = $(this).val().replace(/\D/g, ''); // Remove non-digit characters
        let maxPay = $(this).attr('max'); // Get the max attribute value
        value = Math.max(1, Math.min(maxPay, value)); // Ensure the value is between 1 and maxQty
        $(this).val(value);
  });
  $('#savePayBill').on('click', function() {
    var paymentRefNo = $('#payment_ref_no').val();
    var paymentAccount = $('#payment_account').val();
    var paymentDate = $('#payment_date').val();
    var paymentAmounts = [];

    // Collect payment amounts from the table inputs
    $('.payment-input').each(function() {
        var amount = $(this).val();
        if (amount) {
            paymentAmounts.push({
                transaction_no: $(this).closest('tr').find('td').eq(1).text(), // Assuming transaction number is in second column
                amount: amount
            });
        }
    });
    $.ajax({
        url: 'admin/process/admin_action.php', // PHP script to handle the transaction
        type: 'POST',
        data: {
            action: 'createPaymentTransaction', // Your custom action name
            payment_ref_no: paymentRefNo,
            payment_account: paymentAccount,
            payment_date: paymentDate,
            payment_amounts: paymentAmounts
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                  title: "Payment Successful!",
                  text: "",
                  icon: "success"
                });
                // Optionally, close the modal and refresh the table
                $('#payModal').modal('hide');
                tablePaymentList.ajax.reload(); // Reload the table to reflect the changes
            } else {
              Swal.fire({
                  title: "Payment Failed!",
                  text: response.message,
                  icon: "error"
                });
                // alert('Error: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('An error occurred while creating the payment transaction.');
        }
    });
    console.log(paymentAmounts);
  });

  var tablePaymentList = $('#payBillsTable').DataTable({
    order: [[0, 'desc']], // Default sorting on the first column in descending order
    paging: true, // Enable pagination
    scrollCollapse: true, // Allow scroll to collapse
    scrollX: true, // Enable horizontal scrolling
    scrollY: true, // Enable vertical scrolling
    responsive: true, // Make the table responsive
    autoWidth: false, // Disable auto width calculation
    ajax: {
        url: 'admin/process/table.php?table_type=paybill-table', // AJAX URL
        dataSrc: 'data', // Assuming your response returns an object with a 'data' key
        error: function(xhr, error, thrown) {
            console.error('Error fetching data: ', error);
            alert('Error fetching data. Please try again.');
        }
    },
    columns: [
        { data: 'payee', title: 'PAYEE', className:'text-dark'},
        { data: 'transaction_no', title: 'TRANSACTION NO.', className:'text-dark' },
        { data: 'ref_no', title: 'REF NO.', className:'text-dark' },
        { 
            data: 'due_date', 
            title: 'Due Date', 
            className: 'text-dark', 
            render: function(data, type, row) {
                const today = new Date();
                const dueDate = new Date(data);
                const diffTime = dueDate - today;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays < 0) {
                    return `<span>${data} <span class="badge bg-danger">Overdue</span></span>`;
                } else if (diffDays === 0) {
                    return `<span>${data} <span class="badge bg-warning">Due Today</span></span>`;
                } else {
                    return `<span>${data} <span class="badge bg-success">${diffDays} Days Remaining</span></span>`;
                }
            } 
        },
        { data: 'bill_amount', title: 'BILL AMOUNT', className:'text-dark' },
        { data: 'total_payments', title: 'PAID AMOUNT', className:'text-dark' },
        { data: 'open_balance', title: 'OPEN BALANCE', className:'text-dark' },
        {
            data: null, // Make sure this matches the key in your response
            title: 'PAYMENT',
            render: function(data, type, row) {
                return `<input type="number" class="form-control payment-input" name="payment[]" min="1" max="${data.open_balance}" />`;
            }
        }
    ],
    drawCallback: function(settings) {
        // Optional: If you need to do something after the table is drawn (e.g., handle event listeners for the inputs)
        $('.payment-input').on('change', function() {
            var paymentValue = $(this).val();
            var rowIndex = $(this).closest('tr').index();
            console.log('Payment value changed for row ' + rowIndex + ': ' + paymentValue);
            // You can do further processing here like updating the total or making an AJAX call to save the value.
        });
    }
  });
  
  $('#payModal').on('shown.bs.modal', function () {
    $('#payBillsTable').DataTable().columns.adjust().draw();
  });

  $('#payBillbtn').on('click', function() {
    $.ajax({
            url: 'admin/process/admin_action.php',
            type: 'POST',
            data: { action: 'generatePaymentReference' },
            success: function(response) {
                if (response.success) {
                    // Update the input field with the new reference
                    $('#payment_ref_no').val(response.reference);
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while generating the payment reference.');
            }
        });
  });
  var tableTransaction = $('#transactionTable').DataTable({
    order: [[0, 'desc']],
    paging: true,
    scrollCollapse: true,
    scrollX: true,
    scrollY: true,
    responsive: true,
    searching: true,
    autoWidth: false,
    ajax: {
        url: 'admin/process/table.php?table_type=transaction-list',
        dataSrc: 'data'
    },
    columns: [
        { data: 'Date', title: 'Date', className: 'text-start' },
        {
            data: 'Type',
            title: 'Type',
            render: function (data, type, row) {
                if (data) {
                    // Capitalize the first letter
                    return data.charAt(0).toUpperCase() + data.slice(1);
                }
                return data;
            }
        },
        { data: 'Transaction No', title: 'Transaction No' },
        { data: 'No', title: 'Receipt No' },
        { data: 'Payee', title: 'Payee' },
        { data: 'Total Before Sales', title: 'Total Before Sales' },
        { data: 'Sales Tax', title: 'Sales Tax' },
        { data: 'Total', title: 'Total' }
        <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'view_transaction') || userHasPermission($pdo, $_SESSION["user_id"], 'void_transaction') || userHasPermission($pdo, $_SESSION["user_id"], 'delete_transaction')){ ?>
    ,{
        title: "Actions",
        data: 'Type', // Use 'Type' to determine which buttons to display
        render: function (data, type, row) {
            let actions = '';

            <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'view_transaction')){ ?>
                actions += `<button type="button" class="btn btn-primary btn-sm btn-view">View</button>`;
            <?php } ?>

            if (data === 'bill') {
                <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_transaction')){ ?>
                    actions += `<button type="button" class="btn btn-danger btn-sm btn-delete">Delete</button>`;
                <?php } ?>
            } else if (data === 'expense' || data === 'invoice') {
                <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'void_transaction')){ ?>
                    actions += `<button type="button" class="btn btn-danger btn-sm btn-void">Void</button>`;
                <?php } ?>
                <?php if(userHasPermission($pdo, $_SESSION["user_id"], 'delete_transaction')){ ?>
                    actions += `<button type="button" class="btn btn-danger btn-sm btn-delete">Delete</button>`;
                <?php } ?>
            }

            return actions;
        }
    }
<?php } ?>
    ]
});

function LoadTransactionTable(){
        $.ajax({
            url: 'admin/process/table.php?table_type=transaction-list',
            dataType: 'json',
            success: function(data) {
              tableTransaction.clear().rows.add(data.data).draw(false); // Update data without redrawing
            
              // Reload the DataTable after a delay (e.g., 1 second) to reflect any changes in the table structure or formatting
              setTimeout(function() {
                tableTransaction.ajax.reload(null, false); // Reload the DataTable without resetting current page
              }, 1000); // Adjust delay as needed
            },
            error: function () {
                alert('Failed to retrieve categories.');
            }
        });
}
    $('#transactionTable').on('click', 'button.btn-view', function () {
      var data = tableTransaction.row($(this).parents('tr')).data();

      // Extract the Transaction No and Type
      var transactionNo = data['Transaction No'];
      var type = data['Type'];
      $('#submitForm').hide();
      $('#updateForm').show();
      // Reset form inputs
      $('#billForm')[0].reset();
      $('#expenseForm')[0].reset();
      $('#invoiceForm')[0].reset();
      console.log('Type:', type);
      clearRows();
      if (type === 'bill') {
        $('#expenseForm').hide();
        $('#invoiceForm').hide();
        $('#billForm').show();
        $('#transactionModalLabel').text('Bill');  // Set modal title for Bill
      } else if (type === 'expense') {
        $('#billForm').hide();
        $('#invoiceForm').hide();
        $('#expenseForm').show();
        $('#transactionModalLabel').text('Expense');  // Set modal title for Expense
      } else if (type === 'invoice') {
        $('#billForm').hide();
        $('#expenseForm').hide();
        $('#invoiceForm').show();
        $('#transactionModalLabel').text('Invoice');  // Set modal title for Expense
      }
      // Show the modal
      $('#transactionModal').modal('show');
      // Create the new URL with parameters
      const params = new URLSearchParams(window.location.search);
      params.set('transacNo', transactionNo);
      params.set('type', type);
      const newUrl = `${window.location.pathname}?${params.toString()}`;
      
      // Update the URL without reloading the page
      history.pushState(null, '', newUrl);

      // alert(`Transaction No: ${transactionNo}, Type: ${type}`);
        $.ajax({
          url: 'admin/process/admin_action.php',
          type: 'POST',
          dataType: 'json',
          data: {
              action: 'getTransactionDetails',
              transactionType: type,
              transactionNo: transactionNo
          },
          success: function(response) {
              if (response.transaction) {
                  // console.log("Transaction Details:", response.transaction);
                  // console.log("Associated Items:", response.items);
                  // You can also display these details in a modal or on the page
                  populateTransactionDetails(response, type);
                  
              } else {
                  alert(response.message || "Transaction not found.");
              }
          },
          error: function(xhr, status, error) {
              console.error("AJAX Error:", error);
          }
        });
    });

    $('#transactionTable').on('click', 'button.btn-void', function () {
      var data = tableTransaction.row($(this).parents('tr')).data();
      transac_type = data['Type'];
      transac_no = data['Transaction No'];
      console.log("transac: " +data['Type']+" TransacNo: "+data['Transaction No']);
      
      Swal.fire({
      title: "Void this Transaction: \n"+data['Transaction No']+" ?",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: "Yes",
      denyButtonText: `No`
      }).then((result) => {
      if (result.isConfirmed) {
            //AJAX request
            $.ajax({
            url: 'admin/process/admin_action.php', // Update with your PHP script path
            type: 'POST',
            data: {
                action: 'voidTransaction',
                transac_type: transac_type,
                transac_no: transac_no
            },
            dataType: 'json', // Expecting JSON response
            success: function (response) {
                if (response.success) {

                } else {
                    console.log(response.message);
                    // toastr.error(response.message);
                    Swal.fire("Error!", response.message, "error");
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.log('An error occurred:', error);
            }
          });
      }else if (result.isDenied) {
        Swal.fire("Void has been canceled.", "", "error");
      }
      });
    });
    $('#transactionTable').on('click', 'button.btn-delete', function () {
    var data = tableTransaction.row($(this).parents('tr')).data();
    transac_type = data['Type'];
    transac_no = data['Transaction No'];
    console.log("transac: " + data['Type'] + " TransacNo: " + data['Transaction No']);
    
    Swal.fire({
        title: "Delete this Transaction: \n" + data['Transaction No'] + " ?",
        text: "This action cannot be undone!",
        icon: "warning",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Yes, delete it",
        denyButtonText: `No`
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX request
            $.ajax({
                url: 'admin/process/admin_action.php', // Update with your PHP script path
                type: 'POST',
                data: {
                    action: 'deleteTransaction',
                    transac_type: transac_type,
                    transac_no: transac_no
                },
                dataType: 'json', // Expecting JSON response
                success: function (response) {
                    if (response.success) {
                        Swal.fire("Deleted!", response.message, "success");
                        tableTransaction.ajax.reload(null, false); // Reload table data without resetting pagination
                    } else {
                        console.log(response.message);
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error response
                    console.log('An error occurred:', error);
                    Swal.fire("Error!", "An unexpected error occurred.", "error");
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Delete has been canceled.", "", "info");
        }
    });
});

    function populateTransactionDetails(data, transactionType) {
      // Clear main form fields before populating
      console.log(data.transaction.supplier_id);
      // $('#supplier, #bill_supplier, #payee_id, #customer_id').val(''); 
      // $('#bill_address, #invoice_bill_address, #expense_date, #invoice_date').val('');
      // $('#billNo, #expense_no, #invoice_no').val('');
      
      // Populate main fields based on transaction type
      if (transactionType === 'bill') {
          $('#bill_supplier').val(data.transaction.supplier_id).selectpicker('refresh');
          $('#bill_address').val(data.transaction.bill_address);
          $('#bill_start_date').val(data.transaction.bill_date);
          $('#bill_end_date').val(data.transaction.bill_due_date);
          $('#billNo').val(data.transaction.bill_no);
          $('#taxOption').val(data.transaction.tax_type);
          $('#attach_Remarks').val(data.transaction.remarks);
          
          toggleTaxColumn();
      }
      else if (transactionType === 'expense') {
        $('#expense_supplier').val(data.transaction.payee_id).selectpicker('refresh');
        $('#expense_date').val(data.transaction.expense_date);
        $('#expense_payment').val(data.transaction.expense_payment_method).selectpicker('refresh');
        $('#expense_ref_no').val(data.transaction.expense_no);
        $('#taxOption').val(data.transaction.tax_type);
        $('#attach_Remarks').val(data.transaction.remarks);
        toggleTaxColumn();
      }
      else if (transactionType === 'invoice') {
        $('#invoice_customer').val(data.transaction.customer_id).selectpicker('refresh');
        $('#invoice_customer_email').val(data.transaction.customer_email);
        $('#invoice_bill_address').val(data.transaction.invoice_bill_address);
        $('#invoice_date').val(data.transaction.invoice_date);
        $('#invoice_duedate').val(data.transaction.invoice_duedate);
        $('#invoice_ship_address').val(data.transaction.invoice_shipping_address);
        $('#invoice_via').val(data.transaction.invoice_ship_via);
        $('#invoice_ship_date').val(data.transaction.invoice_ship_date);
        $('#invoice_track_no').val(data.transaction.invoice_track_no);
        $('#taxOption').val(data.transaction.tax_type);
        $('#attach_Remarks').val(data.transaction.remarks);
        toggleTaxColumn();
      }

      // Clear existing items in the table
      table.clear();

      data.items.forEach(item => {
        table.row.add({
        product_name: item.product_name,
        sku: item.product_sku,
        barcode: item.item_barcode,
        expiry: item.item_expiry,
        qty: item.item_qty,
        rate: item.item_rate,
        amount: item.item_amount,
        tax: item.item_tax,
        customer: item.customer_id
      }).draw(false);
    });
    $('.selected-product').selectpicker('refresh');
      // Populate the file attachments section
  const fileAttachedContainer = $('#file_attached');
  fileAttachedContainer.empty(); // Clear existing content

  if (data.files && data.files.length > 0) {
    data.files.forEach(file => {
      const fileRow = `
        <div class="row mb-2">
          <div class="col d-flex justify-content-between align-items-center border p-2">
            <div class="text-dark">${file.file_name}</div>
            <div>
              <a href="http://localhost/ip/uploads/${file.file_path}" target="_blank">
                <iconify-icon icon="line-md:download-loop" width="24" height="24"></iconify-icon>
              </a>
            </div>
          </div>
        </div>`;
      fileAttachedContainer.append(fileRow);
    });
  } else {
    // Show "No Files Uploaded" if no files are available
    fileAttachedContainer.html(`
      <div class="row mb-2">
        <div class="col d-flex justify-content-between align-items-center border p-2">
          <div>No Files Uploaded</div>
        </div>
      </div>
    `);
  }
      // Update total amount or any other summary fields based on the retrieved data
      updateTotalAmount();
  }

  $('[data-form]').on('click', function() {
    var formType = $(this).data('form');
    $('#updateForm').hide();
    $('#submitForm').show();
    $('.select-customer').trigger('changed.bs.select');
    
    // Reset form inputs
    $('#billForm')[0].reset();
    $('#expenseForm')[0].reset();
    $('#invoiceForm')[0].reset();
    $('#attach_Remarks').val("");
    clearRows();
    if (formType === 'bill') {
      $('#expenseForm').hide();
      $('#invoiceForm').hide();
      $('#billForm').show();
      $('#transactionModalLabel').text('Bill');  // Set modal title for Bill
    } else if (formType === 'expense') {
      $('#billForm').hide();
      $('#invoiceForm').hide();
      $('#expenseForm').show();
      $('#transactionModalLabel').text('Expense');  // Set modal title for Expense
    } else if (formType === 'invoice') {
      $('#billForm').hide();
      $('#expenseForm').hide();
      $('#invoiceForm').show();
      $('#transactionModalLabel').text('Invoice');  // Set modal title for Expense
    }
    
  });


  var table = $('#editableTable').DataTable({
    columns: [
      { title: "Product Name", data: "product_name", className: "text-dark" },
      { title: "SKU", data: "sku"},
      { title: "Barcode", data: "barcode", className: "text-dark" },
      { title: "Expiry Date", data: "expiry", className: "text-dark" },
      { title: "Qty", data: "qty", className: "text-dark" },
      { title: "Rate", data: "rate", className: "text-dark"},
      { title: "Amount", data: "amount", className: "text-dark" },
      {
        title: "Tax", 
        data: "tax",
        className: "text-dark",
        render: function(data, type, row) {
        // Check if data is null, undefined, or an empty string
        if (data === null || data === undefined || data === '') {
          return null; // Display 0% if no data
        }
        return data + '%'; // Display as percentage if there is data
      }
      },
      { title: "Customer", data: "customer" },
      {
        title: "Actions", 
        data: null,
        defaultContent: `
          <button type="button" class="btn btn-primary btn-sm edit-row">Edit</button>
          <button type="button" class="btn btn-danger btn-sm delete-row">Delete</button>`
      }
    ],
    paging: false,
    ordering: false,
    searching: false,
    autoWidth: true
  });
  var taxOption = $('#taxOption');
    var taxColumnIndex = 7; // Adjust this if the tax column index changes

    // Function to show/hide tax column based on the selected value
    function toggleTaxColumn() {
      var selectedValue = taxOption.val();

      if (selectedValue === "3") { // Out of Scope of Tax
        table.column(taxColumnIndex).visible(false); // Hide the tax column
        // Reset tax values for all rows
        table.rows().every(function() {
          var rowData = this.data();
          rowData.tax = null;
          this.data(rowData).draw(false); // Redraw row with tax set to null
        });
      } else {
        table.column(taxColumnIndex).visible(true); // Show the tax column
        table.rows().every(function() {
          var rowData = this.data();
          rowData.tax = null;
          this.data(rowData).draw(false); // Redraw row with tax set to null
        });
      }
    }


    // Call the function on page load to apply the initial state
    toggleTaxColumn();

    // Handle the change event of the dropdown
    taxOption.change(function() {
        toggleTaxColumn(); // Reuse the same logic on change
        updateTotalAmount();
    });
  var currentlyEditingRow = null;
  function enterEditMode(row) {
    if (currentlyEditingRow) {
      // Attempt to save changes for the currently editing row
      if (!saveChanges(currentlyEditingRow)) {
        return;  // Exit if save was not successful
      }
    }

    currentlyEditingRow = row; // Set currently editing row
    var rowData = table.row(row).data();
    var lastRow = $('#editableTable tbody tr').last();

    // If this is the last row, add a new row after editing starts
    if (row.is(lastRow)) {
      addNewRow();
    }

  // Check if the tax column is visible
  var isTaxVisible = table.column(7).visible();
  var fields = [
    '<select class="selected-product selectpicker form-control col-auto" data-live-search="true"><?php echo $selectProduct; ?></select>',
    '<input type="text" class="form-control sku col-auto" value="' + rowData.sku + '" readonly>',
    '<input type="text" class="form-control barcode col-auto" value="' + rowData.barcode + '">',
    '<input type="date" class="form-control expiry col-auto" value="' + rowData.expiry + '">',
    '<input type="number" class="form-control qty col-auto" min="1" value="' + rowData.qty + '">',
    '<input type="number" class="form-control rate col-auto" min="1" value="' + rowData.rate + '">',
    '<input type="number" class="form-control amount col-auto" min="1" value="' + rowData.amount + '">'
  ];

  // Add the tax field only if visible
  if (isTaxVisible) {
    fields.push(
      '<select class="tax selectpicker form-control" data-live-search="true">' +
        '<?php foreach ($taxes as $tax): ?>' +
          '<option value="<?php echo $tax['tax_percentage']; ?>"><?php echo $tax['tax_name']; ?></option>' +
        '<?php endforeach; ?>' +
      '</select>'
    );
  }


  // Add the customer field (always visible)
  fields.push(
    '<select class="selected-customer selectpicker form-control" data-live-search="true">' +
      '<option value="">None</option>' +
      '<option value="Customer A">Customer A</option>' +
      '<option value="Customer B">Customer B</option>' +
    '</select>'
  );

  // Fill the row cells with the corresponding fields
  row.find('td').each(function(index) {
    $(this).html(fields[index]);
  });

  // Set values for the fields
  row.find('.selected-product').val(rowData.product_name).selectpicker('refresh');
  if (isTaxVisible) {
    row.find('.tax').val(rowData.tax || 0).selectpicker('refresh');
  }
  row.find('.selected-customer').val(rowData.customer).selectpicker('refresh');

  row.find('.edit-row').text('Save');

  // Reset tax value when "Out of Scope of Tax" is selected
  row.find('.tax').change(function() {
    if ($(this).val() === "3") { // Assuming "3" represents Out of Scope of Tax
      rowData.tax = null; // Set tax to null
      table.row(row).data(rowData); // Update the row data
      $(this).val(null); // Reset tax select
    }
  });
}


  // Function to add a new row to the table
  function addNewRow() {
    table.row.add({
      product_name: '',
      sku: '',
      barcode: '',
      expiry: '',
      qty: '',
      rate: '',
      amount: '',
      tax: '',
      customer: ''
    }).draw(false);
    updateTotalAmount();
  }

  function saveChanges(row) {
    // Check if all input fields are empty
    var allEmpty = true;

    row.find('input, select').each(function() {
        var value = $(this).selectpicker ? $(this).selectpicker('val') : $(this).val().trim();
        if (value) {
            allEmpty = false; // Found a non-empty field
        }
    });

    if (allEmpty) {
        // Clear all fields
        row.find('input, select').val('').trigger('change'); // Clear input fields and trigger change for selectpicker
        row.find('.edit-row').text('Edit'); // Reset edit button text
        currentlyEditingRow = null; // Reset currently editing row
        return true; // Indicate success
    }

    var isValid = true;
    var errorMessages = []; // Use a Set to collect unique error messages

    function validateField(selector, fieldName, type) {
        var field = row.find(selector);
        var value = field.selectpicker ? field.selectpicker('val') : field.val().trim();

        switch (type) {
            case 'required':
                if (!value || value.length === 0) {
                    isValid = false;
                    errorMessages.push(fieldName + ' is required.'); // Add to Set
                    field.addClass('is-invalid');
                }
                break;
            case 'number':
                var value = field.val().trim();
                const floatValue = parseFloat(value.replace(/,/g, ''));
                if (isNaN(floatValue) || floatValue < 0) {
                    isValid = false;
                    errorMessages.push(fieldName + ' must be a positive number.'); // Add to Set
                    field.addClass('is-invalid');
                }
                break;
            case 'positiveNumber':
                var positiveValue = parseFloat(value.replace(/,/g, '')); // Use parseFloat for decimal support
                if (isNaN(positiveValue) || positiveValue < 0) { // Must be greater than or equal to 0
                    isValid = false;
                    errorMessages.push(fieldName + ' must be 0 or greater.'); // Updated message
                    field.addClass('is-invalid');
                }
                break;
        }
    }

    // Validate each field
    validateField('.selected-product', 'Product', 'required');
    validateField('.qty', 'Quantity', 'number');
    validateField('.rate', 'Rate', 'number');
    validateField('.amount', 'Amount', 'number');

    if (table.column(7).visible()) {
        validateField('.tax', 'Tax', 'positiveNumber');
    }

    // Check if all validations passed
    if (!isValid) {
        toastr.error(errorMessages[0]);
        return false; // Stop the save process
    }

    // Prepare updated data if validation is successful
    var updatedData = {
        product_name: row.find('.selected-product').selectpicker('val'),
        sku: row.find('.sku').val(),
        barcode: row.find('.barcode').val(),
        expiry: row.find('.expiry').val(),
        qty: parseInt(row.find('.qty').val(), 10) || 0,
        rate: parseFloat(row.find('.rate').val()) || 0,
        amount: parseFloat(row.find('.amount').val()) || 0,
        tax: table.column(7).visible() ? row.find('.tax').selectpicker('val') : null,
        customer: row.find('.selected-customer').selectpicker('val')
    };

    // Update the table with the new data
    table.row(row).data(updatedData).draw(false);
    updateTotalAmount();
    row.find('.edit-row').text('Edit');
    currentlyEditingRow = null; // Reset currently editing row

    // Remove all 'is-invalid' classes after successful save
    row.find('.is-invalid').removeClass('is-invalid');

    return true; // Indicate success
  }

  // Handle double-click on a row to trigger edit mode
  $('#editableTable tbody').on('dblclick', 'tr', function() {
    var row = $(this);
    if (row.find('.edit-row').text() === 'Edit') {
      enterEditMode(row);
    }
  });

  // Handle Enter key press to trigger save
  $('#editableTable tbody').on('keydown', 'input, select', function(event) {
    if (event.key === "Enter") {
      var row = $(this).closest('tr');
      saveChanges(row);
    }
  });

  // Handle Edit button click
  $('#editableTable tbody').on('click', '.edit-row', function() {
    var row = $(this).closest('tr');
    if ($(this).text() === 'Edit') {
      enterEditMode(row);
    } else {
      saveChanges(row);
    }
  });

  // Handle clicking outside row to exit edit mode
  $(document).on('click', function(e) {
    if (currentlyEditingRow && !$(e.target).closest('tr').is(currentlyEditingRow)) {
      saveChanges(currentlyEditingRow);
    }
  });

  // Other existing functionality...
  
  // Add Row button
  $('#addRow').on('click', function() {
    table.row.add({
      product_name: '',
      sku: '',
      barcode: '',
      expiry: '',
      qty: '',
      rate: '',
      amount: '',
      tax: '',
      customer: ''
    }).draw(false);
    updateTotalAmount();
  });

  // Clear Rows button
  $('#clearRows').on('click', function() {
    clearRows();
  });

  $(document).on('changed.bs.select', '.selected-product', function (e, clickedIndex, isSelected, previousValue) {
    var selectedOption = $(this).find('option:selected');
    var sku = selectedOption.data('sku');  // Get the SKU from the selected option

    // Determine the active form type
    var formType = $('#billForm:visible').length ? 'bill' : $('#expenseForm:visible').length ? 'expense' : 'invoice';

    // console.log('Form Type:', formType);
    // Choose the appropriate rate based on form type
    var rate;
    if (formType === 'bill' || formType === 'expense') {
        rate = selectedOption.data('rate-purchase');  // For bill and expense, use purchase price
    } else if (formType === 'invoice') {
        rate = selectedOption.data('rate-sell');  // For invoice, use selling price
    }

    // console.log(rate);
    var row = $(this).closest('tr');  // Get the closest row of the table

    // Populate the SKU and Rate fields in the current row
    row.find('.sku').val(sku);
    row.find('.rate').val(rate);
    row.find('.qty').val(1);
    row.find('.amount').val((1 * rate).toFixed(2));
    updateTotalAmount();
  });

  $(document).on('changed.bs.select', '.select-customer', function (e, clickedIndex, isSelected, previousValue) {
    var selectedOption = $(this).find('option:selected');
    var customer_email = selectedOption.data('customer-email');

    $('#invoice_customer_email').val(customer_email);
  });
// Update the total amount after selecting a new tax value from the picker
$(document).on('changed.bs.select', '.tax', function (e, clickedIndex, isSelected, previousValue) {
    var row = $(this).closest('tr');  // Get the current row
    var qty = parseFloat(row.find('.qty').val()) || 0;  // Get quantity
    var rate = parseFloat(row.find('.rate').val()) || 0;  // Get base rate
    var taxRate = parseFloat($(this).selectpicker('val')) || 0;  // Get the selected tax rate

    var taxType = $('#taxOption').val();  // Fetch the selected tax type
    var amount = 0;

    // Calculate amount based on tax type
    if (taxType === "1") {  // Exclusive of Tax
        amount = qty * rate;  // Calculate amount without tax
        row.find('.amount').val(amount.toFixed(2));  // Update the amount in the row
    } else if (taxType === "2") {  // Inclusive of Tax
        // Amount includes tax, calculate rate
        amount = qty * rate / (1 + taxRate / 100);  // Adjust amount to exclude tax
        row.find('.amount').val(amount.toFixed(2));  // Update the amount in the row
    } else if (taxType === "3") {  // Out of Scope of Tax
        amount = qty * rate;  // No tax applied, just the base amount
        row.find('.amount').val(amount.toFixed(2));  // Update the amount in the row
    }

    // Update total amounts at the bottom
    updateTotalAmount();
});

// Handle calculations for Qty, Rate, and Amount based on tax selection
$('#editableTable').on('input', '.qty, .rate', function() {
    var row = $(this).closest('tr');  // Get the current row
    var qty = parseFloat(row.find('.qty').val()) || 0;  // Get quantity
    var rate = parseFloat(row.find('.rate').val()) || 0;  // Get base rate

    var taxRate = parseFloat(row.find('.tax').selectpicker('val')) || 0;  // Get the selected tax rate
    var taxType = $('#taxOption').val();  // Fetch the selected tax type
    
    var amount = 0;

    // Calculate amount based on tax type
    if (taxType === "1") {  // Exclusive of Tax
        // Amount is just qty * rate (no changes)
        amount = qty * rate;
        row.find('.amount').val(amount.toFixed(2));  // Update the amount in the row (without tax)
    } else if (taxType === "2") {  // Inclusive of Tax
        // For Inclusive of Tax, calculate amount based on qty and rate
        amount = qty * rate / (1 + taxRate / 100);  // Adjust amount to exclude tax
        row.find('.amount').val(amount.toFixed(2));  // Update the amount in the row
    } else if (taxType === "3") {  // Out of Scope of Tax
        amount = qty * rate;  // No tax applied, just the base amount
        row.find('.amount').val(amount.toFixed(2));  // Update the amount in the row (without tax)
    }

    // Update total amounts at the bottom
    updateTotalAmount();
});

// Handle input for amount to update rate if needed
$('#editableTable').on('input', '.amount', function() {
    var row = $(this).closest('tr');
    var qty = parseFloat(row.find('.qty').val()) || 0;
    var amount = parseFloat($(this).val()) || 0;

    if (qty > 0) {
        row.find('.rate').val((amount / qty).toFixed(2));
    }
    updateTotalAmount();
});
// Update the total amount and tax at the bottom
function updateTotalAmount() {
    var subtotal = 0;
    var totalTax = 0;
    var grandTotal = 0;
    var taxType = $('#taxOption').val();  // Fetch the selected tax type

    // Iterate over each row to calculate subtotal and tax
    table.rows().every(function() {
        var data = this.data();
        var rowAmount = parseFloat(data.amount) || 0;  // Subtotal for each row
        var taxRate = parseFloat(data.tax) || 0;  // Get the selected tax rate
        var rate = parseFloat(data.rate) || 0;  // Get the base rate

        // For Inclusive tax, the row amount already includes tax.
        if (taxType === "2") { // Inclusive of Tax
            // Extract the tax from the row amount for display
            var taxAmount = (rowAmount * taxRate / (100 + taxRate));
            totalTax += Math.floor(taxAmount * 100) / 100;  // Round down to two decimal places
            subtotal += rowAmount; // The subtotal is still the amount with tax.
        } else {
            subtotal += rowAmount;  // Sum the amounts for subtotal
            if (taxType === "1") {  // Exclusive of Tax
                totalTax += rowAmount * (taxRate / 100);  // Add tax for exclusive tax
            }
            // Out of Scope (taxType === "3") doesn't add any tax
        }
    });

    // For Inclusive Tax, grandTotal should be equal to subtotal
    if (taxType === "2") {
        grandTotal = subtotal;  // Total is just the subtotal itself
    } else {
        grandTotal = subtotal + totalTax;  // Grand total = subtotal + tax for exclusive tax
    }

    // Update the subtotal and grand total displays
    $('#lblSubAmount').text(subtotal.toFixed(2));  // Display subtotal
    $('#lblTaxAmount').text(totalTax.toFixed(2));  // Display total tax
    $('#lblAmount').text(grandTotal.toFixed(2));  // Display grand total

    //update val
    $('#totalSubAmount').val(subtotal.toFixed(2));
    $('#totalTaxAmount').val(totalTax.toFixed(2));
    $('#totalAmount').val(grandTotal.toFixed(2));
}

  // Handle Delete button
  $('#editableTable tbody').on('click', '.delete-row', function() {
    var row = table.row($(this).closest('tr'));
    
    if (currentlyEditingRow && currentlyEditingRow.is(row.node())) {
      // If the row being deleted is currently being edited, reset the editing state
      currentlyEditingRow = null;
    }

    // If there are more than 2 rows, remove the row
    if (table.rows().count() > 2) {
      row.remove().draw(false);
    } else {
      // If only 2 rows left, clear the row instead of removing it
      row.data({
        product_name: '',
        sku: '',
        barcode: '',
        expiry: '',
        qty: '',
        rate: '',
        amount: '',
        tax: '',
        customer: ''
      }).draw(false);
    }
    
    updateTotalAmount();
  });

  // Form submission
  $('#submitForm').on('click', function() {
    // File validation first
    const uploadedFiles = $('.attachment_file').filepond('getFiles');
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    
    // Validate uploaded files
    for (const file of uploadedFiles) {
        // Check file type
        if (!allowedTypes.includes(file.fileType)) {
            Swal.fire({
                title: "Invalid File Type",
                text: `File "${file.filename}" is not allowed. Only JPG, PNG and PDF files are permitted.`,
                icon: "error"
            });
            return;
        }
        
        // Check file size
        if (file.size > maxFileSize) {
            Swal.fire({
                title: "File Too Large",
                text: `File "${file.filename}" exceeds 5MB limit.`,
                icon: "error"
            });
            return;
        }
    }
    // Determine which form is currently active (Bill or Expense)
    var activeForm;
    if ($('#billForm').is(':visible')) {
      activeForm = 'bill';
    } else if ($('#expenseForm').is(':visible')) {
      activeForm = 'expense';
    } else if ($('#invoiceForm').is(':visible')) {
      activeForm = 'invoice';
    }

    var formData = new FormData();

    // Based on the active form, capture the corresponding fields
    if (activeForm === 'bill') {
      formData.append('action', 'addTransaction');
      formData.append('formType', 'bill');  // Pass form type to backend
      formData.append('billSupplier', $('#bill_supplier').val());
      formData.append('billAddress', $('#bill_address').val());
      formData.append('billDate', $('#bill_start_date').val());
      formData.append('billdueDate', $('#bill_end_date').val());
      formData.append('billNo', $('#billNo').val());
      formData.append('tax_type', $('#taxOption').val());
      //totals
      formData.append('sub_total', $('#totalSubAmount').val());
      formData.append('total_tax', $('#totalTaxAmount').val());
      formData.append('grand_total', $('#totalAmount').val());
      
      // Get itemList from table and append it to FormData
      var itemList = table.rows().data().toArray();
      formData.append('items', JSON.stringify(itemList));
    } else if (activeForm === 'expense') {
      formData.append('action', 'addTransaction');
      formData.append('formType', 'expense');  // Pass form type to backend
      formData.append('payee_id', $('#expense_supplier').val());
      formData.append('expenseDate', $('#expense_date').val());
      formData.append('expense_payment_method', $('#expense_payment').val());
      formData.append('expenseNo', $('#expense_ref_no').val());
      formData.append('tax_type', $('#taxOption').val());
      var itemList = table.rows().data().toArray();
      formData.append('items', JSON.stringify(itemList));
      //totals
      formData.append('sub_total', $('#totalSubAmount').val());
      formData.append('total_tax', $('#totalTaxAmount').val());
      formData.append('grand_total', $('#totalAmount').val());
    } else if (activeForm === 'invoice') {
      formData.append('action', 'addTransaction');
      formData.append('formType', 'invoice');
      formData.append('customer_id', $('#invoice_customer').val());
      formData.append('customer_email', $('#invoice_customer_email').val());
      formData.append('invoice_bill_address', $('#invoice_bill_address').val());
      formData.append('invoice_date', $('#invoice_date').val());
      formData.append('invoice_duedate', $('#invoice_duedate').val());
      formData.append('invoice_ship_address', $('#invoice_ship_address').val());
      formData.append('invoice_ship_via', $('#invoice_via').val());
      formData.append('invoice_ship_date', $('#invoice_ship_date').val());
      formData.append('invoice_track_no', $('#invoice_track_no').val());
      formData.append('tax_type', $('#taxOption').val());

      // Item details
      var itemList = table.rows().data().toArray();
      // console.log(itemList);
      formData.append('items', JSON.stringify(itemList));

      // Totals
      formData.append('sub_total', $('#totalSubAmount').val());
      formData.append('total_tax', $('#totalTaxAmount').val());
      formData.append('grand_total', $('#totalAmount').val());
    }

    // Attach remarks
    formData.append('remarks', $('#attach_Remarks').val());
    // Attach all files uploaded via FilePond
    uploadedFiles.forEach((file, index) => {
        formData.append(`attachments[${index}]`, file.file); // Append each file with a unique name
    });

    Swal.fire({
      title: "Do you want to save the changes?",
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: "Save",
      denyButtonText: `Don't save`
    }).then((result) => {
      if (result.isConfirmed) {
        for (const [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        //AJAX request
        $.ajax({
            url: 'admin/process/admin_action.php', // Update with your PHP script path
            type: 'POST',
            data: formData,
            contentType: false, // Important for file uploads
            processData: false, // Important for file uploads
            success: function (response) {
                // Handle success response
                $('#responseMessage').html(response.message);
                if (response.success) {
                    // Reset the active form
                    if (activeForm === 'bill') {
                        
                        $('#billForm')[0].reset(); // Reset the bill form
                        $('#attach_Remarks').val("");
                        Swal.fire("Saved!", response.message, "success");
                    } else if (activeForm === 'expense') {
                       
                        $('#expenseForm')[0].reset(); // Reset the expense form
                        Swal.fire("Saved!", response.message, "success");
                        $('#attach_Remarks').val("");
                    }
                    else if (activeForm === 'invoice') {
                        
                        $('#invoiceForm')[0].reset(); // Reset the expense form
                        Swal.fire("Saved!", response.message, "success");
                        $('#attach_Remarks').val("");
                    }
                    clearRows();
                    $('#transactionModal').modal('hide');
                    LoadTransactionTable();
                    $('.attachment_file').filepond('removeFiles');
                } else {
                    console.log(response.message);
                    // toastr.error(response.message);
                    Swal.fire("Error!", response.message, "error");
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                console.log('An error occurred:', error);
            }
        });
      } else if (result.isDenied) {
        Swal.fire("Changes are not saved", "", "error");
      }
    });
  });
  
  $('#updateForm').on('click', function() {
        // File validation first
        const uploadedFiles = $('.attachment_file').filepond('getFiles');
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    const maxFileSize = 5 * 1024 * 1024; // 5MB
    
    // Validate uploaded files
    for (const file of uploadedFiles) {
        // Check file type
        if (!allowedTypes.includes(file.fileType)) {
            Swal.fire({
                title: "Invalid File Type",
                text: `File "${file.filename}" is not allowed. Only JPG, PNG and PDF files are permitted.`,
                icon: "error"
            });
            return;
        }
        
        // Check file size
        if (file.size > maxFileSize) {
            Swal.fire({
                title: "File Too Large",
                text: `File "${file.filename}" exceeds 5MB limit.`,
                icon: "error"
            });
            return;
        }
    }
    // Retrieve transaction details from the URL parameters
    const params = new URLSearchParams(window.location.search);
    const transactionNo = params.get('transacNo');
    const type = params.get('type'); // e.g., 'bill', 'expense', 'invoice'

    // Ensure both 'transactionNo' and 'type' are present
    if (!transactionNo || !type) {
        Swal.fire("Error", "Transaction details are missing", "error");
        return;
    }
    // Determine which form is currently active (Bill, Expense, or Invoice)
    var activeForm;
    if ($('#billForm').is(':visible')) {
        activeForm = 'bill';
    } else if ($('#expenseForm').is(':visible')) {
        activeForm = 'expense';
    } else if ($('#invoiceForm').is(':visible')) {
        activeForm = 'invoice';
    }

    var formData = new FormData();

    // Set up formData based on the active form type
    if (activeForm === 'bill') {
        formData.append('action', 'updateTransaction');
        formData.append('formType', 'bill');  // Pass form type to backend
        formData.append('transacNo', transactionNo); // Attach the transaction number
        formData.append('type', type);  // Attach the transaction type
        formData.append('tax_type', $('#taxOption').val());

        // Capture the form fields
        formData.append('billSupplier', $('#bill_supplier').val());
        formData.append('billAddress', $('#bill_address').val());
        formData.append('billDate', $('#bill_start_date').val());
        formData.append('billdueDate', $('#bill_end_date').val());
        formData.append('billNo', $('#billNo').val());
        formData.append('sub_total', $('#totalSubAmount').val());
        formData.append('total_tax', $('#totalTaxAmount').val());
        formData.append('grand_total', $('#totalAmount').val());

        // Get itemList from table and append it to FormData
        var itemList = table.rows().data().toArray();
        formData.append('items', JSON.stringify(itemList));
    } else if (activeForm === 'expense') {
        formData.append('action', 'updateTransaction');
        formData.append('formType', 'expense');  // Pass form type to backend
        formData.append('transacNo', transactionNo); // Attach the transaction number
        formData.append('type', type);  // Attach the transaction type
        formData.append('tax_type', $('#taxOption').val());

        // Capture the form fields
        formData.append('payee_id', $('#expense_supplier').val());
        formData.append('expenseDate', $('#expense_date').val());
        formData.append('expense_payment_method', $('#expense_payment').val());
        formData.append('expenseNo', $('#expense_ref_no').val());
        formData.append('sub_total', $('#totalSubAmount').val());
        formData.append('total_tax', $('#totalTaxAmount').val());
        formData.append('grand_total', $('#totalAmount').val());

        // Get itemList from table and append it to FormData
        var itemList = table.rows().data().toArray();
        formData.append('items', JSON.stringify(itemList));
    } else if (activeForm === 'invoice') {
        formData.append('action', 'updateTransaction');
        formData.append('formType', 'invoice');  // Pass form type to backend
        formData.append('transacNo', transactionNo); // Attach the transaction number
        formData.append('type', type);  // Attach the transaction type
        formData.append('tax_type', $('#taxOption').val());

        // Capture the form fields
        formData.append('customer_id', $('#invoice_customer').val());
        formData.append('customer_email', $('#invoice_customer_email').val());
        formData.append('invoice_bill_address', $('#invoice_bill_address').val());
        formData.append('invoice_date', $('#invoice_date').val());
        formData.append('invoice_duedate', $('#invoice_duedate').val());
        formData.append('invoice_ship_address', $('#invoice_ship_address').val());
        formData.append('invoice_ship_via', $('#invoice_via').val());
        formData.append('invoice_ship_date', $('#expense_date').val());
        formData.append('invoice_track_no', $('#invoice_customer_email').val());

        // Item details
        var itemList = table.rows().data().toArray();
        formData.append('items', JSON.stringify(itemList));

        // Totals
        formData.append('sub_total', $('#totalSubAmount').val());
        formData.append('total_tax', $('#totalTaxAmount').val());
        formData.append('grand_total', $('#totalAmount').val());
    }

    // Attach remarks
    formData.append('remarks', $('#attach_Remarks').val());
    // Attach all files uploaded via FilePond
    uploadedFiles.forEach((file, index) => {
        formData.append(`attachments[${index}]`, file.file); // Append each file with a unique name
    });
    // Confirm before saving changes
    Swal.fire({
        title: "Do you want to save the changes?",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "Save",
        denyButtonText: `Don't save`
    }).then((result) => {
        if (result.isConfirmed) {
            // Log FormData to the console for debugging
            for (const [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            // AJAX request to update transaction
            $.ajax({
                url: 'admin/process/admin_action.php', // Path to your PHP script
                type: 'POST',
                data: formData,
                contentType: false, // Important for file uploads
                processData: false, // Important for file uploads
                success: function(response) {
                    // Handle success response
                    $('#responseMessage').html(response.message);
                    if (response.success) {
                        // Reset the active form
                        if (activeForm === 'bill') {
                            $('#billForm')[0].reset();
                            Swal.fire("Saved!", response.message, "success");
                        } else if (activeForm === 'expense') {
                            $('#expenseForm')[0].reset();
                            Swal.fire("Saved!", response.message, "success");
                        } else if (activeForm === 'invoice') {
                            $('#invoiceForm')[0].reset();
                            Swal.fire("Saved!", response.message, "success");
                        }
                    clearRows(); // Optionally clear rows
                    // Reload DataTable
                    $('#transactionModal').modal('hide');
                    LoadTransactionTable();
                    $('.attachment_file').filepond('removeFiles');
                    } else {
                        Swal.fire("Error!", response.message, "error");
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.log('An error occurred:', error);
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Changes are not saved", "", "error");
        }
    });
});

  function clearRows(){
    currentlyEditingRow = null; // Reset the editing state

    table.clear().draw(false); // Clear all rows
    addEmptyRows(2); // Add 2 default empty rows
    updateTotalAmount();
  }
  // Initialize table with 2 empty rows
  function addEmptyRows(numRows) {
    for (var i = 0; i < numRows; i++) {
      table.row.add({
        product_name: '',
        sku: '',
        barcode: '',
        expiry: '',
        qty: '',
        rate: '',
        amount: '',
        tax: '',
        customer: ''
      }).draw(false);
    }
    updateTotalAmount();
  }

  // Initialize with 2 empty rows
  addEmptyRows(2);
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