async function getData(path) {
    const response = await fetch(`${BASE_URL}admin/api/${path}`);
    const data = await response.json();
    if (!response.ok) {
        throw new Error(data?.description || data?.message || "Request failed");
    }
    return data;
}

const tableEl = document.querySelector("#content"); // فرض: tbody
const paginationEl = document.querySelector(".pagination");

// ===== رندر ردیف‌ها =====
function renderRows(users) {
    if (!tableEl) return;
    tableEl.innerHTML = "";

    users.forEach((user) => {
        tableEl.insertAdjacentHTML(
            "beforeend",
            `
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <img class="avatar-img avatar-sm"
                             src="../assets/images/avatar/avatar-1.jpg"
                             alt="${user.name}">
                        <div>
                            <p class="fw-semibold mb-0">${user.name}</p>
                            <p class="text-muted small mb-0">${user.email}</p>
                        </div>
                    </div>
                </td>
                <td>Admin</td>
                <td>Operations</td>
                <td><span class="badge text-bg-success">Active</span></td>
                <td>Jan 12, 2026</td>
                <td class="text-end">
                    <a class="btn btn-light btn-sm" href="user-details.html">View</a>
                </td>
            </tr>
        `,
        );
    });
}

// ===== ساخت پیجینیشن =====
function renderPaginationLinks(links) {
    if (!paginationEl) return;
    paginationEl.innerHTML = "";

    links.forEach((link) => {
        const li = document.createElement("li");
        li.classList.add("page-item");

        const a = document.createElement("a");
        a.classList.add("page-link");

        const isPrev = link.label.includes("Previous");
        const isNext = link.label.includes("Next");

        if (isPrev || isNext) {
            a.textContent = isPrev ? "Previous" : "Next";

            if (link.url === null) {
                li.classList.add("disabled");
                a.setAttribute("aria-disabled", "true");
            } else {
                a.href = link.url;
            }
        } else {
            a.textContent = link.label;

            if (link.active) {
                li.classList.add("active");
                a.setAttribute("aria-current", "page");
            } else if (link.url) {
                a.href = link.url;
            }
        }

        // ✅ handler درست: یک listener روی هر لینک
        a.addEventListener("click", handlePageClick);

        li.appendChild(a);
        paginationEl.appendChild(li);
    });
}

// ===== کلیک روی پیجینیشن =====
async function handlePageClick(e) {
    // اگر disabled بود، اجازه بده مرورگر رفتار پیش‌فرض رو انجام بده (بی‌اثر)
    if (this.getAttribute("aria-disabled") === "true") {
        e.preventDefault();
        return;
    }

    // اگر href نداره، کاری نکن
    if (!this.href) {
        e.preventDefault();
        return;
    }

    e.preventDefault();

    try {
        const response = await fetch(this.href, {
            headers: { Accept: "application/json" },
        });

        if (!response.ok) {
            throw new Error("Failed to load page");
        }

        const data = await response.json();

        renderRows(data.data);
        renderPaginationLinks(data.links);

        // اختیاری: به بالای جدول برو
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (err) {
        console.error("Pagination click error:", err);
    }
}

// ===== بارگذاری اولیه =====
async function initUsersTable() {
    try {
        const data = await getData("admins");
        renderRows(data.data);
        renderPaginationLinks(data.links);
    } catch (err) {
        console.error("Initial load error:", err);
    }
}

initUsersTable();

// ===== Modal: search users (debounced) & add admin =====
(function () {
    const modalEl = document.getElementById("exampleModal");
    const inputEl = document.getElementById("adminSearchInput");
    const bodyEl = document.getElementById("candidatesBody");
    const statusEl = document.getElementById("candidatesStatus");
    if (!modalEl || !inputEl || !bodyEl || !statusEl) return;

    const DEBOUNCE_MS = 400; // فاصله‌ی بعد از آخرین کلید
    const MIN_CHARS = 2;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    let timer = null;
    let controller = null; // برای لغو درخواست قبلی

    const esc = (s) =>
        String(s ?? "").replace(
            /[&<>"']/g,
            (c) =>
                ({
                    "&": "&amp;",
                    "<": "&lt;",
                    ">": "&gt;",
                    '"': "&quot;",
                    "'": "&#39;",
                })[c],
        );

    function setStatus(msg) {
        statusEl.textContent = msg;
        statusEl.classList.toggle("d-none", !msg);
    }

    function clearResults() {
        bodyEl.innerHTML = "";
    }

    function render(users) {
        bodyEl.innerHTML = users
            .map(
                (u) => `
            <tr>
                <td>
                    <p class="fw-semibold mb-0">${esc(u.name)} ${esc(u.family)}</p>
                    <p class="text-muted small mb-0">${esc(u.email)}</p>
                </td>
                <td>@${esc(u.username)}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-primary btn-sm"
                            data-add-admin data-id="${u.id}">Add</button>
                </td>
            </tr>
        `,
            )
            .join("");
    }

    async function search(q) {
        controller?.abort();
        controller = new AbortController();
        setStatus("Searching...");

        try {
            const res = await fetch(
                `${BASE_URL}admin/api/users/search?q=${encodeURIComponent(q)}`,
                {
                    headers: { Accept: "application/json" },
                    signal: controller.signal,
                },
            );
            if (!res.ok) throw new Error("HTTP " + res.status);

            const users = await res.json();
            clearResults();

            if (!users.length) {
                setStatus("No users found.");
                return;
            }
            setStatus("");
            render(users);
        } catch (err) {
            if (err.name === "AbortError") return; // درخواست جدیدتر جایگزینش شده
            console.error("User search failed:", err);
            clearResults();
            setStatus("Something went wrong. Please try again.");
        }
    }

    // ===== Debounce روی تایپ =====
    inputEl.addEventListener("input", () => {
        clearTimeout(timer);
        const q = inputEl.value.trim();

        if (q.length < MIN_CHARS) {
            controller?.abort();
            clearResults();
            setStatus(q ? `Type at least ${MIN_CHARS} characters.` : "");
            return;
        }
        timer = setTimeout(() => search(q), DEBOUNCE_MS);
    });

    // ===== دکمه‌ی Add (event delegation) =====
    bodyEl.addEventListener("click", async (e) => {
        const btn = e.target.closest("[data-add-admin]");
        if (!btn) return;

        btn.disabled = true;
        try {
            const res = await fetch(
                `${BASE_URL}admin/api/users/${btn.dataset.id}`,
                {
                    method: "PATCH",
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                    },
                    body: JSON.stringify({ is_admin: true }),
                },
            );
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(data.message || "Request failed");

            btn.closest("tr").remove();
            setStatus(data.message);
            if (typeof initUsersTable === "function") initUsersTable();
        } catch (err) {
            btn.disabled = false;
            setStatus(err.message);
        }
    });

    // ===== ریست با بسته شدن مودال =====
    modalEl.addEventListener("hidden.bs.modal", () => {
        clearTimeout(timer);
        controller?.abort();
        inputEl.value = "";
        clearResults();
        setStatus("");
    });
})();
