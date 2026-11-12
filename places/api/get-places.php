
<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';


// تبدیل تاریخ فارسی به انگلیسی
function convertPersianDateToEnglish($persianDate) {

    if (empty($persianDate)) {
        return '';
    }

    // تبدیل اعداد فارسی به انگلیسی
    $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    $converted = str_replace(
        $persianNumbers,
        $englishNumbers,
        $persianDate
    );

    $converted = trim($converted);

    // تبدیل واحدهای زمانی
    $persianUnits = [
        'هجری قمری' => 'AH',
        'ه.ق' => 'AH',
        'قمری' => 'AH',

        'پیش از میلاد' => 'BC',
        'ق.م' => 'BC',
        'ق م' => 'BC',
        'پ.م' => 'BC',

        'هجری شمسی' => 'SH',
        'ه.ش' => 'SH',
        'شمسی' => 'SH',
        'ش' => 'SH',

        'میلادی' => 'AD',
        'م' => 'AD'
    ];

    foreach ($persianUnits as $persian => $english) {
        $converted = str_replace($persian, $english, $converted);
    }

    // تبدیل حدود به c.
    if (strpos($converted, 'حدود') === 0) {
        $converted = str_replace('حدود', 'c.', $converted);
    }

    // پاکسازی فاصله‌ها
    $converted = preg_replace('/\s+/', ' ', $converted);
    $converted = trim($converted);

    // اگر فقط عدد باشد، واحد پیش‌فرض هجری قمری
    if (preg_match('/^\d+$/', $converted)) {
        $converted .= ' AH';
    }

    // اگر عدد داشته باشد اما واحد زمانی نداشته باشد
    if (
        preg_match('/\d+$/', $converted) &&
        !preg_match('/(AH|SH|AD|BC)$/i', $converted)
    ) {
        $converted .= ' AH';
    }

    return $converted;
}


// تشخیص زبان
$lang = 'en';

if (isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en'])) {

    $lang = $_GET['lang'];

} elseif (
    isset($_SERVER['HTTP_REFERER']) &&
    strpos($_SERVER['HTTP_REFERER'], '/persian/') !== false
) {

    $lang = 'fa';

}

$is_fa = ($lang === 'fa');


try {

    // دریافت اطلاعات اماکن
    $sql = "
        SELECT
            id,

            name_en,
            name_fa,

            full_name_en,
            full_name_fa,

            slug,

            province_en,
            province_fa,

            city_en,
            city_fa,

            address_en,
            address_fa,

            latitude,
            longitude,

            established_year,

            era_en,
            era_fa,

            short_story_en,
            short_story_fa,

            description_en,
            description_fa,

            history_en,
            history_fa,

            quote_en,
            quote_fa,

            visiting_hours_en,
            visiting_hours_fa,

            best_season_en,
            best_season_fa,

            tags_en,
            tags_fa,

            image,

            is_featured AS isFeatured

        FROM places

        WHERE is_active = 1

        ORDER BY sort_order ASC, name_en ASC
    ";

    $stmt = $pdo->query($sql);

    $places = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($places as &$place) {

        // فیلدهای وابسته به زبان

        $place['name'] = $is_fa
            ? ($place['name_fa'] ?: $place['name_en'])
            : ($place['name_en'] ?: $place['name_fa']);

        $place['fullName'] = $is_fa
            ? ($place['full_name_fa'] ?: $place['full_name_en'])
            : ($place['full_name_en'] ?: $place['full_name_fa']);

        $place['province'] = $is_fa
            ? ($place['province_fa'] ?: $place['province_en'])
            : ($place['province_en'] ?: $place['province_fa']);

        $place['city'] = $is_fa
            ? ($place['city_fa'] ?: $place['city_en'])
            : ($place['city_en'] ?: $place['city_fa']);

        $place['address'] = $is_fa
            ? ($place['address_fa'] ?: $place['address_en'])
            : ($place['address_en'] ?: $place['address_fa']);

        $place['era'] = $is_fa
            ? ($place['era_fa'] ?: $place['era_en'])
            : ($place['era_en'] ?: $place['era_fa']);

        $place['shortStory'] = $is_fa
            ? ($place['short_story_fa'] ?: $place['short_story_en'])
            : ($place['short_story_en'] ?: $place['short_story_fa']);

        $place['description'] = $is_fa
            ? ($place['description_fa'] ?: $place['description_en'])
            : ($place['description_en'] ?: $place['description_fa']);

        $place['history'] = $is_fa
            ? ($place['history_fa'] ?: $place['history_en'])
            : ($place['history_en'] ?: $place['history_fa']);

        $place['quote'] = $is_fa
            ? ($place['quote_fa'] ?: $place['quote_en'])
            : ($place['quote_en'] ?: $place['quote_fa']);

        $place['visitingHours'] = $is_fa
            ? ($place['visiting_hours_fa'] ?: $place['visiting_hours_en'])
            : ($place['visiting_hours_en'] ?: $place['visiting_hours_fa']);

        $place['bestSeason'] = $is_fa
            ? ($place['best_season_fa'] ?: $place['best_season_en'])
            : ($place['best_season_en'] ?: $place['best_season_fa']);


        // تگ‌ها
        $place['tags'] = $is_fa
            ? ($place['tags_fa'] ?: $place['tags_en'])
            : ($place['tags_en'] ?: $place['tags_fa']);


        // تبدیل تگ‌ها به آرایه
        if (!empty($place['tags'])) {

            $place['categories'] = array_values(
                array_filter(
                    array_map('trim', explode(',', $place['tags']))
                )
            );

        } else {

            $place['categories'] = [];

        }


        // تبدیل تاریخ تأسیس برای نسخه انگلیسی
        $place['established'] = $is_fa
            ? $place['established_year']
            : convertPersianDateToEnglish($place['established_year']);


        // مسیر تصویر
        $place['img'] = !empty($place['image'])
            ? '../public/img/' . $place['image']
            : null;


        // حذف فیلدهای خام
        unset(
            $place['name_en'],
            $place['name_fa'],

            $place['full_name_en'],
            $place['full_name_fa'],

            $place['province_en'],
            $place['province_fa'],

            $place['city_en'],
            $place['city_fa'],

            $place['address_en'],
            $place['address_fa'],

            $place['established_year'],

            $place['era_en'],
            $place['era_fa'],

            $place['short_story_en'],
            $place['short_story_fa'],

            $place['description_en'],
            $place['description_fa'],

            $place['history_en'],
            $place['history_fa'],

            $place['quote_en'],
            $place['quote_fa'],

            $place['visiting_hours_en'],
            $place['visiting_hours_fa'],

            $place['best_season_en'],
            $place['best_season_fa'],

            $place['tags_en'],
            $place['tags_fa'],

            $place['image']
        );

    }

    unset($place);


    // خروجی JSON
    echo json_encode([

        'success' => true,

        'language' => $lang,

        'data' => $places,

        'count' => count($places)

    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        'success' => false,

        'error' => 'Database error'

    ], JSON_UNESCAPED_UNICODE);

}

?>
