-- database/seeds.sql
USE pos_magazin_uz;

-- Rollar
INSERT INTO rollar (nomi, izoh) VALUES
('Admin', 'Tizim administratori'),
('Kassir', 'Savdo (POS) foydalanuvchisi'),
('Omborchi', 'Kirim/ombor uchun foydalanuvchi');

-- Admin foydalanuvchi (parol: 123456)
INSERT INTO foydalanuvchilar (rol_id, fio, email, telefon, login, parol_hash, faol)
VALUES 
(1, 'Administrator', 'admin@pos.uz', '+998901234567', 'admin', '$2y$10$YourHashedPasswordHere', 1),
(2, 'Test Kassir', 'kassir@pos.uz', '+998901234568', 'kassir', '$2y$10$YourHashedPasswordHere', 1),
(3, 'Test Omborchi', 'omborchi@pos.uz', '+998901234569', 'omborchi', '$2y$10$YourHashedPasswordHere', 1);

-- Kategoriyalar
INSERT INTO kategoriyalar (nomi, izoh, tartib) VALUES
('Ichimliklar', 'Suv, sharbat, gazlangan ichimliklar', 1),
('Qandolat mahsulotlari', 'Shirinliklar, pechenye, tort', 2),
('Non mahsulotlari', 'Non, bulochka, lavash', 3),
('Sut mahsulotlari', 'Sut, qatiq, pishloq', 4),
('Go\'sht mahsulotlari', 'Mol, qo\'y, tovuq go\'shti', 5);

-- Subkategoriyalar
INSERT INTO subkategoriyalar (kategoriya_id, nomi, izoh) VALUES
(1, 'Gazlangan ichimliklar', 'Kola, fanta, sprite'),
(1, 'Sharbatlar', 'Meva sharbatlari'),
(1, 'Mineral suv', 'Gazli va gazsiz'),
(2, 'Pechenye', 'Turli xil pechenyeler'),
(2, 'Konfet', 'Shokolad va konfetlar'),
(3, 'Non', 'Oq non, qora non'),
(4, 'Sut', 'Sterilizatsiyalangan sut'),
(4, 'Qatiq', 'Uy qatiq');

-- Mahsulotlar
INSERT INTO mahsulotlar (kategoriya_id, subkategoriya_id, nomi, shtrix_kod, birlik, kelish_narxi, sotish_narxi, miqdor, minimal_miqdor) VALUES
(1, 1, 'Coca Cola 1L', '4780012345678', 'dona', 8000, 10000, 50, 10),
(1, 1, 'Fanta 1.5L', '4780012345679', 'dona', 10000, 13000, 30, 5),
(1, 3, 'Hydrox 0.5L', '4780012345680', 'dona', 1500, 2500, 100, 20),
(2, 4, 'Chocolate Chip Pechenye', '4780012345681', 'kg', 25000, 35000, 15, 3),
(2, 5, 'Milka Shokolad', '4780012345682', 'dona', 12000, 16000, 40, 8),
(3, 6, 'Oq Non', '4780012345683', 'dona', 3000, 4500, 25, 5),
(4, 7, 'Malika Suti 1L', '4780012345684', 'dona', 9000, 12000, 20, 4),
(4, 8, 'Qatiq 500ml', '4780012345685', 'dona', 6000, 8500, 15, 3);

-- Mijozlar
INSERT INTO mijozlar (fio, telefon, manzil) VALUES
('Ali Valiyev', '+998901112233', 'Toshkent sh. Chilonzor 5'),
('Bekzod Karimov', '+998902223344', 'Toshkent sh. Yunusobod 12'),
('Shoxrux Tursunov', '+998903334455', 'Toshkent vil. Chirchiq'),
('Jasur Aliyev', '+998904445566', 'Samarqand sh.'),
('Botir Umarov', '+998905556677', 'Buxoro sh.');

-- Yetkazib beruvchilar
INSERT INTO yetkazib_beruvchilar (nomi, telefon, manzil) VALUES
('Coca Cola Uzbekistan', '+998711234567', 'Toshkent, Olmazor tumani'),
('Nestle Uzbekistan', '+998712345678', 'Toshkent, Sergeli tumani'),
('Lactalis Uzbekistan', '+998713456789', 'Toshkent, Yashnobod tumani');

-- Sozlamalar
INSERT INTO sozlamalar (kalit_soz, qiymat) VALUES
('kompaniya_nomi', 'POS MAGAZIN MChJ'),
('kompaniya_manzili', 'Toshkent sh. Chilonzor tumani'),
('kompaniya_telefon', '+998781234567'),
('kompaniya_email', 'info@posmagazin.uz'),
('solish_stavkasi', '15'),
('valyuta', 'UZS');