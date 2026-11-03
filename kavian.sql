-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 10, 2026 at 11:33 AM
-- Server version: 13.0.2-MariaDB
-- PHP Version: 8.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kavian`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `product_type` enum('print','pdf') NOT NULL DEFAULT 'print',
  `pdf_lang` varchar(10) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(12,0) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `celebrity_categories`
--

CREATE TABLE `celebrity_categories` (
  `id` int(11) NOT NULL,
  `celebrity_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `celebrity_categories`
--

INSERT INTO `celebrity_categories` (`id`, `celebrity_id`, `category_id`, `is_primary`) VALUES
(1, 1, 1, 1),
(2, 1, 6, 0),
(3, 2, 1, 1),
(4, 2, 12, 0),
(5, 3, 1, 1),
(6, 3, 6, 0),
(7, 4, 1, 1),
(8, 4, 7, 0),
(9, 5, 1, 0),
(10, 5, 4, 1),
(11, 5, 5, 0),
(12, 6, 3, 1),
(13, 6, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'رضا کریمی', 'reza@email.com', 'پیشنهاد همکاری', 'سلام، آیا امکان همکاری در پروژه‌های فرهنگی وجود دارد؟', 0, '2026-08-18 16:26:01'),
(2, 'سارا حسینی', 'sara@email.com', 'درخواست اطلاعات', 'لطفاً اطلاعات بیشتر درباره نحوه مشارکت در سایت ارائه دهید.', 1, '2026-08-18 16:26:01');

-- --------------------------------------------------------

--
-- Table structure for table `hero_carousel`
--

