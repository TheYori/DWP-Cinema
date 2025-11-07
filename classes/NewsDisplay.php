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
}