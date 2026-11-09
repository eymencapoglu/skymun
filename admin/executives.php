<?php
require_once __DIR__ . '/../config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit;
}


$msg = '';
$msgType = 'success';

if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $newStatus = toggle_executive_status($id);
    $msg = 'Üyenin yayın durumu başarıyla güncellendi (' . ($newStatus ? 'Aktif' : 'Pasif') . ').';
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    delete_executive($id);
    $msg = 'Executive üyesi başarıyla silindi.';
}

if (isset($_GET['msg']) && $_GET['msg'] === 'saved') {
    $msg = 'Executive üyesi başarıyla kaydedildi.';
}

$executives = get_all_executives(false);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Ekibi Yönetimi | SKYMUN Admin</title>

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
            padding-bottom: 60px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto 0;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
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

        .btn-primary {
            background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(51, 163, 255, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(51, 163, 255, 0.5);
        }

        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 0.92rem;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #86efac;
        }

        .table-card {
            background: rgba(0, 7, 46, 0.75);
            border: 1px solid rgba(51, 163, 255, 0.18);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 4, 26, 0.5);
            backdrop-filter: blur(16px);
        }

        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        table.admin-table th {
            background: rgba(29, 11, 121, 0.35);
            color: #94a3b8;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(51, 163, 255, 0.15);
        }

        table.admin-table td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.95rem;
            vertical-align: middle;
        }

        table.admin-table tr:last-child td {
            border-bottom: none;
        }

        table.admin-table tr:hover td {
            background: rgba(51, 163, 255, 0.04);
        }

        .member-thumb {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid rgba(51, 163, 255, 0.25);
            background: #00072e;
        }

        .member-thumb-placeholder {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            background: rgba(29, 11, 121, 0.4);
            border: 1px solid rgba(51, 163, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(51, 163, 255, 0.5);
            font-size: 1.1rem;
        }

        .badge-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
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
            gap: 10px;
        }

        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.88rem;
            transition: all 0.2s ease;
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

        .empty-state {
            padding: 60px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 2.8rem;
            color: rgba(51, 163, 255, 0.3);
            margin-bottom: 14px;
        }

        .mobile-cards-view {
            display: none;
        }

        @media (max-width: 868px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .page-header .btn-primary {
                width: 100%;
                justify-content: center;
                padding: 12px;
                font-size: 0.95rem;
            }

            .desktop-table-view {
                display: none !important;
            }

            .mobile-cards-view {
                display: flex !important;
                flex-direction: column;
                gap: 14px;
            }

            .table-card {
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }

            .mobile-data-card {
                background: rgba(0, 7, 46, 0.88);
                border: 1px solid rgba(51, 163, 255, 0.22);
                border-radius: 14px;
                padding: 16px;
                box-shadow: 0 8px 24px rgba(0, 4, 26, 0.5);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .mobile-card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: 12px;
            }

            .mobile-card-lead {
                display: flex;
                align-items: center;
                gap: 12px;
                flex: 1;
            }

            .mobile-card-thumb {
                width: 52px;
                height: 52px;
                border-radius: 12px;
                object-fit: cover;
                border: 1px solid rgba(51, 163, 255, 0.3);
                flex-shrink: 0;
            }

            .mobile-card-thumb.icon-fallback {
                background: rgba(51, 163, 255, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--color-primary);
                font-size: 1.25rem;
            }

            .mobile-card-badge {
                font-family: var(--font-logo);
                font-size: 0.74rem;
                color: var(--color-primary);
                letter-spacing: 0.8px;
                margin-top: 3px;
                text-transform: uppercase;
            }

            .mobile-card-title {
                font-size: 1rem;
                font-weight: 600;
                color: #ffffff;
                line-height: 1.3;
            }

            .mobile-card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
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
                transition: all 0.2s ease;
                min-height: 38px;
            }
        }
    </style>
</head>
<body>

<?php require_once __DIR__ . '/nav.php'; ?>

