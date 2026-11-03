<?php
// اگر کاربر لاگین است، نباید صفحه ورود را ببیند
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: /Kavian/dashboard/index.php');
    exit;
}

$pageTitle = 'ورود | میراث ایران';

$redirect = $_GET['redirect'] ?? '/Kavian/';

$tab = $_GET['tab'] ?? 'login';

include 'views/header.php';
?>

<link rel="stylesheet" href="/Kavian/public/css/auth.css">
<section class="auth-section">

    <div class="auth-container">

        <div class="auth-tabs">
            <button class="auth-tab active" id="loginTab">ورود</button>
            <button class="auth-tab" id="signupTab">ثبت نام</button>
        </div>


        <!-- LOGIN -->

        <form
            class="auth-form"
            id="loginForm"
            action="/Kavian/app/auth/login.php"
            method="POST"
        >

            <h2>خوش آمدید</h2>
            <p class="auth-subtitle">
                برای ورود اطلاعات خود را وارد کنید
            </p>

            <input
                type="hidden"
                name="redirect"
                value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="auth-group">
                <label>ایمیل</label>
                <input
                    type="email"
                    name="email"
                    placeholder="example@email.com"
                    required
                >
            </div>

            <div class="auth-group">
                <label>رمز عبور</label>
                <input
                    type="password"
                    name="password"
                    placeholder="••••••••"
                    required
                >
            </div>

            <div class="auth-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    مرا به خاطر بسپار
                </label>
            </div>

            <button type="submit" class="auth-btn">
                ورود
            </button>

            <p class="auth-switch">
                حساب ندارید؟
                <span class="switch-link" id="switchToSignup">
                    ثبت نام کنید
                </span>
            </p>

            <div id="loginMessage" class="auth-message"></div>

        </form>


        <!-- REGISTER -->

        <form
            class="auth-form hidden"
            id="signupForm"
            action="/Kavian/app/auth/register.php"
            method="POST"
        >

            <h2>ایجاد حساب</h2>

            <p class="auth-subtitle">
                برای ثبت نام اطلاعات زیر را تکمیل کنید
            </p>

            <input
                type="hidden"
                name="redirect"
                value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="auth-group">
                <label>نام <span class="required">*</span></label>
                <input
                    type="text"
                    name="name"
                    placeholder="نام"
                    required
                    minlength="2"
                >
            </div>

            <div class="auth-group">
                <label>نام خانوادگی</label>
                <input
                    type="text"
                    name="family"
                    placeholder="نام خانوادگی"
                >
            </div>

            <div class="auth-group">
                <label>ایمیل <span class="required">*</span></label>
                <input
                    type="email"
                    name="email"
                    placeholder="example@email.com"
                    required
                >
            </div>

            <div class="auth-group">
                <label>شماره تلفن</label>
                <input
                    type="tel"
                    name="phone"
                    placeholder="09xxxxxxxxx"
                >
            </div>

            <div class="auth-group">
                <label>رمز عبور <span class="required">*</span></label>
                <input
                    type="password"
                    name="password"
                    placeholder="حداقل 6 کاراکتر"
                    required
                    minlength="6"
                >
            </div>

            <div class="auth-group">
                <label>تکرار رمز عبور <span class="required">*</span></label>
                <input
                    type="password"
                    name="confirm"
                    placeholder="تکرار رمز عبور"
                    required
                    minlength="6"
                >
            </div>

            <div class="auth-terms">
                <label class="terms-label">
                    <input type="checkbox" name="terms" required>
                    با <a href="/Kavian/terms.php" target="_blank">شرایط و قوانین</a> موافقم
                </label>
            </div>

            <button type="submit" class="auth-btn">
                ثبت نام
            </button>

            <p class="auth-switch">
                قبلاً ثبت نام کردید؟
                <span class="switch-link" id="switchToLogin">
                    وارد شوید
                </span>
            </p>

            <div id="signupMessage" class="auth-message"></div>

        </form>

    </div>

</section>

<script src="/Kavian/public/js/login-script.js"></script>

<script>
    const initialTab = <?= json_encode($tab) ?>;

    if (initialTab === 'signup') {
        showSignup();
    }
</script>

<?php include 'views/footer.php'; ?>