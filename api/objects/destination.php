<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';

class Destination
{
    private $conn;
    private $table_name = "destination";

    // Destination properties
    public $DestinationID;
    public $Location_Name;
    public $Description;
    public $EntranceFeeAdultMalaysian;
    public $EntranceFeeAdultNonMalaysian;
    public $EntranceFeeChildrenMalaysian;
    public $EntranceFeeChildrenNonMalaysian;
    public $Open_Hours;
    public $Google_Maps_Image_URL;
    public $State;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Method to read destinations from the database
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}

?>
