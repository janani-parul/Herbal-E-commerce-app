/* ===== HERBAL SHOP - ANIMATIONS & INTERACTIONS ===== */

// ===== PAGE LOADER =====
window.addEventListener('load', function () {
  const loader = document.getElementById('page-loader');
  if (loader) {
    setTimeout(() => {
      loader.classList.add('hidden');
      setTimeout(() => loader.remove(), 500);
    }, 600);
  }
});

// ===== SCROLL REVEAL ANIMATION =====
function revealOnScroll() {
  const reveals = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
  reveals.forEach(el => {
    const windowHeight = window.innerHeight;
    const elementTop = el.getBoundingClientRect().top;
    const revealPoint = 100;
    if (elementTop < windowHeight - revealPoint) {
      el.classList.add('active');
    }
  });
}

window.addEventListener('scroll', revealOnScroll);
window.addEventListener('load', revealOnScroll);

// ===== COUNT-UP ANIMATION =====
function animateCounter(el) {
  const target = parseInt(el.getAttribute('data-target'));
  const duration = 1500;
  const step = target / (duration / 16);
  let current = 0;

  const timer = setInterval(() => {
    current += step;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    el.textContent = Math.floor(current);
  }, 16);
}

function initCounters() {
  const counters = document.querySelectorAll('.count-up');
  counters.forEach(counter => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !counter.classList.contains('counted')) {
          counter.classList.add('counted');
          animateCounter(counter);
        }
      });
    }, { threshold: 0.5 });
    observer.observe(counter);
  });
}

// ===== CART FUNCTIONALITY =====
let cart = JSON.parse(localStorage.getItem('herbal_cart')) || [];

function updateCartDisplay() {
  const total = cart.reduce((sum, item) => sum + item.price, 0);
  const cartEl = document.getElementById('cart-total');
  if (cartEl) {
    cartEl.textContent = '₹' + total.toFixed(2);
    cartEl.classList.add('cart-badge');
    // Bounce animation
    cartEl.style.transform = 'scale(1.3)';
    setTimeout(() => cartEl.style.transform = 'scale(1)', 300);
  }
  const cartCountEl = document.getElementById('cart-count');
  if (cartCountEl) cartCountEl.textContent = cart.length;
}

function addToCart(id, name, price) {
  cart.push({ id, name, price });
  localStorage.setItem('herbal_cart', JSON.stringify(cart));
  updateCartDisplay();

  // Toast notification
  showToast(`🌿 ${name || 'Product'} added to cart!`, 'success');
}

// ===== TOAST NOTIFICATIONS =====
function showToast(message, type = 'success') {
  const existing = document.getElementById('toast-container');
  if (existing) existing.remove();

  const container = document.createElement('div');
  container.id = 'toast-container';
  container.style.cssText = `
    position: fixed; bottom: 30px; right: 30px; z-index: 9999;
    animation: fadeInUp 0.4s ease;
  `;

  const toast = document.createElement('div');
  toast.style.cssText = `
    background: ${type === 'success' ? 'linear-gradient(135deg,#2e7d32,#4caf50)' : 'linear-gradient(135deg,#c62828,#ef5350)'};
    color: white; padding: 14px 24px; border-radius: 12px;
    font-family: Poppins, sans-serif; font-weight: 500; font-size: 0.95rem;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2); max-width: 320px;
  `;
  toast.textContent = message;
  container.appendChild(toast);
  document.body.appendChild(container);

  setTimeout(() => {
    container.style.opacity = '0';
    container.style.transition = 'opacity 0.5s';
    setTimeout(() => container.remove(), 500);
  }, 3000);
}

// ===== NAVBAR SCROLL EFFECT =====
window.addEventListener('scroll', function () {
  const navbar = document.querySelector('.navbar');
  if (navbar) {
    if (window.scrollY > 50) {
      navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,0.25)';
      navbar.style.padding = '8px 0';
    } else {
      navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.15)';
      navbar.style.padding = '14px 0';
    }
  }
});

// ===== ACTIVE NAV LINK =====
function setActiveNav() {
  const currentPage = window.location.pathname.split('/').pop();
  document.querySelectorAll('.nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.php')) {
      link.style.background = 'rgba(255,255,255,0.25)';
      link.style.fontWeight = '700';
    }
  });
}

// ===== SMOOTH BUTTON RIPPLE EFFECT =====
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.btn');
  if (!btn) return;

  const ripple = document.createElement('span');
  const rect = btn.getBoundingClientRect();
  const size = Math.max(rect.width, rect.height);
  ripple.style.cssText = `
    position: absolute; border-radius: 50%;
    width: ${size}px; height: ${size}px;
    left: ${e.clientX - rect.left - size / 2}px;
    top: ${e.clientY - rect.top - size / 2}px;
    background: rgba(255,255,255,0.3);
    transform: scale(0); animation: ripple 0.6s ease-out;
    pointer-events: none;
  `;

  if (!document.getElementById('ripple-style')) {
    const style = document.createElement('style');
    style.id = 'ripple-style';
    style.textContent = `@keyframes ripple { to { transform: scale(2.5); opacity: 0; } }`;
    document.head.appendChild(style);
  }

  btn.style.position = 'relative';
  btn.style.overflow = 'hidden';
  btn.appendChild(ripple);
  setTimeout(() => ripple.remove(), 700);
});

// ===== CONTACT FORM =====
const contactForm = document.getElementById('contact-form');
if (contactForm) {
  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
    btn.disabled = true;

    setTimeout(() => {
      btn.innerHTML = '✅ Message Sent!';
      btn.style.background = 'linear-gradient(135deg,#388e3c,#66bb6a)';
      showToast('Your message has been sent successfully!', 'success');
      contactForm.reset();
      setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        btn.style.background = '';
      }, 3000);
    }, 1500);
  });
}

// ===== INIT =====
document.addEventListener('DOMContentLoaded', function () {
  updateCartDisplay();
  initCounters();
  setActiveNav();
  revealOnScroll();
});
