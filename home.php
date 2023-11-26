<?php
// Assuming you have a session started for user authentication
session_start();

// Include your database connection configuration
include("config.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Check if the request is a POST and user is authenticated
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];

    // Check if action and destinationID are set in the $_POST array
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $destinationID = isset($_POST['destinationID']) ? $_POST['destinationID'] : null;
    $commentText = isset($_POST['commentText']) ? $_POST['commentText'] : null;

    // Only proceed if action and destinationID are not null
    if ($action !== null && $destinationID !== null) {
        if ($action === "comment" && $commentText !== null) {
            // Handle comment action
            handleComment($conn, $userID, $destinationID, $commentText);
        } else {
            // Handle like and dislike actions
            handleLikeDislikeActions($conn, $userID, $destinationID, $action);
        }
    }
}

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

    // You can also fetch and return the updated like and dislike counts if needed
    $updatedCounts = getLikesDislikesCount($conn, $destinationID);
    echo json_encode($updatedCounts);

    // Use exit to stop further execution
    exit;
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

// Function to handle comments
function handleComment($conn, $userID, $destinationID, $commentText)
{
    // Retrieve the username from the session
    $queryUsername = $conn->prepare("SELECT Username FROM `user` WHERE UserID = ?");
    $queryUsername->bind_param("i", $userID);
    $queryUsername->execute();
    $resultUsername = $queryUsername->get_result();
    $rowUsername = $resultUsername->fetch_assoc();
    $username = $rowUsername['Username'];
    $queryUsername->close();

    // Insert the comment along with the username
    $queryAddComment = $conn->prepare("INSERT INTO `comment` (UserID, DestinationID, CommentText, Username) VALUES (?, ?, ?, ?)");
    $queryAddComment->bind_param("iiss", $userID, $destinationID, $commentText, $username);
    $queryAddComment->execute();

    // Optionally, you can return the updated comments for the destination
    $updatedComments = getDestinationComments($conn, $destinationID);
    echo json_encode($updatedComments);
}

// Function to get comments for a destination
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


// Fetch destinations from the database
$queryDestinations = "SELECT * FROM `destination` ORDER BY State";
$resultDestinations = mysqli_query($conn, $queryDestinations);

// Fetch destination likes and dislikes from the database
$queryLikesDislikes = "SELECT COALESCE(SUM(NumLikes), 0) AS totalLikes, COALESCE(SUM(NumDislikes), 0) AS totalDislikes FROM `destinationlikesdislike`";
$resultLikesDislikes = mysqli_query($conn, $queryLikesDislikes);

// Fetch comments from the database
$queryComments = "SELECT * FROM `comment`";
$resultComments = mysqli_query($conn, $queryComments);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sabah & Sarawak Travel Recommendation and Blog</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Your custom CSS files -->
    <link rel="stylesheet" type="text/css" href="Home.css">
    <link rel="stylesheet" type="text/css" href="styles.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    
        <!-- Bootstrap JavaScript dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
<style>
    .search-bar {
        margin-top: 20px;
        max-width: 500px; /* Adjust the maximum width */
        margin-left: auto;
        margin-right: auto;
    }

    .search-bar form {
        display: flex;
    }

    .search-bar input {
        padding: 15px; /* Adjust the padding */
        flex: 1;
        border: 1px solid #ccc;
        border-radius: 5px 0 0 5px;
        height: 60px; /* Adjust the height */
    }

    .search-bar button {
        padding: 15px; /* Adjust the padding */
        background-color: #007bff;
        color: #fff;
        border: 1px solid #007bff;
        border-radius: 0 5px 5px 0;
        cursor: pointer;
        height: 60px; /* Adjust the height */
    }

    .search-bar button:hover {
        background-color: #0056b3;
        border: 1px solid #0056b3;
    }
</style>


