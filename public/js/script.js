const BASE_URL = "http://localhost:8000";

async function getData(path) {
    const response = await fetch(`${BASE_URL}/api/${path}`);
    const data = await response.json();
    if (!response.ok) {
        throw new Error(data?.description || data?.message || "Request failed");
    }
    return data;
}


// ===== HAMBURGER MENU =====
const hamburger = document.getElementById('hamburger');
const nav = document.getElementById('nav');

if (hamburger && nav) {
  hamburger.addEventListener('click', () => {
    nav.classList.toggle('open');
  });

  document.querySelectorAll('.nav__link').forEach(link => {
    link.addEventListener('click', () => {
      nav.classList.remove('open');
    });
  });
}

// ===== SLIDER =====
const slides = document.querySelectorAll('.slider__slide');
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
const dotsContainer = document.getElementById('dots');

if (slides.length && dotsContainer) {
  let currentIndex = 0;
  let interval;

  slides.forEach((_, index) => {
    const dot = document.createElement('span');
    dot.dataset.index = index;
    if (index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToSlide(index));
    dotsContainer.appendChild(dot);
  });

  const dots = dotsContainer.querySelectorAll('span');

  function goToSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
      dots[i].classList.toggle('active', i === index);
    });
    currentIndex = index;
  }

  function nextSlide() {
    goToSlide((currentIndex + 1) % slides.length);
  }

  function prevSlide() {
    goToSlide((currentIndex - 1 + slides.length) % slides.length);
  }

  if (prevBtn && nextBtn) {
    prevBtn.addEventListener('click', () => {
      clearInterval(interval);
      prevSlide();
      autoSlide();
    });

    nextBtn.addEventListener('click', () => {
      clearInterval(interval);
      nextSlide();
      autoSlide();
    });
  }

  function autoSlide() {
    interval = setInterval(nextSlide, 4500);
  }
  autoSlide();
}

// ===== PRODUCT DATA (برای صفحه اصلی) =====
const allProducts = [
  { id: 1, name: 'Wireless Headphones', price: '$49.99', emoji: '🎧', category: 'audio' },
  { id: 2, name: 'Smart Watch', price: '$89.00', emoji: '⌚', category: 'wearable' },
  { id: 3, name: '4K Action Camera', price: '$199.99', emoji: '📷', category: 'camera' },
  { id: 4, name: 'Gaming Laptop', price: '$999.00', emoji: '💻', category: 'laptop' },
  { id: 5, name: '5G Smartphone', price: '$699.00', emoji: '📱', category: 'phone' },
  { id: 6, name: 'Wireless Earbuds', price: '$29.99', emoji: '🎵', category: 'audio' },
  { id: 7, name: 'Fitness Band', price: '$39.00', emoji: '🏃', category: 'wearable' },
  { id: 8, name: 'DSLR Camera', price: '$549.00', emoji: '📸', category: 'camera' },
  { id: 9, name: 'Ultrabook', price: '$1299.00', emoji: '💻', category: 'laptop' },
  { id: 10, name: 'Tablet', price: '$329.00', emoji: '📱', category: 'phone' }
];

// ===== RENDER PRODUCTS (صفحه اصلی) =====
const grid = document.getElementById('productGrid');
const noResult = document.getElementById('noResult');
let currentCategory = 'best seller';
let searchQuery = '';

async function renderProducts() {
  if (!grid) return;

  const filtered = await getData('products/bestseller');

  grid.innerHTML = '';
  if (filtered.length === 0) {
    if (noResult) noResult.style.display = 'block';
    return;
  }
  if (noResult) noResult.style.display = 'none';

  filtered.forEach(product => {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.dataset.category = product.category;

    card.innerHTML = `
      <img class="product-card__emoji" src="${product.images[0].name}" alt="${product.name}" width="100%" height="200px" style="border-radius:5px">
      <h3 class="product-card__name">${product.name}</h3>
      <div class="product-card__price">${product.price}</div>
      <button class="product-card__btn" data-id="${product.id}">View Details</button>
    `;

    const btn = card.querySelector('.product-card__btn');
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
    });

    grid.appendChild(card);
  });
}

// ===== CATEGORY FILTER =====
const catBtns = document.querySelectorAll('.cat-btn');
if (catBtns.length) {
  catBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      catBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      currentCategory = btn.dataset.cat;
      renderProducts();
    });
  });
}

// ===== SEARCH =====
const searchInput = document.getElementById('searchInput');
if (searchInput) {
  searchInput.addEventListener('input', (e) => {
    searchQuery = e.target.value.trim();
    renderProducts();
  });
}

// ===== THEME TOGGLE (improved) =====
const themeToggle = document.getElementById('themeToggle');
if (themeToggle) {
  let isDark = true;

  // بررسی localStorage
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'light') {
    document.body.classList.add('light-theme');
    isDark = false;
    themeToggle.querySelector('.theme-icon').textContent = '☀️';
  }

  themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('light-theme');
    isDark = !isDark;
    const icon = themeToggle.querySelector('.theme-icon');
    icon.textContent = isDark ? '🌙' : '☀️';
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
  });
}

// ===== INIT PRODUCTS =====
if (document.getElementById('productGrid')) {
  renderProducts();
}

// اگر در صفحه سبد خرید هستیم، تابع renderCart در cart.js انجام میشه
// ولی badge رو در script کلی به‌روز می‌کنیم
function updateCartBadge() {
  const badge = document.getElementById('cartBadge');
  if (badge) {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const total = cart.reduce((sum, item) => sum + item.qty, 0);
    badge.textContent = total;
  }
}

// به‌روزرسانی اولیه
updateCartBadge();

// گوش دادن به تغییرات storage (برای همگام‌سازی بین صفحات)
window.addEventListener('storage', (e) => {
  if (e.key === 'cart') {
    updateCartBadge();
    // اگر در صفحه سبد خرید هستیم، رندر مجدد
    if (document.getElementById('cartItems')) {
      // تابع renderCart در cart.js موجود است
      if (typeof renderCart === 'function') renderCart();
    }
  }
});