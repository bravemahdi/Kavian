<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'about';
$is_fa = ($current_lang === 'fa');
?>

<?php if ($page === 'about'): ?>
<div class="page-content active" id="pageAbout">
    <div class="container-page">

        <div class="page-header text-center mb-5">
            <h1><i class="fas fa-chess-queen" style="color: #00796B;"></i> درباره ما</h1>
            <p>آشنایی با وب‌سایت اماکن ایران</p>
            <div class="header-line"></div>
        </div>

        <div class="about-card mb-4">
            <h3><i class="fas fa-chess-queen"></i> مشاهیر ایران چیست؟</h3>
            <p>
                <strong>مشاهیر ایران</strong> یک وب‌سایت تخصصی و جامع برای معرفی بزرگان، دانشمندان، شاعران، عارفان،
                هنرمندان و شخصیت‌های تأثیرگذار تاریخ ایران زمین است.
            </p>
            <p>
                در این سایت می‌توانید با زندگی، آثار، اندیشه‌ها و تأثیرات بزرگانی همچون
                <strong>حافظ، سعدی، مولانا، فردوسی، ابن‌سینا، خیام، امیرکبیر، کوروش بزرگ، داریوش بزرگ</strong>
                آشنا شوید.
            </p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="about-card h-100">
                    <h3><i class="fas fa-bullseye"></i> رسالت ما</h3>
                    <p>پاسداشت نام و یاد بزرگان ایران و انتقال میراث ارزشمند آنان به نسل‌های آینده.</p>
                    <ul>
                        <li>معرفی جامع و دقیق مشاهیر ایران</li>
                        <li>حفظ و اشاعه فرهنگ و تاریخ ایرانی</li>
                        <li>ارائه محتوای معتبر و علمی</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="about-card h-100">
                    <h3><i class="fas fa-eye"></i> چشم‌انداز ما</h3>
                    <p>تبدیل شدن به بزرگ‌ترین مرجع دیجیتال مشاهیر ایران در سطح کشور و جهان.</p>
                    <ul>
                        <li>توسعه محتوای چندرسانه‌ای</li>
                        <li>ایجاد بانک اطلاعاتی کامل از مشاهیر</li>
                        <li>ارائه محتوای آموزشی و پژوهشی</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="about-card mb-4">
            <h3><i class="fas fa-gem"></i> ارزش‌های ما</h3>

            <div class="row g-3 mt-2">

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-scroll"></i>
                        <span>تاریخ‌گرایی</span>
                        <small>پاسداشت گذشته و درس‌آموزی از آن</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-feather-alt"></i>
                        <span>ادب‌پروری</span>
                        <small>گسترش فرهنگ و ادب پارسی</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-lightbulb"></i>
                        <span>دانش‌افزایی</span>
                        <small>ارتقای آگاهی و دانش عمومی</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-handshake"></i>
                        <span>همبستگی ملی</span>
                        <small>وحدت و یکپارچگی ایرانیان</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-balance-scale"></i>
                        <span>عدالت‌محوری</span>
                        <small>بی‌طرفی در بازنمایی تاریخ</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-heart"></i>
                        <span>ایران‌دوستی</span>
                        <small>عشق به ایران و افتخار به گذشته</small>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<?php endif; ?>


