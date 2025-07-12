<?php
session_start();
require 'config.php';

// Check if database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle profile picture upload
if (isset($_POST['upload_profile'])) {
    if (isset($_SESSION['user_id']) && isset($_FILES['profile_image'])) {
        $user_id = $_SESSION['user_id'];
        $file = $_FILES['profile_image'];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($fileExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 2 * 1024 * 1024) { // 2MB limit
                    $newFileName = uniqid('profile_', true) . '.' . $fileExt;
                    $fileDestination = 'uploads/' . $newFileName;

                    if (move_uploaded_file($fileTmp, $fileDestination)) {
                        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                        $stmt->bind_param("si", $newFileName, $user_id);
                        $stmt->execute();
                        $stmt->close();

                        $_SESSION['profile_image'] = $newFileName;
                        header("Location: user-dashboard.php?upload=success");
                        exit();
                    } else {
                        $uploadError = "Failed to move uploaded file.";
                    }
                } else {
                    $uploadError = "File is too large (max 2MB).";
                }
            } else {
                $uploadError = "File upload error.";
            }
        } else {
            $uploadError = "Invalid file type. Only jpg, jpeg, png, gif, webp allowed.";
        }
    }
}

// Delete item handling via POST form
if (isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $user_id = $_SESSION['user_id'] ?? 0;
    
    // Make sure user is logged in
    if ($user_id > 0) {
        // Check if the table name is correct (might be 'Items' with capital I)
        $stmt = $conn->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");
        
        if ($stmt === false) {
            echo "<script>alert('Database error: " . $conn->error . "'); window.location='user-dashboard.php';</script>";
        } else {
            $stmt->bind_param("ii", $delete_id, $user_id);
            if ($stmt->execute()) {
                echo "<script>alert('Item deleted successfully.'); window.location='user-dashboard.php';</script>";
            } else {
                echo "<script>alert('Error deleting item: " . $stmt->error . "'); window.location='user-dashboard.php';</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('Please log in to delete items.'); window.location='login.php';</script>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Dashboard - ReWear</title>
    <link rel="stylesheet" href="user-dashboard.css">
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
            <li><a href="#about" style="color: #667eea">About</a></li>
            <li><a href="contact-us.php">Contact</a></li>
          </ul>
        </nav>
        <div class="user-menu">
          <div class="points-badge">💎 285 Points</div>
          <div class="user-avatar">SJ</div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Dashboard Header -->
      <div class="dashboard-header">
        <h1 class="dashboard-title">Welcome back, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?>!</h1>
        <p class="dashboard-subtitle">
          Manage your listings and track your exchanges
        </p>
      </div>

      <!-- Profile Section -->
      <section class="profile-section">
        <div class="profile-content">
          <div class="profile-avatar">
            👤
            <div class="edit-icon">✏</div>
          </div>
          <div class="profile-info">
            <div class="info-group">
              <span class="info-label">Full Name</span>
              <span class="info-value">
            <?php
              if (isset($_SESSION['user_id'])) {
                  echo "<div class='user-info'>";
                  echo htmlspecialchars($_SESSION['user_name'] ?? 'User') . "</strong></p>";
                  echo "<a href='logout.php' class='btn btn-logout'>Logout</a>";
                  echo "</div>";
              } else {
                  // User not logged in
                  echo "<div class='auth-buttons'>";
                  echo "<a href='login.php' class='btn btn-outline'>Login</a>";
                  echo "<a href='register.php' class='btn btn-primary'>Sign Up</a>";
                  echo "</div>";
              }
            ?>
              </span>
            </div>
            <div class="info-group">
              <span class="info-label">Email</span>
              <span class="info-value">
            <?php
                if (isset($_SESSION['user_id'])) {
                    echo "<div class='user-info'>";
                    echo htmlspecialchars($_SESSION['email'] ?? 'No email') . "</strong></p>";
                    echo "</div>";
                } else {
                    // User not logged in
                    echo "<div class='auth-buttons'>";
                    echo "<a href='login.php' class='btn btn-outline'>Login</a>";
                    echo "<a href='register.php' class='btn btn-primary'>Sign Up</a>";
                    echo "</div>";
                }
            ?>
              </span>
            </div>
            <div class="info-group">
              <span class="info-label">Member Since</span>
              <span class="info-value">March 2023</span>
            </div>
            <div class="info-group">
              <span class="info-label">Location</span>
              <span class="info-value">New York, NY</span>
            </div>
          </div>
          <div class="profile-stats">
            <div class="stat-item">
              <div class="stat-number">285</div>
              <div class="stat-label">Total Points</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">4.9</div>
              <div class="stat-label">Rating</div>
            </div>
            <div class="stat-item">
              <div class="stat-number">47</div>
              <div class="stat-label">Trades</div>
            </div>
          </div>
        </div>
      </section>

      <!-- My Listings Section -->
      <section class="listings-section">
        <div class="section-header">
          <h2 class="section-title">My Listings</h2>
          <a href="add_item.php" class="section-action">+ Add New Listing</a>
        </div>
        <div class="listings-grid">
          <?php
          if (isset($_SESSION['user_id'])) {
              $user_id = $_SESSION['user_id'];
              
              // First, let's check what columns actually exist in your items table
              // You can adjust the column names based on your actual table structure
              $stmt = $conn->prepare("SELECT id, title, description FROM items WHERE user_id = ?");
              
              if ($stmt === false) {
                  // If prepare fails, show error details
                  echo "<div class='error-message'>";
                  echo "Database error: " . $conn->error;
                  echo "</div>";
              } else {
                  $stmt->bind_param("i", $user_id);
                  
                  if ($stmt->execute()) {
                      $result = $stmt->get_result();
                      
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              echo "<div class='listing-card'>";
                              echo "<div class='listing-image'>";
                              echo "👗"; // You can add logic to show different icons based on category
                              echo "<div class='listing-status status-active'>";
                              echo "Active"; // Default status since we removed status column for now
                              echo "</div>";
                              echo "</div>";
                              
                              echo "<div class='listing-info'>";
                              echo "<div class='listing-title'>" . htmlspecialchars($row['title']) . "</div>";
                              echo "<div class='listing-details'>" . htmlspecialchars($row['description']) . "</div>";
                              echo "<div class='listing-points'>50 Points</div>"; // Default points
                              echo "<div class='listing-actions'>";
                              echo "<button class='action-btn btn-edit'>Edit</button>";
                              echo "<form method='POST' action='user-dashboard.php' onsubmit='return confirm(\"Are you sure you want to delete this item?\");' style='display:inline;'>";
                              echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
                              echo "<button type='submit' class='action-btn btn-delete'>Delete</button>";
                              echo "</form>";
                              echo "</div>";
                              echo "</div>";
                              echo "</div>";
                          }
                      } else {
                          echo "<div class='no-items'>";
                          echo "<p>No items listed yet.</p>";
                          echo "<p>Start by adding your first listing!</p>";
                          echo "</div>";
                      }
                      
                      $stmt->close();
                  } else {
                      echo "<div class='error-message'>";
                      echo "Query execution failed: " . $stmt->error;
                      echo "</div>";
                  }
              }
          } else {
              echo "<div class='no-items'>";
              echo "<p>Please log in to view your listings.</p>";
              echo "</div>";
          }
          ?>
        </div>
      </section>

      <!-- My Purchases Section -->
      <section class="purchases-section">
        <div class="section-header">
          <h2 class="section-title">My Purchases</h2>
          <a href="#" class="section-action">View All</a>
        </div>
        <div class="listings-grid">
          <div class="purchase-card">
            <div class="purchase-image">
              👗
              <div class="purchase-date">5 days ago</div>
            </div>
            <div class="purchase-info">
              <div class="purchase-title">Summer Floral Dress</div>
              <div class="purchase-details">
                Size M • H&M • Excellent condition
              </div>
              <div class="purchase-points">45 Points</div>
            </div>
          </div>

          <div class="purchase-card">
            <div class="purchase-image">
              👟
              <div class="purchase-date">2 weeks ago</div>
            </div>
            <div class="purchase-info">
              <div class="purchase-title">White Sneakers</div>
              <div class="purchase-details">Size 8 • Nike • Good condition</div>
              <div class="purchase-points">60 Points</div>
            </div>
          </div>

          <div class="purchase-card">
            <div class="purchase-image">
              👜
              <div class="purchase-date">1 month ago</div>
            </div>
            <div class="purchase-info">
              <div class="purchase-title">Designer Handbag</div>
              <div class="purchase-details">Michael Kors • Like new</div>
              <div class="purchase-points">95 Points</div>
            </div>
          </div>

          <div class="purchase-card">
            <div class="purchase-image">
              🩳
              <div class="purchase-date">1 month ago</div>
            </div>
            <div class="purchase-info">
              <div class="purchase-title">Denim Shorts</div>
              <div class="purchase-details">
                Size S • Levi's • Good condition
              </div>
              <div class="purchase-points">30 Points</div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </body>
</html>