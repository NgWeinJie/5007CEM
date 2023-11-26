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
    public $ImageDestination;

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

    // create destination
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
            Location_Name=:Location_Name, Description=:Description, EntranceFeeAdultMalaysian=:EntranceFeeAdultMalaysian, EntranceFeeAdultNonMalaysian=:EntranceFeeAdultNonMalaysian, EntranceFeeChildrenMalaysian=:EntranceFeeChildrenMalaysian, EntranceFeeChildrenNonMalaysian=:EntranceFeeChildrenNonMalaysian,
            Open_Hours=:Open_Hours, Google_Maps_Image_URL=:Google_Maps_Image_URL, State=:State, ImageDestination=:ImageDestination";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Location_Name=htmlspecialchars(strip_tags($this->Location_Name));
    $this->Description=htmlspecialchars(strip_tags($this->Description));
    $this->EntranceFeeAdultMalaysiann=htmlspecialchars(strip_tags($this->EntranceFeeAdultMalaysian));
    $this->EntranceFeeAdultNonMalaysian=htmlspecialchars(strip_tags($this->EntranceFeeAdultNonMalaysian));
    $this->EntranceFeeChildrenMalaysian=htmlspecialchars(strip_tags($this->EntranceFeeChildrenMalaysian));
    $this->EntranceFeeChildrenNonMalaysian=htmlspecialchars(strip_tags($this->EntranceFeeChildrenNonMalaysian));
    $this->Open_Hours=htmlspecialchars(strip_tags($this->Open_Hours));
    $this->Google_Maps_Image_URL=htmlspecialchars(strip_tags($this->Google_Maps_Image_URL));
    $this->State=htmlspecialchars(strip_tags($this->State));
    $this->ImageDestination=htmlspecialchars(strip_tags($this->ImageDestination));

    // bind values
    $stmt->bindParam(":Location_Name", $this->Location_Name);
    $stmt->bindParam(":Description", $this->Description);
    $stmt->bindParam(":EntranceFeeAdultMalaysian", $this->EntranceFeeAdultMalaysian);
    $stmt->bindParam(":EntranceFeeAdultNonMalaysian", $this->EntranceFeeAdultNonMalaysian);
    $stmt->bindParam(":EntranceFeeChildrenMalaysian", $this->EntranceFeeChildrenMalaysian);
    $stmt->bindParam(":EntranceFeeChildrenNonMalaysian", $this->EntranceFeeChildrenNonMalaysian);
    $stmt->bindParam(":Open_Hours", $this->Open_Hours);
    $stmt->bindParam(":Google_Maps_Image_URL", $this->Google_Maps_Image_URL);
    $stmt->bindParam(":State", $this->State);
    $stmt->bindParam(":ImageDestination", $this->ImageDestination);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
      
}
// used when filling up the update product form
function readOne() {
    // query to read single record
    $query = "SELECT
                DestinationID, Location_Name, Description, EntranceFeeAdultMalaysian, EntranceFeeAdultNonMalaysian,
                EntranceFeeChildrenMalaysian, EntranceFeeChildrenNonMalaysian, Open_Hours, Google_Maps_Image_URL, State, ImageDestination
            FROM
                " . $this->table_name . "
            WHERE
                DestinationID = ?
            LIMIT
                0,1";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // bind id of destination to be read
    $stmt->bindParam(1, $this->DestinationID);

    // execute query
    $stmt->execute();

    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // check if the key exists before assigning its value
    $this->DestinationID = $row['DestinationID'];
    $this->Location_Name = $row['Location_Name'];
    $this->Description = $row['Description'];
    $this->EntranceFeeAdultMalaysian = $row['EntranceFeeAdultMalaysian'];
    $this->EntranceFeeAdultNonMalaysian = $row['EntranceFeeAdultNonMalaysian'];
    $this->EntranceFeeChildrenMalaysian = $row['EntranceFeeChildrenMalaysian'];
    $this->EntranceFeeChildrenNonMalaysian = $row['EntranceFeeChildrenNonMalaysian'];
    $this->Open_Hours = $row['Open_Hours'];
    $this->Google_Maps_Image_URL = $row['Google_Maps_Image_URL'];
    $this->State = $row['State'];

    // check if ImageDestination key exists before assigning its value
    $this->ImageDestination = isset($row['ImageDestination']) ? $row['ImageDestination'] : null;
}

