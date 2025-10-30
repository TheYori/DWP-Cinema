<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
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
                <a href="login.php" class="text-white hover:text-purple-300">Login</a>
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
                        <a href="login.php" class="text-white hover:text-purple-300">Login</a>
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
            <!-- News 1 -->
            <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                <img src="http://static.photos/horror/640x360/13" alt="News Image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-3">All-Night Horror Marathon!</h3>
                    <p class="mb-4">
                        Join us this Halloween for 12 hours of uninterrupted horror classics. From dusk till dawn, we'll be screening rare 35mm prints of forbidden frights.
                    </p>
                    <a href="article.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                        Read More <i data-feather="arrow-right" class="inline"></i>
                    </a>
                </div>
            </div>

            <!-- News 2 -->
            <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                <img src="http://static.photos/horror/640x360/666" alt="News Image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-3">Newly Restored: The Beyond</h3>
                    <p class="mb-4">
                        Lucio Fulci's gory masterpiece returns in a stunning 4K restoration for one week only. Experience the gates of hell like never before.
                    </p>
                    <a href="article.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                        Read More <i data-feather="arrow-right" class="inline"></i>
                    </a>
                </div>
            </div>

            <!-- News 3 -->
            <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                <img src="http://static.photos/horror/640x360/99" alt="News Image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-3">Midnight Madness Sale</h3>
                    <p class="mb-4">
                        For one night only, all vintage horror posters and memorabilia will be 50% off from midnight to 3am. Come if you dare...
                    </p>
                    <a href="article.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                        Read More <i data-feather="arrow-right" class="inline"></i>
                    </a>
                </div>
            </div>

            <!-- News 4 -->
            <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                <img src="http://static.photos/horror/640x360/42" alt="News Image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-3">Classic Horror Festival</h3>
                    <p class="mb-4">
                        Our annual Classic Horror Festival returns with screenings of rare 35mm prints from the golden age of horror cinema.
                    </p>
                    <a href="article.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                        Read More <i data-feather="arrow-right" class="inline"></i>
                    </a>
                </div>
            </div>

            <!-- News 5 -->
            <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                <img src="http://static.photos/horror/640x360/7" alt="News Image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-3">New Membership Program</h3>
                    <p class="mb-4">
                        Join our new Scream Club membership program for exclusive benefits, early access to tickets, and special members-only events.
                    </p>
                    <a href="article.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                        Read More <i data-feather="arrow-right" class="inline"></i>
                    </a>
                </div>
            </div>

            <!-- News 6 -->
            <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                <img src="http://static.photos/horror/640x360/24" alt="News Image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-3">Behind the Screams Tour</h3>
                    <p class="mb-4">
                        Take a guided tour of our historic theater and learn about its haunted history and the legends that surround it.
                    </p>
                    <a href="article.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                        Read More <i data-feather="arrow-right" class="inline"></i>
                    </a>
                </div>
            </div>
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
