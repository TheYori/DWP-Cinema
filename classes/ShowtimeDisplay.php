<?php
class ShowtimeDisplay {
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    // Fetch the current date or next upcoming showings
    // max 2 movies
    public function getShowings($limit = 2)
    {
        $today = date("Y-m-d");

        // Try current date's showings
        $queryToday = "
        SELECT s.Showtime_id, m.movie_id, m.title, m.poster, m.movie_length, m.genre, m.director, m.debut_date, m.movie_desc, s.show_date, s.show_time, h.hall_name
        FROM Showtimes s
        JOIN Movies m ON s.movie_id = m.movie_id
        JOIN Halls h ON s.hall_id = h.hall_id
        WHERE s.show_date = ?
        ORDER BY s.show_time ASC";

        $stmt = $this->conn->prepare($queryToday);
        $stmt->execute([$today]);
        $showings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If none, try upcoming dates
        // only upcoming dates - No past date
        if (count($showings) === 0)
        {
            $stmt = $this->conn->prepare("SELECT MIN(show_date) AS next_date FROM Showtimes WHERE show_date > ?");
            $stmt->execute([$today]);
            $nextDate = $stmt->fetchColumn();

            if ($nextDate)
            {
                $stmt = $this->conn->prepare("
                SELECT s.Showtime_id, m.movie_id, m.title, m.poster, m.movie_length, m.genre, m.director, m.debut_date, m.movie_desc, s.show_date, s.show_time, h.hall_name
                FROM Showtimes s
                JOIN Movies m ON s.movie_id = m.movie_id
                JOIN Halls h ON s.hall_id = h.hall_id
                WHERE s.show_date = ?
                ORDER BY s.show_time ASC");

               $stmt->execute([$nextDate]);
               $showings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        // Group movies
        $group = [];
        foreach ($showings as $showing)
        {
            $movie_id = $showing['movie_id'];
            if (!isset($group[$movie_id]))
            {
                $group[$movie_id] = ['movie' => $showing, 'showtimes' => []];
            }
            $group[$movie_id]['showtimes'][] = ['Showtime_id' => $showing['Showtime_id'], 'hall' => $showing['hall_name'], 'time' => substr($showing['show_time'], 0, 5)];
        }

        // Set a limit
        return array_slice($group, 0, $limit);
    }
}
