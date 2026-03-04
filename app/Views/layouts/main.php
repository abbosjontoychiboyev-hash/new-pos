<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title><?= APP_NAME ?? 'POS System' ?></title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    
    <?php if (isset($extraCss)): ?>
        <?= $extraCss ?>
    <?php endif; ?>
</head>
<body>
    <?php if (is_logged_in()): ?>
        <?php require_once APP_PATH . '/Views/partials/navbar.php'; ?>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 p-0">
                    <?php require_once APP_PATH . '/Views/partials/sidebar.php'; ?>
                </div>
                <div class="col-md-10 p-4">
                    <?php require_once APP_PATH . '/Views/partials/alerts.php'; ?>
                    <?php require_once $viewPath; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php require_once $viewPath; ?>
    <?php endif; ?>
    
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/app.js') ?>"></script>
    
    <?php if (isset($extraJs)): ?>
        <?= $extraJs ?>
    <?php endif; ?>
</body>
</html>