<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">EXECUTIVE EKİBİ YÖNETİMİ</h1>
            <p class="page-subtitle">Sitede listelenen yönetim kurulu üyelerini düzenleyin ve yönetin</p>
        </div>
        <a href="executive-edit.php" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Yeni Üye Ekle
        </a>
    </div>

    <?php if (!$pdo): ?>
        <div style="background:rgba(239, 68, 68, 0.22);border:1px solid #ef4444;color:#fecaca;padding:16px 20px;border-radius:12px;margin-bottom:25px;line-height:1.6;">
            <div style="font-weight:700;font-size:1.05rem;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Veritabanına Bağlanılamadı!
            </div>
            <div>
                Veritabanı bağlantısı kurulamadığı için üyeler listelenemiyor veya kaydedilemiyor.
                <?php if (!empty($db_error)): ?>
                    <br><strong style="color:#ffffff;">MySQL Hata Çıktısı:</strong> <code style="background:rgba(0,0,0,0.4);padding:3px 8px;border-radius:4px;color:#fca5a5;font-size:0.88rem;word-break:break-all;"><?= htmlspecialchars($db_error) ?></code>
                <?php endif; ?>
            </div>
            <div style="font-size:0.88rem;color:#cbd5e1;margin-top:12px;padding-top:12px;border-top:1px solid rgba(239,68,68,0.3);">
                <strong>cPanel'de Çözüm Adımları:</strong><br>
                1. <strong>cPanel &rarr; MySQL® Veritabanları</strong> sayfasına gidin.<br>
                2. <strong>"Mevcut Kullanıcılar"</strong> tablosundan kullanıcının tam adına bakın (Örn: <code>cpaneluser_dbuser</code>).<br>
                3. <strong>"Veritabanına Kullanıcı Ekle"</strong> bölümünden bu kullanıcıyı <code>cpaneluser_skymun</code> veritabanına ekleyin ve <strong>"TÜM İZİNLER" (ALL PRIVILEGES)</strong> kutucuğunu işaretleyin.<br>
                4. Bilgileri <code>config.php</code> dosyasındaki <code>DB_USER</code> ve <code>DB_PASS</code> alanlarına yazın.
            </div>
        </div>
    <?php endif; ?>

    <?php if ($msg): ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= htmlspecialchars($msg) ?></span>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <?php if (empty($executives)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-users-slash"></i>
                <h3>Henüz hiç executive üyesi eklenmemiş.</h3>
                <p style="margin-top:8px;">İlk üyeyi eklemek için yukarıdaki "Yeni Üye Ekle" butonunu kullanabilirsiniz.</p>
            </div>
        <?php else: ?>
            <div class="desktop-table-view">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Fotoğraf</th>
                            <th>Adı Soyadı</th>
                            <th>Unvanı / Görevi</th>
                            <th style="width: 80px; text-align: center;">Sıra</th>
                            <th style="width: 120px; text-align: center;">Durum</th>
                            <th style="width: 110px; text-align: right;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($executives as $ex): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($ex['image']) && file_exists(__DIR__ . '/../' . $ex['image'])): ?>
                                        <img src="../<?= htmlspecialchars($ex['image']) ?>" alt="<?= htmlspecialchars($ex['name']) ?>" class="member-thumb" style="object-position: center 30%;">
                                    <?php else: ?>
                                        <div class="member-thumb-placeholder">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong style="font-size: 1rem; color: #ffffff;"><?= htmlspecialchars($ex['name']) ?></strong>
                                </td>
                                <td>
                                    <span style="color: var(--color-primary); font-weight: 500;"><?= htmlspecialchars($ex['title']) ?></span>
                                </td>
                                <td style="text-align: center; color: #94a3b8;">
                                    <?= (int)$ex['sort_order'] ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="?action=toggle&id=<?= $ex['id'] ?>" 
                                       class="badge-status <?= $ex['status'] == 1 ? 'badge-active' : 'badge-passive' ?>"
                                       title="Durumu değiştirmek için tıklayın">
                                        <?= $ex['status'] == 1 ? 'Aktif' : 'Pasif' ?>
                                    </a>
                                </td>
                                <td style="text-align: right;">
                                    <div class="actions-cell" style="justify-content: flex-end;">
                                        <a href="executive-edit.php?id=<?= $ex['id'] ?>" class="btn-action btn-edit" title="Düzenle">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="?action=delete&id=<?= $ex['id'] ?>" 
                                           class="btn-action btn-delete" 
                                           title="Sil" 
                                           onclick="return confirm('Bu üyeyi silmek istediğinize emin misiniz?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mobile-cards-view">
                <?php foreach ($executives as $ex): ?>
                    <div class="mobile-data-card">
                        <div class="mobile-card-header">
                            <div class="mobile-card-lead">
                                <?php if (!empty($ex['image']) && file_exists(__DIR__ . '/../' . $ex['image'])): ?>
                                    <img src="../<?= htmlspecialchars($ex['image']) ?>" alt="<?= htmlspecialchars($ex['name']) ?>" class="mobile-card-thumb" style="object-position: center 30%;">
                                <?php else: ?>
                                    <div class="mobile-card-thumb icon-fallback">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h3 class="mobile-card-title"><?= htmlspecialchars($ex['name']) ?></h3>
                                    <div class="mobile-card-badge"><?= htmlspecialchars($ex['title']) ?></div>
                                </div>
                            </div>
                            <a href="?action=toggle&id=<?= $ex['id'] ?>" 
                               class="badge-status <?= $ex['status'] == 1 ? 'badge-active' : 'badge-passive' ?>"
                               title="Durumu değiştirmek için tıklayın">
                                <i class="fa-solid <?= ($ex['status'] == 1) ? 'fa-check' : 'fa-xmark' ?>"></i>
                                <?= $ex['status'] == 1 ? 'Aktif' : 'Pasif' ?>
                            </a>
                        </div>

                        <div class="mobile-card-footer">
                            <span style="font-size:0.8rem; color:#94a3b8;">
                                Sıra No: #<?= (int)$ex['sort_order'] ?>
                            </span>
                            <div class="mobile-actions-group">
                                <a href="executive-edit.php?id=<?= $ex['id'] ?>" class="mobile-act-btn btn-edit">
                                    <i class="fa-solid fa-pen-to-square"></i> Düzenle
                                </a>
                                <a href="?action=delete&id=<?= $ex['id'] ?>" 
                                   class="mobile-act-btn btn-delete" 
                                   onclick="return confirm('Bu üyeyi silmek istediğinize emin misiniz?');">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<a href="executive-edit.php" class="admin-mobile-fab" title="Yeni Üye Ekle" aria-label="Yeni Üye Ekle">
    <i class="fa-solid fa-plus"></i>
</a>

</body>
</html>
