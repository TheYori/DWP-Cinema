<?php
class BookingDisplay
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    public function getShowtime($showtime_id)
    {
        $query = "
        SELECT s.Showtime_id, s.show_date, s.show_time, h.hall_id, h.hall_name, m.title, m.poster, m.movie_length, m.genre, m.director
        FROM Showtimes s
        JOIN Movies m ON s.movie_id = m.movie_id
        JOIN Halls h ON s.hall_id = h.hall_id
        WHERE s.Showtime_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$showtime_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getSeats($hall_id, $showtime_id)
    {
        $query = "
        SELECT se.seat_id, se.seat_name,
        CASE WHEN wh.seat_id IS NOT NULL THEN 1 ELSE 0 END AS is_booked
        FROM Seats se
        LEFT JOIN Will_have wh ON se.seat_id = wh.seat_id
        LEFT JOIN Tickets t ON wh.ticket_id = t.ticket_id
        LEFT JOIN Showtimes s ON t.Showtime_id = s.Showtime_id
        WHERE se.hall_id = ?
        AND (s.Showtime_id = ? OR s.Showtime_id IS NULL)
        ORDER BY se.seat_id ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$hall_id, $showtime_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserTickets($user_id)
    {
        $sql = "
        SELECT 
            t.ticket_id,
            t.ticket_date,
            t.ticket_time,
            s.show_date,
            s.show_time,
            m.title,
            h.hall_name,
            GROUP_CONCAT(se.seat_name ORDER BY se.seat_name ASC) AS seats,
            (COUNT(se.seat_id) * 12.50 + 2.50) AS total_price
        FROM Tickets t
        JOIN Showtimes s ON t.Showtime_id = s.Showtime_id
        JOIN Movies m ON s.movie_id = m.movie_id
        JOIN Halls h ON s.hall_id = h.hall_id
        JOIN Will_have wh ON t.ticket_id = wh.ticket_id
        JOIN Seats se ON wh.seat_id = se.seat_id
        WHERE t.user_id = ?
        GROUP BY t.ticket_id
        ORDER BY t.ticket_date DESC, t.ticket_time DESC
    ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
