

async function getData(path) {
    const response = await fetch(`${BASE_URL}admin/api/${path}`);
    const data = await response.json();
    if (!response.ok) {
        throw new Error(data?.description || data?.message || "Request failed");
    }
    return data;
}



const tableEl      = document.querySelector('#content');       // فرض: tbody
const paginationEl = document.querySelector('.pagination');

// ===== رندر ردیف‌ها =====
function renderRows(users) {
    if (!tableEl) return;
    tableEl.innerHTML = '';

    users.forEach(user => {
        tableEl.insertAdjacentHTML('beforeend', `
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
        `);
    });
}

// ===== ساخت پیجینیشن =====
function renderPaginationLinks(links) {
    if (!paginationEl) return;
    paginationEl.innerHTML = '';

    links.forEach(link => {
        const li = document.createElement('li');
        li.classList.add('page-item');

        const a = document.createElement('a');
        a.classList.add('page-link');

        const isPrev = link.label.includes('Previous');
        const isNext = link.label.includes('Next');

        if (isPrev || isNext) {
            a.textContent = isPrev ? 'Previous' : 'Next';

            if (link.url === null) {
                li.classList.add('disabled');
                a.setAttribute('aria-disabled', 'true');
            } else {
                a.href = link.url;
            }
        } else {
            a.textContent = link.label;

            if (link.active) {
                li.classList.add('active');
                a.setAttribute('aria-current', 'page');
            } else if (link.url) {
                a.href = link.url;
            }
        }

        // ✅ handler درست: یک listener روی هر لینک
        a.addEventListener('click', handlePageClick);

        li.appendChild(a);
        paginationEl.appendChild(li);
    });
}

// ===== کلیک روی پیجینیشن =====
async function handlePageClick(e) {
    // اگر disabled بود، اجازه بده مرورگر رفتار پیش‌فرض رو انجام بده (بی‌اثر)
    if (this.getAttribute('aria-disabled') === 'true') {
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
            headers: { 'Accept': 'application/json' },
        });

        if (!response.ok) {
            throw new Error('Failed to load page');
        }

        const data = await response.json();

        renderRows(data.data);
        renderPaginationLinks(data.links);

        // اختیاری: به بالای جدول برو
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (err) {
        console.error('Pagination click error:', err);
    }
}

// ===== بارگذاری اولیه =====
async function initUsersTable() {
    try {
        const data = await getData('admins');
        renderRows(data.data);
        renderPaginationLinks(data.links);
    } catch (err) {
        console.error('Initial load error:', err);
    }
}

initUsersTable();



