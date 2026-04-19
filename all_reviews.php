<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php"); exit();
}
if ($_SESSION['role'] !== 'admin') {
  header("Location: user_dashboard.php"); exit();
}

// Handle delete review
if (isset($_GET['delete'])) {
  $del_id = intval($_GET['delete']);
  $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
  $stmt->bind_param("i", $del_id);
  $stmt->execute();
  $stmt->close();
  header("Location: all_reviews.php?deleted=1");
  exit();
}
$success = isset($_GET['deleted']) ? "Review deleted successfully." : '';

// Fetch all reviews with username and product name
$reviews = $conn->query("
  SELECT r.*, u.username, m.name AS product_name
  FROM reviews r
  LEFT JOIN users u ON r.user_id = u.id
  LEFT JOIN menu m ON r.product_id = m.id
  ORDER BY r.created_at DESC
");
$total   = $conn->query("SELECT COUNT(*) as c FROM reviews")->fetch_assoc()['c'] ?? 0;
$avg_raw = $conn->query("SELECT AVG(rating) as avg FROM reviews")->fetch_assoc()['avg'] ?? 0;
$avg     = round($avg_raw, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Reviews - Herbal Shop</title>
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
    .star-display { color: #ffc107; letter-spacing: 2px; }
    .user-avatar-sm { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #2e7d32, #8bc34a); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
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
        <a href="orders.php" class="sidebar-link"><span class="icon">📦</span> Orders</a>
        <a href="users.php" class="sidebar-link"><span class="icon">👥</span> Users</a>
        <div class="nav-label">Store</div>
        <a href="all_reviews.php" class="sidebar-link active"><span class="icon">⭐</span> Reviews</a>
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
          <div class="topbar-title">⭐ Customer Reviews</div>
          <small class="text-muted">All reviews submitted by users</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <span class="badge-herbal"><?php echo date('D, d M Y'); ?></span>
          <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['username']??'A',0,1)); ?></div>
        </div>
      </div>

      <div class="content-area">

        <?php if ($success): ?>
          <div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="animation:fadeInDown 0.5s ease both;">
            <span>✅</span> <?php echo htmlspecialchars($success); ?>
          </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="row g-4 mb-4">
          <div class="col-sm-4 reveal">
            <div class="card p-4 text-center" style="border-top:4px solid #2e7d32;">
              <div style="font-size:2rem;">⭐</div>
              <div class="stat-number text-success count-up mt-1" data-target="<?php echo $total; ?>">0</div>
              <small class="text-muted fw-600">Total Reviews</small>
            </div>
          </div>
          <div class="col-sm-4 reveal delay-1">
            <div class="card p-4 text-center" style="border-top:4px solid #ffc107;">
              <div style="font-size:2rem;">🌟</div>
              <div class="stat-number mt-1" style="color:#e6a800;font-size:2rem;"><?php echo $avg; ?></div>
              <small class="text-muted fw-600">Average Rating</small>
            </div>
          </div>
          <div class="col-sm-4 reveal delay-2">
            <div class="card p-4 text-center" style="border-top:4px solid #8bc34a;">
              <div style="font-size:2rem;">💬</div>
              <div class="stat-number mt-1" style="color:#8bc34a;font-size:1.4rem;">
                <?php echo str_repeat('★', (int)round($avg)) . str_repeat('☆', 5-(int)round($avg)); ?>
              </div>
              <small class="text-muted fw-600">Overall Rating</small>
            </div>
          </div>
        </div>

        <!-- Reviews Table -->
        <div class="card p-4 reveal">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0" style="color:#1b5e20;">⭐ All User Reviews</h5>
            <span class="badge-herbal"><?php echo $total; ?> Reviews</span>
          </div>

          <?php if (!$reviews || $reviews->num_rows === 0): ?>
            <div class="text-center py-5">
              <div style="font-size:4rem;">💬</div>
              <h5 class="text-muted mt-3">No reviews yet</h5>
              <p class="text-muted">Reviews will appear here once users submit them.</p>
            </div>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead>
                <tr>
                  <th>#</th>
                  <th>User</th>
                  <th>Product</th>
                  <th>Rating</th>
                  <th>Review</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $reviews->fetch_assoc()): ?>
                <tr>
                  <td><small class="text-muted">#<?php echo $row['id']; ?></small></td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <div class="user-avatar-sm"><?php echo strtoupper(substr($row['username'] ?? 'U', 0, 1)); ?></div>
                      <span><?php echo htmlspecialchars($row['username'] ?? 'Unknown'); ?></span>
                    </div>
                  </td>
                  <td>
                    <span class="fw-bold text-success"><?php echo htmlspecialchars($row['product_name'] ?? 'Product #'.$row['product_id']); ?></span>
                  </td>
                  <td>
                    <div class="star-display" style="font-size:1rem;">
                      <?php echo str_repeat('★', $row['rating']) . str_repeat('☆', 5-$row['rating']); ?>
                    </div>
                    <small class="text-muted"><?php echo $row['rating']; ?>/5</small>
                  </td>
                  <td style="max-width:200px;">
                    <span style="font-size:0.88rem;"><?php echo htmlspecialchars(substr($row['comment'], 0, 80)) . (strlen($row['comment']) > 80 ? '...' : ''); ?></span>
                  </td>
                  <td><small class="text-muted"><?php echo date('d M Y', strtotime($row['created_at'])); ?></small></td>
                  <td>
                    <a href="all_reviews.php?delete=<?php echo $row['id']; ?>"
                       class="btn btn-sm btn-outline-danger"
                       onclick="return confirm('Delete this review?')">
                      🗑️
                    </a>
                  </td>
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
