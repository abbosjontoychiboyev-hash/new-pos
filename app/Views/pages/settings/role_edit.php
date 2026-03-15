<!-- Page Title -->
<?php $title = 'Rolni tahrirlash'; ?>

<div class="page-header">
    <h1>Rolni tahrirlash</h1>
    <div class="page-actions">
        <a href="/<?= BASE_PATH ?>/settings/roles" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Orqaga
        </a>
        <a href="/<?= BASE_PATH ?>/settings/roles/show/<?= $role['id'] ?>" class="btn btn-info">
            <i class="fas fa-eye"></i> Ko'rish
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Rol ma'lumotlari</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/<?= BASE_PATH ?>/settings/roles/update/<?= $role['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= generate_csrf() ?>">

                    <div class="mb-3">
                        <label for="nomi" class="form-label">Rol nomi <span class="text-danger">*</span></label>
                        <input type="text" name="nomi" id="nomi" class="form-control" required
                               value="<?= htmlspecialchars($role['nomi']) ?>">
                        <div class="form-text">Masalan: Admin, Kassir, Omborchi</div>
                    </div>

                    <div class="mb-3">
                        <label for="izoh" class="form-label">Izoh</label>
                        <textarea name="izoh" id="izoh" class="form-control" rows="3"
                                  placeholder="Rol haqida qisqacha izoh"><?= htmlspecialchars($role['izoh'] ?? '') ?></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Saqlash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>