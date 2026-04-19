<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
if ($_SESSION['role'] !== 'admin') {
  header("Location: user_dashboard.php");
  exit();
}

// Fetch stats
$total_products = $conn->query("SELECT COUNT(*) as total FROM menu")->fetch_assoc()['total'] ?? 0;
$total_orders   = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'] ?? 0;
$total_users    = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'] ?? 0;
$total_reviews  = $conn->query("SELECT COUNT(*) as total FROM reviews")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Herbal Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body { background: #f0f4f0; }

    .admin-wrapper {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      flex-shrink: 0;
      background: linear-gradient(180deg, #1b5e20 0%, #2e7d32 60%, #388e3c 100%);
      min-height: 100vh;
      position: fixed;
      top: 0; left: 0;
      z-index: 200;
      box-shadow: 4px 0 20px rgba(0,0,0,0.15);
      display: flex;
      flex-direction: column;
      padding: 0 0 30px;
      transition: all 0.3s ease;
    }

    .sidebar-brand {
      padding: 28px 24px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      text-align: center;
    }

    .sidebar-brand .brand-emoji {
      font-size: 2.5rem;
      display: block;
      animation: leafSway 3s ease-in-out infinite;
    }

    .sidebar-brand h5 {
      color: white;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
      margin: 8px 0 2px;
    }

    .sidebar-brand small {
      color: rgba(255,255,255,0.6);
      font-size: 0.75rem;
    }

    .sidebar-nav {
      padding: 20px 12px;
      flex: 1;
    }

    .sidebar-nav .nav-label {
      color: rgba(255,255,255,0.4);
      font-size: 0.7rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      padding: 12px 12px 6px;
      font-weight: 600;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 16px;
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      border-radius: 10px;
      margin-bottom: 4px;
      transition: all 0.3s ease;
      font-weight: 500;
      font-size: 0.9rem;
    }

    .sidebar-link:hover, .sidebar-link.active {
      background: rgba(255,255,255,0.15);
      color: white;
      transform: translateX(4px);
    }

    .sidebar-link .icon {
      font-size: 1.1rem;
      width: 24px;
      text-align: center;
    }

    .sidebar-footer {
      padding: 16px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    /* Main Content */
    .main-content {
      margin-left: 240px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* Top Bar */
    .topbar {
      background: white;
      padding: 16px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 15px rgba(0,0,0,0.06);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .topbar-title {
      font-weight: 700;
      font-size: 1.2rem;
      color: #1b5e20;
    }

    .admin-avatar {
      width: 42px; height: 42px;
      background: linear-gradient(135deg, #2e7d32, #8bc34a);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      color: white; font-weight: 700; font-size: 1rem;
    }

    .content-area {
      padding: 30px;
      flex: 1;
    }

    /* Stat Cards */
    .stat-card-admin {
      border-radius: 16px;
      border: none;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
      transition: all 0.4s ease;
      cursor: default;
    }

    .stat-card-admin:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 20px 50px rgba(0,0,0,0.14);
    }

    .stat-card-admin .card-body {
      padding: 28px;
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .stat-icon-wrap {
      width: 65px; height: 65px;
      border-radius: 16px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.8rem;
      flex-shrink: 0;
    }

    /* Recent Activity placeholder */
    .activity-item {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 14px 0;
      border-bottom: 1px solid #f1f8e9;
      transition: all 0.3s ease;
    }

    .activity-item:hover { background: #fafff8; border-radius: 8px; padding-left: 8px; }
    .activity-dot {
      width: 10px; height: 10px;
      border-radius: 50%;
      flex-shrink: 0;
    }

    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .main-content { margin-left: 0; }
    }
  </style>
</head>
<body>

  <!-- Page Loader -->
  <div class="page-loader" id="page-loader">
    <div class="text-center">
      <div class="loader-ring mb-3"></div>
      <p style="color:#2e7d32;font-family:Poppins,sans-serif;font-weight:600;">Loading Dashboard...</p>
    </div>
  </div>

  <div class="admin-wrapper">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <span class="brand-emoji">🌿</span>
        <h5>Herbal Shop</h5>
        <small>Admin Panel</small>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="dashboard.php" class="sidebar-link active">
          <span class="icon">📊</span> Dashboard
        </a>
        <a href="products.php" class="sidebar-link">
          <span class="icon">🌿</span> Products
        </a>
        <a href="orders.php" class="sidebar-link">
          <span class="icon">📦</span> Orders
        </a>
        <a href="users.php" class="sidebar-link">
          <span class="icon">👥</span> Users
        </a>
        <div class="nav-label">Store</div>
        <a href="all_reviews.php" class="sidebar-link">
          <span class="icon">⭐</span> Reviews
        </a>
        <a href="index.php" class="sidebar-link">
          <span class="icon">🏠</span> View Shop
        </a>
        <div class="nav-label">Account</div>
        <a href="logout.php" class="sidebar-link">
          <span class="icon">🚪</span> Logout
        </a>
      </nav>
      <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
          <div class="admin-avatar" style="width:36px;height:36px;font-size:0.85rem;">
            <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
          </div>
          <div>
            <div style="color:white;font-size:0.85rem;font-weight:600;"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></div>
            <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;">Administrator</div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">

      <!-- Top Bar -->
      <div class="topbar">
        <div>
          <div class="topbar-title">📊 Dashboard Overview</div>
          <small class="text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <span class="badge-herbal"><?php echo date('D, d M Y'); ?></span>
          <div class="admin-avatar">
            <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
          </div>
        </div>
      </div>

      <!-- Content -->
      <div class="content-area">

        <!-- Stat Cards -->
        <div class="row g-4 mb-4">
          <div class="col-sm-6 col-xl-3 reveal">
            <div class="stat-card-admin card">
              <div class="card-body">
                <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9);">🌿</div>
                <div>
                  <div class="stat-number text-success count-up" data-target="<?php echo $total_products; ?>" style="font-size:2rem;">0</div>
                  <div class="text-muted" style="font-size:0.85rem;font-weight:500;">Total Products</div>
                </div>
              </div>
              <div style="height:4px;background:linear-gradient(90deg,#2e7d32,#8bc34a);"></div>
            </div>
          </div>

          <div class="col-sm-6 col-xl-3 reveal delay-1">
            <div class="stat-card-admin card">
              <div class="card-body">
                <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#e3f2fd,#bbdefb);">📦</div>
                <div>
                  <div class="stat-number count-up" style="color:#1565c0;font-size:2rem;" data-target="<?php echo $total_orders; ?>">0</div>
                  <div class="text-muted" style="font-size:0.85rem;font-weight:500;">Total Orders</div>
                </div>
              </div>
              <div style="height:4px;background:linear-gradient(90deg,#1565c0,#42a5f5);"></div>
            </div>
          </div>

          <div class="col-sm-6 col-xl-3 reveal delay-2">
            <div class="stat-card-admin card">
              <div class="card-body">
                <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#fce4ec,#f8bbd0);">👥</div>
                <div>
                  <div class="stat-number count-up" style="color:#c62828;font-size:2rem;" data-target="<?php echo $total_users; ?>">0</div>
                  <div class="text-muted" style="font-size:0.85rem;font-weight:500;">Registered Users</div>
                </div>
              </div>
              <div style="height:4px;background:linear-gradient(90deg,#c62828,#ef5350);"></div>
            </div>
          </div>

          <div class="col-sm-6 col-xl-3 reveal delay-3">
            <div class="stat-card-admin card">
              <div class="card-body">
                <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#fff8e1,#ffecb3);">⭐</div>
                <div>
                  <div class="stat-number count-up" style="color:#e65100;font-size:2rem;" data-target="<?php echo $total_reviews; ?>">0</div>
                  <div class="text-muted" style="font-size:0.85rem;font-weight:500;">Total Reviews</div>
                </div>
              </div>
              <div style="height:4px;background:linear-gradient(90deg,#e65100,#ffa726);"></div>
            </div>
          </div>
        </div>

        <!-- Quick Actions + Activity -->
        <div class="row g-4">
          <!-- Quick Actions -->
          <div class="col-md-4 reveal-left">
            <div class="card p-4 h-100">
              <h5 class="fw-bold mb-4" style="color:#1b5e20;">⚡ Quick Actions</h5>
              <div class="d-grid gap-3">
                <a href="products.php" class="btn btn-success d-flex align-items-center gap-2">
                  <span>➕</span> Add New Product
                </a>
                <a href="orders.php" class="btn btn-outline-primary d-flex align-items-center gap-2">
                  <span>📦</span> View Orders
                </a>
                <a href="users.php" class="btn btn-outline-danger d-flex align-items-center gap-2">
                  <span>👥</span> Manage Users
                </a>
                <a href="index.php" class="btn btn-outline-success d-flex align-items-center gap-2" target="_blank">
                  <span>👁️</span> View Live Shop
                </a>
              </div>
            </div>
          </div>

          <!-- Shop Summary -->
          <div class="col-md-8 reveal-right">
            <div class="card p-4 h-100">
              <h5 class="fw-bold mb-4" style="color:#1b5e20;">📈 Shop Summary</h5>
              <div class="row g-3">
                <div class="col-6">
                  <div class="p-3 rounded-3 text-center" style="background:#f1f8e9;">
                    <div style="font-size:2rem;">🌿</div>
                    <div class="fw-bold text-success fs-4 count-up" data-target="<?php echo $total_products; ?>">0</div>
                    <small class="text-muted">Products Listed</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-3 rounded-3 text-center" style="background:#e3f2fd;">
                    <div style="font-size:2rem;">📦</div>
                    <div class="fw-bold fs-4 count-up" style="color:#1565c0;" data-target="<?php echo $total_orders; ?>">0</div>
                    <small class="text-muted">Orders Placed</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-3 rounded-3 text-center" style="background:#fce4ec;">
                    <div style="font-size:2rem;">👥</div>
                    <div class="fw-bold fs-4 count-up" style="color:#c62828;" data-target="<?php echo $total_users; ?>">0</div>
                    <small class="text-muted">Active Users</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="p-3 rounded-3 text-center" style="background:#fff8e1;">
                    <div style="font-size:2rem;">⭐</div>
                    <div class="fw-bold fs-4 count-up" style="color:#e65100;" data-target="<?php echo $total_reviews; ?>">0</div>
                    <small class="text-muted">Customer Reviews</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/script.js"></script>
</body>
</html>
