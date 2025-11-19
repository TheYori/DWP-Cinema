<?php
spl_autoload_register(function ($class)
{include "../classes/".$class.".php";});
$session = new AdminSessionHandler();
//look for logout keyword and log the user out if == 1
$logout = filter_input(INPUT_GET, 'logout', FILTER_VALIDATE_INT);

if ($logout === 1)
{
    $logoutAction = new Logout();
    $msg = "You are now logged out.";
}
elseif ($session->logged_in())
{
    $redirect = new Redirector("company.php");
}

// START FORM PROCESSING
if (isset($_POST['submit']))
{ // Form has been submitted.
    $login = new LoginAdmin($_POST['username'],$_POST['password']);
    $msg = $login->message;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Login</title>
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
                <a href="../index.php" class="flex items-center">
                    <span class="horror-font text-3xl blood-red glow">Midnight Scream</span>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="../index.php" class="text-white hover:text-purple-300">Back to Main Site</a>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Admin Login Form -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-md">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h1 class="horror-font text-4xl blood-red mb-8 text-center">Admin Portal</h1>
            <p class="text-center mb-6 italic">Restricted access - authorized personnel only</p>

            <?php if (isset($msg) && !empty($msg)): ?>
                <div class="mb-4 p-3 rounded bg-red-900 text-red-200 border border-red-700 text-center">
                    <?php echo $msg; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block mb-2">Username</label>
                    <input type="text" id="username" name="username" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="password" class="block mb-2">Password</label>
                    <input type="password" id="password" name="password" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" name="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Login <i data-feather="log-in" class="inline ml-2"></i>
                </button>
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
                <a href="../index.php" class="hover:text-purple-300">Back to Main Site</a>
            </div>
        </div>
    </div>
</footer>

<script>
    feather.replace();
</script>
</body>
</html>
