<?php
// Start or resume a session
session_start();

// Include your database connection configuration
include("config.php");

// Check if the request is a POST and user is authenticated
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];
    $destinationID = isset($_POST['destinationID']) ? $_POST['destinationID'] : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;

    // Validate destinationID and action
    if ($destinationID !== null && ($action === "like" || $action === "dislike")) {
        // Update database based on the action (like/dislike)
        handleLikeDislikeActions($conn, $userID, $destinationID, $action);

        // Return updated counts
        $updatedCounts = getLikesDislikesCount($conn, $destinationID);
        echo json_encode(['totalLikes' => $updatedCounts['totalLikes'], 'totalDislikes' => $updatedCounts['totalDislikes']]);
        exit; // Stop further execution
    } else {
        // Handle invalid input or action
        echo json_encode(['error' => 'Invalid input or action.']);
    }
} else {
    // Handle unauthenticated user
    echo json_encode(['error' => 'User not authenticated.']);
}

// Function to handle like and dislike actions
function handleLikeDislikeActions($conn, $userID, $destinationID, $action)
{
    if ($action === "like") {
        $queryUpdateLikes = $conn->prepare("INSERT INTO `destinationlikesdislike` (UserID, DestinationID, NumLikes) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE NumLikes = COALESCE(NumLikes, 0) + 1");
        $queryUpdateLikes->bind_param("ii", $userID, $destinationID);
        $queryUpdateLikes->execute();
    } elseif ($action === "dislike") {
        $queryUpdateDislikes = $conn->prepare("INSERT INTO `destinationlikesdislike` (UserID, DestinationID, NumDislikes) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE NumDislikes = COALESCE(NumDislikes, 0) + 1");
        $queryUpdateDislikes->bind_param("ii", $userID, $destinationID);
        $queryUpdateDislikes->execute();
    }
}

// Function to get like and dislike counts for a destination
function getLikesDislikesCount($conn, $destinationID)
{
    $queryLikesDislikesCount = "SELECT COALESCE(SUM(NumLikes), 0) AS totalLikes, COALESCE(SUM(NumDislikes), 0) AS totalDislikes FROM `destinationlikesdislike` WHERE DestinationID = ?";
    $stmt = $conn->prepare($queryLikesDislikesCount);
    $stmt->bind_param("i", $destinationID);
    $stmt->execute();
    $result = $stmt->get_result();
    $counts = $result->fetch_assoc();
    $stmt->close();

    return $counts;
}
?>
