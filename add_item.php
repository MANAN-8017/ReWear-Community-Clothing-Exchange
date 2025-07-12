<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $type = trim($_POST['type']);
    $size = trim($_POST['size']);
    $conditions = trim($_POST['conditions']); // Note: this should match your HTML form field name
    $tags = trim($_POST['tags']);
    $user_id = $_SESSION['user_id'];
    $item_id = rand(100000, 999999); // or auto-generated UUID

    if (!$title || !$category || !$size || !$conditions) {
        $error = "All required fields must be filled.";
    } else {
        $stmt = $conn->prepare("INSERT INTO Items (user_id, item_id, title, description, category, type, size, conditions, tags) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssssss", $user_id, $item_id, $title, $description, $category, $type, $size, $conditions, $tags);
        if ($stmt->execute()) {
            $success = "Item listed successfully!";
        } else {
            $error = "Failed to list item. Try again.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Item - ReWear</title>
    <link rel="stylesheet" href="add_item.css">
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
            <li><a href="contact-us.php">Contact</a></li>
          </ul>
        </nav>

        <div class="auth-buttons">
          <?php
if (isset($_SESSION['user_id'])) {
    // User is logged in – show profile pic and View Profile
    $profileImage = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default.jpg'; // fallback image
    echo '
    <div class="user-info">
      <img src="uploads/' . htmlspecialchars($profileImage) . '" alt="Profile" style="width:40px; height:40px; border-radius:50%;">
    </div>';
} else {
    // User not logged in – show login and signup
    echo '
    <a href="login.php" class="btn btn-outline">Login</a>
    <a href="register.php" class="btn btn-primary">Sign Up</a>';
}
?>
        </div>
      </div>
    </header>

    <!-- Display Messages -->
    <?php if ($error): ?>
      <div style="color: red; text-align: center; padding: 10px; background: #ffebee; margin: 10px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div style="color: green; text-align: center; padding: 10px; background: #e8f5e8; margin: 10px;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Page Header -->
    <section class="page-header">
      <h1>Add New Item</h1>
      <p>List your unused clothes and start earning points</p>
    </section>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Single Form for Everything -->
      <form method="POST" action="add_item.php" enctype="multipart/form-data">
        <div class="add-item-container">
          <!-- Image Upload Section -->
          <div class="image-upload-section">
            <h2 class="section-title">Add Images</h2>
            <div class="upload-area" id="uploadArea">
              <div class="upload-icon">📷</div>
              <div class="upload-text">Click to upload images</div>
              <div class="upload-subtext">or drag and drop files here</div>
              <input
                type="file" 
                class="file-input" 
                id="fileInput" 
                name="images[]"
                multiple 
                accept="image/*"
              />
            </div>
            <div class="image-preview" id="imagePreview"></div>
          </div>

          <!-- Product Details Form -->
          <div class="product-details-section">
            <h2 class="section-title">Product Details</h2>
            
            <div class="form-group">
              <label class="form-label" for="title">Title *</label>
              <input 
                type="text" 
                name="title" 
                id="title"
                class="form-input" 
                placeholder="Enter product title" 
                required
                value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
              />
            </div>

            <div class="form-group">
              <label class="form-label" for="description">Description *</label>
              <textarea
                name="description"
                id="description"
                class="form-textarea"
                placeholder="Describe your item, including brand, style, and any notable features..."
                required
              ><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="category">Category *</label>
                <select name="category" id="category" class="form-select" required>
                  <option value="">Select category</option>
                  <option value="dresses" <?php echo (isset($_POST['category']) && $_POST['category'] == 'dresses') ? 'selected' : ''; ?>>Dresses</option>
                  <option value="formal" <?php echo (isset($_POST['category']) && $_POST['category'] == 'formal') ? 'selected' : ''; ?>>Formal Wear</option>
                  <option value="casual" <?php echo (isset($_POST['category']) && $_POST['category'] == 'casual') ? 'selected' : ''; ?>>Casual</option>
                  <option value="outerwear" <?php echo (isset($_POST['category']) && $_POST['category'] == 'outerwear') ? 'selected' : ''; ?>>Outerwear</option>
                  <option value="footwear" <?php echo (isset($_POST['category']) && $_POST['category'] == 'footwear') ? 'selected' : ''; ?>>Footwear</option>
                  <option value="accessories" <?php echo (isset($_POST['category']) && $_POST['category'] == 'accessories') ? 'selected' : ''; ?>>Accessories</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="type">Type</label>
                <select name="type" id="type" class="form-select">
                  <option value="">Select type</option>
                  <option value="shirt" <?php echo (isset($_POST['type']) && $_POST['type'] == 'shirt') ? 'selected' : ''; ?>>Shirt</option>
                  <option value="pants" <?php echo (isset($_POST['type']) && $_POST['type'] == 'pants') ? 'selected' : ''; ?>>Pants</option>
                  <option value="jacket" <?php echo (isset($_POST['type']) && $_POST['type'] == 'jacket') ? 'selected' : ''; ?>>Jacket</option>
                  <option value="dress" <?php echo (isset($_POST['type']) && $_POST['type'] == 'dress') ? 'selected' : ''; ?>>Dress</option>
                  <option value="skirt" <?php echo (isset($_POST['type']) && $_POST['type'] == 'skirt') ? 'selected' : ''; ?>>Skirt</option>
                  <option value="shoes" <?php echo (isset($_POST['type']) && $_POST['type'] == 'shoes') ? 'selected' : ''; ?>>Shoes</option>
                  <option value="bag" <?php echo (isset($_POST['type']) && $_POST['type'] == 'bag') ? 'selected' : ''; ?>>Bag</option>
                  <option value="other" <?php echo (isset($_POST['type']) && $_POST['type'] == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="form-label" for="size">Size *</label>
                <select name="size" id="size" class="form-select" required>
                  <option value="">Select size</option>
                  <option value="xs" <?php echo (isset($_POST['size']) && $_POST['size'] == 'xs') ? 'selected' : ''; ?>>XS</option>
                  <option value="s" <?php echo (isset($_POST['size']) && $_POST['size'] == 's') ? 'selected' : ''; ?>>S</option>
                  <option value="m" <?php echo (isset($_POST['size']) && $_POST['size'] == 'm') ? 'selected' : ''; ?>>M</option>
                  <option value="l" <?php echo (isset($_POST['size']) && $_POST['size'] == 'l') ? 'selected' : ''; ?>>L</option>
                  <option value="xl" <?php echo (isset($_POST['size']) && $_POST['size'] == 'xl') ? 'selected' : ''; ?>>XL</option>
                  <option value="xxl" <?php echo (isset($_POST['size']) && $_POST['size'] == 'xxl') ? 'selected' : ''; ?>>XXL</option>
                  <option value="one-size" <?php echo (isset($_POST['size']) && $_POST['size'] == 'one-size') ? 'selected' : ''; ?>>One Size</option>
                </select>
              </div>

              <div class="form-group">
                <label class="form-label" for="conditions">Condition *</label>
                <select name="conditions" id="conditions" class="form-select" required>
                  <option value="">Select condition</option>
                  <option value="new" <?php echo (isset($_POST['conditions']) && $_POST['conditions'] == 'new') ? 'selected' : ''; ?>>New with tags</option>
                  <option value="like-new" <?php echo (isset($_POST['conditions']) && $_POST['conditions'] == 'like-new') ? 'selected' : ''; ?>>Like new</option>
                  <option value="excellent" <?php echo (isset($_POST['conditions']) && $_POST['conditions'] == 'excellent') ? 'selected' : ''; ?>>Excellent</option>
                  <option value="good" <?php echo (isset($_POST['conditions']) && $_POST['conditions'] == 'good') ? 'selected' : ''; ?>>Good</option>
                  <option value="fair" <?php echo (isset($_POST['conditions']) && $_POST['conditions'] == 'fair') ? 'selected' : ''; ?>>Fair</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="tags">Tags</label>
              <input
                type="text"
                name="tags"
                id="tags"
                class="tags-input"
                placeholder="e.g., vintage, designer, summer, casual"
                value="<?php echo isset($_POST['tags']) ? htmlspecialchars($_POST['tags']) : ''; ?>"
              />
              <div class="tags-help">
                Separate tags with commas to help others find your item
              </div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="submit-section">
          <button type="submit" class="btn-submit" id="submitBtn">
            List Item for Exchange
          </button>
        </div>
      </form>

      <!-- Previous Listings -->
      <div class="previous-listings">
        <h2 class="section-title">Your Previous Listings</h2>
        <div class="listings-grid">
          <?php
          // Fetch user's previous listings
          $stmt = $conn->prepare("SELECT * FROM Items WHERE user_id = ? ORDER BY created_at DESC LIMIT 4");
          $stmt->bind_param("i", $_SESSION['user_id']);
          $stmt->execute();
          $result = $stmt->get_result();
          
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo '
                  <div class="listing-card">
                    <div class="listing-placeholder">📦</div>
                    <div class="listing-title">' . htmlspecialchars($row['title']) . '</div>
                    <div class="listing-points">Category: ' . htmlspecialchars($row['category']) . '</div>
                  </div>';
              }
          } else {
              echo '<p>No previous listings found. This will be your first item!</p>';
          }
          $stmt->close();
          ?>
        </div>
      </div>
    </main>

    <script>
      // Image upload functionality
      const uploadArea = document.getElementById("uploadArea");
      const fileInput = document.getElementById("fileInput");
      const imagePreview = document.getElementById("imagePreview");

      // Click to upload
      uploadArea.addEventListener("click", () => {
        fileInput.click();
      });

      // Drag and drop functionality
      uploadArea.addEventListener("dragover", (e) => {
        e.preventDefault();
        uploadArea.classList.add("dragover");
      });

      uploadArea.addEventListener("dragleave", () => {
        uploadArea.classList.remove("dragover");
      });

      uploadArea.addEventListener("drop", (e) => {
        e.preventDefault();
        uploadArea.classList.remove("dragover");
        const files = Array.from(e.dataTransfer.files);
        fileInput.files = e.dataTransfer.files;
        handleFiles(files);
      });

      // File input change
      fileInput.addEventListener("change", (e) => {
        const files = Array.from(e.target.files);
        handleFiles(files);
      });

      function handleFiles(files) {
        imagePreview.innerHTML = '';
        files.forEach((file) => {
          if (file.type.startsWith("image/")) {
            displayImage(file);
          }
        });
      }

      function displayImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const previewItem = document.createElement("div");
          previewItem.className = "preview-item";
          previewItem.innerHTML = `
            <img src="${e.target.result}" alt="Preview" class="preview-image">
          `;
          imagePreview.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
      }

      // Form submission with loading state
      document.querySelector('form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Adding Item...';
      });

      // Previous listings click handlers
      document.querySelectorAll(".listing-card").forEach((card) => {
        card.addEventListener("click", () => {
          const title = card.querySelector(".listing-title").textContent;
          alert(`Viewing details for: ${title}`);
        });
      });
    </script>
  </body>
</html>