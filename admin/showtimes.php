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
    <title>Midnight Scream Spectacle - Admin Showtimes</title>
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
            <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
            <a href="showtimes.php" class="text-purple-300 font-bold">Showtimes</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Showtimes Tabs -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex border-b border-gray-700 mb-8">
            <button id="add-showtime-tab" class="px-6 py-3 font-medium text-white border-b-2 border-purple-500">
                Add Showtime
            </button>
            <button id="view-showtimes-tab" class="px-6 py-3 font-medium text-gray-400 hover:text-white">
                View/Edit Showtimes
            </button>
        </div>

        <!-- Add Showtime Form -->
        <div id="add-showtime-section" class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Add New Showtime</h2>
            <form class="space-y-6">
                <div>
                    <label for="showtime-movie" class="block mb-2">Movie Title</label>
                    <select id="showtime-movie" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select a movie</option>
                        <option value="1">The Exorcist</option>
                        <option value="2">Halloween</option>
                        <option value="3">The Texas Chain Saw Massacre</option>
                    </select>
                </div>
                <div>
                    <label for="showtime-date" class="block mb-2">Date</label>
                    <input type="date" id="showtime-date" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="showtime-time" class="block mb-2">Time</label>
                    <input type="time" id="showtime-time" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="showtime-hall" class="block mb-2">Hall</label>
                    <select id="showtime-hall" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">Select a hall</option>
                        <option value="Hall A">Hall A</option>
                        <option value="Hall B">Hall B</option>
                        <option value="Hall C">Hall C</option>
                    </select>
                </div>
                <button type="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Add Showtime <i data-feather="plus" class="inline ml-2"></i>
                </button>
            </form>
        </div>

        <!-- View Showtimes Table -->
        <div id="view-showtimes-section" class="purple-dark rounded-lg shadow-xl p-8 hidden">
            <h2 class="horror-font text-3xl blood-red mb-6">Current Showtimes</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4">Movie</th>
                        <th class="pb-4">Date</th>
                        <th class="pb-4">Time</th>
                        <th class="pb-4">Hall</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-b border-gray-700">
                        <td class="py-4">The Exorcist</td>
                        <td class="py-4">October 31, 2023</td>
                        <td class="py-4">19:00</td>
                        <td class="py-4">Hall A</td>
                        <td class="py-4">
                            <a href="edit-showtime.php?id=1" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
                                <i data-feather="edit" class="mr-1"></i> Edit
                            </a>
                            <button class="text-red-400 hover:text-red-300">
                                <i data-feather="trash-2" class="mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <td class="py-4">Halloween</td>
                        <td class="py-4">October 30, 2023</td>
                        <td class="py-4">20:30</td>
                        <td class="py-4">Hall B</td>
                        <td class="py-4">
                            <a href="edit-showtime.php?id=2" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
                                <i data-feather="edit" class="mr-1"></i> Edit
                            </a>
                            <button class="text-red-400 hover:text-red-300">
                                <i data-feather="trash-2" class="mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">The Texas Chain Saw Massacre</td>
                        <td class="py-4">October 29, 2023</td>
                        <td class="py-4">22:00</td>
                        <td class="py-4">Hall C</td>
                        <td class="py-4">
                            <a href="edit-showtime.php?id=3" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
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

<script>
    feather.replace();

    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const addShowtimeTab = document.getElementById('add-showtime-tab');
        const viewShowtimesTab = document.getElementById('view-showtimes-tab');
        const addShowtimeSection = document.getElementById('add-showtime-section');
        const viewShowtimesSection = document.getElementById('view-showtimes-section');

        addShowtimeTab.addEventListener('click', function() {
            addShowtimeTab.classList.add('text-white', 'border-purple-500');
            addShowtimeTab.classList.remove('text-gray-400');
            viewShowtimesTab.classList.add('text-gray-400');
            viewShowtimesTab.classList.remove('text-white', 'border-purple-500');
            addShowtimeSection.classList.remove('hidden');
            viewShowtimesSection.classList.add('hidden');
        });

        viewShowtimesTab.addEventListener('click', function() {
            viewShowtimesTab.classList.add('text-white', 'border-purple-500');
            viewShowtimesTab.classList.remove('text-gray-400');
            addShowtimeTab.classList.add('text-gray-400');
            addShowtimeTab.classList.remove('text-white', 'border-purple-500');
            viewShowtimesSection.classList.remove('hidden');
            addShowtimeSection.classList.add('hidden');
        });
    });
</script>
</body>
</html>