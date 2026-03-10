-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 08 2026 г., 06:02
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
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 1, 'Administrator', 'abbosjontoychiboyev@gmail.com', '+(998)91 150-01-04', 'admin', '$2y$12$YgyO0kf2wZGF0BUodT1hLOVQyQUSDox4oWdZVSi9RW3HEyIPWSj62', 1, '2026-03-08 03:58:22', '2026-02-27 05:01:16', '2026-03-08 03:58:22', NULL),
(3, 2, 'Administrator', '', '+998930008827', 'seller1', '$2y$10$VxJeR1qYpb/HGAekI.p9nOP5qpXw58LeCuEbCUoqfvlvb8FD3Ei3G', 0, NULL, '2026-03-06 07:14:29', '2026-03-06 16:07:32', '2026-03-06 16:07:32'),
(4, 2, 'To\'ychiboyev Najmiddin Shukurjon o\'g\'li', 'kassa@example.com', '+998200045578', 'kassa', '$2y$10$FYht3HsHa5hHtToSa3kUMuBcEtlH3CI9EfOkLZy1/mtmvSVbxUhee', 1, '2026-03-08 04:02:43', '2026-03-06 16:07:15', '2026-03-08 04:02:43', NULL);

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
  `id` bigint(20) UNSIGNED NOT NULL,
  `kassir_id` bigint(20) UNSIGNED NOT NULL,
  `ochilgan_vaqt` datetime NOT NULL,
  `yopilgan_vaqt` datetime DEFAULT NULL,
  `ochilish_naqd` decimal(12,2) NOT NULL DEFAULT 0.00,
  `yopilish_naqd` decimal(12,2) DEFAULT NULL,
  `holat` enum('OCHIQ','YOPIQ') NOT NULL DEFAULT 'OCHIQ',
  `izoh` varchar(255) DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `kassa_smenalari`
--

