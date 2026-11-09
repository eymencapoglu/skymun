<?php
require_once __DIR__ . '/../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_action'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === ADMIN_USER && $password === ADMIN_PASS) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: committees.php');
        exit;
    } else {
        $error = 'Hatalı kullanıcı adı veya şifre girdiniz.';
    }
}


if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: committees.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKYMUN | Yönetim Paneli Girişi</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michorama&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root {
            --color-primary: #33a3ff;
            --color-navy: #00072e;
            --color-purple: #1d0b79;
            --color-navy-dark: #00041a;
            --font-main: 'Outfit', sans-serif;
            --font-logo: 'Michorama', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--color-navy);
            background-image: linear-gradient(172deg, #00072e 0%, #1d0b79 65%, #00041a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(0, 7, 46, 0.85);
            border: 1px solid rgba(51, 163, 255, 0.22);
            border-radius: 20px;
            padding: 40px 32px;
            box-shadow: 0 15px 35px rgba(0, 4, 26, 0.7);
            backdrop-filter: blur(20px);
            text-align: center;
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            right: 20%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #33a3ff, transparent);
        }

        .login-logo {
            max-height: 70px;
            margin-bottom: 16px;
            filter: drop-shadow(0 0 12px rgba(51, 163, 255, 0.4));
        }

        .login-title {
            font-family: var(--font-logo);
            font-size: 1.4rem;
            letter-spacing: 2px;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 0.88rem;
            color: #94a3b8;
            margin-bottom: 28px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.88rem;
            margin-bottom: 20px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.84rem;
            color: #cbd5e1;
            margin-bottom: 7px;
            letter-spacing: 0.5px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(51, 163, 255, 0.6);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: rgba(29, 11, 121, 0.25);
            border: 1px solid rgba(51, 163, 255, 0.25);
            border-radius: 10px;
            color: #ffffff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 12px rgba(51, 163, 255, 0.35);
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-family: var(--font-logo);
            font-size: 0.95rem;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 20px rgba(51, 163, 255, 0.5);
        }

        @media (max-width: 480px) {
            body {
                padding: 16px;
            }

            .login-card {
                padding: 30px 20px;
                border-radius: 16px;
            }

            .login-title {
                font-size: 1.25rem;
            }

            .form-control {
                font-size: 16px !important;
                padding: 13px 14px 13px 42px;
            }

            .btn-submit {
                min-height: 48px;
                font-size: 0.95rem;
            }

            .back-link {
                padding: 8px 12px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                min-height: 44px;
            }
        }
    </style>
</head>
<body>

<div class="login-card">
    <img src="../logo-transparent.png" alt="SKYMUN" class="login-logo" onerror="this.style.display='none'">
    <h1 class="login-title">SKYMUN PANEL</h1>
    <p class="login-subtitle">Yönetim sistemine erişmek için giriş yapın</p>

    <?php if ($error): ?>
        <div class="alert-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="login_action" value="1">
        
        <div class="form-group">
            <label class="form-label">Kullanıcı Adı</label>
            <div class="input-wrap">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" class="form-control" placeholder="Kullanıcı adınız" required autofocus>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Şifre</label>
            <div class="input-wrap">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Şifreniz" required>
            </div>
        </div>

        <button type="submit" class="btn-submit">GİRİŞ YAP</button>
    </form>

    <a href="../index.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Siteye Geri Dön
    </a>
</div>

</body>
</html>
