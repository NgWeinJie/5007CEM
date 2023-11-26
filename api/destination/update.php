<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// include database and object files
include_once '../config/database.php';
include_once '../objects/destination.php';
  
// get database connection
$database = new Database();
$db = $database->getConnection();
  
// prepare destination object
$destination = new Destination($db);
  
// get id of destination to be edited
$data = json_decode(file_get_contents("php://input"));
  
// set ID property of destination to be edited
$destination->DestinationID = $data->DestinationID;
  
// set destination property values
$destination->Location_Name = $data->Location_Name;
$destination->Description = $data->Description;
$destination->EntranceFeeAdultMalaysian = $data->EntranceFeeAdultMalaysian;
$destination->EntranceFeeAdultNonMalaysian = $data->EntranceFeeAdultNonMalaysian;
$destination->EntranceFeeChildrenMalaysian = $data->EntranceFeeChildrenMalaysian;
$destination->EntranceFeeChildrenNonMalaysian = $data->EntranceFeeChildrenNonMalaysian;
$destination->Open_Hours = $data->Open_Hours;
$destination->Google_Maps_Image_URL = $data->Google_Maps_Image_URL;
$destination->State = $data->State;
$destination->ImageDestination = $data->ImageDestination;
  
// update the destination
if($destination->update()){
    // set response code - 200 ok
    http_response_code(200);
    // tell the user
    echo json_encode(array("message" => "Destination was updated."));
}
// if unable to update the destination, tell the user
else{
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(array("message" => "Unable to update destination."));
}
?>
