<?php
require_once __DIR__ . '/../config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}


$msg = '';
$msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_channel_settings'])) {
    $ig_val = trim($_POST['instagram_val'] ?? '');
    $ig_status = isset($_POST['instagram_status']) ? 1 : 0;
    update_contact_setting('instagram', $ig_val, $ig_status);

    $mail_val = trim($_POST['mail_val'] ?? '');
    $mail_status = isset($_POST['mail_status']) ? 1 : 0;
    update_contact_setting('mail', $mail_val, $mail_status);

    $phone_val = trim($_POST['phone_val'] ?? 'Direct Contacts');
    $phone_status = isset($_POST['phone_status']) ? 1 : 0;
    update_contact_setting('phone', $phone_val, $phone_status);

    $tiktok_val = trim($_POST['tiktok_val'] ?? '');
    $tiktok_status = isset($_POST['tiktok_status']) ? 1 : 0;
    update_contact_setting('tiktok', $tiktok_val, $tiktok_status);

    $msg = 'İletişim kanal ayarları başarıyla kaydedildi.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_phone_contact'])) {
    $phone_id = (int)($_POST['phone_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;
    $sort_order = (int)($_POST['sort_order'] ?? 0);

    if (empty($name) || empty($phone_number)) {
        $msg = 'Lütfen en azından İsim ve Telefon Numarası alanlarını doldurunuz.';
        $msgType = 'danger';
    } else {
        $data = [
            'name' => $name,
            'title' => $title,
            'phone_number' => $phone_number,
            'status' => $status,
            'sort_order' => $sort_order
        ];
        $res = save_contact_phone($data, $phone_id);
        if ($res) {
            $msg = ($phone_id > 0) ? 'Telefon yetkilisi başarıyla güncellendi.' : 'Yeni telefon yetkilisi başarıyla eklendi.';
        } else {
            $msg = 'Kayıt sırasında hata oluştu: ' . ($last_sql_error ?: 'Bilinmeyen hata.');
            $msgType = 'danger';
        }
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'toggle_phone' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $newStatus = toggle_contact_phone_status($id);
    $msg = 'Telefon yetkilisinin yayın durumu güncellendi (' . ($newStatus ? 'Aktif' : 'Pasif') . ').';
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_phone' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    delete_contact_phone($id);
    $msg = 'Telefon yetkilisi başarıyla silindi.';
}

$contactSettings = get_contact_settings();
$phoneContacts = get_all_contact_phones(false);

$ig = $contactSettings['instagram'] ?? ['channel_value' => '#', 'status' => 1];
$mail = $contactSettings['mail'] ?? ['channel_value' => 'info@example.com', 'status' => 1];
$phone = $contactSettings['phone'] ?? ['channel_value' => 'Direct Contacts', 'status' => 0];
$tiktok = $contactSettings['tiktok'] ?? ['channel_value' => '#', 'status' => 1];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim ve Destek Yönetimi | SKYMUN Admin</title>

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
            --ease-smooth: cubic-bezier(0.16, 1, 0.3, 1);
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px 0;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-title {
            font-family: var(--font-logo);
            font-size: 1.6rem;
            letter-spacing: 2px;
            color: #ffffff;
        }

        .page-subtitle {
            color: #94a3b8;
            font-size: 0.92rem;
            margin-top: 4px;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.92rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #86efac;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .contact-admin-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .admin-card {
            background: rgba(0, 7, 46, 0.75);
            border: 1px solid rgba(51, 163, 255, 0.18);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 4, 26, 0.5);
            backdrop-filter: blur(16px);
            margin-bottom: 30px;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(51, 163, 255, 0.14);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.02);
        }

        .card-title {
            font-family: var(--font-logo);
            font-size: 1.05rem;
            letter-spacing: 1.5px;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: var(--color-primary);
        }

        .card-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.88rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(51, 163, 255, 0.22);
            color: #f8fafc;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.92rem;
            font-family: inherit;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 14px rgba(51, 163, 255, 0.3);
            background: rgba(255, 255, 255, 0.07);
        }

        .switch-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.82rem;
            color: #94a3b8;
        }

        .switch-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--color-primary);
            cursor: pointer;
        }

        .btn-submit {
            background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
            color: #ffffff;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(51, 163, 255, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(51, 163, 255, 0.5);
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        table.admin-table th {
            text-align: left;
            padding: 12px 14px;
            font-size: 0.76rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            border-bottom: 1px solid rgba(51, 163, 255, 0.15);
            background: rgba(255, 255, 255, 0.01);
        }

        table.admin-table td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            color: #e2e8f0;
            vertical-align: middle;
        }

        table.admin-table tr:hover td {
            background: rgba(51, 163, 255, 0.04);
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 0.76rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.15);
            color: #86efac;
            border: 1px solid rgba(34, 197, 94, 0.35);
        }

        .badge-active:hover {
            background: rgba(34, 197, 94, 0.28);
        }

        .badge-passive {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.35);
        }

        .badge-passive:hover {
            background: rgba(239, 68, 68, 0.28);
        }

        .actions-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.84rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .btn-edit {
            background: rgba(51, 163, 255, 0.12);
            color: var(--color-primary);
            border: 1px solid rgba(51, 163, 255, 0.3);
        }

        .btn-edit:hover {
            background: var(--color-primary);
            color: #00072e;
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background: #ef4444;
            color: #ffffff;
        }

        .admin-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 4, 26, 0.85);
            backdrop-filter: blur(12px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .admin-modal-overlay.is-active {
            opacity: 1;
            visibility: visible;
        }

        .admin-modal {
            background: rgba(0, 7, 46, 0.98);
            border: 1px solid rgba(51, 163, 255, 0.35);
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            transform: scale(0.94);
            transition: transform 0.3s ease;
        }

        .admin-modal-overlay.is-active .admin-modal {
            transform: scale(1);
        }

        .admin-modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(51, 163, 255, 0.16);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-modal-header h3 {
            font-family: var(--font-logo);
            font-size: 1.1rem;
            color: #ffffff;
            letter-spacing: 1px;
        }

        .modal-close-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
        }

        .modal-close-btn:hover {
            color: #ffffff;
        }

        .admin-modal-body {
            padding: 22px 24px;
        }

        .desktop-table-view {
            display: block;
        }

        .mobile-cards-view {
            display: none;
        }

        @media (max-width: 868px) {
            body {
                padding-bottom: calc(85px + env(safe-area-inset-bottom, 16px)) !important;
            }

            .container {
                padding: 16px 12px calc(40px + env(safe-area-inset-bottom, 16px)) !important;
            }

            .page-title {
                font-size: 1.25rem !important;
                letter-spacing: 1px !important;
            }

            .page-subtitle {
                font-size: 0.82rem !important;
            }

            .contact-admin-grid {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }

            .admin-card {
                border-radius: 14px !important;
                margin-bottom: 20px !important;
            }

            .card-header {
                padding: 14px 16px !important;
            }

            .card-title {
                font-size: 0.95rem !important;
            }

            .card-body {
                padding: 16px 14px !important;
            }

            .form-grid-row {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
            }

            .form-control {
                font-size: 16px !important;
                padding: 11px 14px !important;
                border-radius: 10px !important;
            }

            .btn-submit {
                width: 100% !important;
                justify-content: center !important;
                min-height: 44px !important;
                font-size: 0.95rem !important;
            }

            .desktop-table-view {
                display: none !important;
            }

            .mobile-cards-view {
                display: flex !important;
                flex-direction: column !important;
                gap: 12px !important;
                padding: 12px;
            }

            .mobile-contact-card {
                background: rgba(0, 7, 46, 0.88);
                border: 1px solid rgba(51, 163, 255, 0.22);
                border-radius: 12px;
                padding: 14px;
                display: flex;
                flex-direction: column;
                gap: 10px;
                box-shadow: 0 4px 16px rgba(0, 4, 26, 0.4);
            }

            .mobile-contact-head {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 10px;
            }

            .mobile-contact-name {
                font-size: 1rem;
                font-weight: 600;
                color: #ffffff;
            }

            .mobile-contact-title {
                font-size: 0.78rem;
                color: var(--color-primary);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-top: 2px;
            }

            .mobile-contact-phone {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                background: rgba(56, 189, 248, 0.12);
                border: 1px solid rgba(56, 189, 248, 0.25);
                color: #38bdf8;
                padding: 10px 14px;
                border-radius: 8px;
                text-decoration: none;
                font-size: 0.92rem;
                font-weight: 500;
                min-height: 42px;
            }

            .mobile-contact-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-top: 10px;
                border-top: 1px solid rgba(51, 163, 255, 0.1);
            }

            .mobile-actions-group {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .mobile-act-btn {
                padding: 8px 14px;
                border-radius: 8px;
                font-size: 0.84rem;
                font-weight: 500;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                min-height: 38px;
                cursor: pointer;
            }

            .admin-modal-overlay {
                align-items: flex-end !important;
                padding: 0 !important;
            }

            .admin-modal {
                max-width: 100% !important;
                border-radius: 20px 20px 0 0 !important;
                border-bottom: none !important;
                transform: translateY(100%) !important;
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
                max-height: 85vh !important;
                overflow-y: auto !important;
                padding-bottom: calc(20px + env(safe-area-inset-bottom, 16px)) !important;
            }

            .admin-modal-overlay.is-active .admin-modal {
                transform: translateY(0) !important;
            }
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/nav.php'; ?>

<div class="container">
    <div class="page-header">
        <h1 class="page-title">İLETİŞİM VE DESTEK YÖNETİMİ</h1>
        <p class="page-subtitle">Sitedeki 4 iletişim kutusunu (Instagram, E-Mail, Phone, TikTok) ve telefon rehberini düzenleyin</p>
    </div>

    <?php if (!empty($msg)): ?>
        <div class="alert alert-<?= $msgType ?>">
            <i class="fa-solid <?= ($msgType === 'success') ? 'fa-circle-check' : 'fa-triangle-exclamation' ?>"></i>
            <span><?= htmlspecialchars($msg) ?></span>
        </div>
    <?php endif; ?>

    <div class="contact-admin-grid">

        <div class="admin-card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-sliders"></i>
                    <span>İletişim Kutuları & Kanalları</span>
                </h2>
                <span style="font-size:0.8rem; color:#94a3b8;">Aktif / Pasif</span>
            </div>

            <div class="card-body">
                <form action="contact.php" method="POST">
                    <input type="hidden" name="save_channel_settings" value="1">

                    <div class="form-group">
                        <div class="form-label">
                            <span><i class="fa-brands fa-instagram" style="color:#e1306c;"></i> Instagram Linki / Hesabı</span>
                            <label class="switch-toggle">
                                <input type="checkbox" name="instagram_status" value="1" <?= (!empty($ig['status'])) ? 'checked' : '' ?>>
                                <span>Aktif</span>
                            </label>
                        </div>
                        <input type="text" name="instagram_val" class="form-control" 
                               value="<?= htmlspecialchars($ig['channel_value']) ?>" 
                               placeholder="https://instagram.com/yourhandle">
                    </div>

                    <div class="form-group">
                        <div class="form-label">
                            <span><i class="fa-solid fa-envelope" style="color:var(--color-primary);"></i> E-Mail Adresi</span>
                            <label class="switch-toggle">
                                <input type="checkbox" name="mail_status" value="1" <?= (!empty($mail['status'])) ? 'checked' : '' ?>>
                                <span>Aktif</span>
                            </label>
                        </div>
                        <input type="email" name="mail_val" class="form-control" 
                               value="<?= htmlspecialchars($mail['channel_value']) ?>" 
                               placeholder="info@example.com">
                    </div>

                    <div class="form-group">
                        <div class="form-label">
                            <span><i class="fa-solid fa-phone" style="color:#22c55e;"></i> Telefon Destek Kutusu</span>
                            <label class="switch-toggle">
                                <input type="checkbox" name="phone_status" value="1" <?= (!empty($phone['status'])) ? 'checked' : '' ?>>
                                <span>Aktif</span>
                            </label>
                        </div>
                        <input type="text" name="phone_val" class="form-control" 
                               value="<?= htmlspecialchars($phone['channel_value']) ?>" 
                               placeholder="Direct Contacts / Destek Hattı">
                        <small style="color:#94a3b8; font-size:0.78rem; display:block; margin-top:4px;">
                            * Telefon kutusuna tıklandığında sağdaki listede eklediğiniz yetkililer popup olarak açılır.
                        </small>
                    </div>

                    <div class="form-group">
                        <div class="form-label">
                            <span><i class="fa-brands fa-tiktok" style="color:#25f4ee;"></i> TikTok Linki / Hesabı</span>
                            <label class="switch-toggle">
                                <input type="checkbox" name="tiktok_status" value="1" <?= (!empty($tiktok['status'])) ? 'checked' : '' ?>>
                                <span>Aktif</span>
                            </label>
                        </div>
                        <input type="text" name="tiktok_val" class="form-control" 
                               value="<?= htmlspecialchars($tiktok['channel_value']) ?>" 
                               placeholder="https://tiktok.com/@yourhandle">
                    </div>

                    <div style="margin-top: 26px; padding-top: 18px; border-top: 1px solid rgba(51, 163, 255, 0.12);">
                        <button type="submit" class="btn-submit">
                            <i class="fa-solid fa-floppy-disk"></i> Kanal Ayarlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div>
            <div class="admin-card" style="margin-bottom: 24px;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Yeni Telefon Yetkilisi Ekle</span>
                    </h2>
                </div>
                <div class="card-body">
                    <form action="contact.php" method="POST">
                        <input type="hidden" name="save_phone_contact" value="1">
                        <input type="hidden" name="phone_id" value="0">

                        <div class="form-grid-row" style="display:grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label">İsim Soyisim *</label>
                                <input type="text" name="name" class="form-control" placeholder="Örn: Yetkili Adı" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ünvan / Görev *</label>
                                <input type="text" name="title" class="form-control" placeholder="Örn: Secretary-General" required>
                            </div>
                        </div>

                        <div class="form-grid-row" style="display:grid; grid-template-columns: 1.4fr 0.6fr; gap: 14px;">
                            <div class="form-group">
                                <label class="form-label">Telefon Numarası *</label>
                                <input type="text" name="phone_number" class="form-control" placeholder="Örn: +00 000 000 00 00" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sıra No</label>
                                <input type="number" name="sort_order" class="form-control" value="0">
                            </div>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; margin-top:14px;">
                            <label class="switch-toggle">
                                <input type="checkbox" name="status" value="1" checked>
                                <span>Yayında (Aktif)</span>
                            </label>
                            <button type="submit" class="btn-submit" style="padding: 8px 18px; font-size: 0.88rem;">
                                <i class="fa-solid fa-plus"></i> Rehbere Ekle
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="admin-card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fa-solid fa-phone-volume"></i>
                        <span>Telefon Rehberi Listesi (<?= count($phoneContacts) ?>)</span>
                    </h2>
                </div>

                <div>
                    <?php if (empty($phoneContacts)): ?>
                        <div style="padding: 35px 20px; text-align: center; color: #94a3b8;">
                            <i class="fa-solid fa-phone-slash" style="font-size: 2rem; color: rgba(51, 163, 255, 0.3); margin-bottom: 10px; display: block;"></i>
                            Henüz rehbere kayıtlı telefon numarası bulunmuyor.
                        </div>
                    <?php else: ?>
                        <div class="desktop-table-view" style="overflow-x: auto;">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Sıra</th>
                                        <th>İsim & Ünvan</th>
                                        <th>Telefon</th>
                                        <th>Durum</th>
                                        <th style="text-align: right;">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($phoneContacts as $pc): ?>
                                        <tr>
                                            <td style="color: #94a3b8; font-family: monospace; font-size: 0.9rem;">
                                                #<?= (int)$pc['sort_order'] ?>
                                            </td>
                                            <td>
                                                <strong style="color: #ffffff; display: block; font-size: 0.94rem;">
                                                    <?= htmlspecialchars($pc['name']) ?>
                                                </strong>
                                                <span style="color: var(--color-primary); font-size: 0.78rem; text-transform: uppercase;">
                                                    <?= htmlspecialchars($pc['title']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $pc['phone_number']) ?>" 
                                                   style="color: #93c5fd; text-decoration: none; font-size: 0.86rem; display: inline-flex; align-items: center; gap: 6px;">
                                                    <i class="fa-solid fa-phone" style="font-size: 0.76rem;"></i>
                                                    <?= htmlspecialchars($pc['phone_number']) ?>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($pc['status'] == 1): ?>
                                                    <a href="contact.php?action=toggle_phone&id=<?= $pc['id'] ?>" class="badge-status badge-active" title="Pasif Yapmak İçin Tıklayın">
                                                        <i class="fa-solid fa-check"></i> Aktif
                                                    </a>
                                                <?php else: ?>
                                                    <a href="contact.php?action=toggle_phone&id=<?= $pc['id'] ?>" class="badge-status badge-passive" title="Aktif Yapmak İçin Tıklayın">
                                                        <i class="fa-solid fa-xmark"></i> Pasif
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: right;">
                                                <div class="actions-cell" style="justify-content: flex-end;">
                                                    <button type="button" 
                                                            class="btn-action btn-edit" 
                                                            title="Düzenle"
                                                            onclick="openEditPhoneModal(<?= htmlspecialchars(json_encode($pc), ENT_QUOTES, 'UTF-8') ?>)">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </button>
                                                    <a href="contact.php?action=delete_phone&id=<?= $pc['id'] ?>" 
                                                       class="btn-action btn-delete" 
                                                       title="Sil" 
                                                       onclick="return confirm('Bu telefon kaydını silmek istediğinizden emin misiniz?');">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mobile-cards-view">
                            <?php foreach ($phoneContacts as $pc): ?>
                                <div class="mobile-contact-card">
                                    <div class="mobile-contact-head">
                                        <div>
                                            <div class="mobile-contact-name"><?= htmlspecialchars($pc['name']) ?></div>
                                            <div class="mobile-contact-title"><?= htmlspecialchars($pc['title']) ?></div>
                                        </div>
                                        <?php if ($pc['status'] == 1): ?>
                                            <a href="contact.php?action=toggle_phone&id=<?= $pc['id'] ?>" class="badge-status badge-active">
                                                <i class="fa-solid fa-check"></i> Aktif
                                            </a>
                                        <?php else: ?>
                                            <a href="contact.php?action=toggle_phone&id=<?= $pc['id'] ?>" class="badge-status badge-passive">
                                                <i class="fa-solid fa-xmark"></i> Pasif
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $pc['phone_number']) ?>" class="mobile-contact-phone">
                                        <i class="fa-solid fa-phone"></i>
                                        <span><?= htmlspecialchars($pc['phone_number']) ?></span>
                                    </a>

                                    <div class="mobile-contact-footer">
                                        <div style="font-size: 0.8rem; color: #94a3b8; font-family: monospace;">
                                            Sıra: #<?= (int)$pc['sort_order'] ?>
                                        </div>
                                        <div class="mobile-actions-group">
                                            <button type="button" 
                                                    class="mobile-act-btn btn-edit" 
                                                    onclick="openEditPhoneModal(<?= htmlspecialchars(json_encode($pc), ENT_QUOTES, 'UTF-8') ?>)">
                                                <i class="fa-solid fa-pen"></i> Düzenle
                                            </button>
                                            <a href="contact.php?action=delete_phone&id=<?= $pc['id'] ?>" 
                                               class="mobile-act-btn btn-delete" 
                                               onclick="return confirm('Bu telefon kaydını silmek istediğinizden emin misiniz?');">
                                                <i class="fa-solid fa-trash-can"></i> Sil
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="admin-modal-overlay" id="editPhoneModalOverlay">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h3><i class="fa-solid fa-pen" style="color:var(--color-primary); font-size:0.95rem;"></i> Yetkiliyi Düzenle</h3>
            <button type="button" class="modal-close-btn" onclick="closeEditPhoneModal()">&times;</button>
        </div>
        <div class="admin-modal-body">
            <form action="contact.php" method="POST">
                <input type="hidden" name="save_phone_contact" value="1">
                <input type="hidden" name="phone_id" id="edit_phone_id" value="">

                <div class="form-group">
                    <label class="form-label">İsim Soyisim *</label>
                    <input type="text" name="name" id="edit_phone_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Ünvan / Görev *</label>
                    <input type="text" name="title" id="edit_phone_title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Telefon Numarası *</label>
                    <input type="text" name="phone_number" id="edit_phone_number" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Sıra No</label>
                    <input type="number" name="sort_order" id="edit_phone_sort" class="form-control">
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:20px; padding-top:14px; border-top:1px solid rgba(51, 163, 255, 0.12);">
                    <label class="switch-toggle">
                        <input type="checkbox" name="status" id="edit_phone_status" value="1">
                        <span>Yayında (Aktif)</span>
                    </label>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditPhoneModal(data) {
        document.getElementById('edit_phone_id').value = data.id || '';
        document.getElementById('edit_phone_name').value = data.name || '';
        document.getElementById('edit_phone_title').value = data.title || '';
        document.getElementById('edit_phone_number').value = data.phone_number || '';
        document.getElementById('edit_phone_sort').value = data.sort_order || 0;
        document.getElementById('edit_phone_status').checked = (parseInt(data.status) === 1);

        const overlay = document.getElementById('editPhoneModalOverlay');
        overlay.classList.add('is-active');
    }

    function closeEditPhoneModal() {
        const overlay = document.getElementById('editPhoneModalOverlay');
        overlay.classList.remove('is-active');
    }

    document.getElementById('editPhoneModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditPhoneModal();
        }
    });
</script>

</body>
</html>