</head>
<body>
<header class="header">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- Menu icon container -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myTopnav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav menu containing the list of links -->
            <div class="collapse navbar-collapse" id="myTopnav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="destinationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Destinations</a>
                        <div class="dropdown-menu" aria-labelledby="destinationsDropdown">
                                <a class="dropdown-item" href="#sabah" id="sabah-link">Sabah</a>
                                <a class="dropdown-item" href="#sarawak" id="sarawak-link">Sarawak</a>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="currency_converter.php">Currency Conversion</a></li>
                    <li class="nav-item"><a class="nav-link" href="AboutUs.html">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact Us</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="account_details.php">
                            <i class="fas fa-user account-icon"></i> Account Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="fas fa-sign-out-alt logout-icon"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="slideshow-container">
            <div class="slide">
                <a href="#Mantanani Islands"> <img src="image/Mantanani Islands.jpg" alt="Slide 1"></a>
            </div>
            <div class="slide">
                <a href="#Kuching Waterfront"><img src="image/Kuching Waterfront.jpeg" alt="Slide 2"></a>
            </div>
            <div class="slide">
                <a href="#Mount-Kinabalu"><img src="image/Mount-Kinabalu.png" alt="Slide 3"></a>
            </div>
            <div class="slide">
                <a href="#Talang-Satang National Park"><img src="image/Talang-Satang National Park.jpg" alt="Slide 3"></a>
            </div>
            <div class="slide">
                <a href="#Semenggoh Wildlife Centre"><img src="image/Semenggoh Wildlife Centre.jpg" alt="Slide 3"></a>
            </div>
        </div>
    <center><a href="Home.html"><img src="image/logo.png" alt="travel Logo" class="logo"></a></center>
    
<div class="search-bar">
    <form id="searchForm">
        <input type="text" id="searchInput" name="s" placeholder="Search destinations...">
        <button type="submit">Search</button>
    </form>
</div>


