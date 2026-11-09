<?php
require_once __DIR__ . '/../config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}


$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$committee = null;
if ($id > 0) {
    $committee = get_committee_by_id($id);
    if (!$committee) {
        header('Location: committees.php');
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
    
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'doc', 'docx'];
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_committee'])) {
    $title = trim($_POST['title'] ?? '');
    $short_name = trim($_POST['short_name'] ?? '');
    $agenda = trim($_POST['agenda'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;

    $member1_name = trim($_POST['member1_name'] ?? '');
    $member1_title = trim($_POST['member1_title'] ?? '');
    $member2_name = trim($_POST['member2_name'] ?? '');
    $member2_title = trim($_POST['member2_title'] ?? '');
    $member3_name = trim($_POST['member3_name'] ?? '');
    $member3_title = trim($_POST['member3_title'] ?? '');

    if (empty($title)) {
        $error = 'Lütfen komite başlığını giriniz.';
    } else {
        $cover_image = $committee['cover_image'] ?? '';
        $uploadedCover = handle_file_upload('cover_image', 'cover_');
        if ($uploadedCover) $cover_image = $uploadedCover;

        $study_guide = $committee['study_guide'] ?? '';
        $uploadedGuide = handle_file_upload('study_guide', 'guide_');
        if ($uploadedGuide) $study_guide = $uploadedGuide;

        $member1_image = $committee['member1_image'] ?? '';
        $uploadedM1 = handle_file_upload('member1_image', 'member_');
        if ($uploadedM1) $member1_image = $uploadedM1;

        $member2_image = $committee['member2_image'] ?? '';
        $uploadedM2 = handle_file_upload('member2_image', 'member_');
        if ($uploadedM2) $member2_image = $uploadedM2;

        $member3_image = $committee['member3_image'] ?? '';
        $uploadedM3 = handle_file_upload('member3_image', 'member_');
        if ($uploadedM3) $member3_image = $uploadedM3;

        $data = [
            'title' => $title,
            'short_name' => $short_name,
            'agenda' => $agenda,
            'description' => $description,
            'cover_image' => $cover_image,
            'study_guide' => $study_guide,
            'member1_name' => $member1_name,
            'member1_title' => $member1_title,
            'member1_image' => $member1_image,
            'member2_name' => $member2_name,
            'member2_title' => $member2_title,
            'member2_image' => $member2_image,
            'member3_name' => $member3_name,
            'member3_title' => $member3_title,
            'member3_image' => $member3_image,
            'status' => $status
        ];

        $savedId = save_committee($data, $id);
        if ($savedId) {
            header('Location: committees.php?msg=saved');
            exit;
        } else {
            global $last_sql_error, $db_error;
            $detail = !empty($last_sql_error) ? $last_sql_error : (!empty($db_error) ? $db_error : '');
            $error = 'Komite veritabanına kaydedilirken bir hata oluştu.' . ($detail ? '<br><small style="font-family:monospace;display:inline-block;margin-top:6px;background:rgba(0,0,0,0.35);padding:6px 10px;border-radius:6px;color:#ffffff;word-break:break-all;">' . htmlspecialchars($detail) . '</small>' : ' Lütfen veritabanı bağlantınızı ve cPanel ayarlarınızı kontrol ediniz.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id > 0 ? 'Komiteyi Düzenle' : 'Yeni Komite Ekle' ?> | SKYMUN Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michorama&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        :root {
            --color-primary: #33a3ff;
            --color-navy: #00072e;
            --color-purple: #1d0b79;
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
            padding-bottom: 80px;
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

        .container {
            width: 90%;
            max-width: 1050px;
            margin: 35px auto 0;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-title {
            font-family: var(--font-logo);
            font-size: 1.6rem;
            color: #ffffff;
            letter-spacing: 1.5px;
        }

        .btn-back {
            color: #94a3b8;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.25s ease;
        }

        .btn-back:hover {
            color: #ffffff;
            background: rgba(51, 163, 255, 0.2);
            border-color: var(--color-primary);
        }

        .form-card {
            background: rgba(0, 7, 46, 0.75);
            border: 1px solid rgba(51, 163, 255, 0.2);
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 4, 26, 0.5);
            backdrop-filter: blur(16px);
        }

        .form-section-title {
            font-family: var(--font-logo);
            font-size: 1.15rem;
            color: var(--color-primary);
            letter-spacing: 1px;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(51, 163, 255, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 0.88rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 8px;
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
            box-shadow: 0 0 12px rgba(51, 163, 255, 0.35);
        }

        textarea.form-control {
            min-height: 120px;
            line-height: 1.6;
            resize: vertical;
        }

        .file-upload-box {
            background: rgba(29, 11, 121, 0.15);
            border: 1px dashed rgba(51, 163, 255, 0.35);
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            flex-wrap: wrap;
        }

        .file-upload-box input[type="file"] {
            color: #cbd5e1;
            font-size: 0.85rem;
        }

        .current-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.82rem;
            color: #38bdf8;
        }

        .current-thumb {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--color-primary);
        }

        .member-block {
            background: rgba(29, 11, 121, 0.18);
            border: 1px solid rgba(51, 163, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .member-header {
            font-size: 0.95rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .member-header i {
            color: var(--color-primary);
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            margin: 25px 0;
            user-select: none;
        }

        .checkbox-wrap input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--color-primary);
            cursor: pointer;
        }

        .btn-save {
            padding: 13px 34px;
            background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
            border: 1px solid rgba(51, 163, 255, 0.4);
            border-radius: 10px;
            color: #ffffff;
            font-family: var(--font-logo);
            font-size: 1rem;
            letter-spacing: 1.5px;
            cursor: pointer;
            box-shadow: 0 0 20px rgba(51, 163, 255, 0.4);
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(51, 163, 255, 0.65);
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
            .btn-back {
                padding: 7px 12px;
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
            .form-grid-2 {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .form-control, textarea.form-control, input[type="text"], input[type="number"], select {
                font-size: 16px !important;
            }
            .btn-save {
                width: 100%;
                min-height: 48px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 0.95rem;
            }
            .file-upload-box {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .member-block {
                padding: 14px;
            }
        }
    </style>
</head>
<body>

<nav class="admin-navbar">
    <a href="committees.php" class="admin-brand">
        <img src="../logo-transparent.png" alt="SKYMUN" onerror="this.style.display='none'">
        <span>SKYMUN ADMIN</span>
    </a>

    <a href="committees.php" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Listeye Dön
    </a>
</nav>

<div class="container">
    <div class="page-header">
        <h1 class="page-title"><?= $id > 0 ? 'KOMİTEYİ DÜZENLE' : 'YENİ KOMİTE OLUŞTUR' ?></h1>
    </div>

    <?php if (!$pdo): ?>
        <div style="background:rgba(239, 68, 68, 0.22);border:1px solid #ef4444;color:#fecaca;padding:16px 20px;border-radius:12px;margin-bottom:25px;line-height:1.6;">
            <div style="font-weight:700;font-size:1.05rem;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Veritabanına Bağlanılamadı!
            </div>
            <div>
                Veritabanı bağlantısı kurulamadığı için komite kaydedilemez.
                <?php if (!empty($db_error)): ?>
                    <br><strong style="color:#ffffff;">MySQL Hata Çıktısı:</strong> <code style="background:rgba(0,0,0,0.4);padding:3px 8px;border-radius:4px;color:#fca5a5;font-size:0.88rem;word-break:break-all;"><?= htmlspecialchars($db_error) ?></code>
                <?php endif; ?>
            </div>
            <div style="font-size:0.88rem;color:#cbd5e1;margin-top:12px;padding-top:12px;border-top:1px solid rgba(239,68,68,0.3);">
                <strong>cPanel'de Çözüm Adımları:</strong><br>
                1. <strong>cPanel &rarr; MySQL® Veritabanları</strong> sayfasına gidin.<br>
                2. <strong>"Mevcut Kullanıcılar"</strong> tablosundan oluşturduğunuz kullanıcının tam adına bakın (Örn: <code>cpaneluser_dbuser</code>).<br>
                3. <strong>"Veritabanına Kullanıcı Ekle"</strong> bölümünden bu kullanıcıyı <code>cpaneluser_skymun</code> veritabanına ekleyin ve açılan ekranda <strong>"TÜM İZİNLER" (ALL PRIVILEGES)</strong> kutucuğunu işaretleyip kaydedin.<br>
                4. Bilgileri <code>config.php</code> dosyasındaki <code>DB_USER</code> ve <code>DB_PASS</code> alanlarına yazın.
            </div>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background:rgba(239,68,68,0.2);border:1px solid #ef4444;color:#fca5a5;padding:14px 18px;border-radius:10px;margin-bottom:20px;line-height:1.5;">
            <i class="fa-solid fa-circle-exclamation"></i> <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data" class="form-card">
        <input type="hidden" name="save_committee" value="1">

        <div class="form-section-title">
            <i class="fa-solid fa-circle-info"></i> Temel Komite Bilgileri
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Komite Başlığı *</label>
                <input type="text" name="title" class="form-control" placeholder="Örn: North Atlantic Council (NAC)" 
                       value="<?= htmlspecialchars($committee['title'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Komite Kısaltması / Kodu</label>
                <input type="text" name="short_name" class="form-control" placeholder="Örn: NAC, UNSC, DISEC" 
                       value="<?= htmlspecialchars($committee['short_name'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Gündem Maddesi (Agenda Item)</label>
            <textarea name="agenda" class="form-control" style="min-height: 80px;" placeholder="Komitenin ele alacağı ana gündem konusu..."><?= htmlspecialchars($committee['agenda'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Komite Detay Açıklaması (About the Committee)</label>
            <textarea name="description" class="form-control" style="min-height: 180px;" placeholder="Komite işleyişi, tarihi, hedefleri ve detaylı açıklama metni..."><?= htmlspecialchars($committee['description'] ?? '') ?></textarea>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Kapak Görseli</label>
                <div class="file-upload-box">
                    <input type="file" name="cover_image" accept="image/*">
                    <?php if (!empty($committee['cover_image']) && file_exists(__DIR__ . '/../' . $committee['cover_image'])): ?>
                        <div class="current-preview">
                            <img src="../<?= htmlspecialchars($committee['cover_image']) ?>" class="current-thumb" alt="Kapak">
                            <span>Mevcut Görsel</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Study Guide (Çalışma Kılavuzu PDF)</label>
                <div class="file-upload-box">
                    <input type="file" name="study_guide" accept=".pdf,.doc,.docx">
                    <?php if (!empty($committee['study_guide']) && file_exists(__DIR__ . '/../' . $committee['study_guide'])): ?>
                        <div class="current-preview">
                            <a href="../<?= htmlspecialchars($committee['study_guide']) ?>" download style="color:#38bdf8;">
                                <i class="fa-solid fa-file-pdf"></i> Mevcut Guide'ı İndir
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="form-section-title" style="margin-top: 35px;">
            <i class="fa-solid fa-users"></i> Akademik Ekip Üyeleri (Academic Team)
        </div>

        <div class="member-block">
            <div class="member-header">
                <i class="fa-solid fa-user-tie"></i> 1. Ekip Üyesi
            </div>
            <div class="form-grid-2">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="member1_name" class="form-control" placeholder="Ad Soyad" 
                           value="<?= htmlspecialchars($committee['member1_name'] ?? '') ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ünvan / Title</label>
                    <input type="text" name="member1_title" class="form-control" placeholder="Ünvan (örn. Under-Secretary-General, Chair)" 
                           value="<?= htmlspecialchars($committee['member1_title'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px; margin-bottom: 0;">
                <label class="form-label">Portre Fotoğrafı</label>
                <div class="file-upload-box">
                    <input type="file" name="member1_image" accept="image/*">
                    <?php if (!empty($committee['member1_image']) && file_exists(__DIR__ . '/../' . $committee['member1_image'])): ?>
                        <div class="current-preview">
                            <img src="../<?= htmlspecialchars($committee['member1_image']) ?>" class="current-thumb" alt="Üye 1">
                            <span>Mevcut Fotoğraf</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="member-block">
            <div class="member-header">
                <i class="fa-solid fa-user-tie"></i> 2. Ekip Üyesi
            </div>
            <div class="form-grid-2">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="member2_name" class="form-control" placeholder="Ad Soyad" 
                           value="<?= htmlspecialchars($committee['member2_name'] ?? '') ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ünvan / Title</label>
                    <input type="text" name="member2_title" class="form-control" placeholder="Ünvan (örn. Co-Chair, Academic Assistant)" 
                           value="<?= htmlspecialchars($committee['member2_title'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px; margin-bottom: 0;">
                <label class="form-label">Portre Fotoğrafı</label>
                <div class="file-upload-box">
                    <input type="file" name="member2_image" accept="image/*">
                    <?php if (!empty($committee['member2_image']) && file_exists(__DIR__ . '/../' . $committee['member2_image'])): ?>
                        <div class="current-preview">
                            <img src="../<?= htmlspecialchars($committee['member2_image']) ?>" class="current-thumb" alt="Üye 2">
                            <span>Mevcut Fotoğraf</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="member-block">
            <div class="member-header">
                <i class="fa-solid fa-user-tie"></i> 3. Ekip Üyesi (İsteğe Bağlı)
            </div>
            <div class="form-grid-2">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="member3_name" class="form-control" placeholder="Ad Soyad" 
                           value="<?= htmlspecialchars($committee['member3_name'] ?? '') ?>">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Ünvan / Title</label>
                    <input type="text" name="member3_title" class="form-control" placeholder="Ünvan" 
                           value="<?= htmlspecialchars($committee['member3_title'] ?? '') ?>">
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px; margin-bottom: 0;">
                <label class="form-label">Portre Fotoğrafı</label>
                <div class="file-upload-box">
                    <input type="file" name="member3_image" accept="image/*">
                    <?php if (!empty($committee['member3_image']) && file_exists(__DIR__ . '/../' . $committee['member3_image'])): ?>
                        <div class="current-preview">
                            <img src="../<?= htmlspecialchars($committee['member3_image']) ?>" class="current-thumb" alt="Üye 3">
                            <span>Mevcut Fotoğraf</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <label class="checkbox-wrap">
            <input type="checkbox" name="status" value="1" <?= (!isset($committee['status']) || $committee['status'] == 1) ? 'checked' : '' ?>>
            <span><strong>Bu komiteyi sitede aktif olarak yayınla</strong></span>
        </label>

        <button type="submit" class="btn-save">
            <i class="fa-solid fa-floppy-disk"></i> KAYDET VE YAYINLA
        </button>
    </form>
</div>

</body>
</html>
