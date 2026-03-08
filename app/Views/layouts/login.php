<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish - POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            padding: 40px;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header h2 {
            color: #333;
            font-weight: 600;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            color: white;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h2>POS Magazin</h2>
            <p>Hisobingizga kiring</p>
        </div>
        
        <?php if ($flash = flash('error')): ?>
            <div class="alert alert-danger"><?= $flash ?></div>
        <?php endif; ?>
        
        <?php if ($flash = flash('success')): ?>
            <div class="alert alert-success"><?= $flash ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            
            <div class="mb-3">
                <label for="login" class="form-label">Login</label>
                <input type="text" 
                       class="form-control <?= isset($_SESSION['errors']['login']) ? 'is-invalid' : '' ?>" 
                       id="login" 
                       name="login" 
                       value="<?= old('login') ?>" 
                       required>
                <?php if (isset($_SESSION['errors']['login'])): ?>
                    <div class="invalid-feedback">
                        <?= implode(', ', $_SESSION['errors']['login']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Parol</label>
                <input type="password" 
                       class="form-control <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : '' ?>" 
                       id="password" 
                       name="password" 
                       required>
                <?php if (isset($_SESSION['errors']['password'])): ?>
                    <div class="invalid-feedback">
                        <?= implode(', ', $_SESSION['errors']['password']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn-login">Kirish</button>
        </form>
        
    
    </div>
    
    <?php 
    // Clear old input and errors
    unset($_SESSION['old']);
    unset($_SESSION['errors']);
    ?>
</body>
</html>