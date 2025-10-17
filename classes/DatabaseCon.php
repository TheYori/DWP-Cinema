<?php
class DatabaseCon {
    private $user = "Rguldborg"; // Only for localhost - Will likely needs to me changed before uploading to Simply.com
    private $pass = "123456"; // Only for localhost - Will likely needs to me changed before uploading to Simply.com
    public $databaseCon;
    public function __construct()
    {
        $user = $this ->user;
        $pass = $this ->pass;

        try
        {
            // Only for localhost - Will likely needs to me changed before uploading to Simply.com
            $this -> databaseCon = new PDO("mysql:host=localhost;dbname=CinemaDB", $user, $pass);
            return $this -> databaseCon;
        }
        catch (PDOException $error)
        {
            echo "Encountered an error: ". $error->getMessage() . "<br/>";
            die();
        }
    }
    public function DatabaseClose()
    {
        $this -> databaseCon = null;
    }
}