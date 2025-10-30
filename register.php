<?php
spl_autoload_register(function ($class)
{include "classes/".$class.".php";});
$session = new UserSessionHandler();

// START FORM PROCESSING
if (isset($_POST['submit'])) {
    $newUser = new RegisterNewUser($_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['birth_date'], $_POST['email'], $_POST['street'], $_POST['postal_code'], $_POST['city'], $_POST['pass']);
    $msg = $newUser->message;

    if ($msg === "User registered successfully.") {
        $redirect = new Redirector("login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Register</title>
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
                <a href="news.php" class="text-white hover:text-purple-300">News</a>
                <a href="about.php" class="text-white hover:text-purple-300">About Us</a>
                <a href="login.php" class="text-white hover:text-purple-300">Login</a>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Register Form -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-2xl">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h1 class="horror-font text-4xl blood-red mb-8 text-center">Join the Nightmare</h1>
            <?php
            if (!empty($msg)) {
                echo "<p><strong>" . htmlspecialchars($msg) . "</strong></p>";
            }
            ?>
            <form action="" method="post" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="fname" class="block mb-2">First Name</label>
                        <input type="text" name="fname" id="fname" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="lname" class="block mb-2">Last Name</label>
                        <input type="text" name="lname" id="lname" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" name="email" id="email" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone" class="block mb-2">Phone Number</label>
                        <input type="text" name="phone" id="phone" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="birth_date" class="block mb-2">Date of Birth</label>
                        <input type="date" name="birth_date" id="birth_date" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <div>
                        <label for="pass" class="block mb-2">Password</label>
                        <input type="password" name="pass" id="pass" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <label for="street" class="block mb-2">Street Address</label>
                    <input type="text" name="street" id="street" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="postal_code" class="block mb-2">Postal Code</label>
                        <input type="number" name="postal_code" id="postal_code" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="city" class="block mb-2">City</label>
                        <input type="text" name="city" id="city" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <button type="submit" name="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Register <i data-feather="user-plus" class="inline ml-2"></i>
                </button>

                <div class="text-center">
                    <a href="login.php" class="text-purple-300 hover:text-purple-200">
                        Already have an account? <br> <b>Login here!</b>
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