<?php if ($page === 'contact'): ?>
<div class="page-content active" id="pageContact">

    <div class="container-page">

        <div class="page-header text-center mb-5">
            <h1>
                <i class="fas fa-handshake" style="color: #00796B;"></i>
                تماس با ما
            </h1>

            <p>ما مشتاق شنیدن نظرات، پیشنهادات و انتقادات شما هستیم</p>

            <div class="header-line"></div>
        </div>


        <div class="row g-4 mb-5">

            <div class="col-md-4">
                <div class="contact-card">

                    <div class="icon-circle">
                        <i class="fas fa-envelope"></i>
                    </div>

                    <h5>ایمیل</h5>

                    <p style="direction: ltr;">
                        info@mashahir.ir
                    </p>

                    <p style="direction: ltr; font-size: 0.85rem;">
                        support@mashahir.ir
                    </p>

                </div>
            </div>


            <div class="col-md-4">
                <div class="contact-card">

                    <div class="icon-circle instagram-bg">
                        <i class="fab fa-instagram"></i>
                    </div>

                    <h5>اینستاگرام</h5>

                    <p>@mashahir_ir</p>

                    <p style="font-size: 0.85rem;">
                        دنبال کنید و پیام دهید
                    </p>

                </div>
            </div>


            <div class="col-md-4">
                <div class="contact-card">

                    <div class="icon-circle telegram-bg">
                        <i class="fab fa-telegram-plane"></i>
                    </div>

                    <h5>تلگرام</h5>

                    <p>@mashahir_ir</p>

                    <p style="font-size: 0.85rem;">
                        ارسال پیام در تلگرام
                    </p>

                </div>
            </div>

        </div>


        <div class="row mb-5">

            <div class="col-12">

                <div class="social-section">

                    <h5>
                        <i class="fas fa-share-alt"></i>
                        ما را در شبکه‌های اجتماعی دنبال کنید
                    </h5>

                    <div class="social-buttons">

                        <a href="#" class="social-btn telegram-btn">
                            <i class="fab fa-telegram-plane"></i>
                            تلگرام
                        </a>

                        <a href="#" class="social-btn instagram-btn">
                            <i class="fab fa-instagram"></i>
                            اینستاگرام
                        </a>

                        <a href="#" class="social-btn whatsapp-btn">
                            <i class="fab fa-whatsapp"></i>
                            واتساپ
                        </a>

                        <a href="#" class="social-btn youtube-btn">
                            <i class="fab fa-youtube"></i>
                            یوتیوب
                        </a>

                        <a href="#" class="social-btn aparat-btn">
                            <i class="fas fa-video"></i>
                            آپارات
                        </a>

                        <a href="#" class="social-btn twitter-btn">
                            <i class="fab fa-twitter"></i>
                            توییتر
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-lg-7">

                <div class="form-section">

                    <div class="form-title">
                        <i class="fas fa-pen"></i>
                        ارسال پیام
                    </div>

                    <p class="form-desc">
                        نظرات، پیشنهادات و انتقادات شما باعث بهبود محتوای ما می‌شود.
                    </p>


                    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>

                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            پیام شما با موفقیت ارسال شد.
                        </div>

                    <?php endif; ?>


                    <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>

                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            ارسال پیام انجام نشد. لطفاً دوباره تلاش کنید.
                        </div>

                    <?php endif; ?>


                    <form id="contactForm" action="../../mashahir/sendmessage.php" method="POST">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    نام و نام خانوادگی
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="contactName"
                                    name="name"
                                    placeholder="نام خود را وارد کنید..."
                                    required
                                >

                            </div>


                            <div class="col-md-6">

                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    آدرس ایمیل
                                </label>

                                <input
                                    type="email"
                                    class="form-control"
                                    id="contactEmail"
                                    name="email"
                                    placeholder="ایمیل خود را وارد کنید..."
                                    required
                                >

                            </div>

                        </div>


                        <div class="mb-3 mt-3">

                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                موضوع
                            </label>

                            <select
                                class="form-control"
                                id="contactSubject"
                                name="subject"
                                required
                            >

                                <option value="">
                                    موضوع را انتخاب کنید...
                                </option>

                                <option value="پیشنهاد برای مشاهیر جدید">
                                    پیشنهاد برای مشاهیر جدید
                                </option>

                                <option value="انتقاد و پیشنهاد">
                                    انتقاد و پیشنهاد
                                </option>

                                <option value="همکاری در تولید محتوا">
                                    همکاری در تولید محتوا
                                </option>

                                <option value="سوال درباره مشاهیر">
                                    سوال درباره مشاهیر
                                </option>

                            </select>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                <i class="fas fa-comment"></i>
                                پیام شما
                            </label>

                            <textarea
                                class="form-control"
                                id="contactMessage"
                                name="message"
                                rows="5"
                                placeholder="متن پیام خود را بنویسید..."
                                required
                            ></textarea>

                        </div>


                        <button
                            type="submit"
                            class="btn-submit"
                        >
                            <i class="fas fa-paper-plane"></i>
                            ارسال پیام
                        </button>

                    </form>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="form-section h-100">

                    <h5 class="info-title">
                        <i class="fas fa-info-circle"></i>
                        اطلاعات تماس
                    </h5>


                    <div class="info-item">

                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>

                        <div>

                            <p class="info-label">
                                ساعت پاسخگویی
                            </p>

                            <p class="info-value">
                                روزهای کاری ۹ صبح تا ۱۸
                            </p>

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-icon">
                            <i class="fas fa-reply-all"></i>
                        </div>

                        <div>

                            <p class="info-label">
                                زمان پاسخگویی
                            </p>

                            <p class="info-value">
                                حداکثر ۲۴ ساعت
                            </p>

                        </div>

                    </div>


                    <div class="info-item">

                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>

                        <div>

                            <p class="info-label">
                                تیم ما
                            </p>

                            <p class="info-value">
                                کارشناسان تاریخ و ادب فارسی
                            </p>

                        </div>

                    </div>


                    <hr class="info-divider">


                    <div class="info-quote">

                        <i class="fas fa-quote-right"></i>

                        ما به پیام‌های شما با دقت و احترام پاسخ می‌دهیم.

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<?php endif; ?>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>