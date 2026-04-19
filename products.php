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

$success = '';
$error = '';

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
  $name  = trim($_POST['name']);
  $price = floatval($_POST['price']);
  $image = trim($_POST['image']);

  if (empty($name) || $price <= 0) {
    $error = "Please provide a valid product name and price.";
  } else {
    $stmt = $conn->prepare("INSERT INTO menu (name, price, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $image);
    if ($stmt->execute()) {
      $success = "Product '{$name}' added successfully! 🌿";
    } else {
      $error = "Failed to add product. Please try again.";
    }
    $stmt->close();
  }
}

// Handle Delete
if (isset($_GET['delete'])) {
  $del_id = intval($_GET['delete']);
  $stmt = $conn->prepare("DELETE FROM menu WHERE id = ?");
  $stmt->bind_param("i", $del_id);
  $stmt->execute();
  $stmt->close();
  header("Location: products.php?deleted=1");
  exit();
}

if (isset($_GET['deleted'])) {
  $success = "Product deleted successfully.";
}

// Fetch products
$products = $conn->query("SELECT * FROM menu ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Products - Herbal Shop</title>
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

    .product-img-preview {
      width: 60px; height: 60px;
      border-radius: 10px;
      object-fit: cover;
      border: 2px solid #e8f5e9;
      transition: all 0.3s ease;
    }
    .product-img-preview:hover { transform: scale(1.5); border-color: #2e7d32; box-shadow: 0 8px 20px rgba(0,0,0,0.15); }

    tr { animation: fadeInUp 0.4s ease both; }

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
      <p style="color:#2e7d32;font-family:Poppins,sans-serif;font-weight:600;">Loading...</p>
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
        <a href="dashboard.php" class="sidebar-link"><span class="icon">📊</span> Dashboard</a>
        <a href="products.php" class="sidebar-link active"><span class="icon">🌿</span> Products</a>
        <a href="orders.php" class="sidebar-link"><span class="icon">📦</span> Orders</a>
        <a href="users.php" class="sidebar-link"><span class="icon">👥</span> Users</a>
        <div class="nav-label">Store</div>
        <a href="all_reviews.php" class="sidebar-link"><span class="icon">⭐</span> Reviews</a>
        <a href="index.php" class="sidebar-link"><span class="icon">🏠</span> View Shop</a>
        <div class="nav-label">Account</div>
        <a href="logout.php" class="sidebar-link"><span class="icon">🚪</span> Logout</a>
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
          <div class="topbar-title">🌿 Manage Products</div>
          <small class="text-muted">Add, view, and remove herbal products</small>
        </div>
        <div class="d-flex align-items-center gap-3">
          <span class="badge-herbal"><?php echo date('D, d M Y'); ?></span>
          <div class="admin-avatar"><?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?></div>
        </div>
      </div>

      <!-- Content -->
      <div class="content-area">

        <?php if ($success): ?>
          <div class="alert alert-success d-flex align-items-center gap-2 reveal" style="animation:fadeInDown 0.5s ease both;">
            <span>✅</span> <?php echo htmlspecialchars($success); ?>
          </div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2">
            <span>⚠️</span> <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <div class="row g-4">
          <!-- Add Product Form -->
          <div class="col-lg-4 reveal-left">
            <div class="card p-4 h-100">
              <h5 class="fw-bold mb-4" style="color:#1b5e20;">➕ Add New Product</h5>
              <form method="POST" id="add-product-form">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                  <label class="form-label">🌿 Product Name</label>
                  <input type="text" class="form-control" name="name" placeholder="e.g. Aloe Vera Gel" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">💰 Price (₹)</label>
                  <input type="number" class="form-control" name="price" placeholder="e.g. 299" min="1" step="0.01" required>
                </div>
                <div class="mb-4">
                  <label class="form-label">🖼️ Image URL</label>
                  <input type="url" class="form-control" name="image" id="image-url-input" placeholder="https://example.com/image.jpg">
                  <!-- Image Preview -->
                  <div class="mt-2 text-center" id="img-preview-wrap" style="display:none;">
                    <img id="img-preview" src="" alt="Preview" style="max-height:120px;border-radius:10px;border:2px solid #c8e6c9;">
                  </div>
                </div>
                <button type="submit" class="btn btn-success w-100 py-3" id="add-btn">
                  ➕ Add Product
                </button>
              </form>
            </div>
          </div>

          <!-- Product Table -->
          <div class="col-lg-8 reveal-right">
            <div class="card p-4">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0" style="color:#1b5e20;">📋 All Products</h5>
                <span class="badge-herbal">
                  <?php echo $products->num_rows; ?> Products
                </span>
              </div>

              <?php if ($products->num_rows === 0): ?>
                <div class="text-center py-5">
                  <div style="font-size:3rem;">🌿</div>
                  <p class="text-muted mt-2">No products yet. Add your first product!</p>
                </div>
              <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover align-middle">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Image</th>
                      <th>Product Name</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                      <td><span class="badge bg-light text-dark fw-bold">#<?php echo $row['id']; ?></span></td>
                      <td>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>"
                          class="product-img-preview"
                          onerror="this.src='https://via.placeholder.com/60x60/e8f5e9/2e7d32?text=🌿'"
                          alt="<?php echo htmlspecialchars($row['name']); ?>">
                      </td>
                      <td>
                        <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                      </td>
                      <td>
                        <span class="fw-bold text-success">₹<?php echo number_format($row['price'], 2); ?></span>
                      </td>
                      <td>
                        <a href="products.php?delete=<?php echo $row['id']; ?>"
                          class="btn btn-sm btn-outline-danger"
                          onclick="return confirm('Delete \'<?php echo addslashes($row['name']); ?>\'?')">
                          🗑️ Delete
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
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/script.js"></script>
  <script>
    // Image URL preview
    const imgInput = document.getElementById('image-url-input');
    const imgPreview = document.getElementById('img-preview');
    const imgPreviewWrap = document.getElementById('img-preview-wrap');

    imgInput.addEventListener('input', function () {
      const url = this.value.trim();
      if (url) {
        imgPreview.src = url;
        imgPreviewWrap.style.display = 'block';
        imgPreview.onerror = function () {
          imgPreviewWrap.style.display = 'none';
        };
      } else {
        imgPreviewWrap.style.display = 'none';
      }
    });

    // Form submit loading
    document.getElementById('add-product-form').addEventListener('submit', function () {
      const btn = document.getElementById('add-btn');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Adding...';
      btn.disabled = true;
    });
  </script>
</body>
</html>
