<?php if(userHasPermission($pdo, $_SESSION["user_id"], 'manage_pos')){?>
<div class="body-wrapper-inner">
    <div class="container-fluid mw-100">
        <div class="card shadow-sm p-3">
            <h4>Category</h4>

            <!-- Responsive Category Buttons -->
            <div class="category-container d-flex flex-nowrap overflow-auto">
            <button class="btn btn-success category-btn" data-category="all">All Categories</button>
            <?php 
            $categories = getCategories($pdo); // Function to fetch categories from the database
            foreach ($categories as $category) {
                echo "<button class='btn btn-light category-btn' data-category='{$category['category_name']}'>{$category['category_name']}</button>";
            }
            ?>
            </div>

            <div class="row mt-3">
                <!-- Product List (Left) -->
                <div class="col-lg-6 col-md-4 col-12">
                    <div class="row" id="product-list" style="max-height: 500px; overflow-y: auto;">
                        <!-- Products will be dynamically loaded here -->
                    </div>
                </div>

                <!-- Cart Section (Right) -->
                <div class="col-lg-6 col-md-8 col-12 mt-3 mt-md-0">
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="text-dark fw-bold">Customer (Optional)</label>
                            <input type="text" class="form-control" id="customer-name" placeholder="Enter customer name">
                        </div>
                        <div class="col-6">
                            <label class="text-dark fw-bold">Discount</label>
                            <select class="selectpicker form-control" id="discount" data-live-search="true">
                                <?php 
                                $discounts = getDiscounts($pdo); // Function to fetch discount from the database
                                foreach ($discounts as $discount) {
                                    echo "<option value='{$discount['discount_percentage']}'>{$discount['discount_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="text-dark fw-bold">Pay</label>
                            <input type="number" class="form-control" id="pay-amount" placeholder="Enter payment amount">
                        </div>
                        <div class="col-6">
                            <label class="text-dark fw-bold">Payment Method</label>
                            <select class="selectpicker form-control" id="payment-method" data-live-search="true">
                                <?php 
                                $payment_types = getPaymentTypes($pdo); // Function to fetch discount from the database
                                foreach ($payment_types as $payment_type) {
                                    echo "<option value='{$payment_type['payment_name']}'>{$payment_type['payment_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="cart" class="table table-striped">
                        </table>
                    </div>
                    <div class="row mb-2 mt-2">
                        <div class="col-lg-6 col-md-6">
                            <h5>Subtotal: <span id="totalSubtotal">₱0.00</span></h5>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h5>Discount: <span id="totalDiscount">₱0.00</span></h5>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h5>Total: <span id="totalBalance">₱0.00</span></h5>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <h5>Change: <span id="totalChange">₱0.00</span></h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-danger w-100" id="empty-cart">Empty Cart</button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-success w-100" id="pay">Pay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="receipt-preview" style="display:none;"></div>
<div id="confirm-print-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Print Receipt?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="receipt-preview-content" class="border p-2"></div>
                <p class="text-dark">Do you want to print this receipt?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="confirm-print">Yes</button>
            </div>
        </div>
    </div>
</div>

<style>
    .category-container {
        white-space: nowrap;
        overflow-x: auto;
        display: flex;
        gap: 5px;
        padding-bottom: 10px;
    }
    .category-container button {
        flex: 1;
        min-width: 120px;
    }

    .product-card {
        position: relative;
        border: 1px solid #4CAF50;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s ease-in-out;
        background-color: white;
        height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
    }

    .product-card img {
        width: 100%;
        height: 80px;
        object-fit: cover;
    }

    .product-card h5 {
        font-size: 14px;
        margin: 5px 0;
    }

    .product-card p {
        font-size: 14px;
        font-weight: bold;
    }

    .product-card:hover {
        background-color: #f8f9fa;
    }

    .quantity-badge {
        position: absolute;
        top: 5px;
        left: 5px;
        background-color: #ff4081;
        color: white;
        padding: 5px 10px;
        font-size: 14px;
        border-radius: 10px;
    }

    .qty-input-wrapper {
        display: flex;
        align-items: center;
    }

    .qty-input-wrapper button {
        width: 30px;
        height: 30px;
        padding: 0;
    }

    .qty-input-wrapper input {
        width: 60px;
        text-align: center;
    }

    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .table-responsive {
    max-height: 300px; /* Adjust the height to show only 3 entries */
    overflow-y: auto;
    }
</style>

<script>
$(document).ready(function () {
    var csrfToken = "<?php echo $_SESSION['csrf_token']; ?>";

    // Initialize DataTable
    let table = $('#cart').DataTable({
        paging: false,
        searching: false,
        autoWidth: false,
        info: false,
        columns: [
            { title: 'Item' },
            { title: 'Qty' },
            { title: 'Discount' },
            { title: 'Unit Price', className: 'd-none' },
            { title: 'Subtotal' },
            { title: 'Action' }
        ],
        rowCallback: function (row, data, index) {
            $('td', row).addClass('text-dark'); // Apply text-dark to all <td> in the row
        }
    });


    let products = [];
    let cart = [];


    // Fetch products from the server
    async function fetchProducts() {
    try {
        const response = await fetch('admin/process/admin_action.php', {
            method: 'POST', // Change to POST
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded', // Use the appropriate content type
            },
            body: new URLSearchParams({
                action: 'fetchProducts', // Include action in body
                csrf_token: csrfToken,  // Include CSRF token in the body
            })
        });

        // Log the raw response text for debugging purposes
        const text = await response.text();
        //console.log('Response Text:', text); // Check if the response is valid JSON

        // Check if the response is empty or invalid
        if (text.trim() === '') {
            throw new Error('Empty response from the server');
        }

        // Try to parse the JSON response manually
        const data = JSON.parse(text); 

        if (data.success) {
            products = data.products;
            loadProducts('all');
        } else {
            console.error('No products found');
        }
    } catch (error) {
        console.error('Error fetching products:', error);
    }
}

    // Load products into the product list
    function loadProducts(category) {
        let productContainer = $('#product-list');
        productContainer.empty();
        products.forEach(product => {
            if (category === 'all' || product.category === category) {
                let productCard = `
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-3">
                        <div class="product-card add-to-cart user-select-none" data-id="${product.id}">
                            <img src="${product.image}" class="img-fluid" alt="Product" width="60" height="60">
                            <h5 class="mt-2">${product.name}</h5>
                            <p class="text-success">₱${product.price.toFixed(2)}</p>
                        </div>
                    </div>`;
                productContainer.append(productCard);
            }
        });
    }

    fetchProducts();
    loadProducts('all');

    // Add to cart logic
    $('#product-list').on('click', '.add-to-cart', function () {
        let productId = $(this).data('id');
        let product = products.find(p => p.id === productId);
        let existingItem = cart.find(item => item.id === productId);

        // // Auto select discount based on product
        // $('#discount').val(product.discount);

        if (existingItem) {
            existingItem.qty++;
            existingItem.subtotal = existingItem.qty * existingItem.price * (1 - existingItem.discount / 100);
        } else {
            cart.push({
                id: productId,
                name: product.name,
                qty: 1,
                price: product.price,
                discount: product.discount,
                subtotal: product.price
            });
        }
        updateCart();
    });

    // Update cart display
    function updateCart() {
        table.clear();

        cart.forEach(item => {
            let row = [
                item.name,
                `<div class="qty-input-wrapper">
                    <button class="btn btn-outline-secondary btn-sm qty-decrease" data-id="${item.id}">-</button>
                    <input type="number" value="${item.qty}" class="form-control qty-input" data-id="${item.id}">
                    <button class="btn btn-outline-secondary btn-sm qty-increase" data-id="${item.id}">+</button>
                </div>`,
                `<select class="form-control discount-dropdown" data-id="${item.id}">
                    <option value="0" ${item.discount === 0 ? 'selected' : ''}>0%</option>
                    <option value="5" ${item.discount === 5 ? 'selected' : ''}>5%</option>
                    <option value="10" ${item.discount === 10 ? 'selected' : ''}>10%</option>
                </select>`,
                item.price.toFixed(2),
                item.subtotal.toFixed(2),
                `<button class="btn btn-danger btn-sm remove-item" data-id="${item.id}">Remove</button>`
            ];

            table.row.add(row);
        });

        table.draw();
        updateTotal();
    }

    // Update total, discount, and change
    function updateTotal() {
        let totalSubtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        let discountPercentage = parseFloat($('#discount').val()) / 100;
        let totalDiscount = totalSubtotal * discountPercentage;
        let totalAfterDiscount = totalSubtotal - totalDiscount;

        // Get the payment amount from the input field
        let payAmount = parseFloat($('#pay-amount').val()) || 0;  // Default to 0 if no value is entered
        let change = payAmount - totalAfterDiscount;

        // Update the UI with the calculated values
        $('#totalSubtotal').text(`₱${totalSubtotal.toFixed(2)}`);
        $('#totalDiscount').text(`₱${totalDiscount.toFixed(2)}`);
        $('#totalBalance').text(`₱${totalAfterDiscount.toFixed(2)}`);
        $('#totalChange').text(`₱${change.toFixed(2)}`);
        
    }

    $('#pay-amount').on('input', function () {
        updateTotal();
    });

    $('#discount').on('change', function () {
        updateTotal();
    });

    // Handle quantity change (input value)
    $('#cart').on('change input', '.qty-input', function () {
        let newQty = parseInt($(this).val());
        let itemId = $(this).data('id');
        let item = cart.find(item => item.id === itemId);
        item.qty = newQty;
        item.subtotal = newQty * item.price * (1 - item.discount / 100); // Recalculate subtotal
        updateCart();
    });

    
    // Handle quantity increase and decrease
    $('#cart').on('click', '.qty-increase', function () {
        let itemId = $(this).data('id');
        let item = cart.find(item => item.id === itemId);
        item.qty++;
        item.subtotal = item.qty * item.price * (1 - item.discount / 100); // Recalculate subtotal
        updateCart();
    });

    $('#cart').on('click', '.qty-decrease', function () {
        let itemId = $(this).data('id');
        let item = cart.find(item => item.id === itemId);
        if (item.qty > 1) {
            item.qty--;
            item.subtotal = item.qty * item.price * (1 - item.discount / 100); // Recalculate subtotal
            updateCart();
        }
    });

    // Remove item from cart
    $('#cart').on('click', '.remove-item', function () {
        let itemId = $(this).data('id');
        cart = cart.filter(item => item.id !== itemId);
        updateCart();
    });

    // Handle discount change for each item
    $('#cart').on('change', '.discount-dropdown', function () {
        let newDiscount = parseInt($(this).val());
        let itemId = $(this).data('id');
        let item = cart.find(item => item.id === itemId);
        item.discount = newDiscount;
        item.subtotal = item.qty * item.price * (1 - newDiscount / 100); // Recalculate subtotal
        updateCart();
    });

    // Empty cart
    $('#empty-cart').on('click', function () {
        cart = [];
        updateCart();
    });

    // Category selection
    $('.category-btn').on('click', function () {
        let category = $(this).data('category');

        // Remove active class from all buttons
        $('.category-btn').removeClass('btn-success').addClass('btn-light');

        // Add active class to the clicked button
        $(this).removeClass('btn-light').addClass('btn-success');

        // Load products for the selected category
        loadProducts(category);
    });

    function generateReceiptContent() {
        let date = new Date();
        let formattedDate = date.toISOString().slice(0, 10) + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        let invoiceNumber = Math.floor(100000 + Math.random() * 900000);
        let customerName = $('#customer-name').val() || "N/A";
        let paymentMethod = $('#payment-method').val();

        // Initialize variables for total calculations
        let totalSubtotal = 0;
        let totalDiscount = 0;
        let totalAmountAfterDiscount = 0;

        let receiptContent = `
            <div class='receipt text-dark' style='width: 100%; margin: 0; padding: 0px; font-family: Arial, sans-serif; text-align: left;'>
                <h4 style='text-align: center; margin: 5px 0;'>Takoyame Takoyaki</h4>
                <div style='border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 2px 0;'>
                    <p style="margin:5px 2px;"><b>Address:</b> Sto. Nino Plaridel, Bulacan <br><b>Contact No:</b> 09392887055</p>
                </div>
                <div style='border-top: none; border-bottom: 1px dashed black; padding: 2px 0;'>
                    <p style="margin:5px 2px;"><b>Invoice:</b> ${invoiceNumber} <br><b>Cashier:</b> Admin <br><b>Time:</b> ${formattedDate}</p>
                    <p><b>Customer:</b> ${customerName} <br><b>Payment Type:</b> ${paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1)}</p>
                </div>
                <table width='100%' style='border-collapse: collapse; margin-top: 5px; text-align: left;'>
                    <tr><th>Item</th><th>Qty</th><th>Price</th><th>Amt</th></tr>
        `;

        // Extract data from the DataTable
        let table = $('#cart').DataTable();

        table.rows().every(function() {
            let row = this.node();
            let name = $(row).find('td:nth-child(1)').text(); // Item name
            let qty = parseInt($(row).find('.qty-input').val()) || 1; // Quantity
            let priceText = $(row).find('td:nth-child(4)').text().replace(/[^\d.]/g, ''); // Price (remove non-numeric characters)
            let unitPrice = parseFloat(priceText); // Correct unit price (no change based on quantity)

            let discount = parseFloat($(row).find('.discount-dropdown').val()) || 0; // Discount percentage
            let subtotal = unitPrice * qty; // Subtotal before discount
            let itemDiscountAmount = (subtotal * discount) / 100; // Discount for this item

            let discountedSubtotal = subtotal - itemDiscountAmount; // Discounted subtotal for the item
            totalSubtotal += subtotal; // Total before discount
            totalAmountAfterDiscount += discountedSubtotal; // Total after discount applied
            let globalDiscount = parseFloat($('#discount').val()) || 0;
            totalDiscount = (totalAmountAfterDiscount * globalDiscount) / 100; // Total discount for all items

            let discountLabel = itemDiscountAmount > 0 ? `<br><i>Discount ${discount}% (-₱${itemDiscountAmount.toFixed(2)})</i>` : '';

            receiptContent += `
                <tr>
                    <td>${name}${discountLabel}</td>
                    <td>${qty}</td>
                    <td>₱${unitPrice.toFixed(2)}</td>
                    <td>₱${discountedSubtotal.toFixed(2)}</td>
                </tr>`;
        });

        // Calculate final values for the receipt
        let totalBalance = totalAmountAfterDiscount - totalDiscount; // This is the total after all discounts
        let paidAmount = parseFloat($('#pay-amount').val()) || 0; // Paid amount from user input
        let totalChange = paidAmount - totalBalance; // Change to be returned to the customer

        // Update the receipt content with correct totals
        receiptContent += `
            </table>
            <div style='border-top: 1px dashed black; padding: 5px 0; margin:5px 2px;'>
                <p>
                    <b>Subtotal:</b> ₱${totalAmountAfterDiscount.toFixed(2)}<br>
                    <b>Discount:</b> ₱${totalDiscount.toFixed(2)}<br>
                    <b>Total:</b> ₱${totalBalance.toFixed(2)}<br>
                    <b>Paid:</b> ₱${paidAmount.toFixed(2)}<br>
                    <b>Change:</b> ₱${totalChange.toFixed(2)}
                </p>
            </div>
            <p style='text-align: center; margin: 5px 0;'>Thank You - Please Come Again!</p>
        </div>
        `;

        return { content: receiptContent, invoiceNumber };
    }

    $(document).on('click', '#pay', function () {
        updateTotal();
        if (table.rows().count() <= 0) {
            Swal.fire({
                title: "Please add items to the cart first.",
                icon: "warning",
                draggable: true
            });
        } else {
            let totalBalance = parseFloat($('#totalBalance').text().replace(/[^\d.]/g, ''));
            let payAmount = parseFloat($('#pay-amount').val()) || 0;

            if (payAmount < totalBalance) {
                Swal.fire({
                    title: "Insufficient funds. Please check your payment amount.",
                    icon: "error",
                    draggable: true
                });
            } else {
                Swal.fire({
                    title: "Payment successful!",
                    icon: "success",
                    draggable: true
                }).then(() => {
                    console.log(cart);
                    // After clicking "OK" on the success message, generate the receipt content
                    let { content, invoiceNumber } = generateReceiptContent();
                    console.log(invoiceNumber);
                    // Insert receipt content into the modal's preview area
                    $('#receipt-preview-content').html(content);

                    // Show the print confirmation modal
                    $('#confirm-print-modal').modal('show');

                    // Reset and prepare for the next transaction if needed
                    // $('#pay-amount').val("");
                    // $('#customer-name').val("");
                    // $('#discount').prop('selectedIndex', 0);
                    // $('#payment-method').prop('selectedIndex', 0);
                    // table.clear().draw();
                    updateTotal();
                });
            }
        }
    });

    $('#confirm-print').click(function() {
    let printWindow = window.open('', '', 'width=300,height=600');

        // Inject CSS to remove margins
        let style = `
            <style>
            @media print {
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                }
                .receipt {
                    width: 100%; /* Use the full width of the paper */
                    margin: 0;
                    padding: 0;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                td, th {
                    padding: 5px;
                    font-size: 9px; /* Set font size of the items to 9px */
                }
                h4 {
                    margin: 0;
                    font-size: 12px; /* Optional: Set the title font size to 9px */
                }
                .receipt p {
                    font-size: 9px; /* Optional: Set paragraph font size to 9px */
                }
            }
            </style>
        `;

        printWindow.document.write(style + $('#receipt-preview-content').html());
        printWindow.document.close();

        printWindow.print();

        setTimeout(function() {
            printWindow.close(); // Close the window after printing
        }, 1000);
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
