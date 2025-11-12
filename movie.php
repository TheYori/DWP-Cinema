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

<!-- Movie Details -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Movie Poster -->
            <div class="lg:w-1/3">
                <div class="shadow-2xl">
                    <img src="http://static.photos/horror/640x360/4" alt="The Exorcist Poster" class="w-full rounded-lg">
                </div>
            </div>

            <!-- Movie Info -->
            <div class="lg:w-2/3">
                <h1 class="horror-font text-5xl blood-red mb-4">The Exorcist</h1>
                <div class="flex flex-wrap gap-4 mb-6">
                    <span class="flex items-center"><i data-feather="clock" class="mr-2"></i> 122 min</span>
                    <span class="flex items-center"><i data-feather="calendar" class="mr-2"></i> 1973</span>
                    <span class="flex items-center"><i data-feather="star" class="mr-2"></i> 8.1/10</span>
                    <span class="flex items-center"><i data-feather="user" class="mr-2"></i> William Friedkin</span>
                </div>

                <div class="mb-8">
                    <h2 class="horror-font text-2xl blood-red mb-2">Genres</h2>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 moss-green rounded-full text-sm">Horror</span>
                        <span class="px-3 py-1 moss-green rounded-full text-sm">Supernatural</span>
                        <span class="px-3 py-1 moss-green rounded-full text-sm">Psychological</span>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="horror-font text-2xl blood-red mb-2">Synopsis</h2>
                    <p class="mb-4">
                        When a 12-year-old girl is possessed by a mysterious entity, her mother seeks the help of two priests to save her.
                        What follows is a terrifying battle between faith and evil that will test the limits of belief.
                    </p>
                    <p>
                        Based on the novel by William Peter Blatty, The Exorcist shocked audiences worldwide upon its release and remains
                        one of the most influential horror films ever made. Its realistic effects and disturbing content caused fainting
                        spells and heart attacks among original theatergoers.
                    </p>
                </div>
                <!-- "Watch trailer" button -->
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="inline-flex items-center moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                        <i data-feather="play" class="mr-2"></i> Watch Trailer
                    </a>
                </div>
            </div>
        </div>

        <!-- Upcoming Showtimes -->
        <div class="mt-16 purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-8">Upcoming Screamings</h2>

            <div class="space-y-4">
                <!-- Showtime 1 -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center purple-light p-6 rounded-lg">
                    <div>
                        <h3 class="horror-font text-2xl mb-2">Friday, October 13</h3>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <span><i data-feather="tv"></i> Hall A</span>
                            <span><i data-feather="clock"></i> 19:00</span>
                            <span><i data-feather="dollar-sign"></i> $12.50</span>
                        </div>
                    </div>
                    <a href="booking.php?movie=The%20Exorcist&date=Friday,%20October%2013&time=19:00&hall=Hall%20A&price=12.50" class="mt-4 md:mt-0 inline-block moss-green hover:bg-green-900 text-white py-2 px-6 rounded transition duration-300">
                        Book Now
                    </a>
                </div>

                <!-- Showtime 2 -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center purple-light p-6 rounded-lg">
                    <div>
                        <h3 class="horror-font text-2xl mb-2">Saturday, October 14</h3>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <span><i data-feather="tv"></i> Hall B</span>
                            <span><i data-feather="clock"></i> 21:30</span>
                            <span><i data-feather="dollar-sign"></i> $12.50</span>
                        </div>
                    </div>
                    <a href="booking.php?movie=The%20Exorcist&date=Saturday,%20October%2014&time=21:30&hall=Hall%20B&price=12.50" class="mt-4 md:mt-0 inline-block moss-green hover:bg-green-900 text-white py-2 px-6 rounded transition duration-300">
                        Book Now
                    </a>
                </div>

                <!-- Showtime 3 -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center purple-light p-6 rounded-lg">
                    <div>
                        <h3 class="horror-font text-2xl mb-2">Sunday, October 15</h3>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <span><i data-feather="tv"></i> Hall C</span>
                            <span><i data-feather="clock"></i> 22:45</span>
                            <span><i data-feather="dollar-sign"></i> $15.00 (Midnight Special)</span>
                        </div>
                    </div>
                    <a href="booking.php?movie=The%20Exorcist&date=Sunday,%20October%2015&time=22:45&hall=Hall%20C&price=15.00" class="mt-4 md:mt-0 inline-block moss-green hover:bg-green-900 text-white py-2 px-6 rounded transition duration-300">
                        Book Now
                    </a>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="mt-16">
            <h2 class="horror-font text-3xl blood-red mb-8">From the Crypt</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Review 1 -->
                <div class="purple-dark rounded-lg shadow-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-yellow-400"></i>
                        </div>
                        <span class="ml-2 text-sm">by HorrorFan91</span>
                    </div>
                    <p class="italic mb-4">"Still the scariest movie ever made. I saw it when I was 12 and didn't sleep for a week."</p>
                    <p class="text-sm text-gray-400">Posted on October 31, 2022</p>
                </div>

                <!-- Review 2 -->
                <div class="purple-dark rounded-lg shadow-xl p-6">
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-yellow-400 mr-1"></i>
                            <i data-feather="star" class="text-gray-400"></i>
                        </div>
                        <span class="ml-2 text-sm">by ScareMaster</span>
                    </div>
                    <p class="italic mb-4">"The practical effects hold up incredibly well. That head spin still gives me chills."</p>
                    <p class="text-sm text-gray-400">Posted on August 15, 2023</p>
                </div>
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
