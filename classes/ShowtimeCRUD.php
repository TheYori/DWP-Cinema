<?php
class ShowtimeCRUD
{
    private $conn;

    public function __construct()
    {
        $database = new DatabaseCon();
        $this->conn = $database->databaseCon;
    }

    //cRud - Read (SQL select) all data in the Showtime Table (With joined movies + halls)
    public function getAllShowtimes()
    {
        $query = $this->conn->prepare("
        SELECT s.showtime_id, s.show_date, s.show_time, h.hall_name, m.title AS movie_title
        FROM `Showtimes` s
        INNER JOIN Halls h ON s.hall_id = h.hall_id
        INNER JOIN Movies m ON s.movie_id = m.movie_id
        ORDER BY s.show_date ASC
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // cRud - Read a single showing by ID
    public function getShowtimeById($id)
    {
        $query = $this->conn->prepare("SELECT * FROM `Showtimes` WHERE showtime_id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Crud - Create new entry into Showtimes Table
    public function create($show_date, $show_time, $hall_id, $movie_id)
    {
        $query = $this->conn->prepare("INSERT INTO `Showtimes` (show_date, show_time, hall_id, movie_id) VALUES (:show_date, :show_time, :hall_id, :movie_id)");
        $san_show_date = htmlspecialchars($show_date);
        $san_show_time = htmlspecialchars($show_time);
        $san_hall_id = htmlspecialchars($hall_id);
        $san_movie_id = htmlspecialchars($movie_id);
        $query->bindParam(':show_date', $san_show_date);
        $query->bindParam(':show_time', $san_show_time);
        $query->bindParam(':hall_id', $san_hall_id, PDO::PARAM_INT);
        $query->bindParam(':movie_id', $san_movie_id, PDO::PARAM_INT);
        return $query->execute();
    }

    // crUd - Updates selected data in Showtimes Table
    public function update($id, $show_date, $show_time, $hall_id, $movie_id)
    {
        $query = $this->conn->prepare("UPDATE `Showtimes` SET show_date = :show_date, show_time = :show_time, hall_id = :hall_id, movie_id = :movie_id WHERE showtime_id = :id");
        $san_id = htmlspecialchars($id);
        $san_show_date = htmlspecialchars($show_date);
        $san_show_time = htmlspecialchars($show_time);
        $san_hall_id = htmlspecialchars($hall_id);
        $san_movie_id = htmlspecialchars($movie_id);
        $query->bindParam(':id', $san_id, PDO::PARAM_INT);
        $query->bindParam(':show_date', $san_show_date);
        $query->bindParam(':show_time', $san_show_time);
        $query->bindParam(':hall_id', $san_hall_id, PDO::PARAM_INT);
        $query->bindParam(':movie_id', $san_movie_id, PDO::PARAM_INT);
        return $query->execute();
    }

    //cruD - Delete the selected data via ID in the Showtimes Table
    public function delete($id)
    {
        $query = $this->conn->prepare("DELETE FROM `Showtimes` WHERE showtime_id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }


    // Get movies for dropdown
    public function getAllMovies()
    {
        $query = $this->conn->prepare("SELECT movie_id, title FROM Movies ORDER BY title ASC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    //Get halls for dropdown
    public function getAllHalls()
    {
        $query = $this->conn->prepare("SELECT hall_id, hall_name FROM Halls ORDER BY hall_name ASC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
