<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the user is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

// Create CRUD object
$companyCRUD = new CompanyCRUD();

// Get company info by ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: company.php");
    exit;
}

$id = (int) $_GET['id'];
$company = $companyCRUD->getById($id);

if (!$company) {
    // If no record found, redirect back
    header("Location: company.php?notfound=1");
    exit;
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_key = trim($_POST['info-type']);
    $key_value = trim($_POST['info-data']);

    if (!empty($data_key) && !empty($key_value)) {
        $updated = $companyCRUD->update($id, $data_key, $key_value);
        if ($updated) {
            header("Location: company.php?updated=1");
            exit;
        } else {
            $message = "<p class='text-red-400 font-semibold mt-4'>Failed to change the data entry</p>";
        }
    } else {
        $message = "<p class='text-yellow-400 font-semibold mt-4'>Both fields are required, ya idjit...</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Edit Company Info</title>
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
            <a href="company.php" class="text-purple-300 font-bold">Company Info</a>
            <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
            <a href="showtimes.php" class="text-white hover:text-purple-300">Showtimes</a>
            <a href="news.php" class="text-white hover:text-purple-300">News</a>
            <a href="bookings.php" class="text-white hover:text-purple-300">Bookings</a>
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Edit Company Info Form -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Edit Company Information</h2>
            <form method="POST" class="space-y-6">
                <?php if (!empty($message)) echo $message; ?>
                <div>
                    <label for="edit-info-type" class="block mb-2">Information Type</label>
                    <input type="text" name="info-type" id="edit-info-type"
                           value="<?= htmlspecialchars($company['data_key']); ?>" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="edit-info-data" class="block mb-2">Information Data</label>
                    <textarea name="info-data" id="edit-info-data" rows="4" required
                              class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"><?= htmlspecialchars($company['key_value']); ?></textarea>
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="company.php" class="inline-block purple-light hover:bg-purple-800 text-white font-bold py-3 px-6 rounded transition duration-300">
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
</body>
</html>
