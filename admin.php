<?php
// Include your database connection configuration
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $locationName = $_POST['locationName'];
    $description = $_POST['description'];
    $entranceFeeAdultMalaysian = $_POST['entranceFeeAdultMalaysian'];
    $entranceFeeAdultNonMalaysian = $_POST['entranceFeeAdultNonMalaysian'];
    $entranceFeeChildrenMalaysian = $_POST['entranceFeeChildrenMalaysian'];
    $entranceFeeChildrenNonMalaysian = $_POST['entranceFeeChildrenNonMalaysian'];
    $openHours = $_POST['openHours'];
    $googleMapsImageURL = $_POST['googleMapsImageURL'];
    $state = $_POST['state'];

    // Handle image upload
    $ImageDestination = $_FILES['ImageDestination'];

    if ($ImageDestination['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $ImageDestination['name'];
        $imageTempName = $ImageDestination['tmp_name'];

        // Define the directory where you want to store the uploaded images
        $uploadDirectory = __DIR__ . "/image/"; // Use the full server path

        if (!is_dir($uploadDirectory)) {
            // Create the 'image' directory if it doesn't exist
            if (mkdir($uploadDirectory, 0777, true)) {
                echo "The 'image' directory has been created.";
            } else {
                echo "Failed to create the 'image' directory.";
            }
        }

        if (!is_writable($uploadDirectory)) {
            echo "The 'image' directory is not writable.";
            // You may need to adjust directory permissions here.
        } else {
            $destination = $uploadDirectory . $imageFileName;

            // Move the uploaded image to the destination folder
            if (move_uploaded_file($imageTempName, $destination)) {
                // Image upload successful
                echo "Image uploaded successfully!";
                
                // Now, save the file path or filename to the database
                $imagePath = "image/" . $imageFileName; // Update this path accordingly

                // Use prepared statement to insert data into the database
               $stmt = $conn->prepare("INSERT INTO Destination (Location_Name, Description, EntranceFeeAdultMalaysian, EntranceFeeAdultNonMalaysian, EntranceFeeChildrenMalaysian, EntranceFeeChildrenNonMalaysian, Open_Hours, Google_Maps_Image_URL, ImageDestination, State) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // Bind parameters
                $stmt->bind_param("ssddddssss", $locationName, $description, $entranceFeeAdultMalaysian, $entranceFeeAdultNonMalaysian, $entranceFeeChildrenMalaysian, $entranceFeeChildrenNonMalaysian, $openHours, $googleMapsImageURL, $imagePath, $state);

                // Execute the statement
                if ($stmt->execute()) {
                    echo "Destination added successfully!";
                    // Redirect back to Admin.html
                    header("Location: Admin.html");
                    exit; // Make sure to exit to prevent further script execution
                } else {
                    echo "Error: " . $stmt->error;
                }

                // Close the prepared statement
                $stmt->close();
            } else {
                echo "Error uploading the image.";
            }
        }
    } else {
        echo "Image upload failed. Error code: " . $ImageDestination['error'];
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS for styling */
        body {
            background-color: lightskyblue;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #edeff2;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        input[type="number"] {
            background-color: #edeff2;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="number"] {
            background-color: #edeff2;
            step: 0.01;
        }

        input[type="submit"] {
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
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
                    <li class="nav-item active"">
                        <a class="nav-link" href="admin.php">Add Destination</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_contactform.php">User Contact Form</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Add Destination</h1>
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="ImageDestination">Upload Image (jpg, jpeg, png):</label>
                <input type="file" class="form-control-file" name="ImageDestination">
            </div>

            <div class="form-group">
                <label for="locationName">Location Name:</label>
                <input type="text" class="form-control" name="locationName" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="entranceFeeAdultMalaysian">Entrance Fee (Adult - Malaysian):</label>
                <input type="number" step="0.01" class="form-control" name="entranceFeeAdultMalaysian" required>
            </div>

            <div class="form-group">
                <label for="entranceFeeAdultNonMalaysian">Entrance Fee (Adult - Non-Malaysian):</label>
                <input type="number" step="0.01" class="form-control" name="entranceFeeAdultNonMalaysian" required>
            </div>

            <div class="form-group">
                <label for="entranceFeeChildrenMalaysian">Entrance Fee (Children - Malaysian):</label>
                <input type="number" step="0.01" class="form-control" name="entranceFeeChildrenMalaysian" required>
            </div>

            <div class="form-group">
                <label for="entranceFeeChildrenNonMalaysian">Entrance Fee (Children - Non-Malaysian):</label>
                <input type="number" step="0.01" class="form-control" name="entranceFeeChildrenNonMalaysian" required>
            </div>

            <div class="form-group">
                <label for="openHours">Open Hours:</label>
                <input type="text" class="form-control" name="openHours" required>
            </div>

            <div class="form-group">
                <label for="googleMapsImageURL">Google Maps Image URL:</label>
                <input type="text" class="form-control" name="googleMapsImageURL" required>
            </div>

            <div class="form-group">
                <label for="state">State:</label>
                <select class="form-control" name="state">
                    <option value="Sarawak">Sarawak</option>
                    <option value="Sabah">Sabah</option>
                </select>
            </div>

            <input type="submit" class="btn btn-primary" value="Add Destination">
        </form>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


