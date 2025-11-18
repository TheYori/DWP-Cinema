<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the admin is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

$showtimeCRUD = new ShowtimeCRUD();
$movies = $showtimeCRUD->getAllMovies();
$halls = $showtimeCRUD->getAllHalls();

$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // clear it so it shows only once
}

// Handle form submission
$showtimeCRUD = new ShowtimeCRUD();
$movies = $showtimeCRUD->getAllMovies();
$halls = $showtimeCRUD->getAllHalls();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = $_POST['movie_id'];
    $hall_id = $_POST['hall_id'];
    $show_date = $_POST['show_date'];
    $show_time = $_POST['show_time'];

    if ($showtimeCRUD->create($show_date, $show_time, $hall_id, $movie_id)) {
        $_SESSION['message'] = "<p class='text-green-400 font-semibold'>Showtime added successfully!</p>";
    } else {
        $_SESSION['message'] = "<p class='text-red-400 font-semibold'>Error adding showtime. Please check inputs.</p>";
    }

    // Redirect to avoid form resubmission
    header("Location: showtimes.php");
    exit;
}

// Handle delete request
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    if ($showtimeCRUD->delete($id)) {
        $_SESSION['message'] = "<p class='text-green-400 font-semibold'>Showtime deleted successfully!</p>";
    } else {
        $_SESSION['message'] = "<p class='text-red-400 font-semibold'>Error deleting showtime. Please try again.</p>";
    }

    // Redirect to prevent repeated deletions on refresh
    header("Location: showtimes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Showtimes</title>
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
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
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
            <a href="showtimes.php" class="text-purple-300 font-bold">Showtimes</a>
            <a href="news.php" class="text-white hover:text-purple-300">News</a>
            <a href="bookings.php" class="text-white hover:text-purple-300">Bookings</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Showtimes Tabs -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex border-b border-gray-700 mb-8">
            <button id="add-showtime-tab" class="px-6 py-3 font-medium text-white border-b-2 border-purple-500">
                Add Showtime
            </button>
            <button id="view-showtimes-tab" class="px-6 py-3 font-medium text-gray-400 hover:text-white">
                View/Edit Showtimes
            </button>
        </div>

        <!-- Add Showtime Form -->
        <div id="add-showtime-section" class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Add New Showtime</h2>
            <?php if (!empty($message)): ?>
                <div class="mb-6 px-6 py-3 rounded text-center border w-full mx-auto
                    <?php echo (strpos($message, 'successfully') !== false)
                            ? 'bg-green-900 text-green-200 border-green-700'
                            : 'bg-red-900 text-red-200 border-red-700'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form method="post" class="space-y-6">
                <div>
                    <label for="showtime-movie" class="block mb-2">Movie Title</label>
                    <select name="movie_id" id="showtime-movie" required
                            class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select a movie</option>
                        <?php foreach ($movies as $movie): ?>
                            <option value="<?php echo $movie['movie_id']; ?>">
                                <?php echo htmlspecialchars($movie['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="showtime-date" class="block mb-2">Date</label>
                    <input type="date" name="show_date" id="showtime-date" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="showtime-time" class="block mb-2">Time</label>
                    <input type="time" name="show_time" id="showtime-time" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="showtime-hall" class="block mb-2">Hall</label>
                    <select name="hall_id" id="showtime-hall" required
                            class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select a hall</option>
                        <?php foreach ($halls as $hall): ?>
                            <option value="<?php echo $hall['hall_id']; ?>">
                                <?php echo htmlspecialchars($hall['hall_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Add Showtime <i data-feather="plus" class="inline ml-2"></i>
                </button>
            </form>
        </div>

        <!-- View Showtimes Table -->
        <div id="view-showtimes-section" class="purple-dark rounded-lg shadow-xl p-8 hidden">
            <h2 class="horror-font text-3xl blood-red mb-6">Current Showtimes</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4">Movie</th>
                        <th class="pb-4">Date</th>
                        <th class="pb-4">Time</th>
                        <th class="pb-4">Hall</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $showtimes = $showtimeCRUD->getAllShowtimes();
                    if ($showtimes):
                        foreach ($showtimes as $showtime):
                            ?>
                            <tr class="border-b border-gray-700">
                                <td class="py-4"><?php echo htmlspecialchars($showtime['movie_title']); ?></td>
                                <td class="py-4"><?php echo date('j M Y', strtotime($showtime['show_date'])); ?></td>
                                <td class="py-4"><?php echo substr($showtime['show_time'], 0, 5); ?></td>
                                <td class="py-4"><?php echo htmlspecialchars($showtime['hall_name']); ?></td>
                                <td class="py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="edit-showtime.php?id=<?php echo $showtime['showtime_id']; ?>"
                                           class="flex items-center text-yellow-400 hover:text-yellow-300">
                                            <i data-feather="edit" class="mr-1"></i> Edit
                                        </a>
                                        <a href="showtimes.php?delete=<?php echo $showtime['showtime_id']; ?>"
                                           onclick="return confirm('Are you sure you want to delete this showtime?');"
                                           class="flex items-center text-red-400 hover:text-red-300">
                                            <i data-feather="trash-2" class="mr-1"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <tr><td colspan="5" class="py-4 text-center text-gray-400">No showtimes found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    feather.replace();

    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addShowtimeTab = document.getElementById('add-showtime-tab');
        const viewShowtimesTab = document.getElementById('view-showtimes-tab');
        const addShowtimeSection = document.getElementById('add-showtime-section');
        const viewShowtimesSection = document.getElementById('view-showtimes-section');

        addShowtimeTab.addEventListener('click', function() {
            addShowtimeTab.classList.add('text-white', 'border-purple-500');
            addShowtimeTab.classList.remove('text-gray-400');
            viewShowtimesTab.classList.add('text-gray-400');
            viewShowtimesTab.classList.remove('text-white', 'border-purple-500');
            addShowtimeSection.classList.remove('hidden');
            viewShowtimesSection.classList.add('hidden');
        });

        viewShowtimesTab.addEventListener('click', function() {
            viewShowtimesTab.classList.add('text-white', 'border-purple-500');
            viewShowtimesTab.classList.remove('text-gray-400');
            addShowtimeTab.classList.add('text-gray-400');
            addShowtimeTab.classList.remove('text-white', 'border-purple-500');
            viewShowtimesSection.classList.remove('hidden');
            addShowtimeSection.classList.add('hidden');
        });
    });
</script>
</body>
</html>