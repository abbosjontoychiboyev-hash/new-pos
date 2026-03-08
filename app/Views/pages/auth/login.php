<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirish - POS Magazin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .login-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }
        
        .login-body {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-control:focus {
            border-color: #667eea;
            background: white;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
            background: #fff8f8;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102,126,234,0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #16a34a;
        }
        
        .demo-info {
            margin-top: 25px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        
        .demo-info small {
            color: #666;
            font-size: 13px;
        }
        
        .demo-info .badge {
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 5px;
            color: #495057;
            font-size: 12px;
            margin-top: 8px;
            display: inline-block;
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>POS Magazin</h1>
                <p>Savdo nuqtasi boshqaruv tizimi</p>
            </div>
            
            <div class="login-body">
                <?php if (isset($_SESSION['flash']['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= $_SESSION['flash']['error'] ?>
                    </div>
                    <?php unset($_SESSION['flash']['error']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash']['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= $_SESSION['flash']['success'] ?>
                    </div>
                    <?php unset($_SESSION['flash']['success']); ?>
                <?php endif; ?>
                
                <form method="POST" action="/new-pos/login">
                    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                    
                    <div class="form-group">
                        <label for="login">Login</label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" 
                                   class="form-control <?= isset($_SESSION['errors']['login']) ? 'is-invalid' : '' ?>" 
                                   id="login" 
                                   name="login" 
                                   value="<?= isset($_SESSION['old']['login']) ? htmlspecialchars($_SESSION['old']['login']) : '' ?>" 
                                   placeholder="Login kiriting"
                                   required>
                        </div>
                        <?php if (isset($_SESSION['errors']['login'])): ?>
                            <div class="invalid-feedback">
                                <?= implode(', ', $_SESSION['errors']['login']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Parol</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   class="form-control <?= isset($_SESSION['errors']['password']) ? 'is-invalid' : '' ?>" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Parol kiriting"
                                   required>
                        </div>
                        <?php if (isset($_SESSION['errors']['password'])): ?>
                            <div class="invalid-feedback">
                                <?= implode(', ', $_SESSION['errors']['password']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Kirish
                    </button>
                </form>
                
                <div class="demo-info">
                    <small>AliMuhammadxon market</small>
                    <div class="badge">
                        <i class="fas fa-user"></i> ADMIN 
                    </div>
                    <div class="badge" style="margin-left: 5px;">
                        <i class="fas fa-user"></i> Sotuvchi
                    </div>
                    <div class="badge" style="margin-left: 5px;">
                        <i class="fas fa-user"></i> omborchi
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    
    <?php 
    // Clear old data
    unset($_SESSION['old']);
    unset($_SESSION['errors']);
    ?>
</body>
</html>