<div class="content">
    <?php
    $currentState = '';
    
    while ($rowDestination = mysqli_fetch_assoc($resultDestinations)) {
        // Extracting destination information
        $locationName = $rowDestination['Location_Name'];
        $description = $rowDestination['Description'];
        $entranceFeeAdultMalaysian = $rowDestination['EntranceFeeAdultMalaysian'];
        $entranceFeeAdultNonMalaysian = $rowDestination['EntranceFeeAdultNonMalaysian'];
        $entranceFeeChildrenMalaysian = $rowDestination['EntranceFeeChildrenMalaysian'];
        $entranceFeeChildrenNonMalaysian = $rowDestination['EntranceFeeChildrenNonMalaysian'];
        $openHours = $rowDestination['Open_Hours'];
        $googleMapsURL = $rowDestination['Google_Maps_Image_URL'];
        $state = $rowDestination['State'];
        
        // Fetch likes and dislikes count for this destination
        $destinationID = $rowDestination['DestinationID'];
        $commentsForDestination = getDestinationComments($conn, $destinationID);
        $queryLikesDislikesCount = "SELECT COALESCE(SUM(NumLikes), 0) AS totalLikes, COALESCE(SUM(NumDislikes), 0) AS totalDislikes FROM `destinationlikesdislike` WHERE DestinationID = '$destinationID'";
        $resultLikesDislikesCount = mysqli_query($conn, $queryLikesDislikesCount);
        $rowLikesDislikesCount = mysqli_fetch_assoc($resultLikesDislikesCount);
        $totalLikes = $rowLikesDislikesCount['totalLikes'];
        $totalDislikes = $rowLikesDislikesCount['totalDislikes'];

        // Display the destination information
        echo "<div class='destination' data-destination-id='{$destinationID}'>";
        echo "<img src='{$rowDestination['ImageDestination']}' alt='Destination Image' />";
        echo "<h2>$locationName</h2>";
        echo "<div class='info'>";
        echo "<p>$description</p>";
        echo "<h4>Entrance Fee:</h4>";
        echo "<table>";
        echo "<tr><td>Adult</td><td>: RM $entranceFeeAdultMalaysian (Malaysian)</td><td></td><td> RM $entranceFeeAdultNonMalaysian (Non-Malaysian)</td></tr>";
        echo "<tr><td>Children</td><td>: RM $entranceFeeChildrenMalaysian (Malaysian)</td><td></td><td> RM $entranceFeeChildrenNonMalaysian (Non-Malaysian)</td></tr>";
        echo "</table>";
        echo "<h4>Open Hours:</h4>";
        echo "<p>$openHours</p>";
        echo "<h4>Google Maps</h4>";
        echo "<a href='$googleMapsURL'><img src='image/Google Maps logo.png' alt='Google Map' style='width: 50px; height: 50px;'></a>";

        echo "<div class='button-container'>";
        // Display like and dislike counts
        echo "<div class='like-dislike'>";
        echo "<button class='like-button' data-action='like'><i class='fas fa-thumbs-up'></i></button>";
        echo "<span class='like-count'>" . $totalLikes . "</span>";
        echo "</div>";
        echo "<div class='like-dislike'>";
        echo "<button class='dislike-button' data-action='dislike'><i class='fas fa-thumbs-down'></i></button>";
        echo "<span class='dislike-count'>" . $totalDislikes . "</span>";
        echo "</div>";
        echo "<div class='comment-section'>";
        echo "<button class='btn btn-secondary comment-button' data-toggle='modal' data-target='#commentModal{$destinationID}'>Comment</button>";

        echo "<div class='modal fade' id='commentModal{$destinationID}' tabindex='-1' role='dialog' aria-labelledby='commentModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog' role='document'>";
        echo "<div class='modal-content'>";
        echo "<div class='modal-header'>";
        echo "<h5 class='modal-title' id='commentModalLabel'>Comments</h5>";
        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo "<span aria-hidden='true'>&times;</span>";
        echo "</button>";
        echo "</div>";
        echo "<style>";
        echo '.user-avatar { width: 40px !important; height: auto !important; margin-right: 10px !important; }';
        echo '.comment-container { display: flex; align-items: flex-start; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px; }';
        echo '.comment-details { flex: 1; }';
        echo '.user-name { font-weight: bold; margin-bottom: 5px; color: #007bff; }';
        echo '.comment-text { margin-bottom: 10px; line-height: 1.4; }';
        echo '.timestamp { font-size: 12px; color: #555; }';
        echo '.comment-section { max-height: 400px; overflow-y: auto; }';
        echo "</style>"; 
        echo "<div class='comment-form'>";
        echo "<textarea class='form-control' name='comment_text' rows='4' placeholder='Write your comment here...'></textarea>";
        echo "<button class='btn btn-primary submit-comment' data-destination-id='{$destinationID}'>Submit</button>";
        echo "<h5>Comments list</h5>";
        // Loop through and display comments with user names, avatars, and timestamps
       $comments = getDestinationComments($conn, $destinationID);
        echo "<div class='comment-section' data-destination-id='{$destinationID}'>";
        foreach ($comments as $comment) {
            echo "<div class='comment-container'>";
            echo "<img src='image/user.png' alt='User Avatar' class='user-avatar'>";
            echo "<div class='comment-details'>";
            $UserName = isset($comment['Username']) ? $comment['Username'] : '';
            $commentText = isset($comment['CommentText']) ? $comment['CommentText'] : '';
            $createdAt = isset($comment['created_at']) ? $comment['created_at'] : ''; // Include created_at field
            echo "<div class='user-name'>$UserName</div>";
            echo "<div class='comment-text'>$commentText</div>";
            echo "<div class='timestamp'>$createdAt</div>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    ?>
</div>
        
    <!-- Back to Top Button -->
    <button id="back-to-top-btn">
        <i class="fas fa-arrow-up"></i>
    </button>
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Explore</h3>
                <ul>
                    <li><a href="#sabah">Sabah</a></li>
                    <li><a href="#sarawak">Sarawak</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="currency_converter.php">Currency Conversion</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Information</h3>
                <ul>
                    <li><a href="AboutUs.html">About Us</a></li>
                    <li><a href="PrivacyPolicy.html">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>&nbsp;&nbsp;&nbsp;Email: travelpro@support.com</p>
                <p>&nbsp;&nbsp;&nbsp;Phone: +6011-462-7221</p>
                <p><a href="contact_us.php">&nbsp;&nbsp;&nbsp;Contact Form</a></p>
            </div>
        </div>
        <div class="copyright">
            &copy; 2023 Travel Pro [Sabah & Sarawak Travel Recommendation and Blog]
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("DOMContentLoaded event listener is running");

        function toggleCommentSection(commentSection) {
            if (commentSection.style.display === 'none' || commentSection.style.display === '') {
                commentSection.style.display = 'block';
            } else {
                commentSection.style.display = 'none';
            }
        }

        let slideIndex = 1;

        function showSlides() {
            const slides = document.querySelectorAll('.slide');

            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = 'none';
            }

            if (slideIndex > slides.length) {
                slideIndex = 1;
            }

            slides[slideIndex - 1].style.display = 'block';

            slideIndex++;

            setTimeout(showSlides, 3000); // Change slide every 3 seconds (adjust as needed)
        }

        showSlides(); // Start the slideshow

        // Function to handle the like and dislike button click
        function handleLikeDislikeClick(element) {
            const action = element.getAttribute("data-action");
            const destinationID = element.closest('.destination').dataset.destinationId;
            const countElement = element.nextElementSibling;

            console.log("Action: ", action);
            console.log("Destination ID: ", destinationID);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/WebDevelopmentProject/public_html/handle_like_dislike.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        // Process successful response
                        const response = JSON.parse(xhr.responseText);
                        console.log("Response: ", response); // Log the response for debugging

                        // Update the like and dislike counts on the client side
                        if (response.totalLikes !== undefined) {
                            countElement.textContent = response.totalLikes;
                        }

                        if (response.totalDislikes !== undefined) {
                            // Update the dislike count
                            const dislikeCountElement = element.closest('.destination').querySelector('.dislike-count');
                            dislikeCountElement.textContent = response.totalDislikes;
                        }
                    } else {
                        console.error("Failed to handle like/dislike action. HTTP Status: " + xhr.status);
                    }
                }
            };
            // Use encodeURIComponent to properly encode the data
            const data = `action=${encodeURIComponent(action)}&destinationID=${encodeURIComponent(destinationID)}`;
            xhr.send(data);
        }

        // Event delegation for like and dislike buttons
        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("like-button") || event.target.classList.contains("dislike-button")) {
                handleLikeDislikeClick(event.target);
                console.log("Button clicked");
            }
        });

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("submit-comment")) {
            const destinationID = event.target.getAttribute("data-destination-id");
            const commentText = event.target.parentElement.querySelector("textarea").value;

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/WebDevelopmentProject/public_html/handle-comment.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            // Clear the existing comments for the specific destination
                            const commentSection = document.querySelector(`.comment-section[data-destination-id="${destinationID}"]`);
                            commentSection.innerHTML = "";

                            // Display the updated comments
                            response.comments.forEach(function (comment) {
                                const commentContainer = createCommentElement(comment);
                                commentSection.appendChild(commentContainer);
                            });

                            // Clear the textarea after successful submission
                            event.target.parentElement.querySelector("textarea").value = "";
                        } else {
                            console.error("Failed to handle comment submission. Error: ", response.error);
                        }
                    } catch (error) {
                        console.error("Error parsing JSON response: ", error);
                    }
                } else {
                    console.error("Failed to handle comment submission. HTTP Status: " + xhr.status);
                }
            }
        };
        const data = `action=submit&destinationID=${encodeURIComponent(destinationID)}&commentText=${encodeURIComponent(commentText)}`;
        xhr.send(data);
    }
});

