<?php
require( "constants.php" );

class DatabaseCon {

    //Local Host variables
    //private $user = "Rguldborg"; // Only for localhost - Will likely needs to me changed before uploading to Simply.com
    //private $pass = "123456"; // Only for localhost - Will likely needs to me changed before uploading to Simply.com
    //private $host = "mysql:host=localhost;dbname=CinemaDB";

    // Simply variables
    //private $user = "matwijkiweducation_com";
    //private $pass = "dyRh4az3bmxnpG2krg5D";
    //private $host = "mysql:host=mysql55.unoeuro.com;dbname=matwijkiweducation_com_db_CinemaDB";

    public $databaseCon;
    public function __construct()
    {
        //$user = $this ->user;
        //$pass = $this ->pass;
        //$host = $this ->host;

        try
        {
            // Only for localhost - Will likely needs to me changed before uploading to Simply.com
            $this -> databaseCon = new PDO(DB_HOST, DB_USER, DB_PASS);
            // Only for Simply.com - The line below has been commented out- To use on Simply remove slashes and outcomment line above.
            //$this -> databaseCon = new PDO($host, $user, $pass);
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