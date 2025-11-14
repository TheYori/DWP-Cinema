<?php
class MovieDisplay
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    // All movies (you can add WHEREs later if you need filtering)
    public function getAllMovies(): array
    {
        try {
            $sql = "
                SELECT 
                    m.movie_id,
                    m.title,
                    m.movie_desc,
                    m.movie_length,
                    m.debut_date,
                    YEAR(m.debut_date) AS release_year,
                    m.rating,
                    m.director,
                    m.genre,
                    m.poster
                FROM Movies m
                ORDER BY m.debut_date DESC, m.title ASC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log('MovieDisplay::getAllMovies error: '.$e->getMessage());
            return [];
        }
    }

    public function getMovieById(int $id): ?array
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    movie_id, title, movie_desc, movie_length,
                    debut_date, YEAR(debut_date) AS release_year,
                    rating, director, genre, poster
                FROM Movies
                WHERE movie_id = ?
                LIMIT 1
            ");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            error_log('MovieDisplay::getMovieById error: '.$e->getMessage());
            return null;
        }
    }
}