<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - ReWear</title>
    <link rel="stylesheet" href="contact-us.css">
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <div class="logo">ReWear</div>
        <nav>
          <ul class="nav-links">
            <li><a href="LandingPage.php">Home</a></li>
            <li><a href="#browse">Browse</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="contact-us.html" class="active">Contact</a></li>
          </ul>
        </nav>
        <div class="auth-buttons">
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // User is logged in – show profile pic and View Profile
    $profileImage = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default.jpg';
    

    echo '
    <div class="user-info">
      <a href="user-dashboard.php" class="btn btn-primary">View Profile</a>
    </div>';
} else {
    echo '
    <a href="login.php" class="btn btn-outline">Login</a>
    <a href="register.php" class="btn btn-primary">Sign Up</a>';
}
?>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Get in Touch</h1>
        <p>
          We'd love to hear from you! Whether you have questions, suggestions,
          or need help with your sustainable fashion journey.
        </p>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
      <div class="contact-container">
        <div class="contact-grid">
          <!-- Contact Information -->
          <div class="contact-info">
            <div class="contact-info-content">
              <h2>Let's Connect</h2>
              <p>
                Have questions about our sustainable fashion exchange? Need help
                with a trade? Or want to suggest new features? We're here to
                help!
              </p>

              <div class="contact-item">
                <div class="contact-icon">📧</div>
                <div class="contact-details">
                  <h3>Email Us</h3>
                  <p>support@rewear.com</p>
                </div>
              </div>

              <div class="contact-item">
                <div class="contact-icon">📱</div>
                <div class="contact-details">
                  <h3>Call Us</h3>
                  <p>+1 (555) 123-4567</p>
                </div>
              </div>

              <div class="contact-item">
                <div class="contact-icon">💬</div>
                <div class="contact-details">
                  <h3>Live Chat</h3>
                  <p>Available 9 AM - 6 PM EST</p>
                </div>
              </div>

              <div class="contact-item">
                <div class="contact-icon">🌍</div>
                <div class="contact-details">
                  <h3>Follow Us</h3>
                  <p>@ReWearFashion on all platforms</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="contact-form">
            <div class="success-message" id="successMessage">
              ✅ Thank you for your message! We'll get back to you within 24
              hours.
            </div>

            <h2 class="form-title">Send us a Message</h2>

            <form id="contactForm">
              <div class="form-row">
                <div class="form-group">
                  <label class="form-label" for="firstName">First Name</label>
                  <input
                    type="text"
                    class="form-input"
                    id="firstName"
                    name="firstName"
                    required
                  />
                </div>
                <div class="form-group">
                  <label class="form-label" for="lastName">Last Name</label>
                  <input
                    type="text"
                    class="form-input"
                    id="lastName"
                    name="lastName"
                    required
                  />
                </div>
              </div>

              <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input
                  type="email"
                  class="form-input"
                  id="email"
                  name="email"
                  required
                />
              </div>

              <div class="form-group">
                <label class="form-label" for="subject">Subject</label>
                <select
                  class="form-select"
                  id="subject"
                  name="subject"
                  required
                >
                  <option value="">Select a topic</option>
                  <option value="general">General Inquiry</option>
                  <option value="trading">Trading Questions</option>
                  <option value="technical">Technical Support</option>
                  <option value="feedback">Feedback & Suggestions</option>
                  <option value="partnership">Partnership Opportunities</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="message">Message</label>
                <textarea
                  class="form-textarea"
                  id="message"
                  name="message"
                  placeholder="Tell us more about your inquiry..."
                  required
                ></textarea>
              </div>

              <div class="form-checkbox">
                <input
                  type="checkbox"
                  class="checkbox-input"
                  id="newsletter"
                  name="newsletter"
                />
                <label class="checkbox-label" for="newsletter">
                  I'd like to receive updates about new features and sustainable
                  fashion tips
                </label>
              </div>

              <button type="submit" class="submit-btn">Send Message</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <script>
      // Form submission handling
      document
        .getElementById("contactForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          // Get form data
          const formData = new FormData(this);
          const data = Object.fromEntries(formData);

          // Simulate form submission
          console.log("Form submitted with data:", data);

          // Show success message
          const successMessage = document.getElementById("successMessage");
          successMessage.style.display = "block";

          // Reset form
          this.reset();

          // Hide success message after 5 seconds
          setTimeout(() => {
            successMessage.style.display = "none";
          }, 5000);

          // Scroll to success message
          successMessage.scrollIntoView({ behavior: "smooth" });
        });

      // Add input animations
      const inputs = document.querySelectorAll(
        ".form-input, .form-textarea, .form-select"
      );
      inputs.forEach((input) => {
        input.addEventListener("focus", function () {
          this.parentElement.style.transform = "translateY(-2px)";
        });

        input.addEventListener("blur", function () {
          this.parentElement.style.transform = "translateY(0)";
        });
      });

      // Contact item hover effects
      const contactItems = document.querySelectorAll(".contact-item");
      contactItems.forEach((item) => {
        item.addEventListener("mouseenter", function () {
          this.style.backgroundColor = "rgba(102, 126, 234, 0.1)";
        });

        item.addEventListener("mouseleave", function () {
          this.style.backgroundColor = "rgba(255, 255, 255, 0.8)";
        });
      });
    </script>
  </body>
</html>
