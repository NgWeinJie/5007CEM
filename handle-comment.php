<?php
// Include your database connection configuration
include("config.php");

// Assuming you have a session started for user authentication
session_start();

// Check if the request is a POST and user is authenticated
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];
    $destinationID = isset($_POST['destinationID']) ? $_POST['destinationID'] : null;
    $commentText = isset($_POST['commentText']) ? $_POST['commentText'] : null;

    // Validate destinationID and commentText
    if ($destinationID !== null && $commentText !== null) {
        // Add new comment to the database
        handleComment($conn, $userID, $destinationID, $commentText);

        // Return updated comments for the specific destination
        $updatedComments = getDestinationComments($conn, $destinationID);
        echo json_encode(['success' => true, 'comments' => $updatedComments]);
        exit; // Stop further execution
    } else {
        // Handle invalid input
        echo json_encode(['error' => 'Invalid input.']);
    }
}

function handleComment($conn, $userID, $destinationID, $commentText)
{
    // Add your logic to insert the comment into the database
    // Use prepared statements to prevent SQL injection
    $query = "INSERT INTO `comment` (UserID, DestinationID, Comment_Text, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $userID, $destinationID, $commentText);
    $stmt->execute();
    $stmt->close();
}

function getDestinationComments($conn, $destinationID)
{
    // Use a JOIN statement to fetch comments along with user information
    $query = "SELECT c.Comment_Text, c.created_at, u.Username
              FROM `comment` c
              INNER JOIN `user` u ON c.UserID = u.UserID
              WHERE c.DestinationID = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $destinationID);
    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];

    while ($row = $result->fetch_assoc()) {
        $comments[] = [
            'CommentText' => $row['Comment_Text'],
            'Username' => $row['Username'],
            'created_at' => $row['created_at'],
        ];
    }

    $stmt->close();

    return $comments;
}
?>
