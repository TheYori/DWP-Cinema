<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$isLoggedIn = $session->logged_in();

// Load selected article
$newsDisplay = new NewsDisplay();

if (!isset($_GET['id'])) {
    die("No article selected.");
}

$article = $newsDisplay->getNewsById($_GET['id']);

if (!$article) {
    die("Article not found.");
}

// Resolve banner image
$img = $article['banner_img'];

if (empty($img)) {
    $bannerPath = "images/news/placeholder.jpg";
} elseif (preg_match('/^https?:\/\//', $img)) {
    $bannerPath = $img;
} else {
    $bannerPath = "images/news/" . ltrim($img, "/");
}

$formattedDate = date("F j, Y", strtotime($article['release_date']));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']) ?> - Midnight Scream Spectacle</title>
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
            <a href="index.php" class="horror-font text-3xl blood-red glow">Midnight Scream</a>

            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-white hover:text-purple-300">Home</a>
                <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
                <a href="news.php" class="text-white font-bold">News</a>
                <a href="about.php" class="text-white hover:text-purple-300">About Us</a>

                <?php if ($isLoggedIn): ?>
                    <a href="profile.php" class="text-white hover:text-purple-300">Profile</a>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-purple-300">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- News Article Content -->
<section class="py-0">

    <!-- Dynamic Banner -->
    <div class="w-full h-[32rem] bg-cover bg-center"
         style="background-image: url('<?= htmlspecialchars($bannerPath) ?>');">
    </div>

    <div class="container mx-auto px-8 lg:px-16 max-w-5xl py-16">
        <h1 class="horror-font text-5xl md:text-6xl blood-red mb-6">
            <?= htmlspecialchars($article['title']) ?>
        </h1>

        <p class="text-gray-400 mb-12 text-xl">
            <?= $formattedDate ?>
        </p>

        <div class="prose max-w-none text-xl leading-relaxed">
            <?= nl2br(htmlspecialchars($article['content'])) ?>
        </div>

        <div class="mt-12">
            <a href="news.php"
               class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                ← Back to News
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="purple-dark py-8">
    <div class="container mx-auto px-6 text-center md:text-left">
        <span class="horror-font text-2xl blood-red">Midnight Scream</span>
        <p class="text-sm mt-1">© 2023 All Rights Reserved</p>
    </div>
</footer>

<script>
    feather.replace();
</script>

</body>
</html>
