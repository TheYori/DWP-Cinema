<?php
class NewsCRUD
{
    private $conn;
    private $uploadPath = "../images/news/";

    public function __construct()
    {
        $database = new DatabaseCon();
        $this->conn = $database->databaseCon;
    }

    // cRud - Read (or select) all data in News Table
    public function getAllNews()
    {
        $query = $this->conn->prepare("SELECT * FROM News ORDER BY release_date DESC");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = $this->conn->prepare("SELECT * FROM News WHERE news_id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Crud - Create new entry into News Table
    public function create($title, $content, $release_date, $imageFile)
    {
        $banner_img = $this->handleImageUpload($imageFile);
        if ($banner_img === false)
        {
            return false; // image upload failed
        }

        $query = $this->conn->prepare("INSERT INTO News (title, content, release_date, banner_img) VALUES (:title, :content, :release_date, :banner_img)");
        $query->bindParam(':title', $title);
        $query->bindParam(':content', $content);
        $query->bindParam(':release_date', $release_date);
        $query->bindParam(':banner_img', $banner_img);
        return $query->execute();
    }

    // crUd - Updates selected data in News Table
    public function update($id, $title, $content, $release_date, $imageFile = null)
    {
        if ($imageFile && $imageFile['error'] === UPLOAD_ERR_OK)
        {
            $banner_img = $this->handleImageUpload($imageFile);
            if ($banner_img === false)
            {
                return false;
            }

            $query = $this->conn->prepare("UPDATE News SET title = :title, content = :content, release_date = :release_date, banner_img = :banner_img WHERE news_id = :id");
            $query->bindParam(':banner_img', $banner_img);
        }
        else
        {
            $query = $this->conn->prepare("UPDATE News SET title = :title, content = :content, release_date = :release_date WHERE news_id = :id");
        }

        $query->bindParam(':title', $title);
        $query->bindParam(':content', $content);
        $query->bindParam(':release_date', $release_date);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }

    // cruD - Delete the selected data via ID in News Table
    public function delete($id)
    {
        $query = $this->conn->prepare("DELETE FROM News WHERE news_id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }

    private function handleImageUpload($imageFile)
    {
        if ($imageFile['error'] !== UPLOAD_ERR_OK)
        {
            return false;
        }

        // Ensure folder exists
        if (!is_dir($this->uploadPath))
        {
            mkdir($this->uploadPath, 0777, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $fileType = mime_content_type($imageFile['tmp_name']);

        if (!in_array($fileType, $allowedTypes))
        {
            return false;
        }

        // Generate safe filename
        $fileName = uniqid() . "-" . basename($imageFile['name']);
        $targetPath = $this->uploadPath . $fileName;

        if (move_uploaded_file($imageFile['tmp_name'], $targetPath))
        {
            return $fileName; // only save the filename in DB
        }

        return false;
    }
}