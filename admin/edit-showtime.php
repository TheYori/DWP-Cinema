<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the admin is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

$showtimeCRUD = new ShowtimeCRUD();

// Get the ID from the URL
$showtimeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($showtimeId === false || $showtimeId === null) {
    header("Location: showtimes.php");
    exit;
}

$id = $showtimeId;

// Get showtime info
$showtime = $showtimeCRUD->getShowtimeById($id);
if (!$showtime) {
    $_SESSION['message'] = "<p class='text-red-400 font-semibold'>Showtime not found.</p>";
    header("Location: showtimes.php");
    exit;
}

// Get dropdown data
$movies = $showtimeCRUD->getAllMovies();
$halls = $showtimeCRUD->getAllHalls();

// Submission Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = $_POST['movie_id'];
    $hall_id = $_POST['hall_id'];
    $show_date = $_POST['show_date'];
    $show_time = $_POST['show_time'];

    if ($showtimeCRUD->update($id, $show_date, $show_time, $hall_id, $movie_id)) {
        $_SESSION['message'] = "<p class='text-green-400 font-semibold'>Showtime updated successfully!</p>";
    } else {
        $_SESSION['message'] = "<p class='text-red-400 font-semibold'>Error updating showtime.</p>";
    }

    header("Location: showtimes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Edit Showtime</title>
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
            <a href="seats.php" class="text-white hover:text-purple-300">Booked Seats</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>

    <!-- Edit Showtime Form -->
    <section class="py-16">
        <div class="container mx-auto px-6 max-w-4xl">
            <div class="purple-dark rounded-lg shadow-xl p-8">
                <h2 class="horror-font text-3xl blood-red mb-6">Edit Showtime</h2>
                <form method="post" class="space-y-6">
                    <div>
                        <label for="edit-showtime-movie" class="block mb-2">Movie Title</label>
                        <select name="movie_id" id="edit-showtime-movie" required
                                class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <?php foreach ($movies as $movie): ?>
                                <option value="<?php echo $movie['movie_id']; ?>"
                                        <?php echo ($movie['movie_id'] == $showtime['movie_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($movie['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="edit-showtime-date" class="block mb-2">Date</label>
                        <input type="date" name="show_date" id="edit-showtime-date"
                               value="<?php echo htmlspecialchars($showtime['show_date']); ?>"
                               required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label for="edit-showtime-time" class="block mb-2">Time</label>
                        <input type="time" name="show_time" id="edit-showtime-time"
                               value="<?php echo substr($showtime['show_time'], 0, 5); ?>"
                               required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label for="edit-showtime-hall" class="block mb-2">Hall</label>
                        <select name="hall_id" id="edit-showtime-hall" required
                                class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <?php foreach ($halls as $hall): ?>
                                <option value="<?php echo $hall['hall_id']; ?>"
                                        <?php echo ($hall['hall_id'] == $showtime['hall_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($hall['hall_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="showtimes.php" class="inline-block purple-light hover:bg-purple-800 text-white font-bold py-3 px-6 rounded transition duration-300">
                            Cancel
                        </a>
                        <button type="submit" class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                            Save Changes <i data-feather="save" class="inline ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        feather.replace();

        // Get ID from URL and load corresponding data (in a real app)
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const showtimeId = urlParams.get('id');

            // In a real app, we would fetch the data for this ID from the server
            if (showtimeId) {
                console.log(`Loading data for showtime ID: ${showtimeId}`);
                // Example: fetch(`/api/showtimes/${showtimeId}`).then(...)
            }
        });
    </script>
</body>
</html>