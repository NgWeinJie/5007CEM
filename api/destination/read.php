<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/destination.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$destination = new Destination($db);

// read destination will be here
// query destination
$stmt = $destination->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {
    // destination array
    $destination_arr = array();
    $destination_arr["records"] = array();

    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        extract($row);

        $destination_item = array(
            "destinationID" => $DestinationID,
            "locationName" => $Location_Name,
            "description" => html_entity_decode($Description),
            "entranceFeeAdultMalaysian" => $EntranceFeeAdultMalaysian,
            "entranceFeeAdultNonMalaysian" => $EntranceFeeAdultNonMalaysian,
            "entranceFeeChildrenMalaysian" => $EntranceFeeChildrenMalaysian,
            "entranceFeeChildrenNonMalaysian" => $EntranceFeeChildrenNonMalaysian,
            "openHours" => $Open_Hours,
            "googleMapsImageURL" => $Google_Maps_Image_URL,
            "state" => $State,
            "imageDestination" => $ImageDestination

        );

        array_push($destination_arr["records"], $destination_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data in json format
    echo json_encode($destination_arr);
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(array("message" => "No destinations found."));
}

?>
