<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbal Shop - Contact Us</title>
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
  <section class="hero-section" style="min-height:35vh;">
    <div class="container py-5">
      <div class="hero-content text-center">
        <div class="hero-badge mb-3">📞 Get In Touch</div>
        <h1 class="hero-title">Contact Us</h1>
        <p class="hero-subtitle">We'd love to hear from you. Send us a message!</p>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="container my-5">
    <div class="row g-5 align-items-start">

      <!-- Contact Form -->
      <div class="col-md-7 reveal-left">
        <div class="card p-5">
          <h3 class="fw-bold mb-1" style="color:#1b5e20;">Send a Message</h3>
          <p class="text-muted mb-4">Fill out the form below and we'll get back to you within 24 hours.</p>

          <form id="contact-form">
            <div class="mb-4">
              <label for="name" class="form-label">👤 Your Name</label>
              <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
            </div>
            <div class="mb-4">
              <label for="email" class="form-label">📧 Email Address</label>
              <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-4">
              <label for="subject" class="form-label">📌 Subject</label>
              <input type="text" class="form-control" id="subject" placeholder="What is this about?">
            </div>
            <div class="mb-4">
              <label for="message" class="form-label">💬 Message</label>
              <textarea class="form-control" id="message" rows="5" placeholder="Write your message here..." required></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100 py-3 fs-6">
              📤 Send Message
            </button>
          </form>
        </div>
      </div>

      <!-- Contact Info -->
      <div class="col-md-5 reveal-right">
        <div class="mb-4">
          <h4 class="fw-bold mb-4" style="color:#1b5e20;">Contact Information</h4>

          <div class="d-flex align-items-start gap-3 mb-4">
            <div class="feature-icon flex-shrink-0" style="width:50px;height:50px;font-size:1.3rem;">📍</div>
            <div>
              <h6 class="fw-bold mb-1">Our Address</h6>
              <p class="text-muted mb-0">123 Green Street, Nature City,<br>India - 400001</p>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3 mb-4">
            <div class="feature-icon flex-shrink-0" style="width:50px;height:50px;font-size:1.3rem;">📞</div>
            <div>
              <h6 class="fw-bold mb-1">Phone Number</h6>
              <p class="text-muted mb-0">+91 98765 43210<br>+91 87654 32109</p>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3 mb-4">
            <div class="feature-icon flex-shrink-0" style="width:50px;height:50px;font-size:1.3rem;">📧</div>
            <div>
              <h6 class="fw-bold mb-1">Email Address</h6>
              <p class="text-muted mb-0">info@herbalshop.com<br>support@herbalshop.com</p>
            </div>
          </div>

          <div class="d-flex align-items-start gap-3 mb-4">
            <div class="feature-icon flex-shrink-0" style="width:50px;height:50px;font-size:1.3rem;">🕐</div>
            <div>
              <h6 class="fw-bold mb-1">Business Hours</h6>
              <p class="text-muted mb-0">Mon - Sat: 9:00 AM – 7:00 PM<br>Sunday: 10:00 AM – 4:00 PM</p>
            </div>
          </div>
        </div>

        <!-- Map Placeholder -->
        <div class="card p-4 text-center reveal" style="background:linear-gradient(135deg,#e8f5e9,#f1f8e9);">
          <div style="font-size:3rem;margin-bottom:12px;">🗺️</div>
          <h6 class="fw-bold text-success">Find Us On Map</h6>
          <p class="text-muted small mb-0">123 Green Street, Nature City</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="container mb-5">
    <div class="text-center mb-5 reveal">
      <span class="badge-herbal mb-3 d-inline-block">Help Center</span>
      <h2 class="section-title d-block">Frequently Asked Questions</h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="accordion" id="faqAccordion">
          <div class="accordion-item border-0 mb-3 reveal delay-1" style="border-radius:12px;overflow:hidden;box-shadow:var(--shadow);">
            <h2 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="font-weight:600;color:#1b5e20;background:#f1f8e9;">
                🌿 Are all your products 100% natural?
              </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted">Yes! Every product in our store is made from 100% natural, organically sourced ingredients. We never use artificial preservatives or harmful chemicals.</div>
            </div>
          </div>
          <div class="accordion-item border-0 mb-3 reveal delay-2" style="border-radius:12px;overflow:hidden;box-shadow:var(--shadow);">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="font-weight:600;color:#1b5e20;">
                🚚 How long does delivery take?
              </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted">Standard delivery takes 3-5 business days. Express delivery (1-2 days) is available for select locations at an additional charge.</div>
            </div>
          </div>
          <div class="accordion-item border-0 mb-3 reveal delay-3" style="border-radius:12px;overflow:hidden;box-shadow:var(--shadow);">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="font-weight:600;color:#1b5e20;">
                🔄 What is your return policy?
              </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted">We offer a 7-day return policy on unopened products. If you're not satisfied, contact us within 7 days of delivery for a full refund or exchange.</div>
            </div>
          </div>
        </div>
      </div>
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
