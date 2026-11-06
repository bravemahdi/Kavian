<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

// تابع تبدیل تاریخ فارسی به انگلیسی
function convertPersianDateToEnglish($persianDate) {
    if (empty($persianDate)) {
        return '';
    }
    
    // تبدیل اعداد فارسی به انگلیسی
    $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $converted = str_replace($persianNumbers, $englishNumbers, $persianDate);
    
    // حذف فاصله‌های اضافی
    $converted = trim($converted);
    
    // تبدیل واحدهای زمانی با حساسیت بیشتر
    $persianUnits = [
        'ه.ق' => 'AH',
        'هجری قمری' => 'AH',
        'ق.م' => 'BC', // برای تاریخ‌های قبل از میلاد مانند "۵۵۰ ق.م"
        'ق م' => 'BC',
        'قمری' => 'AH',
        'ه.ش' => 'SH',
        'هجری شمسی' => 'SH',
        'ش' => 'SH',
        'شمسی' => 'SH',
        'م' => 'AD',
        'میلادی' => 'AD',
        'پ.م' => 'BC',
        'پیش از میلاد' => 'BC'
    ];
    
    // اول کلمات کامل را جایگزین کنیم
    foreach ($persianUnits as $persian => $english) {
        if (strpos($converted, $persian) !== false) {
            $converted = str_replace($persian, $english, $converted);
        }
    }
    
    // اگر "حدود" در ابتدا وجود دارد، آن را نگه داریم
    if (strpos($converted, 'حدود') === 0) {
        $converted = str_replace('حدود', 'c.', $converted);
    }
    
    // پاکسازی فاصله‌های اضافی
    $converted = preg_replace('/\s+/', ' ', $converted);
    $converted = trim($converted);
    
    // برای تاریخ‌هایی که واحد ندارند ولی اعداد دارند
    if (preg_match('/^\d+$/', $converted)) {
        $converted .= ' AH'; // پیش‌فرض هجری قمری
    }
    
    // فرمت نهایی: اگر عدد داریم و واحد نداریم، واحد اضافه کنیم
    if (preg_match('/\d+$/', $converted) && !preg_match('/(AH|SH|AD|BC)$/i', $converted)) {
        $converted .= ' AH';
    }
    
    return $converted;
}

// تشخیص زبان: اول از پارامتر GET، بعد از Referer، پیش‌فرض انگلیسی
$lang = 'en';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en'])) {
    $lang = $_GET['lang'];
} elseif (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/persian/') !== false) {
    $lang = 'fa';
}
$is_fa = ($lang === 'fa');

try {
    $sql = "SELECT 
        id,
        name_en,
        name_fa,
        full_name_en,
        full_name_fa,
        birth_year,
        birth_place_en,
        birth_place_fa,
        death_year,
        death_place_en,
        death_place_fa,
        job_en,
        job_fa,
        era_en,
        era_fa,
        short_story_en,
        short_story_fa,
        biography_en,
        biography_fa,
        quote_en,
        quote_fa,
        tags_fa,
        tags_en,
        image,
        is_featured as isFeatured
    FROM mashahir_celebrities 
    WHERE is_active = 1 
    ORDER BY sort_order ASC, name_en ASC";

    $stmt = $pdo->query($sql);
    $celebrities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($celebrities as &$celebrity) {

        // فیلدهای زبان‌آگاه
        $celebrity['name']       = $is_fa ? ($celebrity['name_fa']        ?: $celebrity['name_en'])        : $celebrity['name_en'];
        $celebrity['fullName']   = $is_fa ? ($celebrity['full_name_fa']   ?: $celebrity['full_name_en'])   : $celebrity['full_name_en'];
        $celebrity['birthPlace'] = $is_fa ? ($celebrity['birth_place_fa'] ?: $celebrity['birth_place_en']) : $celebrity['birth_place_en'];
        $celebrity['deathPlace'] = $is_fa ? ($celebrity['death_place_fa'] ?: $celebrity['death_place_en']) : $celebrity['death_place_en'];
        $celebrity['job']        = $is_fa ? ($celebrity['job_fa']         ?: $celebrity['job_en'])         : $celebrity['job_en'];
        $celebrity['era']        = $is_fa ? ($celebrity['era_fa']         ?: $celebrity['era_en'])         : $celebrity['era_en'];
        $celebrity['shortStory'] = $is_fa ? ($celebrity['short_story_fa'] ?: $celebrity['short_story_en']) : $celebrity['short_story_en'];
        $celebrity['biography']  = $is_fa ? ($celebrity['biography_fa']   ?: $celebrity['biography_en'])   : $celebrity['biography_en'];
        $celebrity['quote']      = $is_fa ? ($celebrity['quote_fa']       ?: $celebrity['quote_en'])       : $celebrity['quote_en'];
        
        // تگ‌ها به صورت رشته‌ای با کاما جدا
        $celebrity['tags'] = $is_fa ? ($celebrity['tags_fa'] ?: $celebrity['tags_en']) : $celebrity['tags_en'];
        
        // تبدیل رشته تگ‌ها به آرایه
        if (!empty($celebrity['tags'])) {
            $celebrity['categories'] = array_map('trim', explode(',', $celebrity['tags']));
        } else {
            $celebrity['categories'] = [];
        }
        
        // تبدیل تاریخ‌ها از فارسی به انگلیسی برای نسخه انگلیسی
        if ($is_fa) {
            $celebrity['birth'] = $celebrity['birth_year'];
            $celebrity['death'] = $celebrity['death_year'];
        } else {
            // تبدیل تاریخ فارسی به انگلیسی
            $celebrity['birth'] = convertPersianDateToEnglish($celebrity['birth_year']);
            $celebrity['death'] = convertPersianDateToEnglish($celebrity['death_year']);
        }

        // مسیر تصویر
        $celebrity['img'] = '../public/img/' . $celebrity['image'];

        // حذف فیلدهای خام (بعد از اینکه همه تبدیل‌ها انجام شد)
        unset(
            $celebrity['name_en'], $celebrity['name_fa'],
            $celebrity['full_name_en'], $celebrity['full_name_fa'],
            $celebrity['birth_year'],
            $celebrity['birth_place_en'], $celebrity['birth_place_fa'],
            $celebrity['death_year'],
            $celebrity['death_place_en'], $celebrity['death_place_fa'],
            $celebrity['job_en'], $celebrity['job_fa'],
            $celebrity['era_en'], $celebrity['era_fa'],
            $celebrity['short_story_en'], $celebrity['short_story_fa'],
            $celebrity['biography_en'], $celebrity['biography_fa'],
            $celebrity['quote_en'], $celebrity['quote_fa'],
            $celebrity['tags_fa'], $celebrity['tags_en'],
            $celebrity['image']
        );
    }

    echo json_encode([
        'success'  => true,
        'language' => $lang,
        'data'     => $celebrities,
        'count'    => count($celebrities)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
