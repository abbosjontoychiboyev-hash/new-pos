-- Migration to fix decimal quantities for kilogram products and other issues
-- Run this SQL to update database schema

-- Change quantity fields from INT to DECIMAL(10,3) for proper kilogram support
ALTER TABLE mahsulotlar MODIFY COLUMN miqdor DECIMAL(10,3) NOT NULL DEFAULT 0;
ALTER TABLE mahsulotlar MODIFY COLUMN minimal_miqdor DECIMAL(10,3) NOT NULL DEFAULT 0;

ALTER TABLE ombor_jurnali MODIFY COLUMN miqdor_ozgarish DECIMAL(10,3) NOT NULL;
ALTER TABLE ombor_jurnali MODIFY COLUMN eski_miqdor DECIMAL(10,3) NOT NULL;
ALTER TABLE ombor_jurnali MODIFY COLUMN yangi_miqdor DECIMAL(10,3) NOT NULL;

ALTER TABLE savdo_slot_items MODIFY COLUMN soni DECIMAL(10,3) NOT NULL;
ALTER TABLE savdo_tarkibi MODIFY COLUMN soni DECIMAL(10,3) NOT NULL;

ALTER TABLE kirim_tarkibi MODIFY COLUMN soni DECIMAL(10,3) NOT NULL;
ALTER TABLE qaytarishlar MODIFY COLUMN miqdor DECIMAL(10,3) NOT NULL;

-- Ensure price fields are DECIMAL(12,2) for money
ALTER TABLE mahsulotlar MODIFY COLUMN kelish_narxi DECIMAL(12,2) NOT NULL DEFAULT 0;
ALTER TABLE mahsulotlar MODIFY COLUMN sotish_narxi DECIMAL(12,2) NOT NULL DEFAULT 0;

-- Add indexes for better performance on reports
CREATE INDEX idx_savdolar_sana ON savdolar(sana);
CREATE INDEX idx_savdolar_kassir_id ON savdolar(kassir_id);
CREATE INDEX idx_qaytarishlar_qaytarilgan_vaqt ON qaytarishlar(qaytarilgan_vaqt);
CREATE INDEX idx_yetkazib_beruvchi_tolovlari_sana ON yetkazib_beruvchi_tolovlari(sana);

-- Add columns for better shift tracking if needed
-- (Tables already have necessary columns)