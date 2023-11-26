<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/destination.php'; 

// utilities
$utilities = new Utilities();

// instantiate database and destination object
$database = new Database();
$db = $database->getConnection();

// initialize object
$destination = new Destination($db);

// query destinations
$stmt = $destination->readPaging($from_record_num, $records_per_page); 
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    // destinations array
    $destinations_arr = array();
    $destinations_arr["records"] = array();
    $destinations_arr["paging"] = array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['Location_Name'] to
        // just $Location_Name only
        extract($row);

        $destination_item = array(
            "DestinationID" => $DestinationID,
            "Location_Name" => $Location_Name,
            "Description" => html_entity_decode($Description),
            "EntranceFeeAdultMalaysian" => $EntranceFeeAdultMalaysian,
            "EntranceFeeAdultNonMalaysian" => $EntranceFeeAdultNonMalaysian,
            "EntranceFeeChildrenMalaysian" => $EntranceFeeChildrenMalaysian,
            "EntranceFeeChildrenNonMalaysian" => $EntranceFeeChildrenNonMalaysian,
            "Open_Hours" => $Open_Hours,
            "Google_Maps_Image_URL" => $Google_Maps_Image_URL,
            "State" => $State,
            "ImageDestination" => $ImageDestination
        );

        array_push($destinations_arr["records"], $destination_item);
    }

    // include paging
    $total_rows = $destination->count();
    $page_url = "{$home_url}destination/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $destinations_arr["paging"] = $paging;

    // set response code - 200 OK
    http_response_code(200);

    // make it json format
    echo json_encode($destinations_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user destinations do not exist
    echo json_encode(
        array("message" => "No destinations found.")
    );
}
?>
