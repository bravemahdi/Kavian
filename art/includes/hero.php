<?
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';

$is_fa = ($current_lang === 'fa');
?>

<header class="hero-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php if ($is_fa): ?>
                <div class="carousel-item active">
                    <img src="<?= IMG_PATH ?>banner1.png" class="d-block w-100 hero-img" alt="ابن‌سینا">
                    <div class="hero-overlay"></div>
                    <div class="carousel-caption custom-caption fade-in">
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= IMG_PATH ?>banner2.png" class="d-block w-100 hero-img" alt="خیام">
                    <div class="hero-overlay"></div>
                    <div class="carousel-caption custom-caption fade-in">
                    </div>
                </div>
            
                
            <?php else: ?>
                <div class="carousel-item active">
                    <img src="<?= IMG_PATH ?>banner1.png" class="d-block w-100 hero-img" alt="Avicenna">
                    <div class="hero-overlay"></div>
                    <div class="carousel-caption custom-caption fade-in">
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="<?= IMG_PATH ?>banner2.png" class="d-block w-100 hero-img" alt="Khayyam">
                    <div class="hero-overlay"></div>
                    <div class="carousel-caption custom-caption fade-in">
                    </div>
                </div>
              
            <?php endif; ?>
        </div>
    </div>
</header>