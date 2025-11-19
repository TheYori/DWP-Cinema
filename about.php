<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$isLoggedIn = $session->logged_in();

$company = new CompanyDisplay();
$hours = "Opening Hours:";
$address = "Company Address:";
$email = "Company Email:";
$number = "Company Phone number:";
$about = "Midnight Scream – Where Horror Never Sleeps";

$openHours = $company->getCompanyInfo($hours);
$companyAddress = $company->getCompanyInfo($address);
$companyEmail = $company->getCompanyInfo($email);
$companyPhone = $company->getCompanyInfo($number);
$companyAbout = $company->getCompanyInfo($about);

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Sanitize strings
    $userName  = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
    $userEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $message   = trim($_POST['message'] ?? '');

    // Validate allowed subjects
    $allowedSubjects = [
            "General Inquiry",
            "Film Submission",
            "Private Event",
            "Press",
            "Other"
    ];

    $subjectRaw = trim($_POST['subject'] ?? 'General Inquiry');
    $subject = in_array($subjectRaw, $allowedSubjects) ? $subjectRaw : "General Inquiry";

    if ($userName && $userEmail && $message)
    {
        $to = "rickiguldborg40@gmail.com";
        $emailSubject = "Message from a Midnight Scream user: " . htmlspecialchars($subject);
        $body = "From: $userName <$userEmail>\n\nMessage:\n$message";

        $headers = "From: Midnight Scream Website <no-reply@midnightscream.dk>\r\n";
        $headers .= "Reply-To: $userEmail\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $emailSubject, $body, $headers))
        {
            header("Location: about.php?sent=1");
            exit;
        } else {
            $error = "Oops! Something went wrong. The crypt might be blocking the message. Try again later.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - About Us</title>
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
                <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
                <a href="news.php" class="text-white hover:text-purple-300">News</a>
                <a href="#" class="text-white hover:text-purple-300">About Us</a>
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




<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Mission Statement -->
        <div class="purple-dark rounded-lg shadow-xl p-8 mb-12">
            <h2 class="horror-font text-4xl blood-red mb-6 text-center"><?php echo nl2br(htmlspecialchars($about)); ?></h2>
            <div class="mb-8">
                <p class="mb-4 text-l">
                    <?php echo nl2br(htmlspecialchars($companyAbout)); ?>
                </p>
            </div>
        </div>

        <!-- Company Information -->
        <div class="purple-dark rounded-lg shadow-xl p-8 mb-12">
            <h1 class="horror-font text-4xl blood-red mb-6 text-center">General information</h1>
            <div class="mb-8">
                <h2 class="horror-font text-2xl blood-red mb-4">Company Info</h2>
                <p class="mb-4 text-l">
                    <?php echo "<b>" . nl2br(htmlspecialchars($hours)) . "</b>" . "<br>" . nl2br(htmlspecialchars($openHours)); ?>
                </p>
                <p class="mb-4 text-l">
                    <?php echo "<b>" . nl2br(htmlspecialchars($address)) . "</b>" . "<br>" . nl2br(htmlspecialchars($companyAddress)); ?>
                </p>
            </div>
            <div class="border-t border-gray-700 pt-6">
                <h2 class="horror-font text-2xl blood-red mb-4">Contact Info</h2>
                <p class="mb-4">
                <p class="mb-4 text-l">
                    <?php echo "<b>" . nl2br(htmlspecialchars($email)) . "</b>" . " " . nl2br(htmlspecialchars($companyEmail)); ?>
                </p>
                <p class="mb-4 text-l">
                    <?php echo "<b>" . nl2br(htmlspecialchars($number)) . "</b>" . " " . nl2br(htmlspecialchars($companyPhone)); ?>
                </p>
            </div>
        </div>



        <!-- Contact Form -->
        <div class="purple-dark rounded-lg shadow-xl p-8">
            <h2 class="horror-font text-4xl blood-red mb-6 text-center">Contact the Crypt Keepers</h2>
            <?php if (isset($_GET['sent'])): ?>
                <div class="bg-green-800 text-white p-4 rounded mb-4">
                    Your message has been sent successfully! The crypt keepers will reply soon... 🦇
                </div>
            <?php elseif (!empty($error)): ?>
                <div class="bg-red-800 text-white p-4 rounded mb-4"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" class="space-y-6">
                <div>
                    <label for="name" class="block mb-2">Your Name</label>
                    <input  type="text" id="name" name="name" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="email" class="block mb-2">Email Address</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label for="subject" class="block mb-2">Subject</label>
                    <select id="subject" name="subject" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option>General Inquiry</option>
                        <option>Film Submission</option>
                        <option>Private Event</option>
                        <option>Press</option>
                        <option>Other</option>
                    </select>
                </div>
                <div>
                    <label for="message" class="block mb-2">Your Message</label>
                    <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 bg-gray-800 text-white rounded focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <button type="submit" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300">
                    Send Message <i data-feather="send" class="inline ml-2"></i>
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
