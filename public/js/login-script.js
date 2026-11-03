const loginTab = document.getElementById('loginTab');
const signupTab = document.getElementById('signupTab');
const loginForm = document.getElementById('loginForm');
const signupForm = document.getElementById('signupForm');
const switchToSignup = document.getElementById('switchToSignup');
const switchToLogin = document.getElementById('switchToLogin');

function showLogin() {
    loginTab.classList.add('active');
    signupTab.classList.remove('active');
    loginForm.classList.remove('hidden');
    signupForm.classList.add('hidden');
}

function showSignup() {
    signupTab.classList.add('active');
    loginTab.classList.remove('active');
    signupForm.classList.remove('hidden');
    loginForm.classList.add('hidden');
}

loginTab.addEventListener('click', showLogin);
signupTab.addEventListener('click', showSignup);

switchToSignup.addEventListener('click', showSignup);
switchToLogin.addEventListener('click', showLogin);

const urlParams = new URLSearchParams(window.location.search);

// مدیریت خطاهای ورود
if (urlParams.get('error') === '1') {
    const msg = document.getElementById('loginMessage');
    if (msg) {
        msg.textContent = 'ایمیل یا رمز عبور اشتباه است';
        msg.className = 'auth-message error';
    }
}

// مدیریت خطاهای ثبت نام
if (urlParams.get('error') === 'password_mismatch') {
    const msg = document.getElementById('signupMessage');
    if (msg) {
        msg.textContent = 'رمز عبور و تکرار آن مطابقت ندارد';
        msg.className = 'auth-message error';
    }
    showSignup();
}

if (urlParams.get('error') === 'password_length') {
    const msg = document.getElementById('signupMessage');
    if (msg) {
        msg.textContent = 'رمز عبور باید حداقل ۶ کاراکتر باشد';
        msg.className = 'auth-message error';
    }
    showSignup();
}

if (urlParams.get('error') === 'invalid_email') {
    const msg = document.getElementById('signupMessage');
    if (msg) {
        msg.textContent = 'ایمیل وارد شده معتبر نیست';
        msg.className = 'auth-message error';
    }
    showSignup();
}

if (urlParams.get('error') === 'invalid_name') {
    const msg = document.getElementById('signupMessage');
    if (msg) {
        msg.textContent = 'نام باید حداقل ۲ کاراکتر داشته باشد';
        msg.className = 'auth-message error';
    }
    showSignup();
}

if (urlParams.get('error') === 'email_exists') {
    const msg = document.getElementById('signupMessage');
    if (msg) {
        msg.textContent = 'این ایمیل قبلاً ثبت شده است';
        msg.className = 'auth-message error';
    }
    showSignup();
}

// مدیریت موفقیت‌آمیز بودن ثبت نام
if (urlParams.get('register') === 'success') {
    const msg = document.getElementById('loginMessage');
    if (msg) {
        msg.textContent = 'ثبت نام موفق! حالا وارد شوید';
        msg.className = 'auth-message success';
    }
}

// مدیریت تب اولیه
window.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('tab') === 'signup') {
        showSignup();
    }
});

// اعتبارسنجی فرم ثبت نام در سمت کلاینت
const signupFormElement = document.getElementById('signupForm');
if (signupFormElement) {
    signupFormElement.addEventListener('submit', function(e) {
        // اعتبارسنجی قوانین و شرایط
        const termsCheckbox = this.querySelector('input[name="terms"]');
        if (termsCheckbox && !termsCheckbox.checked) {
            e.preventDefault();
            const msg = document.getElementById('signupMessage');
            if (msg) {
                msg.textContent = 'لطفاً با شرایط و قوانین موافقت کنید';
                msg.className = 'auth-message error';
            }
            return false;
        }
        
        // اعتبارسنجی رمز عبور
        const password = this.querySelector('input[name="password"]').value;
        const confirm = this.querySelector('input[name="confirm"]').value;
        
        if (password !== confirm) {
            e.preventDefault();
            const msg = document.getElementById('signupMessage');
            if (msg) {
                msg.textContent = 'رمز عبور و تکرار آن مطابقت ندارد';
                msg.className = 'auth-message error';
            }
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            const msg = document.getElementById('signupMessage');
            if (msg) {
                msg.textContent = 'رمز عبور باید حداقل ۶ کاراکتر باشد';
                msg.className = 'auth-message error';
            }
            return false;
        }
        
        // اعتبارسنجی ایمیل
        const email = this.querySelector('input[name="email"]').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            const msg = document.getElementById('signupMessage');
            if (msg) {
                msg.textContent = 'ایمیل وارد شده معتبر نیست';
                msg.className = 'auth-message error';
            }
            return false;
        }
        
        // اعتبارسنجی نام
        const name = this.querySelector('input[name="name"]').value;
        if (name.length < 2) {
            e.preventDefault();
            const msg = document.getElementById('signupMessage');
            if (msg) {
                msg.textContent = 'نام باید حداقل ۲ کاراکتر داشته باشد';
                msg.className = 'auth-message error';
            }
            return false;
        }
        
        // اعتبارسنجی شماره تلفن (اگر وارد شده)
        const phone = this.querySelector('input[name="phone"]').value;
        if (phone && !/^09[0-9]{9}$/.test(phone)) {
            e.preventDefault();
            const msg = document.getElementById('signupMessage');
            if (msg) {
                msg.textContent = 'شماره تلفن معتبر نیست. فرمت صحیح: 09xxxxxxxxx';
                msg.className = 'auth-message error';
            }
            return false;
        }
        
        // همه چیز درست است
        const msg = document.getElementById('signupMessage');
        if (msg) {
            msg.textContent = 'در حال ثبت نام...';
            msg.className = 'auth-message info';
        }
    });
}