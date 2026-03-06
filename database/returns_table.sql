-- Qaytarishlar jadvali
CREATE TABLE IF NOT EXISTS `qaytarishlar` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `savdo_id` bigint(20) UNSIGNED NOT NULL,
  `savdo_tarkibi_id` bigint(20) UNSIGNED NOT NULL,
  `mahsulot_id` bigint(20) UNSIGNED NOT NULL,
  `miqdor` int(11) NOT NULL,
  `summa` decimal(12,2) NOT NULL,
  `sabab` varchar(255) DEFAULT NULL,
  `foydalanuvchi_id` bigint(20) UNSIGNED NOT NULL,
  `qaytarilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_qaytarishlar_savdo` (`savdo_id`),
  KEY `idx_qaytarishlar_mahsulot` (`mahsulot_id`),
  KEY `idx_qaytarishlar_vaqt` (`qaytarilgan_vaqt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;