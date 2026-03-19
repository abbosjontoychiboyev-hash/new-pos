-- Add actual_cash column to kassa_smenalari table for shortage/overage calculation
ALTER TABLE kassa_smenalari ADD COLUMN actual_cash DECIMAL(12,2) NULL DEFAULT NULL AFTER yopilish_naqd;

-- Add index for better performance on date queries
CREATE INDEX idx_kassa_smenalari_ochilgan_vaqt ON kassa_smenalari(ochilgan_vaqt);
CREATE INDEX idx_kassa_smenalari_yopilgan_vaqt ON kassa_smenalari(yopilgan_vaqt);

-- Add index for nasiya_tolovlar date queries
CREATE INDEX idx_nasiya_tolovlar_tolov_vaqt ON nasiya_tolovlar(tolov_vaqt);

-- Add index for yetkazib_beruvchi_tolovlari date queries (if not exists)
CREATE INDEX idx_yetkazib_beruvchi_tolovlari_sana ON yetkazib_beruvchi_tolovlari(sana);

-- Add index for qaytarishlar date queries (if not exists)
CREATE INDEX idx_qaytarishlar_qaytarilgan_vaqt ON qaytarishlar(qaytarilgan_vaqt);