<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ReWear - Sustainable Fashion Exchange</title>
    <link rel="stylesheet" href="LandingPage.css">
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="header-content">
        <div class="logo">ReWear</div>
        <nav>
          <ul class="nav-links">
            <li><a href="#home">Home</a></li>
            <li><a href="#browse">Browse</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="contact-us.php">Contact</a></li>
          </ul>
        </nav>
        
<?php
session_start();
?>
<div class="auth-buttons">
<?php
if (isset($_SESSION['user_id'])) {
    // User is logged in – show profile pic and View Profile
    $profileImage = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default.jpg';
    

    echo '
    <div class="user-info">
      <img src="uploads/' . htmlspecialchars($profileImage) . '" alt="Profile" style="width:40px; height:40px; border-radius:50%;">
      <a href="user-dashboard.php" class="btn btn-primary">View Profile</a>
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
<style>
.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-info img {
  border-radius: 50%;
  width: 40px;
  height: 40px;
  object-fit: cover;
}
</style>

      </div>
    </header>

    <!-- Search Section -->
    <section class="search-section">
      <div class="search-container">
        <div class="search-bar">
          <input
            type="text"
            class="search-input"
            placeholder="Search for clothing items..."
          />
          <span class="search-icon">🔍</span>
        </div>
      </div>
    </section>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Sustainable Fashion Exchange</h1>
        <p>
          Trade unused clothes, reduce waste, and discover your next favorite
          outfit
        </p>
        <div class="hero-buttons">
          <a href="#" class="btn btn-hero primary">Start Swapping</a>
          <a href="#" class="btn btn-hero primary">Browse Items</a>
        </div>
      </div>
    </section>

    <!-- Featured Carousel -->
    <section class="featured-section">
      <div class="section-title">Featured Items</div>
      <div class="carousel-container">
        <div class="carousel-track">
          <div class="carousel-item">
            <div class="item-image">👗</div>
            <div class="item-details">
              <div class="item-title">Vintage Summer Dress</div>
              <div class="item-points">45 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">🧥</div>
            <div class="item-details">
              <div class="item-title">Designer Leather Jacket</div>
              <div class="item-points">120 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">👔</div>
            <div class="item-details">
              <div class="item-title">Business Suit</div>
              <div class="item-points">90 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">👟</div>
            <div class="item-details">
              <div class="item-title">Running Shoes</div>
              <div class="item-points">35 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">👚</div>
            <div class="item-details">
              <div class="item-title">Casual Blouse</div>
              <div class="item-points">25 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">🩳</div>
            <div class="item-details">
              <div class="item-title">Denim Shorts</div>
              <div class="item-points">20 Points</div>
            </div>
          </div>
          <!-- Duplicate items for infinite loop -->
          <div class="carousel-item">
            <div class="item-image">👗</div>
            <div class="item-details">
              <div class="item-title">Vintage Summer Dress</div>
              <div class="item-points">45 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">🧥</div>
            <div class="item-details">
              <div class="item-title">Designer Leather Jacket</div>
              <div class="item-points">120 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">👔</div>
            <div class="item-details">
              <div class="item-title">Business Suit</div>
              <div class="item-points">90 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">👟</div>
            <div class="item-details">
              <div class="item-title">Running Shoes</div>
              <div class="item-points">35 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">👚</div>
            <div class="item-details">
              <div class="item-title">Casual Blouse</div>
              <div class="item-points">25 Points</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="item-image">🩳</div>
            <div class="item-details">
              <div class="item-title">Denim Shorts</div>
              <div class="item-points">20 Points</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
      <div class="categories-container">
        <div class="section-title">Shop by Category</div>
        <div class="categories-grid">
          <div class="category-card">
            <div class="category-icon">👗</div>
            <div class="category-title">Dresses</div>
            <div class="category-count">248 items</div>
          </div>
          <div class="category-card">
            <div class="category-icon">👔</div>
            <div class="category-title">Formal Wear</div>
            <div class="category-count">156 items</div>
          </div>
          <div class="category-card">
            <div class="category-icon">👚</div>
            <div class="category-title">Casual</div>
            <div class="category-count">392 items</div>
          </div>
          <div class="category-card">
            <div class="category-icon">🧥</div>
            <div class="category-title">Outerwear</div>
            <div class="category-count">184 items</div>
          </div>
          <div class="category-card">
            <div class="category-icon">👟</div>
            <div class="category-title">Footwear</div>
            <div class="category-count">267 items</div>
          </div>
          <div class="category-card">
            <div class="category-icon">👜</div>
            <div class="category-title">Accessories</div>
            <div class="category-count">145 items</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Product Listings -->
    <section class="products-section">
      <div class="products-container">
        <div class="section-title">Recent Listings</div>
        <div class="products-grid">
          <div class="product-card">
            <div class="product-image">
              👗
              <div class="product-badge">New</div>
            </div>
            <div class="product-info">
              <div class="product-title">Floral Maxi Dress</div>
              <div class="product-details">
                Size M • Brand: Zara • Excellent condition
              </div>
              <div class="product-points">50 Points</div>
            </div>
          </div>
          <div class="product-card">
            <div class="product-image">
              🧥
              <div class="product-badge">Hot</div>
            </div>
            <div class="product-info">
              <div class="product-title">Vintage Denim Jacket</div>
              <div class="product-details">
                Size L • Brand: Levi's • Good condition
              </div>
              <div class="product-points">75 Points</div>
            </div>
          </div>
          <div class="product-card">
            <div class="product-image">
              👔
              <div class="product-badge">Premium</div>
            </div>
            <div class="product-info">
              <div class="product-title">Designer Blazer</div>
              <div class="product-details">
                Size S • Brand: Hugo Boss • Like new
              </div>
              <div class="product-points">120 Points</div>
            </div>
          </div>
          <div class="product-card">
            <div class="product-image">
              👟
              <div class="product-badge">New</div>
            </div>
            <div class="product-info">
              <div class="product-title">White Sneakers</div>
              <div class="product-details">
                Size 8 • Brand: Adidas • Excellent condition
              </div>
              <div class="product-points">45 Points</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <script>
      // Add click handlers to interactive elements
      document
        .querySelectorAll(".btn, .category-card, .product-card")
        .forEach((element) => {
          element.addEventListener("click", function (e) {
            e.preventDefault();

            // Add click animation
            this.style.transform = "scale(0.95)";
            setTimeout(() => {
              this.style.transform = "";
            }, 150);

            // Show alert for demo purposes
            const elementType = this.classList.contains("btn")
              ? "Button"
              : this.classList.contains("category-card")
              ? "Category"
              : "Product";
            const elementName = this.querySelector(
              ".category-title, .product-title"
            )
              ? this.querySelector(".category-title, .product-title")
                  .textContent
              : this.textContent.trim();

            alert(
              ${elementType} "${elementName}" clicked! This would navigate to the appropriate page.
            );
          });
        });

      // Search functionality
      document
        .querySelector(".search-input")
        .addEventListener("keypress", function (e) {
          if (e.key === "Enter") {
            alert(Searching for: "${this.value}");
          }
        });
    </script>
  </body>
</html>