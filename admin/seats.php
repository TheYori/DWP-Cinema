<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the admin is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

// Use SQL VIEW
$bookedSeatsView = new BookedSeatsView();
$bookedSeats = $bookedSeatsView->getAllBookedSeats();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Management</title>
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
        .admin-nav {
            background-color: #0a0515;
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
            <h1>Welcome to the backend: <strong><?php echo $_SESSION['username']; ?></strong></h1>
            <div class="flex items-center space-x-4">
                <a href="../index.php" class="hidden md:block text-white hover:text-purple-300">Back to Main Site</a>
                <a href="login.php?logout=1" class="moss-green hover:bg-green-900 text-white font-bold py-2 px-4 rounded transition duration-300" >
                    Log Out <i data-feather="log-out" class="inline ml-1"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Admin Navigation -->
<div class="admin-nav py-4 shadow-inner">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap gap-4">
            <a href="company.php" class="text-white hover:text-purple-300">Company Info</a>
            <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
            <a href="showtimes.php" class="text-white hover:text-purple-300">Showtimes</a>
            <a href="news.php" class="text-white hover:text-purple-300">News</a>
            <a href="bookings.php" class="text-white hover:text-purple-300">Bookings</a>
            <a href="admin.php" class="text-purple-300 font-bold">Admins</a>
        </div>
    </div>
</div>

<!-- Booked Seats Table -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">All Booked Seats</h2>

            <?php if (empty($bookedSeats)): ?>
                <p>No seats booked yet.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="border-b border-gray-700 text-purple-300">
                            <th class="pb-3">Movie</th>
                            <th class="pb-3">Hall</th>
                            <th class="pb-3">Show Date</th>
                            <th class="pb-3">Show Time</th>
                            <th class="pb-3">Seat</th>
                            <th class="pb-3">Ticket ID</th>
                            <th class="pb-3">User ID</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($bookedSeats as $b): ?>
                            <tr class="border-b border-gray-800 hover:bg-purple-900/30">
                                <td class="py-3"><?= htmlspecialchars($b['movie_title']); ?></td>
                                <td class="py-3"><?= htmlspecialchars($b['hall_name']); ?></td>
                                <td class="py-3"><?= date('M j, Y', strtotime($b['show_date'])); ?></td>
                                <td class="py-3"><?= substr($b['show_time'], 0, 5); ?></td>
                                <td class="py-3"><?= htmlspecialchars($b['seat_name']); ?></td>
                                <td class="py-3"><?= $b['ticket_id']; ?></td>
                                <td class="py-3"><?= $b['user_id']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<script>feather.replace();</script>
</body>
</html>

