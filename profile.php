<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$session->confirm_logged_in()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Profile</title>
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
                <a href="#" class="text-white hover:text-purple-300">Profile</a>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Profile Content -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="purple-dark rounded-lg shadow-xl overflow-hidden mb-8">
            <div class="p-6">
                <h1 class="horror-font text-4xl blood-red mb-4">Your Dark Profile</h1>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold">Vincent Graves</h2>
                        <p class="text-gray-400">Member since October 31, 2020</p>
                    </div>
                    <div class="flex space-x-3">
                        <button class="purple-light hover:bg-purple-800 text-white py-2 px-4 rounded transition duration-300">
                            <i data-feather="edit" class="mr-2"></i> Edit Profile
                        </button>
                        <button class="purple-light hover:bg-purple-800 text-white py-2 px-4 rounded transition duration-300">
                            <i data-feather="key" class="mr-2"></i> Change Password
                        </button>
                        <a href="login.php?logout=1" class="moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                            <i data-feather="log-out" class="mr-2"></i> Log Out
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="purple-dark rounded-lg shadow-xl overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="horror-font text-2xl blood-red mb-6">Personal Information</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-bold mb-2">Name</h3>
                        <p>Vincent Graves</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Email</h3>
                        <p>vincent@graves.com</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Phone</h3>
                        <p>+1 666-666-6666</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-2">Date of Birth</h3>
                        <p>October 31, 1985</p>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-bold mb-2">Address</h3>
                        <p>666 Cemetery Lane</p>
                        <p>New York, NY 10001</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <?php
        $booking = new BookingDisplay();
        $user_id = $session->get_user_id();
        $tickets = $booking->getUserTickets($user_id);

        echo '<div class="purple-dark rounded-lg shadow-xl p-6 mt-8">';
        echo '<h2 class="horror-font text-2xl blood-red mb-4">Your Tickets</h2>';

        if (empty($tickets)) {
            echo '<p>No tickets purchased yet. Ready to scream?</p>';
        } else {
            foreach ($tickets as $t) {
                echo "
        <div class='border-b border-gray-700 pb-6 mb-6'>
            <h3 class='horror-font text-xl blood-red'>".htmlspecialchars($t['title'])."</h3>
            <p class='text-gray-400'>".htmlspecialchars($t['hall_name'])." — ".date('F j, Y', strtotime($t['show_date']))." at ".substr($t['show_time'], 0, 5)."</p>
            <p>Seats: ".htmlspecialchars($t['seats'])."</p>
            <p>Total Price: $".number_format($t['total_price'], 2)."</p>
            <div class='flex space-x-3 mt-3'>
            <a class='text-purple-300 hover:text-purple-100 text-sm flex items-center'>
                <i data-feather=\"download\" class='mr-1'></i> Download Ticket
            </a>
        </div>
        </div>";
            }
        }

        echo '</div>';
        ?>

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
    // Check if user is logged in (in a real app, this would be done server-side)
    document.addEventListener('DOMContentLoaded', function() {
        // If not logged in, redirect to login page
        // const isLoggedIn = false; // This would check actual auth status
        // if (!isLoggedIn) {
        //     window.location.href = 'login.php';
        // }
    });
</script>
</body>
</html>
