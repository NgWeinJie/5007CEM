<?php
session_start();
// Include your database connection configuration
include("config.php");

// Debugging: Display PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle the form submission to create a new blog post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    echo "Form submitted!";
    echo "<pre>";
    print_r($_POST);
    print_r($_FILES);
    echo "</pre>";

    $title = $_POST['post-title'];
    $content = $_POST['post-content'];

    // Handle file upload
    if ($_FILES['post-media']['error'] == UPLOAD_ERR_OK) {
        $mediaTempName = $_FILES['post-media']['tmp_name'];
        $mediaName = $_FILES['post-media']['name'];
        $mediaDestination = 'image/' . $mediaName; // Specify your image folder

        if (move_uploaded_file($mediaTempName, $mediaDestination)) {
            // File uploaded successfully, now you can use $mediaDestination in your database query
        } else {
            $errorMessage = "Error moving uploaded file.";
        }
    }

    // Insert the new post into the database
    $insertQuery = "INSERT INTO `blogpost` (UserID, Title, Content, Media, Publication_Date) VALUES (?, ?, ?, ?, NOW())";
    $insertStmt = mysqli_prepare($conn, $insertQuery);

    if ($insertStmt) {
        // Assuming you have the UserID in the session, replace with your authentication logic
        $userID = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : null;

        mysqli_stmt_bind_param($insertStmt, "isss", $userID, $title, $content, $mediaDestination);
        $success = mysqli_stmt_execute($insertStmt);

        if ($success) {
            // Redirect to the same page to prevent form resubmission
            header("Location: $_SERVER[PHP_SELF]");
            exit();
        } else {
            $errorMessage = "Error creating the blog post.";
        }

        // Close the statement
        mysqli_stmt_close($insertStmt);
    } else {
        $errorMessage = "Error preparing insert statement.";
    }
}

$selectQuery = "SELECT b.*, u.Username, u.UserID, b.Publication_Date FROM `blogpost` b
                LEFT JOIN `user` u ON b.UserID = u.UserID";


$result = mysqli_query($conn, $selectQuery);

// Check if the query was successful
if ($result) {
    $blogPosts = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $errorMessage = "Error fetching blog posts.";
}
// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="blog.css">

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
        <h1>Welcome to Our Blog</h1>
                    <?php
            // Display success or error messages
            if (isset($success)) {
                echo '<div class="alert alert-success" role="alert">Blog post created successfully!</div>';
            } elseif (isset($errorMessage)) {
                echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
            }
            ?>
    <main>
        <section id="post-form" class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <h2>Create a New Post</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="post-title">Title:</label>
                        <input type="text" class="form-control" id="post-title" name="post-title" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="post-content">Content:</label>
                        <textarea class="form-control" id="post-content" name="post-content" required></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="post-media">Attach Media:</label>
                <input type="file" class="form-control-file" id="post-media" name="post-media" accept="image/*, video/*">
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Publish Post</button>
            </div>
        </form>
    </section>

    <section id="posts" style="max-height: 800px; overflow-y: auto;">
        <h2>Blog Posts</h2>
        <div id="post-list">
        <?php
        // Display the list of blog posts
        if (isset($blogPosts)) {
            foreach ($blogPosts as $post) {
                echo '<style>';
                echo '.user-info { display: flex; align-items: center; }';
                echo '.avatar { width: 40px; height: auto; margin-right: 10px; }';
                echo '.media-image { max-width: 450px; width: 100%; height: auto; margin-top: 10px; }';
                echo '.blog-post { padding-bottom: 20px; }';
                echo '.blog-post { padding-top: 20px; }';
                echo '</style>';
                echo '<div class="blog-post">';

                echo '<div class="user-info">';
                echo '<img src="image/user.png" alt="User Avatar" class="avatar">';
                echo '<span class="username">' . $post['Username'] . '</span>';
                echo '</div>';

                // Display the title
                echo '<h3>' . $post['Title'] . '</h3>';

                // Display content
                echo '<p>' . $post['Content'] . '</p>';

                // Display the image if available
                if (!empty($post['Media'])) {
                    echo '<img src="' . $post['Media'] . '" alt="Blog Image" class="media-image">';
                }
                echo '<span class="publication-date";">' . $post['Publication_Date'] . '</span>';

                echo '</div>';
            }
        }
        ?>
        </div>
    </section>
</main>
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
        <script>
function toggleMenu() {
        var menuList = document.getElementById("myTopnav");
        menuList.classList.toggle("active");
    }

        </script>
</body>
</html>