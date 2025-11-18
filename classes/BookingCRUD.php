<?php
require_once "DatabaseCon.php";

class BookingCRUD
{
    private $db;

    public function __construct()
    {
        $database = new DatabaseCon();
        $this->db = $database->databaseCon;
    }


    // Get all bookings with user, movie, showtime, hall, and seat info.
    public function getAllBookings()
    {
        $query = $this->db->prepare("
            SELECT t.ticket_id, t.ticket_date, t.ticket_time, u.first_name, u.last_name, m.title AS movie_title, s.show_date, s.show_time, h.hall_name,
                GROUP_CONCAT(se.seat_name ORDER BY se.seat_name SEPARATOR ', ') AS seats
            FROM Tickets t
            INNER JOIN Users u ON t.user_id = u.user_id
            INNER JOIN Showtimes s ON t.Showtime_id = s.Showtime_id
            INNER JOIN Movies m ON s.movie_id = m.movie_id
            INNER JOIN Halls h ON s.hall_id = h.hall_id
            INNER JOIN Will_have w ON t.ticket_id = w.ticket_id
            INNER JOIN Seats se ON w.seat_id = se.seat_id
            GROUP BY t.ticket_id
            ORDER BY t.ticket_date DESC, t.ticket_time DESC
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}

