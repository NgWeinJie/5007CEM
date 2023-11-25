<?php
include("config.php");

$invalidEmailMessage = '';
$invalidPasswordMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT AdminID, Admin_name, Admin_password FROM admin WHERE Admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            if (password_verify($password, $row['Admin_password'])) {
                // Admin login successful
                session_start();
                $_SESSION['AdminID'] = $row['AdminID'];
                $_SESSION['AdminName'] = $row['Admin_name'];
                session_regenerate_id(true);

                // Redirect to admin dashboard
                header("Location: admin_destination.php");
                exit;
            } else {
                // Invalid password
                $invalidPasswordMessage = "Invalid password.";
            }
        } else {
            // Invalid email
            $invalidEmailMessage = "Invalid email.";
        }
    } else {
        // Error in SQL execution
        echo "Error: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="register&login.css">

</head>
<body>
    <div class="container-fluid d-flex align-items-center justify-content-center" style="height: 100vh;">
        <video autoplay loop muted>
            <source src="image/bg.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="content">
            <h1>Login</h1>
            <?php
            // Display validation errors as Bootstrap alerts
            if (!empty($invalidEmailMessage) || !empty($invalidPasswordMessage)) {
                echo '<div class="alert alert-danger" role="alert">';
                echo (!empty($invalidEmailMessage)) ? $invalidEmailMessage . '<br>' : '';
                echo (!empty($invalidPasswordMessage)) ? $invalidPasswordMessage . '<br>' : '';
                echo '</div>';
            }
            ?>
            <form action="admin_login.php" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control <?php echo (!empty($invalidEmailMessage)) ? 'is-invalid' : ''; ?>" id="email" name="email" placeholder="Enter your email" required>
                    <div class="invalid-feedback" id="emailError"><?php echo $invalidEmailMessage; ?></div>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control <?php echo (!empty($invalidPasswordMessage)) ? 'is-invalid' : ''; ?>" id="password" name="password" placeholder="Enter your password" required>
                    <div class="invalid-feedback" id="passwordError"><?php echo $invalidPasswordMessage; ?></div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
    
    <!-- Include Bootstrap JS (optional) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
