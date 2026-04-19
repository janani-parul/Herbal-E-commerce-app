<?php
// Shared navbar - include this at top of every public page
// Requires session_start() to already be called
$is_logged_in = isset($_SESSION['user_id']);
$is_admin     = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$current_user = htmlspecialchars($_SESSION['username'] ?? '');
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-light">
  <div class="container">
    <a class="navbar-brand text-white" href="index.php">🌿 Herbal Shop</a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <li class="nav-item">
          <a class="nav-link text-white <?php echo $current_page=='index.php'?'fw-bold':''; ?>" href="index.php">🏠 Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white <?php echo $current_page=='about.php'?'fw-bold':''; ?>" href="about.php">🌱 About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white <?php echo $current_page=='contact.php'?'fw-bold':''; ?>" href="contact.php">📞 Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white <?php echo $current_page=='review.php'?'fw-bold':''; ?>" href="review.php">⭐ Reviews</a>
        </li>

        <?php if ($is_logged_in): ?>
          <?php if ($is_admin): ?>
            <li class="nav-item">
              <a class="nav-link text-white" href="dashboard.php">📊 Dashboard</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link text-white <?php echo $current_page=='shop.php'?'fw-bold':''; ?>"
                 href="shop.php"
                 style="<?php echo $current_page=='shop.php'?'background:rgba(255,255,255,0.2);border-radius:8px;padding:6px 14px;':''; ?>">
                🛍️ Shop
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white <?php echo $current_page=='user_dashboard.php'?'fw-bold':''; ?>" href="user_dashboard.php">👤 My Account</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link text-white" href="logout.php"
               style="background:rgba(255,80,80,0.18);border-radius:8px;padding:6px 14px;">
              🚪 Logout
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link text-white" href="login.php">🔐 Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white fw-bold" href="register.php"
               style="background:rgba(255,255,255,0.15);border-radius:8px;padding:6px 14px;">
              🌱 Register
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