// update the destination
function update(){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                Location_Name = :Location_Name,
                Description = :Description,
                EntranceFeeAdultMalaysian = :EntranceFeeAdultMalaysian,
                EntranceFeeAdultNonMalaysian = :EntranceFeeAdultNonMalaysian,
                EntranceFeeChildrenMalaysian = :EntranceFeeChildrenMalaysian,
                EntranceFeeChildrenNonMalaysian = :EntranceFeeChildrenNonMalaysian,
                Open_Hours = :Open_Hours,
                Google_Maps_Image_URL = :Google_Maps_Image_URL,
                State = :State,
                ImageDestination = :ImageDestination
            WHERE
                DestinationID = :DestinationID";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->Location_Name=htmlspecialchars(strip_tags($this->Location_Name));
    $this->Description=htmlspecialchars(strip_tags($this->Description));
    $this->EntranceFeeAdultMalaysian=htmlspecialchars(strip_tags($this->EntranceFeeAdultMalaysian));
    $this->EntranceFeeAdultNonMalaysian=htmlspecialchars(strip_tags($this->EntranceFeeAdultNonMalaysian));
    $this->EntranceFeeChildrenMalaysian=htmlspecialchars(strip_tags($this->EntranceFeeChildrenMalaysian));
    $this->EntranceFeeChildrenNonMalaysian=htmlspecialchars(strip_tags($this->EntranceFeeChildrenNonMalaysian));
    $this->Open_Hours=htmlspecialchars(strip_tags($this->Open_Hours));
    $this->Google_Maps_Image_URL=htmlspecialchars(strip_tags($this->Google_Maps_Image_URL));
    $this->State=htmlspecialchars(strip_tags($this->State));
    $this->ImageDestination=htmlspecialchars(strip_tags($this->ImageDestination));
    $this->DestinationID=htmlspecialchars(strip_tags($this->DestinationID));
  
    // bind new values
    $stmt->bindParam(':Location_Name', $this->Location_Name);
    $stmt->bindParam(':Description', $this->Description);
    $stmt->bindParam(':EntranceFeeAdultMalaysian', $this->EntranceFeeAdultMalaysian);
    $stmt->bindParam(':EntranceFeeAdultNonMalaysian', $this->EntranceFeeAdultNonMalaysian);
    $stmt->bindParam(':EntranceFeeChildrenMalaysian', $this->EntranceFeeChildrenMalaysian);
    $stmt->bindParam(':EntranceFeeChildrenNonMalaysian', $this->EntranceFeeChildrenNonMalaysian);
    $stmt->bindParam(':Open_Hours', $this->Open_Hours);
    $stmt->bindParam(':Google_Maps_Image_URL', $this->Google_Maps_Image_URL);
    $stmt->bindParam(':State', $this->State);
    $stmt->bindParam(':ImageDestination', $this->ImageDestination);
    $stmt->bindParam(':DestinationID', $this->DestinationID);
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}

// delete the destination
function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE DestinationID = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->DestinationID = htmlspecialchars(strip_tags($this->DestinationID));
  
    // bind ID of record to delete
    $stmt->bindParam(1, $this->DestinationID);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}

// search destinations
function search($keywords)
{
    // select all query
    $query = "SELECT
                DestinationID, Location_Name, Description, EntranceFeeAdultMalaysian, EntranceFeeAdultNonMalaysian,
                EntranceFeeChildrenMalaysian, EntranceFeeChildrenNonMalaysian, Open_Hours, Google_Maps_Image_URL, State, ImageDestination
            FROM
                " . $this->table_name . "
            WHERE
                Location_Name LIKE ? OR Description LIKE ? OR State LIKE ?
            ORDER BY
                DestinationID DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // sanitize
    $keywords = htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);

    // execute query
    $stmt->execute();

    return $stmt;
}

// read destinations with pagination
public function readPaging($from_record_num, $records_per_page){

    // select query
    $query = "SELECT
                DestinationID, Location_Name, Description, EntranceFeeAdultMalaysian, EntranceFeeAdultNonMalaysian,
                EntranceFeeChildrenMalaysian, EntranceFeeChildrenNonMalaysian, Open_Hours, Google_Maps_Image_URL, State, ImageDestination
            FROM
                " . $this->table_name . "
            ORDER BY DestinationID DESC
            LIMIT ?, ?";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

    // execute query
    $stmt->execute();

    // return values from database
    return $stmt;
}

// used for paging destinations
public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
  
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    return $row['total_rows'];
}



}


?>
