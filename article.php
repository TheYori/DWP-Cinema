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
    <title>Midnight Scream Spectacle - News Article</title>
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
<!-- News Article Content -->
<section class="py-0">
    <!-- Horizontal Banner with increased height -->
    <div class="w-full h-[32rem] bg-cover bg-center" style="background-image: url('http://static.photos/horror/1200x630/13')"></div>
    <div class="container mx-auto px-8 lg:px-16 max-w-5xl py-16">
        <h1 class="horror-font text-5xl md:text-6xl blood-red mb-6">All-Night Horror Marathon!</h1>
        <p class="text-gray-400 mb-12 text-xl">October 31, 2023</p>

        <div class="prose max-w-none text-xl leading-relaxed">
            <p>Join us this Halloween for 12 hours of uninterrupted horror classics. From dusk till dawn, we'll be screening rare 35mm prints of forbidden frights.</p>
            <p>This special event will feature the original uncut version of "The Evil Dead" (1981), a never-before-seen extended cut of "Suspiria" (1977), and a surprise midnight screening of a lost horror classic with special guest introduction by horror historian Dr. Mortimer Graves.</p>
            <p>Tickets are $50 and include all-night access to all screenings, complimentary coffee and snacks to keep you awake, a limited edition poster signed by our staff, and a survival kit for making it through the night (earplugs, eye mask, and energy supplements).</p>
            <p>The schedule includes:</p>
            <ul>
                <li>6:00 PM - The Evil Dead (1981) - 35mm</li>
                <li>8:15 PM - Suspiria (Extended Cut) - 35mm</li>
                <li>10:30 PM - Surprise Film - 35mm</li>
                <li>1:00 AM - Night of the Living Dead (1968) - 4K Restoration</li>
            </ul>
            <p>Tickets are limited to 100 attendees. Book now to secure your spot for this terrifying all-night experience.</p>
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