INSERT INTO `kassa_smenalari` (`id`, `kassir_id`, `ochilgan_vaqt`, `yopilgan_vaqt`, `ochilish_naqd`, `yopilish_naqd`, `holat`, `izoh`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, 1, '2026-02-27 12:01:16', '2026-02-27 12:03:03', 500000.00, 450000.00, 'YOPIQ', 'sa', '2026-02-27 07:01:16', '2026-02-27 07:03:03'),
(2, 1, '2026-02-27 12:56:32', '2026-03-02 16:29:17', 55000.00, 60000.00, 'YOPIQ', 'q', '2026-02-27 07:56:32', '2026-03-02 11:29:17'),
(3, 1, '2026-03-02 16:29:24', '2026-03-04 14:52:05', 45000.00, 50000.00, 'YOPIQ', NULL, '2026-03-02 11:29:24', '2026-03-04 09:52:05'),
(4, 1, '2026-03-04 14:58:40', '2026-03-04 15:43:40', 105000.00, 98000.00, 'YOPIQ', NULL, '2026-03-04 09:58:40', '2026-03-04 10:43:40'),
(5, 1, '2026-03-06 09:32:01', NULL, 45000.00, NULL, 'OCHIQ', NULL, '2026-03-06 04:32:01', '2026-03-06 04:32:01'),
(6, 4, '2026-03-06 21:19:41', '2026-03-06 21:21:08', 300000.00, 250000.00, 'YOPIQ', NULL, '2026-03-06 16:19:41', '2026-03-06 16:21:08'),
(7, 4, '2026-03-08 09:13:39', NULL, 175000.00, NULL, 'OCHIQ', NULL, '2026-03-08 04:13:39', '2026-03-08 04:13:39');

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
(1, 'Suvlar', 'Barcha suvlar', 1, 0, '2026-02-27 05:33:56', '2026-02-27 05:33:56', NULL),
(2, 'Mevalar', 'Mevalar', 1, 1, '2026-03-04 09:18:34', '2026-03-04 09:18:34', NULL),
(3, 'Shkloandlar', 'Shkolandlar', 1, 2, '2026-03-06 15:16:01', '2026-03-06 15:16:01', NULL),
(4, 'Banishniy suvlar', '0.5 banishniy', 1, 3, '2026-03-06 15:16:22', '2026-03-06 15:16:22', NULL),
(5, 'Saryog\'lar', 'Margarinlar', 1, 4, '2026-03-06 15:17:01', '2026-03-06 15:17:01', NULL),
(6, 'Ro\'zg\'or', 'Ovqat pishirish uchun', 1, 5, '2026-03-06 15:17:31', '2026-03-06 15:17:31', NULL),
(7, 'Sut mahsulotlari', 'Barcha turdagi', 1, 6, '2026-03-06 15:20:28', '2026-03-06 15:20:28', NULL),
(8, 'Detskiy', 'Zivachka chupa chups ', 1, 7, '2026-03-06 15:23:33', '2026-03-06 15:23:33', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `kirimlar`
--

CREATE TABLE `kirimlar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(3, '1520015', 1, 1, 19500.00, 'QABUL_QILINDI', '2026-02-27 11:26:26', 'Dealy', '2026-02-27 06:26:26', '2026-02-27 06:26:26');

-- --------------------------------------------------------

--
-- Структура таблицы `kirim_tarkibi`
--

CREATE TABLE `kirim_tarkibi` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(3, 3, 2, 15, 1300.00, 19500.00, '2026-02-27 06:26:26');

-- --------------------------------------------------------

--
-- Структура таблицы `mahsulotlar`
--

CREATE TABLE `mahsulotlar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 1, 1, 'Hydrolife 0.5l', '45879652100584', 'dona', 1250.00, 1999.00, 0, 5, 1, '2026-02-27 05:43:12', '2026-03-07 18:06:54', NULL),
(2, 1, 2, 'Dealy 0.5l', '5879541002245684', 'dona', 1300.00, 1999.00, 4, 5, 1, '2026-02-27 05:43:44', '2026-03-06 06:51:00', NULL),
(3, 8, 14, 'Orbit', '42113270', 'dona', 4180.00, 5000.00, 61, 25, 1, '2026-03-06 15:27:17', '2026-03-06 16:20:26', NULL),
(4, 8, 14, 'STYX', '4780102223311', 'dona', 1200.00, 2000.00, 10, 5, 1, '2026-03-06 15:29:00', '2026-03-06 16:16:29', NULL),
(5, 8, 14, 'Element', '4780027071226', 'dona', 1130.00, 2000.00, 38, 20, 1, '2026-03-06 15:29:45', '2026-03-06 15:30:54', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `mijozlar`
--

CREATE TABLE `mijozlar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 'Alisher Karimov', '+998901234567', 'Farg\'ona viloyati farg\'ona shahar Eco City 68-uy 72-xonadon', '1-martga', 1, '2026-02-27 07:22:45', '2026-02-27 07:22:45'),
(2, 'Qarzchi Mijoz', '998974563214', 'Eco City 68-uy', 'qsqsq', 1, '2026-03-06 16:11:11', '2026-03-06 16:11:11');

-- --------------------------------------------------------

--
-- Структура таблицы `nasiya_tolovlar`
--

CREATE TABLE `nasiya_tolovlar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  `id` bigint(20) UNSIGNED NOT NULL,
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
(2, 2, 'KIRIM', 15, 20, 35, 'KIRIM', 3, 1, 'Kirim hujjati orqali', '2026-02-27 11:26:26'),
(3, 1, 'CHIQIM', -1, 20, 19, 'SAVDO', 1, 1, 'POS savdo', '2026-02-27 11:45:50'),
(4, 1, 'CHIQIM', -4, 19, 15, 'SAVDO', 2, 1, 'POS savdo', '2026-02-27 11:47:35'),
(5, 1, 'CHIQIM', -2, 15, 13, 'SAVDO', 3, 1, 'POS savdo', '2026-02-27 12:01:30'),
(6, 1, 'KIRIM', 2, 13, 15, 'SOZLASH', NULL, 1, 'Yangi suv keldi', '2026-03-04 13:46:31'),
(7, 1, 'KIRIM', 5, 15, 20, 'SOZLASH', NULL, 1, 'Keldi', '2026-03-04 13:46:47'),
(8, 3, 'CHIQIM', 0, 75, 75, 'KIRIM', NULL, 1, 'Yangi mahsulot qo\'shildi', '2026-03-06 20:27:17'),
(9, 4, 'CHIQIM', 0, 10, 10, 'KIRIM', NULL, 1, 'Yangi mahsulot qo\'shildi', '2026-03-06 20:29:00'),
(10, 5, 'CHIQIM', 0, 40, 40, 'KIRIM', NULL, 1, 'Yangi mahsulot qo\'shildi', '2026-03-06 20:29:45');

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
(1, 'Admin', 'Tizim administratori', '2026-02-27 05:01:16', '2026-02-27 05:01:16'),
(2, 'Kassir', 'Savdo (POS) foydalanuvchisi', '2026-02-27 05:01:16', '2026-02-27 05:01:16'),
(3, 'Omborchi', 'Kirim/ombor uchun foydalanuvchi', '2026-02-27 05:01:16', '2026-02-27 05:01:16');

-- --------------------------------------------------------

--
-- Структура таблицы `savdolar`
--

CREATE TABLE `savdolar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 'CHK-20260227-1772174749-643', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 1999.00, 499.00, 1500.00, 1500.00, 0.00, 'YAKUNLANGAN', '2026-02-27 11:45:49', NULL, '2026-02-27 06:45:49', '2026-02-27 06:45:49'),
(2, 'CHK-20260227-1772174855-612', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 7996.00, 496.00, 7500.00, 7500.00, 0.00, 'YAKUNLANGAN', '2026-02-27 11:47:35', 'sa', '2026-02-27 06:47:35', '2026-02-27 06:47:35'),
(3, 'CHK-20260227-1772175690-288', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 3998.00, 0.00, 3998.00, 3998.00, 0.00, 'YAKUNLANGAN', '2026-02-27 12:01:30', NULL, '2026-02-27 07:01:30', '2026-02-27 07:01:30'),
(4, '', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 9995.00, 995.00, 9000.00, 9000.00, 0.00, 'YAKUNLANGAN', '2026-03-04 15:19:28', '', '2026-03-04 10:19:28', '2026-03-04 10:19:28'),
(15, 'CHK-20260304-0001-428', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 5997.00, 0.00, 5997.00, 5997.00, 0.00, 'YAKUNLANGAN', '2026-03-04 15:30:38', '', '2026-03-04 10:30:38', '2026-03-04 10:30:38'),
(16, 'CHK-20260304-0002-598', 1, NULL, NULL, 'KARTA', 'TOLANGAN', 19990.00, 990.00, 19000.00, 19000.00, 0.00, 'YAKUNLANGAN', '2026-03-04 15:31:07', '', '2026-03-04 10:31:07', '2026-03-04 10:31:07'),
(17, 'CHK-20260304-0003-471', 1, NULL, NULL, 'ARALASH', 'TOLANGAN', 9995.00, 95.00, 9900.00, 9900.00, 0.00, 'YAKUNLANGAN', '2026-03-04 15:31:49', '', '2026-03-04 10:31:49', '2026-03-04 10:31:49'),
(18, 'CHK-20260304-0004-528', 1, NULL, 1, 'NAQD', 'TOLANGAN', 3998.00, 0.00, 3998.00, 3000.00, 998.00, 'YAKUNLANGAN', '2026-03-04 15:47:09', '', '2026-03-04 10:47:09', '2026-03-06 07:33:26'),
(19, 'CHK-20260306-0001-547', 1, NULL, 1, 'ARALASH', 'TOLANGAN', 7996.00, 96.00, 7900.00, 7900.00, 0.00, 'YAKUNLANGAN', '2026-03-06 08:29:19', '', '2026-03-06 03:29:19', '2026-03-06 07:32:54'),
(20, 'CHK-20260306-0002-446', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 0.00, 0.00, 0.00, 0.00, 0.00, 'BEKOR', '2026-03-06 08:29:51', '', '2026-03-06 03:29:51', '2026-03-06 03:46:50'),
(21, 'CHK-20260306-0003-116', 1, NULL, 1, 'NAQD', 'QISMAN', 0.00, 92.00, 0.00, 0.00, 0.00, 'BEKOR', '2026-03-06 09:32:30', '', '2026-03-06 04:32:30', '2026-03-06 06:51:00'),
(22, 'CHK-20260306-0004-841', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 5997.00, 0.00, 5997.00, 5997.00, 0.00, 'YAKUNLANGAN', '2026-03-06 09:36:44', '', '2026-03-06 04:36:44', '2026-03-06 04:36:44'),
(23, 'CHK-20260306-0005-886', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 11994.00, 0.00, 11994.00, 11994.00, 0.00, 'YAKUNLANGAN', '2026-03-06 09:39:10', '', '2026-03-06 04:39:10', '2026-03-06 04:39:10'),
(24, 'CHK-20260306-0006-410', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 13993.00, 0.00, 13993.00, 13993.00, 0.00, 'YAKUNLANGAN', '2026-03-06 09:40:00', '', '2026-03-06 04:40:00', '2026-03-06 04:40:00'),
(25, 'CHK-20260306-0007-364', 1, NULL, 1, 'NAQD', 'TOLANGAN', 5997.00, 0.00, 5997.00, 5997.00, 0.00, 'YAKUNLANGAN', '2026-03-06 12:40:29', '', '2026-03-06 07:40:29', '2026-03-06 16:10:22'),
(26, 'CHK-20260306-0008-849', 1, NULL, 1, 'NAQD', 'QISMAN', 24000.00, 4000.00, 20000.00, 10000.00, 10000.00, 'YAKUNLANGAN', '2026-03-06 20:30:54', '', '2026-03-06 15:30:54', '2026-03-06 16:10:00'),
(27, 'CHK-20260306-0009-121', 1, NULL, 2, 'ARALASH', 'NASIYA', 0.00, 999.00, 0.00, 0.00, 0.00, 'BEKOR', '2026-03-06 21:13:04', '', '2026-03-06 16:13:04', '2026-03-06 16:16:29'),
(28, 'CHK-20260306-0010-729', 4, NULL, NULL, 'NAQD', 'TOLANGAN', 20000.00, 0.00, 20000.00, 20000.00, 0.00, 'YAKUNLANGAN', '2026-03-06 21:19:57', '', '2026-03-06 16:19:57', '2026-03-06 16:19:57'),
(29, 'CHK-20260306-0011-178', 4, NULL, NULL, 'NAQD', 'TOLANGAN', 30000.00, 0.00, 30000.00, 35000.00, 0.00, 'YAKUNLANGAN', '2026-03-06 21:20:26', '', '2026-03-06 16:20:26', '2026-03-06 16:20:26'),
(30, 'CHK-20260307-0001-309', 1, NULL, NULL, 'NAQD', 'TOLANGAN', 5997.00, 0.00, 5997.00, 5997.00, 0.00, 'YAKUNLANGAN', '2026-03-07 23:06:54', '', '2026-03-07 18:06:54', '2026-03-07 18:06:54');

-- --------------------------------------------------------

--
-- Структура таблицы `savdo_slotlari`
--

CREATE TABLE `savdo_slotlari` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 1, 1, NULL, 'Mijoz 1', 1999.00, 'aktiv', '2026-03-07 18:14:59', '2026-03-07 18:24:12'),
(2, 1, 2, NULL, 'Mijoz 2', 0.00, 'kutilmoqda', '2026-03-07 18:23:17', '2026-03-07 18:23:27'),
(3, 1, 3, NULL, 'Mijoz 3', 0.00, 'aktiv', '2026-03-07 18:23:46', '2026-03-07 18:23:46'),
(4, 1, 4, NULL, 'Mijoz 4', 0.00, 'aktiv', '2026-03-07 18:23:50', '2026-03-07 18:23:50'),
(5, 4, 1, NULL, 'Mijoz 1', 0.00, 'aktiv', '2026-03-08 03:40:02', '2026-03-08 03:40:02');

-- --------------------------------------------------------

--
-- Структура таблицы `savdo_slot_items`
--

CREATE TABLE `savdo_slot_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slot_id` bigint(20) UNSIGNED NOT NULL,
  `mahsulot_id` bigint(20) UNSIGNED NOT NULL,
  `soni` int(11) NOT NULL,
  `birlik_narx` decimal(12,2) NOT NULL,
  `chegirma` decimal(12,2) NOT NULL DEFAULT 0.00,
  `qator_summa` decimal(12,2) NOT NULL,
  `qoshilgan_vaqt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `savdo_slot_items`
--

INSERT INTO `savdo_slot_items` (`id`, `slot_id`, `mahsulot_id`, `soni`, `birlik_narx`, `chegirma`, `qator_summa`, `qoshilgan_vaqt`) VALUES
(1, 1, 2, 1, 1999.00, 0.00, 1999.00, '2026-03-07 18:24:12');

-- --------------------------------------------------------

--
-- Структура таблицы `savdo_tarkibi`
--

CREATE TABLE `savdo_tarkibi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `savdo_id` bigint(20) UNSIGNED NOT NULL,
  `mahsulot_id` bigint(20) UNSIGNED NOT NULL,
  `soni` int(11) NOT NULL,
  `birlik_narx` decimal(12,2) NOT NULL DEFAULT 0.00,
  `chegirma` decimal(12,2) NOT NULL DEFAULT 0.00,
  `qator_summa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `savdo_tarkibi`
--

INSERT INTO `savdo_tarkibi` (`id`, `savdo_id`, `mahsulot_id`, `soni`, `birlik_narx`, `chegirma`, `qator_summa`, `yaratilgan_vaqt`) VALUES
(1, 1, 1, 1, 1999.00, 0.00, 1999.00, '2026-02-27 06:45:50'),
(2, 2, 1, 4, 1999.00, 0.00, 7996.00, '2026-02-27 06:47:35'),
(3, 3, 1, 2, 1999.00, 0.00, 3998.00, '2026-02-27 07:01:30'),
(4, 4, 2, 3, 1999.00, 0.00, 5997.00, '2026-03-04 10:19:28'),
(5, 4, 1, 2, 1999.00, 0.00, 3998.00, '2026-03-04 10:19:28'),
(6, 15, 2, 3, 1999.00, 0.00, 5997.00, '2026-03-04 10:30:39'),
(7, 16, 1, 6, 1999.00, 0.00, 11994.00, '2026-03-04 10:31:07'),
(8, 16, 2, 4, 1999.00, 0.00, 7996.00, '2026-03-04 10:31:07'),
(9, 17, 2, 3, 1999.00, 0.00, 5997.00, '2026-03-04 10:31:49'),
(10, 17, 1, 2, 1999.00, 0.00, 3998.00, '2026-03-04 10:31:50'),
(11, 18, 2, 2, 1999.00, 0.00, 3998.00, '2026-03-04 10:47:09'),
(12, 19, 1, 2, 1999.00, 0.00, 3998.00, '2026-03-06 03:29:19'),
(13, 19, 2, 2, 1999.00, 0.00, 3998.00, '2026-03-06 03:29:19'),
(17, 22, 2, 1, 1999.00, 0.00, 1999.00, '2026-03-06 04:36:44'),
(18, 22, 1, 2, 1999.00, 0.00, 3998.00, '2026-03-06 04:36:44'),
(19, 23, 2, 6, 1999.00, 0.00, 11994.00, '2026-03-06 04:39:10'),
(20, 24, 2, 7, 1999.00, 0.00, 13993.00, '2026-03-06 04:40:00'),
(21, 25, 1, 3, 1999.00, 0.00, 5997.00, '2026-03-06 07:40:29'),
(22, 26, 3, 4, 5000.00, 0.00, 20000.00, '2026-03-06 15:30:54'),
(23, 26, 5, 2, 2000.00, 0.00, 4000.00, '2026-03-06 15:30:54'),
(27, 28, 3, 4, 5000.00, 0.00, 20000.00, '2026-03-06 16:19:57'),
(28, 29, 3, 6, 5000.00, 0.00, 30000.00, '2026-03-06 16:20:26'),
(29, 30, 1, 3, 1999.00, 0.00, 5997.00, '2026-03-07 18:06:54');

-- --------------------------------------------------------

--
-- Структура таблицы `sozlamalar`
--

CREATE TABLE `sozlamalar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kalit_soz` varchar(100) NOT NULL,
  `qiymat` text DEFAULT NULL,
  `yaratilgan_vaqt` timestamp NULL DEFAULT current_timestamp(),
  `yangilangan_vaqt` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `sozlamalar`
--

INSERT INTO `sozlamalar` (`id`, `kalit_soz`, `qiymat`, `yaratilgan_vaqt`, `yangilangan_vaqt`) VALUES
(1, 'currency_name', 'so\'m', '2026-03-06 07:06:55', '2026-03-06 07:06:55'),
(2, 'currency_symbol', 'so\'m', '2026-03-06 07:06:56', '2026-03-06 07:06:56'),
(3, 'currency_position', 'right', '2026-03-06 07:06:56', '2026-03-06 07:06:56'),
(4, 'decimal_places', '0', '2026-03-06 07:06:56', '2026-03-06 07:06:56'),
(5, 'thousand_separator', ' ', '2026-03-06 07:06:56', '2026-03-06 07:06:56'),
(6, 'company_name', 'Alimuhammadxon', '2026-03-06 07:07:48', '2026-03-06 08:02:01'),
(7, 'company_address', 'Farg\'ona shahar Yangi O\'zbekiston massivi Nurafshin MFY 68-uy', '2026-03-06 07:07:48', '2026-03-06 07:07:48'),
(8, 'company_phone', '+998930008827', '2026-03-06 07:07:48', '2026-03-06 07:07:48'),
(9, 'company_email', 'alixon@example.com', '2026-03-06 07:07:48', '2026-03-06 07:07:48'),
(10, 'company_tax_number', '', '2026-03-06 07:07:48', '2026-03-06 07:07:48');

-- --------------------------------------------------------

--
-- Структура таблицы `subkategoriyalar`
--

CREATE TABLE `subkategoriyalar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 1, 'Gazli suvlar', 'Barcha turdagi', 1, 0, '2026-02-27 05:34:16', '2026-02-27 05:34:16', NULL),
(2, 1, 'Gazsiz suvlar', 'Barcha turdagi', 1, 1, '2026-02-27 05:40:38', '2026-02-27 05:40:38', NULL),
(3, 2, 'Quruq mevalar', 'Quruq mevalar', 1, 1, '2026-03-04 09:24:41', '2026-03-04 09:24:41', NULL),
(4, 2, 'Ho\'l mevalar', 'Ho\'l mevalar', 1, 2, '2026-03-04 09:25:01', '2026-03-04 09:25:01', NULL),
(5, 3, 'Plitkali', 'Plitkalilar', 1, 1, '2026-03-06 15:17:55', '2026-03-06 15:17:55', NULL),
(6, 3, 'Donalilar', 'Bruni Snikers ... ', 1, 2, '2026-03-06 15:18:23', '2026-03-06 15:18:23', NULL),
(7, 3, 'Kilolik', 'Step, Krakant, ....', 1, 3, '2026-03-06 15:18:50', '2026-03-06 15:18:50', NULL),
(8, 5, 'Margarin', 'Yeyish uchun emas', 1, 1, '2026-03-06 15:19:09', '2026-03-06 15:19:09', NULL),
(9, 5, 'Saryog\'', 'Yeyish uchun', 1, 2, '2026-03-06 15:19:27', '2026-03-06 15:19:27', NULL),
(10, 7, 'Qatiqlar', 'Chimyon', 1, 1, '2026-03-06 15:21:51', '2026-03-06 15:21:51', NULL),
(11, 7, 'Qaymoqlar', 'Chimyon', 1, 2, '2026-03-06 15:22:09', '2026-03-06 15:22:09', NULL),
(12, 7, 'Do\'lta', 'Qaymoqniki', 1, 3, '2026-03-06 15:22:36', '2026-03-06 15:22:36', NULL),
(13, 7, 'Qaymoq Saryog\'i', 'Saryog\'', 1, 4, '2026-03-06 15:22:57', '2026-03-06 15:22:57', NULL),
(14, 8, 'Saqich', 'Kango element', 1, 1, '2026-03-06 15:24:00', '2026-03-06 15:24:00', NULL),
(15, 8, 'Qurtlar', 'barchasi', 1, 2, '2026-03-06 15:24:22', '2026-03-06 15:24:22', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `tolovlar`
--

CREATE TABLE `tolovlar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 18, 1, NULL, 'NAQD', 0.00, 'Boshlang\'ich to\'lov', 1, '2026-03-04 15:47:09'),
(2, 19, 1, NULL, 'ARALASH', 900.00, 'Boshlang\'ich to\'lov', 1, '2026-03-06 08:29:19'),
(3, 21, 1, NULL, 'NAQD', 10000.00, 'Boshlang\'ich to\'lov', 1, '2026-03-06 09:32:30'),
(4, 19, 1, NULL, 'NAQD', 7000.00, '', 1, '2026-03-06 12:32:54'),
(5, 18, 1, NULL, 'NAQD', 3000.00, '', 1, '2026-03-06 12:33:26'),
(6, 25, 1, NULL, 'NAQD', 0.00, 'Boshlang\'ich to\'lov', 1, '2026-03-06 12:40:29'),
(7, 25, 1, NULL, 'NAQD', 997.00, '', 1, '2026-03-06 12:40:50'),
(8, 26, 1, NULL, 'NAQD', 0.00, 'Boshlang\'ich to\'lov', 1, '2026-03-06 20:30:54'),
(9, 26, 1, NULL, 'NAQD', 10000.00, '', 1, '2026-03-06 21:10:00'),
(10, 25, 1, NULL, 'NAQD', 5000.00, '', 1, '2026-03-06 21:10:22'),
(11, 27, 2, NULL, 'ARALASH', 0.00, 'Boshlang\'ich to\'lov', 1, '2026-03-06 21:13:04');

