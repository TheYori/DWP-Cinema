<?php
class MovieCRUD
{
    private $conn;
    private $uploadPath = "../images/movies/";

    public function __construct()
    {
        $database = new DatabaseCon();
        $this->conn = $database->databaseCon;
    }

    //cRud - Read (SQL select) all data in the Movies Table
    public function getAllMovies()
    {
        $query = $this->conn->prepare("SELECT * FROM Movies ORDER BY debut_date ASC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMovieById($id)
    {
        $query = $this->conn->prepare("SELECT * FROM Movies WHERE movie_id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Crud - Create new entry into Movies Table
    public function create($title, $movie_length, $debut_date, $rating, $director, $genre, $movie_desc, $poster)
    {
        $poster = $this->handleImageUpload($poster);
        if ($poster === false) {
            return false;
        }

        $query = $this->conn->prepare("INSERT INTO Movies (title, movie_length, debut_date, rating, director, genre, movie_desc, poster) VALUES (:title, :movie_length, :debut_date, :rating, :director, :genre, :movie_desc, :poster)");

        $san_title = htmlspecialchars($title);
        $san_movie_length = htmlspecialchars($movie_length);
        $san_debut_date = htmlspecialchars($debut_date);
        $san_rating = htmlspecialchars($rating);
        $san_director = htmlspecialchars($director);
        $san_genre = htmlspecialchars($genre);
        $san_movie_desc = htmlspecialchars($movie_desc);
        $query->bindParam(":title", $san_title);
        $query->bindParam(":movie_length", $san_movie_length);
        $query->bindParam(":debut_date", $san_debut_date);
        $query->bindParam(":rating", $san_rating);
        $query->bindParam(":director", $san_director);
        $query->bindParam(":genre", $san_genre);
        $query->bindParam(":movie_desc", $san_movie_desc);
        $query->bindParam(":poster", $poster);

        return $query->execute();
    }


    // crUd - Updates selected data in Movies Table
    public function update($id, $title, $movie_length, $debut_date, $rating, $director, $genre, $movie_desc, $poster = null)
    {
        if ($poster && $poster["error"] === UPLOAD_ERR_OK)
        {
            $poster = $this->handleImageUpload($poster);
            if ($poster === false)
            {
                return false;
            }

            $query = $this->conn->prepare("UPDATE Movies SET title = :title, movie_length = :movie_length, debut_date = :debut_date, rating = :rating, director = :director, genre = :genre, movie_desc = :movie_desc, poster = :poster WHERE movie_id = :id");
            $query->bindParam(":poster", $poster);
        }
        else
        {
            $query = $this->conn->prepare("UPDATE Movies SET title = :title, movie_length = :movie_length, debut_date = :debut_date, rating = :rating, director = :director, genre = :genre, movie_desc = :movie_desc WHERE movie_id = :id");
        }
        $san_title = htmlspecialchars($title);
        $san_movie_length = htmlspecialchars($movie_length);
        $san_debut_date = htmlspecialchars($debut_date);
        $san_rating = htmlspecialchars($rating);
        $san_director = htmlspecialchars($director);
        $san_genre = htmlspecialchars($genre);
        $san_movie_desc = htmlspecialchars($movie_desc);
        $san_id= htmlspecialchars($id);
        $query->bindParam(":title", $san_title);
        $query->bindParam(":movie_length", $san_movie_length);
        $query->bindParam(":debut_date", $san_debut_date);
        $query->bindParam(":rating", $san_rating);
        $query->bindParam(":director", $san_director);
        $query->bindParam(":genre", $san_genre);
        $query->bindParam(":movie_desc", $san_movie_desc);
        $query->bindParam(":id", $san_id);
        return $query->execute();
    }

    //cruD - Delete the selected data via ID in the Movies Table
    public function delete($id)
    {
        $query = $this->conn->prepare("DELETE FROM Movies WHERE movie_id = :id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }

    private function handleImageUpload($poster)
    {
        if ($poster["error"] != UPLOAD_ERR_OK)
        {
            return false;
        }

        if (!is_dir($this->uploadPath))
        {
            mkdir($this->uploadPath, 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($poster["tmp_name"]);

        if (!in_array($fileType, $allowedTypes))
        {
            return false;
        }

        // Generate safe filename
        $fileName = uniqid() . "-" . basename($poster['name']);
        $targetPath = $this->uploadPath . $fileName;

        if (move_uploaded_file($poster["tmp_name"], $targetPath))
        {
            return $fileName; //Only the name of the file is saved to the DB
        }
        return false;
    }
}