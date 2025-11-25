<?php
class MovieDisplay
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    // All movies
    public function getAllMovies(): array
    {
        try
        {
            $sql = "
                SELECT m.movie_id, m.title,m.movie_desc, m.movie_length, m.debut_date,
                YEAR(m.debut_date) AS release_year, m.rating, m.director, m.genre, m.poster
                FROM Movies m
                ORDER BY m.debut_date DESC, m.title ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        }
        catch (PDOException $e)
        {
            error_log('MovieDisplay::getAllMovies error: '.$e->getMessage());
            return [];
        }
    }

    public function getMovieById($id)
    {
        $sql = "SELECT * FROM Movies WHERE movie_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}