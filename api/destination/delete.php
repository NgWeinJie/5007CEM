<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/database.php';
include_once '../objects/destination.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare destination object
$destination = new Destination($db);

// get destination id from the request
$data = json_decode(file_get_contents("php://input"));

// check if DestinationID is set in the request data
if (!empty($data->DestinationID)) {
    // set destination id to be deleted
    $destination->DestinationID = $data->DestinationID;

    // delete the destination
    if ($destination->delete()) {
        // set response code - 200 ok
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "Destination was deleted."));
    } else {
        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to delete destination."));
    }
} else {
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Bad request. DestinationID is missing."));
}
?>
