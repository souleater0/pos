<div class="body-wrapper-inner">
    <div class="container-fluid mw-100">
        <div class="card shadow-sm p-3">
            <h4>Category</h4>

            <!-- Responsive Category Buttons -->
            <div class="category-container d-flex flex-nowrap overflow-auto">
                <button class="btn btn-success category-btn" data-category="all">All Categories</button>
                <button class="btn btn-light category-btn" data-category="main">Main Dish</button>
                <button class="btn btn-light category-btn" data-category="addons">Addons</button>
            </div>

            <div class="row mt-3">
                <!-- Product List (Left) -->
                <div class="col-lg-6 col-md-4 col-12">
                    <div class="row" id="product-list">
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
                            <select class="form-control" id="discount">
                                <option value="0">No Discount</option>
                                <option value="5">5% Discount</option>
                                <option value="10">10% Discount</option>
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
                            <select class="form-control" id="payment-method">
                                <option value="cash">Cash</option>
                                <option value="gcash">Gcash</option>
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
</style>

<script>
$(document).ready(function () {
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


    let products = [
        { id: 1, name: "Bacon Cheesy Takoyaki", price: 105.00, discount: 0, image: "assets/images/products/image.png", category: "all" },
        { id: 2, name: "Ultimate Cheesy Takoyaki", price: 85.00, discount: 0, image: "assets/images/products/image.png", category: "main" },
        { id: 3, name: "Baby Octo Takoyaki", price: 120.00, discount: 0, image: "assets/images/products/image.png", category: "addons" }
    ];

    let cart = [];

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

    loadProducts('all');

    // Add to cart logic
    $('#product-list').on('click', '.add-to-cart', function () {
        let productId = $(this).data('id');
        let product = products.find(p => p.id === productId);
        let existingItem = cart.find(item => item.id === productId);

        // Auto select discount based on product
        $('#discount').val(product.discount);

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
        loadProducts(category);
    });

    $(document).on('click', '#pay', function () {
        if (table.rows().count() <= 0) {
                Swal.fire({
                    title: "Please add items to the cart first.",
                    icon: "warning",
                    draggable: true
                });
        } else {
            
        }
    });
});

</script>
