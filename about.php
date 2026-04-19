<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbal Shop - About Us</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

  <!-- Hero Banner -->
  <section class="hero-section" style="min-height:40vh;">
    <div class="container py-5">
      <div class="hero-content text-center">
        <div class="hero-badge mb-3">🌱 Our Story</div>
        <h1 class="hero-title">About Our Herbal Shop</h1>
        <p class="hero-subtitle">Rooted in nature, growing with purpose</p>
      </div>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="container my-5">
    <div class="row g-4 align-items-center">
      <div class="col-md-6 reveal-left">
        <div class="card p-5 h-100" style="border-left: 5px solid #2e7d32 !important;">
          <div class="feature-icon mb-4">🎯</div>
          <h2 class="section-title text-start" style="font-size:1.8rem;">Our Mission</h2>
          <div style="width:50px;height:4px;background:linear-gradient(90deg,#2e7d32,#8bc34a);border-radius:2px;margin:16px 0 20px;"></div>
          <p class="text-muted lh-lg">To promote a healthy and natural lifestyle through our range of organic and herbal products. We believe in the power of nature to heal and nurture the human body and soul.</p>
          <ul class="list-unstyled mt-3">
            <li class="mb-2">✅ 100% natural ingredients</li>
            <li class="mb-2">✅ Ethically sourced products</li>
            <li class="mb-2">✅ Zero harmful chemicals</li>
          </ul>
        </div>
      </div>
      <div class="col-md-6 reveal-right">
        <div class="card p-5 h-100" style="border-left: 5px solid #8bc34a !important;">
          <div class="feature-icon mb-4">👁️</div>
          <h2 class="section-title text-start" style="font-size:1.8rem;">Our Vision</h2>
          <div style="width:50px;height:4px;background:linear-gradient(90deg,#8bc34a,#2e7d32);border-radius:2px;margin:16px 0 20px;"></div>
          <p class="text-muted lh-lg">To be the most trusted brand for herbal products, known for our commitment to quality, sustainability, and customer satisfaction across the globe.</p>
          <ul class="list-unstyled mt-3">
            <li class="mb-2">🌍 Global herbal wellness leader</li>
            <li class="mb-2">🌱 Sustainable farming practices</li>
            <li class="mb-2">💚 Community health initiatives</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="py-5 my-3" style="background:linear-gradient(135deg,#1b5e20,#2e7d32);">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="text-white" style="font-family:'Playfair Display',serif;font-size:2rem;">Our Achievements</h2>
      </div>
      <div class="row g-4 text-center">
        <div class="col-6 col-md-3 reveal">
          <div class="stat-number text-white count-up" data-target="500">0</div>
          <p class="text-white-50 mt-1">Products</p>
        </div>
        <div class="col-6 col-md-3 reveal delay-1">
          <div class="stat-number text-white count-up" data-target="10000">0</div>
          <p class="text-white-50 mt-1">Happy Customers</p>
        </div>
        <div class="col-6 col-md-3 reveal delay-2">
          <div class="stat-number text-white count-up" data-target="50">0</div>
          <p class="text-white-50 mt-1">Cities Served</p>
        </div>
        <div class="col-6 col-md-3 reveal delay-3">
          <div class="stat-number text-white count-up" data-target="15">0</div>
          <p class="text-white-50 mt-1">Years Experience</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Values Section -->
  <section class="container my-5">
    <div class="text-center mb-5 reveal">
      <span class="badge-herbal mb-3 d-inline-block">Why Choose Us</span>
      <h2 class="section-title d-block">Our Core Values</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4 reveal delay-1">
        <div class="card text-center p-4 h-100">
          <div class="feature-icon mx-auto mb-3">🌿</div>
          <h5 class="fw-bold">Natural Purity</h5>
          <p class="text-muted">Every product is made from pure, natural ingredients with no artificial additives or preservatives.</p>
        </div>
      </div>
      <div class="col-md-4 reveal delay-2">
        <div class="card text-center p-4 h-100">
          <div class="feature-icon mx-auto mb-3">🔬</div>
          <h5 class="fw-bold">Scientifically Tested</h5>
          <p class="text-muted">All our herbal products undergo rigorous quality testing to ensure safety and effectiveness.</p>
        </div>
      </div>
      <div class="col-md-4 reveal delay-3">
        <div class="card text-center p-4 h-100">
          <div class="feature-icon mx-auto mb-3">♻️</div>
          <h5 class="fw-bold">Eco-Friendly</h5>
          <p class="text-muted">We are committed to sustainable packaging and environmentally responsible practices.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Team Section -->
  <section class="container mb-5">
    <div class="text-center mb-5 reveal">
      <span class="badge-herbal mb-3 d-inline-block">The People</span>
      <h2 class="section-title d-block">Meet Our Team</h2>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-md-3 col-sm-6 reveal delay-1">
        <div class="card text-center p-4">
          <div style="width:80px;height:80px;background:linear-gradient(135deg,#2e7d32,#8bc34a);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 16px;">👩‍⚕️</div>
          <h6 class="fw-bold">Dr. Priya Sharma</h6>
          <small class="text-success fw-600">Chief Herbalist</small>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 reveal delay-2">
        <div class="card text-center p-4">
          <div style="width:80px;height:80px;background:linear-gradient(135deg,#1b5e20,#4caf50);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 16px;">👨‍🔬</div>
          <h6 class="fw-bold">Rahul Verma</h6>
          <small class="text-success fw-600">R&D Specialist</small>
        </div>
      </div>
      <div class="col-md-3 col-sm-6 reveal delay-3">
        <div class="card text-center p-4">
          <div style="width:80px;height:80px;background:linear-gradient(135deg,#388e3c,#66bb6a);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2rem;margin:0 auto 16px;">👩‍💼</div>
          <h6 class="fw-bold">Anita Patel</h6>
          <small class="text-success fw-600">Operations Head</small>
        </div>
      </div>
    </div>
  </section>

  <div class="container"><div class="divider"></div></div>

  <!-- CTA Section -->
  <section class="container mb-5 text-center reveal">
    <div class="card p-5" style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);">
      <h3 class="section-title d-block mb-4">Ready to Go Natural?</h3>
      <p class="text-muted mb-4">Explore our wide range of premium herbal products and start your wellness journey today.</p>
      <a href="index.php#products" class="btn btn-success px-5 py-3 fs-5">🛍️ Shop Now</a>
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
  <script src="assets/script.js"></script>
</body>
</html>
