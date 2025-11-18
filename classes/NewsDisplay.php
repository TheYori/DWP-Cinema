<?php
class NewsDisplay
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    //Get latest news where it isn't newer than current date
    //Limited to only 3
    public function getRecentNews($limit = 3)
    {
        $today = date("Y-m-d");
        $query = "
        SELECT news_id, release_date, title, content, banner_img 
        FROM News
        WHERE release_date <= ?
        ORDER BY release_date DESC
        LIMIT ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(1, $today);
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all published news (release_date <= today)
    public function getPublishedNews()
    {
        $sql = "SELECT * 
                FROM News 
                WHERE release_date <= CURDATE()
                ORDER BY release_date DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get one article by id (for article.php)
    public function getNewsById($id)
    {
        $sql = "SELECT * FROM News WHERE news_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}