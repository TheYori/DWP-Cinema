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
                <a href="#" class="text-white font-bold">Movies</a>
                <a href="about.php" class="text-white font-bold">About Us</a>
                <a href="login.php" class="text-white hover:text-purple-300">Login</a>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Movies Grid -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <h1 class="horror-font text-5xl blood-red text-center mb-12">Our Collection of Terror</h1>

        <!-- Search and Filter -->
        <div class="mb-12 purple-dark p-6 rounded-lg">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <div class="flex-grow">
                    <div class="relative">
                        <input type="text" placeholder="Search for nightmares..." class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <button class="absolute right-3 top-3 text-gray-400">
                            <i data-feather="search"></i>
                        </button>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <select class="bg-gray-800 text-white px-4 py-3 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>All Genres</option>
                        <option>Classic Horror</option>
                        <option>Slasher</option>
                        <option>Zombie</option>
                        <option>Psychological</option>
                        <option>Supernatural</option>
                    </select>
                    <select class="bg-gray-800 text-white px-4 py-3 rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>Sort By</option>
                        <option>Release Date (Newest)</option>
                        <option>Release Date (Oldest)</option>
                        <option>Alphabetical</option>
                        <option>Runtime</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Movies Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Movie 1 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/1" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">The Evil Dead</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 85 min</span>
                        <span><i data-feather="calendar"></i> 1981</span>
                    </div>
                    <p class="mb-4 line-clamp-3">Five friends travel to a cabin in the woods, where they unknowingly release flesh-possessing demons.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Movie 2 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/2" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">Halloween</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 91 min</span>
                        <span><i data-feather="calendar"></i> 1978</span>
                    </div>
                    <p class="mb-4 line-clamp-3">Fifteen years after murdering his sister, Michael Myers escapes from a mental hospital and returns to Haddonfield.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Movie 3 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/3" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">Suspiria</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 98 min</span>
                        <span><i data-feather="calendar"></i> 1977</span>
                    </div>
                    <p class="mb-4 line-clamp-3">An American newcomer to a prestigious German ballet academy comes to realize that the school is a front for witchcraft.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Movie 4 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/4" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">The Exorcist</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 122 min</span>
                        <span><i data-feather="calendar"></i> 1973</span>
                    </div>
                    <p class="mb-4 line-clamp-3">When a 12-year-old girl is possessed by a mysterious entity, her mother seeks the help of two priests to save her.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Movie 5 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/5" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">The Thing</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 109 min</span>
                        <span><i data-feather="calendar"></i> 1982</span>
                    </div>
                    <p class="mb-4 line-clamp-3">A research team in Antarctica is hunted by a shape-shifting alien that assumes the appearance of its victims.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Movie 6 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/6" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">Re-Animator</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 86 min</span>
                        <span><i data-feather="calendar"></i> 1985</span>
                    </div>
                    <p class="mb-4 line-clamp-3">A medical student discovers a technique to bring dead tissue back to life, with horrific consequences.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Movie 7 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/7" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">Phantasm</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 88 min</span>
                        <span><i data-feather="calendar"></i> 1979</span>
                    </div>
                    <p class="mb-4 line-clamp-3">A teenage boy and his friends face off against a mysterious grave robber known only as the Tall Man.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
                    </a>
                </div>
            </div>

            <!-- Movie 8 -->
            <div class="movie-card purple-dark rounded-lg shadow-lg overflow-hidden">
                <img src="http://static.photos/horror/640x360/8" alt="Movie Poster" class="w-full h-64 object-cover">
                <div class="p-6">
                    <h3 class="horror-font text-2xl blood-red mb-2">Black Christmas</h3>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span><i data-feather="clock"></i> 98 min</span>
                        <span><i data-feather="calendar"></i> 1974</span>
                    </div>
                    <p class="mb-4 line-clamp-3">During Christmas break, sorority sisters receive disturbing phone calls from a stranger.</p>
                    <a href="movie.php" class="inline-block moss-green hover:bg-green-900 text-white py-2 px-4 rounded transition duration-300 w-full text-center">
                        View Details
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
</script>
</body>
</html>
