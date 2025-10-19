<?php
spl_autoload_register(function ($class)
{include"../classes/".$class.".php";});
//check of the user is logged in:
$session = new AdminSessionHandler();
$session->confirm_logged_in();

// Create CRUD object
$companyCRUD = new CompanyCRUD();
$companyData = $companyCRUD->getAll();

// Handle form submission
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data_key = trim($_POST['info-type']);
    $key_value = trim($_POST['info-data']);

    if (!empty($data_key) && !empty($key_value)) {
        $created = $companyCRUD->create($data_key, $key_value);

        if ($created) {
            // Optional: reload page to refresh data and clear form
            header("Location: company.php?success=1");
            exit;
        } else {
            $message = "<p class='text-red-400 font-semibold mt-4'>Failed to save information. Please try again.</p>";
        }
    } else {
        $message = "<p class='text-yellow-400 font-semibold mt-4'>Both fields are required.</p>";
    }
}

// Fetch all company info (refresh list after adding)
$companyData = $companyCRUD->getAll();

// Optional: show success message after redirect
if (isset($_GET['success'])) {
    $message = "<p class='text-green-400 font-semibold mt-4'>Company information successfully added!</p>";
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete']; // make sure it’s an integer for safety
    if ($companyCRUD->delete($id)) {
        header("Location: company.php?deleted=1");
        exit;
    } else {
        header("Location: company.php?error=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Admin Company Info</title>
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
            <a href="admin.php" class="text-white hover:text-purple-300">Admins</a>
        </div>
    </div>
</div>

<!-- Company Info Tabs -->
<section class="py-8">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="flex border-b border-gray-700 mb-8">
            <button id="add-tab" class="px-6 py-3 font-medium text-white border-b-2 border-purple-500">
                Add Company Info
            </button>
            <button id="view-tab" class="px-6 py-3 font-medium text-gray-400 hover:text-white">
                View/Edit Company Info
            </button>
        </div>

        <!-- Add Company Info Form -->
        <div id="add-section" class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-3xl blood-red mb-6">Add New Company Information</h2>
            <?php if (!empty($message)) echo $message; ?>
            <form method="POST" class="space-y-6">
                <div>
                    <label for="info-type" class="block mb-2">Information Type</label>
                    <input type="text" name="info-type" id="info-type" required
                           class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="info-data" class="block mb-2">Information Data</label>
                    <textarea name="info-data" id="info-data" rows="4" required
                              class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <button type="submit"
                        class="moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Save Information <i data-feather="save" class="inline ml-2"></i>
                </button>
            </form>
        </div>

        <!-- View Company Info Table -->
        <div id="view-section" class="purple-dark rounded-lg shadow-xl p-8 hidden">
            <h2 class="horror-font text-3xl blood-red mb-6">Company Information</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr class="text-left border-b border-gray-700">
                        <th class="pb-4">Type</th>
                        <th class="pb-4">Data</th>
                        <th class="pb-4">Actions</th>
                    </tr>
                    </thead>
                    <?php if (!empty($companyData)): ?>
                        <?php foreach ($companyData as $company): ?>
                            <tr class="border-b border-gray-700 hover:bg-purple-light/30 transition">
                                <td class="py-4"><?= htmlspecialchars($company['data_key']); ?></td>
                                <td class="py-4"><?= htmlspecialchars($company['key_value']); ?></td>
                                <td class="py-4">
                                    <div class="flex items-center space-x-4">
                                        <a href="edit-company.php?id=<?= $company['key_id']; ?>"
                                           class="flex items-center text-yellow-400 hover:text-yellow-300 transition">
                                            <i data-feather="edit" class="mr-1"></i>
                                            Edit
                                        </a>
                                        <a href="company.php?delete=<?= $company['key_id']; ?>"
                                           onclick="return confirm('Are you sure you want to delete this entry?');"
                                           class="flex items-center text-red-400 hover:text-red-300 transition">
                                            <i data-feather="trash-2" class="mr-1 w-4 h-4"></i>
                                            Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-4 text-gray-400 text-center">No company information found.</td>
                        </tr>
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
        const addTab = document.getElementById('add-tab');
        const viewTab = document.getElementById('view-tab');
        const addSection = document.getElementById('add-section');
        const viewSection = document.getElementById('view-section');

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
