<?php
spl_autoload_register(function ($class) {
    include "classes/" . $class . ".php";
});

$userSession = new UserSessionHandler();
$adminSession = new AdminSessionHandler();

// Check login
if (!$userSession->logged_in() && !$adminSession->logged_in()) {
    header("Location: login.php");
    exit;
}

// Get the ticket ID from URL
$ticket_id = isset($_GET['ticket_id']) ? (int)$_GET['ticket_id'] : 0;
if ($ticket_id <= 0) {
    die("Invalid ticket ID");
}

// Load invoice data
$invoiceGen = new InvoiceGenerator();
$ticket = $invoiceGen->getInvoiceData($ticket_id);

// In case there somehow ain't a ticket? Better safe than sorry I guess, but this is kinda redundant.
if (!$ticket) {
    die("No data found for this ticket.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?= htmlspecialchars($ticket['ticket_id']); ?> - Midnight Scream</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'EB Garamond', serif; background: #fff; color: #000; padding: 40px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.15); }
        .horror-font { font-family: 'Creepster', cursive; color: #ff3a3a; }
        .details { margin-bottom: 20px; }
        .print-btn { background: #1a1029; color: white; padding: 8px 16px; border-radius: 5px; cursor: pointer; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>

<div class="invoice-box">
    <div class="flex justify-between items-center mb-6">
        <h1 class="horror-font text-3xl">Midnight Scream Spectacle</h1>
        <button onclick="window.print()" class="print-btn">🖨 Print / Save PDF</button>
    </div>

    <p><strong>Invoice #:</strong> <?= htmlspecialchars($ticket['ticket_id']); ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($ticket['ticket_date'] . ' ' . $ticket['ticket_time']); ?></p>

    <hr class="my-4">

    <div class="details">
        <h2 class="text-xl font-bold mb-2">Customer</h2>
        <p><?= htmlspecialchars($ticket['first_name'] . ' ' . $ticket['last_name']); ?></p>
        <p><?= htmlspecialchars($ticket['email']); ?></p>
        <p><?= htmlspecialchars($ticket['phone_number']); ?></p>
    </div>

    <div class="details">
        <h2 class="text-xl font-bold mb-2">Booking Details</h2>
        <p><strong>Movie:</strong> <?= htmlspecialchars($ticket['movie_title']); ?></p>
        <p><strong>Hall:</strong> <?= htmlspecialchars($ticket['hall_name']); ?></p>
        <p><strong>Date:</strong> <?= date('F j, Y', strtotime($ticket['show_date'])); ?></p>
        <p><strong>Time:</strong> <?= substr($ticket['show_time'], 0, 5); ?></p>
        <p><strong>Seats:</strong> <?= htmlspecialchars($ticket['seats']); ?></p>
    </div>

    <hr class="my-4">

    <?php
    $seatCount = !empty($ticket['seats']) ? count(explode(',', $ticket['seats'])) : 0;
    $pricePerSeat = 12.50; // Ticket Price (As it is not stored in DB)
    $serviceFee = 2.50; // Service Fee - Deepsite decided to charge the customer extra. Whom am I to disagree with free money.
    $totalPrice = $seatCount * $pricePerSeat + $serviceFee;
    ?>
    <p><strong>Total:</strong> $<?= number_format($totalPrice, 2); ?></p>

    <p class="mt-6 text-sm text-gray-600">Thank you for choosing Midnight Scream!.
        No refunds after the haunting begins!</p>
</div>

</body>
</html>

