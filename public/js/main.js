const BASE_URL = 'http://localhost:8000/'

"use strict";

(function () {
  var sidebarStorageKey = "adminHMD.sidebarMini";
  var themeStorageKey = "adminHMD.colorTheme";
  var desktopMedia = "(min-width: 992px)";

  function onReady(callback) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", callback);
      return;
    }

    callback();
  }

  function isDesktop() {
    return window.matchMedia(desktopMedia).matches;
  }

  function canUseStorage() {
    try {
      var testKey = sidebarStorageKey + ".test";
      window.localStorage.setItem(testKey, "1");
      window.localStorage.removeItem(testKey);
      return true;
    } catch (error) {
      return false;
    }
  }

  function getSavedMiniState(storageAvailable) {
    if (!storageAvailable) {
      return false;
    }

    return window.localStorage.getItem(sidebarStorageKey) === "true";
  }

  function saveMiniState(storageAvailable, isMini) {
    if (storageAvailable) {
      window.localStorage.setItem(sidebarStorageKey, String(isMini));
    }
  }

  function getPreferredTheme(storageAvailable) {
    var savedTheme = storageAvailable ? window.localStorage.getItem(themeStorageKey) : "";

    if (savedTheme === "dark" || savedTheme === "light") {
      return savedTheme;
    }

    if (window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches) {
      return "dark";
    }

    return "light";
  }

  onReady(function () {
    var body = document.body;
    var sidebarToggle = document.querySelector("[data-sidebar-toggle]");
    var themeToggles = document.querySelectorAll("[data-theme-toggle]");
    var themeIcons = document.querySelectorAll("[data-theme-icon]");
    var closeButtons = document.querySelectorAll("[data-sidebar-close]");
    var sidebarLinks = document.querySelectorAll(".sidebar-nav .nav-link");
    var mediaQuery = window.matchMedia(desktopMedia);
    var storageAvailable = canUseStorage();

    function initValidation() {
      var forms = document.querySelectorAll(".needs-validation");

      Array.prototype.forEach.call(forms, function (form) {
        form.addEventListener("submit", function (event) {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }

          form.classList.add("was-validated");
        });
      });
    }

    function initTableSearch() {
      var searchInputs = document.querySelectorAll("[data-table-search]");

      Array.prototype.forEach.call(searchInputs, function (input) {
        var tableId = input.getAttribute("data-table-search");
        var table = document.getElementById(tableId);

        if (!table) {
          return;
        }

        input.addEventListener("input", function () {
          var query = input.value.trim().toLowerCase();
          var rows = table.querySelectorAll("tbody tr");

          Array.prototype.forEach.call(rows, function (row) {
            row.hidden = query !== "" && row.textContent.toLowerCase().indexOf(query) === -1;
          });
        });
      });
    }

    function updateThemeControls(theme) {
      var nextTheme = theme === "dark" ? "light" : "dark";
      var label = "Switch to " + nextTheme + " mode";
      var iconClass = theme === "dark" ? "bi bi-sun" : "bi bi-moon-stars";

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.setAttribute("aria-label", label);
        button.setAttribute("title", label);
      });

      Array.prototype.forEach.call(themeIcons, function (icon) {
        icon.className = iconClass;
      });
    }

    function applyTheme(theme) {
      document.documentElement.setAttribute("data-theme", theme);
      document.documentElement.setAttribute("data-bs-theme", theme);

      if (storageAvailable) {
        window.localStorage.setItem(themeStorageKey, theme);
      }

      updateThemeControls(theme);
    }

    function initThemeToggle() {
      applyTheme(getPreferredTheme(storageAvailable));

      Array.prototype.forEach.call(themeToggles, function (button) {
        button.addEventListener("click", function () {
          var currentTheme = document.documentElement.getAttribute("data-theme") === "dark" ? "dark" : "light";
          applyTheme(currentTheme === "dark" ? "light" : "dark");
        });
      });
    }

    initValidation();
    initTableSearch();
    initThemeToggle();

    // Initialize user profile values in UI. Provide a window.adminHMDUser object to override defaults.
    function initUserProfile() {
      var user = window.adminHMDUser || { name: "Admin Hasan", workspace: "Active Workspace", avatar: "../assets/images/avatar/avatar.jpg" };

      var sidebarNameEl = document.querySelector(".sidebar-user strong");
      var sidebarWorkspaceEl = document.querySelector(".sidebar-user small");
      var sidebarAvatar = document.querySelector(".sidebar-user .avatar-img");
      var profileNameEls = document.querySelectorAll(".profile-name");
      var profileAvatarEls = document.querySelectorAll(".profile-button .avatar-img, .profile-button img");

      if (sidebarNameEl) sidebarNameEl.textContent = user.name;
      if (sidebarWorkspaceEl) sidebarWorkspaceEl.textContent = user.workspace;
      if (sidebarAvatar && user.avatar) { sidebarAvatar.src = user.avatar; sidebarAvatar.alt = user.name; }

      Array.prototype.forEach.call(profileNameEls, function (el) { el.textContent = user.name; });
      Array.prototype.forEach.call(profileAvatarEls, function (img) { if (user.avatar) img.src = user.avatar; if (user.name) img.alt = user.name; });
    }

    initUserProfile();

    if (!sidebarToggle) {
      return;
    }

    function setClass(element, className, enabled) {
      if (enabled) {
        element.classList.add(className);
      } else {
        element.classList.remove(className);
      }
    }

    function setToggleExpanded() {
      var expanded = isDesktop()
        ? !body.classList.contains("sidebar-mini")
        : body.classList.contains("sidebar-open");

      sidebarToggle.setAttribute("aria-expanded", String(expanded));
    }

    function closeMobileSidebar() {
      body.classList.remove("sidebar-open");
      setToggleExpanded();
    }

    function toggleSidebar() {
      if (isDesktop()) {
        body.classList.toggle("sidebar-mini");
        saveMiniState(storageAvailable, body.classList.contains("sidebar-mini"));
      } else {
        body.classList.toggle("sidebar-open");
      }

      setToggleExpanded();
    }

    function addCloseHandlers(items) {
      Array.prototype.forEach.call(items, function (item) {
        item.addEventListener("click", function () {
          if (!isDesktop()) {
            closeMobileSidebar();
          }
        });
      });
    }

    if (getSavedMiniState(storageAvailable) && isDesktop()) {
      body.classList.add("sidebar-mini");
    }

    sidebarToggle.addEventListener("click", toggleSidebar);
    addCloseHandlers(closeButtons);
    addCloseHandlers(sidebarLinks);
    setToggleExpanded();

    function handleBreakpointChange() {
      if (isDesktop()) {
        body.classList.remove("sidebar-open");
        setClass(body, "sidebar-mini", getSavedMiniState(storageAvailable));
      } else {
        body.classList.remove("sidebar-mini");
      }

      setToggleExpanded();
    }

    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener("change", handleBreakpointChange);
    } else if (mediaQuery.addListener) {
      mediaQuery.addListener(handleBreakpointChange);
    }
  });
})();




