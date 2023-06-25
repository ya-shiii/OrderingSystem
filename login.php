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
  </style>

</head>

<body class="flex justify-center items-center h-screen">
  <div class="container">
    <div class="flex justify-center items-center">
      <div class="w-full md:w-4/12">
        <div id="loginForm" class="card rounded-lg bg-[#4e4485] drop-shadow-xl p-5 floating-animation">
          <div class="card-body">
            <div class="mx-auto flex items-center">
              <img class="mx-auto h-16" src="assets/img/SG_Logo.png" alt="Image Description">
            </div>

            <h2 class="card-title text-center font-bold text-2xl text-white font-black mb-3">SHII Grills</h2>
            <form action="api/login.php" method="POST">
              <div class="form-group w-full">
                <input type="text" class="form-control font-bold w-full h-8 mb-5 px-2 autocomplete-none" id="uname"
                  name="uname" placeholder="Enter employee username">
              </div>
              <div class="form-group">
                <input type="password" class="form-control font-bold w-full h-8 mb-5 px-2 autocomplete-none" id="pass"
                  name="pass" placeholder="Enter your password">
              </div>
              <div class="flex justify-end mb-2 mt-10">
                <button type="submit"
                  class="btn rounded-full text-white drop-shadow-lg font-bold uppercase py-2 w-40 bg-gradient-to-r from-[#2c4ec9] to-pink-500">Login</button>
              </div>
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#uname').val(''); // Clear the input field's value on page load

      // Apply floating animation to the login form after page load
      setTimeout(function () {
        $('#loginForm').addClass('floating-animation');
      }, 1000);
    });
  </script>
</body>

</html>