// ===== CART STATE =====
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// ===== DOM REFS =====
const cartItemsContainer = document.getElementById('cartItems');
const emptyCartEl = document.getElementById('emptyCart');
const subtotalEl = document.getElementById('subtotal');
const shippingEl = document.getElementById('shipping');
const totalEl = document.getElementById('totalPrice');
const cartBadge = document.getElementById('cartBadge');

// ===== RENDER CART =====
function renderCart() {
  if (!cartItemsContainer) return;

  // به‌روزرسانی نشانگر سبد خرید
  const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
  if (cartBadge) cartBadge.textContent = totalItems;

  if (cart.length === 0) {
    cartItemsContainer.innerHTML = '';
    emptyCartEl.style.display = 'block';
    document.querySelector('.cart-wrapper').style.display = 'none';
    subtotalEl.textContent = '$0.00';
    shippingEl.textContent = '$0.00';
    totalEl.textContent = '$0.00';
    return;
  }

  emptyCartEl.style.display = 'none';
  document.querySelector('.cart-wrapper').style.display = 'grid';

  cartItemsContainer.innerHTML = '';
  let subtotal = 0;

  cart.forEach((item, index) => {
    const priceNum = parseFloat(item.price.replace('$', ''));
    const itemTotal = priceNum * item.qty;
    subtotal += itemTotal;

    const div = document.createElement('div');
    div.className = 'cart-item';

    div.innerHTML = `
      <span class="cart-item__emoji">${item.emoji}</span>
      <div class="cart-item__info">
        <div class="cart-item__name">${item.name}</div>
        <div class="cart-item__price">${item.price}</div>
        <div class="cart-item__controls">
          <button class="cart-item__qty-btn" data-index="${index}" data-action="decrease">−</button>
          <span class="cart-item__qty">${item.qty}</span>
          <button class="cart-item__qty-btn" data-index="${index}" data-action="increase">+</button>
        </div>
      </div>
      <button class="cart-item__remove" data-index="${index}" aria-label="Remove item">✕</button>
    `;

    cartItemsContainer.appendChild(div);
  });

  // محاسبه هزینه ارسال (مثلاً بالای ۵۰ دلار رایگان)
  const shippingCost = subtotal >= 50 ? 0 : 5.99;
  const total = subtotal + shippingCost;

  subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
  shippingEl.textContent = shippingCost === 0 ? 'Free' : `$${shippingCost.toFixed(2)}`;
  totalEl.textContent = `$${total.toFixed(2)}`;

  // ذخیره در localStorage
  localStorage.setItem('cart', JSON.stringify(cart));

  // افزودن رویدادها به دکمه‌ها
  document.querySelectorAll('.cart-item__qty-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const index = parseInt(btn.dataset.index);
      const action = btn.dataset.action;
      if (action === 'increase') {
        cart[index].qty += 1;
      } else if (action === 'decrease') {
        if (cart[index].qty > 1) {
          cart[index].qty -= 1;
        } else {
          // حذف آیتم اگر تعداد به صفر رسید
          cart.splice(index, 1);
        }
      }
      renderCart();
      showToast('Cart updated', 'success');
    });
  });

  document.querySelectorAll('.cart-item__remove').forEach(btn => {
    btn.addEventListener('click', () => {
      const index = parseInt(btn.dataset.index);
      const removedItem = cart[index];
      cart.splice(index, 1);
      renderCart();
      showToast(`Removed ${removedItem.name}`, 'error');
    });
  });
}

// ===== ADD TO CART (از صفحات دیگر) =====
function addToCart(product) {
  const existing = cart.find(item => item.id === product.id);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({
      id: product.id,
      name: product.name,
      price: product.price,
      emoji: product.emoji,
      qty: 1
    });
  }
  renderCart();
  showToast(`${product.name} added to cart!`, 'success');
}

// ===== TOAST (هماهنگ با صفحات دیگر) =====
function showToast(message, type = 'success') {
  const toast = document.getElementById('toastMessage');
  if (!toast) return;

  // clear previous timeout
  if (window.toastTimeout) {
    clearTimeout(window.toastTimeout);
    toast.classList.remove('show');
  }

  toast.textContent = message;
  toast.className = 'toast-message';
  toast.classList.add(type);

  void toast.offsetWidth; // reflow

  toast.classList.add('show');

  window.toastTimeout = setTimeout(() => {
    toast.classList.remove('show');
    window.toastTimeout = null;
  }, 4000);
}

// ===== CHECKOUT =====
document.getElementById('checkoutBtn')?.addEventListener('click', () => {
  if (cart.length === 0) {
    showToast('Your cart is empty!', 'error');
    return;
  }
  showToast('🎉 Order placed successfully!', 'success');
  cart = [];
  renderCart();
});

// ===== INIT =====
renderCart();

// اکسپوز کردن addToCart برای استفاده در صفحات دیگر
window.addToCart = addToCart;
window.showToast = showToast;