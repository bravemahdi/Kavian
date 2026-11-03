-- جدول علاقه‌مندی‌های کاربر
CREATE TABLE IF NOT EXISTS `user_favorites` (
  `id`           int(11)     NOT NULL AUTO_INCREMENT,
  `user_id`      int(11)     NOT NULL,
  `celebrity_id` int(11)     NOT NULL,
  `created_at`   timestamp   NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_celebrity` (`user_id`, `celebrity_id`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `fk_uf_user`      FOREIGN KEY (`user_id`)      REFERENCES `users`(`id`)               ON DELETE CASCADE,
  CONSTRAINT `fk_uf_celebrity` FOREIGN KEY (`celebrity_id`) REFERENCES `mashahir_celebrities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- جدول تاریخچه بازدید کاربر
CREATE TABLE IF NOT EXISTS `user_history` (
  `id`           int(11)     NOT NULL AUTO_INCREMENT,
  `user_id`      int(11)     NOT NULL,
  `celebrity_id` int(11)     NOT NULL,
  `visited_at`   timestamp   NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_celebrity_hist` (`user_id`, `celebrity_id`),
  KEY `idx_user_visited` (`user_id`, `visited_at`),
  CONSTRAINT `fk_uh_user`      FOREIGN KEY (`user_id`)      REFERENCES `users`(`id`)               ON DELETE CASCADE,
  CONSTRAINT `fk_uh_celebrity` FOREIGN KEY (`celebrity_id`) REFERENCES `mashahir_celebrities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