// Function to create a comment element
function createCommentElement(comment) {
    const commentContainer = document.createElement("div");
    commentContainer.classList.add("comment-container");

    const userAvatar = document.createElement("img");
    userAvatar.src = "image/user.png";
    userAvatar.alt = "User Avatar";
    userAvatar.classList.add("user-avatar");

    const commentDetails = document.createElement("div");
    commentDetails.classList.add("comment-details");

    const userName = document.createElement("p");
    userName.classList.add("user-name");
    userName.textContent = comment.Username;

    const commentText = document.createElement("p");
    commentText.classList.add("comment-text");
    commentText.textContent = comment.CommentText;

    const timestamp = document.createElement("p");
    timestamp.classList.add("timestamp");
    timestamp.textContent = comment.created_at;

    commentDetails.appendChild(userName);
    commentDetails.appendChild(commentText);
    commentDetails.appendChild(timestamp);

    commentContainer.appendChild(userAvatar);
    commentContainer.appendChild(commentDetails);

    return commentContainer;
}

        // Get the button element (make sure this button exists in your HTML)
        var backButton = document.getElementById("back-to-top-btn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                backButton.style.display = "block";
            } else {
                backButton.style.display = "none";
            }
        };

        // Scroll to the top of the document when the button is clicked
        backButton.onclick = function () {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE, and Opera
        };
    });

    function toggleMenu() {
        var menuList = document.getElementById("myTopnav");
        menuList.classList.toggle("active");
    }

    const destinationsDropdown = document.getElementById("destinationsDropdown");
    const dropdownMenu = document.querySelector(".dropdown-menu");
    let dropdownOpen = false;
    let closeTimer; // Variable to store the timer for delayed closing

    // Function to open the dropdown menu when hovering over "Destinations"
    destinationsDropdown.addEventListener("mouseenter", function () {
        if (!dropdownOpen) {
            dropdownMenu.style.display = "block";
            dropdownOpen = true;
        }
    });

    // Function to close the dropdown menu when mouse leaves "Destinations"
    destinationsDropdown.addEventListener("mouseleave", function () {
        // Use a setTimeout to introduce a delay before closing the dropdown
        closeTimer = setTimeout(function () {
            if (dropdownOpen) {
                dropdownMenu.style.display = "none";
                dropdownOpen = false;
            }
        }, 500); // Adjust the delay (in milliseconds) as needed
    });

    // Function to clear the close timer when re-entering the dropdown
    dropdownMenu.addEventListener("mouseenter", function () {
        clearTimeout(closeTimer);
    });

    // Function to close the dropdown menu when mouse leaves the entire navbar
    const navbar = document.querySelector(".navbar");
    navbar.addEventListener("mouseleave", function () {
        if (dropdownOpen) {
            dropdownMenu.style.display = "none";
            dropdownOpen = false;
        }
    });

    // JavaScript code to toggle the visibility of the '.info' element
    const destinationElements = document.querySelectorAll('.destination');

    destinationElements.forEach(function (element) {
        // Find the '.info' element inside the '.destination'
        const infoElement = element.querySelector('.info');

        // Add click event listeners to the 'img' and 'h2' elements
        const imgElement = element.querySelector('img');
        const h2Element = element.querySelector('h2');

        imgElement.addEventListener('click', function () {
            toggleInfoVisibility(infoElement);
        });

        h2Element.addEventListener('click', function () {
            toggleInfoVisibility(infoElement);
        });
    });

    function toggleInfoVisibility(infoElement) {
        // Toggle the visibility of the '.info' element
        if (infoElement.style.display === 'none' || infoElement.style.display === '') {
            infoElement.style.display = 'block';
        } else {
            infoElement.style.display = 'none';
        }
    }
