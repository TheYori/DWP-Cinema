<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the user is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

$movieCRUD = new MovieCRUD();
$message = "";

// Check for movie ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: movies.php");
    exit;
}

$id = (int)$_GET['id'];
$movie = $movieCRUD->getMovieById($id);

if (!$movie) {
    $message = "<p class='text-red-400 font-semibold mt-4'>Movie not found.</p>";
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $movie_length = trim($_POST['movie_length']);
    $debut_date = $_POST['debut_date'];
    $rating = $_POST['rating'];
    $director = trim($_POST['director']);
    $genre = $_POST['genre'];
    $movie_desc = $_POST['movie_desc'];
    $poster = $_FILES['poster'];

    if ($movieCRUD->update($id, $title, $movie_length, $debut_date, $rating, $director, $genre, $movie_desc, $poster)) {
        header("Location: movies.php?updated=1");
        exit;
    } else {
        $message = "<p class='text-red-400 font-semibold mt-4'>Failed to update movie. Please check inputs or image.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Edit Movie</title>
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
            <a href="movies.php" class="text-purple-300 font-bold">Movies</a>
            <a href="showtimes.php" class="text-white hover:text-purple-300">Showtimes</a>
            <a href="news.php" class="text-white hover:text-purple-300">News</a>
            <a href="bookings.php" class="text-white hover:text-purple-300">Bookings</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Edit Movie Form -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Edit Movie Information</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <?php if (!empty($message)) echo $message; ?>

                <div>
                    <label for="poster" class="block mb-2">Poster Image</label>
                    <?php if (!empty($movie['poster'])): ?>
                        <img src="../images/movies/<?php echo htmlspecialchars($movie['poster']); ?>"
                             alt="Movie Poster" class="w-32 h-48 object-cover rounded mb-2">
                    <?php else: ?>
                        <p class="text-gray-400 italic mb-2">No poster uploaded</p>
                    <?php endif; ?>
                    <input type="file" name="poster" id="poster" accept="image/*"
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="title" class="block mb-2">Title</label>
                    <input type="text" name="title" id="title"
                           value="<?php echo htmlspecialchars($movie['title']); ?>" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label for="movie_desc" class="block mb-2">Description</label>
                    <textarea name="movie_desc" id="movie_desc" rows="4" required
                              class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo htmlspecialchars($movie['movie_desc']); ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="movie_length" class="block mb-2">Length (minutes)</label>
                        <input type="number" name="movie_length" id="movie_length"
                               value="<?php echo htmlspecialchars($movie['movie_length']); ?>" required
                               class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="debut_date" class="block mb-2">Debut Date</label>
                        <input type="date" name="debut_date" id="debut_date"
                               value="<?php echo htmlspecialchars($movie['debut_date']); ?>" required
                               class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="rating" class="block mb-2">Rating</label>
                        <input type="text" name="rating" id="rating"
                               value="<?php echo htmlspecialchars($movie['rating']); ?>" required
                               class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="director" class="block mb-2">Director</label>
                        <input type="text" name="director" id="director"
                               value="<?php echo htmlspecialchars($movie['director']); ?>" required
                               class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <label for="genre" class="block mb-2">Genre</label>
                    <input type="text" name="genre" id="genre"
                           value="<?php echo htmlspecialchars($movie['genre']); ?>" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="movies.php" class="inline-block purple-light hover:bg-purple-800 text-white font-bold py-3 px-6 rounded transition duration-300">
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

    // Get ID from URL and load corresponding data (in a real app)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const movieId = urlParams.get('id');

        // In a real app, we would fetch the data for this ID from the server
        if (movieId) {
            console.log(`Loading data for movie ID: ${movieId}`);
            // Example: fetch(`/api/movies/${movieId}`).then(...)
        }
    });
</script>
</body>
</html>
