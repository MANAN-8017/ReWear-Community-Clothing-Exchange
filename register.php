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

// Optional: use $error_msg in your HTML form below if needed
?>


<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ReWear</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="register-container">
        <h2>Create Account</h2>


        <form class="register-form" action="register.php" method="POST">
            <div class="input-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html> -->


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
            <li><a href="contact-us.html">Contact</a></li>
          </ul>
        </nav>
        <a href="landing.html" class="back-to-home">← Back to Home</a>
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

    <!-- <script>
      // Password strength checker
      function checkPasswordStrength(password) {
        const strengthElement = document.getElementById('passwordStrength');
        let strength = 0;
        let message = '';

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        switch (strength) {
          case 0:
          case 1:
            message = 'Weak password';
            strengthElement.className = 'password-strength strength-weak';
            break;
          case 2:
          case 3:
            message = 'Medium strength';
            strengthElement.className = 'password-strength strength-medium';
            break;
          case 4:
          case 5:
            message = 'Strong password';
            strengthElement.className = 'password-strength strength-strong';
            break;
        }

        strengthElement.textContent = password.length > 0 ? message : '';
      }

      // Password input event listener
      document.querySelector('input[type="password"]').addEventListener('input', function() {
        checkPasswordStrength(this.value);
      });

      // Form submission handler
      document.querySelector('.register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const password = this.querySelector('input[type="password"]').value;
        const confirmPassword = this.querySelectorAll('input[type="password"]')[1].value;
        
        // Password confirmation check
        if (password !== confirmPassword) {
          alert('Passwords do not match. Please try again.');
          return;
        }
        
        // Add loading state
        const submitBtn = this.querySelector('.btn-primary');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating Account...';
        submitBtn.disabled = true;
        
        // Simulate registration process
        setTimeout(() => {
          alert('Welcome to ReWear! Your account has been created successfully.');
          submitBtn.textContent = originalText;
          submitBtn.disabled = false;
        }, 2000);
      });

      // Social login handlers
      document.querySelectorAll('.btn-social').forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const provider = this.textContent.trim();
          
          // Add click animation
          this.style.transform = 'scale(0.95)';
          setTimeout(() => {
            this.style.transform = '';
          }, 150);
          
          alert(`${provider} registration would be implemented here`);
        });
      });

      // Input field focus effects
      document.querySelectorAll('.input-field, .select-field').forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.style.transform = '';
        });
      });

      // Header link handlers
      document.querySelectorAll('.header a').forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const linkText = this.textContent.trim();
          
          if (linkText === '← Back to Home') {
            alert('Navigating back to home page...');
          } else {
            alert(`Navigating to ${linkText} page...`);
          }
        });
      });
    </script> -->
  </body>
</html>
