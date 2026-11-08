<?php
require_once __DIR__ . '/../config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$executive = null;
if ($id > 0) {
    $executive = get_executive_by_id($id);
    if (!$executive) {
        header('Location: executives.php');
        exit;
    }
}

$error = '';
$success = '';

function handle_file_upload($fieldName, $prefix = 'file_') {
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES[$fieldName];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowed)) {
        return null;
    }

    $uniqueName = $prefix . uniqid() . '.' . $ext;
    if (!is_dir(UPLOADS_DIR)) {
        @mkdir(UPLOADS_DIR, 0755, true);
    }
    $destination = UPLOADS_DIR . $uniqueName;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return 'uploads/' . $uniqueName;
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_executive'])) {
    $name = trim($_POST['name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $status = isset($_POST['status']) ? 1 : 0;

    if (empty($name)) {
        $error = 'Lütfen üyenin adını ve soyadını giriniz.';
    } elseif (empty($title)) {
        $error = 'Lütfen üyenin görev/unvan bilgisini giriniz (Örn: Secretary-General).';
    } else {
        $image = $executive['image'] ?? '';
        $uploadedImage = handle_file_upload('image', 'exec_');
        if ($uploadedImage) {
            $image = $uploadedImage;
        }

        $data = [
            'name' => $name,
            'title' => $title,
            'image' => $image,
            'status' => $status,
            'sort_order' => $sort_order
        ];

        $savedId = save_executive($data, $id);
        if ($savedId) {
            header('Location: executives.php?msg=saved');
            exit;
        } else {
            global $last_sql_error, $db_error;
            $detail = !empty($last_sql_error) ? $last_sql_error : (!empty($db_error) ? $db_error : '');
            $error = 'Üye veritabanına kaydedilirken bir hata oluştu.' . ($detail ? '<br><small style="font-family:monospace;display:inline-block;margin-top:6px;background:rgba(0,0,0,0.35);padding:6px 10px;border-radius:6px;color:#ffffff;word-break:break-all;">' . htmlspecialchars($detail) . '</small>' : ' Lütfen veritabanı bağlantınızı ve cPanel ayarlarınızı kontrol ediniz.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id > 0 ? 'Üyeyi Düzenle' : 'Yeni Executive Üyesi Ekle' ?> | SKYMUN Admin</title>

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
            color: #f8fafc;
            padding-bottom: 70px;
        }

        .admin-navbar {
            background: rgba(0, 7, 46, 0.88);
            border-bottom: 1px solid rgba(51, 163, 255, 0.18);
            backdrop-filter: blur(16px);
            padding: 14px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff;
        }

        .admin-brand img {
            height: 38px;
            filter: drop-shadow(0 0 8px rgba(51, 163, 255, 0.5));
        }

        .admin-brand span {
            font-family: var(--font-logo);
            font-size: 1.15rem;
            letter-spacing: 1.5px;
        }

        .admin-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-back-nav {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.25s ease;
        }

        .btn-back-nav:hover {
            color: var(--color-primary);
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 40px auto 0;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-title {
            font-family: var(--font-logo);
            font-size: 1.5rem;
            letter-spacing: 1.5px;
            color: #ffffff;
        }

        .form-card {
            background: rgba(0, 7, 46, 0.75);
            border: 1px solid rgba(51, 163, 255, 0.18);
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 15px 35px rgba(0, 4, 26, 0.5);
            backdrop-filter: blur(16px);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 8px;
            letter-spacing: 0.4px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(29, 11, 121, 0.2);
            border: 1px solid rgba(51, 163, 255, 0.22);
            border-radius: 10px;
            color: #ffffff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 14px rgba(51, 163, 255, 0.35);
        }

        .form-hint {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 6px;
        }

        .current-preview {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 10px;
            padding: 10px 14px;
            background: rgba(0, 7, 46, 0.5);
            border-radius: 10px;
            border: 1px solid rgba(51, 163, 255, 0.12);
        }

        .current-thumb {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid rgba(51, 163, 255, 0.3);
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            margin-top: 8px;
            user-select: none;
        }

        .checkbox-wrap input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--color-primary);
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid rgba(51, 163, 255, 0.12);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #cbd5e1;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.92rem;
            transition: all 0.25s ease;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .btn-submit {
            background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
            border: none;
            color: #ffffff;
            padding: 12px 32px;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(51, 163, 255, 0.35);
            transition: all 0.3s ease;
        }

        @media (max-width: 868px) {
            body {
                padding-bottom: calc(50px + env(safe-area-inset-bottom, 16px));
            }
            .admin-navbar {
                padding: 12px 14px;
            }
            .admin-brand span {
                display: none;
            }
            .btn-back-nav {
                font-size: 0.84rem;
            }
            .container {
                width: 100%;
                padding: 0 12px;
                margin-top: 18px;
            }
            .page-title {
                font-size: 1.25rem;
                letter-spacing: 1px;
            }
            .form-card {
                padding: 18px 14px;
                border-radius: 14px;
            }
            .form-control, input[type="text"], input[type="number"], select {
                font-size: 16px !important;
            }
            .form-actions {
                flex-direction: column-reverse;
                gap: 12px;
                margin-top: 25px;
                padding-top: 20px;
            }
            .btn-secondary, .btn-submit {
                width: 100%;
                min-height: 48px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

<nav class="admin-navbar">
    <a href="executives.php" class="admin-brand">
        <img src="../logo-transparent.png" alt="SKYMUN" onerror="this.style.display='none'">
        <span>SKYMUN ADMIN</span>
    </a>

    <div class="admin-actions">
        <a href="executives.php" class="btn-back-nav">
            <i class="fa-solid fa-arrow-left"></i> Listeye Dön
        </a>
    </div>
</nav>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"><?= $id > 0 ? 'EXECUTIVE ÜYESİNİ DÜZENLE' : 'YENİ EXECUTIVE ÜYESİ EKLE' ?></h1>
    </div>

    <?php if (!$pdo): ?>
        <div style="background:rgba(239, 68, 68, 0.22);border:1px solid #ef4444;color:#fecaca;padding:16px 20px;border-radius:12px;margin-bottom:25px;line-height:1.6;">
            <div style="font-weight:700;font-size:1.05rem;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Veritabanına Bağlanılamadı!
            </div>
            <div>
                Veritabanı bağlantısı kurulamadığı için üye kaydedilemez.
                <?php if (!empty($db_error)): ?>
                    <br><strong style="color:#ffffff;">MySQL Hata Çıktısı:</strong> <code style="background:rgba(0,0,0,0.4);padding:3px 8px;border-radius:4px;color:#fca5a5;font-size:0.88rem;word-break:break-all;"><?= htmlspecialchars($db_error) ?></code>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background:rgba(239,68,68,0.2);border:1px solid #ef4444;color:#fca5a5;padding:14px 18px;border-radius:10px;margin-bottom:20px;line-height:1.5;">
            <i class="fa-solid fa-circle-exclamation"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="form-card">
        <input type="hidden" name="save_executive" value="1">

        <div class="form-group">
            <label class="form-label">Adı Soyadı <span style="color:#ef4444;">*</span></label>
            <input type="text" name="name" class="form-control" 
                   value="<?= htmlspecialchars($executive['name'] ?? '') ?>" 
                   placeholder="Örn: Ad Soyad" required>
        </div>

        <div class="form-group">
            <label class="form-label">Unvanı / Görevi <span style="color:#ef4444;">*</span></label>
            <input type="text" name="title" class="form-control" 
                   value="<?= htmlspecialchars($executive['title'] ?? '') ?>" 
                   placeholder="Örn: Secretary-General veya Director-General" required>
            <div class="form-hint">Kartın üzerinde isim altında zarif mavi renkle gösterilecek görev tanımı.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Sıralama Numarası</label>
            <input type="number" name="sort_order" class="form-control" 
                   value="<?= (int)($executive['sort_order'] ?? 0) ?>" 
                   style="max-width: 140px;">
            <div class="form-hint">Küçük numaraya sahip üyeler (1, 2, 3...) sitede en başta görüntülenir.</div>
        </div>

        <div class="form-group">
            <label class="form-label">Profil Fotoğrafı (Büyük Kart Görseli)</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($executive['image']) && file_exists(__DIR__ . '/../' . $executive['image'])): ?>
                <div class="current-preview">
                    <img src="../<?= htmlspecialchars($executive['image']) ?>" class="current-thumb" alt="Fotoğraf">
                    <span style="color:#cbd5e1;font-size:0.88rem;">Mevcut Fotoğraf Yüklü</span>
                </div>
            <?php endif; ?>
            <div class="form-hint">Önerilen oran dikey/portre veya 1:1 kare formatıdır (JPG, PNG, WebP).</div>
        </div>

        <div class="form-group">
            <label class="checkbox-wrap">
                <input type="checkbox" name="status" value="1" <?= (!isset($executive['status']) || $executive['status'] == 1) ? 'checked' : '' ?>>
                <span style="font-size: 0.95rem; color: #ffffff;">Bu üyeyi sitede aktif olarak yayınla</span>
            </label>
        </div>

        <div class="form-actions">
            <a href="executives.php" class="btn-secondary">İptal</a>
            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-floppy-disk"></i> <?= $id > 0 ? 'DEĞİŞİKLİKLERİ KAYDET' : 'ÜYEYİ EKLE' ?>
            </button>
        </div>
    </form>
</div>

</body>
</html>
