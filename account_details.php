<?php
// Include your database connection configuration
include("config.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Assuming you have a session started for user authentication
session_start();

// Fetch account details based on UserID or Email
function fetchAccountDetails($conn, $identifier)
{
    // Use a prepared statement to prevent SQL injection
    $query = "SELECT * FROM `user` WHERE UserID = ? OR Email = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Check if the prepared statement is successful
    if ($stmt) {
        // Bind the parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "ss", $identifier, $identifier);
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Fetch the account details
        $accountDetails = mysqli_fetch_assoc($result);

        // Close the statement
        mysqli_stmt_close($stmt);

        return $accountDetails;
    } else {
        // Handle the case where the prepared statement fails
        return null;
    }
}

// Handle updating account details if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the updated data
    $updatedUserName = $_POST['userName'];
    $updatedEmail = $_POST['email'];
    $updatedPhoneNumber = $_POST['phoneNumber'];

    // Update the user information in the database
    $updateQuery = "UPDATE `user` SET Username=?, Email=?, Phone_Number=? WHERE UserID=?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);

    if ($updateStmt) {
        mysqli_stmt_bind_param($updateStmt, "sssi", $updatedUserName, $updatedEmail, $updatedPhoneNumber, $_SESSION['UserID']);
        $success = mysqli_stmt_execute($updateStmt);

        if ($success) {
            // Update the session variables if needed
            $_SESSION['Username'] = $updatedUserName;
            $_SESSION['Email'] = $updatedEmail;
            $_SESSION['Phone_Number'] = $updatedPhoneNumber;
            $successMessage = "Account details updated successfully!";
        } else {
            $errorMessage = "Error updating account details.";
        }

        // Close the statement
        mysqli_stmt_close($updateStmt);
    } else {
        $errorMessage = "Error preparing update statement.";
    }
}

// Fetch account details if the user is authenticated
if (isset($_SESSION['UserID']) || isset($_SESSION['Email'])) {
    $identifier = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : $_SESSION['Email'];
    $accountDetails = fetchAccountDetails($conn, $identifier);

    // Now you have the account details, and you can use them as needed
    if ($accountDetails) {
        $userID = $accountDetails['UserID'];
        $userName = $accountDetails['Username'];
        $email = $accountDetails['Email'];
        $phoneNumber = $accountDetails['Phone_Number'];
    } else {
        // Handle the case where fetching account details fails
        $errorMessage = "Error fetching account details.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <!-- Your custom CSS file -->
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="account_details.css">
    <!-- Link to Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                            <a class="dropdown-item" href="#sabah">Sabah</a>
                            <a class="dropdown-item" href="#sarawak">Sarawak</a>
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
        <center>
        <a href="Home.html"><img src="image/logo.png" width="300px" height="300px" alt="Website Logo"></a>
    </center>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
                <?php
                // Display success or error messages
                if (isset($successMessage)) {
                    echo '<div id="successMessage" class="alert alert-success mt-3" role="alert">' . $successMessage . '</div>';
                } elseif (isset($errorMessage)) {
                    echo '<div class="alert alert-danger mt-3" role="alert">' . $errorMessage . '</div>';
                }
                ?>
            <h2 class="text-center">Account Details</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="userName" class="form-label">User Name</label>
                    <input type="text" class="form-control" id="userName" name="userName" value="<?php echo $userName; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $phoneNumber; ?>" readonly>
                </div>


                            <button type="button" id="editButton" class="btn btn-primary d-block mx-auto mt-5 mb-3">Edit</button>

                            <button type="submit" id="submitButton" class="btn btn-success d-block mx-auto mb-5" style="display: none;">Save</button>


            </form>
        </div>
    </div>
</div>
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

<!-- Link to Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // JavaScript to handle the edit button click event
        document.addEventListener('DOMContentLoaded', function () {
            const editButton = document.getElementById('editButton');
            if (editButton) {
                editButton.addEventListener('click', function () {
                    // Enable form fields for editing
                    document.getElementById('userName').readOnly = false;
                    document.getElementById('email').readOnly = false;
                    document.getElementById('phoneNumber').readOnly = false;
                    // Show the submit button
                    const submitButton = document.getElementById('submitButton');
                    if (submitButton) {
                        submitButton.style.display = 'block';
                    }
                    // Hide the edit button
                    editButton.style.display = 'none';
                });
            }

            // JavaScript to handle the form submission (you can replace this with your backend code)
            const accountDetailsForm = document.getElementById('accountDetailsForm');
            if (accountDetailsForm) {
                accountDetailsForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    // Collect the updated data and send it to the server
                    const updatedData = {
                        userName: document.getElementById('userName').value,
                        email: document.getElementById('email').value,
                        phoneNumber: document.getElementById('phoneNumber').value
                    };

                    alert('Updated Data:\n' + JSON.stringify(updatedData, null, 2));
                    document.getElementById('userName').readOnly = true;
                    document.getElementById('email').readOnly = true;
                    document.getElementById('phoneNumber').readOnly = true;
                    // Hide the submit button
                    const submitButton = document.getElementById('submitButton');
                    if (submitButton) {
                        submitButton.style.display = 'none';
                    }
                    // Show the edit button
                    editButton.style.display = 'block';
                    // Show success message
                    const successMessage = document.getElementById('successMessage');
                    if (successMessage) {
                        successMessage.style.display = 'block';
                        // Hide success message after 3 seconds
                        setTimeout(function () {
                            successMessage.style.display = 'none';
                        }, 3000);
                    }
                });
            }
        });
    </script>
</body>
</html>

