<?php
include("config.php");

// Function to truncate the description
function truncateDescription($description, $maxLength = 100) {
    if (strlen($description) > $maxLength) {
        return substr($description, 0, $maxLength) . '...';
    }
    return $description;
}

// Initialize alert variables
$successMessage = $errorMessage = "";

// Edit operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_destination"])) {
    // Validate and sanitize the input
    $destinationID = $_POST["destination_id"];
    // Add more validations for other fields as needed

    // Assuming you have a table named 'destination' with fields to update
    $sql = "UPDATE destination SET
            Location_Name = ?, 
            Description = ?, 
            EntranceFeeAdultMalaysian = ?, 
            EntranceFeeAdultNonMalaysian = ?, 
            EntranceFeeChildrenMalaysian = ?, 
            EntranceFeeChildrenNonMalaysian = ?, 
            Open_Hours = ?, 
            Google_Maps_Image_URL = ?, 
            State = ?, 
            ImageDestination = ?
            WHERE DestinationID = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddddssssi", 
        $_POST["location_name"], 
        $_POST["description"], 
        $_POST["entrance_fee_adult_myr"], 
        $_POST["entrance_fee_adult_non_myr"], 
        $_POST["entrance_fee_children_myr"], 
        $_POST["entrance_fee_children_non_myr"], 
        $_POST["open_hours"], 
        $_POST["google_maps_url"], 
        $_POST["state"], 
        $_POST["image_destination"], 
        $destinationID);

    if ($stmt->execute()) {
        $successMessage = "Changes saved successfully!";
    } else {
        $errorMessage = "Error updating destination: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Delete operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_destination"])) {
    // Validate and sanitize the input
    $destinationID = $_POST["destination_id"];
    
    // Assuming you have a table named 'destination'
    $sql = "DELETE FROM destination WHERE DestinationID = ?";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $destinationID);

    if ($stmt->execute()) {
        $successMessage = "Destination deleted successfully!";
    } else {
        $errorMessage = "Error deleting destination: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Fetch all destinations from the database
$sql = "SELECT * FROM destination";
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
    <title>Admin - Destination List</title>
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
        }
        .btn-primary,
        .btn-danger {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
            <!-- Display success or error message -->
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php elseif (!empty($errorMessage)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
        <h2>Destination List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Location Name</th>
                    <th>Description</th>
                    <!-- ... (Other table headers) -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['DestinationID']; ?></td>
                        <td><?php echo $row['Location_Name']; ?></td>
                        <td><?php echo truncateDescription($row['Description']); ?></td>
                        <!-- ... (Display other destination information) -->
                        <td>
                            <!-- Edit button with modal trigger -->
                            <button class="btn btn-primary btn-sm edit-btn" data-toggle="modal" data-target="#editModal<?php echo $row['DestinationID']; ?>">Edit</button>
                            
                            <!-- Delete button with modal trigger -->
                            <button class="btn btn-danger btn-sm delete-btn" data-toggle="modal" data-target="#deleteModal<?php echo $row['DestinationID']; ?>">Delete</button>
                        </td>
                    </tr>

 
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $row['DestinationID']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Destination</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Form for editing destination -->
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <div class="form-group">
                                        <label for="location_name">Location Name:</label>
                                        <input type="text" class="form-control" name="location_name" value="<?php echo $row['Location_Name']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description:</label>
                                        <textarea class="form-control" name="description"><?php echo $row['Description']; ?></textarea>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="entrance_fee_adult_myr">Entrance Fee (Adult Malaysian):</label>
                                            <input type="number" class="form-control" name="entrance_fee_adult_myr" value="<?php echo $row['EntranceFeeAdultMalaysian']; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="entrance_fee_adult_non_myr">Entrance Fee (Adult Non-Malaysian):</label>
                                            <input type="number" class="form-control" name="entrance_fee_adult_non_myr" value="<?php echo $row['EntranceFeeAdultNonMalaysian']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="entrance_fee_children_myr">Entrance Fee (Children Malaysian):</label>
                                            <input type="number" class="form-control" name="entrance_fee_children_myr" value="<?php echo $row['EntranceFeeChildrenMalaysian']; ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="entrance_fee_children_non_myr">Entrance Fee (Children Non-Malaysian):</label>
                                            <input type="number" class="form-control" name="entrance_fee_children_non_myr" value="<?php echo $row['EntranceFeeChildrenNonMalaysian']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="open_hours">Open Hours:</label>
                                        <input type="text" class="form-control" name="open_hours" value="<?php echo $row['Open_Hours']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="google_maps_url">Google Maps Image URL:</label>
                                        <input type="text" class="form-control" name="google_maps_url" value="<?php echo $row['Google_Maps_Image_URL']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="state">State:</label>
                                        <input type="text" class="form-control" name="state" value="<?php echo $row['State']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="image_destination">Image Destination:</label>
                                        <input type="text" class="form-control" name="image_destination" value="<?php echo $row['ImageDestination']; ?>">
                                    </div>
                                    
                                    <input type="hidden" name="destination_id" value="<?php echo $row['DestinationID']; ?>">
                                    <button type="submit" name="edit_destination" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal<?php echo $row['DestinationID']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <!-- Delete modal content goes here -->
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete Destination</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this destination?</p>
                                    <!-- Form for deleting destination -->
                                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="hidden" name="destination_id" value="<?php echo $row['DestinationID']; ?>">
                                        <button type="submit" name="delete_destination">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
    <script>
        $(document).ready(function () {
            // Handle edit button click
            $('.edit-btn').click(function () {
                // Get the destination ID from the button's data attribute
                var destinationID = $(this).data('destination-id');
                // Open the corresponding edit modal
                $('#editModal' + destinationID).modal('show');
            });

            // Handle delete button click
            $('.delete-btn').click(function () {
                // Get the destination ID from the button's data attribute
                var destinationID = $(this).data('destination-id');
                // Open the corresponding delete modal
                $('#deleteModal' + destinationID).modal('show');
            });
        });
    </script>
    </script>
</body>
</html>
