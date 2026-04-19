<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    if ($user['role'] === 'admin') {
      header("Location: dashboard.php");
    } else {
    header("Location: user_dashboard.php");
    }
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbal Shop - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/style.css">
  <style>
    .login-bg {
      min-height: 100vh;
      background: linear-gradient(180deg,
        #0b0c2a 0%,
        #0d1b3e 20%,
        #0a2a1a 55%,
        #1b5e20 80%,
        #2e7d32 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
      padding: 40px 20px;
    }

    /* Glowing orbs */
    .login-bg::before {
      content: '';
      position: absolute;
      top: 10%;
      right: 10%;
      width: 300px; height: 300px;
      background: radial-gradient(circle, rgba(100,200,100,0.12) 0%, transparent 70%);
      border-radius: 50%;
      animation: float 8s ease-in-out infinite;
    }
    .login-bg::after {
      content: '';
      position: absolute;
      bottom: 15%;
      left: 5%;
      width: 250px; height: 250px;
      background: radial-gradient(circle, rgba(50,150,200,0.1) 0%, transparent 70%);
      border-radius: 50%;
      animation: float 10s ease-in-out infinite reverse;
    }

    /* Stars */
    .stars { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; }
    .star {
      position: absolute;
      background: white;
      border-radius: 50%;
      animation: twinkle ease-in-out infinite;
    }
    @keyframes twinkle {
      0%,100% { opacity: 0.2; transform: scale(1); }
      50%      { opacity: 1;   transform: scale(1.4); }
    }

    /* Fireflies */
    .firefly {
      position: fixed;
      width: 6px; height: 6px;
      background: radial-gradient(circle, #aaff80, #00ff88);
      border-radius: 50%;
      box-shadow: 0 0 8px 3px rgba(100,255,100,0.6);
      animation: fireflyMove linear infinite;
      pointer-events: none;
      z-index: 2;
    }
    @keyframes fireflyMove {
      0%   { transform: translate(0,0);       opacity: 0; }
      20%  { opacity: 1; }
      50%  { transform: translate(var(--dx), var(--dy)); opacity: 0.8; }
      80%  { opacity: 1; }
      100% { transform: translate(var(--dx2),var(--dy2)); opacity: 0; }
    }

    /* Mountain silhouette at bottom */
    .mountain-bg {
      position: fixed;
      bottom: 0; left: 0; width: 100%;
      height: 200px;
      pointer-events: none;
      z-index: 0;
    }
    .login-card {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 440px;
      animation: zoomIn 0.7s ease both;
    }
    .login-logo {
      font-size: 3rem;
      animation: leafSway 3s ease-in-out infinite;
      display: inline-block;
    }
    .input-group-icon {
      position: relative;
    }
    .input-group-icon .icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.1rem;
      z-index: 5;
    }
    .input-group-icon .form-control {
      padding-left: 42px !important;
    }
    .toggle-password {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      z-index: 5;
      font-size: 1.1rem;
    }

    /* ===== FULL DARK CARD — NO WHITE ===== */
    * { box-sizing: border-box; }
    .page-loader { background: #0b0c2a !important; }
    .page-loader p { color: #80ff90 !important; }

    .glass-card {
      background: rgba(3, 18, 8, 0.82) !important;
      backdrop-filter: blur(18px) !important;
      -webkit-backdrop-filter: blur(18px) !important;
      border: 1px solid rgba(80, 220, 100, 0.22) !important;
      border-radius: 24px !important;
      box-shadow: 0 10px 50px rgba(0,0,0,0.7), inset 0 1px 0 rgba(255,255,255,0.04) !important;
    }

    /* All text inside card */
    .glass-card h4,
    .glass-card h4[style] { color: #7fffaa !important; }
    .glass-card p,
    .glass-card .text-muted,
    .glass-card small { color: rgba(160,255,175,0.6) !important; }
    .glass-card .form-label { color: rgba(180,255,195,0.9) !important; font-weight:500; }

    /* Inputs — dark, not white */
    .glass-card .form-control,
    .glass-card input[type="text"],
    .glass-card input[type="password"] {
      background: rgba(0, 30, 10, 0.7) !important;
      border: 1px solid rgba(80,220,100,0.3) !important;
      color: #c8ffd0 !important;
      border-radius: 12px !important;
      padding-left: 42px !important;
    }
    .glass-card .form-control::placeholder { color: rgba(130,200,140,0.45) !important; }
    .glass-card .form-control:focus {
      background: rgba(0, 40, 14, 0.8) !important;
      border-color: rgba(80,220,100,0.6) !important;
      box-shadow: 0 0 0 3px rgba(40,180,70,0.2) !important;
      color: #d8ffd8 !important;
      outline: none !important;
    }

    /* Icons */
    .glass-card .icon { color: rgba(140,255,160,0.65) !important; }
    .glass-card .toggle-password { color: rgba(140,255,160,0.65) !important; }

    /* Login button */
    .glass-card .btn-success,
    .glass-card button[type="submit"] {
      background: linear-gradient(135deg, #0d5c28, #1e8a40) !important;
      border: 1px solid rgba(80,220,100,0.35) !important;
      color: #ccffcc !important;
      box-shadow: 0 4px 20px rgba(0,130,50,0.4) !important;
      font-weight: 600;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }
    .glass-card .btn-success:hover {
      background: linear-gradient(135deg, #127033, #26a84e) !important;
      transform: translateY(-2px);
      box-shadow: 0 8px 28px rgba(0,150,60,0.55) !important;
    }

    /* Error alert */
    .glass-card .alert-danger {
      background: rgba(180,30,30,0.25) !important;
      border: 1px solid rgba(255,80,80,0.35) !important;
      color: #ffb0b0 !important;
      border-radius: 10px !important;
    }

    /* ===== FLYING BIRDS ===== */
    .birds-container {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      pointer-events: none;
      z-index: 1;
      overflow: hidden;
    }

    .bird {
      position: absolute;
      opacity: 0.6;
      animation: flyBird linear infinite;
      transform-origin: center;
    }

    .bird svg {
      animation: flapWings ease-in-out infinite alternate;
    }

    @keyframes flyBird {
      0%   { transform: translateX(-120px) translateY(0px); opacity: 0; }
      5%   { opacity: 0.7; }
      95%  { opacity: 0.7; }
      100% { transform: translateX(110vw) translateY(var(--drift)); opacity: 0; }
    }

    @keyframes flapWings {
      0%   { transform: scaleY(1); }
      100% { transform: scaleY(-0.4); }
    }

    .bird:nth-child(1)  { top: 12%; animation-duration: 9s;  animation-delay: 0s;    --drift: -30px; }
    .bird:nth-child(2)  { top: 22%; animation-duration: 11s; animation-delay: 1.5s;  --drift: 20px;  }
    .bird:nth-child(3)  { top: 35%; animation-duration: 8s;  animation-delay: 3s;    --drift: -50px; }
    .bird:nth-child(4)  { top: 8%;  animation-duration: 13s; animation-delay: 0.5s;  --drift: 40px;  }
    .bird:nth-child(5)  { top: 55%; animation-duration: 10s; animation-delay: 2s;    --drift: -20px; }
    .bird:nth-child(6)  { top: 18%; animation-duration: 7s;  animation-delay: 4s;    --drift: 60px;  }
    .bird:nth-child(7)  { top: 70%; animation-duration: 12s; animation-delay: 1s;    --drift: -40px; }
    .bird:nth-child(8)  { top: 42%; animation-duration: 9.5s;animation-delay: 5s;   --drift: 30px;  }
    .bird:nth-child(9)  { top: 28%; animation-duration: 14s; animation-delay: 2.5s;  --drift: -60px; }
    .bird:nth-child(10) { top: 62%; animation-duration: 8.5s;animation-delay: 3.5s;  --drift: 50px; }
    .bird:nth-child(11) { top: 5%;  animation-duration: 11s; animation-delay: 6s;    --drift: -20px; }
    .bird:nth-child(12) { top: 80%; animation-duration: 9s;  animation-delay: 7s;    --drift: 40px;  }

    /* leaf particles */
    .leaf {
      position: fixed;
      font-size: 1.2rem;
      animation: leafFall linear infinite;
      opacity: 0;
      pointer-events: none;
      z-index: 1;
    }
    @keyframes leafFall {
      0%   { transform: translateY(-20px) rotate(0deg); opacity: 0; }
      10%  { opacity: 0.8; }
      90%  { opacity: 0.6; }
      100% { transform: translateY(105vh) rotate(360deg); opacity: 0; }
    }
    .leaf:nth-child(1)  { left: 5%;  animation-duration: 8s;  animation-delay: 0s; }
    .leaf:nth-child(2)  { left: 15%; animation-duration: 10s; animation-delay: 2s; }
    .leaf:nth-child(3)  { left: 30%; animation-duration: 7s;  animation-delay: 4s; }
    .leaf:nth-child(4)  { left: 50%; animation-duration: 9s;  animation-delay: 1s; }
    .leaf:nth-child(5)  { left: 70%; animation-duration: 11s; animation-delay: 3s; }
    .leaf:nth-child(6)  { left: 85%; animation-duration: 8s;  animation-delay: 5s; }
    .leaf:nth-child(7)  { left: 95%; animation-duration: 12s; animation-delay: 0.5s; }
  </style>
</head>
<body style="margin:0;padding:0;background:#0b0c2a;">

  <!-- Page Loader -->
  <div class="page-loader" id="page-loader">
    <div class="text-center">
      <div class="loader-ring mb-3"></div>
      <p style="color:#2e7d32;font-family:Poppins,sans-serif;font-weight:600;">Loading...</p>
    </div>
  </div>

  <!-- Stars -->
  <div class="stars" id="stars-container"></div>

  <!-- Fireflies -->
  <div id="fireflies-container" style="position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:2;overflow:hidden;"></div>

  <!-- Mountain SVG silhouette -->
  <svg class="mountain-bg" viewBox="0 0 1440 200" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
    <path d="M0,200 L0,120 L120,60 L240,130 L380,40 L500,110 L640,20 L760,90 L900,30 L1020,100 L1160,50 L1300,110 L1440,70 L1440,200 Z" fill="#0a2a1a" opacity="0.9"/>
    <path d="M0,200 L0,150 L100,100 L200,150 L320,90 L440,140 L580,80 L700,130 L840,70 L960,120 L1100,85 L1220,135 L1360,100 L1440,130 L1440,200 Z" fill="#1b3a20" opacity="0.85"/>
    <path d="M0,200 L0,170 L150,140 L280,165 L400,130 L520,160 L660,125 L780,155 L920,135 L1060,158 L1200,140 L1340,162 L1440,148 L1440,200 Z" fill="#2d5a30" opacity="0.8"/>
  </svg>

  <!-- Flying Birds -->
  <div class="birds-container">
    <!-- Bird SVG: simple M-shape wings -->
    <div class="bird" style="--bird-size:28px;">
      <svg width="28" height="14" viewBox="0 0 28 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 7 Q7 0 14 7 Q21 0 28 7" stroke="rgba(255,255,255,0.85)" stroke-width="2.5" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="22" height="11" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 5.5 Q5.5 0 11 5.5 Q16.5 0 22 5.5" stroke="rgba(255,255,255,0.75)" stroke-width="2" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="32" height="16" viewBox="0 0 32 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 8 Q8 0 16 8 Q24 0 32 8" stroke="rgba(255,255,255,0.7)" stroke-width="2.5" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="18" height="9" viewBox="0 0 18 9" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 4.5 Q4.5 0 9 4.5 Q13.5 0 18 4.5" stroke="rgba(200,255,200,0.8)" stroke-width="2" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="26" height="13" viewBox="0 0 26 13" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 6.5 Q6.5 0 13 6.5 Q19.5 0 26 6.5" stroke="rgba(255,255,255,0.8)" stroke-width="2.5" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="20" height="10" viewBox="0 0 20 10" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 5 Q5 0 10 5 Q15 0 20 5" stroke="rgba(180,255,180,0.7)" stroke-width="2" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="34" height="17" viewBox="0 0 34 17" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 8.5 Q8.5 0 17 8.5 Q25.5 0 34 8.5" stroke="rgba(255,255,255,0.6)" stroke-width="3" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="24" height="12" viewBox="0 0 24 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 6 Q6 0 12 6 Q18 0 24 6" stroke="rgba(255,255,255,0.85)" stroke-width="2" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="16" height="8" viewBox="0 0 16 8" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 4 Q4 0 8 4 Q12 0 16 4" stroke="rgba(200,255,200,0.75)" stroke-width="2" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="28" height="14" viewBox="0 0 28 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 7 Q7 0 14 7 Q21 0 28 7" stroke="rgba(255,255,255,0.7)" stroke-width="2.5" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="22" height="11" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 5.5 Q5.5 0 11 5.5 Q16.5 0 22 5.5" stroke="rgba(255,255,255,0.8)" stroke-width="2" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="bird">
      <svg width="30" height="15" viewBox="0 0 30 15" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 7.5 Q7.5 0 15 7.5 Q22.5 0 30 7.5" stroke="rgba(180,255,180,0.65)" stroke-width="2.5" fill="none" stroke-linecap="round"/>
      </svg>
    </div>
  </div>

  <!-- Falling Leaves -->
  <div style="position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:1;overflow:hidden;">
    <span class="leaf" style="left:5%;animation-duration:8s;animation-delay:0s;">🍃</span>
    <span class="leaf" style="left:15%;animation-duration:10s;animation-delay:2s;">🌿</span>
    <span class="leaf" style="left:30%;animation-duration:7s;animation-delay:4s;">🍃</span>
    <span class="leaf" style="left:50%;animation-duration:9s;animation-delay:1s;">🌿</span>
    <span class="leaf" style="left:70%;animation-duration:11s;animation-delay:3s;">🍃</span>
    <span class="leaf" style="left:85%;animation-duration:8s;animation-delay:5s;">🌿</span>
    <span class="leaf" style="left:95%;animation-duration:12s;animation-delay:0.5s;">🍃</span>
  </div>

  <div class="login-bg">
    <div class="login-card">
      <!-- Logo -->
      <div class="text-center mb-4">
        <div class="login-logo">🌿</div>
        <h2 class="text-white fw-bold mt-2" style="font-family:'Playfair Display',serif;">Herbal Shop</h2>
        <p class="text-white-50">Your natural wellness destination</p>
      </div>

      <!-- Card -->
      <div class="glass-card p-4 p-md-5">
        <h4 class="fw-bold text-center mb-1" style="color:#1b5e20;">Welcome Back!</h4>
        <p class="text-muted text-center mb-4" style="font-size:0.9rem;">Sign in to your account</p>

        <?php if (isset($error)): ?>
          <div class="alert alert-danger d-flex align-items-center gap-2">
            <span>⚠️</span> <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form method="POST" id="login-form" autocomplete="off">
          <div class="mb-4">
            <label for="username" class="form-label">Username</label>
            <div class="input-group-icon position-relative">
              <span class="icon">👤</span>
              <input type="text" class="form-control" id="username" name="username"
                placeholder="Enter your username" required
                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group-icon position-relative">
              <span class="icon">🔒</span>
              <input type="password" class="form-control" id="password" name="password"
                placeholder="Enter your password" required>
              <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>
          </div>

          <button type="submit" class="btn btn-success w-100 py-3 fs-6 mt-2" id="login-btn">
            🔐 Login
          </button>
        </form>

      </div>

      <!-- Bottom Links -->
      <div class="text-center mt-3">
        <p style="color:rgba(160,255,175,0.65);font-size:0.92rem;margin-bottom:8px;">
          Don't have an account?
          <a href="register.php" style="color:#7fffaa;font-weight:700;text-decoration:none;"> Register →</a>
        </p>
        <p class="text-white-50 mb-0" style="font-size:0.8rem;">🔒 Protected by secure authentication</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/script.js"></script>
  <script>
    function togglePassword() {
      const pwd = document.getElementById('password');
      pwd.type = pwd.type === 'password' ? 'text' : 'password';
    }

    document.getElementById('login-form').addEventListener('submit', function () {
      const btn = document.getElementById('login-btn');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signing in...';
      btn.disabled = true;
    });

    // Generate Stars
    const starsContainer = document.getElementById('stars-container');
    for (let i = 0; i < 120; i++) {
      const star = document.createElement('div');
      star.classList.add('star');
      const size = Math.random() * 3 + 1;
      star.style.cssText = `
        width:${size}px; height:${size}px;
        top:${Math.random()*60}%;
        left:${Math.random()*100}%;
        animation-duration:${2+Math.random()*4}s;
        animation-delay:${Math.random()*5}s;
      `;
      starsContainer.appendChild(star);
    }

    // Generate Fireflies
    const ffContainer = document.getElementById('fireflies-container');
    for (let i = 0; i < 18; i++) {
      const ff = document.createElement('div');
      ff.classList.add('firefly');
      const dx  = (Math.random()-0.5)*200 + 'px';
      const dy  = (Math.random()-0.5)*200 + 'px';
      const dx2 = (Math.random()-0.5)*300 + 'px';
      const dy2 = (Math.random()-0.5)*300 + 'px';
      ff.style.cssText = `
        top:${40+Math.random()*55}%;
        left:${Math.random()*100}%;
        --dx:${dx}; --dy:${dy};
        --dx2:${dx2}; --dy2:${dy2};
        animation-duration:${6+Math.random()*8}s;
        animation-delay:${Math.random()*6}s;
        width:${4+Math.random()*4}px;
        height:${4+Math.random()*4}px;
      `;
      ffContainer.appendChild(ff);
    }
  </script>
</body>
</html>
