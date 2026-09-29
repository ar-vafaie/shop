

(function () {
    const categoryGrid = document.getElementById("categoryGrid");
    const categoryProductGrid = document.getElementById("categoryProductGrid");
    const selectedCategoryTitle = document.getElementById("selectedCategoryTitle");
    const noCategoryResult = document.getElementById("noCategoryResult");
    const categorySearch = document.getElementById("categorySearch");

    let currentCategory = "";
    let currentCategoryName = "";
    let searchQuery = "";

    async function renderCategories() {
        try {
            const data = await getData("categories");

            categoryGrid.innerHTML = "";
            data.forEach(cat => {
                const card = document.createElement("div");
                card.className = `category-card ${cat.id === currentCategory ? "active" : ""}`;
                card.dataset.category = cat.id;
                card.innerHTML = `
                    <span class="category-card__emoji">
                        <img src="${cat.img_path}" width="100%" height="100%" style="border-radius:5px" alt="${cat.name}">
                    </span>
                    <div class="category-card__name">${cat.name}</div>
                    <div class="category-card__count">${cat.products_count ?? 0} products</div>
                `;

                card.addEventListener("click", () => {
                    currentCategory = cat.id;
                    currentCategoryName = cat.name;

                    document.querySelectorAll(".category-card")
                        .forEach(c => c.classList.remove("active"));
                    card.classList.add("active");

                    loadProductsByCategory(cat.id, cat.name);
                });

                categoryGrid.appendChild(card);
            });
        } catch (err) {
            console.error("renderCategories failed:", err);
        }
    }

    async function loadProductsByCategory(categoryId, categoryName) {
        try {
            selectedCategoryTitle.textContent = `📌 ${categoryName} (loading...)`;
            noCategoryResult.style.display = "none";
            categoryProductGrid.innerHTML = "";

            const products = await getData(`categories/${categoryId}`);

            if (!products.length) {
                noCategoryResult.style.display = "block";
                selectedCategoryTitle.textContent = `📌 ${categoryName} (0)`;
                return;
            }

            selectedCategoryTitle.textContent = `📌 ${categoryName} (${products.length})`;

            products.forEach(product => {
                const card = document.createElement("div");
                card.className = "product-card";

                const img = product.images?.[0]?.name ?? "/images/placeholder.png";

                card.innerHTML = `
                    <img class="product-card__emoji" src="${img}" alt="${product.name}" width="100%" height="200px" style="border-radius:5px">
                    <h3 class="product-card__name">${product.name}</h3>
                    <div class="product-card__price">${product.price}</div>
                    <button class="product-card__btn" data-id="${product.id}">View Product</button>
                `;

                card.querySelector(".product-card__btn").addEventListener("click", () => {
                    window.location.href = `/product/${product.id}`;
                });

                categoryProductGrid.appendChild(card);
            });
        } catch (err) {
            console.error("loadProductsByCategory failed:", err);
            noCategoryResult.style.display = "block";
            noCategoryResult.textContent = "Failed to load products.";
        }
    }

    categorySearch?.addEventListener("input", (e) => {
        searchQuery = e.target.value.trim();
        // فعلاً فیلتر سمت کلاینت غیرفعاله
    });

    // initial
    renderCategories();

    function updateCartBadge() {
        const badge = document.getElementById("cartBadge");
        if (!badge) return;
        const cart = JSON.parse(localStorage.getItem("cart")) || [];
        badge.textContent = cart.reduce((s, i) => s + i.qty, 0);
    }
    updateCartBadge();
    window.addEventListener("storage", updateCartBadge);
})();

async function getData(path) {
    const response = await fetch(`${BASE_URL}/api/${path}`);
    const data = await response.json();
    if (!response.ok) {
        throw new Error(data?.description || data?.message || "Request failed");
    }
    return data;
}