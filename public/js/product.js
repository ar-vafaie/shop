// ===== PRODUCT DETAIL PAGE =====
(function () {
    const root = document.getElementById('productDetail');
    if (!root) return;

    const productId    = root.dataset.productId;
    const productName  = root.dataset.productName;
    const productPrice = root.dataset.productPrice; // "49.99"
    const productImage = root.dataset.productImage;
    const productStock = parseInt(root.dataset.productStock || '0', 10);

    const mainImage    = document.getElementById('mainImage');
    const thumbnails   = document.getElementById('thumbnails');
    const qtyValue     = document.getElementById('qtyValue');
    const qtyDecrease  = document.getElementById('qtyDecrease');
    const qtyIncrease  = document.getElementById('qtyIncrease');
    const addToCartBtn = document.getElementById('addToCartBtn');
    const wishlistBtn  = document.getElementById('wishlistBtn');

    // ===== Thumbnails =====
    if (thumbnails && mainImage) {
        thumbnails.addEventListener('click', (e) => {
            const thumb = e.target.closest('.product-detail__thumb');
            if (!thumb) return;

            thumbnails.querySelectorAll('.product-detail__thumb')
                .forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');

            const src = thumb.dataset.src;
            mainImage.innerHTML =
                `<img class="product-detail__image" src="${src}" alt="${productName}">`;
        });
    }

    // ===== Quantity =====
    let quantity = 1;
    const MAX_QTY = productStock > 0 ? productStock : 1;

    function updateQtyDisplay() {
        if (qtyValue) qtyValue.textContent = quantity;
    }

    qtyDecrease?.addEventListener('click', () => {
        if (quantity > 1) {
            quantity--;
            updateQtyDisplay();
        }
    });

    qtyIncrease?.addEventListener('click', () => {
        if (quantity < MAX_QTY) {
            quantity++;
            updateQtyDisplay();
        } else if (typeof window.showToast === 'function') {
            window.showToast(`Only ${MAX_QTY} in stock`, 'error');
        }
    });

    // ===== Add to Cart =====
    addToCartBtn?.addEventListener('click', () => {
        if (addToCartBtn.disabled) return;

        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existing = cart.find(item => String(item.id) === String(productId));

        if (existing) {
            existing.qty += quantity;
        } else {
            cart.push({
                id:    productId,
                name:  productName,
                price: `$${parseFloat(productPrice).toFixed(2)}`,
                image: productImage,   // ✅ با cart.js هماهنگ
                qty:   quantity,
            });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        const badge = document.getElementById('cartBadge');
        if (badge) {
            badge.textContent = cart.reduce((s, i) => s + i.qty, 0);
        }

        if (typeof window.showToast === 'function') {
            window.showToast(`${productName} × ${quantity} added to cart!`, 'success');
        }
    });

    // ===== Wishlist =====
    wishlistBtn?.addEventListener('click', function () {
        const isWished = this.classList.toggle('active');
        this.textContent = isWished ? '♥ Wishlist' : '♡ Wishlist';
        this.style.color = isWished ? '#e74c3c' : '';

        if (typeof window.showToast === 'function') {
            window.showToast(isWished ? 'Added to wishlist' : 'Removed from wishlist', 'success');
        }
    });
})();