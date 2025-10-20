<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the user is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Movies</title>
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
        .admin-nav {
            background-color: #0a0515;
        }
    </style>
</head>
<body class="min-h-screen">
<!-- Navigation -->
<nav class="purple-dark sticky top-0 z-50 shadow-lg">
    <div class="container mx-auto px-6 py-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <a href="../index.php" class="flex items-center">
                    <span class="horror-font text-3xl blood-red glow">Midnight Scream</span>
                </a>
            </div>
            <h1>Welcome to the backend: <strong><?php echo $_SESSION['username']; ?></strong></h1>
            <div class="flex items-center space-x-4">
                <a href="../index.php" class="hidden md:block text-white hover:text-purple-300">Back to Main Site</a>
                <a href="login.php?logout=1" class="moss-green hover:bg-green-900 text-white font-bold py-2 px-4 rounded transition duration-300" >
                    Log Out <i data-feather="log-out" class="inline ml-1"></i>
                </a>
            </div>
            <div class="md:hidden">
                <button class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Admin Navigation -->
<div class="admin-nav py-4 shadow-inner">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap gap-4">
            <a href="company.php" class="text-white hover:text-purple-300">Company Info</a>
            <a href="movies.php" class="text-purple-300 font-bold">Movies</a>
            <a href="showtimes.php" class="text-white hover:text-purple-300">Showtimes</a>
            <a href="news.php" class="text-white hover:text-purple-300">News</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Movies Tabs -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex border-b border-gray-700 mb-8">
            <button id="add-movie-tab" class="px-6 py-3 font-medium text-white border-b-2 border-purple-500">
                Add Movie
            </button>
            <button id="view-movies-tab" class="px-6 py-3 font-medium text-gray-400 hover:text-white">
                View/Edit Movies
            </button>
        </div>

        <!-- Add Movie Form -->
        <div id="add-movie-section" class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Add New Movie</h2>
            <form class="space-y-6">
                <div>
                    <label for="movie-poster" class="block mb-2">Poster Image</label>
                    <input type="file" id="movie-poster" accept="image/*" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="movie-title" class="block mb-2">Title</label>
                    <input type="text" id="movie-title" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="movie-description" class="block mb-2">Description</label>
                    <textarea id="movie-description" rows="4" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="movie-length" class="block mb-2">Length (minutes)</label>
                        <input type="number" id="movie-length" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="movie-release-date" class="block mb-2">Release Date</label>
                        <input type="date" id="movie-release-date" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="movie-rating" class="block mb-2">Rating</label>
                        <input type="text" id="movie-rating" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="movie-director" class="block mb-2">Director</label>
                        <input type="text" id="movie-director" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                <div>
                    <label for="movie-genre" class="block mb-2">Genre</label>
                    <select id="movie-genre" multiple class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>Horror</option>
                        <option>Thriller</option>
                        <option>Psychological</option>
                        <option>Supernatural</option>
                        <option>Slasher</option>
                        <option>Sci-Fi</option>
                        <option>Classic</option>
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                        Add Movie <i data-feather="plus" class="inline ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- View Movies Table -->
        <div id="view-movies-section" class="purple-dark rounded-lg shadow-xl p-8 hidden">
            <h2 class="horror-font text-3xl blood-red mb-6">Movie Collection</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4">Poster</th>
                        <th class="pb-4">Title</th>
                        <th class="pb-4">Year</th>
                        <th class="pb-4">Director</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-b border-gray-700">
                        <td class="py-4"><img src="http://static.photos/horror/200x200/1" alt="Movie Poster" class="w-16 h-24 object-cover rounded"></td>
                        <td class="py-4">The Exorcist</td>
                        <td class="py-4">1973</td>
                        <td class="py-4">William Friedkin</td>
                        <td class="py-4">
                            <a href="edit-movie.php?id=1" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
                                <i data-feather="edit" class="mr-1"></i> Edit
                            </a>
                            <button class="text-red-400 hover:text-red-300">
                                <i data-feather="trash-2" class="mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <td class="py-4"><img src="http://static.photos/horror/200x200/2" alt="Movie Poster" class="w-16 h-24 object-cover rounded"></td>
                        <td class="py-4">Halloween</td>
                        <td class="py-4">1978</td>
                        <td class="py-4">John Carpenter</td>
                        <td class="py-4">
                            <a href="edit-movie.php?id=2" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
                                <i data-feather="edit" class="mr-1"></i> Edit
                            </a>
                            <button class="text-red-400 hover:text-red-300">
                                <i data-feather="trash-2" class="mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4"><img src="http://static.photos/horror/200x200/3" alt="Movie Poster" class="w-16 h-24 object-cover rounded"></td>
                        <td class="py-4">The Texas Chain Saw Massacre</td>
                        <td class="py-4">1974</td>
                        <td class="py-4">Tobe Hooper</td>
                        <td class="py-4">
                            <a href="edit-movie.php?id=3" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
                                <i data-feather="edit" class="mr-1"></i> Edit
                            </a>
                            <button class="text-red-400 hover:text-red-300">
                                <i data-feather="trash-2" class="mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
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
                <a href="../index.php" class="hover:text-purple-300">Back to Main Site</a>
            </div>
        </div>
    </div>
</footer>

<script>
    feather.replace();

    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addMovieTab = document.getElementById('add-movie-tab');
        const viewMoviesTab = document.getElementById('view-movies-tab');
        const addMovieSection = document.getElementById('add-movie-section');
        const viewMoviesSection = document.getElementById('view-movies-section');

        addMovieTab.addEventListener('click', function() {
            addMovieTab.classList.add('text-white', 'border-purple-500');
            addMovieTab.classList.remove('text-gray-400');
            viewMoviesTab.classList.add('text-gray-400');
            viewMoviesTab.classList.remove('text-white', 'border-purple-500');
            addMovieSection.classList.remove('hidden');
            viewMoviesSection.classList.add('hidden');
        });

        viewMoviesTab.addEventListener('click', function() {
            viewMoviesTab.classList.add('text-white', 'border-purple-500');
            viewMoviesTab.classList.remove('text-gray-400');
            addMovieTab.classList.add('text-gray-400');
            addMovieTab.classList.remove('text-white', 'border-purple-500');
            viewMoviesSection.classList.remove('hidden');
            addMovieSection.classList.add('hidden');
        });
    });
</script>
</body>
</html>