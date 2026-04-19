<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
// Admins go to admin dashboard
if ($_SESSION['role'] === 'admin') {
  header("Location: dashboard.php");
  exit();
}

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Get user's orders
$orders = $conn->query("SELECT o.*, m.name AS product_name FROM orders o LEFT JOIN menu m ON o.product_id = m.id WHERE o.user_id = $user_id ORDER BY o.created_at DESC");
$order_count = $conn->query("SELECT COUNT(*) as c FROM orders WHERE user_id = $user_id")->fetch_assoc()['c'] ?? 0;

// Get user's reviews
$reviews = $conn->query("SELECT r.*, m.name AS product_name FROM reviews r LEFT JOIN menu m ON r.product_id = m.id WHERE r.user_id = $user_id ORDER BY r.created_at DESC");
$review_count = $conn->query("SELECT COUNT(*) as c FROM reviews WHERE user_id = $user_id")->fetch_assoc()['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account - Herbal Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body { background: #f0f7f0; }
    .user-hero {
      background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 50%, #388e3c 100%);
      padding: 50px 0 80px;
      position: relative;
      overflow: hidden;
    }
    .user-hero::before {
      content: '';
      position: absolute;
      top: -50px; right: -50px;
      width: 300px; height: 300px;
      background: rgba(255,255,255,0.05);
      border-radius: 50%;
    }
    .user-hero::after {
      content: '';
      position: absolute;
      bottom: -80px; left: -40px;
      width: 250px; height: 250px;
      background: rgba(255,255,255,0.04);
      border-radius: 50%;
    }
    .avatar-circle {
      width: 90px; height: 90px;
      background: linear-gradient(135deg, #8bc34a, #4caf50);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 2.5rem; font-weight: 800; color: white;
      border: 4px solid rgba(255,255,255,0.4);
      box-shadow: 0 8px 24px rgba(0,0,0,0.2);
      margin: 0 auto 16px;
      animation: leafSway 4s ease-in-out infinite;
    }
    .content-area { margin-top: -40px; position: relative; z-index: 10; }
    .stat-card {
      background: white;
      border-radius: 16px;
      padding: 24px;
      text-align: center;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      border-top: 4px solid #2e7d32;
      transition: all 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
    .stat-num { font-size: 2.2rem; font-weight: 800; color: #2e7d32; }
    .section-card {
      background: white;
      border-radius: 20px;
      padding: 28px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.07);
      margin-bottom: 24px;
    }
    .section-card h5 { color: #1b5e20; font-weight: 700; margin-bottom: 20px; }
    .status-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .status-pending    { background: #fff8e1; color: #e65100; }
    .status-processing { background: #e3f2fd; color: #1565c0; }
    .status-delivered  { background: #e8f5e9; color: #1b5e20; }
    .status-cancelled  { background: #ffebee; color: #c62828; }
    .star-display { color: #ffc107; font-size: 1rem; }
    .product-btn {
      background: linear-gradient(135deg, #2e7d32, #4caf50);
      color: white; border: none; border-radius: 12px;
      padding: 10px 22px; font-weight: 600; cursor: pointer;
      transition: all 0.3s;
    }
    .product-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(46,125,50,0.35); }
    .empty-state { text-align: center; padding: 40px 20px; color: #9e9e9e; }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: 12px; }
  </style>
</head>
<body>

  <div class="page-loader" id="page-loader">
    <div class="text-center">
      <div class="loader-ring mb-3"></div>
      <p style="color:#2e7d32;font-family:Poppins,sans-serif;font-weight:600;">Loading...</p>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
      <a class="navbar-brand text-white" href="index.php">🌿 Herbal Shop</a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto align-items-center gap-1">
          <li class="nav-item"><a class="nav-link text-white" href="index.php">🏠 Home</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="about.php">🌱 About</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="contact.php">📞 Contact</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="review.php">⭐ Reviews</a></li>
          <li class="nav-item"><a class="nav-link text-white active fw-bold" href="user_dashboard.php">👤 My Account</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="logout.php">🚪 Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <div class="user-hero text-center text-white">
    <div class="container">
      <div class="avatar-circle"><?php echo strtoupper(substr($username,0,1)); ?></div>
      <h2 class="fw-bold mb-1" style="font-family:'Playfair Display',serif;">Welcome, <?php echo htmlspecialchars($username); ?>! 🌿</h2>
      <p class="text-white-50 mb-0">Your personal herbal wellness dashboard</p>
    </div>
  </div>

  <div class="container content-area pb-5">

    <!-- Stats -->
    <div class="row g-4 mb-4">
      <div class="col-6 col-md-3 reveal">
        <div class="stat-card">
          <div style="font-size:2rem;">📦</div>
          <div class="stat-num count-up mt-2" data-target="<?php echo $order_count; ?>">0</div>
          <small class="text-muted fw-600">My Orders</small>
        </div>
      </div>
      <div class="col-6 col-md-3 reveal delay-1">
        <div class="stat-card" style="border-top-color:#ffc107;">
          <div style="font-size:2rem;">⭐</div>
          <div class="stat-num count-up mt-2" style="color:#e6a800;" data-target="<?php echo $review_count; ?>">0</div>
          <small class="text-muted fw-600">My Reviews</small>
        </div>
      </div>
      <div class="col-6 col-md-3 reveal delay-2">
        <div class="stat-card" style="border-top-color:#8bc34a;">
          <div style="font-size:2rem;">🌿</div>
          <div class="stat-num mt-2" style="color:#8bc34a;">🌱</div>
          <small class="text-muted fw-600">Member</small>
        </div>
      </div>
      <div class="col-6 col-md-3 reveal delay-3">
        <div class="stat-card" style="border-top-color:#4caf50;">
          <div style="font-size:2rem;">🔒</div>
          <div class="stat-num mt-2" style="color:#4caf50;font-size:1.2rem;">Secure</div>
          <small class="text-muted fw-600">Account</small>
        </div>
      </div>
    </div>

    <div class="row g-4">

      <!-- My Orders -->
      <div class="col-lg-7 reveal">
        <div class="section-card">
          <h5>📦 My Orders</h5>
          <?php if ($order_count == 0): ?>
            <div class="empty-state">
              <div class="empty-icon">🛒</div>
              <p>You haven't placed any orders yet.</p>
              <a href="index.php" class="product-btn">Start Shopping →</a>
            </div>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead><tr><th>#</th><th>Product</th><th>Qty</th><th>Total</th><th>Status</th></tr></thead>
              <tbody>
                <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                  <td><small>#<?php echo $row['id']; ?></small></td>
                  <td><?php echo htmlspecialchars($row['product_name'] ?? 'N/A'); ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                  <td class="text-success fw-bold">₹<?php echo number_format($row['total'],2); ?></td>
                  <td><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-5">

        <!-- Quick Actions -->
        <div class="section-card reveal delay-1 mb-4">
          <h5>⚡ Quick Actions</h5>
          <div class="d-grid gap-2">
            <a href="index.php" class="product-btn text-center text-decoration-none">🛍️ Browse Products</a>
            <a href="review.php" class="btn btn-outline-success rounded-3 fw-600">⭐ Write a Review</a>
            <a href="contact.php" class="btn btn-outline-secondary rounded-3 fw-600">📞 Contact Support</a>
          </div>
        </div>

        <!-- My Reviews -->
        <div class="section-card reveal delay-2">
          <h5>⭐ My Reviews</h5>
          <?php if ($review_count == 0): ?>
            <div class="empty-state">
              <div class="empty-icon">💬</div>
              <p>You haven't written any reviews yet.</p>
              <a href="review.php" class="btn btn-outline-success rounded-3">Write First Review →</a>
            </div>
          <?php else: ?>
            <?php while ($row = $reviews->fetch_assoc()): ?>
            <div class="p-3 mb-3" style="background:#f0f7f0;border-radius:12px;">
              <div class="d-flex justify-content-between align-items-start mb-1">
                <strong style="color:#1b5e20;"><?php echo htmlspecialchars($row['product_name'] ?? 'Product'); ?></strong>
                <div class="star-display"><?php echo str_repeat('★', $row['rating']) . str_repeat('☆', 5-$row['rating']); ?></div>
              </div>
              <p class="text-muted mb-1" style="font-size:0.88rem;"><?php echo htmlspecialchars($row['comment']); ?></p>
              <small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small>
            </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container text-center">
      <p class="mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem;">© 2025 Herbal Shop. Made with 🌿 & ❤️</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/script.js"></script>
</body>
</html>
