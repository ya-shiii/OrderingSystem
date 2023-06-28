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
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="assets/datatables.min.css" />

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery -->
    <script src="assets/jquery/jquery-3.6.4.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="assets/datatables/datatables.min.js"></script>

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
    </style>

</head>

<body>
    <!-- Navbar -->
    <div id="navbar" class="flex items-center justify-between bg-[#4e4485] py-2 px-16 w-full">
        <a href="index.php">
            <div class="flex items-center">

                <img class="h-10 mr-2" src="assets/img/SG_Logo.png" alt="Logo">
                <h1 class="text-white font-bold text-xl">SHII GRILLS</h1>

            </div>
        </a>
        <div class="flex">
            <p class="text-white font-bold uppercase text-xl mx-2">
                <a href="index.php">
                    Place order
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2 current-page">
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

            <p class="text-white font-bold uppercase text-xl mx-2 ">
                <a href="statistics.php">
                    Sales statistics
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2 ">
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
        <div id="addbtn" class="ml-14 mb-3">
            <a href="#">
                <p class="btn text-white bg-[#4e4485] px-6 py-3 rounded-full font-bold uppercase">Clear list</p>
            </a>
        </div>
        <div id="main-content" class="rounded-2xl w-11/12 bg-[#a99cf0] drop-shadow-xl p-8 mx-auto">
            <table id="items-table" class="table bg-[#a99cf0]" style="width:100%">
                <thead>
                    <tr>
                        <th>Queue</th>
                        <th>Items</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will be populated dynamically using JavaScript -->
                </tbody>
                <tfoot>
                    <tr>
                        <th>Queue</th>
                        <th>Items</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            // Apply floating animation to the main content after page load
            setTimeout(function () {
                $('#main-content').addClass('floating-animation');
            }, 0);

            // Apply floating-down animation to the navbar after page load
            setTimeout(function () {
                $('#navbar, #addbtn').addClass('floating-animation-down');
            }, 0);

            // Open Add item Modal
            $('#addbtn a').click(function (e) {
                e.preventDefault();
                $('#add-item-modal').removeClass('hidden');
            });

            // Close Add item Modal
            $('#cancel-add-item').click(function () {
                $('#add-item-modal').addClass('hidden');
            });

            // Close Edit item Modal
            $('#cancel-edit-item').click(function () {
                $('#edit-item-modal').addClass('hidden');
            });

            // Close Edit item Modal
            $('#cancel-delete-item').click(function () {
                $('#delete-item-modal').addClass('hidden');
            });

            // Initialize DataTable
            $('#items-table').DataTable({
                ajax: {
                    url: 'api/orders.php',
                    dataSrc: 'data' // Use 'data' as the property to retrieve the JSON data
                },
                columns: [
                    { data: '0', className: 'text-center bg-[#a99cf0]' }, // Use the index to access the 'ITEM ID' column
                    { data: '1', className: 'text-center bg-[#a99cf0]' }, // Use the index to access the 'ITEM NAME' column
                    {
                        data: '2', className: 'text-center bg-[#a99cf0]', render: function (data, type, row) {
                            return '₱ ' + data; // Append "Php" before the data
                        }
                    }, // Use the index to access the 'ITEM PRICE' column
                    { data: '3', className: 'text-center bg-[#a99cf0]' }, // Use the index to access the 'ITEM TYPE' column
                    {
                        data: '4', // Use the index to access the 'OPTIONS' column
                        className: 'text-center bg-[#a99cf0]',
                        render: function (data, type, row) {
                            return data;
                        }
                    }
                ]
            });




        });

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('hidden');
        }

        function openEditModal(itemId) {
            // Show the edit item modal
            document.getElementById("edit-item-modal").classList.remove("hidden");

            // Make an AJAX request to fetch item details
            $.ajax({
                url: "api/edit_item.php",
                type: "GET",
                data: { item_id: itemId },
                success: function (response) {
                    // Parse the JSON response
                    var item = JSON.parse(response);

                    // Populate the form fields with item details
                    $("#edit-item-id").val(item.item_id);
                    $("#edit-item_price").val(item.item_price);
                    $("#edit-item_name").val(item.item_name);
                    $("#edit-item_type").val(item.item_type);

                    // Show the Edit item modal
                    showModal("edit-item-modal");
                },
                error: function (xhr, status, error) {
                    // Handle the error if the request fails
                    console.log(error);
                }
            });
        }


        // for deleting item
        function openDeleteModal(itemId) {
            // Show the delete item modal
            document.getElementById("delete-item-modal").classList.remove("hidden");

            // Perform AJAX request to retrieve item details
            $.ajax({
                url: 'api/get_item.php',
                type: 'POST',
                data: { itemId: itemId },
                success: function (response) {
                    // Parse the JSON response
                    var item = JSON.parse(response);

                    // Update the delete-item-modal form with the retrieved values
                    $('#delete-item-id').val(item.item_id);
                    $('#delete-item_name').val(item.item_name);
                    $('#delete-item_type').val(item.item_type);

                    // Open the delete-item-modal
                    $('#delete-item-modal').removeClass('hidden');
                },
                error: function () {
                    // Handle error case
                    console.log('Error occurred while retrieving item details.');
                }
            });
        }


    </script>


</body>

</html>