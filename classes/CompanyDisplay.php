<?php
class CompanyDisplay {
    private $conn;

    public function __construct()
    {
        $db = new DatabaseCon();
        $this->conn = $db->databaseCon;
    }

    //Get Specific company data by using data_key
    //data_key is used instead of ID, so no errors occur when moving to Simply.com
    public function getCompanyInfo($data_key)
    {
        $query = "SELECT key_value FROM Company WHERE data_key = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$data_key]);
        return $stmt->fetchColumn(); //By using colum I only get the value
    }
}
