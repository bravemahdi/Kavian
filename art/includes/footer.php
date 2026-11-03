
<?
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
?>
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="footer-brand">
                    <h4><?= SITE_NAME ?></h4>
                    <p><?= $current_lang === 'fa' ? 'یک مرجع جامع برای آشنایی با هنر، هنرمندان و میراث ارزشمند هنری ایران' : 'A comprehensive reference for learning about the greats, scientists, poets, artists, and influential figures of Iran.' ?></p>
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
                        <li><a href="#"><?= $current_lang === 'fa' ? 'نقاشی' : 'Scientists' ?></a></li>
                        <li><a href="#"><?= $current_lang === 'fa' ? 'معماری' : 'Poets' ?></a></li>
                        <li><a href="#"><?= $current_lang === 'fa' ? 'ادبیات' : 'Artists' ?></a></li>
                        <li><a href="#"><?= $current_lang === 'fa' ? 'سینما' : 'Heroes' ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="footer-contact">
                    <h5><?= t('contact_us') ?></h5>
                    <p>Email: info@iranianhonar.com</p>
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

<script src="script.js"></script>
<script src="../switch-lang.js"></script>

</body>
</html>