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
    $newStatus = toggle_committee_status($id);
    $msg = 'Komite durumu başarıyla güncellendi (' . ($newStatus ? 'Aktif' : 'Pasif') . ').';
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    delete_committee($id);
    $msg = 'Komite başarıyla silindi.';
}

$committees = get_all_committees(false);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komite Yönetimi | SKYMUN Admin</title>

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
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-title {
            font-family: var(--font-logo);
            font-size: 1.8rem;
            color: #ffffff;
            letter-spacing: 1.5px;
        }

        .page-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 22px;
            background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
            border: 1px solid rgba(51, 163, 255, 0.4);
            border-radius: 10px;
            color: #ffffff;
            font-weight: 600;
            text-decoration: none;
            font-size: 0.92rem;
            letter-spacing: 0.5px;
            box-shadow: 0 0 15px rgba(51, 163, 255, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px rgba(51, 163, 255, 0.6);
        }

        .alert {
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.92rem;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #86efac;
        }

        .table-card {
            background: rgba(0, 7, 46, 0.7);
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
            background: rgba(29, 11, 121, 0.3);
            padding: 16px 20px;
            color: #cbd5e1;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(51, 163, 255, 0.15);
        }

        table.admin-table td {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #f8fafc;
            font-size: 0.92rem;
            vertical-align: middle;
        }

        table.admin-table tr:hover {
            background: rgba(51, 163, 255, 0.04);
        }

        .thumb-preview {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid rgba(51, 163, 255, 0.25);
            background: rgba(0, 7, 46, 0.5);
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .badge-active {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.35);
            color: #4ade80;
        }

        .badge-active:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .badge-inactive {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .badge-inactive:hover {
            background: rgba(34, 197, 94, 0.15);
            border-color: rgba(34, 197, 94, 0.35);
            color: #4ade80;
        }

        .action-btns {
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
            color: #ffffff;
            text-decoration: none;
            font-size: 0.88rem;
            transition: all 0.25s ease;
        }

        .btn-edit {
            background: rgba(51, 163, 255, 0.15);
            border: 1px solid rgba(51, 163, 255, 0.3);
            color: #33a3ff;
        }

        .btn-edit:hover {
            background: var(--color-primary);
            color: var(--color-navy);
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .btn-delete:hover {
            background: #ef4444;
            color: #ffffff;
        }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            color: rgba(51, 163, 255, 0.4);
            margin-bottom: 16px;
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
                width: 48px;
                height: 48px;
                border-radius: 10px;
                object-fit: cover;
                border: 1px solid rgba(51, 163, 255, 0.25);
                flex-shrink: 0;
            }

            .mobile-card-thumb.icon-fallback {
                background: rgba(51, 163, 255, 0.1);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--color-primary);
                font-size: 1.2rem;
            }

            .mobile-card-badge {
                font-family: var(--font-logo);
                font-size: 0.72rem;
                color: var(--color-primary);
                letter-spacing: 1px;
                margin-bottom: 2px;
            }

            .mobile-card-title {
                font-size: 0.98rem;
                font-weight: 600;
                color: #ffffff;
                line-height: 1.3;
            }

            .mobile-card-body {
                background: rgba(255, 255, 255, 0.02);
                border: 1px solid rgba(255, 255, 255, 0.05);
                border-radius: 8px;
                padding: 10px 12px;
                font-size: 0.84rem;
            }

            .mobile-body-label {
                color: #94a3b8;
                font-weight: 600;
                font-size: 0.76rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                display: block;
                margin-bottom: 3px;
            }

            .mobile-body-text {
                color: #e2e8f0;
                line-height: 1.45;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                margin: 0;
            }

            .mobile-card-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 10px;
                padding-top: 10px;
                border-top: 1px solid rgba(51, 163, 255, 0.1);
            }

            .mobile-btn-meta {
                color: #38bdf8;
                font-size: 0.8rem;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 6px 10px;
                background: rgba(56, 189, 248, 0.1);
                border: 1px solid rgba(56, 189, 248, 0.25);
                border-radius: 6px;
            }

            .mobile-btn-meta.disabled {
                color: #64748b;
                background: rgba(255, 255, 255, 0.03);
                border-color: rgba(255, 255, 255, 0.08);
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
            <h1 class="page-title">KOMİTE YÖNETİMİ</h1>
            <p class="page-subtitle">Sitede yayınlanan tüm komite kartlarını düzenleyin ve yönetin</p>
        </div>
        <a href="committee-edit.php" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Yeni Komite Ekle
        </a>
    </div>

    <?php if (!$pdo): ?>
        <div style="background:rgba(239, 68, 68, 0.22);border:1px solid #ef4444;color:#fecaca;padding:16px 20px;border-radius:12px;margin-bottom:25px;line-height:1.6;">
            <div style="font-weight:700;font-size:1.05rem;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Veritabanına Bağlanılamadı!
            </div>
            <div>
                Veritabanı bağlantısı kurulamadığı için komiteler listelenemiyor veya kaydedilemiyor.
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

    <?php if ($msg): ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span><?= htmlspecialchars($msg) ?></span>
        </div>
    <?php endif; ?>

    <div class="table-card">
        <?php if (empty($committees)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-folder-open"></i>
                <h3>Henüz hiç komite eklenmemiş.</h3>
                <p style="margin-top:8px;">İlk komitenizi eklemek için yukarıdaki "Yeni Komite Ekle" butonunu kullanabilirsiniz.</p>
            </div>
        <?php else: ?>
            <div class="desktop-table-view">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 70px;">Görsel</th>
                            <th>Komite Adı</th>
                            <th>Kısaltma</th>
                            <th>Gündem Maddesi</th>
                            <th>Study Guide</th>
                            <th>Durum</th>
                            <th style="width: 110px; text-align: right;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($committees as $com): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($com['cover_image']) && file_exists(__DIR__ . '/../' . $com['cover_image'])): ?>
                                        <img src="../<?= htmlspecialchars($com['cover_image']) ?>" class="thumb-preview" alt="Kapak">
                                    <?php else: ?>
                                        <div class="thumb-preview" style="display:flex;align-items:center;justify-content:center;color:#33a3ff;font-size:1.2rem;">
                                            <i class="fa-solid fa-landmark"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($com['title']) ?></strong>
                                </td>
                                <td>
                                    <span style="font-family:var(--font-logo);color:var(--color-primary);font-size:0.9rem;">
                                        <?= htmlspecialchars($com['short_name'] ?? '-') ?>
                                    </span>
                                </td>
                                <td style="max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color:#94a3b8;">
                                    <?= htmlspecialchars($com['agenda'] ?? '-') ?>
                                </td>
                                <td>
                                    <?php if (!empty($com['study_guide']) && file_exists(__DIR__ . '/../' . $com['study_guide'])): ?>
                                        <a href="../<?= htmlspecialchars($com['study_guide']) ?>" download style="color:#38bdf8;font-size:0.85rem;display:inline-flex;align-items:center;gap:6px;">
                                            <i class="fa-solid fa-file-pdf"></i> İndir
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#64748b;font-size:0.85rem;">Yok</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="?action=toggle&id=<?= $com['id'] ?>" 
                                       class="badge-status <?= ($com['status'] == 1) ? 'badge-active' : 'badge-inactive' ?>"
                                       title="Durumu değiştirmek için tıklayın">
                                        <i class="fa-solid <?= ($com['status'] == 1) ? 'fa-check' : 'fa-xmark' ?>"></i>
                                        <?= ($com['status'] == 1) ? 'Aktif' : 'Pasif' ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="action-btns" style="justify-content: flex-end;">
                                        <a href="committee-edit.php?id=<?= $com['id'] ?>" class="btn-action btn-edit" title="Düzenle">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="?action=delete&id=<?= $com['id'] ?>" 
                                           class="btn-action btn-delete" 
                                           title="Sil" 
                                           onclick="return confirm('Bu komiteyi silmek istediğinize emin misiniz?');">
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
                <?php foreach ($committees as $com): ?>
                    <div class="mobile-data-card">
                        <div class="mobile-card-header">
                            <div class="mobile-card-lead">
                                <?php if (!empty($com['cover_image']) && file_exists(__DIR__ . '/../' . $com['cover_image'])): ?>
                                    <img src="../<?= htmlspecialchars($com['cover_image']) ?>" class="mobile-card-thumb" alt="Kapak">
                                <?php else: ?>
                                    <div class="mobile-card-thumb icon-fallback">
                                        <i class="fa-solid fa-landmark"></i>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <div class="mobile-card-badge"><?= htmlspecialchars($com['short_name'] ?? 'MUN') ?></div>
                                    <h3 class="mobile-card-title"><?= htmlspecialchars($com['title']) ?></h3>
                                </div>
                            </div>
                            <a href="?action=toggle&id=<?= $com['id'] ?>" 
                               class="badge-status <?= ($com['status'] == 1) ? 'badge-active' : 'badge-inactive' ?>">
                                <i class="fa-solid <?= ($com['status'] == 1) ? 'fa-check' : 'fa-xmark' ?>"></i>
                                <?= ($com['status'] == 1) ? 'Aktif' : 'Pasif' ?>
                            </a>
                        </div>

                        <?php if (!empty($com['agenda'])): ?>
                            <div class="mobile-card-body">
                                <span class="mobile-body-label">Gündem Maddesi</span>
                                <p class="mobile-body-text"><?= htmlspecialchars($com['agenda']) ?></p>
                            </div>
                        <?php endif; ?>

                        <div class="mobile-card-footer">
                            <?php if (!empty($com['study_guide']) && file_exists(__DIR__ . '/../' . $com['study_guide'])): ?>
                                <a href="../<?= htmlspecialchars($com['study_guide']) ?>" download class="mobile-btn-meta">
                                    <i class="fa-solid fa-file-pdf"></i> Guide İndir
                                </a>
                            <?php else: ?>
                                <span class="mobile-btn-meta disabled"><i class="fa-solid fa-file-slash"></i> Guide Yok</span>
                            <?php endif; ?>

                            <div class="mobile-actions-group">
                                <a href="committee-edit.php?id=<?= $com['id'] ?>" class="mobile-act-btn btn-edit">
                                    <i class="fa-solid fa-pen-to-square"></i> Düzenle
                                </a>
                                <a href="?action=delete&id=<?= $com['id'] ?>" 
                                   class="mobile-act-btn btn-delete" 
                                   onclick="return confirm('Bu komiteyi silmek istediğinize emin misiniz?');">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<a href="committee-edit.php" class="admin-mobile-fab" title="Yeni Komite Ekle" aria-label="Yeni Komite Ekle">
    <i class="fa-solid fa-plus"></i>
</a>

</body>
</html>
