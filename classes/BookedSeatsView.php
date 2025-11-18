<?php

class BookedSeatsView
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    public function getAllBookedSeats()
    {
        $sql = "SELECT * FROM view_booked_seats ORDER BY show_date, show_time, seat_name";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
