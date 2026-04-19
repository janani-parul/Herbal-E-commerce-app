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

$orders = $conn->query("SELECT o.*, u.username FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
$total  = $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'] ?? 0;
$pending = $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='pending'")->fetch_assoc()['c'] ?? 0;
$delivered = $conn->query("SELECT COUNT(*) as c FROM orders WHERE status='delivered'")->fetch_assoc()['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders - Herbal Shop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    body { background: #f0f4f0; }
    .admin-wrapper { display: flex; min-height: 100vh; }
    .sidebar {
      width: 240px; flex-shrink: 0;
      background: linear-gradient(180deg, #1b5e20 0%, #2e7d32 60%, #388e3c 100%);
      min-height: 100vh; position: fixed; top: 0; left: 0; z-index: 200;
      box-shadow: 4px 0 20px rgba(0,0,0,0.15);
      display: flex; flex-direction: column; padding: 0 0 30px;
    }
    .sidebar-brand { padding: 28px 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); text-align: center; }
    .sidebar-brand .brand-emoji { font-size: 2.5rem; display: block; animation: leafSway 3s ease-in-out infinite; }
    .sidebar-brand h5 { color: white; font-family: 'Playfair Display', serif; font-weight: 700; margin: 8px 0 2px; }
    .sidebar-brand small { color: rgba(255,255,255,0.6); font-size: 0.75rem; }
    .sidebar-nav { padding: 20px 12px; flex: 1; }
    .sidebar-nav .nav-label { color: rgba(255,255,255,0.4); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; padding: 12px 12px 6px; font-weight: 600; }
    .sidebar-link { display: flex; align-items: center; gap: 10px; padding: 12px 16px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 10px; margin-bottom: 4px; transition: all 0.3s ease; font-weight: 500; font-size: 0.9rem; }
    .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,0.15); color: white; transform: translateX(4px); }
    .sidebar-link .icon { font-size: 1.1rem; width: 24px; text-align: center; }
    .sidebar-footer { padding: 16px; border-top: 1px solid rgba(255,255,255,0.1); }
    .main-content { margin-left: 240px; flex: 1; display: flex; flex-direction: column; }
    .topbar { background: white; padding: 16px 30px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 15px rgba(0,0,0,0.06); position: sticky; top: 0; z-index: 100; }
    .topbar-title { font-weight: 700; font-size: 1.2rem; color: #1b5e20; }
    .admin-avatar { width: 42px; height: 42px; background: linear-gradient(135deg, #2e7d32, #8bc34a); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1rem; }
    .content-area { padding: 30px; flex: 1; }
    .status-badge { padding: 5px 14px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; text-transform: capitalize; }
    .status-pending    { background: #fff8e1; color: #e65100; }
    .status-processing { background: #e3f2fd; color: #1565c0; }
    .status-delivered  { background: #e8f5e9; color: #1b5e20; }
    .status-cancelled  { background: #ffebee; color: #c62828; }
    @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-content { margin-left: 0; } }
  </style>
</head>
<body>

  <div class="page-loader" id="page-loader">
    <div class="text-center">
      <div class="loader-ring mb-3"></div>
      <p style="color:#2e7d32;font-family:Poppins,sans-serif;font-weight:600;">Loading...</p>
    </div>
  </div>

  <div class="admin-wrapper">
    <aside class="sidebar">
      <div class="sidebar-brand">
        <span class="brand-emoji">🌿</span>
        <h5>Herbal Shop</h5>
        <small>Admin Panel</small>
      </div>
      <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="dashboard.php" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
        <a href="products.php" class="sidebar-link"><span class="icon">🌿</span> Products</a>
        <a href="orders.php" class="sidebar-link active"><span class="icon">📦</span> Orders</a>
        <a href="users.php" class="sidebar-link"><span class="icon">👥</span> Users</a>
        <div class="nav-label">Store</div>
        <a href="all_reviews.php" class="sidebar-link"><span class="icon">⭐</span> Reviews</a>
        <a href="index.php" class="sidebar-link"><span class="icon">🏠</span> View Shop</a>
        <div class="nav-label">Account</div>
        <a href="logout.php" class="sidebar-link"><span class="icon">🚪</span> Logout</a>
      </nav>
      <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
          <div class="admin-avatar" style="width:36px;height:36px;font-size:0.85rem;"><?php echo strtoupper(substr($_SESSION['username']??'A',0,1)); ?></div>
          <div>
            <div style="color:white;font-size:0.85rem;font-weight:600;"><?php echo htmlspecialchars($_SESSION['username']??'Admin'); ?></div>
            <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;">Administrator</div>
          </div>
        </div>
      </div>
    </aside>

    <div class="main-content">
      <div class="topbar">
        <div>
          <div class="topbar-title">📦 Orders Management</div>
          <small class="text-muted">View and manage all customer orders</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <span class="badge-herbal"><?php echo date('D, d M Y'); ?></span>
          <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['username']??'A',0,1)); ?></div>
        </div>
      </div>

      <div class="content-area">

        <!-- Stats Row -->
        <div class="row g-4 mb-4">
          <div class="col-sm-4 reveal">
            <div class="card p-4 text-center" style="border-top:4px solid #2e7d32;">
              <div style="font-size:2rem;">📦</div>
              <div class="stat-number text-success count-up mt-1" data-target="<?php echo $total; ?>">0</div>
              <small class="text-muted fw-600">Total Orders</small>
            </div>
          </div>
          <div class="col-sm-4 reveal delay-1">
            <div class="card p-4 text-center" style="border-top:4px solid #e65100;">
              <div style="font-size:2rem;">⏳</div>
              <div class="stat-number count-up mt-1" style="color:#e65100;" data-target="<?php echo $pending; ?>">0</div>
              <small class="text-muted fw-600">Pending</small>
            </div>
          </div>
          <div class="col-sm-4 reveal delay-2">
            <div class="card p-4 text-center" style="border-top:4px solid #1565c0;">
              <div style="font-size:2rem;">✅</div>
              <div class="stat-number count-up mt-1" style="color:#1565c0;" data-target="<?php echo $delivered; ?>">0</div>
              <small class="text-muted fw-600">Delivered</small>
            </div>
          </div>
        </div>

        <!-- Orders Table -->
        <div class="card p-4 reveal">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0" style="color:#1b5e20;">📋 All Orders</h5>
            <span class="badge-herbal"><?php echo $total; ?> Total</span>
          </div>

          <?php if (!$orders || $orders->num_rows === 0): ?>
            <div class="text-center py-5">
              <div style="font-size:4rem;">📭</div>
              <h5 class="text-muted mt-3">No orders yet</h5>
              <p class="text-muted">Orders will appear here when customers place them.</p>
            </div>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Product ID</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $orders->fetch_assoc()): ?>
                <tr>
                  <td><strong>#<?php echo $row['id']; ?></strong></td>
                  <td><?php echo htmlspecialchars($row['username'] ?? 'Guest'); ?></td>
                  <td><?php echo $row['product_id']; ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                  <td class="fw-bold text-success">₹<?php echo number_format($row['total'], 2); ?></td>
                  <td>
                    <span class="status-badge status-<?php echo $row['status']; ?>">
                      <?php echo ucfirst($row['status']); ?>
                    </span>
                  </td>
                  <td><small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small></td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/script.js"></script>
</body>
</html>
