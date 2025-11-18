<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the admin is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Management</title>
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
        </div>
    </div>
</nav>

<!-- Admin Navigation -->
<div class="admin-nav py-4 shadow-inner">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap gap-4">
            <a href="company.php" class="text-white hover:text-purple-300">Company Info</a>
            <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
            <a href="showtimes.php" class="text-white hover:text-purple-300">Showtimes</a>
            <a href="news.php" class="text-white hover:text-purple-300">News</a>
            <a href="bookings.php" class="text-white hover:text-purple-300">Bookings</a>
            <a href="seats.php" class="text-white hover:text-purple-300">Booked Seats</a>
            <a href="admin.php" class="text-purple-300 font-bold">Admins</a>
        </div>
    </div>
</div>

<!-- Admin Management Tabs -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex border-b border-gray-700 mb-8">
            <button id="add-admin-tab" class="px-6 py-3 font-medium text-white border-b-2 border-purple-500">
                Add Admin
            </button>
            <button id="view-admins-tab" class="px-6 py-3 font-medium text-gray-400 hover:text-white">
                View/Edit Admins
            </button>
        </div>

        <!-- Add Admin Form -->
        <div id="add-admin-section" class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Add New Admin</h2>
            <form class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="admin-first-name" class="block mb-2">First Name</label>
                        <input type="text" id="admin-first-name" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="admin-last-name" class="block mb-2">Last Name</label>
                        <input type="text" id="admin-last-name" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                <div>
                    <label for="admin-username" class="block mb-2">Username</label>
                    <input type="text" id="admin-username" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="admin-password" class="block mb-2">Password</label>
                    <input type="password" id="admin-password" required class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <button type="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Create Admin <i data-feather="user-plus" class="inline ml-2"></i>
                </button>
            </form>
        </div>

        <!-- View Admins Table -->
        <div id="view-admins-section" class="purple-dark rounded-lg shadow-xl p-8 hidden">
            <h2 class="horror-font text-3xl blood-red mb-6">Admin Accounts</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4">First Name</th>
                        <th class="pb-4">Last Name</th>
                        <th class="pb-4">Username</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="border-b border-gray-700">
                        <td class="py-4">Vincent</td>
                        <td class="py-4">Graves</td>
                        <td class="py-4">admin1</td>
                        <td class="py-4">
                            <a href="edit-admin.php?id=1" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
                                <i data-feather="edit" class="mr-1"></i> Edit
                            </a>
                            <button class="text-red-400 hover:text-red-300">
                                <i data-feather="trash-2" class="mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <td class="py-4">Eliza</td>
                        <td class="py-4">Moon</td>
                        <td class="py-4">admin2</td>
                        <td class="py-4">
                            <a href="edit-admin.php?id=2" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
                                <i data-feather="edit" class="mr-1"></i> Edit
                            </a>
                            <button class="text-red-400 hover:text-red-300">
                                <i data-feather="trash-2" class="mr-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4">Max</td>
                        <td class="py-4">Raven</td>
                        <td class="py-4">admin3</td>
                        <td class="py-4">
                            <a href="edit-admin.php?id=3" class="inline-block mr-2 text-yellow-400 hover:text-yellow-300">
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
        const addAdminTab = document.getElementById('add-admin-tab');
        const viewAdminsTab = document.getElementById('view-admins-tab');
        const addAdminSection = document.getElementById('add-admin-section');
        const viewAdminsSection = document.getElementById('view-admins-section');

        addAdminTab.addEventListener('click', function() {
            addAdminTab.classList.add('text-white', 'border-purple-500');
            addAdminTab.classList.remove('text-gray-400');
            viewAdminsTab.classList.add('text-gray-400');
            viewAdminsTab.classList.remove('text-white', 'border-purple-500');
            addAdminSection.classList.remove('hidden');
            viewAdminsSection.classList.add('hidden');
        });

        viewAdminsTab.addEventListener('click', function() {
            viewAdminsTab.classList.add('text-white', 'border-purple-500');
            viewAdminsTab.classList.remove('text-gray-400');
            addAdminTab.classList.add('text-gray-400');
            addAdminTab.classList.remove('text-white', 'border-purple-500');
            viewAdminsSection.classList.remove('hidden');
            addAdminSection.classList.add('hidden');
        });
    });
</script>
</body>
</html>