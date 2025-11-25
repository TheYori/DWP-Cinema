<?php
require_once "DatabaseCon.php"; // make sure the path is correct

class CompanyCRUD
{
    private $db;

    public function __construct()
    {
        // Create a new database connection using your DatabaseCon class
        $database = new DatabaseCon();
        $this->db = $database->databaseCon;
    }

    // cRud - Read all data in Company Table
    public function getAll()
    {
        $query = $this->db->prepare("SELECT * FROM Company");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crud - Create new entry into Company Table
    public function create($data_key, $key_value)
    {
        $query = $this->db->prepare("INSERT INTO Company (data_key, key_value) VALUES (:data_key, :key_value)");
        $san_data_key = htmlspecialchars($data_key);
        $san_key_value = htmlspecialchars($key_value);
        $query->bindParam(':data_key', $san_data_key);
        $query->bindParam(':key_value', $san_key_value);
        return $query->execute();
    }

    // crUd - Finds the data I want to update via Company ID (key_id)
    public function getById($id)
    {
        $query = $this->db->prepare("SELECT * FROM Company WHERE key_id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // crUd - Updates selected data in Company Table
    public function update($id, $data_key, $key_value)
    {
        try
        {
            $sql = "UPDATE Company SET data_key = :data_key, key_value = :key_value WHERE key_id = :id";

            $stmt = $this->db->prepare($sql);

            // Bind parameters safely
            $stmt->bindParam(':data_key', $data_key, PDO::PARAM_STR);
            $stmt->bindParam(':key_value', $key_value, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("CompanyCRUD update() failed: " . $e->getMessage());
            return false;
        }
    }

    // cruD - Delete the selected data via ID in Company Table
    public function delete($id)
    {
        $query = $this->db->prepare("DELETE FROM Company WHERE key_id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        return $query->execute();
    }
}
