<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer-master/src/SMTP.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // تشخیص زبان از Referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $is_english = (strpos($referer, '/english/') !== false);
    $lang_dir = $is_english ? 'english' : 'persian';
    header("Location: $lang_dir/pages.php?page=contact");
    exit;
}


// دریافت اطلاعات فرم

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');


// بررسی اطلاعات

if (
    empty($name) ||
    empty($email) ||
    empty($subject) ||
    empty($message)
) {
    // تشخیص زبان از Referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $is_english = (strpos($referer, '/english/') !== false);
    $lang_dir = $is_english ? 'english' : 'persian';
    header("Location: $lang_dir/pages.php?page=contact&error=1");
    exit;
}


// بررسی ایمیل

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // تشخیص زبان از Referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $is_english = (strpos($referer, '/english/') !== false);
    $lang_dir = $is_english ? 'english' : 'persian';
    header("Location: $lang_dir/pages.php?page=contact&error=1");
    exit;
}


$mail = new PHPMailer(true);


try {

    // --------------------------------
    // SMTP Gmail
    // --------------------------------

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    // Gmail خودت
    $mail->Username = 'sajjadgh00888@gmail.com';

    // App Password گوگل
    $mail->Password = 'eqeu fdgv pnid uoqe';

    // TLS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;


    // --------------------------------
    // فرستنده
    // --------------------------------

    $mail->setFrom(
        'sajjadgh00888@gmail.com',
        'Mashahir Iran Website'
    );


    // وقتی Reply می‌زنی به ایمیل کاربر برمی‌گردد

    $mail->addReplyTo(
        $email,
        $name
    );


    // --------------------------------
    // گیرنده
    // --------------------------------

    $mail->addAddress(
        'sajjadgh00888@gmail.com'
    );


    // --------------------------------
    // محتوای ایمیل
    // --------------------------------

    $mail->isHTML(false);

    $mail->CharSet = 'UTF-8';

    $mail->Subject = $subject;


    $mail->Body =
        "پیام جدید از فرم تماس با ما\n\n" .

        "نام و نام خانوادگی:\n" .
        $name .
        "\n\n" .

        "ایمیل:\n" .
        $email .
        "\n\n" .

        "موضوع:\n" .
        $subject .
        "\n\n" .

        "پیام:\n" .
        $message .
        "\n\n" .

        "-------------------------\n" .
        "ارسال شده از وب‌سایت مشاهیر ایران";


    // --------------------------------
    // ارسال
    // --------------------------------

    $mail->send();


    // موفقیت
    // تشخیص زبان از Referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $is_english = (strpos($referer, '/english/') !== false);
    $lang_dir = $is_english ? 'english' : 'persian';
    
    header(
        "Location: $lang_dir/pages.php?page=contact&success=1"
    );

    exit;


} catch (Exception $e) {

    // خطا
    // تشخیص زبان از Referer
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    $is_english = (strpos($referer, '/english/') !== false);
    $lang_dir = $is_english ? 'english' : 'persian';
    
    header(
        "Location: $lang_dir/pages.php?page=contact&error=1"
    );

    exit;
}