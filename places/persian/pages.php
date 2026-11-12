<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';

$page = $_GET['page'] ?? 'about';
$is_fa = ($current_lang === 'fa');

$tr = static function (string $fa, string $en) use ($is_fa): string {
    return $is_fa ? $fa : $en;
};

if (!in_array($page, ['about', 'contact'], true)) {
    $page = 'about';
}
?>

<?php if ($page === 'about'): ?>
<div class="page-content active" id="pageAbout">
    <div class="container-page">

        <div class="page-header text-center mb-5">
            <h1><i class="fas fa-map-marked-alt" style="color: #00796B;"></i> <?= $tr('درباره ما', 'About Us') ?></h1>
            <p><?= $tr('آشنایی با وب‌سایت اماکن ایران', 'Discover the Iran Places website') ?></p>
            <div class="header-line"></div>
        </div>

        <div class="about-card mb-4">
            <h3><i class="fas fa-map-marked-alt"></i> <?= $tr('اماکن ایران چیست؟', 'What is Iran Places?') ?></h3>
            <p>
                <strong><?= $tr('اماکن ایران', 'Iran Places') ?></strong>
                <?= $tr(
                    'وب‌سایتی برای معرفی مکان‌های تاریخی، طبیعی، فرهنگی، مذهبی و گردشگری ایران است. هدف این مجموعه، گردآوری اطلاعات کاربردی و آشنایی بیشتر مخاطبان با جاذبه‌ها و میراث ارزشمند کشور است.',
                    'is a website dedicated to introducing Iran’s historical, natural, cultural, religious, and tourism destinations. Its goal is to bring useful information together and help visitors discover the country’s attractions and heritage.'
                ) ?>
            </p>
            <p>
                <?= $tr(
                    'در این وب‌سایت می‌توانید اطلاعاتی درباره پیشینه و تاریخچه مکان‌ها، موقعیت جغرافیایی، نشانی، ساعات بازدید و بهترین زمان سفر مطالعه کنید.',
                    'You can explore information about each place’s background and history, geographic location, address, visiting hours, and the best time to visit.'
                ) ?>
            </p>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="about-card h-100">
                    <h3><i class="fas fa-bullseye"></i> <?= $tr('رسالت ما', 'Our Mission') ?></h3>
                    <p><?= $tr('کمک به شناخت بهتر جاذبه‌ها و میراث طبیعی و تاریخی ایران از طریق ارائه اطلاعات منظم و قابل دسترس.', 'To make Iran’s natural and historical attractions easier to discover through organized and accessible information.') ?></p>
                    <ul>
                        <li><?= $tr('معرفی مکان‌های دیدنی و گردشگری ایران', 'Introducing Iran’s attractions and destinations') ?></li>
                        <li><?= $tr('گردآوری اطلاعات تاریخی و جغرافیایی مکان‌ها', 'Collecting historical and geographic information') ?></li>
                        <li><?= $tr('کمک به برنامه‌ریزی بهتر برای بازدید و سفر', 'Helping visitors plan trips and visits') ?></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="about-card h-100">
                    <h3><i class="fas fa-eye"></i> <?= $tr('چشم‌انداز ما', 'Our Vision') ?></h3>
                    <p><?= $tr('تبدیل شدن به مرجعی جامع و قابل استفاده برای شناخت و کشف مکان‌های ایران.', 'To become a comprehensive and useful reference for discovering places across Iran.') ?></p>
                    <ul>
                        <li><?= $tr('گسترش بانک اطلاعاتی مکان‌های ایران', 'Expanding the database of places in Iran') ?></li>
                        <li><?= $tr('تکمیل اطلاعات و تصاویر مکان‌ها', 'Enriching place information and photography') ?></li>
                        <li><?= $tr('بهبود تجربه جستجو و کشف مقصدها', 'Improving destination search and discovery') ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="about-card mb-4">
            <h3><i class="fas fa-gem"></i> <?= $tr('ارزش‌های ما', 'Our Values') ?></h3>
            <div class="row g-3 mt-2">
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-landmark"></i>
                        <span><?= $tr('پاسداشت میراث', 'Heritage Preservation') ?></span>
                        <small><?= $tr('توجه به ارزش تاریخی و فرهنگی مکان‌ها', 'Recognizing the historical and cultural value of places') ?></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-map"></i>
                        <span><?= $tr('دقت اطلاعات', 'Information Accuracy') ?></span>
                        <small><?= $tr('تلاش برای ارائه اطلاعات روشن و کاربردی', 'Striving to provide clear and useful information') ?></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-compass"></i>
                        <span><?= $tr('شناخت ایران', 'Discovering Iran') ?></span>
                        <small><?= $tr('آشنایی با جاذبه‌های گوناگون کشور', 'Exploring the country’s diverse attractions') ?></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-leaf"></i>
                        <span><?= $tr('احترام به طبیعت', 'Respect for Nature') ?></span>
                        <small><?= $tr('توجه به ارزش و حفاظت از محیط طبیعی', 'Recognizing the importance of protecting nature') ?></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-users"></i>
                        <span><?= $tr('دسترسی همگانی', 'Accessibility') ?></span>
                        <small><?= $tr('ارائه اطلاعات برای همه علاقه‌مندان', 'Making information available to all interested visitors') ?></small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="value-item">
                        <i class="fas fa-heart"></i>
                        <span><?= $tr('علاقه به ایران', 'Appreciation of Iran') ?></span>
                        <small><?= $tr('معرفی ظرفیت‌ها و زیبایی‌های سرزمین ایران', 'Sharing the richness and beauty of Iran') ?></small>
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
                <i class="fas fa-envelope" style="color: #00796B;"></i>
                <?= $tr('تماس با ما', 'Contact Us') ?>
            </h1>
            <p><?= $tr('نظرات، پیشنهادها و گزارش‌های خود درباره مکان‌های معرفی‌شده را با ما در میان بگذارید.', 'Share your feedback, suggestions, and reports about the places featured on our website.') ?></p>
            <div class="header-line"></div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="icon-circle"><i class="fas fa-map-marked-alt"></i></div>
                    <h5><?= $tr('اطلاعات مکان‌ها', 'Place Information') ?></h5>
                    <p><?= $tr('گزارش اطلاعات نادرست یا ناقص', 'Report incorrect or incomplete information') ?></p>
                    <p style="font-size: 0.85rem;"><?= $tr('به بهبود محتوای سایت کمک کنید', 'Help us improve the website') ?></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-card">
                    <div class="icon-circle instagram-bg"><i class="fas fa-camera"></i></div>
                    <h5><?= $tr('تصاویر مکان‌ها', 'Place Photos') ?></h5>
                    <p><?= $tr('پیشنهاد تصویر برای یک مکان', 'Suggest a photo for a destination') ?></p>
                    <p style="font-size: 0.85rem;"><?= $tr('در معرفی بهتر جاذبه‌ها مشارکت کنید', 'Contribute to better destination profiles') ?></p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-card">
                    <div class="icon-circle telegram-bg"><i class="fas fa-lightbulb"></i></div>
                    <h5><?= $tr('پیشنهاد و همکاری', 'Suggestions & Collaboration') ?></h5>
                    <p><?= $tr('پیشنهاد مکان‌های جدید', 'Suggest new places') ?></p>
                    <p style="font-size: 0.85rem;"><?= $tr('ایده‌های خود را با ما مطرح کنید', 'Share your ideas with us') ?></p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="form-section">
                    <div class="form-title">
                        <i class="fas fa-pen"></i>
                        <?= $tr('ارسال پیام', 'Send a Message') ?>
                    </div>

                    <p class="form-desc">
                        <?= $tr('پیام‌های شما به ما کمک می‌کند اطلاعات مکان‌ها را دقیق‌تر و کامل‌تر کنیم.', 'Your messages help us make place information more accurate and complete.') ?>
                    </p>

                    <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?= $tr('پیام شما با موفقیت ارسال شد.', 'Your message was sent successfully.') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error']) && $_GET['error'] == '1'): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= $tr('ارسال پیام انجام نشد. لطفاً دوباره تلاش کنید.', 'The message could not be sent. Please try again.') ?>
                        </div>
                    <?php endif; ?>

                    <form id="contactForm" action="sendmessage.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-user"></i>
                                    <?= $tr('نام و نام خانوادگی', 'Full Name') ?>
                                </label>
                                <input type="text" class="form-control" id="contactName" name="name"
                                    placeholder="<?= $tr('نام خود را وارد کنید...', 'Enter your name...') ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="fas fa-envelope"></i>
                                    <?= $tr('آدرس ایمیل', 'Email Address') ?>
                                </label>
                                <input type="email" class="form-control" id="contactEmail" name="email"
                                    placeholder="<?= $tr('ایمیل خود را وارد کنید...', 'Enter your email...') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                <?= $tr('موضوع', 'Subject') ?>
                            </label>
                            <select class="form-control" id="contactSubject" name="subject" required>
                                <option value=""><?= $tr('موضوع را انتخاب کنید...', 'Select a subject...') ?></option>
                                <option value="پیشنهاد مکان جدید"><?= $tr('پیشنهاد مکان جدید', 'Suggest a new place') ?></option>
                                <option value="اصلاح اطلاعات مکان"><?= $tr('اصلاح اطلاعات یک مکان', 'Correct place information') ?></option>
                                <option value="ارسال یا اصلاح تصویر"><?= $tr('ارسال یا اصلاح تصویر', 'Submit or correct a photo') ?></option>
                                <option value="انتقاد و پیشنهاد"><?= $tr('انتقاد و پیشنهاد', 'Feedback and suggestions') ?></option>
                                <option value="همکاری"><?= $tr('همکاری', 'Collaboration') ?></option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-comment"></i>
                                <?= $tr('پیام شما', 'Your Message') ?>
                            </label>
                            <textarea class="form-control" id="contactMessage" name="message" rows="5"
                                placeholder="<?= $tr('متن پیام خود را بنویسید...', 'Write your message...') ?>" required></textarea>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i>
                            <?= $tr('ارسال پیام', 'Send Message') ?>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="form-section h-100">
                    <h5 class="info-title">
                        <i class="fas fa-info-circle"></i>
                        <?= $tr('راهنمای ارتباط', 'Contact Information') ?>
                    </h5>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-map-pin"></i></div>
                        <div>
                            <p class="info-label"><?= $tr('گزارش درباره مکان‌ها', 'Place Reports') ?></p>
                            <p class="info-value"><?= $tr('نام مکان و اطلاعات موردنظر برای اصلاح را در پیام بنویسید.', 'Include the place name and the information that needs correction.') ?></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-image"></i></div>
                        <div>
                            <p class="info-label"><?= $tr('پیشنهاد تصویر', 'Photo Suggestions') ?></p>
                            <p class="info-value"><?= $tr('در صورت امکان، نام مکان و منبع تصویر پیشنهادی را ذکر کنید.', 'If possible, include the place name and the source of the suggested photo.') ?></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon"><i class="fas fa-route"></i></div>
                        <div>
                            <p class="info-label"><?= $tr('پیشنهاد مقصد جدید', 'Suggest a Destination') ?></p>
                            <p class="info-value"><?= $tr('نام مکان و شهر یا استان آن را ارسال کنید.', 'Send the place name and its city or province.') ?></p>
                        </div>
                    </div>

                    <hr class="info-divider">

                    <div class="info-quote">
                        <i class="fas fa-quote-right"></i>
                        <?= $tr('با مشارکت شما، معرفی مکان‌های ایران کامل‌تر و دقیق‌تر خواهد شد.', 'Your contributions help make our guide to places in Iran more complete and accurate.') ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
