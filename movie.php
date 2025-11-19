<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$isLoggedIn = $session->logged_in();

$movieDisplay = new MovieDisplay();

// 1. Get movie ID from URL
$movieId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($movieId === null || $movieId === false)
{
    die("Invalid movie ID.");
}

// 2. Load the movie
$movie = $movieDisplay->getMovieById($movieId);

if (!$movie)
{
    die("Movie not found.");
}

// 3. Process poster path
$poster = $movie['poster'];

// Block directory traversal
$poster = trim($poster);

if (empty($poster))
{
    $posterPath = "images/movies/placeholder.jpg";

} elseif (filter_var($poster, FILTER_VALIDATE_URL)) {
    // Allow external image URLs
    $posterPath = $poster;

}
else
{
    // Force file to remain inside /images/movies/
    $posterPath = "images/movies/" . basename($poster);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - The Exorcist</title>
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
                <?php if ($isLoggedIn): ?>
                    <a href="profile.php" class="text-white hover:text-purple-300">Profile</a>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-purple-300">Login</a>
                <?php endif; ?>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']) ?> - Midnight Scream</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        body { background-color: #0f0a1a; color: #e0d6eb; font-family: 'EB Garamond', serif; }
        .horror-font { font-family: 'Creepster', cursive; }
    </style>
</head>

<body class="min-h-screen">

<!-- Movie Details -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-5xl">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- Poster -->
            <img src="<?= htmlspecialchars($posterPath) ?>"
                 alt="<?= htmlspecialchars($movie['title']) ?>"
                 class="w-full rounded shadow-lg">

            <!-- Movie Info -->
            <div>
                <h1 class="horror-font text-5xl blood-red mb-6">
                    <?= htmlspecialchars($movie['title']) ?>
                </h1>

                <p class="text-lg mb-4"><?= nl2br(htmlspecialchars($movie['movie_desc'])) ?></p>

                <ul class="space-y-3 text-md">
                    <li><b>Length:</b> <?= htmlspecialchars($movie['movie_length']) ?> min</li>
                    <li><b>Debut Date:</b> <?= htmlspecialchars($movie['debut_date']) ?></li>
                    <li><b>Genre:</b> <?= htmlspecialchars($movie['genre']) ?></li>
                    <li><b>Director:</b> <?= htmlspecialchars($movie['director']) ?></li>
                    <li><b>Rating:</b> <?= htmlspecialchars($movie['rating']) ?>/10</li>
                </ul>

                <a href="movies.php"
                   class="inline-block mt-8 px-6 py-3 bg-purple-700 hover:bg-purple-900 text-white rounded">
                    Back to Movies
                </a>
            </div>

        </div>

    </div>
</section>

<script>feather.replace();</script>
</body>
</html>


<script>
    feather.replace();
    // Get URL parameters and update booking info
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const movie = urlParams.get('movie');
        const date = urlParams.get('date');
        const time = urlParams.get('time');
        const hall = urlParams.get('hall');
        const price = parseFloat(urlParams.get('price'));
        if (movie) {
            document.getElementById('movie-title').textContent = movie;
        }
        if (date && time && hall) {
            const showtimeInfo = document.getElementById('showtime-info');
            if (showtimeInfo) {
                showtimeInfo.innerHTML = `
                        <span class="flex items-center"><i data-feather="calendar" class="mr-2"></i> ${date}</span>
                        <span class="flex items-center"><i data-feather="clock" class="mr-2"></i> ${time}</span>
                        <span class="flex items-center"><i data-feather="tv" class="mr-2"></i> ${hall}</span>
                    `;
                feather.replace();
            }
        }
    });
</script>
</body>
</html>
