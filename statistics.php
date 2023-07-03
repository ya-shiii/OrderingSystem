<?php
$name = 'dateTime';
$value = date('Y-m-d H:i:s');

// Set the cookie
setcookie($name, $value);
session_start();
if ($_SESSION['usertype'] != 'Admin') {
    if (!isset($_SESSION['usertype'])) {
        // Redirect to the login page
        echo "<script>alert('You need to login!'); window.location.href='login.php';</script>";
        exit();
    } elseif ($_SESSION['usertype'] == 'Kitchen Staff') {
        echo "<script>alert('Only admins have access to this page');</script>";
        header("Location: orders.php");
        exit();
    } else {
        echo "<script>alert('Only admins have access to this page');</script>";
        header("Location: index.php");
        exit();
    }
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

            <p class="text-white font-bold uppercase text-xl mx-2">
                <a href="orders.php">
                    orders list
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2 ">
                <a href="items-list.php">
                    Items
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2 current-page">
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
    <div class="flex justify-center items-center h-screen">
        <div class="container">
            <div class="flex justify-center items-center">
                <div class="w-full">
                    <div id="main-content" class="card rounded-lg bg-[#4e4485] drop-shadow-xl p-5 floating-animation">
                        <div class="card-body">
                            <div class="mx-auto flex items-center">
                                <img class="mx-auto h-16" src="assets/img/SG_Logo.png" alt="Image Description">
                            </div>

                            <h2 class="card-title text-center font-bold text-2xl text-white font-black mb-3">SHII Grills
                            </h2>

                            <div class="text-white mt-5">
                                <h3 class="text-xl font-bold mb-2">Summary of Sales</h3>

                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                    <div id="today-sales" class="bg-gray-800 rounded-lg p-4">

                                    </div>
                                    <div id="week-sales" class="bg-gray-800 rounded-lg p-4">

                                    </div>
                                    <div id="prev-week-sales" class="bg-gray-800 rounded-lg p-4">

                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 gap-4 mt-4 px-16">
                                    <div id="month-sales" class="bg-gray-800 rounded-lg p-4">

                                    </div>
                                    <div id="prev-month-sales" class="bg-gray-800 rounded-lg p-4">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
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

            $.ajax({
                url: "api/stats.php",
                method: "GET",
                dataType: "json",
                success: function (response) {
                    // Populate today's sales information
                    $("#today-sales").html(`
                        <h4 class="text-lg font-semibold mb-2">Today's Sales</h4>
                        <p>Total Earnings: ₱${response.today.totalEarnings}</p>
                        <p>Top Selling: ${response.today.topSelling}</p>
                        <p>Sold Quantity: ${response.today.soldQuantity}</p>
                        <p>Least sold: ${response.today.leastSoldItem}</p>
                    `);

                    // Populate this week's sales information
                    $("#week-sales").html(`
                        <h4 class="text-lg font-semibold mb-2">This Week's Sales</h4>
                        <p>Total Earnings: ₱${response.week.totalEarnings}</p>
                        <p>Top Selling: ${response.week.topSelling}</p>
                        <p>Sold Quantity: ${response.week.soldQuantity}</p>
                        <p>Least sold: ${response.week.leastSoldItem}</p>
                    `);

                    // Populate previous week's sales information
                    $("#prev-week-sales").html(`
                        <h4 class="text-lg font-semibold mb-2">Previous Week's Sales</h4>
                        <p>Total Earnings: ₱${response.prevweek.totalEarnings}</p>
                        <p>Top Selling: ${response.prevweek.topSelling}</p>
                        <p>Sold Quantity: ${response.prevweek.soldQuantity}</p>
                        <p>Least sold: ${response.prevweek.leastSoldItem}</p>
                    `);

                    // Populate this month's sales information
                    $("#month-sales").html(`
                        <h4 class="text-lg font-semibold mb-2">This Month's Sales</h4>
                        <p>Total Earnings: ₱${response.month.totalEarnings}</p>
                        <p>Top Selling: ${response.month.topSelling}</p>
                        <p>Sold Quantity: ${response.month.soldQuantity}</p>
                        <p>Least sold: ${response.month.leastSoldItem}</p>
                    `);

                    // Populate previous month's sales information
                    $("#prev-month-sales").html(`
                        <h4 class="text-lg font-semibold mb-2">Previous Month's Sales</h4>
                        <p>Total Earnings: ₱${response.prevmonth.totalEarnings}</p>
                        <p>Top Selling: ${response.prevmonth.topSelling}</p>
                        <p>Sold Quantity: ${response.prevmonth.soldQuantity}</p>
                        <p>Least sold: ${response.prevmonth.leastSoldItem}</p>
                    `);
                },
                error: function () {
                    console.log("Error occurred while fetching sales data.");
                }
            });
        });
    </script>

</body>

</html>