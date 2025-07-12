<?php
session_start();
require 'config.php';

$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_msg = "Please fill in all fields.";
    } else {
        $sql = "SELECT * FROM Users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) === 1) {
                $user = mysqli_fetch_assoc($result);
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['profile_image'] = $user['profile_image'];
                    header("Location: LandingPage.php");
                    exit();
                } else {
                    $error_msg = "Incorrect password.";
                }
            } else {
                $error_msg = "No user found with that email.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error_msg = "Database error.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ReWear</title>
  <link rel="stylesheet" href="login.css">
  <style>
    .error-msg {
      color: red;
      margin-top: 10px;
      font-weight: bold;
      align-self: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="header-content">
      <a href="#" class="logo">ReWear</a>
      <nav>
        <ul class="nav-links">
          <li><a href="LandingPage.php">Home</a></li>
          <li><a href="#browse">Browse</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="contact-us.html">Contact</a></li>
        </ul>
      </nav>
      <a href="LandingPage.html" class="back-to-home">← Back to Home</a>
    </div>
  </header>

  <div class="login-container">
    <div class="welcome-section">
      <div class="welcome-content">
        <div class="welcome-icon">👋</div>
        <h1 class="welcome-title">Welcome Back!</h1>
        <p class="welcome-subtitle">Ready to continue your sustainable fashion journey?</p>
      </div>
    </div>

    <div class="login-form-section">
      <div class="form-header">
        <h2 class="form-title">Log In</h2>
        <p class="form-subtitle">Enter your credentials to access your account</p>
      </div>
      
      <form class="login-form" action="login.php" method="POST">
        <div class="input-group">
          <label class="input-label">Email Address</label>
          <input type="email" name="email" class="input-field" placeholder="Enter your email" required />
          <span class="input-icon">📧</span>
        </div>

        <div class="input-group">
          <label class="input-label">Password</label>
          <input type="password" name="password" class="input-field" placeholder="Enter your password" required />
          <span class="input-icon">🔒</span>
        </div>

        <div class="forgot-password">
          <a href="#">Forgot your password?</a>
        </div>

        <button type="submit" class="btn btn-primary">Log In</button>
      </form>

      <?php if (!empty($error_msg)): ?>
        <div class="error-msg"><?= htmlspecialchars($error_msg) ?></div>
      <?php endif; ?>

      <div class="signup-prompt">
        Don't have an account? <a href="register.php">Sign up here</a>
      </div>
    </div>
  </div>
</body>
</html>
