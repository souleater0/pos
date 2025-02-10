<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_receipt')){ ?>
<div class="body-wrapper-inner">
  <div class="container-fluid">
    <div class="card shadow-sm">
      <div class="card-header bg-transparent border-bottom">
        <div class="row">
          <div class="col">
            <h5 class="mt-1 mb-0">Manage Receipts</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <table id="transactionsTable" class="table table-hover table-cs-color">
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal to View Receipt -->
<div class="modal fade" id="confirm-print-modal" tabindex="-1" aria-labelledby="confirmPrintModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmPrintModalLabel">Receipt Preview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="receipt-preview-content" class="border text-dark p-2">
          <!-- Receipt content will be injected here -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="printReceipt">Print Receipt</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function () {
    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";
    var userPermissions = {
        manage_receipt: <?php echo userHasPermission($pdo, $_SESSION["user_id"], 'manage_receipt') ? 'true' : 'false'; ?>,
        void_receipt: <?php echo userHasPermission($pdo, $_SESSION["user_id"], 'void_receipt') ? 'true' : 'false'; ?>
    };
    // Initialize DataTable for transactions
    var table = $('#transactionsTable').DataTable({
        responsive: true,
        select: false,
        autoWidth: false,
        order: [[0, 'desc']],
        ajax: {
            url: 'admin/process/table.php?table_type=transactions',
            dataSrc: 'data'
        },
        columns: [
            { data: 'invoice_no', title: 'Invoice#', className: "text-start" },
            { data: 'customer_name', title: 'Customer Name', className: "text-start" },
            { data: 'payment_type', title: 'Payment Method', className: "text-start" },
            { data: 'transaction_date', title: 'Date', className: "text-start" },
            { data: 'transaction_grandtotal', title: 'Amount', className: "text-start" },
            {
                data: 'status',  
                title: 'Status',
                className: "text-start",
                render: function(data, type, row) {
                    return data === 1 
                        ? '<span class="badge bg-danger">Voided</span>' 
                        : '<span class="badge bg-success">Active</span>';
                }
            },
            {
                data: null,
                className: "text-start",
                title: 'Action',
                render: function(data, type, row) {
                    var viewButton = '';
                    var voidButton = '';

                    // Check for permissions before displaying buttons
                    if (userPermissions.manage_receipt) {
                        viewButton = `<button class='btn btn-info btn-sm btn-view-receipt' data-invoice="${row.invoice_no}">
                                        <i class='fa-regular fa-eye'></i> View
                                    </button>&nbsp;`;
                    }

                    if (userPermissions.void_receipt) {
                        voidButton = row.status === 0 
                            ? `<button class='btn btn-danger btn-sm btn-void' data-invoice="${row.invoice_no}">
                                <i class='fa-solid fa-ban'></i> Void
                            </button>`
                            : `<button class='btn btn-success btn-sm btn-void' data-invoice="${row.invoice_no}">
                                <i class='fa-solid fa-check'></i> Unvoid
                            </button>`;
                    }

                    return viewButton + voidButton;
                }
            }
        ]
    });




    // Event handler for 'View Receipt' button click
    $('#transactionsTable').on('click', 'button.btn-view-receipt', function () {
        var data = table.row($(this).parents('tr')).data(); // Get the selected row data
        var invoiceNumber = data.invoice_no;  // Adjusted to match your database field name

        // Fetch transaction details from the server
        $.ajax({
            url: 'admin/process/admin_action.php',
            method: 'POST',
            data: {
                action: 'viewReceipt',
                invoice_number: invoiceNumber,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    var receiptContent = generateReceiptContent(response.transaction_data);
                    $('#receipt-preview-content').html(receiptContent.content); // Display the receipt
                    $('#confirm-print-modal').modal('show'); // Show modal
                } else {
                    swal.fire('Error', response.message, 'error');
                }
            },
            error: function () {
                swal.fire('Error', 'Failed to retrieve transaction details.', 'error');
            }
        });
    });

    // Print the receipt when the print button is clicked
    $('#printReceipt').click(function () {
    // Fetch the receipt content from the preview
    var receiptContent = $('#receipt-preview-content').html();

    // Open a new window for printing
    var printWindow = window.open('', '', 'height=600, width=800');

    // Inject necessary CSS for printing
    var style = `
        <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
                line-height: 1.2;
            }
            .receipt {
                width: 100%;
                margin: 0;
                padding: 0;
                font-size: 9px;
            }
            .receipt h4 {
                text-align: center;
                margin: 5px 0;
                font-size: 12px;
            }
            .receipt p {
                margin: 5px 0;
                font-size: 9px;
            }
            .receipt .receipt-header {
                border-top: 1px dashed black;
                border-bottom: 1px dashed black;
                padding: 5px 0;
            }
            .receipt .receipt-footer {
                border-top: 1px dashed black;
                padding: 5px 0;
                margin-top: 10px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            td, th {
                padding: 4px;
                font-size: 9px;
                text-align: left;
            }
            th {
                font-weight: bold;
            }
            td {
                text-align: left;
            }
            td b {
                font-weight: normal;
            }
            td i {
                font-size: 8px;
                color: red;
            }
            .receipt .thank-you {
                text-align: center;
                margin-top: 10px;
                font-size: 9px;
                font-weight: bold;
            }
        }
        </style>
    `;

    // Write the print window content including the style
    printWindow.document.write('<html><head><title>Receipt</title>' + style + '</head><body>');
    printWindow.document.write(receiptContent);
    printWindow.document.write('</body></html>');

    // Close the document and trigger the print action
    printWindow.document.close();
    printWindow.print();

    // Optional: Close the print window after printing
    setTimeout(function() {
        printWindow.close();
    }, 1000);
});


    // Void Transaction functionality
    $('#transactionsTable').on('click', 'button.btn-void', function () {
        var data = table.row($(this).parents('tr')).data(); // Get the selected row data
        var invoiceNumber = data.invoice_no;  // Adjusted to match your database field name

        // Confirm before voiding the transaction
        swal.fire({
            title: 'Are you sure?',
            text: "This transaction will be voided and cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, void it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'admin/process/admin_action.php',
                    method: 'POST',
                    data: {
                        action: 'voidReceipt',
                        invoice_number: invoiceNumber,
                        csrf_token: csrfToken
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            table.ajax.reload(); // Reload the table
                            swal.fire('Voided!', response.message, 'success');
                        } else {
                            swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function () {
                        swal.fire('Error', 'Failed to void the transaction.', 'error');
                    }
                });
            }
        });
    });
});