$(document).ready(function () {
    // Handle form submission
    $('#searchForm').submit(function (e) {
        e.preventDefault(); // Prevent default form submission

        // Get the search input value
        var searchQuery = $('#searchInput').val();

        // Make AJAX request to search.php
        $.ajax({
            type: 'GET',
            url: 'http://localhost/api/destination/search.php',
            data: { s: searchQuery },
            dataType: 'json',
            success: function (response) {
                // Example: Display results in the console
                if (response.records && response.records.length > 0) {
                    console.log('Search results:', response.records);

                    // Get the first destination ID from the search results
                    var firstDestinationID = response.records[0].DestinationID;

                    // Scroll to the destination with the found ID
                    scrollToDestination(firstDestinationID);
                } else {
                    // No results found, display a message on the user's page
                    displayNoResultsMessage();
                }
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error('Error:', error);
            }
        });
    });

    // Function to scroll to the destination with the given ID
    function scrollToDestination(destinationID) {
        var destinationElement = document.querySelector('.destination[data-destination-id="' + destinationID + '"]');
        if (destinationElement) {
            destinationElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Function to display a message when no results are found
    function displayNoResultsMessage() {
        // Create a message element
        var messageElement = document.createElement('p');
        messageElement.textContent = 'No results found.';

        // Append the message element to a specific location on your webpage
        // For example, assuming you have a container with the ID "searchResultsContainer"
        var resultsContainer = document.querySelector('.content'); // Change this to the appropriate container
        resultsContainer.innerHTML = ''; // Clear previous results
        resultsContainer.appendChild(messageElement);
    }
});

// Scroll to the first destination of Sabah when clicking on the Sabah link
    document.getElementById("sabah-link").addEventListener("click", function (event) {
        event.preventDefault();
        scrollToFirstDestination("Sabah");
    });

    // Scroll to the first destination of Sarawak when clicking on the Sarawak link
    document.getElementById("sarawak-link").addEventListener("click", function (event) {
        event.preventDefault();
        scrollToFirstDestination("Sarawak");
    });

    // Function to scroll to the first destination of the specified state
    function scrollToFirstDestination(state) {
        const destinations = document.querySelectorAll('.destination');
        for (const destination of destinations) {
            const destinationState = destination.querySelector('.state').textContent.trim();
            if (destinationState === state) {
                destination.scrollIntoView({ behavior: 'smooth', block: 'start' });
                break; // Stop after scrolling to the first destination of the specified state
            }
        }
    }
</script>

</body>
</html>