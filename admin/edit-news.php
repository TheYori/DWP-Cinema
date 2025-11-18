<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the admin is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

// Initialize NewsCRUD
$newsCRUD = new NewsCRUD();
$message = "";

// Validate ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: news.php");
    exit;
}

$id = (int)$_GET['id'];

// Fetch current news data
$news = $newsCRUD->getById($id);
if (!$news) {
    header("Location: news.php?notfound=1");
    exit;
}

// Submission Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $release_date = $_POST['release_date'];
    $imageFile = $_FILES['banner_img'];

    if ($newsCRUD->update($id, $title, $content, $release_date, $imageFile)) {
        header("Location: news.php?updated=1");
        exit;
    } else {
        $message = "<p class='text-red-400 font-semibold mt-4'>Failed to update news post.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Edit News</title>
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
            <a href="seats.php" class="text-white hover:text-purple-300">Booked Seats</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Edit News Form -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Edit News Post</h2>

            <?php if (!empty($message)) echo $message; ?>

            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Banner Image -->
                <div>
                    <label for="banner_img" class="block mb-2">Banner Image</label>
                    <input type="file" name="banner_img" id="banner_img" accept="image/*"
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <div class="mt-4">
                        <p class="text-sm text-gray-400 mb-1">Current Banner:</p>
                        <img src="../images/news/<?= htmlspecialchars($news['banner_img']); ?>"
                             alt="Current Banner" class="w-64 h-32 object-cover rounded shadow-lg">
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block mb-2">Title</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($news['title']); ?>" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block mb-2">Content</label>
                    <textarea name="content" id="content" rows="5" required
                              class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"><?= htmlspecialchars($news['content']); ?></textarea>
                </div>

                <!-- Release Date -->
                <div>
                    <label for="release_date" class="block mb-2">Release Date</label>
                    <input type="date" name="release_date" id="release_date" value="<?= htmlspecialchars($news['release_date']); ?>" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="news.php" class="inline-block purple-light hover:bg-purple-800 text-white font-bold py-3 px-6 rounded transition duration-300">
                        Cancel
                    </a>
                    <button type="submit" class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                        Save Changes <i data-feather="save" class="inline ml-2"></i>
                    </button>
                </div>
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
                <a href="../index.php" class="hover:text-purple-300">Back to Main Site</a>
            </div>
        </div>
    </div>
</footer>

<script>
    feather.replace();
</script>
</body>
</html>
