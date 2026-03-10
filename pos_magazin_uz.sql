-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 10 2026 г., 05:15
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
(1, 1, 'Administrator', 'abbosjontoychiboyev@gmail.com', '+(998)930008827', 'admin', '$2y$10$l6bfvCsUcicKyi9mPG7TXuZRtizEhsXmByDqRmyIv4kRhAW0tCVI6', 1, '2026-03-08 07:37:53', '2026-02-27 00:01:16', '2026-03-08 07:37:53', NULL),
(2, 2, 'To\'ychiboyev Najmiddin Shukurjon o\'g\'li', 'kassa@example.com', '+998200045578', 'kassa', '$2y$10$FYht3HsHa5hHtToSa3kUMuBcEtlH3CI9EfOkLZy1/mtmvSVbxUhee', 1, '2026-03-07 23:02:43', '2026-03-06 11:07:15', '2026-03-07 23:02:43', NULL);

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
(0, 'Suv  mahsulotlari', 'Barcha turdagi suvlar', 1, 1, '2026-03-08 06:28:15', '2026-03-08 06:28:15', NULL),
(0, 'Sabzavotlar', 'Barcha turdagi', 1, 2, '2026-03-08 06:28:30', '2026-03-08 06:28:30', NULL),
(0, 'Shikolandlar', 'Barcha turdagi', 1, 3, '2026-03-08 06:28:51', '2026-03-08 06:28:51', NULL),
(0, 'Sut mahsulotlari', 'Barcha turdagi', 1, 4, '2026-03-08 06:29:14', '2026-03-08 06:29:14', NULL),
(0, 'Chaq-CHuq mahsulotlar', 'Barcha turdagi', 1, 5, '2026-03-08 06:30:38', '2026-03-08 06:30:38', NULL),
(0, 'Go\'sht maxsulotlari', 'Barcha turdagi', 1, 6, '2026-03-08 06:31:01', '2026-03-08 06:31:01', NULL),
(0, 'konservalar', 'barcha turdagi', 1, 7, '2026-03-08 06:31:52', '2026-03-08 06:31:52', NULL),
(0, 'muzqaymoqlar ', 'barcha turdagi', 1, 8, '2026-03-08 06:32:29', '2026-03-08 06:32:29', NULL),
(0, 'Parfumeriya', 'barcha turdagi', 1, 9, '2026-03-08 06:34:05', '2026-03-08 06:34:05', NULL),
(0, 'kons tovar', 'barcha turdagi', 1, 10, '2026-03-08 06:34:39', '2026-03-08 06:34:39', NULL);

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
(1, 'Admin', 'Tizim administratori', '2026-02-27 00:01:16', '2026-02-27 00:01:16'),
(2, 'Kassir', 'Savdo (POS) foydalanuvchisi', '2026-02-27 00:01:16', '2026-02-27 00:01:16'),
(3, 'Omborchi', 'Kirim/ombor uchun foydalanuvchi', '2026-02-27 00:01:16', '2026-02-27 00:01:16');

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
(0, 4, 1, NULL, 'Mijoz 1', 0.00, 'aktiv', '2026-03-08 06:22:30', '2026-03-08 06:22:30');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
