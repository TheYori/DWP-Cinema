<?php
require_once "DatabaseCon.php";

class UserProfile
{
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    //Get user data by user id
    public function getUserById($user_id)
    {
        $query = $this->conn->prepare("
        SELECT u.first_name, u.last_name, u.email, u.phone_number, u.birth_date, u.street, p.city, u.postal_code, u.user_id
        From Users u
        INNER JOIN PostalCodes p ON u.postal_code = p.postal_code
        WHERE u.user_id = :user_id");

        $query->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
