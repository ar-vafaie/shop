document.addEventListener('DOMContentLoaded', function () {

    // ===== ELEMENTS =====
    const loginForm    = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const tabs         = document.querySelectorAll('.auth-tab');
    const toast        = document.getElementById('toastMessage');
    let   toastTimeout = null;

    if (!loginForm || !registerForm) return;

    // ===== TAB SWITCHING =====
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const target = tab.dataset.tab;
            document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));

            if (target === 'login') {
                loginForm.classList.add('active');
            } else {
                registerForm.classList.add('active');
            }

            clearAllErrors();
        });
    });

    // ===== SWITCH LINKS =====
    document.getElementById('switchToRegister')?.addEventListener('click', (e) => {
        e.preventDefault();
        tabs.forEach(t => t.classList.remove('active'));
        document.querySelector('[data-tab="register"]')?.classList.add('active');
        document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
        registerForm.classList.add('active');
        clearAllErrors();
    });

    document.getElementById('switchToLogin')?.addEventListener('click', (e) => {
        e.preventDefault();
        tabs.forEach(t => t.classList.remove('active'));
        document.querySelector('[data-tab="login"]')?.classList.add('active');
        document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
        loginForm.classList.add('active');
        clearAllErrors();
    });

    // ===== TOAST (4s) =====
    function showToast(message, type = 'error') {
        if (!toast) return;

        if (toastTimeout) {
            clearTimeout(toastTimeout);
            toast.classList.remove('show');
        }

        toast.textContent = message;
        toast.className = 'toast-message';
        toast.classList.add(type);

        void toast.offsetWidth;
        toast.classList.add('show');

        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
            toastTimeout = null;
        }, 4000);
    }

    // ===== HELPERS =====
    function setError(id, message) {
        const el = document.getElementById(id);
        if (el) el.textContent = message;
    }

    function clearAllErrors() {
        document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
    }

    function clearFormErrors(form) {
        form.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidUsername(username) {
        return /^[a-zA-Z0-9_]{3,20}$/.test(username);
    }

    function isValidPassword(pass) {
        // حداقل ۶ کاراکتر، حداقل یک حرف بزرگ، حداقل یک رقم
        return /^(?=.*[A-Z])(?=.*\d).{6,}$/.test(pass);
    }

    // ===== LOGIN VALIDATION =====
    loginForm.addEventListener('submit', (e) => {
        clearFormErrors(loginForm);

        const email    = document.getElementById('loginEmail').value.trim();
        const password = document.getElementById('loginPassword').value.trim();

        let hasError = false;

        if (!email) {
            setError('loginEmailError', 'Email is required.');
            hasError = true;
        } else if (!isValidEmail(email)) {
            setError('loginEmailError', 'Please enter a valid email address.');
            hasError = true;
        }

        if (!password) {
            setError('loginPasswordError', 'Password is required.');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            showToast('Please fix the errors below.', 'error');
            return;
        }

        // اگر معتبر بود، اجازه بده فرم به سرور ارسال شه
    });

    // ===== REGISTER VALIDATION =====
    registerForm.addEventListener('submit', (e) => {
        clearFormErrors(registerForm);

        const name     = document.getElementById('regName').value.trim();
        const family   = document.getElementById('regFamily').value.trim();
        const username = document.getElementById('regUsername').value.trim();
        const email    = document.getElementById('regEmail').value.trim();
        const password = document.getElementById('regPassword').value;
        const confirm  = document.getElementById('regConfirm').value;

        let hasError = false;

        // Name
        if (!name) {
            setError('regNameError', 'Name is required.');
            hasError = true;
        } else if (name.length < 2) {
            setError('regNameError', 'Name must be at least 2 characters.');
            hasError = true;
        }

        // Family
        if (!family) {
            setError('regFamilyError', 'Family is required.');
            hasError = true;
        } else if (family.length < 2) {
            setError('regFamilyError', 'Family must be at least 2 characters.');
            hasError = true;
        }

        // Username
        if (!username) {
            setError('regUsernameError', 'Username is required.');
            hasError = true;
        } else if (!isValidUsername(username)) {
            setError('regUsernameError', '3–20 chars: letters, numbers, underscore only.');
            hasError = true;
        }

        // Email
        if (!email) {
            setError('regEmailError', 'Email is required.');
            hasError = true;
        } else if (!isValidEmail(email)) {
            setError('regEmailError', 'Please enter a valid email address.');
            hasError = true;
        }

        // Password
        if (!password) {
            setError('regPasswordError', 'Password is required.');
            hasError = true;
        } else if (!isValidPassword(password)) {
            setError('regPasswordError', 'Min 6 chars, 1 uppercase & 1 number.');
            hasError = true;
        }

        // Confirm
        if (!confirm) {
            setError('regConfirmError', 'Please confirm your password.');
            hasError = true;
        } else if (password !== confirm) {
            setError('regConfirmError', 'Passwords do not match.');
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            showToast('Please fix the errors below.', 'error');
            return;
        }

        // اگر معتبر بود، اجازه بده فرم به سرور ارسال شه
    });

    // ===== REAL-TIME CLEAR (اختیاری) =====
    document.querySelectorAll('.form-group input').forEach(input => {
        input.addEventListener('input', () => {
            const group = input.closest('.form-group');
            const errorEl = group?.querySelector('.error-msg');
            if (errorEl) errorEl.textContent = '';
        });
    });

    // Expose برای استفاده‌ی احتمالی
    window.showAuthToast = showToast;
});