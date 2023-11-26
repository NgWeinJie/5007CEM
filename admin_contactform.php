<?php
// Include your database connection configuration
include("config.php");

// Fetch all contacts from the database
$sql = "SELECT * FROM `contactform`";
$result = $conn->query($sql);

// Check for errors in SQL execution
if (!$result) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: lightskyblue;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #dcf0fc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        /* Style to center-align content in th, td */
        table {
            width: 100%;
        }

        th, td {
            padding: 10px; /* Adjusted padding */
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        /* Additional styles for small screens */
        @media (max-width: 576px) {
            th, td {
                padding: 8px; /* Adjusted padding for small screens */
                font-size: 12px; /* Adjusted font size for small screens */
            }
        }
    </style>
        <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-lightblue">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin_destination.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Add Destination</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="admin_contactform.php">User Contact Form</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
</head>
<body>
    <div class="container">
        <h2>Contact List</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>SubmissionID</th>
                        <th>UserID</th>
                        <th>Username</th>
                        <th>Question Type</th>
                        <th>Subject</th>
                        <th>Content</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['SubmissionID']; ?></td>
                            <td><?php echo $row['UserID']; ?></td>
                            <!-- Fetch the username based on UserID from the user table -->
                            <?php
                                $userID = $row['UserID'];
                                $usernameQuery = "SELECT Username FROM `user` WHERE UserID = $userID";
                                $usernameResult = $conn->query($usernameQuery);
                                $usernameRow = $usernameResult->fetch_assoc();
                            ?>
                            <td><?php echo isset($usernameRow['Username']) ? $usernameRow['Username'] : ''; ?></td>
                            <td><?php echo $row['Question_Type']; ?></td>
                            <td><?php echo $row['Subject']; ?></td>
                            <td><?php echo $row['Content']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