(function () {
    /* ============================================================
       1) File input preview
    ============================================================ */
    const fileInput = document.getElementById('productImage');
    const nameEl    = document.getElementById('fileName');
    const preview   = document.getElementById('filePreview');

    fileInput?.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) {
            nameEl.textContent = 'No file selected';
            preview.style.display = 'none';
            return;
        }
        nameEl.textContent = file.name;

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = ev => {
                preview.src = ev.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    /* ============================================================
       2) Category selection modal
    ============================================================ */
    const modalEl = document.getElementById('categoryModal');
    if (!modalEl) return;

    const openBtn      = document.getElementById('openCategoryModal');
    const listEl       = document.getElementById('categoryList');
    const searchEl     = document.getElementById('categorySearch');
    const emptyEl      = document.getElementById('categoryEmpty');
    const confirmBtn   = document.getElementById('confirmCategories');
    const selectedBox  = document.getElementById('selectedCategories');
    const inputsBox    = document.getElementById('categoryInputs');

    let allCategories   = [];
    let selectedIds     = new Set();
    let tempSelectedIds = new Set();

    const bsModal = new bootstrap.Modal(modalEl);

    // آدرس API از ProductController::categoriesApi
    const API_URL = BASE_URL +'api/categories';

    async function loadCategories() {
        try {
            const res = await fetch(API_URL, {
                headers: { 'Accept': 'application/json' }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            allCategories = await res.json();
        } catch (e) {
            console.error('Failed to load categories:', e);
            allCategories = [];
        }
        renderList();
    }

    function renderList(filter = '') {
        const q = filter.trim().toLowerCase();
        const filtered = allCategories.filter(c =>
            !q || String(c.name).toLowerCase().includes(q)
        );

        listEl.innerHTML = '';
        emptyEl.classList.toggle('d-none', filtered.length > 0);

        filtered.forEach(cat => {
            const item = document.createElement('div');
            item.className = 'category-item' + (tempSelectedIds.has(cat.id) ? ' selected' : '');
            item.dataset.id = cat.id;
            item.innerHTML = `
                <input type="checkbox" class="form-check-input m-0"
                       ${tempSelectedIds.has(cat.id) ? 'checked' : ''}>
                <span>${cat.name}</span>
            `;
            item.addEventListener('click', (e) => {
                e.preventDefault();
                if (tempSelectedIds.has(cat.id)) {
                    tempSelectedIds.delete(cat.id);
                } else {
                    tempSelectedIds.add(cat.id);
                }
                renderList(searchEl.value);
            });
            listEl.appendChild(item);
        });
    }

    function renderSelected() {
        selectedBox.innerHTML = '';
        inputsBox.innerHTML = '';

        selectedIds.forEach(id => {
            const cat = allCategories.find(c => c.id == id);
            if (!cat) return;

            const chip = document.createElement('span');
            chip.className = 'category-chip';
            chip.innerHTML = `${cat.name} <span class="remove-chip" data-id="${id}">×</span>`;
            selectedBox.appendChild(chip);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'categories[]';
            input.value = id;
            inputsBox.appendChild(input);
        });

        selectedBox.querySelectorAll('.remove-chip').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id, 10);
                selectedIds.delete(id);
                renderSelected();
            });
        });
    }

    openBtn?.addEventListener('click', () => {
        tempSelectedIds = new Set(selectedIds);
        searchEl.value = '';
        renderList();
        bsModal.show();
    });

    searchEl?.addEventListener('input', e => renderList(e.target.value));

    confirmBtn?.addEventListener('click', () => {
        selectedIds = new Set(tempSelectedIds);
        renderSelected();
        bsModal.hide();
    });

    loadCategories();
})();


