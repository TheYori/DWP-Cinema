<?php
spl_autoload_register(function ($class) {
    include "../classes/" . $class . ".php";
});

//check of the admin is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

// Get all bookings using the new class
$bookingsDisplay = new BookingCRUD();
$bookings = $bookingsDisplay->getAllBookings();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Bookings</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Creepster&family=EB+Garamond:wght@400;700&display=swap');
        body {
            font-family: 'EB Garamond', serif;
            background-color: #0f0a1a;
            color: #e0d6eb;
        }
        .horror-font { font-family: 'Creepster', cursive; }
        .purple-dark { background-color: #1a1029; }
        .blood-red { color: #ff3a3a; }
        .admin-nav { background-color: #0a0515; }
    </style>
</head>
<body class="min-h-screen">

<!-- Navigation -->
<nav class="purple-dark sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-6 py-3 flex items-center justify-between">
        <a href="../index.php" class="horror-font text-3xl blood-red glow">Midnight Scream</a>
        <h1>Welcome, <strong><?php echo $_SESSION['username']; ?></strong></h1>
        <div class="flex space-x-4">
            <a href="../index.php" class="hidden md:block text-white hover:text-purple-300">Back to Main Site</a>
            <a href="login.php?logout=1" class="bg-green-900 hover:bg-green-800 text-white font-bold py-2 px-4 rounded transition">
                Log Out <i data-feather="log-out" class="inline ml-1"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Admin Navigation -->
<div class="admin-nav py-4 shadow-inner">
    <div class="container mx-auto px-6 flex flex-wrap gap-4">
        <a href="company.php" class="text-white hover:text-purple-300">Company Info</a>
        <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
        <a href="showtimes.php" class="text-white hover:text-purple-300">Showtimes</a>
        <a href="news.php" class="text-white hover:text-purple-300">News</a>
        <a href="bookings.php" class="text-purple-300 font-bold">Bookings</a>
        <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
    </div>
</div>

<!-- Bookings Table -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-7xl">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">All Bookings</h2>

            <?php if (empty($bookings)): ?>
                <p>No bookings found.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                        <tr class="border-b border-gray-700 text-purple-300">
                            <th class="pb-3">User</th>
                            <th class="pb-3">Movie</th>
                            <th class="pb-3">Hall</th>
                            <th class="pb-3">Show Date</th>
                            <th class="pb-3">Show Time</th>
                            <th class="pb-3">Seats</th>
                            <th class="pb-3">Booked On</th>
                            <th class="pb-3">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($bookings as $b): ?>
                            <tr class="border-b border-gray-800 hover:bg-purple-900/30">
                                <td class="py-3"><?php echo htmlspecialchars($b['first_name'].' '.$b['last_name']); ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($b['movie_title']); ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($b['hall_name']); ?></td>
                                <td class="py-3"><?php echo date('M j, Y', strtotime($b['show_date'])); ?></td>
                                <td class="py-3"><?php echo substr($b['show_time'], 0, 5); ?></td>
                                <td class="py-3"><?php echo htmlspecialchars($b['seats']); ?></td>
                                <td class="py-3"><?php echo date('M j, Y H:i', strtotime($b['ticket_date'].' '.$b['ticket_time'])); ?></td>
                                <td class="py-3">
                                    <a href="../invoice.php?ticket_id=<?= $b['ticket_id']; ?>" target="_blank"
                                       class="text-purple-300 hover:text-purple-100 text-sm flex items-center">
                                        <i data-feather='download' class='mr-1'></i> Invoice
                                    </a>
                                </td>

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
