<?php
// دریافت URL بازگشت بعد از لاگین
// اولویت: متغیر global از errors_helper → بعد query string → بعد پیش‌فرض
$loginRedirect = '';

if (!empty($GLOBALS['_error_login_redirect'])) {
    $loginRedirect = $GLOBALS['_error_login_redirect'];
} elseif (!empty($_GET['redirect'])) {
    $loginRedirect = $_GET['redirect'];
}

// ساخت URL صفحه لاگین
$loginUrl = '/Kavian/login.php';
if ($loginRedirect !== '') {
    $loginUrl .= '?redirect=' . urlencode($loginRedirect);
}

http_response_code(401);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>401 | نیاز به ورود | مشاهیر</title>
  <link rel="stylesheet" href="/Kavian/errors/error.css">
</head>
<body>
  <main class="error-page">
    <section class="error-card" aria-labelledby="error-title">
      <div class="brand">مشاهیر</div>
      <div class="ornament" aria-hidden="true"><span>✦</span></div>

      <div class="error-code">401</div>
      <h1 class="error-title" id="error-title">نیاز به ورود</h1>
      <p class="error-message">
        <?= !empty($GLOBALS['_error_custom_message'])
            ? htmlspecialchars($GLOBALS['_error_custom_message'])
            : 'برای دسترسی به این بخش باید وارد حساب کاربری خود شوید.' ?>
      </p>

      <div class="actions">
        <a class="btn btn-primary" href="<?= htmlspecialchars($loginUrl, ENT_QUOTES) ?>">ورود به حساب</a>
        <a class="btn btn-secondary" href="/Kavian/">بازگشت به صفحه اصلی</a>
      </div>

      <div class="footer-note">سامانه مشاهیر · Kavian</div>
    </section>
  </main>
</body>
</html>
