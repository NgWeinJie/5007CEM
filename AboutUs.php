<!DOCTYPE html>
<html>
    <head>
        <title>About Us</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="stylesheet" type="text/css" href="AboutUs.css">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Bootstrap JavaScript dependencies and Font Awesome CSS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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
    <center>
        <a href="Home.html"><img src="image/logo.png" width="300px" height="300px" alt="Website Logo"></a>
    </center>
            <h1>About Us</h1>
        <p>Welcome to Discover Sabah & Sarawak Travel, your ultimate guide to exploring the stunning states of Sabah and Sarawak, Malaysia. Nestled on the island of Borneo, these regions boast an unparalleled richness in culture, landscapes, and wildlife. From the towering rainforests of Mount Kinabalu to the pristine white-sand beaches of Sipadan Island, our mission is to make your journey through Sabah and Sarawak an unforgettable adventure.</p>
        <p>What We Offer:</p>
        <p>Travel Recommendations: Our website is your go-to source for comprehensive travel recommendations tailored to Sabah and Sarawak. We curate must-visit destinations, hidden gems, and exciting activities to help you make the most of your journey.

            Travel Blog: Dive into our travel blog, where wanderers like you share their firsthand experiences. Get inspired by captivating travel narratives, vivid photographs, and practical tips. These personal accounts serve as invaluable resources for your trip planning.

            Post Creation and Publishing: We encourage you to be a part of our vibrant community of travelers. Create and publish your own travel stories, anecdotes, and tips on our blog. Your insights and adventures contribute to a collective pool of knowledge, empowering fellow travelers to embark on their dream journeys.

            At Discover Sabah & Sarawak Travel, our commitment is to assist you in crafting unforgettable memories. Whether you're seeking lush jungles, pristine beaches, diverse cultures, or thrilling wildlife encounters, we are here to guide you every step of the way. Welcome to the adventure of a lifetime!</p>
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
    </body>
</html>
