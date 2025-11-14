<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$isLoggedIn = $session->logged_in();

$company = new CompanyDisplay();
$showtimeDisplay = new ShowtimeDisplay();
$newsDisplay = new NewsDisplay();

$datakey = "Welcome to Midnight Scream";
$welcomeText = $company->getCompanyInfo($datakey);
$moviesToShow = $showtimeDisplay->getShowings();
$recentNews = $newsDisplay->getRecentNews();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Home</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.fog.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Creepster&family=EB+Garamond:wght@400;700&display=swap');
        @keyframes flicker {
            0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% {
                opacity: 1;
            }
            20%, 22%, 24%, 55% {
                opacity: 0.5;
            }
        }
        body {
            font-family: 'EB Garamond', serif;
            background-color: #0f0a1a;
            color: #e0d6eb;
        }
        .animate-flicker {
            animation: flicker 3s infinite;
        }
        .animate-fadeIn {
            animation: fadeIn 1.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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
        @keyframes lightning {
            0%, 80%, 85%, 90%, 95%, 100% {
                opacity: 0;
            }
            82%, 88%, 93% {
                opacity: 1;
                box-shadow: 0 0 300px 150px rgba(255, 255, 255, 1);
            }
            84%, 87%, 91% {
                opacity: 0.9;
                box-shadow: 0 0 200px 100px rgba(255, 255, 255, 0.9);
            }
        }
    </style>
</head>
<body class="min-h-screen">
<!-- Navigation -->
<nav class="purple-dark sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <a href="#" class="flex items-center">
                    <span class="horror-font text-3xl blood-red glow">Midnight Scream</span>
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-white hover:text-purple-300">Home</a>
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
<!-- Enhanced Scary Banner -->
<div id="banner" class="relative overflow-hidden h-[600px]">
    <div class="absolute inset-0 bg-[url('http://static.photos/horror/1200x630/666')] bg-cover bg-center opacity-20"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/50 to-black/40 z-10"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center z-20 px-4">
        <div class="text-center">
            <h1 class="horror-font text-6xl md:text-8xl blood-red glow mb-6">
                <span class="inline-block animate-[pulse_2s_infinite] hover:animate-none hover:scale-110 transition duration-300">M</span>
                <span class="inline-block animate-[pulse_2.2s_infinite] hover:animate-none hover:scale-110 transition duration-300">I</span>
                <span class="inline-block animate-[pulse_2.4s_infinite] hover:animate-none hover:scale-110 transition duration-300">D</span>
                <span class="inline-block animate-[pulse_2.6s_infinite] hover:animate-none hover:scale-110 transition duration-300">N</span>
                <span class="inline-block animate-[pulse_2.8s_infinite] hover:animate-none hover:scale-110 transition duration-300">I</span>
                <span class="inline-block animate-[pulse_3s_infinite] hover:animate-none hover:scale-110 transition duration-300">G</span>
                <span class="inline-block animate-[pulse_3.2s_infinite] hover:animate-none hover:scale-110 transition duration-300">H</span>
                <span class="inline-block animate-[pulse_3.4s_infinite] hover:animate-none hover:scale-110 transition duration-300">T</span>
                <span class="inline-block mx-4"> </span>
                <span class="inline-block animate-[pulse_3.6s_infinite] hover:animate-none hover:scale-110 transition duration-300">S</span>
                <span class="inline-block animate-[pulse_3.8s_infinite] hover:animate-none hover:scale-110 transition duration-300">C</span>
                <span class="inline-block animate-[pulse_4s_infinite] hover:animate-none hover:scale-110 transition duration-300">R</span>
                <span class="inline-block animate-[pulse_4.2s_infinite] hover:animate-none hover:scale-110 transition duration-300">E</span>
                <span class="inline-block animate-[pulse_4.4s_infinite] hover:animate-none hover:scale-110 transition duration-300">A</span>
                <span class="inline-block animate-[pulse_4.6s_infinite] hover:animate-none hover:scale-110 transition duration-300">M</span>
            </h1>
            <p class="text-xl md:text-3xl italic mb-8 animate-[flicker_5s_infinite] font-bold">ENTER IF YOU DARE...</p>
            <div class="flex justify-center gap-4">
                <a href="about.php" class="bg-transparent border-2 border-red-600 hover:bg-black text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 animate-[pulse_5s_infinite]">
                    <i data-feather="skull" class="mr-2"></i> Our History
                </a>
            </div>
        </div>
    </div>
    <div class="absolute bottom-0 w-full h-32 bg-gradient-to-t from-black via-black/50 to-transparent z-30"></div>
    <div class="absolute top-0 left-0 w-full h-8 bg-gradient-to-b from-black/90 to-transparent z-30"></div>
</div>

<!-- Enhanced Presentation Section -->
<section class="py-20 relative overflow-hidden purple-light">
    <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('http://static.photos/horror/1200x630/13')] bg-cover bg-center"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2">
                    <img src="http://static.photos/horror/800x450/666" alt="Theater" class="rounded-lg shadow-2xl w-full">
                </div>
                <div class="lg:w-1/2">
                    <h2 class="horror-font text-5xl blood-red mb-6">
                        <?php echo nl2br(htmlspecialchars($datakey)); ?>
                    </h2>
                    <div class="space-y-4 text-lg">
                        <p class="text-xl">
                            <?php echo nl2br(htmlspecialchars($welcomeText)); ?>
                        </p>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="about.php" class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 flex items-center gap-2">
                            <i data-feather="book-open"></i> Our Dark Origins
                        </a>
                        <a href="movies.php" class="bg-transparent border-2 border-red-600 hover:bg-red-900 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105 flex items-center gap-2">
                            <i data-feather="film"></i> Browse Films
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Daily Showings (added id for anchor link) -->
<section class="py-16 purple-light">
    <div class="container mx-auto px-6">
        <h2 class="horror-font text-6xl blood-red text-center mb-12">Today's Terrifying Features</h2>

        <?php if (!empty($moviesToShow)): ?>
            <?php foreach ($moviesToShow as $entry):
                $movie = $entry['movie'];
                $showtimes = $entry['showtimes'];
                ?>
                <div class="purple-dark rounded-lg shadow-xl overflow-hidden mb-10">
                    <div class="md:flex">
                        <div class="md:w-1/4">
                            <img src="images/movies/<?php echo htmlspecialchars($movie['poster']); ?>" alt="Movie Poster" class="w-full h-full object-cover">
                        </div>
                        <div class="md:w-2/3 p-6">
                            <h3 class="horror-font text-4xl blood-red mb-2"><?php echo htmlspecialchars($movie['title']); ?></h3>
                            <div class="flex flex-wrap gap-4 mb-4">
                                <span class="text-sm text-lg"><i data-feather="clock"></i> <?php echo $movie['movie_length']; ?> min</span>
                                <span class="text-sm text-lg"><i data-feather="film"></i> <?php echo htmlspecialchars($movie['genre']); ?></span>
                                <span class="text-sm text-lg"><i data-feather="calendar"></i> <?php echo date('Y', strtotime($movie['debut_date'])); ?></span>
                                <span class="text-sm text-lg"><i data-feather="user"></i> <?php echo htmlspecialchars($movie['director']); ?></span>
                            </div>
                            <p class="mb-6 text-xl"><?php echo htmlspecialchars($movie['movie_desc']); ?></p>
                            <div class="flex flex-wrap gap-3">
                                <?php foreach ($showtimes as $show): ?>
                                    <a href="booking.php?showtime_id=<?php echo urlencode($show['Showtime_id']); ?>"
                                       class="moss-green hover:bg-green-900 text-white py-4 px-6 rounded transition duration-300">
                                        <i data-feather="tv"></i> <?php echo htmlspecialchars($show['hall']); ?><br>
                                        <?php echo htmlspecialchars($show['time']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-400 text-lg">No upcoming showings available.</p>
        <?php endif; ?>
    </div>
</section>

<!-- News Section -->
<section class="py-16 purple-light">
    <div class="container mx-auto px-6">
        <h2 class="horror-font text-6xl blood-red text-center mb-12">Grave News</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($recentNews as $news): ?>
                <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                    <img src="images/news/<?php echo htmlspecialchars($news['banner_img']); ?>" alt="News Image" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="horror-font text-2xl blood-red mb-3"><?php echo htmlspecialchars($news['title']); ?></h3>
                        <p class="mb-4"><?php echo nl2br(htmlspecialchars(substr($news['content'], 0, 100))); ?>...</p>
                        <a href="article.php?id=<?php echo $news['news_id']; ?>" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                            Read More <i data-feather="arrow-right" class="inline"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="py-16 bg-black">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="purple-dark rounded-lg shadow-xl p-8 text-center">
            <h2 class="horror-font text-3xl blood-red mb-4">Subscribe to Our Crypt</h2>
            <p class="mb-6">Get weekly updates on upcoming fright flicks, special events, and exclusive offers delivered straight to your coffin.</p>
            <form class="flex flex-col md:flex-row gap-4">
                <input type="email" placeholder="Your email address" class="flex-grow px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Subscribe <i data-feather="send" class="inline"></i>
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
    // Initialize Vanta.js effect
    VANTA.FOG({
        el: "#banner",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        highlightColor: 0xff0000,
        midtoneColor: 0x8b0000,
        lowlightColor: 0x000000,
        baseColor: 0x000000,
        blurFactor: 0.70,
        speed: 2.50,
        zoom: 0.80
    });
    // Initialize feather icons
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