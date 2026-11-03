<?php
http_response_code(403);

// بررسی اینکه کاربر لاگین است یا نه (برای پیام مناسب)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 | دسترسی ممنوع | مشاهیر</title>
  <link rel="stylesheet" href="/Kavian/errors/error.css">
</head>
<body>
  <main class="error-page">
    <section class="error-card" aria-labelledby="error-title">
      <div class="brand">مشاهیر</div>
      <div class="ornament" aria-hidden="true"><span>✦</span></div>

      <div class="error-code">403</div>
      <h1 class="error-title" id="error-title">دسترسی ممنوع</h1>
      <p class="error-message">
        <?php if (!empty($GLOBALS['_error_custom_message'])): ?>
          <?= htmlspecialchars($GLOBALS['_error_custom_message']) ?>
        <?php elseif ($isLoggedIn): ?>
          حساب کاربری شما مجوز دسترسی به این بخش را ندارد.
        <?php else: ?>
          شما اجازه دسترسی به این صفحه یا این بخش از سایت را ندارید.
        <?php endif; ?>
      </p>

      <div class="actions">
        <?php if ($isLoggedIn): ?>
          <a class="btn btn-primary" href="/Kavian/dashboard/">داشبورد من</a>
        <?php else: ?>
          <a class="btn btn-primary" href="/Kavian/login.php">ورود به حساب</a>
        <?php endif; ?>
        <a class="btn btn-secondary" href="javascript:history.back()">بازگشت به صفحه قبل</a>
      </div>

      <div class="footer-note">سامانه مشاهیر · Kavian</div>
    </section>
  </main>
</body>
</html>
