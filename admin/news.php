<?php
spl_autoload_register(function ($class) {
    include "../classes/" . $class . ".php";
});

// Check if the user is logged in
$session = new AdminSessionHandler();
$session->confirm_logged_in();

// Initialize NewsCRUD
$newsCRUD = new NewsCRUD();
$message = "";

// Handle deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($newsCRUD->delete($id)) {
        header("Location: news.php?deleted=1");
        exit;
    } else {
        $message = "<p class='text-red-400 font-semibold mt-4'>Failed to delete news post.</p>";
    }
}

// Handle form submission (create)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $release_date = trim($_POST['release_date']);
    $imageFile = $_FILES['banner_img'];

    if ($newsCRUD->create($title, $content, $release_date, $imageFile)) {
        header("Location: news.php?success=1");
        exit;
    } else {
        $message = "<p class='text-red-400 font-semibold mt-4'>Failed to upload news. Please check the image and try again.</p>";
    }
}

// Fetch all news
$newsList = $newsCRUD->getAllNews();

// Success messages
if (isset($_GET['success'])) {
    $message = "<p class='text-green-400 font-semibold mt-4'>News post added successfully!</p>";
}
if (isset($_GET['deleted'])) {
    $message = "<p class='text-green-400 font-semibold mt-4'>News post deleted successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin News</title>
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
        .horror-font { font-family: 'Creepster', cursive; }
        .purple-dark { background-color: #1a1029; }
        .purple-light { background-color: #2a1a4a; }
        .moss-green { background-color: #1a2910; }
        .blood-red { color: #ff3a3a; }
        .glow { text-shadow: 0 0 5px #9b59b6, 0 0 10px #9b59b6; }
        .admin-nav { background-color: #0a0515; }
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
                <a href="login.php?logout=1" class="moss-green hover:bg-green-900 text-white font-bold py-2 px-4 rounded transition duration-300">
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
            <a href="news.php" class="text-purple-300 font-bold">News</a>
            <a href="bookings.php" class="text-white hover:text-purple-300">Bookings</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- News Tabs -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex border-b border-gray-700 mb-8">
            <button id="add-news-tab" class="px-6 py-3 font-medium text-white border-b-2 border-purple-500">
                Add News
            </button>
            <button id="view-news-tab" class="px-6 py-3 font-medium text-gray-400 hover:text-white">
                View/Edit News
            </button>
        </div>

        <!-- Add News Form -->
        <div id="add-news-section" class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Add New News</h2>
            <?php if (!empty($message)) echo $message; ?>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <div>
                    <label for="banner_img" class="block mb-2">Banner Image</label>
                    <input type="file" name="banner_img" id="banner_img" accept="image/*"
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="title" class="block mb-2">Title</label>
                    <input type="text" name="title" id="title" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="content" class="block mb-2">Content</label>
                    <textarea name="content" id="content" rows="4" required
                              class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div>
                    <label for="release_date" class="block mb-2">Release Date</label>
                    <input type="date" name="release_date" id="release_date" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                        Release News <i data-feather="plus" class="inline ml-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- View News Table -->
        <div id="view-news-section" class="purple-dark rounded-lg shadow-xl p-8 hidden">
            <h2 class="horror-font text-3xl blood-red mb-6">News Collection</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4">Banner</th>
                        <th class="pb-4">Title</th>
                        <th class="pb-4">Release Date</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($newsList)): ?>
                        <?php foreach ($newsList as $news): ?>
                            <tr class="border-b border-gray-700 hover:bg-purple-light/30 transition">
                                <td class="py-4">
                                    <img src="../images/news/<?= htmlspecialchars($news['banner_img']); ?>"
                                         alt="Banner" class="w-48 h-24 object-cover rounded-lg shadow-lg">
                                </td>
                                <td class="py-4"><?= htmlspecialchars($news['title']); ?></td>
                                <td><?php echo !empty($news['release_date']) ? htmlspecialchars(date('j M Y', strtotime($news['release_date']))) : '-'; ?></td>
                                <td class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <a href="edit-news.php?id=<?= $news['news_id']; ?>"
                                           class="flex items-center text-yellow-400 hover:text-yellow-300 transition">
                                            <i data-feather="edit" class="mr-1 w-4 h-4"></i>Edit
                                        </a>
                                        <a href="news.php?delete=<?= $news['news_id']; ?>"
                                           onclick="return confirm('Are you sure you want to delete this news post?');"
                                           class="flex items-center text-red-400 hover:text-red-300 transition">
                                            <i data-feather="trash-2" class="mr-1 w-4 h-4"></i>Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="py-4 text-gray-400">No news posts available.</td></tr>
                    <?php endif; ?>
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
        const addTab = document.getElementById('add-news-tab');
        const viewTab = document.getElementById('view-news-tab');
        const addSection = document.getElementById('add-news-section');
        const viewSection = document.getElementById('view-news-section');

        addTab.addEventListener('click', function() {
            addTab.classList.add('text-white', 'border-purple-500');
            addTab.classList.remove('text-gray-400');
            viewTab.classList.add('text-gray-400');
            viewTab.classList.remove('text-white', 'border-purple-500');
            addSection.classList.remove('hidden');
            viewSection.classList.add('hidden');
        });

        viewTab.addEventListener('click', function() {
            viewTab.classList.add('text-white', 'border-purple-500');
            viewTab.classList.remove('text-gray-400');
            addTab.classList.add('text-gray-400');
            addTab.classList.remove('text-white', 'border-purple-500');
            viewSection.classList.remove('hidden');
            addSection.classList.add('hidden');
        });
    });
</script>
</body>
</html>
