<?php
session_start();
require_once 'config.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Block admins from user shop - admins have products.php
if ($_SESSION['role'] === 'admin') {
  header("Location: products.php");
  exit();
}

// Fetch all products
$products = $conn->query("SELECT * FROM menu ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop - Herbal Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body { background: #f0f7f0; }
    .shop-hero {
      background: linear-gradient(135deg, #1b5e20, #2e7d32, #43a047);
      padding: 48px 0 70px;
      position: relative; overflow: hidden;
    }
    .shop-hero::before {
      content:''; position:absolute; top:-60px; right:-60px;
      width:350px; height:350px;
      background:rgba(255,255,255,0.06); border-radius:50%;
    }
    .content-area { margin-top:-40px; position:relative; z-index:10; }
    .product-card-user {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: all 0.35s ease;
      height: 100%;
    }
    .product-card-user:hover {
      transform: translateY(-8px);
      box-shadow: 0 16px 40px rgba(0,0,0,0.14);
    }
    .product-card-user img {
      width: 100%; height: 200px; object-fit: cover;
      transition: transform 0.4s ease;
    }
    .product-card-user:hover img { transform: scale(1.06); }
    .product-body { padding: 20px; }
    .product-name { font-weight: 700; color: #1b5e20; font-size: 1.05rem; margin-bottom: 6px; }
    .product-price { font-size: 1.3rem; font-weight: 800; color: #2e7d32; }
    .add-btn {
      background: linear-gradient(135deg, #2e7d32, #4caf50);
      color: white; border: none; border-radius: 12px;
      padding: 10px 0; font-weight: 600; width: 100%;
      cursor: pointer; transition: all 0.3s;
    }
    .add-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(46,125,50,0.4); }
    .cart-bar {
      position: fixed; bottom: 24px; right: 24px;
      background: linear-gradient(135deg, #1b5e20, #2e7d32);
      color: white; border-radius: 50px;
      padding: 14px 24px; box-shadow: 0 8px 30px rgba(0,0,0,0.25);
      display: flex; align-items: center; gap: 12px;
      z-index: 1000; animation: slideUp 0.5s ease;
      cursor: pointer;
    }
    @keyframes slideUp { from { transform: translateY(80px); opacity:0; } to { transform: translateY(0); opacity:1; } }
    .cart-badge-fixed {
      background: #ffc107; color: #1b5e20;
      border-radius: 50%; width: 28px; height: 28px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 0.9rem;
    }
    .empty-state { text-align:center; padding: 60px 20px; }
    .empty-state .empty-icon { font-size:4rem; margin-bottom:16px; }
  </style>
</head>
<body>

  <div class="page-loader" id="page-loader">
    <div class="text-center">
      <div class="loader-ring mb-3"></div>
      <p style="color:#2e7d32;font-family:Poppins,sans-serif;font-weight:600;">Loading...</p>
    </div>
  </div>

  <?php include 'navbar.php'; ?>

  <!-- Hero -->
  <div class="shop-hero text-center text-white">
    <div class="container">
      <div class="hero-badge mb-3">🌿 Fresh &amp; Natural</div>
      <h1 class="hero-title" style="font-size:2.5rem;">Herbal Products</h1>
      <p class="hero-subtitle mb-0">Browse our premium collection of natural wellness products</p>
    </div>
  </div>

  <div class="container content-area pb-5">

    <!-- Cart summary bar -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm reveal">
      <div>
        <h6 class="mb-0 text-success fw-bold">🌿 <?php echo $products->num_rows; ?> Products Available</h6>
        <small class="text-muted">Tap a product to add to cart</small>
      </div>
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted">🛒 Cart:</span>
        <span id="cart-total" class="fw-bold text-success">₹0.00</span>
        <span class="badge bg-success rounded-pill" id="cart-count">0</span>
      </div>
    </div>

    <!-- Products Grid -->
    <?php if ($products->num_rows === 0): ?>
      <div class="empty-state bg-white rounded-4">
        <div class="empty-icon">🌿</div>
        <h5 class="text-success">No products yet</h5>
        <p class="text-muted">Products will appear here once added by the admin.</p>
      </div>
    <?php else: ?>
    <div class="row g-4">
      <?php $i=0; while($p = $products->fetch_assoc()): $i++; ?>
      <div class="col-md-4 col-sm-6 reveal" style="transition-delay:<?php echo $i*80; ?>ms;">
        <div class="product-card-user">
          <div style="overflow:hidden;">
            <img src="<?php echo htmlspecialchars($p['image']); ?>"
                 alt="<?php echo htmlspecialchars($p['name']); ?>"
                 onerror="this.src='https://via.placeholder.com/400x200/e8f5e9/2e7d32?text=🌿'">
          </div>
          <div class="product-body">
            <span class="badge-herbal mb-2 d-inline-block" style="font-size:0.72rem;">Natural</span>
            <div class="product-name"><?php echo htmlspecialchars($p['name']); ?></div>
            <div class="product-price mb-3">₹<?php echo number_format($p['price'],2); ?></div>
            <div class="d-flex gap-2">
              <button class="add-btn" onclick="addToCart(<?php echo $p['id']; ?>,'<?php echo addslashes($p['name']); ?>',<?php echo $p['price']; ?>)">
                🛒 Add to Cart
              </button>
              <a href="review.php?product_id=<?php echo $p['id']; ?>" class="btn btn-outline-success px-3" style="border-radius:12px;" title="Review">⭐</a>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
    <?php endif; ?>

  </div>

  <!-- Floating Cart -->
  <div class="cart-bar" id="cart-bar" style="display:none;" onclick="window.scrollTo(0,0)">
    <span>🛒 My Cart</span>
    <span id="cart-total-float">₹0.00</span>
    <div class="cart-badge-fixed" id="cart-count-float">0</div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container text-center">
      <p class="mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem;">© 2025 Herbal Shop. Made with 🌿 & ❤️</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/script.js"></script>
  <script>
    // Override addToCart to also update float cart
    const _origAdd = window.addToCart;
    window.addToCart = function(id, name, price) {
      if (_origAdd) _origAdd(id, name, price);
      // Update float bar
      const count = parseInt(document.getElementById('cart-count').textContent) || 0;
      const total = document.getElementById('cart-total').textContent;
      document.getElementById('cart-count-float').textContent = count;
      document.getElementById('cart-total-float').textContent = total;
      document.getElementById('cart-bar').style.display = 'flex';
    };
  </script>
</body>
</html>
