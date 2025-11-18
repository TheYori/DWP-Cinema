<?php
class InvoiceGenerator
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    //Get all invoice data for a booking / ticket
    public function getInvoiceData($ticket_id)
    {
        $query = "
        SELECT t.ticket_id, t.ticket_date, t.ticket_time, t.user_id, t.Showtime_id, u.first_name, u.last_name, u.email, u.phone_number, u.street, u.postal_code, p.city, s.show_date, s.show_time, m.title AS movie_title, h.hall_name,
        GROUP_CONCAT(se.seat_name ORDER BY se.seat_name SEPARATOR ', ') AS seats
        FROM Tickets t
        JOIN Users u ON t.user_id = u.user_id
        JOIN PostalCodes p ON u.postal_code = p.postal_code
        JOIN Showtimes s ON t.Showtime_id = s.Showtime_id
        JOIN Movies m ON s.movie_id = m.movie_id
        JOIN Halls h ON s.hall_id = h.hall_id
        JOIN Will_have w ON t.ticket_id = w.ticket_id
        JOIN Seats se ON w.seat_id = se.seat_id
        WHERE t.ticket_id = :ticket_id
        GROUP BY t.ticket_id
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':ticket_id', (int)$ticket_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}