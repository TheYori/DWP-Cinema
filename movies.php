<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$isLoggedIn = $session->logged_in();

$movieDisplay = new MovieDisplay();
$movies = $movieDisplay->getAllMovies();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Movies</title>
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
        .movie-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .movie-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.5);
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
                <a href="#" class="text-white hover:text-purple-300">Movies</a>
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

<!-- Movie section -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <h1 class="horror-font text-5xl blood-red text-center mb-12">Our Collection of Terror</h1>

        <!-- Movies Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if (empty($movies)): ?>
                <div class="col-span-full text-center text-gray-400">
                    No movies found. Check back soon…
                </div>
            <?php else: ?>
                <?php foreach ($movies as $mv):
                    $poster = $mv['poster'];

                    // If empty → placeholder
                    if (empty($poster)) {
                        $posterPath = "images/movies/placeholder.jpg";
                    }
                    // If it already starts with "images" or "/images" → use as is
                    elseif (str_starts_with($poster, "images/") || str_starts_with($poster, "/images/")) {
                        $posterPath = $poster;
                    }
                    // Otherwise assume it's just a filename
                    else {
                        $posterPath = "images/movies/" . $poster;
                    }


                    $runtime = $mv['movie_length'] ? (int)$mv['movie_length'].' min' : 'TBA';
                    $year = $mv['release_year'] ?: 'TBA';
                    $desc = $mv['movie_desc'] ?: '';
                    ?>
                    <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                        <img src="<?= htmlspecialchars($posterPath) ?>"
                             alt="<?= htmlspecialchars($mv['title']) ?>"
                             class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="horror-font text-2xl blood-red mb-2">
                                <?= htmlspecialchars($mv['title']) ?>
                            </h3>
                            <div class="flex flex-wrap gap-4 text-sm mb-4">
                                <span><i data-feather="clock"></i> <?= htmlspecialchars($runtime) ?></span>
                                <span><i data-feather="calendar"></i> <?= htmlspecialchars($year) ?></span>
                                <?php if (!empty($mv['genre'])): ?>
                                    <span><i data-feather="film"></i> <?= htmlspecialchars($mv['genre']) ?></span>
                                <?php endif; ?>
                            </div>
                            <p class="mb-4 line-clamp-3">
                                <?= htmlspecialchars($desc) ?>
                            </p>
                            <a href="movie.php?id=<?= urlencode($mv['movie_id']) ?>"
                               class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>



        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <nav class="inline-flex rounded-md shadow">
                <a href="#" class="px-3 py-2 purple-dark text-white rounded-l-md">
                    <i data-feather="chevron-left"></i>
                </a>
                <a href="#" class="px-4 py-2 purple-dark text-white border-l border-gray-700">1</a>
                <a href="#" class="px-4 py-2 purple-dark text-white border-l border-gray-700">2</a>
                <a href="#" class="px-4 py-2 purple-dark text-white border-l border-gray-700">3</a>
                <a href="#" class="px-3 py-2 purple-dark text-white border-l border-gray-700 rounded-r-md">
                    <i data-feather="chevron-right"></i>
                </a>
            </nav>
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
