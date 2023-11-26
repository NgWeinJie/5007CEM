<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/destination.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare product object
$destination = new Destination($db);
  
// set ID property of record to read
$destination->DestinationID = isset($_GET['DestinationID']) ? $_GET['DestinationID'] : die();
  
// read the details of product to be edited
$destination->readOne();
  
if($destination->Location_Name!=null){
    // create array
    $destination_arr = array(
        "DestinationID" =>  $destination->DestinationID,
        "Location_Name" => $destination->Location_Name,
        "Description" => $destination->Description,
        "EntranceFeeAdultMalaysian" => $destination->EntranceFeeAdultMalaysian,
        "EntranceFeeAdultNonMalaysian" => $destination->EntranceFeeAdultNonMalaysian,
        "EntranceFeeChildrenMalaysian" => $destination->EntranceFeeChildrenMalaysian,
        "EntranceFeeChildrenNonMalaysian" => $destination->EntranceFeeChildrenNonMalaysian,
        "Open_Hours" => $destination->Open_Hours,
        "Google_Maps_Image_URL" => $destination->Google_Maps_Image_URL,
        "State" => $destination->State,
        "ImageDestination" => $destination->ImageDestination
    );
    
  
    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo json_encode($destination_arr);
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user product does not exist
    echo json_encode(array("message" => "Product does not exist."));
}
?>