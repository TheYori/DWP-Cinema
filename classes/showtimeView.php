<?php

class ShowtimeView
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    public function getAllShowings()
    {
        $sql = "SELECT * FROM view_showtimes_with_movie_info ORDER BY show_date, show_time";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
