<?php
session_start();
require 'config.php';

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Grab and sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // 1. Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error_msg = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Invalid email format.";
    } elseif ($password !== $confirmPassword) {
        $error_msg = "Passwords do not match.";
    } else {
        // 2. Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error_msg = "Email already registered.";
        } else {
            // 3. All good, proceed to insert
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $user_id = rand(100000, 999999);

            $insert_stmt = $conn->prepare("INSERT INTO Users (user_id, name, email, password_hash) VALUES (?, ?, ?, ?)");
            $insert_stmt->bind_param("isss", $user_id, $name, $email, $passwordHash);

            if ($insert_stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error_msg = "Failed to register user.";
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Join ReWear - Sustainable Fashion Exchange</title>
    <link rel="stylesheet" href="register.css">
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <a href="#" class="logo">ReWear</a>
        <nav>
          <ul class="nav-links">
            <li><a href="LandingPage.php">Home</a></li>
            <li><a href="#browse">Browse</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="contact-us.php">Contact</a></li>
          </ul>
        </nav>
        <a href="LandingPage.php  " class="back-to-home">← Back to Home</a>
      </div>
    </header>

    <!-- Register Container -->
    <div class="register-container">
      <!-- Welcome Section -->
      <div class="welcome-section">
        <div class="welcome-content">
          <div class="welcome-icon">🌱</div>
          <h1 class="welcome-title">Join ReWear</h1>
          <p class="welcome-subtitle">Start your sustainable fashion journey today</p>
          <ul class="welcome-features">
            <li>Trade clothes with eco-conscious community</li>
            <li>Earn points for sustainable choices</li>
            <li>Reduce fashion waste impact</li>
            <li>Discover unique pre-loved items</li>
            <li>Track your environmental contribution</li>
          </ul>
          <div class="impact-stats">
            <div class="stat-item">
              <span>Community Members:</span>
              <span class="stat-value">12,847</span>
            </div>
            <div class="stat-item">
              <span>Items Exchanged:</span>
              <span class="stat-value">45,230</span>
            </div>
            <div class="stat-item">
              <span>CO2 Saved:</span>
              <span class="stat-value">2.3 tons</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Register Form Section -->
      <div class="register-form-section">
        <div class="form-header">
          <h2 class="form-title">Create Account</h2>
          <p class="form-subtitle">Join thousands making fashion more sustainable</p>
        </div>

        <form class="register-form" action="register.php" method="POST">
          <div class="form-row">
            <div class="input-group">
              <label class="input-label">Name</label>
              <input type="text" class="input-field" placeholder="Enter full name" name="name" required>
              <span class="input-icon">👤</span>
            </div>
          </div>

          <div class="input-group">
            <label class="input-label">Email Address</label>
            <input type="email" class="input-field" placeholder="Enter your email" name="email" required>
            <span class="input-icon">📧</span>
          </div>

          <div class="input-group">
            <label class="input-label">Password</label>
            <input type="password" class="input-field" placeholder="Create password" name="password" required>
            <span class="input-icon">🔒</span>
            <div class="password-strength" id="passwordStrength"></div>
          </div>

          <div class="input-group">
            <label class="input-label">Confirm Password</label>
            <input type="password" class="input-field" placeholder="Confirm password" name="confirm_password" required>
            <span class="input-icon">🔒</span>
          </div>

         

          <div class="checkbox-group">
            <input type="checkbox" class="checkbox-input" id="terms" required>
            <label class="checkbox-label" for="terms">
              I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
            </label>
          </div>

          <div class="checkbox-group">
            <input type="checkbox" class="checkbox-input" id="newsletter">
            <label class="checkbox-label" for="newsletter">
              Send me updates about sustainable fashion and community events
            </label>
          </div>

          <button type="submit" class="btn btn-primary">Create Account</button>
        </form>

        <?php if (!empty($error_msg)): ?>
            <div class="error-msg" style="color:red; margin-bottom:10px;"> <?php echo $error_msg; ?> </div>
        <?php endif; ?>

        <div class="login-prompt">
          Already have an account? <a href="login.php">Log in here</a>
        </div>
      </div>
    </div>
  </body>
</html>