<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Kart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header Section with Navigation -->
    <header>
        <nav class="navbar">
            <a href="index.php"><img src="assets/images/logo.jpg" alt="Logo"></a>
            <div class="nav-buttons">
            <a href="backend/restaurant_login.php"><button class="add-res" id="add-res">Add Restaurant</button></a>
                <a href="backend/login.php"><button class="loginbtn" id="login">Login</button></a>
                <a href="backend/register.php"><button class="signupbtn" id="signupbtn">Sign Up</button></a>
            </div>
        </nav>
    </header>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="over-txt">
            <h1 class="heading">Food Kart</h1>
            <h2 class="heading1">Welcome, Make Your Order</h2>
            <button type="button" class="order-btn">Order Now</button>
        </div>
    </section>

    <!-- Explore Section -->
    <section class="explore-section">
        <h2>Explore Our Delicious Food Options!</h2>
        <p>Order from the best restaurants around.</p>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="footer-content">
            <ul>
                <li><a href="privacy.html">Privacy Policy</a></li>
                <li><a href="terms.html">Terms of Service</a></li>
                <li><a href="contact.html">Contact Us</a></li>
            </ul>
            <div class="social-media">
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    <!-- JavaScript File -->
    <script src="assets/js/main.js"></script>
</body>
</html>
