-- ═══════════════════════════════════════════════════════════
--  Kavian — Role Management Migration
--  اجرا: یک‌بار در phpMyAdmin یا MySQL CLI
-- ═══════════════════════════════════════════════════════════

-- ── ۱. جدول نقش‌های سفارشی ──────────────────────────────
CREATE TABLE IF NOT EXISTS `roles` (
  `id`          int(11)      NOT NULL AUTO_INCREMENT,
  `name`        varchar(80)  NOT NULL COMMENT 'نام یکتای نقش (slug، لاتین)',
  `label`       varchar(120) NOT NULL COMMENT 'نام نمایشی فارسی',
  `description` text         DEFAULT NULL,
  `created_at`  timestamp    NOT NULL DEFAULT current_timestamp(),
  `updated_at`  timestamp    NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── ۲. جدول مجوزهای هر نقش ──────────────────────────────
--  هر ردیف یک permission key دارد که نشان می‌دهد چه بخشی
--  از پنل ادمین برای این نقش قابل دسترسی است.
--
--  فرمت permission_key:
--    special:users               → بخش کاربران سایت
--    special:logs                → بخش لاگ‌ها
--    special:subsites            → بخش سایت‌های زیرمجموعه
--    special:roles               → مدیریت نقش‌ها
--    special:orders_global       → مدیریت سفارش‌ها
--    section:celebrities         → تمام زیربخش‌های مشاهیر
--    section:celebrities:categories     → فقط دسته‌بندی‌های مشاهیر
--    section:celebrities:celebrities    → فقط مدیریت مشاهیر
--    section:celebrities:products       → نمایش محصولات مشاهیر
--    section:celebrities:product_register → ثبت محصول
--    section:celebrities:reviews        → نظرات مشاهیر
--    section:sport               → تمام زیربخش‌های ورزش
--    section:sport:products      → فقط محصولات ورزش
--    ... و همینطور برای art، places، exports، technology
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id`             int(11)     NOT NULL AUTO_INCREMENT,
  `role_id`        int(11)     NOT NULL,
  `permission_key` varchar(120) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_role_perm` (`role_id`, `permission_key`),
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── ۳. ستون جدید در جدول users ──────────────────────────
--  اگر کاربر custom_role_id داشت، از آن استفاده می‌شود
--  وگرنه role قدیمی (admin/editor/user) ملاک است.
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `custom_role_id` int(11) DEFAULT NULL
    COMMENT 'ارتباط با جدول roles — NULL یعنی از role قدیمی استفاده شود',
  ADD CONSTRAINT `fk_user_custom_role`
    FOREIGN KEY (`custom_role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

-- ── ۴. داده‌های اولیه — دو نقش نمونه ────────────────────
INSERT IGNORE INTO `roles` (`name`, `label`, `description`) VALUES
  ('admin-mashhir',    'ادمین مشاهیر',    'دسترسی کامل به بخش مشاهیر'),
  ('provider-mashhir', 'تامین‌کننده مشاهیر', 'فقط مشاهده، ویرایش و ثبت محصولات مشاهیر');

-- مجوزهای نقش admin-mashhir (دسترسی به تمام بخش مشاهیر)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_key`)
SELECT r.id, p.perm FROM `roles` r
CROSS JOIN (
  SELECT 'section:celebrities'                    AS perm UNION ALL
  SELECT 'section:celebrities:categories'         UNION ALL
  SELECT 'section:celebrities:celebrities'        UNION ALL
  SELECT 'section:celebrities:products'           UNION ALL
  SELECT 'section:celebrities:product_register'   UNION ALL
  SELECT 'section:celebrities:reviews'
) p WHERE r.name = 'admin-mashhir';

-- مجوزهای نقش provider-mashhir (فقط محصولات)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_key`)
SELECT r.id, p.perm FROM `roles` r
CROSS JOIN (
  SELECT 'section:celebrities:products'           AS perm UNION ALL
  SELECT 'section:celebrities:product_register'
) p WHERE r.name = 'provider-mashhir';
