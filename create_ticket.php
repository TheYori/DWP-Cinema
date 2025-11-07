<?php
spl_autoload_register(function ($class) {
    include "classes/" . $class . ".php";
});

header('Content-Type: application/json');

$session = new UserSessionHandler();
$user_id = $session->get_user_id();

if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['showtime_id']) || empty($data['seat_ids'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

$showtime_id = (int)$data['showtime_id'];
$seat_ids = $data['seat_ids'];

try {
    $db = new DatabaseCon();
    $conn = $db->databaseCon;
    $conn->beginTransaction();

    // Create ticket
    $now = new DateTime();
    $ticket_date = $now->format('Y-m-d');
    $ticket_time = $now->format('H:i:s');

    $stmt = $conn->prepare("INSERT INTO Tickets (ticket_date, ticket_time, user_id, Showtime_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$ticket_date, $ticket_time, $user_id, $showtime_id]);
    $ticket_id = $conn->lastInsertId();

    // Assign seats to ticket
    $linkStmt = $conn->prepare("INSERT INTO Will_have (ticket_id, seat_id) VALUES (?, ?)");
    foreach ($seat_ids as $seat_id) {
        $linkStmt->execute([$ticket_id, (int)$seat_id]);
    }

    $conn->commit();
    echo json_encode(['status' => 'success', 'ticket_id' => $ticket_id]);

} catch (PDOException $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

