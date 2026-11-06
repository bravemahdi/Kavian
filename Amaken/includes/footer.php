<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
?>
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="footer-brand">
                    <h4><?= SITE_NAME ?></h4>
                    <p><?= $current_lang === 'fa' ? 'یک مرجع جامع برای آشنایی با بزرگان، دانشمندان، شاعران و چهره‌های تأثیرگذار ایران' : 'A comprehensive reference for learning about the greats, scientists, poets, artists, and influential figures of Iran.' ?></p>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="footer-links">
                    <h5><?= t('quick_access') ?></h5>
                    <ul>
                        <li><a href="#"><?= t('home') ?></a></li>
                        <li><a href="#"><?= t('categories') ?></a></li>
                        <li><a href="#"><?= t('about') ?></a></li>
                        <li><a href="#"><?= t('contact') ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="footer-links">
                    <h5><?= t('categories') ?></h5>
                    <ul>
                        <li><a href="#"><?= $current_lang === 'fa' ? 'دانشمندان' : 'Scientists' ?></a></li>
                        <li><a href="#"><?= $current_lang === 'fa' ? 'شاعران' : 'Poets' ?></a></li>
                        <li><a href="#"><?= $current_lang === 'fa' ? 'هنرمندان' : 'Artists' ?></a></li>
                        <li><a href="#"><?= $current_lang === 'fa' ? 'قهرمانان' : 'Heroes' ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="footer-contact">
                    <h5><?= t('contact_us') ?></h5>
                    <p>Email: info@iraniancelebs.com</p>
                    <p>Phone: +98-21-00000000</p>
                    <div class="footer-social">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-telegram"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 - <?= t('footer') ?></p>
        </div>
    </div>
</footer>

<script src="../switch-lang.js"></script>

<script>
// ===== Hero not found toast =====
function heroNotFound(btn) {
    var msg = btn.getAttribute('data-msg');
    var toast = document.getElementById('heroNotFoundToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'heroNotFoundToast';
        toast.className = 'hero-not-found-toast';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(function() { toast.classList.remove('show'); }, 3000);
}
</script>

<?php if ((strpos($_SERVER['REQUEST_URI'], '/persian/') !== false)): ?>
<script>
// ===== Custom Indicators sync برای RTL موبایل =====
(function() {
    function initCustomIndicators(carouselId, indicatorsId) {
        var carouselEl = document.getElementById(carouselId);
        var indicatorsEl = document.getElementById(indicatorsId);
        if (!carouselEl || !indicatorsEl) return;

        var dots = indicatorsEl.querySelectorAll('button');
        var carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);

        // کلیک روی هر نقطه
        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var idx = parseInt(this.getAttribute('data-index'));
                carousel.to(idx);
            });
        });

        // sync نقطه‌ها با slide
        carouselEl.addEventListener('slid.bs.carousel', function(e) {
            var activeIdx = e.to;
            dots.forEach(function(dot) {
                dot.classList.remove('active');
                if (parseInt(dot.getAttribute('data-index')) === activeIdx) {
                    dot.classList.add('active');
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initCustomIndicators('productCarouselMobile', 'productMobileIndicators');
        initCustomIndicators('reviewsCarouselMobile', 'reviewsMobileIndicators');
    });
})();
</script>
<?php endif; ?>

</body>
</html>