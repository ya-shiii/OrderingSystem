<?php
$name = 'dateTime';
$value = date('Y-m-d H:i:s');

// Set the cookie
setcookie($name, $value);
session_start();

if (!isset($_SESSION['usertype'])) {
    // Redirect to the login page
    echo "<script>alert('You need to login!'); window.location.href='login.php';</script>";
    exit();
}

$usertype = $_SESSION['usertype'];
$username = $_SESSION['username'];
$id = $_SESSION['user_id'];

?>

<!doctype html>
<html lang="en">

<head>
    <title>Shii Grills</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="assets/img/SG_Logo.png" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .drop-shadow-lg {
            box-shadow: 1px 4px 4px 0 rgba(0, 0, 0, 0.50);
        }

        .drop-shadow-xl {
            box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, 0.50);
        }

        .floating-animation {
            animation: floating 1s ease-in-out;
            opacity: 1;
        }

        .floating-animation-down {
            animation: floating-down 1s ease-in-out;
            opacity: 1;
        }

        .current-page {
            color: #b45dc9 !important;
        }

        @keyframes floating {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floating-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .category-item {
            cursor: pointer;
        }

        .category-item.active {
            background-color: #a99cf0;
        }

        .category-item.active .category-link {
            color: white;
        }

        .category-link {
            display: block;
            color: black;
            text-decoration: none;
        }

        .category-link:hover {
            text-decoration: underline;
        }

        /* CSS for customizing the scrollbar */
        .item-display {
            scrollbar-width: thin;
            scrollbar-color: #888888 #dddddd;
        }

        .item-display::-webkit-scrollbar {
            width: 8px;
        }

        .item-display::-webkit-scrollbar-track {
            background-color: #dddddd;
        }

        .item-display::-webkit-scrollbar-thumb {
            background-color: #b45dc9;
        }
    </style>

</head>

<body>
    <!-- Navbar -->
    <div id="navbar" class="flex items-center justify-between bg-[#4e4485] py-2 px-16 absolute w-full">
        <a href="../OrderingSystem">
            <div class="flex items-center">

                <img class="h-10 mr-2" src="assets/img/SG_Logo.png" alt="Logo">
                <h1 class="text-white font-bold text-xl">SHII GRILLS</h1>

            </div>
        </a>
        <div class="flex">
            <p class="text-white font-bold uppercase text-xl mx-2 current-page">
                <a href="index.php">
                    Place order
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2">
                <a href="orders.php">
                    orders list
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2">
                <a href="items-list.php">
                    Items
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2">
                <a href="statistics.php">
                    Sales statistics
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2">
                <a href="users-list.php">
                    users
                </a>
            </p>
        </div>
        <div>
            <a href="api/logout.php">
                <p class="btn text-white bg-[#dc95ed] px-5 py-1 rounded-full font-bold uppercase">Logout</p>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-200 min-h-screen flex flex-col items-start justify-center py-8">
        <div id="main-content" class="rounded-2xl w-11/12 bg-[#a99cf0] drop-shadow-xl p-8 mx-auto">
            <div class="flex">
                <!-- Category Sidebar -->
                <div class="w-full md:w-1/4 bg-[#f6f7f9] rounded-lg shadow-xl p-4">
                    <h2 class="text-xl font-semibold mb-4">Categories</h2>
                    <ul class="space-y-2 category-list">
                        <!-- Categories will be populated dynamically -->
                    </ul>
                </div>

                <!-- Item Display -->
                <div
                    class="w-full md:w-2/4 bg-white rounded-lg shadow-xl p-4 ml-4 item-display grid grid-cols-2 gap-4 h-96 overflow-y-auto">
                    <!-- Items will be populated dynamically -->
                </div>


                <!-- Cart -->
                <div class="w-full md:w-1/4 bg-white rounded-lg shadow-xl p-4 ml-4">
                    <h2 class="text-xl font-semibold mb-4">Order Cart</h2>
                    <div class="cart-items">
                        <!-- Cart items will be populated dynamically -->
                        <table class="w-full">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                error_reporting(E_ALL);
                                ini_set('display_errors', 1);

                                require_once 'api/db_connect.php';

                                // Retrieve cart items from the temp_order table
                                $cartQuery = "SELECT * FROM temp_order";
                                $cartResult = mysqli_query($conn, $cartQuery);
                                $totalPrice = 0; // Initialize total price variable
                                
                                if ($cartResult && mysqli_num_rows($cartResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($cartResult)) {
                                        $itemName = $row['item_name'];
                                        $quantity = $row['quantity'];
                                        $itemPrice = $row['price'];

                                        // Display each cart item in a table row
                                        echo "<tr class='text-center'>";
                                        echo "<td>$itemName</td>";
                                        echo "<td>$quantity</td>";
                                        echo "</tr>";

                                        // Compute the total price by adding the price of each item
                                        $totalPrice += $itemPrice;
                                    }


                                } else {
                                    // No items in the cart
                                    echo "<tr><td colspan='2'>No items in the cart</td></tr>";
                                }

                                // Free the result set
                                mysqli_free_result($cartResult);
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <p class="font-semibold">Total Price:</p>
                        <?php
                        // Display the total price
                        echo "<p class='font-bold' id='total-price'>$" . number_format($totalPrice, 2) . "</p>";
                        ?>
                        <button class="checkout-btn bg-[#b45dc9] hover:bg-[#dc95ed] text-white font-bold py-2 px-4 
                            rounded-lg mt-4">Checkout</button>
                        <button class="Clear-btn bg-gray-500 hover:bg-[#dc95ed] text-white font-bold py-2 px-4 
                            rounded-lg mt-4" onclick="openClearModal('delete-modal')">Clear</button>

                    </div>

                </div>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center"
        id="QuantityModal">
        <div class="modal-content bg-white rounded-lg shadow-xl p-8 z-50">
            <h2 class="text-2xl font-semibold mb-4">Order Item</h2>
            <div id="item-details" class="mb-2"></div>
            <form id="order-form" method="POST" action="api/addToCart.php">

                <div class="mb-4 flex">
                    <label class="block text-lg font-semibold my-auto mr-2" for="quantity">Quantity: </label>
                    <input type="number" id="quantity_id" name="quantity_id" hidden>
                    <input class="w-24 px-4 py-2 border border-gray-300 rounded-lg" type="number" id="quantity"
                        name="quantity" required>
                </div>
                <button class="submit-btn bg-[#b45dc9] hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg"
                    type="submit">Submit</button>
                <button id="closeQuantityModal"
                    class="close-btn bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg ml-4"
                    type="button">Close</button>
            </form>
        </div>
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('QuantityModal')"></div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75" onclick="closeModal('delete-modal')"></div>
        <div class="bg-white rounded-lg p-8 relative">
            <h2 class="text-2xl font-bold mb-4">Are you sure you want to clear cart?</h2>
            <form id="delete-form" action="api/clear_cart.php" method="POST">
                <input type="hidden" name="delete-id" id="delete-id">
                <div class="flex justify-end">
                    <button id="submit-delete" type="submit"
                        class="btn rounded-full bg-[#4e4485] py-1 px-3 mr-3 text-white">Submit</button>
                    <button id="cancel-delete" type="button" onclick="closeModal('delete-modal')"
                        class="btn rounded-full bg-gray-400 py-1 px-3 text-white mr-2">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Retrieve the categories and items from the server
        function loadCategories() {
            // Simulate the AJAX request to fetch categories from the server
            setTimeout(function () {
                // Dummy data for categories
                var categories = ['All items', 'Beverage', 'Meal', 'Snack', 'Add-on'];

                // Append categories to the sidebar
                var categoryList = document.querySelector('.category-list');
                categories.forEach(function (category) {
                    var categoryItem = document.createElement('li');
                    categoryItem.classList.add('category-item');
                    categoryItem.textContent = category;
                    categoryList.appendChild(categoryItem);
                });

                // Add click event listener to the category items
                var categoryItems = document.querySelectorAll('.category-item');
                categoryItems.forEach(function (item) {
                    item.addEventListener('click', function () {
                        // Remove the active class from all category items
                        categoryItems.forEach(function (item) {
                            item.classList.remove('active');
                        });

                        // Add the active class to the clicked category item
                        this.classList.add('active');

                        // Load items for the selected category
                        loadItems(this.textContent);
                    });
                });

                // Initially load items for the first category
                var firstCategoryItem = document.querySelector('.category-item');
                firstCategoryItem.classList.add('active');
                loadItems(firstCategoryItem.textContent); // Pass the first category
            }, 500);
        }

        // Retrieve items for a specific category from the server
        function loadItems(category) {
            // Simulate the AJAX request to fetch items from the server
            setTimeout(function () {
                // Make an AJAX request to load_items.php
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'api/load_items.php', true);
                xhr.onload = function () {
                    console.log('Response received:', xhr.status);
                    if (xhr.status === 200) {
                        // Parse the response JSON
                        var items = JSON.parse(xhr.responseText);

                        // Filter items based on the selected category
                        if (category !== 'All items') {
                            items = items.filter(function (item) {
                                return item.item_type === category;
                            });
                        }

                        // Generate item cards HTML and append to the item display area
                        var itemDisplay = document.querySelector('.item-display');
                        itemDisplay.innerHTML = '';
                        items.forEach(function (item) {
                            var itemCard = document.createElement('div');
                            itemCard.classList.add('item-card');
                            itemCard.innerHTML = `
                                <h3 class="font-semibold mb-2">${item.item_name}</h3>
                                <p>${item.description}</p>
                                <p>Price: $${item.item_price}</p>
                                <button class="order-btn bg-[#b45dc9] hover:bg-[#dc95ed] text-white font-bold py-2 px-4 rounded-lg mt-4" 
                                onclick="openModal('${item.item_name}', ${item.item_id})">Order</button>
                            `;
                            itemDisplay.appendChild(itemCard);
                        });
                    } else {
                        console.error('Error loading items:', xhr.status);
                    }
                };
                xhr.send();
            }, 500);
        }



        // Submit the order form
        function submitOrderForm(e) {
            e.preventDefault();

            // Retrieve the quantity from the form
            var quantityInput = document.getElementById('quantity');
            var quantity = parseInt(quantityInput.value);

            // Validate the quantity
            if (isNaN(quantity) || quantity <= 0) {
                alert('Please enter a valid quantity.');
                return;
            }

            // Perform additional processing or submit the order
            // ...

            // Clear the form
            quantityInput.value = '';

            // Close the modal
            closeModal();
        }

        // Open the order modal
        function openModal(itemName, itemId) {
            var modal = document.querySelector('#QuantityModal');
            modal.classList.remove('hidden');

            var itemDetailsElement = document.querySelector('#item-details');
            itemDetailsElement.innerHTML = `
                    <p>Item: <span class='font-bold'>${itemName}</span></p>
                `;

            var quantityIdInput = document.querySelector('#quantity_id');
            quantityIdInput.value = itemId;
        }

        function openClearModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('hidden');
        }

        // Close quantity item Modal
        $('#closeQuantityModal').click(function () {
            $('#QuantityModal').addClass('hidden');
        });
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }

        // Initialize the menu
        function initializeMenu() {
            // Load categories and items
            loadCategories();

            // Add event listeners
            var orderBtns = document.querySelectorAll('.order-btn');
            var submitBtn = document.getElementById('submit-btn');
            var closeBtn = document.getElementById('close-btn');

            orderBtns.forEach(function (btn) {
                btn.addEventListener('click', openModal);
            });

            submitBtn.addEventListener('click', submitOrderForm);
            closeBtn.addEventListener('click', closeModal);
        }

        // Initialize the menu when the DOM is ready
        document.addEventListener('DOMContentLoaded', function () {
            initializeMenu();
        });
        // Add event listener to the Checkout button
        var checkoutBtn = document.querySelector('.checkout-btn');
        checkoutBtn.addEventListener('click', function () {
            // Redirect to api/place_order.php
            window.location.href = 'api/place_order.php';
        });

        $(document).ready(function () {
            // Apply floating animation to the main content after page load
            setTimeout(function () {
                $('#main-content').addClass('floating-animation');
            }, 1000);

            // Apply floating-down animation to the navbar after page load
            setTimeout(function () {
                $('#navbar').addClass('floating-animation-down');
            }, 0);

            // Apply floating-down animation to the add button after page load
            setTimeout(function () {
                $('#add-button').addClass('floating-animation-down');
            }, 150);
        });
    </script>

</body>

</html>