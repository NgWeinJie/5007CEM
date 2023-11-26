<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once '../config/database.php';
  
// instantiate product object
include_once '../objects/destination.php';
  
$database = new Database();
$db = $database->getConnection();
  
$destination = new destination($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->Location_Name) &&
    !empty($data->Description) &&
    !empty($data->EntranceFeeAdultMalaysian) &&
    !empty($data->EntranceFeeAdultNonMalaysian) &&
    !empty($data->EntranceFeeChildrenMalaysian) &&
    !empty($data->EntranceFeeChildrenNonMalaysian) &&
    !empty($data->Open_Hours) &&
    !empty($data->Google_Maps_Image_URL) &&
    !empty($data->State) &&
    !empty($data->ImageDestination)
){
  
    // set product property values
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
    
  
    // create the product
    if($destination->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "Destination was created."));
    }
  
    // if unable to create the product, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create destination."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to Destination. Data is incomplete."));
}
?>