<?php
require( "constants.php" );

class DatabaseCon {

    public $databaseCon;
    public function __construct()
    {
        try
        {
            $this -> databaseCon = new PDO(DB_HOST, DB_USER, DB_PASS);
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