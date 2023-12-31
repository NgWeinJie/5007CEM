<!DOCTYPE html>

<html>
    <head>
        <title>Privacy Policy</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="stylesheet" type="text/css" href="PrivacyPolicy.css">
        
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- Bootstrap JavaScript dependencies -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body class="privacy-policy">
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
        <p>Last Updated: [19/9/2023]</p>
        <h4>Introduction:</h4>
        <p>Welcome to Travel Pro! We are committed to safeguarding your privacy and ensuring a secure and enjoyable experience while using our travel recommendation and blog website. This Privacy Policy outlines how we collect, use, and protect your personal information. By accessing or using Travel Pro, you consent to the practices described in this policy.</p>
        <h4>Information We Collect:</h4>
    <p>
        <ul>
            <li><b>Personal Information:</b> We may collect personal information, including your name, email address, and any voluntarily provided details when you create an account or interact with our website.</li>
            <li><b>Usage Data:</b> We collect data about your interactions with Travel Pro, such as pages visited, posts viewed, and user-generated content.</li>
        </ul>
    </p>
    <h4>How We Use Your Information:</h4>
    <p>
        <ul>
            <li><b>Personalization:</b> We use your information to personalize your experience on Travel Pro, including providing travel recommendations and content that align with your interests.</li>
            <li><b>Communication:</b> We may use your email address to send you updates, newsletters, or notifications related to Travel Pro. You can opt out of these communications at any time.</li>
            <li><b>Community Building:</b> Your user-generated content, such as travel blog posts, helps build a community of travelers on Travel Pro.</li>
        </ul>
    </p>
    <h4>Sharing Your Information:</h4>
    <p>We do not sell, trade, or rent your personal information to third parties. Your data may be shared with service providers who assist us in operating Travel Pro, but they are bound by confidentiality agreements.</p>
    <h4>Security Measures:</h4>
    <p>We employ industry-standard security measures to protect your personal information from unauthorized access, disclosure, alteration, or destruction.</p>
    <h4>Cookies:</h4>
    <p>Travel Pro uses cookies to enhance your user experience. You can manage or disable cookies through your browser settings.</p>
    <h4>Your Choices:</h4>
    <p>You can update your account information, manage your email preferences, and delete your account at any time.</p>
    <h4>Children's Privacy</h4>
    <p>Travel Pro is not intended for individuals under the age of 13. We do not knowingly collect personal information from children.</p>
    <h4>Contact Us:</h4>
    <p>If you have questions or concerns about this Privacy Policy or your data, please contact us at <a href="Contact Us.html"> Contact Form</a> or [travelpro@support.com].</p>
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
