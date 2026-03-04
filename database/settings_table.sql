-- Sozlamalar jadvali
CREATE TABLE IF NOT EXISTS `sozlamalar` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kalit_soz` varchar(100) NOT NULL,
  `qiymat` text DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sozlamalar_kalit` (`kalit_soz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standart sozlamalar
INSERT INTO `sozlamalar` (`kalit_soz`, `qiymat`) VALUES
('company_name', 'POS Magazin MChJ'),
('company_address', 'Toshkent sh., Chilonzor tumani'),
('company_phone', '+998781234567'),
('company_email', 'info@posmagazin.uz'),
('company_tax_number', '123456789'),
('currency_name', 'so\'m'),
('currency_symbol', 'so\'m'),
('currency_position', 'right'),
('decimal_places', '0'),
('thousand_separator', ' '),
('receipt_header', 'POS MAGAZIN\nSavdo cheki'),
('receipt_footer', 'Savdo uchun rahmat!\nTel: +998781234567'),
('auto_print_receipt', '0'),
('show_customer_on_receipt', '1'),
('default_payment_method', 'NAQD'),
('payment_naqd', '1'),
('payment_karta', '1'),
('payment_aralash', '1'),
('payment_transfer', '0');