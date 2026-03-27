-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Мар 26 2026 г., 08:29
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
(3, 1, 'Abbosjon To\'ychiboyev', 'admin@example.com', '+998930008827', 'admin', '$2y$10$cwwtGPH3nRv/1Gg3p8R9I.Q.VWGxqRBoIFr1VDZfnYSSQMWfQjX5S', 1, '2026-03-26 04:58:37', '2026-03-10 05:08:31', '2026-03-26 04:58:37', NULL),
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
(1, 3, '2026-03-24 16:06:14', '2026-03-25 09:20:52', 250000.00, 25057.00, NULL, 'YOPIQ', NULL, '2026-03-24 11:06:14', '2026-03-25 04:20:52'),
(2, 3, '2026-03-25 09:52:40', '2026-03-26 10:24:01', 150.00, 25000.00, NULL, 'YOPIQ', NULL, '2026-03-25 04:52:40', '2026-03-26 05:24:01'),
(3, 3, '2026-03-26 10:25:03', NULL, 286000.00, NULL, NULL, 'OCHIQ', NULL, '2026-03-26 05:25:03', '2026-03-26 05:25:03');

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
(1, 'Sut mahsulotlari', 'Sut, qatiq, pishloq va boshqalar', 1, 1, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(2, 'Non mahsulotlari', 'Non va pishiriqlar', 1, 2, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(3, 'Go\'sht mahsulotlari', 'Go\'sht va yarim tayyor mahsulotlar', 1, 3, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(4, 'Sabzavot va mevalar', 'Yangi va quritilgan mahsulotlar', 1, 4, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(5, 'Baqqollik (Bakaleya)', 'Guruch, yog, un va boshqalar', 1, 5, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(6, 'Ichimliklar', 'Suvlar, sharbatlar va choylar', 1, 6, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(7, 'Shirinliklar', 'Konfet, shokolad va tortlar', 1, 7, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(8, 'Konservalar va souslar', 'Tuzlamalar, ketchup va mayonez', 1, 8, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL),
(9, 'Maishiy kimyo', 'Yuvish vositalari va gigiyena', 1, 9, '2026-03-24 10:55:46', '2026-03-24 10:55:46', NULL);

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
  `miqdor` float NOT NULL DEFAULT 0,
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
(31, 6, 1, 'Coca-Cola 1.5L', '5449000000996', 'dona', 11500.00, 13500.00, 47, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:17:14', NULL),
(32, 6, 1, 'Coca-Cola 0.5L', '5449000001009', 'dona', 5200.00, 6500.00, 99, 20, 1, '2026-03-24 11:02:15', '2026-03-24 11:21:55', NULL),
(33, 6, NULL, 'Fanta 1.5L', '5449000131805', 'dona', 11500.00, 13999.00, 39, 10, 1, '2026-03-24 11:02:15', '2026-03-26 05:23:38', NULL),
(34, 6, 1, 'Sprite 1.5L', '5449000131836', 'dona', 11500.00, 13500.00, 26, 10, 1, '2026-03-24 11:02:15', '2026-03-26 05:23:38', NULL),
(35, 6, 1, 'Pepsi 1.5L', '4823063100204', 'dona', 11000.00, 13000.00, 60, 15, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(36, 6, 1, 'Hydrolife 0.5L Gazsiz', '4780005080031', 'dona', 1800.00, 2500.00, 199, 50, 1, '2026-03-24 11:02:15', '2026-03-26 05:11:26', NULL),
(37, 6, 1, 'Hydrolife 1.5L Gazsiz', '4780005080048', 'dona', 3500.00, 4500.00, 148, 30, 1, '2026-03-24 11:02:15', '2026-03-26 05:23:38', NULL),
(38, 6, 1, 'Chortoq 0.5L Gazli', '4780045030430', 'dona', 5200.00, 6500.00, 47, 12, 1, '2026-03-24 11:02:15', '2026-03-26 05:23:38', NULL),
(39, 6, 1, 'Flash Up 0.45L j/b', '4601351010373', 'dona', 6200.00, 7500.00, 72, 24, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(40, 6, 1, 'Red Bull 250ml', '90162909', 'dona', 15500.00, 18500.00, 23, 6, 1, '2026-03-24 11:02:15', '2026-03-26 05:11:26', NULL),
(41, 6, 2, 'Dinay Olma 1L', '4780060510122', 'dona', 10500.00, 12500.00, 30, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(42, 6, 2, 'Bliss Olcha 1L', '4780021030119', 'dona', 11000.00, 13000.00, 25, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(43, 7, 1, 'Snickers 50g', '5000159461122', 'dona', 6200.00, 7500.00, 100, 20, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(44, 7, 1, 'Twix 50g', '5000159392099', 'dona', 6200.00, 7500.00, 79, 15, 1, '2026-03-24 11:02:15', '2026-03-25 11:03:52', NULL),
(45, 7, 1, 'Bounty 55g', '5000159404396', 'dona', 6200.00, 7500.00, 60, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(46, 7, 1, 'Mars 51g', '5000159418546', 'dona', 6200.00, 7500.00, 50, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(47, 7, 1, 'Alpen Gold 85g', '7622210440624', 'dona', 11500.00, 14500.00, 40, 8, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(48, 7, 1, 'Milka 100g', '7622300313234', 'dona', 15000.00, 18500.00, 35, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(49, 7, 1, 'Kinder Surprise', '4008400403522', 'dona', 13500.00, 16500.00, 24, 6, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(50, 7, 1, 'Kinder Chocolate 50g', '4008400401825', 'dona', 10000.00, 12500.00, 30, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(51, 7, 4, 'Orbit Yalpizli', '4003994151101', 'dona', 3600.00, 4500.00, 150, 30, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(52, 7, 4, 'Orbit Qulupnay', '4003994154102', 'dona', 3600.00, 4500.00, 100, 20, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(53, 7, 4, 'Dirol Yalpizli', '7622210609380', 'dona', 3200.00, 4000.00, 120, 25, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(54, 7, 2, 'Lays Klassik 80g', '8690522437345', 'dona', 12500.00, 15500.00, 40, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(55, 7, 2, 'Lays Pishloq 80g', '8690522437352', 'dona', 12500.00, 15500.00, 40, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(56, 7, 2, 'Biskrem 100g', '8690522104117', 'dona', 6500.00, 8000.00, 50, 15, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(57, 5, 3, 'Oleina Yog\' 1L', '4600614001159', 'dona', 16500.00, 18500.00, 100, 20, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(58, 5, 3, 'Zolotaya Semechka 1L', '4607005400021', 'dona', 15500.00, 17500.00, 80, 15, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(59, 5, 2, 'Makfa Makaron 400g', '4600693000043', 'dona', 10000.00, 12500.00, 60, 12, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(60, 5, 2, 'Granum Makaron 400g', '4780000400025', 'dona', 8200.00, 10000.00, 100, 20, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(61, 5, 4, 'Shakar (Qadoqlangan 1kg)', '4780004940022', 'dona', 12000.00, 13500.00, 200, 50, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(62, 1, 1, 'Musaffo Sut 2.5% 1L', '4780047320010', 'dona', 12800.00, 15000.00, 40, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(63, 1, 1, 'Musaffo Sut 3.2% 1L', '4780047320027', 'dona', 13500.00, 16000.00, 30, 8, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(64, 1, 4, 'Musaffo Qaymoq 20%', '4780047320096', 'dona', 10500.00, 12500.00, 25, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(65, 1, 2, 'Pishloq Viola 140g', '4601445012542', 'dona', 18000.00, 22000.00, 20, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(66, 9, 2, 'Ariel Avtomat 1.5kg', '8690506394541', 'dona', 48000.00, 55000.00, 20, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(67, 9, 2, 'Tide Avtomat 1.5kg', '8690506394503', 'dona', 42000.00, 48000.00, 15, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(68, 9, 1, 'Fairy Limon 450ml', '8690506482163', 'dona', 15500.00, 18500.00, 30, 8, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(69, 9, 4, 'Colgate 100ml', '5000174823134', 'dona', 12500.00, 15500.00, 40, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(70, 9, 4, 'Safeguard Sovun 90g', '8690506473185', 'dona', 7200.00, 9000.00, 100, 20, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(71, 9, 3, 'Salfetka Musaffo 100talik', '4780026210011', 'dona', 4200.00, 5500.00, 200, 40, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(72, 6, 3, 'Greenfield Tea 25 paket', '4605246004110', 'dona', 24000.00, 28500.00, 40, 8, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(73, 6, 3, 'Piala Choy 100g', '4780015010011', 'dona', 10500.00, 12500.00, 100, 20, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(74, 6, 3, 'Jacobs Monarch 95g', '7622300311742', 'dona', 38000.00, 46000.00, 20, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(75, 6, 3, 'Nescafe Classic 60g', '7622300112448', 'dona', 21000.00, 26000.00, 30, 5, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(76, 8, 3, 'Heinz Ketchup 350g', '4600104037566', 'dona', 15000.00, 18500.00, 40, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(77, 8, 3, 'Calve Mayonez 200ml', '4600104026362', 'dona', 9000.00, 11500.00, 50, 15, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(78, 8, 1, 'Bonduelle Gorox 400g', '4823061314986', 'dona', 18500.00, 22500.00, 48, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(79, 8, 1, 'Bonduelle Kukuruza 340g', '4823061315006', 'dona', 18500.00, 22500.00, 48, 10, 1, '2026-03-24 11:02:15', '2026-03-24 11:02:15', NULL),
(80, 7, 2, 'Pechenye \"Toplenoe moloko\" (Vaznli)', '2000001', 'kg', 18000.00, 24000.00, 15, 3, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(81, 7, 2, 'Vafli \"Artek\" (Vaznli)', '2000002', 'kg', 22000.00, 28000.00, 10, 2, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(82, 7, 2, 'Pechenye \"Zemlyachka\" (Yubileyniy)', '2000003', 'kg', 20000.00, 26000.00, 12, 3, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(83, 7, 1, 'Konfet \"Alpen Gold\" (Vaznli)', '2000004', 'kg', 55000.00, 68000.00, 10, 2, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(84, 7, 1, 'Konfet \"Mishka na severe\"', '2000005', 'kg', 65000.00, 82000.00, 8, 2, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(85, 7, 1, 'Konfet \"Karakum\"', '2000006', 'kg', 60000.00, 78000.00, 10, 2, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(86, 7, 1, 'Marmelad (Turli xil, vaznli)', '2000007', 'kg', 25000.00, 32000.00, 15, 5, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(87, 5, 1, 'Guruch \"Lazer\" (Vaznli)', '2000008', 'kg', 22000.00, 27000.00, 50, 10, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(88, 5, 1, 'Guruch \"Alanga\" (Vaznli)', '2000009', 'kg', 16000.00, 20000.00, 100, 20, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(89, 5, 4, 'Shakar (Vaznli)', '2000010', 'kg', 11500.00, 13000.00, 150, 25, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(90, 5, 4, 'Un 1-nav (Vaznli)', '2000011', 'kg', 6000.00, 7500.00, 200, 40, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(91, 5, 1, 'No\'xat (Vaznli)', '2000012', 'kg', 14000.00, 18000.00, 30, 5, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(92, 5, 1, 'Mosh (Vaznli)', '2000013', 'kg', 12000.00, 16000.00, 30, 5, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(93, 4, 1, 'Kartoshka (Mahalliy)', '2000014', 'kg', 4000.00, 5500.00, 300, 50, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(94, 4, 1, 'Piyoz', '2000015', 'kg', 2500.00, 3500.00, 200, 30, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(95, 4, 1, 'Sabzi (Sariq)', '2000016', 'kg', 3000.00, 4500.00, 150, 20, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(96, 4, 2, 'Olma \"Golden\"', '2000017', 'kg', 12000.00, 16000.00, 50, 10, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(97, 4, 2, 'Banan (Import)', '2000018', 'kg', 18000.00, 23000.00, 40, 5, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(98, 4, 3, 'Magiz (Qora)', '2000019', 'kg', 45000.00, 60000.00, 10, 2, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(99, 4, 3, 'Bodom pishiq (Sho\'r)', '2000020', 'kg', 90000.00, 120000.00, 5, 1, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(100, 4, 3, 'Yong\'oq mag\'zi', '2000021', 'kg', 70000.00, 95000.00, 8, 2, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(101, 4, 3, 'Pista (Sho\'r)', '2000022', 'kg', 110000.00, 140000.00, 5, 1, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(102, 3, 1, 'Mol go\'shti (Sujok)', '2000023', 'kg', 85000.00, 98000.00, 30, 5, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(103, 3, 2, 'Tovuq go\'shti (Butun)', '2000024', 'kg', 28000.00, 34000.00, 40, 8, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(104, 3, 3, 'Sosiska (Vaznli, Halol)', '2000025', 'kg', 35000.00, 45000.00, 15, 3, 1, '2026-03-24 11:03:32', '2026-03-24 11:03:32', NULL),
(105, 3, 4, 'Pelmen \"Uyga xos\" 450g', '4780004510027', 'dona', 18000.00, 23000.00, 20, 5, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(106, 3, 4, 'Kotlet \"Sifatli\" 400g', '4780004510034', 'dona', 22000.00, 28000.00, 15, 4, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(107, 1, 5, 'Muzqaymoq \"Bahor\" 80g', '4780012010113', 'dona', 3500.00, 5000.00, 50, 10, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(108, 1, 5, 'Muzqaymoq \"Panda\" 500g', '4780012010120', 'dona', 15000.00, 20000.00, 12, 3, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(109, 2, 1, 'Buxanka non', '2000026', 'dona', 2500.00, 3000.00, 100, 15, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(110, 2, 2, 'Yopgan non (Patir)', '2000027', 'dona', 4000.00, 5000.00, 50, 10, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(111, 2, 3, 'Keks (Vaznli)', '2000028', 'kg', 28000.00, 35000.00, 5, 1, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(112, 2, 3, 'Bulochka (Shokoladli)', '2000029', 'dona', 2500.00, 3500.00, 30, 5, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(113, 6, 5, 'Pall Mall (Silver)', '4031300123456', 'dona', 16000.00, 18000.00, 50, 10, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(114, 6, 5, 'Kent 4 (Silver)', '4031300654321', 'dona', 19000.00, 22000.00, 30, 5, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(115, 6, 5, 'Rothmans (Blue)', '4031300987654', 'dona', 15500.00, 17500.00, 40, 10, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(116, 7, 2, 'Pista \"Chaq-chaq\" 50g', '4780006010013', 'dona', 1500.00, 2500.00, 100, 20, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(117, 7, 2, 'Suxariki \"Kirieshki\" 40g', '4607024101114', 'dona', 2500.00, 3500.00, 60, 10, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(118, 7, 2, 'Chipslar \"Maretti\" 70g', '3800063836104', 'dona', 9000.00, 12000.00, 20, 5, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(119, 8, 3, 'Mayonez \"Sloboda\" 400ml', '4600614002132', 'dona', 14000.00, 17500.00, 30, 5, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(120, 8, 3, 'Gorchitsa 100g', '4600614002149', 'dona', 4000.00, 6000.00, 10, 2, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(121, 8, 3, 'Tuz (Qadoqlangan 1kg)', '4780001010018', 'dona', 2000.00, 3000.00, 100, 20, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(122, 8, 3, 'Murch (Paketchada)', '2000030', 'dona', 500.00, 1000.00, 200, 50, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(123, 1, 6, 'Pampers \"Sleep & Play\" 3', '4015400123011', 'dona', 85000.00, 105000.00, 10, 2, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(124, 1, 6, 'Bolalar bo\'tqasi \"Nestle\"', '7613035889755', 'dona', 32000.00, 40000.00, 15, 3, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(125, 9, 5, 'Gugurt (1 quticha)', '2000031', 'dona', 200.00, 500.00, 500, 50, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(126, 9, 5, 'Lampa (Led 9W)', '6970001234567', 'dona', 8000.00, 12000.00, 20, 5, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(127, 9, 5, 'Batareyka \"Duracell\" AA', '5000394123456', 'dona', 6000.00, 8000.00, 40, 8, 1, '2026-03-24 11:04:33', '2026-03-24 11:04:33', NULL),
(128, 5, 2, 'Rollton (Lapsha) tovuqli', '4607049361012', 'dona', 3500.00, 4500.00, 100, 20, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(129, 7, 2, 'Muesli (Nonushta uchun)', '4607025102028', 'dona', 22000.00, 28000.00, 10, 2, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(130, 6, 3, 'Nescafe 3v1 (Paketcha)', '7613035222019', 'dona', 1800.00, 2500.00, 200, 50, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(131, 9, 5, 'Zajigalka (Lighter)', '2000033', 'dona', 1500.00, 2500.00, 50, 10, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(132, 9, 5, 'Batareyka GP (Palchik)', '4891199000018', 'dona', 2500.00, 4000.00, 40, 10, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(133, 9, 5, 'Yelim (Kley 505)', '2000034', 'dona', 2000.00, 3500.00, 30, 5, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(134, 9, 3, 'Axlat paketi (Musorniy paket)', '2000035', 'dona', 6000.00, 9000.00, 20, 5, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(135, 8, 2, 'Shprot (Baliq konservasi)', '4750033101015', 'dona', 15000.00, 20000.00, 20, 5, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(136, 8, 2, 'Tushyonka (Mol go\'shti)', '4607025101014', 'dona', 22000.00, 28000.00, 15, 3, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(137, 8, 1, 'Zaytun (Oliva) mevalari', '8410199000105', 'dona', 16000.00, 22000.00, 10, 2, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(138, 9, 4, 'Pampers Premium Care 4', '4015400742131', 'dona', 120000.00, 145000.00, 5, 1, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(139, 1, 6, 'Nutrilak bolalar bo\'tqasi', '4600494625216', 'dona', 35000.00, 45000.00, 10, 2, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL),
(140, 9, 3, 'Bolalar nam salfetkasi (Wet wipes)', '8690530018000', 'dona', 8000.00, 12000.00, 25, 5, 1, '2026-03-24 11:06:02', '2026-03-24 11:06:02', NULL);

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
(1, 97, 'KIRIM', 0, 40, 40, 'QAYTARISH', NULL, 0, 'Qaytarish: Muddati o\'tgan\r\n', '2026-03-24 16:16:10'),
(2, 33, 'KIRIM', 1, 39, 40, 'QAYTARISH', NULL, 0, 'Qaytarish: sa', '2026-03-24 16:22:22');

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
(1, 16, 3, 97, 0, 8050.00, 'Muddati o\'tgan\r\n', 3, '2026-03-24 11:16:10'),
(2, 18, 6, 33, 1, 13999.00, 'sa', 3, '2026-03-24 11:22:22');

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
(16, 'CHK-20260324-0001-680', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 0.00, 50.00, -50.00, -50.00, 0.00, 'YAKUNLANGAN', '2026-03-24 16:15:19', '', '2026-03-24 11:15:19', '2026-03-24 11:16:10'),
(17, 'CHK-20260324-0002-714', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 27000.00, 0.00, 27000.00, 27000.00, 0.00, 'YAKUNLANGAN', '2026-03-24 16:17:14', '', '2026-03-24 11:17:14', '2026-03-24 11:17:14'),
(18, 'CHK-20260324-0003-350', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 0.00, 0.00, 0.00, 0.00, 0.00, 'YAKUNLANGAN', '2026-03-24 16:21:55', '', '2026-03-24 11:21:55', '2026-03-24 11:22:22'),
(19, 'CHK-20260325-0001-122', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 21000.00, 0.00, 21000.00, 21000.00, 0.00, 'YAKUNLANGAN', '2026-03-25 16:03:52', '', '2026-03-25 11:03:52', '2026-03-25 11:03:52'),
(20, 'CHK-20260326-0001-132', 3, NULL, NULL, 'KARTA', 'TOLANGAN', 25500.00, 500.00, 25000.00, 30000.00, 0.00, 'YAKUNLANGAN', '2026-03-26 10:11:25', '', '2026-03-26 05:11:25', '2026-03-26 05:11:25'),
(21, 'CHK-20260326-0002-254', 3, NULL, NULL, 'NAQD', 'TOLANGAN', 38499.00, 499.00, 38000.00, 40000.00, 0.00, 'YAKUNLANGAN', '2026-03-26 10:23:38', '', '2026-03-26 05:23:38', '2026-03-26 05:23:38');

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
(1, 3, 1, NULL, 'Mijoz 1', 0.00, 'aktiv', '2026-03-24 11:06:09', '2026-03-24 11:06:09');

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
(1, 16, 31, 1, 13500.00, 0.00, 13500.00, '2026-03-24 11:15:19'),
(2, 16, 34, 1, 13500.00, 0.00, 13500.00, '2026-03-24 11:15:19'),
(4, 17, 31, 2, 13500.00, 0.00, 27000.00, '2026-03-24 11:17:14'),
(5, 18, 32, 1, 6500.00, 0.00, 6500.00, '2026-03-24 11:21:55'),
(7, 18, 34, 1, 13500.00, 0.00, 13500.00, '2026-03-24 11:21:55'),
(8, 19, 44, 1, 7500.00, 0.00, 7500.00, '2026-03-25 11:03:52'),
(9, 19, 34, 1, 13500.00, 0.00, 13500.00, '2026-03-25 11:03:52'),
(10, 20, 37, 1, 4500.00, 0.00, 4500.00, '2026-03-26 05:11:26'),
(11, 20, 36, 1, 2500.00, 0.00, 2500.00, '2026-03-26 05:11:26'),
(12, 20, 40, 1, 18500.00, 0.00, 18500.00, '2026-03-26 05:11:26'),
(13, 21, 33, 1, 13999.00, 0.00, 13999.00, '2026-03-26 05:23:38'),
(14, 21, 34, 1, 13500.00, 0.00, 13500.00, '2026-03-26 05:23:38'),
(15, 21, 38, 1, 6500.00, 0.00, 6500.00, '2026-03-26 05:23:38'),
(16, 21, 37, 1, 4500.00, 0.00, 4500.00, '2026-03-26 05:23:38');

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
(1, 1, 'Sut va qatiq', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(2, 1, 'Pishloqlar', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(3, 1, 'Sariyog\' va margarin', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(4, 1, 'Tvorog va smetana', NULL, 1, 4, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(5, 2, 'Buxanka va qolipli nonlar', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(6, 2, 'Patir va shirmoy nonlar', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(7, 2, 'Bulochka va pishiriqlar', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(8, 3, 'Mol va qo\'y go\'shti', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(9, 3, 'Landa (Tovuq) go\'shti', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(10, 3, 'Kolbasa va sosiskalar', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(11, 3, 'Yarim tayyor (Pelmen, kotlet)', NULL, 1, 4, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(12, 4, 'Yangi sabzavotlar', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(13, 4, 'Yangi mevalar', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(14, 4, 'Quritilgan meva va pista-bodom', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(15, 5, 'Guruch va dukkaklilar', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(16, 5, 'Makaron mahsulotlari', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(17, 5, 'O\'simlik yog\'lari', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(18, 5, 'Un va shakar', NULL, 1, 4, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(19, 6, 'Gazlangan ichimliklar', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(20, 6, 'Tabiiy sharbatlar', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(21, 6, 'Choy va kofe', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(22, 6, 'Oddiy ichimlik suvi', NULL, 1, 4, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(23, 7, 'Konfet va shokoladlar', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(24, 7, 'Pechenye va vafli', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(25, 7, 'Tort va pirojniylar', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(26, 8, 'Konservalangan sabzavotlar', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(27, 8, 'Baliq konservalari', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(28, 8, 'Ketchup va mayonez', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(29, 9, 'Idish yuvish vositalari', NULL, 1, 1, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(30, 9, 'Kir yuvish kukunlari', NULL, 1, 2, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(31, 9, 'Salfetka va qog\'oz mahsulotlari', NULL, 1, 3, '2026-03-24 10:55:59', '2026-03-24 10:55:59', NULL),
(32, 6, 'Gazsiz ichimliklar', 'Gazlanmagan ichimliklar', 1, 10, '2026-03-24 10:57:08', '2026-03-24 10:57:08', NULL);

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
(1, 'Chimyon qatiqlar', '+(998)91 150-01-04', 'Eco City 68-uy', 'Har ikki kun', 'Monday', 0.00, NULL, NULL, 1, '2026-03-24 11:14:23', '2026-03-24 11:14:23', NULL, 2, '', NULL, 0.00, 0.00);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `kirimlar`
--
ALTER TABLE `kirimlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `kirim_tarkibi`
--
ALTER TABLE `kirim_tarkibi`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `mahsulotlar`
--
ALTER TABLE `mahsulotlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT для таблицы `mijozlar`
--
ALTER TABLE `mijozlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `nasiya_tolovlar`
--
ALTER TABLE `nasiya_tolovlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `ombor_jurnali`
--
ALTER TABLE `ombor_jurnali`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `qaytarishlar`
--
ALTER TABLE `qaytarishlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `rollar`
--
ALTER TABLE `rollar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `savdolar`
--
ALTER TABLE `savdolar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `savdo_slotlari`
--
ALTER TABLE `savdo_slotlari`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `savdo_slot_items`
--
ALTER TABLE `savdo_slot_items`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `savdo_tarkibi`
--
ALTER TABLE `savdo_tarkibi`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `sozlamalar`
--
ALTER TABLE `sozlamalar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `subkategoriyalar`
--
ALTER TABLE `subkategoriyalar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT для таблицы `tolovlar`
--
ALTER TABLE `tolovlar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `yetkazib_beruvchilar`
--
ALTER TABLE `yetkazib_beruvchilar`
  MODIFY `id` int(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