CREATE TABLE `hero_carousel` (
  `id` int(11) NOT NULL,
  `celebrity_id` int(11) DEFAULT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `subtitle_fa` varchar(255) DEFAULT NULL,
  `subtitle_en` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `image_mobile` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_carousel`
--

INSERT INTO `hero_carousel` (`id`, `celebrity_id`, `title_fa`, `title_en`, `subtitle_fa`, `subtitle_en`, `image`, `image_mobile`, `link_url`, `sort_order`, `is_active`) VALUES
(1, 6, 'ابن‌سینا', 'Avicenna', 'پدر طب ایرانی', 'Father of Persian Medicine', 'd2.png', NULL, 'profile.php?id=6', 1, 1),
(2, 5, 'عمر خیام', 'Omar Khayyam', 'نابغه ریاضیات و نجوم', 'Genius of Mathematics and Astronomy', 'd1.png', NULL, 'profile.php?id=5', 2, 1),
(3, NULL, 'محمدرضا شجریان', 'Mohammad-Reza Shajarian', 'استاد بزرگ آواز سنتی ایران', 'master of traditional Persian classical music', 'd5.png', NULL, 'profile.php?id=4', 3, 1),
(4, NULL, 'سهراب سپهری', 'Sohrab Sepehri', 'عارف نگاه و شاعر سکوت', ' Mystic of Vision and Poet of Silence', 'd3.png', NULL, NULL, 4, 1),
(5, NULL, 'غلامرضا تختی', 'Gholamreza Takhti', 'اسطورهٔ اخلاق و نمادِ پهلوانی', 'Legend of Ethics and Symbol of Heroism', 'd4.png', NULL, NULL, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `home_hero_carousel`
--

CREATE TABLE `home_hero_carousel` (
  `id` int(11) NOT NULL,
  `title_fa` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `subtitle_fa` varchar(255) DEFAULT NULL,
  `subtitle_en` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `image_mobile` varchar(255) DEFAULT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `home_hero_carousel`
--

INSERT INTO `home_hero_carousel` (`id`, `title_fa`, `title_en`, `subtitle_fa`, `subtitle_en`, `image`, `image_mobile`, `link_url`, `sort_order`, `is_active`) VALUES
(1, 'خلیج پارس', 'Persian Gulf', 'میراث دیرینه تمدن ایران', 'Ancient Heritage of Persian Civilization', 'khalij-pars.png', 'khalij-pars-mobile.jpg', NULL, 1, 1),
(3, NULL, NULL, NULL, NULL, 'honar.png', 'honar-mobile.png', NULL, 3, 1),
(4, NULL, NULL, NULL, NULL, 'varzesh.png', 'varzesh-mobile.png', NULL, 4, 1),
(5, NULL, NULL, NULL, NULL, 'afrad.png', 'afrad-mobile.png', NULL, 5, 1),
(6, NULL, NULL, NULL, NULL, 'tecnology.png', 'tecnology-mobile.png', NULL, 6, 1),
(7, NULL, NULL, NULL, NULL, 'makan.png', 'makan-mobile.png', NULL, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mashahir_categories`
--

CREATE TABLE `mashahir_categories` (
  `id` int(11) NOT NULL,
  `name_fa` varchar(100) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mashahir_categories`
--

INSERT INTO `mashahir_categories` (`id`, `name_fa`, `name_en`, `image`, `sort_order`, `is_active`) VALUES
(1, 'نظامی ودفاعی', 'Military & Defense', 'nazami1.png', 1, 1),
(2, 'دین و عرفان ', 'Religion & Mysticism', 'din1.png', 2, 1),
(3, 'سیاست و حکومت', 'Politics & Government', 'siasat1.png', 3, 1),
(4, 'ادبیات', 'Literature', 'adabiat1.png', 4, 1),
(5, 'علم', 'Science', 'elm1.png', 5, 1),
(6, 'هنر', 'Art', 'honar1.png', 6, 1),
(7, 'موسیقی', 'Music', 'mosighi1.png', 7, 1),
(8, 'ورزش ', 'Sports', 'varzesh1.png', 8, 1),
(9, 'مهندسی و فناوری', 'Engineering & Technology', 'mohandesi1.png', 9, 1),
(10, 'جغرافیا و جهانگردی', 'Geography & Travel', 'joghrafia1.png', 10, 1),
(11, 'روزنامه‌نگاری', 'Journalism', 'ruznameh_negari1.png', 11, 1),
(12, 'دیگر حوزه‌ها ', 'Other Fields', 'other1.png', 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mashahir_celebrities`
--

CREATE TABLE `mashahir_celebrities` (
  `id` int(11) NOT NULL,
  `name_fa` varchar(100) NOT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `full_name_fa` varchar(255) DEFAULT NULL,
  `full_name_en` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `birth_year` varchar(50) DEFAULT NULL,
  `birth_place_fa` varchar(255) DEFAULT NULL,
  `birth_place_en` varchar(255) DEFAULT NULL,
  `death_year` varchar(50) DEFAULT NULL,
  `death_place_fa` varchar(255) DEFAULT NULL,
  `death_place_en` varchar(255) DEFAULT NULL,
  `job_fa` varchar(255) DEFAULT NULL,
  `job_en` varchar(255) DEFAULT NULL,
  `era_fa` varchar(100) DEFAULT NULL,
  `era_en` varchar(100) DEFAULT NULL,
  `short_story_fa` text DEFAULT NULL,
  `short_story_en` text DEFAULT NULL,
  `biography_fa` longtext DEFAULT NULL,
  `biography_en` longtext DEFAULT NULL,
  `quote_fa` text DEFAULT NULL,
  `quote_en` text DEFAULT NULL,
  `tags_fa` text DEFAULT NULL,
  `tags_en` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `views_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mashahir_celebrities`
--

INSERT INTO `mashahir_celebrities` (`id`, `name_fa`, `name_en`, `full_name_fa`, `full_name_en`, `slug`, `birth_year`, `birth_place_fa`, `birth_place_en`, `death_year`, `death_place_fa`, `death_place_en`, `job_fa`, `job_en`, `era_fa`, `era_en`, `short_story_fa`, `short_story_en`, `biography_fa`, `biography_en`, `quote_fa`, `quote_en`, `tags_fa`, `tags_en`, `image`, `is_featured`, `is_active`, `sort_order`, `views_count`, `created_at`, `updated_at`) VALUES
(1, 'ابوریحان بیرونی', 'Abu Rayhan al-Biruni', 'ابوریحان محمد بن احمد بیرونی', 'Abu Rayhan Muhammad ibn Ahmad al-Biruni', 'abu-rayhan-biruni', '۳۵۲ ه.ق', 'خوارزم', 'Khwarazm', '۴۴۰ ه.ق', 'غزنین', 'Ghazni', 'دانشمند، ریاضیدان، ستاره‌شناس', 'Scientist, Mathematician, Astronomer', 'اسلامی', 'Islamic', 'ابوریحان بیرونی در سال ۳۵۲ هجری قمری در خوارزم متولد شد.', 'Abu Rayhan al-Biruni was born in Khwarazm in 352 AH.', 'بیرونی در خوارزم به دنیا آمد و در جوانی به مطالعه علوم مختلف پرداخت.', 'Al-Biruni was born in Khwarazm and studied various sciences in his youth.', 'آدمی موجودی است که به سوی دانستن کشیده می‌شود', 'Man is drawn to knowledge', 'علم,ریاضیدان,ستاره‌شناس,تاریخ‌نگار', 'Science,Mathematician,Astronomer,Historian', 'prof16.png', 0, 1, 9, 0, '2026-08-24 15:47:18', '2026-08-25 13:45:33'),
(2, 'سعدی شیرازی', 'Saadi Shirazi', 'شیخ مصلح‌الدین سعدی شیرازی', 'Sheikh Muslih al-Din Saadi Shirazi', 'saadi-shirazi', '۵۸۵ ه.ق', 'شیراز', 'Shiraz', '۶۹۱ ه.ق', 'شیراز', 'Shiraz', 'شاعر، نویسنده', 'Poet, Writer', 'اسلامی', 'Islamic', 'شیخ مصلح‌الدین سعدی شیرازی در شیراز متولد شد.', 'Sheikh Muslih al-Din Saadi Shirazi was born in Shiraz.', 'سعدی در خانواده‌ای روحانی در شیراز به دنیا آمد.', 'Saadi was born into a religious family in Shiraz.', 'بنی‌آدم اعضای یکدیگرند', 'Human beings are members of one another', 'ادبیات,شاعر,حکیم,نویسنده ', 'Literature,Poet,Philosopher,Writer', 'prof13.png', 0, 1, 15, 0, '2026-08-24 15:47:18', '2026-08-25 13:45:43'),
(3, 'مولانا جلال‌الدین بلخی', 'Jalal al-Din Rumi', 'جلال‌الدین محمد بلخی (مولوی)', 'Jalal al-Din Muhammad Rumi', 'jalal-al-din-rumi', '۶۰۴ ه.ق', 'بلخ', 'Balkh', '۶۷۲ ه.ق', 'قونیه', 'Konya', 'شاعر، عارف', 'Poet, Mystic', 'اسلامی', 'Islamic', 'مولانا جلال‌الدین محمد بلخی در سال ۶۰۴ هجری قمری در شهر بلخ به دنیا آمد.', 'Rumi was born in Balkh in 604 AH.', 'مولانا در خانواده‌ای از علما و عرفا به دنیا آمد.', 'Rumi was born into a family of scholars and mystics.', 'بشنو از نی چون حکایت می‌کند', 'Listen to the reed as it tells its tale', 'ادبیات,عارف,فیلسوف', 'Literature,Mystic,Philosopher', 'prof2.png', 1, 1, 3, 0, '2026-08-24 15:47:18', '2026-08-25 07:02:16'),
(4, 'فردوسی توسی', 'Ferdowsi Tusi', 'ابوالقاسم فردوسی توسی', 'Abul-Qasim Ferdowsi Tusi', 'ferdowsi-tusi', '۳۲۹ ه.ق', 'توس', 'Tus', '۴۱۶ ه.ق', 'توس', 'Tus', 'ادبیات,حماسه‌سرا,نویسنده', 'Literature,Epic Poet,Writer', 'اسلامی', 'Islamic', 'ابوالقاسم فردوسی توسی در سال ۳۲۹ هجری قمری در روستای پاژ در نزدیکی توس به دنیا آمد.', 'Abul-Qasim Ferdowsi Tusi was born in the village of Paj near Tus in 329 AH.', 'فردوسی در خانواده‌ای از دهقانان که به فرهنگ ایران باستان علاقه داشتند به دنیا آمد.', 'Ferdowsi was born into a family of landowners who were interested in ancient Persian culture.', 'توانا بود هرکه دانا بود', 'He who has knowledge has power', 'نظامی ودفاعی,موسیقی', 'Military & Defense,Music', 'prof1.png', 0, 1, 4, 0, '2026-08-24 15:47:18', '2026-08-25 07:16:45'),
(5, 'عمر خیام نیشابوری', 'Omar Khayyam', 'عمر خیام نیشابوری', 'Omar Khayyam Neyshaburi', 'omar-khayyam', '۴۳۹ ه.ق', 'نیشابور', 'Neyshabur', '۵۱۷ ه.ق', 'نیشابور', 'Neyshabur', 'شاعر، ریاضیدان، ستاره‌شناس', 'Poet, Mathematician, Astronomer', 'اسلامی', 'Islamic', 'عمر خیام نیشابوری در سال ۴۳۹ هجری قمری در نیشابور به دنیا آمد.', 'Omar Khayyam was born in Neyshabur in 439 AH.', 'خیام در نیشابور به دنیا آمد و در همان شهر رشد یافت.', 'Khayyam was born and raised in Neyshabur.', 'چندین به جهان سخن ز مخزن برخاست', 'Many have spoken of the mysteries of existence', 'ادبیات,ریاضیدان,ستاره‌شناس', 'Literature,Mathematician,Astronomer', 'prof3.png', 0, 1, 5, 0, '2026-08-24 15:47:18', '2026-08-25 07:17:54'),
(6, 'فریدالدین عطار نیشابوری', 'Farid al-Din Attar', 'فریدالدین عطار نیشابوری', 'Farid al-Din Attar Neyshaburi', 'farid-al-din-attar', '۵۴۰ ه.ق', 'نیشابور', 'Neyshabur', '۶۱۸ ه.ق', 'نیشابور', 'Neyshabur', 'شاعر، عارف', 'Poet, Mystic', 'اسلامی', 'Islamic', 'فریدالدین عطار نیشابوری در سال ۵۴۰ هجری قمری در نیشابور متولد شد.', 'Farid al-Din Attar was born in Neyshabur in 540 AH.', 'عطار در نیشابور به دنیا آمد و در جوانی به شغل عطاری مشغول بود.', 'Attar was born in Neyshabur and worked as a perfumer in his youth.', 'چو بشنید از او مرغکان سخن', 'When he heard the words of the birds', 'ادبیات,عارف ', 'Literature,Mystic', 'prof9.png', 0, 1, 6, 0, '2026-08-24 15:47:18', '2026-08-25 08:45:53'),
(7, 'ابوعلی سینا (ابن‌سینا)', 'Avicenna (Ibn Sina)', 'ابوعلی سینا (ابن‌سینا)', 'Abu Ali Sina (Avicenna)', 'abu-ali-sina', '۳۵۹ ه.ق', 'بخارا', 'Bukhara', '۴۲۸ ه.ق', 'همدان', 'Hamadan', 'پزشک، فیلسوف، دانشمند', 'Physician, Philosopher, Scientist', 'اسلامی', 'Islamic', 'ابوعلی سینا در سال ۳۵۹ هجری قمری در بخارا به دنیا آمد.', 'Abu Ali Sina was born in Bukhara in 359 AH.', 'ابن‌سینا در خانواده‌ای از اهل علم متولد شد.', 'Avicenna was born into a family of scholars.', 'العالم یدرک بالعقل و لا یدرک بالحس', 'The world is perceived by reason, not by sense', 'علم,پزشک,فیلسوف,ریاضیدان', 'Science,Physician,Philosopher,Mathematician', 'prof6.png', 0, 1, 7, 0, '2026-08-24 15:47:18', '2026-08-25 08:47:47'),
(8, 'پروین اعتصامی', 'Parvin Etesami', 'پروین اعتصامی', 'Parvin Etesami', 'parvin-etesami', '۱۲۸۵ شمسی', 'تبریز', 'Tabriz', '۱۳۲۰ شمسی', 'تهران', 'Tehran', 'شاعر', 'Poet', 'معاصر', 'Modern', 'پروین اعتصامی در سال ۱۲۸۵ شمسی در تبریز متولد شد.', 'Parvin Etesami was born in Tabriz in 1285 SH.', 'پروین در تبریز به دنیا آمد و در تهران تحصیل کرد.', 'Parvin was born in Tabriz and educated in Tehran.', 'میان شاخه‌های نورس، به بوی صبحدم', 'Among the tender branches, in the scent of dawn', 'ادبیات,شاعر,نویسنده', 'Literature,Poet,Writer', 'prof8.png', 1, 1, 8, 0, '2026-08-24 15:47:18', '2026-08-25 09:04:18'),
(9, 'داریوش بزرگ', 'Darius the Great', 'داریوش بزرگ (داریوش اول هخامنشی)', 'Darius the Great (Darius I Achaemenid)', 'darius-the-great', '۵۵۰ ق.م', 'پاسارگاد', 'Pasargadae', '۴۸۶ ق.م', 'تخت جمشید', 'Persepolis', 'پادشاه', 'King', 'پیش از اسلام', 'Pre-Islamic', 'داریوش بزرگ در سال ۵۵۰ پیش از میلاد متولد شد.', 'Darius the Great was born in 550 BC.', 'داریوش از خاندان هخامنشی بود و پس از مرگ کمبوجیه به پادشاهی رسید.', 'Darius was from the Achaemenid dynasty and became king after the death of Cambyses.', 'آنچه را که باید انجام شود، با دستان خود انجام می‌دهم', 'What must be done, I do with my own hands', 'سیاست,پادشاه,فاتح,سازنده', 'Politics,King,Conqueror,Builder', 'prof18.png', 0, 1, 2, 0, '2026-08-24 15:47:18', '2026-08-25 13:44:58'),
(10, 'شیخ بهایی', 'Sheikh Bahai', 'بهاءالدین محمد بن حسین عاملی (شیخ بهایی)', 'Bahāʾ al-Dīn Muhammad ibn Husayn al-ʿĀmilī (Sheikh Bahai)', 'sheikh-bahai', '۹۵۳ ه.ق', 'بعلبک', 'Baalbek', '۱۰۳۰ ه.ق', 'اصفهان', 'Isfahan', 'دانشمند، معمار، شاعر', 'Scientist, Architect, Poet', 'صفوی', 'Safavid', 'شیخ بهایی در سال ۹۵۳ هجری قمری در بعلبک متولد شد.', 'Sheikh Bahai was born in Baalbek in 953 AH.', 'شیخ بهایی در بعلبک به دنیا آمد و در جوانی به ایران آمد.', 'Sheikh Bahai was born in Baalbek and came to Iran in his youth.', 'خوشا آنان که درویشاند و خوش‌دل', 'Blessed are those who are humble and joyful', 'علم,دانشمند,معمار,شاعر,فقیه,ریاضیدان', 'Science,Scientist,Architect,Poet,Jurist,Mathematician', 'prof21.png', 0, 1, 10, 0, '2026-08-24 15:47:18', '2026-08-25 09:08:20'),
(11, 'خواجه نصیرالدین طوسی', '', 'خواجه نصیرالدین طوسی', 'Nasir al-Din Tusi', 'nasir-al-din-tusi', '۵۹۷ ه.ق', 'طوس', 'Tus', '۶۷۲ ه.ق', 'بغداد', 'Baghdad', 'فیلسوف، ریاضیدان، ستاره‌شناس', 'Philosopher, Mathematician, Astronomer', 'اسلامی', 'Islamic', 'خواجه نصیرالدین طوسی در سال ۵۹۷ هجری قمری در طوس متولد شد.', 'Nasir al-Din Tusi was born in Tus in 597 AH.', 'نصیرالدین طوسی در طوس به دنیا آمد و از کودکی به علوم مختلف علاقه داشت.', 'Nasir al-Din Tusi was born in Tus and had a passion for various sciences from childhood.', 'العلم سلطان و العلم نور', 'Knowledge is power and knowledge is light', 'علم,فیلسوف,ریاضیدان,ستاره‌شناس', 'Science,Philosopher,Mathematician,Astronomer', 'prof4.png', 0, 1, 11, 0, '2026-08-24 15:47:18', '2026-08-25 09:09:43'),
(12, 'عنصرالمعالی کیکاووس', 'Unsur al-Ma\'ali', 'عنصرالمعالی کیکاووس بن اسکندر بن قابوس', 'Unsur al-Ma\'ali Kikavus ibn Iskandar ibn Qabus', 'unsur-al-mali', 'حدود ۴۱۰ ه.ق', 'گرگان', 'Gorgan', 'حدود ۴۸۰ ه.ق', 'گرگان', 'Gorgan', 'حکیم، نویسنده', 'Philosopher, Writer', 'اسلامی', 'Islamic', 'عنصرالمعالی کیکاووس بن اسکندر در قرن پنجم هجری در گرگان متولد شد.', 'Unsur al-Ma\'ali was born in Gorgan in the 5th century AH.', 'کیکاووس از خاندان زیاریان بود که در گرگان و طبرستان حکومت داشتند.', 'Kikavus was from the Ziyarid dynasty that ruled in Gorgan and Tabaristan.', 'ای پسر، دانش را هیچ غایتی نیست و آن را نهایتی نیست', 'O son, knowledge has no end and no limit', 'عرفان,حکیم,نویسنده,ادیب', 'Mysticism,Philosopher,Writer,Literary', 'prof22.png', 0, 1, 12, 0, '2026-08-24 15:47:18', '2026-08-25 09:11:27'),
(13, 'حافظ شیرازی', 'Hafez Shirazi', 'خواجه شمس‌الدین محمد حافظ شیرازی', 'Khwajeh Shams al-Din Muhammad Hafez Shirazi', 'hafez-shirazi', '۷۲۶ ه.ق', 'شیراز', 'Shiraz', '۷۹۲ ه.ق', 'شیراز', 'Shiraz', 'شاعر', 'Poet', 'اسلامی', 'Islamic', 'خواجه شمس‌الدین محمد حافظ شیرازی در سال ۷۲۶ هجری قمری در شیراز متولد شد.', 'Khwajeh Shams al-Din Muhammad Hafez was born in Shiraz in 726 AH.', 'حافظ در دوران حکومت آل مظفر و تیموریان زندگی می‌کرد.', 'Hafez lived during the Muzaffarid and Timurid dynasties.', 'بیا تا گل بر افشانیم و می در ساغر اندازیم', 'Let us scatter the dust of sorrow and fill our cups', 'ادبیات,شاعر,عرفان,حکمت', 'Literature,Poet,Mysticism,Wisdom', 'prof5.png', 1, 1, 13, 0, '2026-08-24 15:47:18', '2026-08-25 09:13:33'),
(14, 'زکریای رازی', 'Al-Razi', 'زکریای رازی (محمد بن زکریای رازی)', 'Zakariya al-Razi (Muhammad ibn Zakariya al-Razi)', 'zakariya-al-razi', '۲۵۱ ه.ق', 'ری', 'Ray', '۳۱۳ ه.ق', 'ری', 'Ray', 'پزشک، شیمیدان، فیلسوف', 'Physician, Chemist, Philosopher', 'اسلامی', 'Islamic', 'زکریای رازی در سال ۲۵۱ هجری قمری در شهر ری به دنیا آمد.', 'Zakariya al-Razi was born in the city of Ray in 251 AH.', 'رازی در ری متولد شد و در جوانی به بغداد رفت.', 'Al-Razi was born in Ray and traveled to Baghdad in his youth.', 'الطب حفظ الصحة و استعادة الصحة', 'Medicine is the preservation of health and restoration of health', 'علم,پزشک,شیمیدان', 'Science,Physician,Chemist', 'prof23.png', 0, 1, 14, 0, '2026-08-24 15:47:18', '2026-08-25 09:14:58'),
(15, 'کوروش بزرگ', 'Cyrus the Great', 'کوروش بزرگ (کوروش دوم هخامنشی)', 'Cyrus the Great (Cyrus II Achaemenid)', 'cyrus-the-great', 'حدود ۶۰۰ ق.م', 'پاسارگاد', 'Pasargadae', '۵۳۰ ق.م', 'پاسارگاد', 'Pasargadae', 'پادشاه، بنیان‌گذار', 'King, Founder', 'پیش از اسلام', 'Pre-Islamic', 'کوروش بزرگ در حدود سال ۶۰۰ پیش از میلاد در پاسارگاد متولد شد.', 'Cyrus the Great was born in Pasargadae around 600 BC.', 'کوروش در خانواده‌ای از پادشاهان پارس به دنیا آمد.', 'Cyrus was born into a family of Persian kings.', 'من کوروش هستم، پادشاه جهان', 'I am Cyrus, king of the world', 'سیاست,پادشاه پادشاهان,فاتح,بنیان‌گذار', 'Politics,King of kings,Conqueror,Founder', 'prof10.png', 1, 1, 1, 0, '2026-08-24 15:47:18', '2026-08-25 13:45:28'),
(16, 'خواجه نظام‌الملک طوسی', 'Nizam al-Mulk', 'خواجه نظام‌الملک طوسی', 'Khwajeh Nizam al-Mulk Tusi', 'nizam-al-mulk', '۴۰۸ ه.ق', 'طوس', 'Tus', '۴۸۵ ه.ق', 'نهاوند', 'Nahavand', 'سیاستمدار، وزیر', 'Politician, Minister', 'سلجوقی', 'Seljuk', 'خواجه نظام‌الملک طوسی در سال ۴۰۸ هجری قمری در طوس متولد شد.', 'Khwajeh Nizam al-Mulk was born in Tus in 408 AH.', 'نظام‌الملک در خانواده‌ای از اهل علم در طوس متولد شد.', 'Nizam al-Mulk was born into a scholarly family in Tus.', 'عدالت اساس پادشاهی است و بی‌عدالتی باعث نابودی حکومت می‌شود', 'Justice is the foundation of kingship, and injustice leads to the destruction of government', 'سیاست,سیاستمدار,وزیر,نویسنده,مصلح', 'Politics,Politician,Minister,Writer,Reformer', 'prof19.png', 0, 1, 16, 0, '2026-08-24 15:47:18', '2026-08-25 13:38:32'),
(17, 'ابوحامد محمد غزالی', 'Al-Ghazali', 'ابوحامد محمد غزالی', 'Abu Hamid Muhammad al-Ghazali', 'abu-hamid-ghazali', '۴۵۰ ه.ق', 'طوس', 'Tus', '۵۰۵ ه.ق', 'طابران توس', 'Tus', 'فیلسوف، متکلم، عارف', 'Philosopher, Theologian, Mystic', 'اسلامی', 'Islamic', 'ابوحامد محمد غزالی در سال ۴۵۰ هجری قمری در طوس متولد شد.', 'Abu Hamid Muhammad al-Ghazali was born in Tus in 450 AH.', 'غزالی در طوس به دنیا آمد و در جوانی به نیشابور رفت.', 'Al-Ghazali was born in Tus and went to Neyshabur in his youth.', 'العلم بلا عمل کالشجر بلا ثمر', 'Knowledge without action is like a tree without fruit', 'علم,فیلسوف,متکلم,عارف,فقیه', 'Science,Philosopher,Theologian,Mystic,Jurist', 'prof24.png', 0, 1, 17, 0, '2026-08-24 15:47:18', '2026-08-25 13:41:13'),
(18, 'شیخ محمود شبستری', 'Mahmud Shabestari', 'شیخ محمود شبستری', 'Sheikh Mahmud Shabestari', 'mahmud-shabestari', '۶۸۷ ه.ق', 'شبستر', 'Shabestar', '۷۲۰ ه.ق', 'تبریز', 'Tabriz', 'عارف، شاعر', 'Mystic, Poet', 'اسلامی', 'Islamic', 'شیخ محمود شبستری در سال ۶۸۷ هجری قمری در شبستر متولد شد.', 'Sheikh Mahmud Shabestari was born in Shabestar in 687 AH.', 'شبستری در شبستر به دنیا آمد و در جوانی به تبریز رفت.', 'Shabestari was born in Shabestar and went to Tabriz in his youth.', 'گلستان بهشت و نار خلد ز عشق آمد که تا جاوید ماند', 'The garden of paradise and the fire of hell came from love, so that it may last forever', 'عرفان,عارف,شاعر,فیلسوف', 'Mysticism,Mystic,Poet,Philosopher', 'prof25.png', 0, 1, 18, 0, '2026-08-24 15:47:18', '2026-08-25 13:42:27');

-- --------------------------------------------------------

--
-- Table structure for table `mashahir_products`
--

CREATE TABLE `mashahir_products` (
  `id` int(11) NOT NULL,
  `celebrity_id` int(11) DEFAULT NULL,
  `title_fa` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `full_title_fa` varchar(500) DEFAULT NULL,
  `full_title_en` varchar(500) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `short_desc_fa` text DEFAULT NULL,
  `short_desc_en` text DEFAULT NULL,
  `description_fa` longtext DEFAULT NULL,
  `description_en` longtext DEFAULT NULL,
  `quote_fa` text DEFAULT NULL,
  `quote_en` text DEFAULT NULL,
  `author_fa` varchar(255) DEFAULT NULL,
  `author_en` varchar(255) DEFAULT NULL,
  `translator_fa` varchar(255) DEFAULT NULL,
  `translator_en` varchar(255) DEFAULT NULL,
  `publisher_fa` varchar(255) DEFAULT NULL,
  `publisher_en` varchar(255) DEFAULT NULL,
  `publish_year` varchar(50) DEFAULT NULL,
  `pages` smallint(5) UNSIGNED DEFAULT NULL,
  `language_fa` varchar(100) DEFAULT NULL,
  `language_en` varchar(100) DEFAULT NULL,
  `genre_fa` varchar(255) DEFAULT NULL,
  `genre_en` varchar(255) DEFAULT NULL,
  `era_fa` varchar(100) DEFAULT NULL,
  `era_en` varchar(100) DEFAULT NULL,
  `tags_fa` text DEFAULT NULL,
  `tags_en` text DEFAULT NULL,
  `price_print` decimal(15,2) DEFAULT NULL,
  `sale_price_print` decimal(15,2) DEFAULT NULL,
  `price_pdf` decimal(15,2) DEFAULT NULL,
  `sale_price_pdf` decimal(15,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_by` varchar(20) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `views_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mashahir_products`
--

INSERT INTO `mashahir_products` (`id`, `celebrity_id`, `title_fa`, `title_en`, `full_title_fa`, `full_title_en`, `slug`, `image`, `short_desc_fa`, `short_desc_en`, `description_fa`, `description_en`, `quote_fa`, `quote_en`, `author_fa`, `author_en`, `translator_fa`, `translator_en`, `publisher_fa`, `publisher_en`, `publish_year`, `pages`, `language_fa`, `language_en`, `genre_fa`, `genre_en`, `era_fa`, `era_en`, `tags_fa`, `tags_en`, `price_print`, `sale_price_print`, `price_pdf`, `sale_price_pdf`, `stock`, `created_by`, `is_featured`, `is_active`, `sort_order`, `views_count`, `created_at`, `updated_at`) VALUES
(1, 13, 'دیوان حافظ', 'Divan of Hafez', 'دیوان خواجه شمس‌الدین محمد حافظ شیرازی', 'The Complete Divan of Khwajeh Shams al-Din Muhammad Hafez Shirazi', 'divan-hafez', 'divan.png', 'مجموعه کامل غزلیات حافظ شیرازی، یکی از بزرگ‌ترین شاعران زبان فارسی', 'The complete collection of ghazals by Hafez Shirazi, one of the greatest Persian-language poets', 'دیوان حافظ مجموعه‌ای از غزل‌ها، قصاید، رباعیات و قطعات حافظ شیرازی است. حافظ که در قرن هشتم هجری می‌زیست، یکی از تأثیرگذارترین شاعران ادبیات فارسی به شمار می‌رود. این دیوان که بر اساس نسخه‌های خطی معتبر تصحیح شده، شامل بیش از پانصد غزل، چند قصیده، مثنوی، رباعی و قطعه است.', 'The Divan of Hafez is a collection of ghazals, odes, quatrains and fragments by Hafez Shirazi. Hafez, who lived in the eighth century AH, is considered one of the most influential poets of Persian literature. This divan, corrected based on authoritative manuscripts, contains more than five hundred ghazals, several odes, masnavis, quatrains and fragments.', 'الا یا ایها الساقی ادر کأساً و ناولها\nکه عشق آسان نمود اول ولی افتاد مشکل‌ها', 'O Saqi, pass around the cup and offer it\nLove seemed easy at first, but then came the difficulties', 'حافظ شیرازی', 'Hafez Shirazi', NULL, NULL, 'نشر ققنوس', 'Qoqnus Publications', '۱۳۹۸', 420, 'فارسی', 'Persian', 'غزل', 'Ghazal', 'اسلامی', 'Islamic', 'ادبیات,غزل,شعر,عرفان,حکمت', 'Literature,Ghazal,Poetry,Mysticism,Wisdom', 95000.00, 75000.00, 45000.00, 35000.00, 101, NULL, 1, 1, 1, 38, '2026-09-04 16:49:16', '2026-09-25 19:06:32'),
(2, 2, 'گلستان سعدی', 'Gulistan of Saadi', 'گلستان سعدی', 'Gulistan of Saadi', 'gulistan-saadi', 'golestan.png', 'گلستان سعدی یکی از برجسته‌ترین آثار نثر فارسی است که مجموعه‌ای از حکایت‌ها، داستان‌ها، پندها و اشعار سعدی شیرازی را دربرمی‌گیرد.', 'Gulistan of Saadi is one of the greatest works of Persian prose, containing stories, anecdotes, wisdom and poetry by Saadi Shirazi.', 'گلستان سعدی از مشهورترین آثار ادبیات فارسی است که سعدی آن را در قرن هفتم هجری به نثر آمیخته با شعر نگاشته است. این اثر شامل هشت باب دربارهٔ سیرت پادشاهان، اخلاق درویشان، قناعت، خاموشی، عشق و جوانی، ضعف و پیری، تأثیر تربیت و آداب صحبت است. گلستان با حکایت‌های کوتاه و آموزنده و نثر آهنگین خود، یکی از مهم‌ترین آثار کلاسیک زبان فارسی به شمار می‌رود.', 'Gulistan of Saadi is one of the most famous works of Persian literature, written by Saadi Shirazi in the seventh century AH in a style combining prose and poetry. The work consists of eight chapters covering the conduct of kings, the ethics of dervishes, contentment, silence, love and youth, weakness and old age, the influence of education, and the manners of companionship. Through its short instructive stories and rhythmic prose, Gulistan remains one of the most important classics of Persian literature.', 'بنی آدم اعضای یکدیگرند\nکه در آفرینش ز یک گوهرند', 'Human beings are members of one another\nSince they were created from the same essence', 'سعدی شیرازی', 'Saadi Shirazi', NULL, NULL, 'یاقوت کویر', 'Yaqout-e Kavir', '۱۳۹۵', 248, 'فارسی', 'Persian', 'نثر ادبی', 'Literary Prose', 'کلاسیک', 'Classical', 'ادبیات,حکایت,شعر,حکمت,اخلاق', 'Literature,Stories,Poetry,Wisdom,Morality', 95000.00, 75000.00, 45000.00, 35000.00, 0, NULL, 1, 1, 2, 5, '2026-09-04 17:13:16', '2026-09-04 19:19:36'),
(3, 3, 'مثنوی معنوی', 'Masnavi', 'مثنوی معنوی مولانا جلال‌الدین محمد بلخی', 'The Masnavi of Jalal al-Din Muhammad Rumi', 'masnavi-manavi', 'p3.png', 'مثنوی معنوی یکی از بزرگ‌ترین آثار عرفانی و ادبیات فارسی و مجموعه‌ای از حکایت‌ها، آموزه‌های عرفانی و اشعار مولانا است.', 'The Masnavi is one of the greatest works of Persian mystical literature, containing stories, spiritual teachings and poetry by Rumi.', 'مثنوی معنوی اثر برجستهٔ مولانا جلال‌الدین محمد بلخی، یکی از مهم‌ترین آثار عرفانی و ادبیات کلاسیک فارسی است. این اثر در شش دفتر سروده شده و شامل هزاران بیت شعر در زمینه‌های عرفان، اخلاق، عشق، حکمت و معارف انسانی و الهی است. مولانا در مثنوی با استفاده از داستان‌ها و حکایت‌های گوناگون، مفاهیم عمیق عرفانی و اخلاقی را بیان می‌کند.', 'The Masnavi is the masterpiece of Jalal al-Din Muhammad Rumi and one of the most important works of Persian mystical and classical literature. Composed in six books, it contains thousands of verses dealing with mysticism, morality, love, wisdom, and spiritual knowledge. Through numerous stories and parables, Rumi conveys profound mystical and ethical teachings.', 'بشنو از نی چون حکایت می‌کند - وز جدایی‌ها شکایت می‌کند', 'Listen to the reed as it tells its tale - And complains of separations', 'مولانا جلال‌الدین محمد بلخی', 'Jalal al-Din Muhammad Rumi', NULL, NULL, 'انتشارات ققنوس', 'Qoqnus Publications', '۱۳۹۸', 800, 'فارسی', 'Persian', 'مثنوی', 'Masnavi', 'عرفانی', 'Mystical', 'ادبیات,عرفان,شعر,حکمت,مولانا', 'Literature,Mysticism,Poetry,Wisdom,Rumi', 120000.00, 95000.00, 50000.00, 40000.00, 0, NULL, 1, 1, 3, 2, '2026-09-04 17:22:54', '2026-09-04 17:33:21'),
(4, 4, 'شاهنامه فردوسی', 'Shahnameh', 'شاهنامه حکیم ابوالقاسم فردوسی', 'The Shahnameh of Ferdowsi', 'shahnameh-ferdowsi', 'p4.png', 'شاهنامه فردوسی حماسه ملی ایران و یکی از بزرگ‌ترین آثار ادبیات فارسی است که تاریخ اسطوره‌ای و پهلوانی ایران را روایت می‌کند.', 'The Shahnameh is the national epic of Iran and one of the greatest works of Persian literature, recounting the mythical and heroic history of Iran.', 'شاهنامه اثر جاودانه حکیم ابوالقاسم فردوسی، بزرگ‌ترین حماسه منظوم فارسی و یکی از مهم‌ترین آثار ادبی جهان است. این اثر در حدود پنجاه هزار بیت دارد و داستان پادشاهان، پهلوانان و رویدادهای اسطوره‌ای و تاریخی ایران را از آغاز آفرینش تا حمله اعراب روایت می‌کند. شاهنامه نقش بسیار مهمی در حفظ زبان و فرهنگ فارسی داشته است.', 'The Shahnameh is the immortal masterpiece of Hakim Abol-Qasem Ferdowsi, the greatest Persian epic poem and one of the most important literary works in the world. Consisting of approximately fifty thousand couplets, it recounts the stories of kings, heroes, and mythical and historical events of Iran from the creation of the world to the Arab conquest. The Shahnameh played a crucial role in preserving Persian language and culture.', 'به نام خداوند جان و خرد - کزین برتر اندیشه برنگذرد', 'In the name of God, the Lord of life and wisdom - Beyond Him, no thought can go', 'حکیم ابوالقاسم فردوسی', 'Hakim Abol-Qasem Ferdowsi', NULL, NULL, 'انتشارات ققنوس', 'Qoqnus Publications', '۱۳۹۸', 900, 'فارسی', 'Persian', 'حماسه', 'Epic', 'کلاسیک', 'Classical', 'ادبیات,حماسه,اسطوره,ایران,پهلوانان', 'Literature,Epic,Mythology,Iran,Heroes', 150000.00, 120000.00, 60000.00, 50000.00, 0, NULL, 1, 1, 4, 2, '2026-09-04 17:23:54', '2026-09-04 19:19:58'),
(5, 5, 'رباعیات خیام', 'Rubaiyat of Omar Khayyam', 'رباعیات حکیم عمر خیام نیشابوری', 'The Rubaiyat of Omar Khayyam', 'rubaiyat-omar-khayyam', 'p5.png', 'رباعیات خیام مجموعه‌ای از رباعی‌های منسوب به عمر خیام است که درونمایه‌هایی چون زندگی، مرگ، عشق، گذر زمان و فلسفه را بیان می‌کند.', 'The Rubaiyat of Omar Khayyam is a collection of quatrains attributed to Omar Khayyam, exploring themes such as life, death, love, the passage of time, and philosophy.', 'رباعیات خیام از مشهورترین آثار شعر فارسی است که در آن اندیشه‌های فلسفی و تأملات خیام درباره زندگی، مرگ، سرنوشت، گذر زمان و لذت بردن از لحظه‌های زندگی بیان شده است. رباعی‌های خیام به دلیل زبان ساده، اندیشه عمیق و نگاه متفاوت به جهان شهرتی جهانی یافته‌اند.', 'The Rubaiyat of Omar Khayyam is one of the most famous works of Persian poetry. His quatrains express philosophical reflections on life, death, destiny, the passage of time, and the importance of enjoying the present moment. Khayyam\'s quatrains have gained worldwide recognition for their simple language, profound thought, and distinctive view of the world.', 'ابر آمد و باز بر سر سبزه گریست - بی باده گلرنگ نمی‌باید زیست', 'The cloud came and wept upon the green - Without rosy wine, life should not be lived', 'حکیم عمر خیام نیشابوری', 'Omar Khayyam', NULL, NULL, 'انتشارات ققنوس', 'Qoqnus Publications', '۱۳۹۸', 180, 'فارسی', 'Persian', 'رباعی', 'Quatrain', 'کلاسیک', 'Classical', 'ادبیات,رباعی,فلسفه,حکمت,عرفان', 'Literature,Quatrain,Philosophy,Wisdom,Mysticism', 85000.00, 65000.00, 40000.00, 30000.00, 0, NULL, 0, 1, 5, 1, '2026-09-04 17:24:03', '2026-09-04 17:34:57'),
(6, 7, 'قانون در طب', 'The Canon of Medicine', 'القانون فی الطب اثر ابن سینا', 'The Canon of Medicine by Avicenna', 'qanun-fi-al-tibb', 'p6.png', 'قانون در طب یکی از مهم‌ترین آثار پزشکی ابن سینا و از تأثیرگذارترین کتاب‌های تاریخ پزشکی است.', 'The Canon of Medicine is one of Avicenna\'s most important medical works and one of the most influential books in the history of medicine.', 'قانون در طب اثر مشهور ابوعلی سینا، دانشمند و پزشک بزرگ ایرانی، یکی از مهم‌ترین آثار پزشکی در تاریخ جهان است. این کتاب دانش پزشکی زمان ابن سینا را به صورت نظام‌مند گردآوری کرده و موضوعاتی مانند مبانی پزشکی، تشخیص بیماری‌ها، داروشناسی، بیماری‌های مختلف و روش‌های درمان را بررسی می‌کند. قانون در طب قرن‌ها در سرزمین‌های اسلامی و اروپا به عنوان یکی از منابع اصلی آموزش پزشکی مورد استفاده قرار گرفت.', 'The Canon of Medicine is the famous medical work of Abu Ali Sina, also known as Avicenna, the renowned Persian scholar and physician. It is one of the most important medical works in world history. The book systematically compiled the medical knowledge of Avicenna\'s time and discusses the foundations of medicine, diagnosis, pharmacology, diseases, and methods of treatment. For centuries, the Canon of Medicine was used as a major source of medical education in both the Islamic world and Europe.', 'پزشکی دانشی است که به شناخت بدن و بیماری‌ها و راه‌های حفظ تندرستی می‌پردازد.', 'Medicine is the science concerned with understanding the body and diseases and preserving health.', 'ابوعلی سینا', 'Avicenna', NULL, NULL, 'انتشارات ققنوس', 'Qoqnus Publications', '۱۳۹۸', 850, 'فارسی', 'Persian', 'پزشکی', 'Medicine', 'کلاسیک', 'Classical', 'پزشکی,علم,داروشناسی,درمان,ابن سینا', 'Medicine,Science,Pharmacology,Treatment,Avicenna', 180000.00, 145000.00, 70000.00, 55000.00, 0, NULL, 1, 1, 6, 3, '2026-09-04 17:24:41', '2026-09-13 10:45:57');

-- --------------------------------------------------------

--
-- Table structure for table `mashahir_product_editions`
--

CREATE TABLE `mashahir_product_editions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `edition_type` enum('pdf','print') NOT NULL DEFAULT 'pdf',
  `lang` varchar(10) NOT NULL DEFAULT 'fa',
  `label_fa` varchar(100) NOT NULL,
  `label_en` varchar(100) NOT NULL,
  `file_path` varchar(500) NOT NULL DEFAULT '',
  `file_size_kb` int(11) DEFAULT NULL,
  `pages` smallint(5) UNSIGNED DEFAULT NULL,
  `sort_order` tinyint(4) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mashahir_product_editions`
--

INSERT INTO `mashahir_product_editions` (`id`, `product_id`, `edition_type`, `lang`, `label_fa`, `label_en`, `file_path`, `file_size_kb`, `pages`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 1, 'pdf', 'fa', 'نسخه فارسی', 'Persian Edition', '/Kavian/mashahir/public/pdfs/divan-hafez/divan-hafez-fa.pdf', 1, 486, 0, 1, '2026-09-11 20:21:29'),
(2, 1, 'pdf', 'en', 'ترجمه انگلیسی', 'English Translation', '/Kavian/mashahir/public/pdfs/divan-hafez/divan-hafez-en.pdf', 1, 312, 1, 1, '2026-09-11 20:21:29'),
(3, 2, 'pdf', 'fa', 'نسخه فارسی', 'Persian Edition', '/Kavian/mashahir/public/pdfs/gulistan-saadi/gulistan-saadi-fa.pdf', 1, 248, 0, 1, '2026-09-11 20:21:29'),
(4, 2, 'pdf', 'en', 'ترجمه انگلیسی', 'English Translation', '/Kavian/mashahir/public/pdfs/gulistan-saadi/gulistan-saadi-en.pdf', 1, 198, 1, 1, '2026-09-11 20:21:29'),
(5, 3, 'pdf', 'fa', 'نسخه فارسی', 'Persian Edition', '/Kavian/mashahir/public/pdfs/masnavi-manavi/masnavi-manavi-fa.pdf', 1, 918, 0, 1, '2026-09-11 20:21:29'),
(6, 4, 'pdf', 'fa', 'نسخه فارسی', 'Persian Edition', '/Kavian/mashahir/public/pdfs/shahnameh-ferdowsi/shahnameh-ferdowsi-fa.pdf', 1, 1246, 0, 1, '2026-09-11 20:21:29'),
(7, 4, 'pdf', 'en', 'ترجمه انگلیسی', 'English Translation', '/Kavian/mashahir/public/pdfs/shahnameh-ferdowsi/shahnameh-ferdowsi-en.pdf', 1, 886, 1, 1, '2026-09-11 20:21:29'),
(8, 5, 'pdf', 'fa', 'نسخه فارسی', 'Persian Edition', '/Kavian/mashahir/public/pdfs/rubaiyat-omar-khayyam/rubaiyat-omar-khayyam-fa.pdf', 1, 124, 0, 1, '2026-09-11 20:21:29'),
(9, 5, 'pdf', 'en', 'ترجمه فیتزجرالد', 'FitzGerald Translation', '/Kavian/mashahir/public/pdfs/rubaiyat-omar-khayyam/rubaiyat-omar-khayyam-en.pdf', 1, 98, 1, 1, '2026-09-11 20:21:29'),
(10, 6, 'pdf', 'fa', 'نسخه فارسی', 'Persian Edition', '/Kavian/mashahir/public/pdfs/qanun-fi-al-tibb/qanun-fi-al-tibb-fa.pdf', 1, 642, 0, 1, '2026-09-11 20:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `mashahir_reviews`
--

CREATE TABLE `mashahir_reviews` (
  `id` int(11) NOT NULL,
  `celebrity_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `rating` tinyint(1) DEFAULT 5,
  `content_fa` text DEFAULT NULL,
  `content_en` text DEFAULT NULL,
  `hashtags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `likes_count` int(11) DEFAULT 0,
  `lang` enum('fa','en') NOT NULL DEFAULT 'fa',
  `is_active` tinyint(1) DEFAULT 1,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mashahir_reviews`
--

INSERT INTO `mashahir_reviews` (`id`, `celebrity_id`, `product_id`, `user_id`, `author_name`, `rating`, `content_fa`, `content_en`, `hashtags`, `likes_count`, `lang`, `is_active`, `status`, `created_at`) VALUES
(1, 1, 1, 2, 'علی محمدی', 5, 'این کتاب بهترین تصحیح از دیوان حافظ است که دیده‌ام. تفسیرها و توضیحات بسیار مفید هستند.', 'This is the best edition of Hafez\'s Divan I have seen. The interpretations and explanations are very useful.', '[\"حافظ\", \"شعر\", \"ادبیات_فارسی\"]', 125, 'fa', 1, 'approved', '2026-08-18 16:26:01'),
(2, 2, 2, 3, 'مریم احمدی', 5, 'سعدی استاد نثر فارسی است. این نسخه بسیار دقیق و زیبا چاپ شده است.', 'Saadi is the master of Persian prose. This edition is very accurate and beautifully printed.', '[\"سعدی\", \"گلستان\", \"نثر_فارسی\"]', 98, 'fa', 1, 'approved', '2026-08-18 16:26:01'),
(3, 3, 3, 4, 'پریسا وفایی', 5, 'این نسخه از مثنوی با توضیحات کامل، درک عرفان مولانا را آسان کرده است.', 'This edition of Masnavi with complete explanations has made understanding Rumi\'s mysticism easy.', '[\"مولانا\", \"مثنوی\", \"عرفان\"]', 156, 'fa', 1, 'approved', '2026-08-18 16:26:01'),
(4, 1, 1, 6, 'Emily Carter', 4, NULL, 'The Divan of Hafez is a masterpiece of Persian poetry. The English translation in this edition captures the beauty of the original remarkably well.', '[\"Hafez\", \"poetry\", \"Persian_literature\"]', 87, 'en', 1, 'approved', '2026-08-19 10:15:00'),
(5, 2, 2, 7, 'James Mitchell', 5, NULL, 'Saadi\'s Gulistan is a timeless work of wisdom. This bilingual edition is perfect for anyone wanting to explore Persian classical literature.', '[\"Saadi\", \"Gulistan\", \"wisdom\"]', 112, 'en', 1, 'approved', '2026-08-19 11:30:00'),
(6, 3, 3, 8, 'Sophia Williams', 5, NULL, 'Rumi\'s Masnavi is one of the greatest spiritual works ever written. This edition with English commentary is truly enlightening.', '[\"Rumi\", \"Masnavi\", \"mysticism\"]', 143, 'en', 1, 'approved', '2026-08-20 09:45:00'),
(8, 13, 1, NULL, '123123', 5, NULL, '123', NULL, 0, 'en', 0, 'rejected', '2026-09-25 19:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(20) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `total_amount` decimal(12,0) NOT NULL,
  `discount_amount` decimal(12,0) NOT NULL DEFAULT 0,
  `final_amount` decimal(12,0) NOT NULL,
  `status` enum('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_ref` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `session_id`, `full_name`, `email`, `phone`, `shipping_address`, `total_amount`, `discount_amount`, `final_amount`, `status`, `payment_ref`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'ORD-260912-BBEA39', 3, 'r1ciau3t0abbr4ufpp7pru5j60', 'zxcb', 'f@c.a', '09999999999', NULL, 35000, 0, 35000, 'paid', 'PAY-6F10709DBB66', '', '2026-09-12 11:57:38', '2026-09-12 11:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_type` enum('print','pdf') NOT NULL,
  `pdf_lang` varchar(10) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,0) NOT NULL,
  `total_price` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_title`, `product_type`, `pdf_lang`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 1, 'دیوان حافظ', 'pdf', NULL, 1, 35000, 35000);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `label` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_system` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `description`, `created_at`, `updated_at`, `is_system`) VALUES
(1, 'admin-mashhir', 'ادمین مشاهیر', 'دسترسی کامل به بخش مشاهیر', '2026-09-27 10:54:29', '2026-09-27 10:54:29', 0),
(2, 'provider-mashhir', 'تامین‌کننده مشاهیر', 'فقط مشاهده، ویرایش و ثبت محصولات مشاهیر', '2026-09-27 10:54:29', '2026-09-27 10:54:29', 0),
(3, 'admin', 'مدیر کل', 'دسترسی کامل به همه بخش‌های پنل', '2026-10-02 08:15:14', '2026-10-02 08:15:14', 1),
(4, 'user', 'کاربر عادی', 'فقط پروفایل شخصی — بدون دسترسی به پنل', '2026-10-02 08:15:14', '2026-10-02 08:15:14', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_key` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_key`) VALUES
(11, 1, 'section:celebrities'),
(12, 1, 'section:celebrities:categories'),
(13, 1, 'section:celebrities:celebrities'),
(15, 1, 'section:celebrities:product_register'),
(14, 1, 'section:celebrities:products'),
(16, 1, 'section:celebrities:reviews'),
(17, 1, 'section:celebrities:tickets'),
(9, 2, 'section:celebrities:product_register'),
(8, 2, 'section:celebrities:products'),
(10, 3, 'special:*');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `family` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','editor','user') DEFAULT 'user',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `custom_role_id` int(11) DEFAULT NULL COMMENT 'ارتباط با جدول roles — NULL یعنی از role قدیمی استفاده شود'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `family`, `email`, `password`, `phone`, `avatar`, `role`, `status`, `email_verified`, `email_verified_at`, `last_login`, `created_at`, `updated_at`, `custom_role_id`) VALUES
(1, 'ادمین', NULL, 'admin@kavian.ir', '$2y$10$fndyNchbw1KlDvLLFC7LgOyBMqnk08x5KDuO6L4YRN200fwjOJ.3i', NULL, '/Kavian/public/uploads/avatars/avatar_1_1788290185.png', 'admin', 'active', 1, NULL, '2026-10-02 11:18:20', '2026-08-18 16:26:01', '2026-10-02 11:18:20', 3),
(2, 'علی', 'محمدی', 'ali@gmail.com', '$2y$10$fndyNchbw1KlDvLLFC7LgOyBMqnk08x5KDuO6L4YRN200fwjOJ.3i', '09123456789', NULL, 'user', 'active', 1, NULL, NULL, '2026-08-18 16:26:01', '2026-10-02 08:15:14', 4),
(3, 'مریم', 'احمدی', 'maryam@gmail.com', '$2y$10$fndyNchbw1KlDvLLFC7LgOyBMqnk08x5KDuO6L4YRN200fwjOJ.3i', '09187654321', NULL, 'user', 'active', 0, NULL, NULL, '2026-08-18 16:26:01', '2026-10-02 08:15:14', 4),
(4, 'پریسا', 'وفایی', 'parisa123@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '09351234567', NULL, 'editor', 'active', 1, NULL, NULL, '2026-08-18 16:26:01', '2026-08-18 16:26:01', NULL),
(5, 'admin', NULL, 'admin2@kavian.ir', '$2y$10$fndyNchbw1KlDvLLFC7LgOyBMqnk08x5KDuO6L4YRN200fwjOJ.3i', NULL, NULL, 'user', 'active', 0, NULL, NULL, '2026-08-26 08:00:38', '2026-10-02 08:15:14', 4),
(6, 'Emily', 'Carter', 'emily.carter@gmail.com', '$2y$10$fndyNchbw1KlDvLLFC7LgOyBMqnk08x5KDuO6L4YRN200fwjOJ.3i', NULL, NULL, 'user', 'active', 1, NULL, NULL, '2026-08-19 10:00:00', '2026-10-02 08:19:20', 4),
(7, 'James', 'Mitchell', 'james.mitchell@gmail.com', '$2y$10$fndyNchbw1KlDvLLFC7LgOyBMqnk08x5KDuO6L4YRN200fwjOJ.3i', NULL, NULL, 'user', 'active', 1, NULL, '2026-10-02 11:19:01', '2026-08-19 11:00:00', '2026-10-02 11:19:01', 2),
(8, 'Sophia', 'Williams', 'sophia.williams@gmail.com', '$2y$10$fndyNchbw1KlDvLLFC7LgOyBMqnk08x5KDuO6L4YRN200fwjOJ.3i', NULL, NULL, 'user', 'active', 1, NULL, '2026-10-02 11:19:26', '2026-08-20 09:00:00', '2026-10-02 11:19:26', 1),
(9, 'Parisa', NULL, 'parisa@gmail.com', '$2y$10$RCPdewLQ4LV78lrMwtCsiOvPUOGVpOHnFWc0iWhH.Qwayu81Kg2Rq', NULL, NULL, 'user', 'active', 0, NULL, NULL, '2026-09-15 09:19:06', '2026-10-02 08:15:14', 4);

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `celebrity_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_favorites`
--

INSERT INTO `user_favorites` (`id`, `user_id`, `celebrity_id`, `created_at`) VALUES
(2, 1, 9, '2026-09-26 11:25:45'),
(3, 1, 15, '2026-09-26 11:25:48');

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

CREATE TABLE `user_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `celebrity_id` int(11) NOT NULL,
  `visited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_history`
--

INSERT INTO `user_history` (`id`, `user_id`, `celebrity_id`, `visited_at`) VALUES
(1, 1, 15, '2026-09-26 11:22:01'),
(2, 1, 3, '2026-09-26 11:20:21'),
(4, 1, 9, '2026-09-26 11:31:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indexes for table `celebrity_categories`
--
ALTER TABLE `celebrity_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `celebrity_category` (`celebrity_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `hero_carousel`
--
ALTER TABLE `hero_carousel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `celebrity_id` (`celebrity_id`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `home_hero_carousel`
--
ALTER TABLE `home_hero_carousel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `mashahir_categories`
--
ALTER TABLE `mashahir_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mashahir_celebrities`
--
ALTER TABLE `mashahir_celebrities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `mashahir_products`
--
ALTER TABLE `mashahir_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `celebrity_id` (`celebrity_id`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `mashahir_product_editions`
--
ALTER TABLE `mashahir_product_editions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_product_type_lang` (`product_id`,`edition_type`,`lang`);

--
-- Indexes for table `mashahir_reviews`
--
ALTER TABLE `mashahir_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `celebrity_id` (`celebrity_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `rating` (`rating`),
  ADD KEY `lang` (`lang`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order` (`order_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_role_name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_role_perm` (`role_id`,`permission_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_user_custom_role` (`custom_role_id`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_celebrity` (`user_id`,`celebrity_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `fk_uf_celebrity` (`celebrity_id`);

--
-- Indexes for table `user_history`
--
ALTER TABLE `user_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_celebrity_hist` (`user_id`,`celebrity_id`),
  ADD KEY `idx_user_visited` (`user_id`,`visited_at`),
  ADD KEY `fk_uh_celebrity` (`celebrity_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `celebrity_categories`
--
ALTER TABLE `celebrity_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hero_carousel`
--
ALTER TABLE `hero_carousel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `home_hero_carousel`
--
ALTER TABLE `home_hero_carousel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mashahir_categories`
--
ALTER TABLE `mashahir_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mashahir_celebrities`
--
ALTER TABLE `mashahir_celebrities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `mashahir_products`
--
ALTER TABLE `mashahir_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `mashahir_product_editions`
--
ALTER TABLE `mashahir_product_editions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `mashahir_reviews`
--
ALTER TABLE `mashahir_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_history`
--
ALTER TABLE `user_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_custom_role` FOREIGN KEY (`custom_role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `fk_uf_celebrity` FOREIGN KEY (`celebrity_id`) REFERENCES `mashahir_celebrities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uf_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_history`
--
ALTER TABLE `user_history`
  ADD CONSTRAINT `fk_uh_celebrity` FOREIGN KEY (`celebrity_id`) REFERENCES `mashahir_celebrities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_uh_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
