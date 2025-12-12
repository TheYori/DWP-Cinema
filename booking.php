<?php
spl_autoload_register(function ($class)
{include"classes/".$class.".php";});
//check of the user is logged in:
$session = new UserSessionHandler();
$session->confirm_logged_in();
$isLoggedIn = $session->logged_in();

$booking= new BookingDisplay();

if (!isset($_GET['showtime_id'])) {
    die("Missing showtime ID.");
}

$showtime_id = filter_input(INPUT_GET, 'showtime_id', FILTER_VALIDATE_INT);

if (!$showtime_id) {
    die("Invalid showtime ID.");
}

$showtime = $booking->getShowtime($showtime_id);

if (!$showtime) {
    die("Showtime not found.");
}

$seats = $booking->getSeats($showtime['hall_id'], $showtime_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midnight Scream Spectacle - Booking</title>
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
        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .seat:hover:not(.occupied) {
            transform: scale(1.1);
        }
        .seat.selected {
            background-color: #9b59b6 !important;
        }
        .seat.occupied {
            background-color: #ff3a3a;
            cursor: not-allowed;
        }
        .screen {
            background: linear-gradient(to bottom, rgba(255,255,255,0.1), rgba(255,255,255,0.3));
            height: 30px;
            width: 100%;
            margin: 30px 0;
            transform: perspective(400px) rotateX(-20deg);
            box-shadow: 0 3px 10px rgba(255,255,255,0.7);
        }
        .checkout-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center hidden">
    <div class="purple-dark rounded-lg shadow-2xl p-8 text-center max-w-md w-full">
        <h3 class="horror-font text-3xl blood-red mb-4">Confirm Your Booking</h3>
        <p class="mb-2">Seats: <span id="modal-seats"></span></p>
        <p class="mb-4">Total: $<span id="modal-total"></span></p>
        <div class="flex justify-around">
            <button id="cancel-btn" class="bg-red-700 hover:bg-red-800 text-white px-6 py-2 rounded">Nope</button>
            <button id="confirm-btn" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded">Hell Yeah!</button>
        </div>
    </div>
</div>
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
                <a href="about.php" class="text-white hover:text-purple-300">About Us</a>
                <?php if ($isLoggedIn): ?>
                    <a href="profile.php" class="text-white hover:text-purple-300">Profile</a>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-purple-300">Login</a>
                <?php endif; ?>
            </div>
            <div class="md:hidden relative z-50">
                <button id="mobile-menu-button" type="button" class="text-white focus:outline-none">
                    <i data-feather="menu"></i>
                </button>

                <div id="mobile-menu" class="hidden fixed inset-0 purple-dark z-50 pt-20">
                    <div class="flex flex-col items-center space-y-6 text-xl">
                        <a href="index.php" class="text-white hover:text-purple-300">Home</a>
                        <a href="movies.php" class="text-white hover:text-purple-300">Movies</a>
                        <a href="news.php" class="text-white hover:text-purple-300">News</a>
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

<!-- Booking Section -->
<section class="py-16">
    <div class="container mx-auto px-6 max-w-4xl">
        <div class="purple-dark rounded-lg shadow-xl overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="horror-font text-3xl blood-red mb-2"><?php echo htmlspecialchars($showtime['title']); ?></h2>
                <div class="flex flex-wrap gap-4 mb-4">
                    <span class="flex items-center"><i data-feather="calendar" class="mr-2"></i>
                        <?php echo date('l, F j, Y', strtotime($showtime['show_date'])); ?>
                    </span>
                    <span class="flex items-center"><i data-feather="clock" class="mr-2"></i>
                        <?php echo substr($showtime['show_time'], 0, 5); ?>
                    </span>
                    <span class="flex items-center"><i data-feather="tv" class="mr-2"></i>
                        <?php echo htmlspecialchars($showtime['hall_name']); ?>
                    </span>
                </div>
                <div>
                    <p><b>Rules:</b></p>
                    <ol type="1">
                        <li><b>1.</b> You can only select seats right besides each other</li>
                        <li><b>2.</b> You only pick seats from a single row</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Screen and Seats -->
        <div class="text-center mb-12">
            <div class="screen"></div>

            <div id="seats-container" class="mb-8 text-center">
                <?php
                $currentRow = '';
                foreach ($seats as $seat) {
                    // Parse seat naming structure (like "Seat A1-1")
                    preg_match('/([A-Z0-9]+-[0-9]+)/', $seat['seat_name'], $matches);
                    $name = isset($matches[1]) ? $matches[1] : $seat['seat_name'];

                    // Start new row if needed
                    $rowLetter = substr($name, 0, 2);
                    if ($rowLetter !== $currentRow) {
                        if ($currentRow !== '') echo '</div>';
                        echo "<div class='mb-4'>";
                        $currentRow = $rowLetter;
                    }

                    $status = $seat['is_booked'] ? 'occupied' : 'bg-gray-600';

                    // Parse row and column (assuming seat_name looks like "Seat A1-3")
                    preg_match('/([A-Z0-9]+)-([0-9]+)/', $seat['seat_name'], $matches);
                    $rowKey = $matches[1] ?? 'X'; // Example: "A1"
                    $colNum = isset($matches[2]) ? (int)$matches[2] : 0;

                    echo "<div class='seat {$status}' 
                            data-id='{$seat['seat_id']}' 
                            data-row='{$rowKey}' 
                            data-col='{$colNum}' 
                            title='{$seat['seat_name']}'>
                           </div>";
                }
                echo "</div>";
                ?>
            </div>

            <div class="flex justify-center gap-8 mb-8">
                <div class="flex items-center">
                    <div class="seat bg-gray-600 mr-2"></div>
                    <span>Available</span>
                </div>
                <div class="flex items-center">
                    <div class="seat selected mr-2"></div>
                    <span>Selected</span>
                </div>
                <div class="flex items-center">
                    <div class="seat occupied mr-2"></div>
                    <span>Occupied</span>
                </div>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="purple-dark rounded-lg shadow-xl p-6">
            <h3 class="horror-font text-2xl blood-red mb-4">Your Order</h3>
            <div class="flex justify-between mb-4">
                <span>Tickets (<span id="selected-count">0</span>):</span>
                <span>$<span id="total-price">0.00</span></span>
            </div>
            <div class="flex justify-between mb-6">
                <span>Service Fee:</span>
                <span>$2.50</span>
            </div>
            <div class="flex justify-between font-bold text-lg mb-6">
                <span>Total:</span>
                <span>$<span id="grand-total">2.50</span></span>
            </div>
            <button id="checkout-btn" class="w-full moss-green hover:bg-green-900 text-white font-bold py-3 px-6 rounded transition duration-300 checkout-btn" disabled>
                Proceed to Checkout
            </button>
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

    document.addEventListener('DOMContentLoaded', function() {
        const seatCount   = document.getElementById('selected-count');
        const totalPrice  = document.getElementById('total-price');
        const grandTotal  = document.getElementById('grand-total');
        const checkoutBtn = document.getElementById('checkout-btn');

        const seatPrice  = 12.50;
        const serviceFee = 2.50;

        let selectedSeats = []; // {id, row, col}
        let activeRow = null;   // locked to a single row after first seat

        // Attach listeners to all available seats
        document.querySelectorAll('.seat:not(.occupied)').forEach(seat => {
            seat.addEventListener('click', () => toggleSeat(seat));
        });

        function toggleSeat(seat) {
            if (seat.classList.contains('occupied')) return;

            const id  = seat.dataset.id;
            const row = seat.dataset.row;
            const col = parseInt(seat.dataset.col);

            const idx = selectedSeats.findIndex(s => s.id === id);

            // --- Deselect ---
            if (idx !== -1) {
                selectedSeats.splice(idx, 1);
                seat.classList.remove('selected');

                if (selectedSeats.length === 0) activeRow = null;
                updateTotals();
                return;
            }

            // --- Select ---
            if (selectedSeats.length === 0) {
                // First seat, define the row
                activeRow = row;
                selectedSeats.push({ id, row, col });
                seat.classList.add('selected');
                updateTotals();
                return;
            }

            // Rule 1: same row only
            if (row !== activeRow) {
                alert('You can only pick seats from a single row.');
                return;
            }

            // Rule 2: adjacent seats only (extend from ends)
            const cols = selectedSeats.map(s => s.col).sort((a, b) => a - b);
            const min  = cols[0];
            const max  = cols[cols.length - 1];
            const isAdjacent = (col === min - 1) || (col === max + 1);

            if (!isAdjacent) {
                alert('You can only select seats right beside each other.');
                return;
            }

            // Passed all checks
            selectedSeats.push({ id, row, col });
            seat.classList.add('selected');
            updateTotals();
        }

        function updateTotals() {
            const count    = selectedSeats.length;
            const subtotal = count * seatPrice;
            const total    = subtotal + serviceFee;

            seatCount.textContent  = count;
            totalPrice.textContent = subtotal.toFixed(2);
            grandTotal.textContent = total.toFixed(2);

            checkoutBtn.disabled = count === 0;
        }

        // --- Modal elements ---
        const modal = document.getElementById('confirm-modal');
        const modalSeats = document.getElementById('modal-seats');
        const modalTotal = document.getElementById('modal-total');
        const cancelBtn = document.getElementById('cancel-btn');
        const confirmBtn = document.getElementById('confirm-btn');

        // --- Checkout button triggers modal ---
        checkoutBtn.addEventListener('click', () => {
            const seatList = selectedSeats
                .sort((a, b) => a.col - b.col)
                .map(s => `${s.row}-${s.col}`)
                .join(', ');

            modalSeats.textContent = seatList;
            modalTotal.textContent = (selectedSeats.length * seatPrice + serviceFee).toFixed(2);
            modal.classList.remove('hidden');
        });

        // --- Cancel and Confirm buttons ---
        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        confirmBtn.addEventListener('click', async () => {
            const seatIds = selectedSeats.map(s => s.id);

            try {
                const response = await fetch('create_ticket.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        showtime_id: <?php echo (int)$showtime_id; ?>,
                        seat_ids: seatIds
                    })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert('Booking successful! Redirecting to your profile...');
                    window.location.href = 'profile.php';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (err) {
                alert('Failed to complete booking.');
                console.error(err);
            }
        });

    });
</script>

<script>
    feather.replace();

    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            mobileMenu.classList.toggle('hidden');
        });

        // Close menu when clicking a link
        mobileMenu.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => mobileMenu.classList.add('hidden'));
        });

        // Close menu when clicking outside
        document.addEventListener('click', (event) => {
            if (!mobileMenu.contains(event.target) && event.target !== mobileMenuButton) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
</script>
</body>
</html>
