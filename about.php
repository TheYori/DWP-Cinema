<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$isLoggedIn = $session->logged_in();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - About Us</title>
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
                <a href="#" class="text-white hover:text-purple-300">About Us</a>
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

<!-- Company Information -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="purple-dark rounded-lg shadow-xl p-8 mb-12">
            <h1 class="horror-font text-4xl blood-red mb-6 text-center">Our Dark Origins</h1>
            <div class="mb-8">
                <h2 class="horror-font text-2xl blood-red mb-4">Established in 1983</h2>
                <p class="mb-4">
                    Midnight Scream was founded by horror enthusiasts Vincent Graves and Eliza Moon on Halloween night, 1983.
                    What began as a small arthouse cinema specializing in obscure horror films has grown into the premier destination
                    for classic terror aficionados.
                </p>
                <p>
                    Our founders believed that horror films deserved to be experienced as they were intended - on the big screen,
                    in the dark, surrounded by fellow thrill-seekers. This philosophy continues to guide our operations today.
                </p>
            </div>
            <div class="border-t border-gray-700 pt-6">
                <h2 class="horror-font text-2xl blood-red mb-4">Legal Information</h2>
                <p class="mb-4">
                    Midnight Scream Spectacle is a registered company under Grave Entertainment Holdings LLC.
                    All rights to displayed films remain with their respective copyright holders.
                </p>
                <p>
                    Midnight Scream is licensed under the International Horror Exhibition Association (IHEA) and complies
                    with all local entertainment and safety regulations.
                </p>
            </div>
        </div>

        <!-- Mission Statement -->
        <div class="purple-dark rounded-lg shadow-xl p-8 mb-12">
            <h2 class="horror-font text-4xl blood-red mb-6 text-center">Our Screaming Mission</h2>
            <div class="mb-8">
                <h3 class="horror-font text-2xl blood-red mb-4">Why We Exist</h3>
                <p class="mb-4">
                    We preserve and celebrate the art of horror cinema. In an era of digital streaming, we maintain the
                    tradition of theatrical horror experiences - complete with 35mm film projectors when possible.
                </p>
                <p>
                    Our goal is to keep the history of horror alive while supporting new filmmakers pushing the genre forward.
                </p>
            </div>
            <div class="mb-8">
                <h3 class="horror-font text-2xl blood-red mb-4">Future Plans</h3>
                <p class="mb-4">
                    By 2025, we plan to establish the Midnight Scream Film Foundation to fund restorations of lost horror classics
                    and provide grants to emerging horror filmmakers.
                </p>
                <p>
                    We're also expanding our locations to bring curated horror experiences to more cities while maintaining
                    our underground aesthetic.
                </p>
            </div>
            <div>
                <h3 class="horror-font text-2xl blood-red mb-4">Supporting the Community</h3>
                <p>
                    A portion of all proceeds supports the Nightmare Foundation, helping provide therapy for children suffering
                    from night terrors and funding research into sleep disorders.
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-4xl blood-red mb-6 text-center">Contact the Crypt Keepers</h2>
            <form class="space-y-6">
                <div>
                    <label for="name" class="block mb-2">Your Name</label>
                    <input type="text" id="name" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="email" class="block mb-2">Email Address</label>
                    <input type="email" id="email" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="subject" class="block mb-2">Subject</label>
                    <select id="subject" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>General Inquiry</option>
                        <option>Film Submission</option>
                        <option>Private Event</option>
                        <option>Press</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label for="message" class="block mb-2">Your Message</label>
                    <textarea id="message" rows="5" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <button type="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Send Message <i data-feather="send" class="inline ml-2"></i>
                </button>
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
