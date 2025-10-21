<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the user is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

$movieCRUD = new MovieCRUD();
$message = "";

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($movieCRUD->delete($id)) {
        header("location: movies.php?deleted=1");
        exit;
    } else {
        $message = "<p class='text-red-400 font-semibold mt-4'>Failed to delete the movie.</p>";
    }
}

// Handle the create form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $movie_length = trim($_POST['movie_length']);
    $debut_date = $_POST['debut_date'];
    $rating = $_POST['rating'];
    $director = trim($_POST['director']);
    $genre = $_POST['genre'];
    $movie_desc = $_POST['movie_desc'];
    $poster = $_FILES['poster'];

    if ($movieCRUD->create($title, $movie_length, $debut_date, $rating, $director, $genre, $movie_desc, $poster)) {
        header("location: movies.php?success=1");
        exit;
    } else {
        $message = "<p class='text-red-400 font-semibold mt-4'>Failed to upload the movie. Please check the image and try again.</p>";
    }
}

// This stupid line caused me so much trouble...
//Gives me all the movies to put in a list
$movieList = $movieCRUD->getAllMovies();

// Success messages
if (isset($_GET['success'])) {
    $message = "<p class='text-green-400 font-semibold mt-4'>The movie was added successfully!</p>";
}
if (isset($_GET['deleted'])) {
    $message = "<p class='text-green-400 font-semibold mt-4'>The movie was deleted successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Movies</title>
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
        .horror-font {font-family: 'Creepster', cursive;}
        .purple-dark {background-color: #1a1029;}
        .purple-light {background-color: #2a1a4a;}
        .moss-green {background-color: #1a2910;}
        .blood-red {color: #ff3a3a;}
        .glow {text-shadow: 0 0 5px #9b59b6, 0 0 10px #9b59b6;}
        .admin-nav {background-color: #0a0515;}
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
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Movies Tabs -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex border-b border-gray-700 mb-8">
            <button id="add-movie-tab" class="px-6 py-3 font-medium text-white border-b-2 border-purple-500">
                Add Movie
            </button>
            <button id="view-movies-tab" class="px-6 py-3 font-medium text-gray-400 hover:text-white">
                View/Edit Movies
            </button>
        </div>

        <!-- Add Movie Form -->
        <div id="add-movie-section" class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Add New Movie</h2>
            <?php if (!empty($message)) echo $message; ?>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="poster" class="block mb-2">Poster Image</label>
                    <input type="file" name="poster" id="poster" accept="image/*" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="title" class="block mb-2">Title</label>
                    <input type="text" name="title" id="title" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="movie_desc" class="block mb-2">Description</label>
                    <textarea name="movie_desc" id="movie_desc" rows="4" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="movie_length" class="block mb-2">Length (minutes)</label>
                        <input type="number" name="movie_length" id="movie_length" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="debut_date" class="block mb-2">Debut Date</label>
                        <input type="date" name="debut_date" id="debut_date" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="rating" class="block mb-2">Rating</label>
                        <input type="text" name="rating" id="rating" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="director" class="block mb-2">Director</label>
                        <input type="text" name="director" id="director" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                <div>
                    <label for="genre" class="block mb-2">Genre</label>
                    <input type="text" name="genre" id="genre" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                        Add Movie <i data-feather="plus" class="inline ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- View Movies Table -->
        <div id="view-movies-section" class="purple-dark rounded-lg shadow-xl p-8 hidden">
            <h2 class="horror-font text-3xl blood-red mb-6">Movie Collection</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4">Poster</th>
                        <th class="pb-4">Title</th>
                        <th class="pb-4">Debut Date</th>
                        <th class="pb-4">Director</th>
                        <th class="pb-4 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($movieList)): ?>
                        <?php foreach ($movieList as $movie): ?>
                            <tr class="border-b border-gray-700">
                                <td class="py-4">
                                    <?php if (!empty($movie['poster'])): ?>
                                        <img src="../images/movies/<?php echo htmlspecialchars($movie['poster']); ?>"
                                             alt="Movie Poster"
                                             class="w-16 h-24 object-cover rounded">
                                    <?php else: ?>
                                        <span class="text-gray-400 italic">No Poster</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4"><?php echo htmlspecialchars($movie['title']); ?></td>
                                <td class="py-4"><?php echo !empty($movie['debut_date']) ? htmlspecialchars(date('j M Y', strtotime($movie['debut_date']))) : '-'; ?></td>
                                <td class="py-4"><?php echo htmlspecialchars($movie['director']); ?></td>
                                <td class="py-4 text-center">
                                    <div class="inline-flex items-center space-x-2 justify-center">
                                        <a href="edit-movie.php?id=<?php echo $movie['movie_id']; ?>"
                                           class="inline-block text-yellow-400 hover:text-yellow-300 flex items-center">
                                            <i data-feather="edit" class="mr-1"></i> Edit
                                        </a>
                                        <a href="?delete=<?php echo $movie['movie_id']; ?>"
                                           onclick="return confirm('Are you sure you want to delete this movie?');"
                                           class="inline-block text-red-400 hover:text-red-300 flex items-center">
                                            <i data-feather="trash-2" class="mr-1"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-400 italic">
                                No movies found.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
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

    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addMovieTab = document.getElementById('add-movie-tab');
        const viewMoviesTab = document.getElementById('view-movies-tab');
        const addMovieSection = document.getElementById('add-movie-section');
        const viewMoviesSection = document.getElementById('view-movies-section');

        addMovieTab.addEventListener('click', function() {
            addMovieTab.classList.add('text-white', 'border-purple-500');
            addMovieTab.classList.remove('text-gray-400');
            viewMoviesTab.classList.add('text-gray-400');
            viewMoviesTab.classList.remove('text-white', 'border-purple-500');
            addMovieSection.classList.remove('hidden');
            viewMoviesSection.classList.add('hidden');
        });

        viewMoviesTab.addEventListener('click', function() {
            viewMoviesTab.classList.add('text-white', 'border-purple-500');
            viewMoviesTab.classList.remove('text-gray-400');
            addMovieTab.classList.add('text-gray-400');
            addMovieTab.classList.remove('text-white', 'border-purple-500');
            viewMoviesSection.classList.remove('hidden');
            addMovieSection.classList.add('hidden');
        });
    });
</script>
</body>
</html>