-- --------------------------------------------------------

--
-- Структура таблицы `yetkazib_beruvchilar`
--

CREATE TABLE `yetkazib_beruvchilar` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
(1, 'Test Yetkazib', '998901112233', 'Toshkent', NULL, NULL, 0.00, NULL, NULL, 1, '2026-02-27 05:51:55', '2026-02-27 05:51:55', NULL, NULL, NULL, NULL, 0.00, 0.00),
(2, 'Hydrolife', '+(998)73 545-55-55', 'Qurbonqashqar MFY Yangikent qishlog\'i Mustaqilllik 97-uy', 'w', 'Payshanba', 0.00, NULL, NULL, 1, '2026-03-06 08:19:30', '2026-03-06 08:19:30', NULL, NULL, NULL, NULL, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Структура таблицы `yetkazib_beruvchi_tolovlari`
--

CREATE TABLE `yetkazib_beruvchi_tolovlari` (
  `id` bigint(20) UNSIGNED NOT NULL,
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_foydalanuvchilar_login` (`login`),
  ADD UNIQUE KEY `uq_foydalanuvchilar_email` (`email`),
  ADD KEY `idx_foydalanuvchilar_rol` (`rol_id`);

--
-- Индексы таблицы `kassa_harakatlari`
--
ALTER TABLE `kassa_harakatlari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kassa_harakatlari_smena` (`kassa_smena_id`),
  ADD KEY `idx_kassa_harakatlari_kiritgan` (`kiritgan_id`),
  ADD KEY `idx_kassa_harakatlari_vaqt` (`yaratilgan_vaqt`);

--
-- Индексы таблицы `kassa_smenalari`
--
ALTER TABLE `kassa_smenalari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_smena_kassir` (`kassir_id`),
  ADD KEY `idx_smena_holat` (`holat`),
  ADD KEY `idx_smena_ochilgan` (`ochilgan_vaqt`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kirimlar_yetkazib` (`yetkazib_beruvchi_id`),
  ADD KEY `idx_kirimlar_kiritgan` (`kiritgan_id`),
  ADD KEY `idx_kirimlar_vaqt` (`kirim_vaqt`);

--
-- Индексы таблицы `kirim_tarkibi`
--
ALTER TABLE `kirim_tarkibi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kirim_tarkibi_kirim` (`kirim_id`),
  ADD KEY `idx_kirim_tarkibi_mahsulot` (`mahsulot_id`);

--
-- Индексы таблицы `mahsulotlar`
--
ALTER TABLE `mahsulotlar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_mahsulotlar_shtrix` (`shtrix_kod`),
  ADD KEY `idx_mahsulotlar_kategoriya` (`kategoriya_id`),
  ADD KEY `idx_mahsulotlar_subkategoriya` (`subkategoriya_id`),
  ADD KEY `idx_mahsulotlar_lowstock` (`miqdor`,`minimal_miqdor`);

--
-- Индексы таблицы `mijozlar`
--
ALTER TABLE `mijozlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mijozlar_telefon` (`telefon`),
  ADD KEY `idx_mijozlar_faol` (`faol`);

--
-- Индексы таблицы `nasiya_tolovlar`
--
ALTER TABLE `nasiya_tolovlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mijoz` (`mijoz_id`),
  ADD KEY `idx_savdo` (`savdo_id`),
  ADD KEY `idx_tolov_vaqt` (`tolov_vaqt`);

--
-- Индексы таблицы `ombor_jurnali`
--
ALTER TABLE `ombor_jurnali`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ombor_mahsulot` (`mahsulot_id`),
  ADD KEY `idx_ombor_vaqt` (`yaratilgan_vaqt`),
  ADD KEY `idx_ombor_manba` (`manba_turi`,`manba_id`),
  ADD KEY `idx_ombor_foydalanuvchi` (`foydalanuvchi_id`);

--
-- Индексы таблицы `rollar`
--
ALTER TABLE `rollar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_rollar_nomi` (`nomi`);

--
-- Индексы таблицы `savdolar`
--
ALTER TABLE `savdolar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_savdolar_chek` (`chek_raqami`),
  ADD KEY `idx_savdolar_kassir` (`kassir_id`),
  ADD KEY `idx_savdolar_mijoz` (`mijoz_id`),
  ADD KEY `idx_savdolar_smena` (`kassa_smena_id`),
  ADD KEY `idx_savdolar_vaqt` (`sotilgan_vaqt`),
  ADD KEY `idx_savdolar_tolov_holati` (`tolov_holati`);

--
-- Индексы таблицы `savdo_slotlari`
--
ALTER TABLE `savdo_slotlari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_savdo_slotlari_kassir` (`kassir_id`),
  ADD KEY `idx_savdo_slotlari_mijoz` (`mijoz_id`),
  ADD KEY `idx_savdo_slotlari_holat` (`holat`);

--
-- Индексы таблицы `savdo_slot_items`
--
ALTER TABLE `savdo_slot_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_slot_items_slot` (`slot_id`),
  ADD KEY `idx_slot_items_mahsulot` (`mahsulot_id`);

--
-- Индексы таблицы `savdo_tarkibi`
--
ALTER TABLE `savdo_tarkibi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_savdo_tarkibi_savdo` (`savdo_id`),
  ADD KEY `idx_savdo_tarkibi_mahsulot` (`mahsulot_id`);

--
-- Индексы таблицы `sozlamalar`
--
ALTER TABLE `sozlamalar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_sozlamalar_kalit` (`kalit_soz`);

--
-- Индексы таблицы `subkategoriyalar`
--
ALTER TABLE `subkategoriyalar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_subkat_kat_nomi` (`kategoriya_id`,`nomi`),
  ADD KEY `idx_subkat_kategoriya` (`kategoriya_id`);

--
-- Индексы таблицы `tolovlar`
--
ALTER TABLE `tolovlar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tolovlar_savdo` (`savdo_id`),
  ADD KEY `idx_tolovlar_mijoz` (`mijoz_id`),
  ADD KEY `idx_tolovlar_smena` (`kassa_smena_id`),
  ADD KEY `idx_tolovlar_vaqt` (`tolangan_vaqt`),
  ADD KEY `fk_tolovlar_qabul_qilgan` (`qabul_qilgan_id`);

--
-- Индексы таблицы `yetkazib_beruvchilar`
--
ALTER TABLE `yetkazib_beruvchilar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_yetkazib_telefon` (`telefon`),
  ADD KEY `idx_yetkazib_faol` (`faol`);

--
-- Индексы таблицы `yetkazib_beruvchi_tolovlari`
--
ALTER TABLE `yetkazib_beruvchi_tolovlari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_yetkazib_tolov_yetkazib` (`yetkazib_beruvchi_id`),
  ADD KEY `idx_yetkazib_tolov_qabul` (`qabul_qilgan_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `foydalanuvchilar`
--
ALTER TABLE `foydalanuvchilar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `kassa_harakatlari`
--
ALTER TABLE `kassa_harakatlari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `kassa_smenalari`
--
ALTER TABLE `kassa_smenalari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `kategoriyalar`
--
ALTER TABLE `kategoriyalar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `kirimlar`
--
ALTER TABLE `kirimlar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `kirim_tarkibi`
--
ALTER TABLE `kirim_tarkibi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `mahsulotlar`
--
ALTER TABLE `mahsulotlar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `mijozlar`
--
ALTER TABLE `mijozlar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `nasiya_tolovlar`
--
ALTER TABLE `nasiya_tolovlar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ombor_jurnali`
--
ALTER TABLE `ombor_jurnali`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `rollar`
--
ALTER TABLE `rollar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `savdolar`
--
ALTER TABLE `savdolar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `savdo_slotlari`
--
ALTER TABLE `savdo_slotlari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `savdo_slot_items`
--
ALTER TABLE `savdo_slot_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `savdo_tarkibi`
--
ALTER TABLE `savdo_tarkibi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `sozlamalar`
--
ALTER TABLE `sozlamalar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `subkategoriyalar`
--
ALTER TABLE `subkategoriyalar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `tolovlar`
--
ALTER TABLE `tolovlar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `yetkazib_beruvchilar`
--
ALTER TABLE `yetkazib_beruvchilar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `yetkazib_beruvchi_tolovlari`
--
ALTER TABLE `yetkazib_beruvchi_tolovlari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `foydalanuvchilar`
--
ALTER TABLE `foydalanuvchilar`
  ADD CONSTRAINT `fk_foydalanuvchilar_rol` FOREIGN KEY (`rol_id`) REFERENCES `rollar` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `kassa_harakatlari`
--
ALTER TABLE `kassa_harakatlari`
  ADD CONSTRAINT `fk_kassa_harakatlari_kiritgan` FOREIGN KEY (`kiritgan_id`) REFERENCES `foydalanuvchilar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kassa_harakatlari_smena` FOREIGN KEY (`kassa_smena_id`) REFERENCES `kassa_smenalari` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `kassa_smenalari`
--
ALTER TABLE `kassa_smenalari`
  ADD CONSTRAINT `fk_smena_kassir` FOREIGN KEY (`kassir_id`) REFERENCES `foydalanuvchilar` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `kirimlar`
--
ALTER TABLE `kirimlar`
  ADD CONSTRAINT `fk_kirimlar_kiritgan` FOREIGN KEY (`kiritgan_id`) REFERENCES `foydalanuvchilar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kirimlar_yetkazib` FOREIGN KEY (`yetkazib_beruvchi_id`) REFERENCES `yetkazib_beruvchilar` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `kirim_tarkibi`
--
ALTER TABLE `kirim_tarkibi`
  ADD CONSTRAINT `fk_kirim_tarkibi_kirim` FOREIGN KEY (`kirim_id`) REFERENCES `kirimlar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_kirim_tarkibi_mahsulot` FOREIGN KEY (`mahsulot_id`) REFERENCES `mahsulotlar` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `mahsulotlar`
--
ALTER TABLE `mahsulotlar`
  ADD CONSTRAINT `fk_mahsulotlar_kategoriya` FOREIGN KEY (`kategoriya_id`) REFERENCES `kategoriyalar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mahsulotlar_subkategoriya` FOREIGN KEY (`subkategoriya_id`) REFERENCES `subkategoriyalar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `ombor_jurnali`
--
ALTER TABLE `ombor_jurnali`
  ADD CONSTRAINT `fk_ombor_foydalanuvchi` FOREIGN KEY (`foydalanuvchi_id`) REFERENCES `foydalanuvchilar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ombor_mahsulot` FOREIGN KEY (`mahsulot_id`) REFERENCES `mahsulotlar` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `savdolar`
--
ALTER TABLE `savdolar`
  ADD CONSTRAINT `fk_savdolar_kassir` FOREIGN KEY (`kassir_id`) REFERENCES `foydalanuvchilar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_savdolar_mijoz` FOREIGN KEY (`mijoz_id`) REFERENCES `mijozlar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_savdolar_smena` FOREIGN KEY (`kassa_smena_id`) REFERENCES `kassa_smenalari` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `savdo_slotlari`
--
ALTER TABLE `savdo_slotlari`
  ADD CONSTRAINT `fk_savdo_slotlari_kassir` FOREIGN KEY (`kassir_id`) REFERENCES `foydalanuvchilar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_savdo_slotlari_mijoz` FOREIGN KEY (`mijoz_id`) REFERENCES `mijozlar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `savdo_slot_items`
--
ALTER TABLE `savdo_slot_items`
  ADD CONSTRAINT `fk_slot_items_mahsulot` FOREIGN KEY (`mahsulot_id`) REFERENCES `mahsulotlar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_slot_items_slot` FOREIGN KEY (`slot_id`) REFERENCES `savdo_slotlari` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `savdo_tarkibi`
--
ALTER TABLE `savdo_tarkibi`
  ADD CONSTRAINT `fk_savdo_tarkibi_mahsulot` FOREIGN KEY (`mahsulot_id`) REFERENCES `mahsulotlar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_savdo_tarkibi_savdo` FOREIGN KEY (`savdo_id`) REFERENCES `savdolar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `subkategoriyalar`
--
ALTER TABLE `subkategoriyalar`
  ADD CONSTRAINT `fk_subkategoriyalar_kategoriya` FOREIGN KEY (`kategoriya_id`) REFERENCES `kategoriyalar` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tolovlar`
--
ALTER TABLE `tolovlar`
  ADD CONSTRAINT `fk_tolovlar_mijoz` FOREIGN KEY (`mijoz_id`) REFERENCES `mijozlar` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tolovlar_qabul_qilgan` FOREIGN KEY (`qabul_qilgan_id`) REFERENCES `foydalanuvchilar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tolovlar_savdo` FOREIGN KEY (`savdo_id`) REFERENCES `savdolar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tolovlar_smena` FOREIGN KEY (`kassa_smena_id`) REFERENCES `kassa_smenalari` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `yetkazib_beruvchi_tolovlari`
--
ALTER TABLE `yetkazib_beruvchi_tolovlari`
  ADD CONSTRAINT `fk_yetkazib_tolov_qabul` FOREIGN KEY (`qabul_qilgan_id`) REFERENCES `foydalanuvchilar` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_yetkazib_tolov_yetkazib` FOREIGN KEY (`yetkazib_beruvchi_id`) REFERENCES `yetkazib_beruvchilar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
