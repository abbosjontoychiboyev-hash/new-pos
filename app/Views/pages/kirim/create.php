<!-- Page Title -->
<?php $title = 'Yangi kirim qo\'shish'; ?>

<style>
    .product-row {
        margin-bottom: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="fas fa-plus-circle"></i> Yangi kirim</h5>
        <a href="/new-pos/kirim" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['flash']['error'] ?></div>
            <?php unset($_SESSION['flash']['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/new-pos/kirim/store" id="kirimForm">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Yetkazib beruvchi <span class="text-danger">*</span></label>
                    <select name="yetkazib_beruvchi_id" class="form-select" required>
                        <option value="">-- Tanlang --</option>
                        <?php foreach ($yetkazibBeruvchilar as $y): ?>
                            <option value="<?= $y['id'] ?>"><?= htmlspecialchars($y['nomi']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Izoh</label>
                    <input type="text" name="izoh" class="form-control" placeholder="Masalan: 'Ombor uchun'">
                </div>
            </div>

            <h6 class="mt-4">Mahsulotlar</h6>
            <div id="products-container">
                <div class="product-row">
                    <div class="row">
                        <div class="col-md-5">
                            <select name="mahsulot_id[]" class="form-select" required>
                                <option value="">Mahsulot tanlang</option>
                                <?php foreach ($mahsulotlar as $m): ?>
                                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nomi']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="soni[]" class="form-control" placeholder="Soni" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="kelish_narxi[]" class="form-control" placeholder="Kelish narxi" onkeyup="this.value = this.value.replace(/[^0-9\.]/g, '')" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.product-row').remove()">
                                <i class="fas fa-trash"></i> O'chirish
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-success" onclick="addProductRow()">
                    <i class="fas fa-plus"></i> Yana mahsulot qo'shish
                </button>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-end">
                <a href="/new-pos/kirim" class="btn btn-secondary me-2">Bekor qilish</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Saqlash</button>
            </div>
        </form>
    </div>
</div>

<script>
function addProductRow() {
    const container = document.getElementById('products-container');
    const firstRow = container.querySelector('.product-row');
    const newRow = firstRow.cloneNode(true);
    // Inputlarni tozalash
    newRow.querySelectorAll('select, input').forEach(el => {
        if (el.tagName === 'SELECT') el.selectedIndex = 0;
        else el.value = '';
    });
    container.appendChild(newRow);
}
</script>