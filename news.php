<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$isLoggedIn = $session->logged_in();

$newsDisplay = new NewsDisplay();
$newsList = $newsDisplay->getPublishedNews();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - News</title>
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
                <a href="news.php" class="text-white font-bold">News</a>
                <a href="about.php" class="text-white hover:text-purple-300">About Us</a>
                <?php if ($isLoggedIn): ?>
                    <a href="profile.php" class="text-white hover:text-purple-300">Profile</a>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-purple-300">Login</a>
                <?php endif; ?>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
                <div id="mobile-menu" class="hidden fixed inset-0 purple-dark z-50 pt-20">
                    <div class="flex flex-col items-center space-y-6 text-xl">
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
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- News Section -->
<section class="py-16 purple-light">
    <div class="container mx-auto px-6">
        <h1 class="horror-font text-4xl blood-red text-center mb-12">Grave News</h1>
        <div class="grid md:grid-cols-3 gap-8">
            <?php if (empty($newsList)): ?>

                <div class="col-span-full text-center text-gray-400">
                    No news yet. Stay tuned…
                </div>

            <?php else: ?>
                <?php foreach ($newsList as $news): ?>

                    <?php
                    $img = $news['banner_img'];

                    // Handle image path
                    if (empty($img)) {
                        $imgPath = "images/news/placeholder.jpg";
                    } elseif (preg_match('/^https?:\/\//', $img)) {
                        $imgPath = $img;
                    } else {
                        $imgPath = "images/news/" . ltrim($img, "/");
                    }

                    $dateFormatted = date("F j, Y", strtotime($news['release_date']));
                    ?>

                    <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                        <img src="<?= htmlspecialchars($imgPath) ?>"
                             alt="<?= htmlspecialchars($news['title']) ?>"
                             class="w-full h-48 object-cover">

                        <div class="p-6">
                            <h3 class="horror-font text-2xl blood-red mb-3">
                                <?= htmlspecialchars($news['title']) ?>
                            </h3>

                            <p class="text-sm text-gray-400 mb-2">
                                <?= $dateFormatted ?>
                            </p>

                            <p class="mb-4 line-clamp-3">
                                <?= htmlspecialchars(substr($news['content'], 0, 150)) ?>...
                            </p>

                            <a href="article.php?id=<?= urlencode($news['news_id']) ?>"
                               class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                                Read More <i data-feather="arrow-right" class="inline"></i>
                            </a>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
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
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
    // Close menu when clicking outside
    document.addEventListener('click', (event) => {
        if (!mobileMenu.contains(event.target) && event.target !== mobileMenuButton) {
            mobileMenu.classList.add('hidden');
        }
    });
</script>
</body>
</html>
