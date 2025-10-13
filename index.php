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
                <a href="#" class="flex items-center">
                    <span class="horror-font text-3xl blood-red glow">Midnight Scream</span>
                </a>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-white hover:text-purple-300">Home</a>
                <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
                <a href="#" class="text-white font-bold">About Us</a>
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
                        <a href="about.php" class="text-white hover:text-purple-300">About Us</a>
                        <a href="login.php" class="text-white hover:text-purple-300">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Banner -->
<div id="banner" class="relative overflow-hidden h-96">
    <div class="absolute inset-0 bg-black opacity-60 z-10"></div>
    <div class="absolute inset-0 flex items-center justify-center z-20">
        <div class="text-center px-4">
            <h1 class="horror-font text-6xl md:text-8xl blood-red glow mb-4">Midnight Scream</h1>
            <p class="text-xl md:text-2xl italic">Where horror classics come to life...</p>
        </div>
    </div>
</div>

<!-- Company Presentation -->
<section class="py-16 purple-light">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="horror-font text-4xl blood-red mb-8">Welcome to Your Worst Nightmares</h2>
            <p class="text-lg mb-8">
                Established in 1983, Midnight Scream has been the home for horror aficionados seeking the thrill of classic terror.
                Our haunted halls have screened every nightmare imaginable, from cult B-movies to forgotten fright flicks that will chill you to the bone.
            </p>
            <a href="about.php" class="inline-block moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                Learn More About Our Dark Origins
            </a>
        </div>
    </div>
</section>

<!-- Daily Showings -->
<section class="py-16 bg-black">
    <div class="container mx-auto px-6">
        <h2 class="horror-font text-4xl blood-red text-center mb-12">Today's Terrifying Features</h2>

        <!-- Movie 1 -->
        <div class="purple-dark rounded-lg shadow-xl overflow-hidden mb-10">
            <div class="md:flex">
                <div class="md:w-1/3">
                    <img src="http://static.photos/horror/640x360/666" alt="Movie Poster" class="w-full h-full object-cover">
                </div>
                <div class="md:w-2/3 p-6">
                    <h3 class="horror-font text-3xl blood-red mb-2">Night of the Living Dead</h3>
                    <div class="flex flex-wrap gap-4 mb-4">
                        <span class="text-sm"><i data-feather="clock"></i> 96 min</span>
                        <span class="text-sm"><i data-feather="film"></i> Horror</span>
                        <span class="text-sm"><i data-feather="calendar"></i> 1968</span>
                        <span class="text-sm"><i data-feather="user"></i> George A. Romero</span>
                    </div>
                    <p class="mb-6">
                        A group of people hide from bloodthirsty zombies in a farmhouse. But as the dead rise, the living become the real threat.
                        This groundbreaking classic redefined horror and birthed the modern zombie genre.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="booking.php?movie=Night%20of%20the%20Living%20Dead&date=Today&time=19:00&hall=Hall%20A&price=12.50" class="moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                            <i data-feather="tv"></i> Hall A - 19:00
                        </a>
                        <a href="booking.php?movie=Night%20of%20the%20Living%20Dead&date=Today&time=22:30&hall=Hall%20B&price=12.50" class="moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                            <i data-feather="tv"></i> Hall B - 22:30
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movie 2 -->
        <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3">
                    <img src="http://static.photos/horror/640x360/42" alt="Movie Poster" class="w-full h-full object-cover">
                </div>
                <div class="md:w-2/3 p-6">
                    <h3 class="horror-font text-3xl blood-red mb-2">The Texas Chain Saw Massacre</h3>
                    <div class="flex flex-wrap gap-4 mb-4">
                        <span class="text-sm"><i data-feather="clock"></i> 83 min</span>
                        <span class="text-sm"><i data-feather="film"></i> Horror</span>
                        <span class="text-sm"><i data-feather="calendar"></i> 1974</span>
                        <span class="text-sm"><i data-feather="user"></i> Tobe Hooper</span>
                    </div>
                    <p class="mb-6">
                        Five friends visiting their grandfather's old house are hunted down by a chainsaw-wielding killer and his family of grave-robbing cannibals.
                        This relentlessly intense horror masterpiece will leave you traumatized.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="booking.php?movie=The%20Texas%20Chain%20Saw%20Massacre&date=Today&time=20:15&hall=Hall%20C&price=12.50" class="moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                            <i data-feather="tv"></i> Hall C - 20:15
                        </a>
                        <a href="booking.php?movie=The%20Texas%20Chain%20Saw%20Massacre&date=Today&time=23:45&hall=Hall%20A&price=15.00" class="moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                            <i data-feather="tv"></i> Hall A - 23:45
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- News Section -->
<section class="py-16 purple-light">
    <div class="container mx-auto px-6">
        <h2 class="horror-font text-4xl blood-red text-center mb-12">Grave News</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <!-- News 1 -->
            <div class="purple-dark rounded-lg shadow-xl overflow-hidden">
                <img src="http://static.photos/horror/640x360/13" alt="News Image" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-3">All-Night Horror Marathon!</h3>
                    <p class="mb-4">
                        Join us this Halloween for 12 hours of uninterrupted horror classics. From dusk till dawn, we'll be screening rare 35mm prints of forbidden frights.
                    </p>
                    <a href="#" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
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
                    <a href="#" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
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
                    <a href="#" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300">
                        Read More <i data-feather="arrow-right" class="inline"></i>
                    </a>
                </div>
            </div>
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
        highlightColor: 0x9b59b6,
        midtoneColor: 0x4b0082,
        lowlightColor: 0x1a1029,
        baseColor: 0x0,
        blurFactor: 0.50,
        speed: 1.50
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