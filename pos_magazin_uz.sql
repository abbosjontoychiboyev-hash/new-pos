-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 24 2026 г., 09:22
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `pos_magazin_uz`
--

-- --------------------------------------------------------

--
-- Структура таблицы `foydalanuvchilar`
--

CREATE TABLE `foydalanuvchilar` (
  `id` int(20) UNSIGNED NOT NULL,
  `rol_id` bigint(20) UNSIGNED NOT NULL,
  `fio` varchar(120) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `telefon` varchar(30) DEFAULT NULL,
  `login` varchar(60) NOT NULL,
  `parol_hash` varchar(255) NOT NULL,
  `faol` tinyint(1) NOT NULL DEFAULT 1,
  `oxirgi_kirish_vaqt` timestamp NULL DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ochirilgan_vaqt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `foydalanuvchilar`
--

INSERT INTO `foydalanuvchilar` (`id`, `rol_id`, `fio`, `email`, `telefon`, `login`, `parol_hash`, `faol`, `oxirgi_kirish_vaqt`, `yaratilgan_vaqt`, `yangilangan_vaqt`, `ochirilgan_vaqt`) VALUES
(2, 2, 'To\'ychiboyev Najmiddin Shukurjon o\'g\'li', 'kassa@example.com', '+998200045578', 'Kassir', '$2y$10$rEwpJt5aWDJ4VR1OlYlNS.loDOXpLG.ootZIvCDkwzmzKKaNM58lW', 1, '2026-03-19 07:24:23', '2026-03-06 11:07:15', '2026-03-19 07:24:23', NULL),
(3, 1, 'Abbosjon To\'ychiboyev', 'admin@example.com', '+998930008827', 'admin', '$2y$10$cwwtGPH3nRv/1Gg3p8R9I.Q.VWGxqRBoIFr1VDZfnYSSQMWfQjX5S', 1, '2026-03-24 04:32:04', '2026-03-10 05:08:31', '2026-03-24 04:32:04', NULL),
(4, 3, 'Omborchi', 'ombor@example.com', '+998200007989', 'Omborchi', '$2y$10$zM2V5n.tsRRy4v4By6iT6ekjdIArb1TzvaQCTW/Z3Dr2Tz1oHXCJ2', 1, '2026-03-13 03:21:34', '2026-03-13 03:19:14', '2026-03-13 03:21:34', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `kassa_harakatlari`
--

CREATE TABLE `kassa_harakatlari` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kassa_smena_id` bigint(20) UNSIGNED NOT NULL,
  `amal` enum('KIRIM','CHIQIM') NOT NULL,
  `summa` decimal(12,2) NOT NULL,
  `sabab` varchar(180) NOT NULL,
  `kiritgan_id` bigint(20) UNSIGNED NOT NULL,
  `yaratilgan_vaqt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `kassa_smenalari`
--

CREATE TABLE `kassa_smenalari` (
  `id` int(20) UNSIGNED NOT NULL,
  `kassir_id` bigint(20) UNSIGNED NOT NULL,
  `ochilgan_vaqt` datetime NOT NULL,
  `yopilgan_vaqt` datetime DEFAULT NULL,
  `ochilish_naqd` decimal(12,2) NOT NULL DEFAULT 0.00,
  `yopilish_naqd` decimal(12,2) DEFAULT NULL,
  `actual_cash` decimal(12,2) DEFAULT NULL,
  `holat` enum('OCHIQ','YOPIQ') NOT NULL DEFAULT 'OCHIQ',
  `izoh` varchar(255) DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `kassa_smenalari`
--

INSERT INTO `kassa_smenalari` (`id`, `kassir_id`, `ochilgan_vaqt`, `yopilgan_vaqt`, `ochilish_naqd`, `yopilish_naqd`, `actual_cash`, `holat`, `izoh`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, 3, '2026-03-13 10:23:11', '2026-03-24 09:40:50', 521000.00, 315000.00, NULL, 'YOPIQ', NULL, '2026-03-13 05:23:11', '2026-03-24 04:40:50'),
(2, 2, '2026-03-13 10:28:21', NULL, 785000.00, NULL, NULL, 'OCHIQ', NULL, '2026-03-13 05:28:21', '2026-03-13 05:28:21'),
(3, 3, '2026-03-24 09:41:02', NULL, 20000.00, NULL, NULL, 'OCHIQ', NULL, '2026-03-24 04:41:02', '2026-03-24 04:41:02');

-- --------------------------------------------------------

--
-- Структура таблицы `kategoriyalar`
--

CREATE TABLE `kategoriyalar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomi` varchar(120) NOT NULL,
  `izoh` varchar(255) DEFAULT NULL,
  `faol` tinyint(1) NOT NULL DEFAULT 1,
  `tartib` int(11) NOT NULL DEFAULT 0,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ochirilgan_vaqt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `kategoriyalar`
--

INSERT INTO `kategoriyalar` (`id`, `nomi`, `izoh`, `faol`, `tartib`, `yaratilgan_vaqt`, `yangilangan_vaqt`, `ochirilgan_vaqt`) VALUES
(1, 'Ichimliklar', 'Gazli va gazsiz ichimliklar', 1, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(2, 'Sut mahsulotlari', 'Sut, qatiq, kefir va boshqalar', 1, 2, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(3, 'Non mahsulotlari', 'Non va pishiriqlar', 1, 3, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(4, 'Shirinliklar', 'Konfet, shokolad, pechenye', 1, 4, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(5, 'Choy va qahva', 'Choy, kofe va ichimlik kukunlari', 1, 5, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(6, 'Go‘sht mahsulotlari', 'Kolbasa, сосиска va boshqalar', 1, 6, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(7, 'Muzlatilgan mahsulotlar', 'Muzqaymoq, yarim tayyor mahsulotlar', 1, 7, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(8, 'Makaron va yorma', 'Makaron, guruch, grechka', 1, 8, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(9, 'Konserva mahsulotlari', 'Banka mahsulotlari', 1, 9, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(10, 'Yog‘ mahsulotlari', 'O‘simlik yog‘i va sariyog‘', 1, 10, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(11, 'Gigiyena vositalari', 'Shampun, sovun, pasta', 1, 11, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(12, 'Tozalash vositalari', 'Kir yuvish va tozalash vositalari', 1, 12, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(13, 'Meva-sabzavot', 'Yangi meva va sabzavotlar', 1, 13, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(14, 'Bolalar uchun', 'Bolalar ovqati va gigiyenasi', 1, 14, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(15, 'Tamaddilar', 'Chips, qarsildoq va yengil tamaddilar', 1, 15, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(16, 'Suvlar', 'Ichimlik suvlari', 1, 16, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(17, 'Sharbatlar', 'Mevali sharbatlar', 1, 17, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(18, 'Gazli ichimliklar', 'Cola, fanta, sprite turkumlari', 1, 18, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(19, 'Energetik ichimliklar', 'Energetik va tonik ichimliklar', 1, 19, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(20, 'Quruq mevalar', 'Mayiz, turshak va boshqalar', 1, 20, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(21, 'Ziravorlar', 'Tuz, qalampir, ziravorlar', 1, 21, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(22, 'Sous va qo‘shimchalar', 'Mayonez, кетчуп va souslar', 1, 22, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(23, 'Tuxum va parranda', 'Tuxum va tovuq mahsulotlari', 1, 23, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(24, 'Fast food mahsulotlari', 'Lavash, hot-dog uchun mahsulotlar', 1, 24, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(25, 'Uy-ro‘zg‘or buyumlari', 'Paket, salfetka, idishlar', 1, 25, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(26, 'Ofis va mayda buyumlar', 'Ruchka, daftar va boshqalar', 1, 26, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(27, 'Chegirmadagi mahsulotlar', 'Aksiya mahsulotlari', 1, 27, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(28, 'Import mahsulotlar', 'Tashqaridan keltirilgan mahsulotlar', 1, 28, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(29, 'Mahalliy mahsulotlar', 'Mahalliy ishlab chiqarilgan mahsulotlar', 1, 29, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(30, 'Boshqa mahsulotlar', 'Turli xil mahsulotlar', 1, 30, '2026-03-13 05:21:32', '2026-03-13 07:31:30', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `kirimlar`
--

CREATE TABLE `kirimlar` (
  `id` int(20) UNSIGNED NOT NULL,
  `hisob_faktura` varchar(60) DEFAULT NULL,
  `yetkazib_beruvchi_id` bigint(20) UNSIGNED NOT NULL,
  `kiritgan_id` bigint(20) UNSIGNED NOT NULL,
  `umumiy_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `holat` enum('QORALAMA','QABUL_QILINDI','BEKOR') NOT NULL DEFAULT 'QABUL_QILINDI',
  `kirim_vaqt` datetime NOT NULL DEFAULT current_timestamp(),
  `izoh` varchar(255) DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `kirimlar`
--

INSERT INTO `kirimlar` (`id`, `hisob_faktura`, `yetkazib_beruvchi_id`, `kiritgan_id`, `umumiy_summa`, `holat`, `kirim_vaqt`, `izoh`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, NULL, 6, 3, 52500.00, 'QABUL_QILINDI', '2026-03-24 13:00:41', 'Sotuv', '2026-03-24 08:00:41', '2026-03-24 08:00:41');

-- --------------------------------------------------------

--
-- Структура таблицы `kirim_tarkibi`
--

CREATE TABLE `kirim_tarkibi` (
  `id` int(20) UNSIGNED NOT NULL,
  `kirim_id` bigint(20) UNSIGNED NOT NULL,
  `mahsulot_id` bigint(20) UNSIGNED NOT NULL,
  `soni` int(11) NOT NULL,
  `birlik_kelish_narxi` decimal(12,2) NOT NULL DEFAULT 0.00,
  `qator_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `kirim_tarkibi`
--

INSERT INTO `kirim_tarkibi` (`id`, `kirim_id`, `mahsulot_id`, `soni`, `birlik_kelish_narxi`, `qator_summa`, `yaratilgan_vaqt`) VALUES
(1, 1, 7, 15, 3500.00, 52500.00, '2026-03-24 08:00:41');

-- --------------------------------------------------------

--
-- Структура таблицы `mahsulotlar`
--

CREATE TABLE `mahsulotlar` (
  `id` int(20) UNSIGNED NOT NULL,
  `kategoriya_id` bigint(20) UNSIGNED NOT NULL,
  `subkategoriya_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nomi` varchar(160) NOT NULL,
  `shtrix_kod` varchar(80) NOT NULL,
  `birlik` varchar(30) NOT NULL DEFAULT 'dona',
  `kelish_narxi` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sotish_narxi` decimal(12,2) NOT NULL DEFAULT 0.00,
  `miqdor` int(11) NOT NULL DEFAULT 0,
  `minimal_miqdor` int(11) NOT NULL DEFAULT 0,
  `faol` tinyint(1) NOT NULL DEFAULT 1,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ochirilgan_vaqt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `mahsulotlar`
--

INSERT INTO `mahsulotlar` (`id`, `kategoriya_id`, `subkategoriya_id`, `nomi`, `shtrix_kod`, `birlik`, `kelish_narxi`, `sotish_narxi`, `miqdor`, `minimal_miqdor`, `faol`, `yaratilgan_vaqt`, `yangilangan_vaqt`, `ochirilgan_vaqt`) VALUES
(1, 16, 1, 'Montella suv 0.5L', '478000000001', 'dona', 2500.00, 4000.00, 117, 10, 1, '2026-03-13 05:21:32', '2026-03-24 07:41:55', NULL),
(2, 16, 1, 'Nestle suv 1L', '478000000002', 'dona', 3500.00, 5500.00, 97, 10, 1, '2026-03-13 05:21:32', '2026-03-19 07:23:07', NULL),
(3, 18, 2, 'Coca-Cola 1L', '478000000003', 'dona', 7000.00, 10000.00, 89, 8, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(4, 18, 2, 'Fanta 1L', '478000000004', 'dona', 6800.00, 9800.00, 76, 8, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(5, 18, 2, 'Sprite 1L', '478000000005', 'dona', 6800.00, 9800.00, 83, 8, 1, '2026-03-13 05:21:32', '2026-03-13 07:36:52', NULL),
(6, 17, 1, 'Bliss olma sharbat 1L', '478000000006', 'dona', 9000.00, 12500.00, 57, 5, 1, '2026-03-13 05:21:32', '2026-03-24 07:36:13', NULL),
(7, 17, 1, 'Dena anor sharbat 1L', '478000000007', 'dona', 9500.00, 13000.00, 68, 5, 1, '2026-03-13 05:21:32', '2026-03-24 08:00:41', NULL),
(8, 2, 3, 'Musaffo sut 1L', '478000000008', 'dona', 8500.00, 11000.00, 69, 6, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(9, 2, 4, 'Qatiq 0.5L', '478000000009', 'dona', 6000.00, 8500.00, 63, 6, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(10, 2, 4, 'Kefir 0.5L', '478000000010', 'dona', 6200.00, 8800.00, 49, 6, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(11, 3, 5, 'Obi non', '478000000011', 'dona', 2500.00, 3500.00, 33, 10, 1, '2026-03-13 05:21:32', '2026-03-24 07:43:27', NULL),
(12, 3, 6, 'Bulochka shirin', '478000000012', 'dona', 3000.00, 5000.00, 33, 5, 1, '2026-03-13 05:21:32', '2026-03-13 07:36:52', NULL),
(13, 4, 7, 'Alpen Gold shokolad', '478000000013', 'dona', 12000.00, 16000.00, 45, 5, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(14, 4, 8, 'KDV konfet 1kg', '478000000014', 'kg', 45000.00, 58000.00, 16, 20, 1, '2026-03-13 05:21:32', '2026-03-19 07:23:07', NULL),
(15, 5, 9, 'Greenfield qora choy', '478000000015', 'quti', 18000.00, 24000.00, 30, 4, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(16, 5, 10, 'Nescafe Classic 95g', '478000000016', 'dona', 28000.00, 35000.00, 28, 4, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(17, 6, 11, 'Doktorskaya kolbasa', '478000000017', 'kg', 52000.00, 65000.00, 10, 15, 1, '2026-03-13 05:21:32', '2026-03-24 07:38:14', NULL),
(18, 6, 12, 'Sosiska premium', '478000000018', 'kg', 48000.00, 62000.00, 17, 3, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(19, 7, 13, 'Eskimo muzqaymoq', '478000000019', 'dona', 4500.00, 7000.00, 89, 10, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(20, 8, 15, 'Makfa makaron 400g', '478000000020', 'dona', 7000.00, 9500.00, 73, 8, 1, '2026-03-13 05:21:32', '2026-03-13 07:36:52', NULL),
(21, 8, 16, 'Guruch 1kg', '478000000021', 'kg', 12500.00, 16000.00, 55, 5, 1, '2026-03-13 05:21:32', '2026-03-13 07:36:52', NULL),
(22, 8, 16, 'Grechka 1kg', '478000000022', 'kg', 17000.00, 22000.00, 33, 4, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(23, 9, 18, 'Bonduelle makkajo‘xori', '478000000023', 'dona', 14000.00, 18000.00, 15, 20, 1, '2026-03-13 05:21:32', '2026-03-19 07:22:04', NULL),
(24, 10, 19, 'Oila kungaboqar yog‘i 1L', '478000000024', 'dona', 14500.00, 18500.00, 48, 6, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(25, 10, 20, 'Sariyog‘ 200g', '478000000025', 'dona', 16000.00, 21000.00, 28, 4, 1, '2026-03-13 05:21:32', '2026-03-13 07:36:52', NULL),
(26, 11, 21, 'Safeguard sovun', '478000000026', 'dona', 6000.00, 8500.00, 46, 5, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(27, 11, 22, 'Colgate tish pastasi', '478000000027', 'dona', 12000.00, 16000.00, 36, 5, 1, '2026-03-13 05:21:32', '2026-03-13 07:36:52', NULL),
(28, 12, 23, 'Ariel kir yuvish kukuni 3kg', '478000000028', 'dona', 48000.00, 58000.00, 2, 2, 1, '2026-03-13 05:21:32', '2026-03-13 07:43:13', NULL),
(29, 15, 27, 'Lays chips', '478000000029', 'dona', 9000.00, 13000.00, 68, 8, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL),
(30, 22, 30, 'Sloboda ketchup 500g', '478000000030', 'dona', 11000.00, 14500.00, 37, 4, 1, '2026-03-13 05:21:32', '2026-03-13 06:05:41', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `mijozlar`
--

CREATE TABLE `mijozlar` (
  `id` int(20) UNSIGNED NOT NULL,
  `fio` varchar(160) NOT NULL,
  `telefon` varchar(30) DEFAULT NULL,
  `manzil` varchar(255) DEFAULT NULL,
  `izoh` varchar(255) DEFAULT NULL,
  `faol` tinyint(1) NOT NULL DEFAULT 1,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `mijozlar`
--

INSERT INTO `mijozlar` (`id`, `fio`, `telefon`, `manzil`, `izoh`, `faol`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, 'Aliyev Sardor', '+998901112233', 'Qo‘qon shahri', 'Doimiy mijoz', 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32'),
(2, 'Karimova Mohira', '+998901112234', 'Qo‘qon shahri', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32');

-- --------------------------------------------------------

--
-- Структура таблицы `nasiya_tolovlar`
--

CREATE TABLE `nasiya_tolovlar` (
  `id` int(20) UNSIGNED NOT NULL,
  `mijoz_id` bigint(20) UNSIGNED NOT NULL,
  `savdo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tolov_usuli` enum('NAQD','KARTA','OTKAZMA') NOT NULL DEFAULT 'NAQD',
  `izoh` varchar(255) DEFAULT NULL,
  `qabul_qilgan_id` bigint(20) UNSIGNED NOT NULL,
  `tolov_vaqt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `ombor_jurnali`
--

CREATE TABLE `ombor_jurnali` (
  `id` int(20) UNSIGNED NOT NULL,
  `mahsulot_id` bigint(20) UNSIGNED NOT NULL,
  `amal` enum('KIRIM','CHIQIM','SOZLASH') NOT NULL,
  `miqdor_ozgarish` int(11) NOT NULL,
  `eski_miqdor` int(11) NOT NULL,
  `yangi_miqdor` int(11) NOT NULL,
  `manba_turi` enum('SAVDO','KIRIM','SOZLASH','QAYTARISH','BOSHQA') NOT NULL DEFAULT 'BOSHQA',
  `manba_id` bigint(20) UNSIGNED DEFAULT NULL,
  `foydalanuvchi_id` bigint(20) UNSIGNED NOT NULL,
  `izoh` varchar(255) DEFAULT NULL,
  `yaratilgan_vaqt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `ombor_jurnali`
--

INSERT INTO `ombor_jurnali` (`id`, `mahsulot_id`, `amal`, `miqdor_ozgarish`, `eski_miqdor`, `yangi_miqdor`, `manba_turi`, `manba_id`, `foydalanuvchi_id`, `izoh`, `yaratilgan_vaqt`) VALUES
(1, 6, 'KIRIM', 1, 56, 57, 'QAYTARISH', NULL, 0, 'Qaytarish: sa', '2026-03-24 12:36:13'),
(2, 17, 'KIRIM', 1, 10, 10, 'QAYTARISH', NULL, 0, 'Qaytarish: sa', '2026-03-24 12:40:22'),
(3, 1, 'KIRIM', 2, 114, 116, 'QAYTARISH', NULL, 0, 'Qaytarish: wq', '2026-03-24 12:41:09'),
(4, 1, 'KIRIM', 1, 116, 117, 'QAYTARISH', NULL, 0, 'Qaytarish: q', '2026-03-24 12:41:55'),
(5, 11, 'KIRIM', 1, 36, 37, 'QAYTARISH', NULL, 0, 'Qaytarish: w', '2026-03-24 12:42:52');

-- --------------------------------------------------------

--
-- Структура таблицы `qaytarishlar`
--

CREATE TABLE `qaytarishlar` (
  `id` int(20) UNSIGNED NOT NULL,
  `savdo_id` int(20) UNSIGNED NOT NULL COMMENT 'Asosiy savdo (chek) ID si',
  `savdo_tarkibi_id` int(20) UNSIGNED DEFAULT NULL COMMENT 'Savdo tarkibidagi aniq qator ID si (agar kerak bo‘lsa)',
  `mahsulot_id` int(20) UNSIGNED NOT NULL COMMENT 'Qaytarilgan mahsulot ID si',
  `miqdor` int(11) NOT NULL COMMENT 'Qaytarilgan miqdor',
  `summa` decimal(12,2) NOT NULL COMMENT 'Qaytarilgan summa',
  `sabab` varchar(255) DEFAULT NULL COMMENT 'Qaytarish sababi',
  `foydalanuvchi_id` int(20) UNSIGNED NOT NULL COMMENT 'Qaytarishni amalga oshirgan kassir ID si',
  `qaytarilgan_vaqt` timestamp NULL DEFAULT current_timestamp() COMMENT 'Qaytarish vaqti'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `qaytarishlar`
--

INSERT INTO `qaytarishlar` (`id`, `savdo_id`, `savdo_tarkibi_id`, `mahsulot_id`, `miqdor`, `summa`, `sabab`, `foydalanuvchi_id`, `qaytarilgan_vaqt`) VALUES
(1, 12, 67, 6, 1, 12500.00, 'sa', 3, '2026-03-24 07:36:13'),
(2, 13, 68, 17, 1, 32500.00, 'sa', 3, '2026-03-24 07:40:21'),
(3, 13, 69, 1, 2, 8000.00, 'wq', 3, '2026-03-24 07:41:09'),
(4, 13, 69, 1, 1, 4000.00, 'q', 3, '2026-03-24 07:41:55'),
(5, 13, 70, 11, 1, 3500.00, 'w', 3, '2026-03-24 07:42:52');

-- --------------------------------------------------------

--
-- Структура таблицы `rollar`
--

CREATE TABLE `rollar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomi` varchar(50) NOT NULL,
  `izoh` varchar(255) DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `rollar`
--

INSERT INTO `rollar` (`id`, `nomi`, `izoh`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, 'Admin', 'Tizim administratori', '2026-02-26 19:01:16', '2026-02-26 19:01:16'),
(2, 'Kassir', 'Savdo (POS) foydalanuvchisi', '2026-02-26 19:01:16', '2026-02-26 19:01:16'),
(3, 'Omborchi', 'Kirim/ombor uchun foydalanuvchi', '2026-02-26 19:01:16', '2026-02-26 19:01:16');

-- --------------------------------------------------------

--
-- Структура таблицы `savdolar`
--

CREATE TABLE `savdolar` (
  `id` int(20) UNSIGNED NOT NULL,
  `chek_raqami` varchar(50) NOT NULL,
  `kassir_id` bigint(20) UNSIGNED NOT NULL,
  `kassa_smena_id` bigint(20) UNSIGNED DEFAULT NULL,
  `mijoz_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tolov_usuli` enum('NAQD','KARTA','OTKAZMA','ARALASH') NOT NULL DEFAULT 'NAQD',
  `tolov_holati` enum('TOLANGAN','QISMAN','NASIYA') NOT NULL DEFAULT 'TOLANGAN',
  `umumiy_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `chegirma_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `yakuniy_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tolangan_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `qarz_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `holat` enum('QORALAMA','YAKUNLANGAN','BEKOR') NOT NULL DEFAULT 'YAKUNLANGAN',
  `sotilgan_vaqt` datetime NOT NULL DEFAULT current_timestamp(),
  `izoh` varchar(255) DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `savdolar`
--

INSERT INTO `savdolar` (`id`, `chek_raqami`, `kassir_id`, `kassa_smena_id`, `mijoz_id`, `tolov_usuli`, `tolov_holati`, `umumiy_summa`, `chegirma_summa`, `yakuniy_summa`, `tolangan_summa`, `qarz_summa`, `holat`, `sotilgan_vaqt`, `izoh`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, 'CHK-20260313-0001-647', 3, NULL, 1, 'NAQD', 'QISMAN', 116500.00, 0.00, 116500.00, 16500.00, 100000.00, 'YAKUNLANGAN', '2026-03-13 10:27:19', 'qarz', '2026-03-13 05:27:19', '2026-03-13 05:27:19'),
(2, 'CHK-20260313-0002-855', 2, NULL, 2, 'KARTA', 'QISMAN', 269500.00, 500.00, 269000.00, 250000.00, 19000.00, 'YAKUNLANGAN', '2026-03-13 10:28:58', 'qarz', '2026-03-13 05:28:58', '2026-03-13 05:28:58'),
(3, 'CHK-20260313-0003-508', 2, NULL, NULL, 'ARALASH', 'TOLANGAN', 0.00, 2000.00, 0.00, 0.00, 0.00, 'BEKOR', '2026-03-13 10:35:42', '', '2026-03-13 05:35:42', '2026-03-13 05:59:52'),
(4, 'CHK-20260313-0004-975', 2, NULL, NULL, 'NAQD', 'TOLANGAN', 63400.00, 400.00, 63000.00, 63000.00, 0.00, 'YAKUNLANGAN', '2026-03-13 10:57:59', '', '2026-03-13 05:57:59', '2026-03-13 05:57:59'),
(5, 'CHK-20260313-0005-684', 3, NULL, 1, 'KARTA', 'QISMAN', 162000.00, 0.00, 162000.00, 100000.00, 62000.00, 'YAKUNLANGAN', '2026-03-13 11:03:02', '', '2026-03-13 06:03:02', '2026-03-13 06:06:35'),
(6, 'CHK-20260313-0006-409', 3, NULL, 2, 'KARTA', 'QISMAN', 406000.00, 0.00, 406000.00, 356000.00, 50000.00, 'YAKUNLANGAN', '2026-03-13 11:04:12', '', '2026-03-13 06:04:12', '2026-03-13 06:06:59'),
(7, 'CHK-20260313-0007-887', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 650000.00, 0.00, 650000.00, 650000.00, 0.00, 'YAKUNLANGAN', '2026-03-13 11:05:04', '', '2026-03-13 06:05:04', '2026-03-13 06:05:04'),
(8, 'CHK-20260313-0008-449', 2, NULL, NULL, 'NAQD', 'TOLANGAN', 450400.00, 0.00, 450400.00, 450400.00, 0.00, 'YAKUNLANGAN', '2026-03-13 11:05:41', '', '2026-03-13 06:05:41', '2026-03-13 06:05:41'),
(9, 'CHK-20260313-0009-767', 3, NULL, NULL, 'ARALASH', 'TOLANGAN', 382800.00, 2800.00, 380000.00, 380000.00, 0.00, 'YAKUNLANGAN', '2026-03-13 12:36:52', '', '2026-03-13 07:36:52', '2026-03-13 07:36:52'),
(10, 'CHK-20260313-0010-262', 3, NULL, 2, 'NAQD', 'QISMAN', 580000.00, 0.00, 580000.00, 330000.00, 250000.00, 'YAKUNLANGAN', '2026-03-13 12:43:13', '', '2026-03-13 07:43:13', '2026-03-19 07:20:57'),
(11, 'CHK-20260319-0001-187', 3, NULL, 2, 'NAQD', 'QISMAN', 39660.00, 660.00, 39000.00, 19000.00, 20000.00, 'YAKUNLANGAN', '2026-03-19 12:23:07', '', '2026-03-19 07:23:07', '2026-03-24 06:40:12'),
(12, 'CHK-20260324-0001-357', 3, NULL, NULL, 'ARALASH', 'TOLANGAN', 13000.00, 500.00, 12500.00, 12000.00, 500.00, 'YAKUNLANGAN', '2026-03-24 09:45:14', '0', '2026-03-24 04:45:14', '2026-03-24 07:36:13'),
(13, 'CHK-20260324-0002-267', 3, NULL, NULL, 'ARALASH', 'TOLANGAN', 13000.00, 0.00, 13000.00, 13000.00, 0.00, 'YAKUNLANGAN', '2026-03-24 12:38:14', '', '2026-03-24 07:38:14', '2026-03-24 07:40:22'),
(14, 'CHK-20260324-0003-158', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 46500.00, 500.00, 46000.00, 46000.00, 0.00, 'YAKUNLANGAN', '2026-03-24 12:43:27', '', '2026-03-24 07:43:27', '2026-03-24 07:43:27'),
(15, 'CHK-20260324-0004-450', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 32500.00, 0.00, 32500.00, 32500.00, 0.00, 'YAKUNLANGAN', '2026-03-24 12:47:30', '', '2026-03-24 07:47:30', '2026-03-24 07:47:30');

-- --------------------------------------------------------

--
-- Структура таблицы `savdo_slotlari`
--

CREATE TABLE `savdo_slotlari` (
  `id` int(20) UNSIGNED NOT NULL,
  `kassir_id` bigint(20) UNSIGNED NOT NULL,
  `slot_raqami` int(11) NOT NULL,
  `mijoz_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `umumiy_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `holat` enum('aktiv','kutilmoqda','tugatilgan') NOT NULL DEFAULT 'aktiv',
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `savdo_slotlari`
--

INSERT INTO `savdo_slotlari` (`id`, `kassir_id`, `slot_raqami`, `mijoz_id`, `nom`, `umumiy_summa`, `holat`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, 3, 1, NULL, 'Mijoz 1', 0.00, 'aktiv', '2026-03-13 05:23:06', '2026-03-13 05:23:06'),
(2, 2, 1, NULL, 'Mijoz 1', 0.00, 'aktiv', '2026-03-13 05:28:10', '2026-03-13 05:28:10');

-- --------------------------------------------------------

--
-- Структура таблицы `savdo_slot_items`
--

CREATE TABLE `savdo_slot_items` (
  `id` int(20) UNSIGNED NOT NULL,
  `slot_id` bigint(20) UNSIGNED NOT NULL,
  `mahsulot_id` bigint(20) UNSIGNED NOT NULL,
  `soni` int(11) NOT NULL,
  `birlik_narx` decimal(12,2) NOT NULL,
  `chegirma` decimal(12,2) NOT NULL DEFAULT 0.00,
  `qator_summa` decimal(12,2) NOT NULL,
  `qoshilgan_vaqt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `savdo_tarkibi`
--

CREATE TABLE `savdo_tarkibi` (
  `id` int(20) UNSIGNED NOT NULL,
  `savdo_id` bigint(20) UNSIGNED NOT NULL,
  `mahsulot_id` bigint(20) UNSIGNED NOT NULL,
  `soni` float NOT NULL,
  `birlik_narx` decimal(12,2) NOT NULL DEFAULT 0.00,
  `chegirma` decimal(12,2) NOT NULL DEFAULT 0.00,
  `qator_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `savdo_tarkibi`
--

INSERT INTO `savdo_tarkibi` (`id`, `savdo_id`, `mahsulot_id`, `soni`, `birlik_narx`, `chegirma`, `qator_summa`, `yaratilgan_vaqt`) VALUES
(1, 1, 29, 1, 13000.00, 0.00, 13000.00, '2026-03-13 05:27:19'),
(2, 1, 28, 1, 58000.00, 0.00, 58000.00, '2026-03-13 05:27:19'),
(3, 1, 27, 1, 16000.00, 0.00, 16000.00, '2026-03-13 05:27:19'),
(4, 1, 26, 1, 8500.00, 0.00, 8500.00, '2026-03-13 05:27:19'),
(5, 1, 25, 1, 21000.00, 0.00, 21000.00, '2026-03-13 05:27:19'),
(6, 2, 25, 1, 21000.00, 0.00, 21000.00, '2026-03-13 05:28:58'),
(7, 2, 24, 1, 18500.00, 0.00, 18500.00, '2026-03-13 05:28:59'),
(8, 2, 23, 1, 18000.00, 0.00, 18000.00, '2026-03-13 05:28:59'),
(9, 2, 22, 1, 22000.00, 0.00, 22000.00, '2026-03-13 05:28:59'),
(10, 2, 21, 1, 16000.00, 0.00, 16000.00, '2026-03-13 05:28:59'),
(11, 2, 28, 3, 58000.00, 0.00, 174000.00, '2026-03-13 05:28:59'),
(18, 4, 4, 3, 9800.00, 0.00, 29400.00, '2026-03-13 05:57:59'),
(19, 4, 2, 1, 5500.00, 0.00, 5500.00, '2026-03-13 05:58:00'),
(20, 4, 1, 1, 4000.00, 0.00, 4000.00, '2026-03-13 05:58:00'),
(21, 4, 6, 1, 12500.00, 0.00, 12500.00, '2026-03-13 05:58:00'),
(22, 4, 9, 1, 8500.00, 0.00, 8500.00, '2026-03-13 05:58:00'),
(23, 4, 11, 1, 3500.00, 0.00, 3500.00, '2026-03-13 05:58:00'),
(24, 5, 23, 9, 18000.00, 0.00, 162000.00, '2026-03-13 06:03:02'),
(25, 6, 14, 7, 58000.00, 0.00, 406000.00, '2026-03-13 06:04:12'),
(26, 7, 17, 10, 65000.00, 0.00, 650000.00, '2026-03-13 06:05:04'),
(27, 8, 17, 1, 65000.00, 0.00, 65000.00, '2026-03-13 06:05:41'),
(28, 8, 18, 1, 62000.00, 0.00, 62000.00, '2026-03-13 06:05:41'),
(29, 8, 12, 1, 5000.00, 0.00, 5000.00, '2026-03-13 06:05:41'),
(30, 8, 11, 1, 3500.00, 0.00, 3500.00, '2026-03-13 06:05:41'),
(31, 8, 10, 1, 8800.00, 0.00, 8800.00, '2026-03-13 06:05:41'),
(32, 8, 9, 1, 8500.00, 0.00, 8500.00, '2026-03-13 06:05:41'),
(33, 8, 8, 1, 11000.00, 0.00, 11000.00, '2026-03-13 06:05:41'),
(34, 8, 7, 1, 13000.00, 0.00, 13000.00, '2026-03-13 06:05:41'),
(35, 8, 1, 1, 4000.00, 0.00, 4000.00, '2026-03-13 06:05:41'),
(36, 8, 2, 1, 5500.00, 0.00, 5500.00, '2026-03-13 06:05:41'),
(37, 8, 3, 1, 10000.00, 0.00, 10000.00, '2026-03-13 06:05:41'),
(38, 8, 4, 1, 9800.00, 0.00, 9800.00, '2026-03-13 06:05:41'),
(39, 8, 5, 1, 9800.00, 0.00, 9800.00, '2026-03-13 06:05:41'),
(40, 8, 6, 1, 12500.00, 0.00, 12500.00, '2026-03-13 06:05:41'),
(41, 8, 24, 1, 18500.00, 0.00, 18500.00, '2026-03-13 06:05:41'),
(42, 8, 23, 1, 18000.00, 0.00, 18000.00, '2026-03-13 06:05:41'),
(43, 8, 22, 1, 22000.00, 0.00, 22000.00, '2026-03-13 06:05:41'),
(44, 8, 21, 1, 16000.00, 0.00, 16000.00, '2026-03-13 06:05:41'),
(45, 8, 20, 1, 9500.00, 0.00, 9500.00, '2026-03-13 06:05:41'),
(46, 8, 19, 1, 7000.00, 0.00, 7000.00, '2026-03-13 06:05:41'),
(47, 8, 25, 1, 21000.00, 0.00, 21000.00, '2026-03-13 06:05:41'),
(48, 8, 26, 1, 8500.00, 0.00, 8500.00, '2026-03-13 06:05:41'),
(49, 8, 27, 1, 16000.00, 0.00, 16000.00, '2026-03-13 06:05:41'),
(50, 8, 28, 1, 58000.00, 0.00, 58000.00, '2026-03-13 06:05:41'),
(51, 8, 29, 1, 13000.00, 0.00, 13000.00, '2026-03-13 06:05:41'),
(52, 8, 30, 1, 14500.00, 0.00, 14500.00, '2026-03-13 06:05:41'),
(53, 9, 21, 3, 16000.00, 0.00, 48000.00, '2026-03-13 07:36:52'),
(54, 9, 20, 1, 9500.00, 0.00, 9500.00, '2026-03-13 07:36:52'),
(55, 9, 12, 1, 5000.00, 0.00, 5000.00, '2026-03-13 07:36:52'),
(56, 9, 14, 1, 58000.00, 0.00, 58000.00, '2026-03-13 07:36:52'),
(57, 9, 7, 1, 13000.00, 0.00, 13000.00, '2026-03-13 07:36:52'),
(58, 9, 6, 1, 12500.00, 0.00, 12500.00, '2026-03-13 07:36:52'),
(59, 9, 5, 1, 9800.00, 0.00, 9800.00, '2026-03-13 07:36:52'),
(60, 9, 28, 3, 58000.00, 0.00, 174000.00, '2026-03-13 07:36:52'),
(61, 9, 27, 2, 16000.00, 0.00, 32000.00, '2026-03-13 07:36:52'),
(62, 9, 25, 1, 21000.00, 0.00, 21000.00, '2026-03-13 07:36:52'),
(63, 10, 28, 10, 58000.00, 0.00, 580000.00, '2026-03-13 07:43:13'),
(64, 11, 1, 1, 4000.00, 0.00, 4000.00, '2026-03-19 07:23:07'),
(65, 11, 2, 1, 5500.00, 0.00, 5500.00, '2026-03-19 07:23:07'),
(66, 11, 14, 1, 58000.00, 0.00, 30160.00, '2026-03-19 07:23:07'),
(68, 13, 17, 0, 65000.00, 0.00, 0.00, '2026-03-24 07:38:14'),
(70, 13, 11, 1, 3500.00, 0.00, 3500.00, '2026-03-24 07:38:14'),
(71, 14, 11, 4, 3500.00, 0.00, 14000.00, '2026-03-24 07:43:27'),
(72, 14, 17, 1, 65000.00, 0.00, 32500.00, '2026-03-24 07:43:27'),
(73, 15, 17, 0.5, 65000.00, 0.00, 32500.00, '2026-03-24 07:47:30');

-- --------------------------------------------------------

--
-- Структура таблицы `sozlamalar`
--

CREATE TABLE `sozlamalar` (
  `id` int(20) UNSIGNED NOT NULL,
  `kalit_soz` varchar(100) NOT NULL,
  `qiymat` text DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `subkategoriyalar`
--

CREATE TABLE `subkategoriyalar` (
  `id` int(20) UNSIGNED NOT NULL,
  `kategoriya_id` bigint(20) UNSIGNED NOT NULL,
  `nomi` varchar(120) NOT NULL,
  `izoh` varchar(255) DEFAULT NULL,
  `faol` tinyint(1) NOT NULL DEFAULT 1,
  `tartib` int(11) NOT NULL DEFAULT 0,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ochirilgan_vaqt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `subkategoriyalar`
--

INSERT INTO `subkategoriyalar` (`id`, `kategoriya_id`, `nomi`, `izoh`, `faol`, `tartib`, `yaratilgan_vaqt`, `yangilangan_vaqt`, `ochirilgan_vaqt`) VALUES
(1, 1, 'Gazsiz ichimliklar', 'Oddiy ichimliklar', 1, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(2, 1, 'Gazli ichimliklar', 'Gazlangan ichimliklar', 1, 2, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(3, 2, 'Sutlar', 'Tabiiy va qadoqlangan sutlar', 1, 3, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(4, 2, 'Qatiq va kefir', 'Qatiq, kefir mahsulotlari', 1, 4, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(5, 3, 'Oddiy non', 'Har kungi non mahsulotlari', 1, 5, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(6, 3, 'Pishiriqlar', 'Bulochka va pechenye', 1, 6, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(7, 4, 'Shokolad', 'Turli xil shokoladlar', 1, 7, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(8, 4, 'Konfet', 'O‘ralgan va o‘ralmagan konfetlar', 1, 8, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(9, 5, 'Qora choy', 'Qora choy turlari', 1, 9, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(10, 5, 'Qahva', 'Eriydigan va tabiiy qahva', 1, 10, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(11, 6, 'Kolbasa', 'Qaynatilgan va dudlangan kolbasa', 1, 11, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(12, 6, 'Sosiska', 'Sosiska mahsulotlari', 1, 12, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(13, 7, 'Muzqaymoq', 'Har xil muzqaymoqlar', 1, 13, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(14, 7, 'Muzlatilgan yarim tayyor', 'Kotlet, somsa va boshqalar', 1, 14, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(15, 8, 'Makaron', 'Makaron va spagetti', 1, 15, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(16, 8, 'Yorma', 'Guruch, grechka, no‘xat', 1, 16, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(17, 9, 'Baliq konservasi', 'Baliqli konserva', 1, 17, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(18, 9, 'Sabzavot konservasi', 'No‘xat, makkajo‘xori', 1, 18, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(19, 10, 'O‘simlik yog‘i', 'Paxta va kungaboqar yog‘i', 1, 19, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(20, 10, 'Sariyog‘', 'Qadoqlangan sariyog‘', 1, 20, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(21, 11, 'Sovun', 'Qattiq va suyuq sovun', 1, 21, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(22, 11, 'Tish pastasi', 'Og‘iz gigiyenasi mahsulotlari', 1, 22, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(23, 12, 'Kir yuvish kukuni', 'Kiyim uchun vositalar', 1, 23, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(24, 12, 'Idish yuvish geli', 'Idish tozalash vositalari', 1, 24, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(25, 13, 'Mevalar', 'Yangi mevalar', 1, 25, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(26, 13, 'Sabzavotlar', 'Yangi sabzavotlar', 1, 26, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(27, 15, 'Chips', 'Kartoshka chipslari', 1, 27, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(28, 15, 'Qarsildoq', 'Kraker va qarsildoq mahsulotlar', 1, 28, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(29, 22, 'Mayonez', 'Mayonez mahsulotlari', 1, 29, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL),
(30, 22, 'Ketchup', 'Pomidorli ketchup mahsulotlari', 1, 30, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tolovlar`
--

CREATE TABLE `tolovlar` (
  `id` int(20) UNSIGNED NOT NULL,
  `savdo_id` bigint(20) UNSIGNED NOT NULL,
  `mijoz_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kassa_smena_id` bigint(20) UNSIGNED DEFAULT NULL,
  `usul` enum('NAQD','KARTA','OTKAZMA','ARALASH') NOT NULL DEFAULT 'NAQD',
  `summa` decimal(12,2) NOT NULL,
  `izoh` varchar(255) DEFAULT NULL,
  `qabul_qilgan_id` bigint(20) UNSIGNED NOT NULL,
  `tolangan_vaqt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tolovlar`
--

INSERT INTO `tolovlar` (`id`, `savdo_id`, `mijoz_id`, `kassa_smena_id`, `usul`, `summa`, `izoh`, `qabul_qilgan_id`, `tolangan_vaqt`) VALUES
(1, 1, 1, NULL, 'NAQD', 16500.00, 'Boshlang\'ich to\'lov', 3, '2026-03-13 10:27:19'),
(2, 2, 2, NULL, 'KARTA', 250000.00, 'Boshlang\'ich to\'lov', 2, '2026-03-13 10:28:59'),
(3, 5, 1, NULL, 'KARTA', 0.00, 'Boshlang\'ich to\'lov', 3, '2026-03-13 11:03:02'),
(4, 6, 2, NULL, 'KARTA', 206000.00, 'Boshlang\'ich to\'lov', 3, '2026-03-13 11:04:12'),
(5, 5, 1, NULL, 'NAQD', 100000.00, 'ertaga', 3, '2026-03-13 11:06:35'),
(6, 6, 2, NULL, 'NAQD', 150000.00, 'ertaga', 3, '2026-03-13 11:06:59'),
(7, 10, 2, NULL, 'NAQD', 80000.00, 'Boshlang\'ich to\'lov', 3, '2026-03-13 12:43:13'),
(8, 10, 2, NULL, 'NAQD', 250000.00, 'as', 3, '2026-03-19 12:20:57'),
(9, 11, 2, NULL, 'NAQD', 15000.00, 'Boshlang\'ich to\'lov', 3, '2026-03-19 12:23:07'),
(10, 11, 2, NULL, 'NAQD', 4000.00, '', 3, '2026-03-24 11:40:12');

-- --------------------------------------------------------

--
-- Структура таблицы `yetkazib_beruvchilar`
--

CREATE TABLE `yetkazib_beruvchilar` (
  `id` int(20) UNSIGNED NOT NULL,
  `nomi` varchar(180) NOT NULL,
  `telefon` varchar(30) DEFAULT NULL,
  `manzil` varchar(255) DEFAULT NULL,
  `izoh` varchar(255) DEFAULT NULL,
  `kelish_kuni` varchar(20) DEFAULT NULL,
  `qarz` decimal(12,2) NOT NULL DEFAULT 0.00,
  `oxirgi_olingan_sana` date DEFAULT NULL,
  `eslatma` text DEFAULT NULL,
  `faol` tinyint(1) NOT NULL DEFAULT 1,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ochirilgan_vaqt` timestamp NULL DEFAULT NULL,
  `tolash_muddati` int(11) DEFAULT NULL COMMENT 'To''lash muddati (kunlarda)',
  `tolash_eslatma` text DEFAULT NULL,
  `oxirgi_tolov_sana` date DEFAULT NULL,
  `jami_olingan` decimal(12,2) DEFAULT 0.00,
  `jami_tolangan` decimal(12,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `yetkazib_beruvchilar`
--

INSERT INTO `yetkazib_beruvchilar` (`id`, `nomi`, `telefon`, `manzil`, `izoh`, `kelish_kuni`, `qarz`, `oxirgi_olingan_sana`, `eslatma`, `faol`, `yaratilgan_vaqt`, `yangilangan_vaqt`, `ochirilgan_vaqt`, `tolash_muddati`, `tolash_eslatma`, `oxirgi_tolov_sana`, `jami_olingan`, `jami_tolangan`) VALUES
(1, 'Fayz Trade', '+998911110001', 'Toshkent', 'Ichimliklar yetkazadi', 'Dushanba', 0.00, '2026-03-01', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:29', '2026-03-24 07:59:29', 7, 'Haftalik to‘lov', '2026-03-05', 3500000.00, 3500000.00),
(2, 'Baraka Foods', '+998911110002', 'Qo‘qon', 'Sut mahsulotlari', 'Seshanba', 150000.00, '2026-03-02', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 5, '5 kun ichida', '2026-03-06', 2800000.00, 2650000.00),
(3, 'Oltin Don Non', '+998911110003', 'Qo‘qon', 'Non va pishiriqlar', 'Har kuni', 0.00, '2026-03-10', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:31', '2026-03-24 07:59:31', 1, 'Kunlik hisob', '2026-03-10', 1200000.00, 1200000.00),
(4, 'Sweet Market Supply', '+998911110004', 'Toshkent', 'Shirinliklar', 'Payshanba', 200000.00, '2026-03-03', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 7, 'Haftalik', '2026-03-07', 3100000.00, 2900000.00),
(5, 'Coffee Tea Servis', '+998911110005', 'Farg‘ona', 'Choy va qahva', 'Juma', 0.00, '2026-03-04', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:26', '2026-03-24 07:59:26', 10, '10 kun ichida', '2026-03-08', 1800000.00, 1800000.00),
(6, 'Go‘sht Savdo Plus', '+998911110006', 'Qo‘qon', 'Kolbasa va go‘sht mahsulotlari', 'Chorshanba', 372500.00, '2026-03-24', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 08:00:41', NULL, 5, 'Qisqa muddatli', '2026-03-09', 4052500.00, 3680000.00),
(7, 'Muzqaymoq Servis', '+998911110007', 'Toshkent', 'Muzqaymoq yetkazib beradi', 'Shanba', 0.00, '2026-03-01', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:33', '2026-03-24 07:59:33', 7, 'Haftalik', '2026-03-06', 2100000.00, 2100000.00),
(8, 'Makaron House', '+998911110008', 'Andijon', 'Makaron mahsulotlari', 'Dushanba', 0.00, '2026-03-02', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:35', '2026-03-24 07:59:35', 14, '2 haftalik', '2026-03-09', 1700000.00, 1700000.00),
(9, 'Konserva Trade', '+998911110009', 'Namangan', 'Konserva mahsulotlari', 'Seshanba', 50000.00, '2026-03-03', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 7, 'Haftalik', '2026-03-07', 1300000.00, 1250000.00),
(10, 'Yog‘ Markaz', '+998911110010', 'Qo‘qon', 'Yog‘ mahsulotlari', 'Juma', 0.00, '2026-03-04', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:38', '2026-03-24 07:59:38', 5, '5 kun', '2026-03-08', 2200000.00, 2200000.00),
(11, 'Clean World', '+998911110011', 'Toshkent', 'Gigiyena vositalari', 'Payshanba', 110000.00, '2026-03-05', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 10, '10 kun', '2026-03-10', 2600000.00, 2490000.00),
(12, 'Toza Uy Servis', '+998911110012', 'Farg‘ona', 'Tozalash vositalari', 'Dushanba', 0.00, '2026-03-02', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:46', '2026-03-24 07:59:46', 7, 'Haftalik', '2026-03-08', 2400000.00, 2400000.00),
(13, 'Fresh Agro', '+998911110013', 'Rishton', 'Meva va sabzavotlar', 'Har kuni', 0.00, '2026-03-10', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:49', '2026-03-24 07:59:49', 1, 'Kunlik', '2026-03-10', 900000.00, 900000.00),
(14, 'Baby Care Supply', '+998911110014', 'Toshkent', 'Bolalar mahsulotlari', 'Seshanba', 70000.00, '2026-03-03', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 10, '10 kun', '2026-03-09', 1900000.00, 1830000.00),
(15, 'Snack Planet', '+998911110015', 'Namangan', 'Chips va tamaddilar', 'Juma', 0.00, '2026-03-04', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:52', '2026-03-24 07:59:52', 7, 'Haftalik', '2026-03-08', 1600000.00, 1600000.00),
(16, 'Aqua Trade', '+998911110016', 'Toshkent', 'Suvlar', 'Dushanba', 0.00, '2026-03-01', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:54', '2026-03-24 07:59:54', 7, 'Haftalik', '2026-03-06', 2000000.00, 2000000.00),
(17, 'Juice Distribution', '+998911110017', 'Farg‘ona', 'Sharbatlar', 'Payshanba', 90000.00, '2026-03-03', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 7, 'Haftalik', '2026-03-08', 2100000.00, 2010000.00),
(18, 'Soft Drinks Group', '+998911110018', 'Toshkent', 'Gazli ichimliklar', 'Seshanba', 0.00, '2026-03-02', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:57', '2026-03-24 07:59:57', 7, 'Haftalik', '2026-03-07', 4300000.00, 4300000.00),
(19, 'Energy Max Supply', '+998911110019', 'Toshkent', 'Energetik ichimliklar', 'Juma', 120000.00, '2026-03-05', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 5, '5 kun', '2026-03-09', 2500000.00, 2380000.00),
(20, 'Dry Fruits Export', '+998911110020', 'Samarqand', 'Quruq mevalar', 'Chorshanba', 0.00, '2026-03-01', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:59', '2026-03-24 07:59:59', 14, '2 haftalik', '2026-03-08', 1450000.00, 1450000.00),
(21, 'Spice House', '+998911110021', 'Buxoro', 'Ziravorlar', 'Dushanba', 0.00, '2026-03-02', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 08:00:02', '2026-03-24 08:00:02', 10, '10 kun', '2026-03-09', 1100000.00, 1100000.00),
(22, 'Sauce Market', '+998911110022', 'Toshkent', 'Sous va ketchup', 'Payshanba', 60000.00, '2026-03-03', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 7, 'Haftalik', '2026-03-07', 1750000.00, 1690000.00),
(23, 'Parranda Agro', '+998911110023', 'Qo‘qon', 'Tuxum va parranda', 'Har kuni', 0.00, '2026-03-10', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 08:00:05', '2026-03-24 08:00:05', 1, 'Kunlik', '2026-03-10', 980000.00, 980000.00),
(24, 'Fast Food Servis', '+998911110024', 'Farg‘ona', 'Fast food ingredientlari', 'Seshanba', 85000.00, '2026-03-04', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 7, 'Haftalik', '2026-03-08', 2300000.00, 2215000.00),
(25, 'Uy Buyumlari Center', '+998911110025', 'Toshkent', 'Uy-ro‘zg‘or buyumlari', 'Juma', 0.00, '2026-03-03', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 08:00:09', '2026-03-24 08:00:09', 10, '10 kun', '2026-03-09', 1400000.00, 1400000.00),
(26, 'Office Mini Supply', '+998911110026', 'Qo‘qon', 'Ofis mayda buyumlari', 'Chorshanba', 0.00, '2026-03-02', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 08:00:12', '2026-03-24 08:00:12', 14, '2 haftalik', '2026-03-09', 900000.00, 900000.00),
(27, 'Aksiya Trade', '+998911110027', 'Toshkent', 'Chegirmadagi mahsulotlar', 'Dushanba', 50000.00, '2026-03-01', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 7, 'Haftalik', '2026-03-06', 1500000.00, 1450000.00),
(28, 'Import Food LLC', '+998911110028', 'Toshkent', 'Import oziq-ovqat', 'Payshanba', 0.00, '2026-03-04', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 08:00:15', '2026-03-24 08:00:15', 10, '10 kun', '2026-03-09', 5200000.00, 5200000.00),
(29, 'Mahalliy Savdo', '+998911110029', 'Qo‘qon', 'Mahalliy mahsulotlar', 'Har kuni', 0.00, '2026-03-10', NULL, 1, '2026-03-13 05:21:32', '2026-03-24 07:59:42', '2026-03-24 07:59:42', 3, '3 kun', '2026-03-10', 1350000.00, 1350000.00),
(30, 'Universal Supply', '+998911110030', 'Farg‘ona', 'Turli xil mahsulotlar', 'Shanba', 100000.00, '2026-03-05', NULL, 1, '2026-03-13 05:21:32', '2026-03-13 05:21:32', NULL, 7, 'Haftalik', '2026-03-10', 3000000.00, 2900000.00);

-- --------------------------------------------------------

--
-- Структура таблицы `yetkazib_beruvchi_tolovlari`
--

CREATE TABLE `yetkazib_beruvchi_tolovlari` (
  `id` int(20) UNSIGNED NOT NULL,
  `yetkazib_beruvchi_id` bigint(20) UNSIGNED NOT NULL,
  `sana` datetime NOT NULL,
  `summa` decimal(12,2) NOT NULL,
  `usul` enum('NAQD','KARTA','OTKAZMA') NOT NULL DEFAULT 'NAQD',
  `izoh` text DEFAULT NULL,
  `qabul_qilgan_id` bigint(20) UNSIGNED NOT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `foydalanuvchilar`
--
ALTER TABLE `foydalanuvchilar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `kassa_harakatlari`
--
ALTER TABLE `kassa_harakatlari`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `kassa_smenalari`
--
ALTER TABLE `kassa_smenalari`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `kategoriyalar`
--
ALTER TABLE `kategoriyalar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_kategoriyalar_nomi` (`nomi`),
  ADD KEY `idx_kategoriyalar_faol` (`faol`);

--
-- Индексы таблицы `kirimlar`
--
ALTER TABLE `kirimlar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `kirim_tarkibi`
--
ALTER TABLE `kirim_tarkibi`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mahsulotlar`
--
ALTER TABLE `mahsulotlar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mijozlar`
--
ALTER TABLE `mijozlar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `nasiya_tolovlar`
--
ALTER TABLE `nasiya_tolovlar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `ombor_jurnali`
--
ALTER TABLE `ombor_jurnali`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `qaytarishlar`
--
ALTER TABLE `qaytarishlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qaytarishlar_savdo` (`savdo_id`),
  ADD KEY `idx_qaytarishlar_mahsulot` (`mahsulot_id`),
  ADD KEY `idx_qaytarishlar_foydalanuvchi` (`foydalanuvchi_id`),
  ADD KEY `idx_qaytarishlar_vaqt` (`qaytarilgan_vaqt`);

--
-- Индексы таблицы `rollar`
--
ALTER TABLE `rollar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `savdolar`
--
ALTER TABLE `savdolar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `savdo_slotlari`
--
ALTER TABLE `savdo_slotlari`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `savdo_slot_items`
--
ALTER TABLE `savdo_slot_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `savdo_tarkibi`
--
ALTER TABLE `savdo_tarkibi`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sozlamalar`
--
ALTER TABLE `sozlamalar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `subkategoriyalar`
--
ALTER TABLE `subkategoriyalar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tolovlar`
--
ALTER TABLE `tolovlar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `yetkazib_beruvchilar`
--
ALTER TABLE `yetkazib_beruvchilar`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `yetkazib_beruvchi_tolovlari`
--
ALTER TABLE `yetkazib_beruvchi_tolovlari`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `foydalanuvchilar`
--
ALTER TABLE `foydalanuvchilar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `kassa_harakatlari`
--
ALTER TABLE `kassa_harakatlari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `kassa_smenalari`
--
ALTER TABLE `kassa_smenalari`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `kategoriyalar`
--
ALTER TABLE `kategoriyalar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `kirimlar`
--
ALTER TABLE `kirimlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `kirim_tarkibi`
--
ALTER TABLE `kirim_tarkibi`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `mahsulotlar`
--
ALTER TABLE `mahsulotlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `mijozlar`
--
ALTER TABLE `mijozlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `nasiya_tolovlar`
--
ALTER TABLE `nasiya_tolovlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ombor_jurnali`
--
ALTER TABLE `ombor_jurnali`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `qaytarishlar`
--
ALTER TABLE `qaytarishlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `rollar`
--
ALTER TABLE `rollar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `savdolar`
--
ALTER TABLE `savdolar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `savdo_slotlari`
--
ALTER TABLE `savdo_slotlari`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `savdo_slot_items`
--
ALTER TABLE `savdo_slot_items`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `savdo_tarkibi`
--
ALTER TABLE `savdo_tarkibi`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT для таблицы `sozlamalar`
--
ALTER TABLE `sozlamalar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subkategoriyalar`
--
ALTER TABLE `subkategoriyalar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `tolovlar`
--
ALTER TABLE `tolovlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `yetkazib_beruvchilar`
--
ALTER TABLE `yetkazib_beruvchilar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `yetkazib_beruvchi_tolovlari`
--
ALTER TABLE `yetkazib_beruvchi_tolovlari`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `qaytarishlar`
--
ALTER TABLE `qaytarishlar`
  ADD CONSTRAINT `fk_qaytarishlar_foydalanuvchi` FOREIGN KEY (`foydalanuvchi_id`) REFERENCES `foydalanuvchilar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qaytarishlar_mahsulot` FOREIGN KEY (`mahsulot_id`) REFERENCES `mahsulotlar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_qaytarishlar_savdo` FOREIGN KEY (`savdo_id`) REFERENCES `savdolar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