function generateReceiptContent(transactionData) {
    let receiptContent = `
        <div class='receipt' style='width: 100%; font-family: Arial, sans-serif;'>
            <h4 style='text-align: center; margin: 5px 0;'>Takoyame Takoyaki</h4>
            <div style='border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 2px 0;'>
                <p style="margin:5px 2px;"><b>Address:</b> Sto. Nino Plaridel, Bulacan <br><b>Contact No:</b> 09392887055</p>
            </div>
            <div style='border-top: none; border-bottom: 1px dashed black; padding: 2px 0;'>
                <p style="margin:5px 2px;"><b>Invoice:</b> ${transactionData.invoice_no} <br><b>Cashier:</b> ${transactionData.cashier_name} <br><b>Date:</b> ${transactionData.transaction_date}</p>
                <p style="margin:5px 2px;"><b>Customer:</b> ${transactionData.customer_name} <br><b>Payment Type:</b> ${transactionData.payment_type}</p>
            </div>
            <table style='width: 100%; border-collapse: collapse; margin-top: 5px; text-align: left;'>
                <tr><th>Item</th><th>Qty</th><th>Price</th><th>Amt</th></tr>
    `;

    let totalSubtotal = 0;
    let totalDiscount = 0;
    let totalAmountAfterDiscount = 0;

    // Loop through items from transaction_data
    transactionData.items.forEach(item => {
        let itemDiscountAmt = parseFloat(item.item_discount_amt) || 0;
        let itemDiscountPercentage = parseFloat(item.item_discount_percentage) || 0;
        let discountLabel = '';

        if (itemDiscountAmt > 0) {
            discountLabel = `<br><i>Discount ${itemDiscountPercentage}% (-₱${itemDiscountAmt.toFixed(2)})</i>`;
        }

        let itemSubtotal = parseFloat(item.item_subtotal);
        totalSubtotal += itemSubtotal;
        totalAmountAfterDiscount += itemSubtotal - itemDiscountAmt;

        receiptContent += `
            <tr>
                <td>${item.item_name}${discountLabel}</td>
                <td>${item.item_qty}</td>
                <td>₱${parseFloat(item.item_price).toFixed(2)}</td>
                <td>₱${itemSubtotal.toFixed(2)}</td>
            </tr>
        `;
    });

    // Calculate the totals
    // let globalDiscount = parseFloat(transactionData.global_discount) || 0;
    // totalDiscount = (totalAmountAfterDiscount * globalDiscount) / 100;
    // let totalBalance = totalAmountAfterDiscount;
    // let totalChange = parseFloat(transactionData.transaction_paid) - totalBalance;

    receiptContent += `
            </table>
            <div style='border-top: 1px dashed black; padding: 5px 0; margin:5px 2px;'>
                <p>
                    <b>Subtotal:</b> ₱${parseFloat(transactionData.transaction_subtotal).toFixed(2)}<br>
                    <b>Discount:</b> -₱${parseFloat(transactionData.transaction_discount).toFixed(2)}<br>
                    <b>Grand Total:</b> ₱${parseFloat(transactionData.transaction_grandtotal).toFixed(2)}<br>
                    <b>Paid:</b> ₱${parseFloat(transactionData.transaction_paid).toFixed(2)}<br>
                    <b>Change:</b> ₱${parseFloat(transactionData.transaction_change).toFixed(2)}
                </p>
            </div>
            <p style='text-align: center; margin: 5px 0;'>Thank you for your purchase!</p>
        </div>
    `;

    return { content: receiptContent };
}



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
