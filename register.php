<?php
include("config.php");

// Initialize the errors array
$errors = array();

// Initialize variables to retain user input
$usernameInput = '';
$emailInput = '';
$phoneInput = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usernameInput = $_POST['username'];
    $emailInput = $_POST['email'];
    $password = $_POST['password'];
    $phoneInput = $_POST['phone'];

    // Validate username
    if (empty($usernameInput)) {
        $errors[] = "Username is required.";
    }

    // Validate email
    if (empty($emailInput) || !filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    // Validate password
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        // Hash the password securely
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Use prepared statement to prevent SQL injection
        $sql = "INSERT INTO user (Username, Email, Password, Phone_Number) VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssss", $usernameInput, $emailInput, $passwordHash, $phoneInput);

            if ($stmt->execute()) {
                // Registration successful
                echo '<script>alert("Registration successful!");</script>';
                echo '<script>window.location.href = "Login.html";</script>';
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="register&login.css">

</head>
<body>
<div class="container-fluid d-flex align-items-center justify-content-center" style="height: 100vh;">
        <video autoplay loop muted>
            <source src="image/bg.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="content1">
            <h1>Register</h1>
            <form action="register.php" method="post">
                <?php
                // Display validation errors
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger" role="alert">';
                    foreach ($errors as $error) {
                        echo $error . '<br>';
                    }
                    echo '</div>';
                }
                ?>
                <div class="form-group">
                    <label for="username">User Name:</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your User Name" value="<?php echo htmlspecialchars($usernameInput); ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($emailInput); ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number" value="<?php echo htmlspecialchars($phoneInput); ?>">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <div class="signup-link">
                <p>Already have an account? <a href="Login.html">Login</a></p>
            </div>
        </div>
    </div>
    
    <!-- Include Bootstrap JS (optional) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

