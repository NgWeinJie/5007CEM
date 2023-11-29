<?php
// Include your database connection configuration
include("config.php");

// Assuming you have a session started for user authentication
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['UserID'])) {
    $userID = $_SESSION['UserID'];
    $username = $_POST['username'];
    $questionType = $_POST['question_type'];
    $subject = $_POST['subject'];
    $content = $_POST['content'];

    // Insert data into the contactform table
    $sql = "INSERT INTO contactform (UserID, Question_Type, Subject, Content) VALUES ('$userID', '$questionType', '$subject', '$content')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['successMessage'] = "Record inserted successfully";
        // Redirect to the same page to prevent form resubmission on page refresh
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['errorMessage'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}

// Display success or error message if set
$successMessage = isset($_SESSION['successMessage']) ? $_SESSION['successMessage'] : '';
$errorMessage = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : '';
unset($_SESSION['successMessage']);
unset($_SESSION['errorMessage']);
?>

<!DOCTYPE html>

<html>
    <head>
        <title>Contact Us</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="stylesheet" type="text/css" href="Contact Us.css">
        
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <!-- Combined Bootstrap JavaScript dependencies and Font Awesome CSS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
                    <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="currency_converter.php">Currency Conversion</a></li>
                    <li class="nav-item"><a class="nav-link" href="AboutUs.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact_us.php">Contact Us</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="account_details.php">
                            <i class="fas fa-user account-icon"></i> Account Details
                        </a>
                    </li>
                        <li class="nav-item">
                            <?php
                            // Check if the user is logged in
                            if (isset($_SESSION['UserID'])) {
                                echo '<a class="nav-link" href="login.php"><i class="fas fa-sign-out-alt logout-icon"></i> Logout</a>';
                            } else {
                                echo '<a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt login-icon"></i> Login</a>';
                            }
                            ?>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
        <div class="content">
        <center><a href="Home.html"><img src="image/logo.png" width="300px" height="300px" alt="Website Logo"></a><center/>
        <h3>Contact Form</h3>
            <?php
            // Display success message if set
            if (!empty($successMessage)) {
                echo '<div id="successMessage" class="alert alert-success" role="alert">' . $successMessage . '</div>';
            }

            // Display error message if set
            if (!empty($errorMessage)) {
                echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
            }
            ?>
        <table>
            <tr>
                 <form action="" method="post" onsubmit="return checkLoginBeforeSubmit();">
                    <td>User Name: </td>
                    <td><input type="text" name="username" placeholder="User Name" required></td>
            <tr>
                    <td for="State">Question about: </td>
                    <td>
                    <select name="question_type" id="State">
                      <option value="sabah">Sabah</option>
                      <option value="sarawak">Sarawak</option>
                      <option value="other">Other</option>
                    </select>
                    </td>
            </tr>
            <tr>
                    <td for="subject">Subject: </td>
                    <td><input type="text" name="subject" placeholder="Write something.."  required></imput></td>
            </tr>
            <tr>
                    <td for="content">Content: </td>
                    <td><textarea name="content" placeholder="Enter content..." style="height:100px" required></textarea></td>
            </tr>

        </table>
        <input type="submit" value="Submit">
    </form>
      </div>
        
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Explore</h3>
                <ul>
                    <li><a href="blog.php">Blog</a></li>
                    <li><a href="currency_converter.php">Currency Conversion</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Information</h3>
                <ul>
                    <li><a href="AboutUs.php">About Us</a></li>
                    <li><a href="PrivacyPolicy.php">Privacy Policy</a></li>
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
<script>
function checkLoginBeforeSubmit() {
    // Check if the user is logged in
    <?php if (!isset($_SESSION['UserID'])) : ?>
        // If not logged in, display a confirmation dialog
        var confirmSubmit = confirm('You are not logged in. Do you want to login page for submit the contact form?');
        
        // If the user chooses to go to the login page, prevent form submission
        if (confirmSubmit) {
            window.location.href = 'login.php';
            return false; // Prevent form submission
        }
    <?php endif; ?>
    // If logged in, allow the form to be submitted
    return true;
}
</script>
    </body>
</html>