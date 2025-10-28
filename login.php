<?php
spl_autoload_register(function ($class)
{include "classes/".$class.".php";});
$session = new UserSessionHandler();
//look for logout keyword and log the user out if == 1
if (isset($_GET['logout']) && $_GET['logout'] == 1)
{
    $logout = new Logout();
    $msg = "You are now logged out.";
}
elseif ($session->logged_in())
{
    $redirect = new Redirector("profile.php");
}
// START FORM PROCESSING
if (isset($_POST['submit']))
{ // Form has been submitted.
    $login = new LoginUser($_POST['email'],$_POST['user_password']);
    $msg = $login->message;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Login</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Creepster&family=EB+Garamond:wght@400;700&display=swap');
        body {
            font-family: 'EB Garamond', serif;
            background-color: #0f0a1a;
            color: #e0d6eb;
        }
        .horror-font {
            font-family: 'Creepster', cursive;
        }
        .purple-dark {
            background-color: #1a1029;
        }
        .purple-light {
            background-color: #2a1a4a;
        }
        .moss-green {
            background-color: #1a2910;
        }
        .blood-red {
            color: #ff3a3a;
        }
        .glow {
            text-shadow: 0 0 5px #9b59b6, 0 0 10px #9b59b6;
        }
    </style>
</head>
<body class="min-h-screen">
<!-- Navigation -->
<nav class="purple-dark sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <a href="index.php" class="flex items-center">
                    <span class="horror-font text-3xl blood-red glow">Midnight Scream</span>
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-white hover:text-purple-300">Home</a>
                <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
                <a href="about.php" class="text-white hover:text-purple-300">About Us</a>
                <a href="#" class="text-white hover:text-purple-300">Login</a>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Login Form -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-md">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h1 class="horror-font text-4xl blood-red mb-8 text-center">Enter the crypt</h1>

            <?php if (isset($msg) && !empty($msg)): ?>
                <div class="mb-4 p-3 rounded bg-red-900 text-red-200 border border-red-700 text-center">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <div>
                    <label for="email" class="block mb-2">Email</label>
                    <input type="text" id="email" name="email" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="user_password" class="block mb-2">Password</label>
                    <input type="password" id="user_password" name="user_password" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" name="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Login <i data-feather="log-in" class="inline ml-2"></i>
                </button>
                <div class="text-center">
                    <a href="register.php" class="text-purple-300 hover:text-purple-200">
                        Haven't signed over your soul yet?  <br> <b>Register here!</b>
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="purple-dark py-8">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <span class="horror-font text-2xl blood-red">Midnight Scream</span>
                <p class="text-sm mt-1">© 2023 All Rights Reserved</p>
            </div>
            <div class="flex flex-col md:flex-row md:space-x-8 space-y-2 md:space-y-0 text-sm">
                <a href="#" class="hover:text-purple-300">Privacy Policy</a>
                <a href="#" class="hover:text-purple-300">Terms of Service</a>
                <a href="#" class="hover:text-purple-300">Contact Us</a>
                <a href="admin/login.php" class="hover:text-purple-300">Admin</a>
            </div>
        </div>
    </div>
</footer>

<script>
    feather.replace();
</script>
</body>
</html>
