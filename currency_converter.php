<?php
$apiKey = '4bce6f56ffefa4fea4a06f9b340a75cf';

// Initialize an empty array to store currency options
$currencies = array();

// Fetch and populate the currencies array
$apiURL = 'http://data.fixer.io/api/latest?access_key=' . $apiKey;
$response = file_get_contents($apiURL);

if ($response) {
    $data = json_decode($response, true);

    if ($data && isset($data['rates'])) {
        // Extract the currency codes from the rates
        $currencies = array_keys($data['rates']);
    }
}

// Check if form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the required POST variables are set
    if (isset($_POST['from_currency'], $_POST['to_currency'], $_POST['amount'])) {
        $fromCurrency = $_POST['from_currency'];
        $toCurrency = $_POST['to_currency'];
        $amount = floatval($_POST['amount']);

        if (!empty($currencies) && in_array($fromCurrency, $currencies) && in_array($toCurrency, $currencies)) {
            $conversionRateFrom = $data['rates'][$fromCurrency];
            $conversionRateTo = $data['rates'][$toCurrency];

            // Perform the currency conversion
            $convertedAmount = ($amount / $conversionRateFrom) * $conversionRateTo;

            // Return the result as JSON
            echo json_encode(['result' => number_format($convertedAmount, 2)]);
            exit; // Stop further execution
        } else {
            echo json_encode(['error' => 'Currency conversion rate not available for the selected currencies.']);
            exit; // Stop further execution
        }
    } else {
        echo json_encode(['error' => 'Invalid POST data.']);
        exit; // Stop further execution
    }
} else {
    // Output the currency options as JSON only if the 'get_currencies' parameter is present
    if (isset($_GET['get_currencies'])) {
        echo json_encode(['currencies' => $currencies]);
        exit; // Stop further execution
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Currency Converter</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!-- Combined Bootstrap CSS, JavaScript dependencies, and Font Awesome CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow p-4">
                    <h2 class="text-center mb-4">Currency Converter</h2>
                    <form name="currency-converter-form" method="post" action="currency_converter.php">
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" class="form-control" name="amount" id="amount" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="from-currency">From Currency:</label>
                                <select class="form-control" name="from_currency" id="from-currency" required>
                                    <?php
                                    foreach ($currencies as $currency) {
                                        echo "<option value='$currency'>$currency</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="to-currency">To Currency:</label>
                                <select class="form-control" name="to_currency" id="to-currency" required>
                                    <?php
                                    foreach ($currencies as $currency) {
                                        echo "<option value='$currency'>$currency</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block" id="convert-button">Convert</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow p-4">
                    <h2 class="text-center mb-4">Conversion Result</h2>
                    <div id="conversion-result" class="text-center">
                        <p style="font-size: 24px;">
                            <!-- Display the conversion result from PHP -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
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
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.forms["currency-converter-form"];
        const amountInput = document.getElementById("amount");
        const fromCurrencySelect = document.getElementById("from-currency");
        const toCurrencySelect = document.getElementById("to-currency");
        const resultDiv = document.getElementById("conversion-result");

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const amount = parseFloat(amountInput.value);
            const fromCurrency = fromCurrencySelect.value;
            const toCurrency = toCurrencySelect.value;

            if (isNaN(amount)) {
                alert("Please enter a valid amount.");
                return;
            }

            if (fromCurrency === toCurrency) {
                alert("Please select different currencies for conversion.");
                return;
            }

            fetch("currency_converter.php", {
                method: "POST",
                body: new URLSearchParams({
                    amount: amount,
                    from_currency: fromCurrency,
                    to_currency: toCurrency,
                }),
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        resultDiv.innerHTML = `
                            <p style="font-size: 24px;">
                                <span style="color: green;">${amount} ${fromCurrency}</span>
                                is equal to
                                <span style="color: red;">${data.result}</span> ${toCurrency}
                            </p>
                        `;
                    }
                })
                .catch((error) => {
                    console.error("Error fetching conversion data:", error);
                });
        });
    });
</script>
</body>
</html>
