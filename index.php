<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbal Shop - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>

  <!-- Page Loader -->
  <div class="page-loader" id="page-loader">
    <div class="text-center">
      <div class="loader-ring mb-3"></div>
      <p style="color:#2e7d32;font-family:Poppins,sans-serif;font-weight:600;">Loading...</p>
    </div>
  </div>

  <?php include 'navbar.php'; ?>

  <!-- Hero Section -->
  <section class="hero-section py-5">
    <div class="container py-5">
      <div class="hero-content text-center">
        <div class="hero-badge mb-3">🌿 100% Natural & Organic</div>
        <div class="hero-emoji mb-3">🌿</div>
        <h1 class="hero-title">Welcome to Our<br><span style="color:#c8e6c9;">Herbal Shop</span></h1>
        <p class="hero-subtitle mb-4">Discover the healing power of nature with our premium herbal products.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin'): ?>
          <a href="shop.php" class="btn btn-light text-success fw-bold px-4 py-3">
            🛍️ Shop Now
          </a>
          <?php elseif (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
          <a href="products.php" class="btn btn-light text-success fw-bold px-4 py-3">
            🛍️ Manage Products
          </a>
          <?php else: ?>
          <a href="login.php" class="btn btn-light text-success fw-bold px-4 py-3">
            🛍️ Shop Now
          </a>
          <?php endif; ?>
          <a href="about.php" class="btn btn-outline-light px-4 py-3">
            Learn More →
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Bar -->
  <section class="py-4 bg-white shadow-sm">
    <div class="container">
      <div class="row text-center g-3">
        <div class="col-6 col-md-3 reveal">
          <div class="feature-icon">🌿</div>
          <p class="fw-600 mb-0" style="font-weight:600;">100% Organic</p>
        </div>
        <div class="col-6 col-md-3 reveal delay-1">
          <div class="feature-icon">🚚</div>
          <p class="fw-600 mb-0" style="font-weight:600;">Free Delivery</p>
        </div>
        <div class="col-6 col-md-3 reveal delay-2">
          <div class="feature-icon">🔒</div>
          <p class="fw-600 mb-0" style="font-weight:600;">Secure Payment</p>
        </div>
        <div class="col-6 col-md-3 reveal delay-3">
          <div class="feature-icon">⭐</div>
          <p class="fw-600 mb-0" style="font-weight:600;">Top Rated</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Products Section -->
  <section class="container my-5" id="products">
    <div class="text-center mb-5 reveal">
      <span class="badge-herbal mb-3 d-inline-block">Our Collection</span>
      <h2 class="section-title d-block">Featured Products</h2>
      <p class="text-muted mt-4">Handpicked natural products for your wellbeing</p>
    </div>

    <!-- Cart Summary -->
    <div class="d-flex justify-content-between align-items-center mb-4 reveal">
      <div>
        <h5 class="mb-0 text-success fw-bold">🌿 Herbal Store</h5>
        <small class="text-muted">Natural products for a healthy life</small>
      </div>
      <div class="text-end">
        <div class="d-flex align-items-center gap-2">
          <span>🛒 Cart:</span>
          <span id="cart-total" class="cart-badge">₹0.00</span>
          <span class="badge bg-success rounded-pill" id="cart-count">0</span>
        </div>
      </div>
    </div>

    <div class="row g-4" id="product-list">
      <!-- Products loaded via AJAX -->
      <div class="col-12 text-center py-5" id="loading-products">
        <div class="loader-ring mx-auto mb-3"></div>
        <p class="text-muted">Loading products...</p>
      </div>
    </div>
  </section>

  <!-- Divider -->
  <div class="container"><div class="divider"></div></div>

  <!-- Testimonials Section -->
  <section class="container mb-5">
    <div class="text-center mb-5 reveal">
      <span class="badge-herbal mb-3 d-inline-block">Happy Customers</span>
      <h2 class="section-title d-block">What People Say</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4 reveal delay-1">
        <div class="card p-4 h-100">
          <div class="mb-3" style="color:#ffc107;font-size:1.3rem;">★★★★★</div>
          <p class="text-muted fst-italic">"Amazing quality! The herbal products have truly transformed my health routine."</p>
          <div class="mt-auto d-flex align-items-center gap-3">
            <div style="width:45px;height:45px;background:linear-gradient(135deg,#2e7d32,#8bc34a);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">P</div>
            <div><strong>Priya Sharma</strong><br><small class="text-muted">Regular Customer</small></div>
          </div>
        </div>
      </div>
      <div class="col-md-4 reveal delay-2">
        <div class="card p-4 h-100">
          <div class="mb-3" style="color:#ffc107;font-size:1.3rem;">★★★★★</div>
          <p class="text-muted fst-italic">"Best herbal shop online! Fast delivery and products are exactly as described."</p>
          <div class="mt-auto d-flex align-items-center gap-3">
            <div style="width:45px;height:45px;background:linear-gradient(135deg,#1b5e20,#4caf50);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">R</div>
            <div><strong>Rahul Kumar</strong><br><small class="text-muted">Loyal Member</small></div>
          </div>
        </div>
      </div>
      <div class="col-md-4 reveal delay-3">
        <div class="card p-4 h-100">
          <div class="mb-3" style="color:#ffc107;font-size:1.3rem;">★★★★☆</div>
          <p class="text-muted fst-italic">"Great selection of organic products. Will definitely order again!"</p>
          <div class="mt-auto d-flex align-items-center gap-3">
            <div style="width:45px;height:45px;background:linear-gradient(135deg,#388e3c,#66bb6a);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">A</div>
            <div><strong>Anita Patel</strong><br><small class="text-muted">New Customer</small></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="row g-4 mb-4">
        <div class="col-md-4">
          <h5 class="text-white fw-bold mb-3">🌿 Herbal Shop</h5>
          <p style="color:rgba(255,255,255,0.7);">Bringing nature's best to your doorstep. 100% organic, handpicked herbal products.</p>
        </div>
        <div class="col-md-4">
          <h6 class="text-white fw-bold mb-3">Quick Links</h6>
          <ul class="list-unstyled">
            <li><a href="index.php">🏠 Home</a></li>
            <li><a href="about.php">🌱 About</a></li>
            <li><a href="contact.php">📞 Contact</a></li>
            <li><a href="review.php">⭐ Reviews</a></li>
          </ul>
        </div>
        <div class="col-md-4">
          <h6 class="text-white fw-bold mb-3">Contact Info</h6>
          <p style="color:rgba(255,255,255,0.7);">📍 123 Green Street, Nature City</p>
          <p style="color:rgba(255,255,255,0.7);">📧 info@herbalshop.com</p>
          <p style="color:rgba(255,255,255,0.7);">📞 +91 98765 43210</p>
        </div>
      </div>
      <hr style="border-color:rgba(255,255,255,0.2);">
      <p class="text-center mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem;">© 2025 Herbal Shop. All rights reserved. Made with 🌿 & ❤️</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/script.js"></script>
  <script>
    $(document).ready(function () {
      $.ajax({
        url: 'get_products.php',
        method: 'GET',
        success: function (data) {
          try {
            const products = JSON.parse(data);
            const productList = $('#product-list');
            $('#loading-products').remove();

            if (products.length === 0) {
              productList.html('<div class="col-12 text-center py-5"><p class="text-muted">No products available yet.</p></div>');
              return;
            }

            products.forEach((product, index) => {
              const delay = index * 100;
              productList.append(`
                <div class="col-md-4 col-sm-6 reveal" style="transition-delay:${delay}ms;">
                  <div class="product-card card h-100">
                    <div style="overflow:hidden;border-radius:16px 16px 0 0;">
                      <img src="${product.image}" class="card-img-top" alt="${product.name}"
                        onerror="this.src='https://via.placeholder.com/400x220/e8f5e9/2e7d32?text=🌿+${encodeURIComponent(product.name)}'">
                    </div>
                    <div class="card-body d-flex flex-column">
                      <span class="badge-herbal mb-2 d-inline-block" style="font-size:0.75rem;">Herbal</span>
                      <h5 class="card-title">${product.name}</h5>
                      <p class="card-text">₹${parseFloat(product.price).toFixed(2)}</p>
                      <div class="mt-auto d-flex gap-2">
                        <button class="btn btn-success flex-grow-1"
                          onclick="addToCart(${product.id}, '${product.name.replace(/'/g,"\\'")}', ${product.price})">
                          🛒 Add to Cart
                        </button>
                        <a href="review.php?product_id=${product.id}" class="btn btn-outline-success">⭐</a>
                      </div>
                    </div>
                  </div>
                </div>
              `);
            });

            revealOnScroll();
          } catch (e) {
            $('#loading-products').html('<p class="text-muted">Could not load products.</p>');
          }
        },
        error: function () {
          $('#loading-products').html('<div class="col-12 text-center"><p class="text-danger">Failed to load products. Please try again.</p></div>');
        }
      });
    });
  </script>
</body>
</html>
