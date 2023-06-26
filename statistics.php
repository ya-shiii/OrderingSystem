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
    </style>

</head>

<body>
    <!-- Navbar -->
    <div id="navbar" class="flex items-center justify-between bg-[#4e4485] py-2 px-16 absolute w-full">
        <a href="index.php">
            <div class="flex items-center">

                <img class="h-10 mr-2" src="assets/img/SG_Logo.png" alt="Logo">
                <h1 class="text-white font-bold text-xl">SHII GRILLS</h1>

            </div>
        </a>
        <div class="flex">
            <p class="text-white font-bold uppercase text-xl mx-2 ">
                <a href="index.php">
                    orders
                </a>
            </p>

            <div class="border border-r-white-500 mx-2"></div>

            <p class="text-white font-bold uppercase text-xl mx-2">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a href="#">
        <div id="add-button"
            class="btn z-10 fixed bottom-4 right-14 w-20 h-20 bg-[#2f58d4] rounded-full flex items-center drop-shadow-xl justify-center p-1">
            <p class="text-white text-xl">+</p>
        </div>
    </a>
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
        });
    </script>

</body>

</html>