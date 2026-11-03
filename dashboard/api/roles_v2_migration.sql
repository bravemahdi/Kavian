-- ═══════════════════════════════════════════════════════════
--  Kavian — Role System V2 Migration
--  همه نقش‌ها از DB، بدون hardcode
--  اجرا: یک‌بار در phpMyAdmin یا MySQL CLI
-- ═══════════════════════════════════════════════════════════

-- ── ۱. اضافه کردن is_system به جدول roles ────────────────
--  is_system=1 یعنی نقش سیستمی قابل حذف نیست
--  (admin و user سیستمی‌اند)
ALTER TABLE `roles`
  ADD COLUMN IF NOT EXISTS `is_system` tinyint(1) NOT NULL DEFAULT 0
    COMMENT '۱ = نقش سیستمی، قابل حذف نیست';

-- ── ۲. اضافه کردن نقش admin به جدول roles ────────────────
INSERT IGNORE INTO `roles` (`name`, `label`, `description`, `is_system`) VALUES
  ('admin', 'مدیر کل',    'دسترسی کامل به همه بخش‌های پنل', 1),
  ('user',  'کاربر عادی', 'فقط پروفایل شخصی — بدون دسترسی به پنل مدیریت', 1);

-- ── ۳. مجوز * برای admin ─────────────────────────────────
--  کلید ویژه special:* = دسترسی کامل
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_key`)
SELECT id, 'special:*' FROM `roles` WHERE `name` = 'admin';

-- ── ۴. برای user هیچ مجوزی نیست (جدول خالی می‌ماند) ────

-- ── ۵. migration کاربران فعلی ────────────────────────────
--  کاربرانی که role=admin دارند → custom_role_id = id نقش admin
UPDATE `users` u
  JOIN `roles` r ON r.name = 'admin'
  SET u.custom_role_id = r.id
  WHERE u.role = 'admin' AND u.custom_role_id IS NULL;

--  کاربرانی که role=support دارند → custom_role_id = id نقش user
--  (چون support حذف می‌شه، به user تبدیل می‌شن — ادمین می‌تونه بعداً نقش بده)
UPDATE `users` u
  JOIN `roles` r ON r.name = 'user'
  SET u.custom_role_id = r.id
  WHERE u.role = 'support' AND u.custom_role_id IS NULL;

--  کاربران user عادی که custom_role_id ندارند → custom_role_id = id نقش user
UPDATE `users` u
  JOIN `roles` r ON r.name = 'user'
  SET u.custom_role_id = r.id
  WHERE u.role = 'user' AND u.custom_role_id IS NULL;

-- ── ۶. تأیید نتیجه ───────────────────────────────────────
SELECT r.name, r.label, r.is_system,
       COUNT(rp.id) AS perm_count,
       COUNT(u.id)  AS user_count
FROM roles r
LEFT JOIN role_permissions rp ON rp.role_id = r.id
LEFT JOIN users u              ON u.custom_role_id = r.id
GROUP BY r.id
ORDER BY r.is_system DESC, r.id;
