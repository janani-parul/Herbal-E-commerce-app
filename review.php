<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = intval($_POST["product_id"]);
  $rating     = intval($_POST["rating"]);
  $comment    = trim($_POST["comment"]);
  $user_id    = intval($_SESSION["user_id"]);

  // Validate
  if ($rating < 1 || $rating > 5) {
    $error = "Please select a valid rating between 1 and 5.";
  } elseif (empty($comment)) {
    $error = "Comment cannot be empty.";
  } else {
    // Prepared statement – no SQL injection
    $stmt = $conn->prepare("INSERT INTO reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $product_id, $rating, $comment);
    if ($stmt->execute()) {
      $success = "Your review has been submitted successfully! 🌿";
    } else {
      $error = "Something went wrong. Please try again.";
    }
    $stmt->close();
  }
}

// Pre-fill product_id from URL
$prefill_product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : (isset($_POST['product_id']) ? intval($_POST['product_id']) : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbal Shop - Add Review</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    /* Star Rating */
    .star-rating { display: flex; flex-direction: row-reverse; justify-content: center; gap: 6px; }
    .star-rating input[type="radio"] { display: none; }
    .star-rating label {
      font-size: 2.8rem; color: #ddd; cursor: pointer;
      transition: all 0.3s cubic-bezier(0.25,0.8,0.25,1);
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input[type="radio"]:checked ~ label {
      color: #ffc107;
      transform: scale(1.25);
      text-shadow: 0 0 12px rgba(255,193,7,0.6);
    }
    .review-hero {
      background: linear-gradient(135deg, #1b5e20, #2e7d32, #8bc34a);
      min-height: 35vh;
      display: flex; align-items: center; justify-content: center;
      position: relative; overflow: hidden;
    }
    .review-hero::before {
      content: '';
      position: absolute; top: -50%; right: -20%;
      width: 500px; height: 500px;
      background: rgba(255,255,255,0.05); border-radius: 50%;
      animation: float 6s ease-in-out infinite;
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

  <?php include 'navbar.php'; ?>

  <!-- Hero -->
  <div class="review-hero">
    <div class="text-center position-relative" style="z-index:2;animation:fadeInUp 0.8s ease both;">
      <div class="hero-badge mb-3">⭐ Share Your Experience</div>
      <h1 class="hero-title">Write a Review</h1>
      <p class="hero-subtitle">Your feedback helps others make better choices</p>
    </div>
  </div>

  <!-- Main Content -->
  <section class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-7">

        <?php if ($success): ?>
          <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="animation:fadeInDown 0.5s ease both;">
            <span style="font-size:1.4rem;">✅</span>
            <div>
              <strong>Review Submitted!</strong><br>
              <small><?php echo $success; ?></small>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
            <span style="font-size:1.4rem;">⚠️</span>
            <div><?php echo htmlspecialchars($error); ?></div>
          </div>
        <?php endif; ?>

        <div class="card p-4 p-md-5 reveal">
          <!-- User Info -->
          <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:#f1f8e9;border-radius:12px;">
            <div style="width:50px;height:50px;background:linear-gradient(135deg,#2e7d32,#8bc34a);border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.2rem;">
              <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
            </div>
            <div>
              <strong style="color:#1b5e20;"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong>
              <br><small class="text-muted">Verified Customer</small>
            </div>
          </div>

          <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="review-form">

            <!-- Product ID -->
            <div class="mb-4">
              <label for="product_id" class="form-label">🌿 Product ID</label>
              <input type="number" class="form-control" id="product_id" name="product_id"
                placeholder="Enter Product ID" required min="1"
                value="<?php echo $prefill_product_id; ?>">
            </div>

            <!-- Star Rating -->
            <div class="mb-4">
              <label class="form-label d-block text-center">⭐ Your Rating</label>
              <div class="star-rating my-2">
                <input type="radio" id="star5" name="rating" value="5" <?php echo (isset($_POST['rating']) && $_POST['rating']==5) ? 'checked' : ''; ?>>
                <label for="star5" title="5 stars">★</label>
                <input type="radio" id="star4" name="rating" value="4" <?php echo (isset($_POST['rating']) && $_POST['rating']==4) ? 'checked' : ''; ?>>
                <label for="star4" title="4 stars">★</label>
                <input type="radio" id="star3" name="rating" value="3" <?php echo (isset($_POST['rating']) && $_POST['rating']==3) ? 'checked' : ''; ?>>
                <label for="star3" title="3 stars">★</label>
                <input type="radio" id="star2" name="rating" value="2" <?php echo (isset($_POST['rating']) && $_POST['rating']==2) ? 'checked' : ''; ?>>
                <label for="star2" title="2 stars">★</label>
                <input type="radio" id="star1" name="rating" value="1" <?php echo (isset($_POST['rating']) && $_POST['rating']==1) ? 'checked' : ''; ?>>
                <label for="star1" title="1 star">★</label>
              </div>
              <p class="text-center text-muted small mt-2" id="rating-text">Click to select your rating</p>
            </div>

            <!-- Comment -->
            <div class="mb-4">
              <label for="comment" class="form-label">💬 Your Review</label>
              <textarea class="form-control" id="comment" name="comment" rows="5"
                placeholder="Share your experience with this product..."
                required><?php echo isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : ''; ?></textarea>
              <div class="text-end mt-1">
                <small class="text-muted" id="char-count">0 / 500 characters</small>
              </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-3 fs-6" id="submit-btn">
              ⭐ Submit Review
            </button>
          </form>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-4">
          <a href="index.php" class="btn btn-outline-success px-4">← Back to Shop</a>
        </div>
      </div>

      <!-- Tips Card -->
      <div class="col-md-4 reveal-right">
        <div class="card p-4 mt-4 mt-md-0" style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);">
          <h5 class="fw-bold mb-3" style="color:#1b5e20;">💡 Review Tips</h5>
          <ul class="list-unstyled">
            <li class="mb-3 d-flex gap-2"><span>🌿</span><span>Be specific about what you liked or disliked</span></li>
            <li class="mb-3 d-flex gap-2"><span>⭐</span><span>Rate based on your overall experience</span></li>
            <li class="mb-3 d-flex gap-2"><span>📝</span><span>Mention how the product helped you</span></li>
            <li class="mb-3 d-flex gap-2"><span>🚫</span><span>Avoid personal or irrelevant information</span></li>
          </ul>
        </div>

        <div class="card p-4 mt-4 text-center">
          <div style="font-size:2.5rem;margin-bottom:8px;">🌟</div>
          <h6 class="fw-bold" style="color:#1b5e20;">Reviews Matter!</h6>
          <p class="text-muted small mb-0">Your honest review helps thousands of customers make better purchasing decisions.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p class="text-center mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem;">© 2025 Herbal Shop. All rights reserved. Made with 🌿 & ❤️</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/script.js"></script>
  <script>
    // Rating label update
    const ratingLabels = { 1: '😞 Poor', 2: '😐 Fair', 3: '😊 Good', 4: '😃 Very Good', 5: '🤩 Excellent!' };
    document.querySelectorAll('.star-rating input').forEach(input => {
      input.addEventListener('change', function () {
        document.getElementById('rating-text').textContent = ratingLabels[this.value] || '';
        document.getElementById('rating-text').style.color = '#ffc107';
        document.getElementById('rating-text').style.fontWeight = '600';
      });
    });

    // Character count
    const commentField = document.getElementById('comment');
    const charCount = document.getElementById('char-count');
    commentField.addEventListener('input', function () {
      const len = this.value.length;
      charCount.textContent = len + ' / 500 characters';
      charCount.style.color = len > 450 ? '#c62828' : '#6c757d';
      if (len > 500) this.value = this.value.substring(0, 500);
    });

    // Submit button loading
    document.getElementById('review-form').addEventListener('submit', function () {
      const btn = document.getElementById('submit-btn');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
      btn.disabled = true;
    });
  </script>
</body>
</html>