(function () {
    /* ============================================================
       1) Multiple file input preview + remove
    ============================================================ */
    const fileInput     = document.getElementById('productImages');
    const nameEl        = document.getElementById('fileName');
    const previewBox    = document.getElementById('imagesPreview');

    // آرایه‌ی فایل‌های فعلی (چون نمی‌تونیم مستقیم به FileList دست بزنیم)
    let currentFiles = [];

    fileInput?.addEventListener('change', function (e) {
        // فایل‌های جدید رو به لیست فعلی اضافه کن
        const newFiles = Array.from(e.target.files);
        currentFiles = currentFiles.concat(newFiles);
        syncInput();
        renderPreviews();
    });

    function syncInput() {
        // FileList غیرقابل ویرایشه، پس با DataTransfer بازسازی می‌کنیم
        const dt = new DataTransfer();
        currentFiles.forEach(f => dt.items.add(f));
        fileInput.files = dt.files;

        nameEl.textContent = currentFiles.length
            ? `${currentFiles.length} file(s) selected`
            : 'No files selected';
    }

    function renderPreviews() {
        previewBox.innerHTML = '';

        currentFiles.forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;

            const item = document.createElement('div');
            item.className = 'image-preview-item';

            const img = document.createElement('img');
            const reader = new FileReader();
            reader.onload = ev => { img.src = ev.target.result; };
            reader.readAsDataURL(file);

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'remove-preview';
            btn.innerHTML = '×';
            btn.title = 'Remove';
            btn.addEventListener('click', () => {
                currentFiles.splice(index, 1);
                syncInput();
                renderPreviews();
            });

            item.appendChild(img);
            item.appendChild(btn);
            previewBox.appendChild(item);
        });
    }

    // بقیه کد کتگوری رو همین‌جا نگه‌دار (بدون تغییر)
    // ...
})();