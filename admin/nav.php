<?php
$currentAdminPage = basename($_SERVER['PHP_SELF'] ?? '');
$isCommitteeActive = in_array($currentAdminPage, ['committees.php', 'committee-edit.php']);
$isExecutiveActive = in_array($currentAdminPage, ['executives.php', 'executive-edit.php']);
$isApplicationActive = in_array($currentAdminPage, ['applications.php', 'application-edit.php']);
$isContactActive = ($currentAdminPage === 'contact.php');
?>
<style>
    input, select, textarea {
        font-size: 16px !important;
        -webkit-tap-highlight-color: transparent;
    }

    button, a {
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
    }

    .admin-navbar {
        background: rgba(0, 7, 46, 0.92);
        border-bottom: 1px solid rgba(51, 163, 255, 0.18);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        padding: 12px 5%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .admin-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #ffffff;
    }

    .admin-brand img {
        height: 34px;
        width: auto;
        filter: drop-shadow(0 0 8px rgba(51, 163, 255, 0.5));
    }

    .admin-brand span {
        font-family: var(--font-logo);
        font-size: 1.05rem;
        letter-spacing: 1.5px;
    }

    .admin-nav-links {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .admin-nav-item {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.9rem;
        padding: 7px 14px;
        border-radius: 8px;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-nav-item.active,
    .admin-nav-item:hover {
        color: #ffffff;
        background: rgba(51, 163, 255, 0.15);
        border: 1px solid rgba(51, 163, 255, 0.25);
    }

    .admin-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-view-site {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 0.84rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
    }

    .btn-view-site:hover {
        background: rgba(51, 163, 255, 0.2);
        border-color: var(--color-primary);
    }

    .btn-logout {
        color: #f87171;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.25);
        padding: 7px 12px;
        border-radius: 8px;
        font-size: 0.84rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
    }

    .btn-logout:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #ffffff;
    }

    .admin-bottom-dock {
        display: none;
    }

    .admin-mobile-fab {
        display: none;
        position: fixed;
        right: 20px;
        bottom: calc(75px + env(safe-area-inset-bottom, 14px));
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 8px 25px rgba(0, 7, 46, 0.8), 0 0 20px rgba(51, 163, 255, 0.5);
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        text-decoration: none;
        z-index: 998;
        transition: transform 0.25s var(--ease-smooth), box-shadow 0.25s ease;
    }

    .admin-mobile-fab:active {
        transform: scale(0.92);
        box-shadow: 0 4px 14px rgba(51, 163, 255, 0.7);
    }

    @media (max-width: 868px) {
        body {
            padding-bottom: calc(85px + env(safe-area-inset-bottom, 16px)) !important;
        }

        .admin-nav-links {
            display: none !important;
        }

        .admin-navbar {
            padding: 10px 16px;
        }

        .admin-brand img {
            height: 28px;
        }

        .admin-brand span {
            font-size: 0.95rem;
            letter-spacing: 1px;
        }

        .btn-view-site span,
        .btn-logout span {
            display: none;
        }

        .btn-view-site,
        .btn-logout {
            padding: 8px 10px;
            font-size: 0.92rem;
            border-radius: 8px;
        }

        .admin-bottom-dock {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: calc(64px + env(safe-area-inset-bottom, 12px));
            padding-bottom: env(safe-area-inset-bottom, 12px);
            background: rgba(0, 5, 32, 0.95);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-top: 1px solid rgba(51, 163, 255, 0.22);
            box-shadow: 0 -10px 30px rgba(0, 4, 26, 0.65);
            z-index: 1000;
            justify-content: space-around;
            align-items: center;
            box-sizing: border-box;
        }

        .dock-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
            color: rgba(248, 250, 252, 0.5);
            height: 100%;
            transition: all 0.25s ease;
            position: relative;
        }

        .dock-item i {
            font-size: 1.25rem;
            transition: transform 0.25s var(--ease-smooth), color 0.25s ease;
        }

        .dock-item span {
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.4px;
            transition: color 0.25s ease;
        }

        .dock-item.active {
            color: #ffffff;
        }

        .dock-item.active i {
            color: var(--color-primary);
            transform: translateY(-2px) scale(1.12);
            text-shadow: 0 0 12px rgba(51, 163, 255, 0.8);
        }

        .dock-item.active span {
            color: #ffffff;
            font-weight: 600;
        }

        .dock-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: var(--color-primary);
            box-shadow: 0 0 10px var(--color-primary);
            border-radius: 0 0 4px 4px;
        }

        .dock-item:active {
            transform: scale(0.94);
        }

        .admin-mobile-fab {
            display: flex;
        }
    }
</style>

<nav class="admin-navbar">
    <a href="committees.php" class="admin-brand">
        <img src="../logo-transparent.png" alt="SKYMUN" onerror="this.style.display='none'">
        <span>SKYMUN ADMIN</span>
    </a>

    <div class="admin-nav-links">
        <a href="committees.php" class="admin-nav-item <?= $isCommitteeActive ? 'active' : '' ?>">
            <i class="fa-solid fa-landmark"></i> Komiteler
        </a>
        <a href="executives.php" class="admin-nav-item <?= $isExecutiveActive ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i> Executive Ekibi
        </a>
        <a href="applications.php" class="admin-nav-item <?= $isApplicationActive ? 'active' : '' ?>">
            <i class="fa-solid fa-feather-pointed"></i> Başvurular
        </a>
        <a href="contact.php" class="admin-nav-item <?= $isContactActive ? 'active' : '' ?>">
            <i class="fa-solid fa-address-book"></i> İletişim
        </a>
    </div>

    <div class="admin-actions">
        <?php
        $siteTarget = '../index.php';
        if ($isCommitteeActive) $siteTarget = '../committees.php';
        elseif ($isExecutiveActive) $siteTarget = '../executive.php';
        elseif ($isApplicationActive) $siteTarget = '../apply.php';
        elseif ($isContactActive) $siteTarget = '../contact.php';
        ?>
        <a href="<?= $siteTarget ?>" target="_blank" class="btn-view-site" title="Canlı Siteyi Gör">
            <i class="fa-solid fa-arrow-up-right-from-square"></i>
            <span>Siteyi Gör</span>
        </a>
        <a href="logout.php" class="btn-logout" title="Çıkış Yap">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Çıkış</span>
        </a>
    </div>
</nav>

<div class="admin-bottom-dock">
    <a href="committees.php" class="dock-item <?= $isCommitteeActive ? 'active' : '' ?>">
        <i class="fa-solid fa-landmark"></i>
        <span>Komiteler</span>
    </a>
    <a href="executives.php" class="dock-item <?= $isExecutiveActive ? 'active' : '' ?>">
        <i class="fa-solid fa-users"></i>
        <span>Executive</span>
    </a>
    <a href="applications.php" class="dock-item <?= $isApplicationActive ? 'active' : '' ?>">
        <i class="fa-solid fa-feather-pointed"></i>
        <span>Başvurular</span>
    </a>
    <a href="contact.php" class="dock-item <?= $isContactActive ? 'active' : '' ?>">
        <i class="fa-solid fa-address-book"></i>
        <span>İletişim</span>
    </a>
</div>
