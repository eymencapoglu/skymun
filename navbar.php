<?php
?>
<style>
    nav.skymun-navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 76px;
        box-sizing: border-box;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 5%;
        background: rgba(0, 7, 46, 0.75);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(51, 163, 255, 0.08);
        z-index: 1000;
        transition: all 0.4s var(--ease-smooth);
    }

    nav.skymun-navbar.scrolled {
        height: 68px;
        background: rgba(0, 7, 46, 0.92);
        border-bottom-color: rgba(51, 163, 255, 0.16);
        box-shadow: 0 10px 30px rgba(0, 4, 26, 0.6);
    }

    
    .nav-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        cursor: pointer;
    }

    .nav-brand-logo {
        height: 46px;
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 0 10px rgba(51, 163, 255, 0.3));
        transition: transform 0.3s ease, filter 0.3s ease;
    }

    .nav-brand:hover .nav-brand-logo {
        transform: scale(1.04);
        filter: drop-shadow(0 0 14px rgba(51, 163, 255, 0.5));
    }

    .nav-brand-text {
        font-family: var(--font-logo);
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #ffffff;
        text-shadow: 0 0 18px rgba(51, 163, 255, 0.3);
    }

    .nav-links-desktop {
        display: flex;
        align-items: center;
        gap: 36px;
    }

    .nav-item {
        position: relative;
        font-size: 0.95rem;
        font-weight: 400;
        color: rgba(248, 250, 252, 0.78);
        text-decoration: none;
        padding: 6px 0;
        letter-spacing: 0.8px;
        transition: color 0.3s ease;
        cursor: pointer;
    }

    .nav-item::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 1.5px;
        background: var(--color-primary);
        box-shadow: 0 0 8px rgba(51, 163, 255, 0.8);
        transition: width 0.35s var(--ease-smooth);
        border-radius: 1px;
    }

    .nav-item:hover {
        color: #ffffff;
    }

    .nav-item:hover::after,
    .nav-item.active::after {
        width: 100%;
    }

    .nav-toggle-btn {
        display: none;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        width: 38px;
        height: 38px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 6px;
        z-index: 1001;
    }

    .nav-toggle-btn span {
        display: block;
        width: 100%;
        height: 2px;
        background: #ffffff;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 7, 46, 0.84);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.4s var(--ease-smooth), visibility 0.4s var(--ease-smooth);
    }

    .mobile-menu-overlay.is-open {
        opacity: 1;
        visibility: visible;
    }

    .mobile-close-btn {
        position: absolute;
        top: 26px;
        right: 6%;
        background: transparent;
        border: none;
        color: rgba(248, 250, 252, 0.8);
        font-size: 1.8rem;
        cursor: pointer;
        padding: 8px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .mobile-close-btn:hover {
        color: var(--color-primary);
        transform: rotate(90deg);
    }

    .mobile-nav-list {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 30px;
        width: 100%;
        max-width: 320px;
    }

    .mobile-nav-item {
        display: inline-block;
        color: rgba(248, 250, 252, 0.78);
        font-size: 1.6rem;
        font-weight: 400;
        letter-spacing: 1.8px;
        text-decoration: none;
        padding: 4px 10px;
        position: relative;
        opacity: 0;
        transform: translateY(16px);
        transition: color 0.3s ease, transform 0.3s ease;
        text-align: center;
    }

    .mobile-nav-item:hover,
    .mobile-nav-item.active {
        color: #ffffff;
        transform: scale(1.04);
    }

    .mobile-nav-item::after {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 1.5px;
        background: var(--color-primary);
        box-shadow: 0 0 10px var(--color-primary);
        transition: width 0.3s var(--ease-smooth);
    }

    .mobile-nav-item:hover::after,
    .mobile-nav-item.active::after {
        width: 50%;
    }

    .mobile-menu-overlay.is-open .mobile-nav-item {
        opacity: 1;
        transform: translateY(0);
    }

    .mobile-menu-overlay.is-open .mobile-nav-item:nth-child(1) { transition: all 0.4s var(--ease-smooth) 0.08s; }
    .mobile-menu-overlay.is-open .mobile-nav-item:nth-child(2) { transition: all 0.4s var(--ease-smooth) 0.14s; }
    .mobile-menu-overlay.is-open .mobile-nav-item:nth-child(3) { transition: all 0.4s var(--ease-smooth) 0.20s; }
    .mobile-menu-overlay.is-open .mobile-nav-item:nth-child(4) { transition: all 0.4s var(--ease-smooth) 0.26s; }
    .mobile-menu-overlay.is-open .mobile-nav-item:nth-child(5) { transition: all 0.4s var(--ease-smooth) 0.32s; }

    @media (max-width: 868px) {
        .nav-links-desktop {
            display: none;
        }

        .nav-toggle-btn {
            display: flex;
        }

        nav.skymun-navbar {
            height: 68px;
            padding: 0 5%;
        }

        .nav-brand-logo {
            height: 40px;
        }

        .nav-brand-text {
            font-size: 1.2rem;
        }
    }
</style>

<nav class="skymun-navbar" id="skymunNavbar">
    <a href="index.php" class="nav-brand" title="<?= htmlspecialchars(SITE_NAME) ?>">
        <img src="logo-transparent.png" alt="<?= htmlspecialchars(SITE_NAME) ?> Logo" class="nav-brand-logo" onerror="this.style.display='none'">
        <span class="nav-brand-text"><?= htmlspecialchars(SITE_NAME) ?></span>
    </a>

    <?php
    $currentPage = basename($_SERVER['PHP_SELF'] ?? '');
    ?>
    <div class="nav-links-desktop">
        <a href="index.php" class="nav-item <?= ($currentPage === 'index.php' || $currentPage === '') ? 'active' : '' ?>">Home</a>
        <a href="committees.php" class="nav-item <?= (strpos($currentPage, 'committee') !== false) ? 'active' : '' ?>">Committees</a>
        <a href="executive.php" class="nav-item <?= ($currentPage === 'executive.php') ? 'active' : '' ?>">Executive</a>
        <a href="apply.php" class="nav-item <?= ($currentPage === 'apply.php') ? 'active' : '' ?>">Apply</a>
        <a href="contact.php" class="nav-item <?= ($currentPage === 'contact.php') ? 'active' : '' ?>">Contact</a>
    </div>

    <button class="nav-toggle-btn" id="navToggleBtn" aria-label="Open Navigation">
        <span></span>
        <span></span>
        <span></span>
    </button>
</nav>

<div class="mobile-menu-overlay" id="mobileMenuOverlay">
    <button class="mobile-close-btn" id="mobileCloseBtn" aria-label="Close Menu">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <div class="mobile-nav-list">
        <a href="index.php" class="mobile-nav-item <?= ($currentPage === 'index.php' || $currentPage === '') ? 'active' : '' ?>">Home</a>
        <a href="committees.php" class="mobile-nav-item <?= (strpos($currentPage, 'committee') !== false) ? 'active' : '' ?>">Committees</a>
        <a href="executive.php" class="mobile-nav-item <?= ($currentPage === 'executive.php') ? 'active' : '' ?>">Executive</a>
        <a href="apply.php" class="mobile-nav-item <?= ($currentPage === 'apply.php') ? 'active' : '' ?>">Apply</a>
        <a href="contact.php" class="mobile-nav-item <?= ($currentPage === 'contact.php') ? 'active' : '' ?>">Contact</a>
    </div>
</div>

<script>
    (function() {
        const navbar = document.getElementById('skymunNavbar');
        const toggleBtn = document.getElementById('navToggleBtn');
        const closeBtn = document.getElementById('mobileCloseBtn');
        const menuOverlay = document.getElementById('mobileMenuOverlay');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 25) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        function openMenu() {
            menuOverlay.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            menuOverlay.classList.remove('is-open');
            document.body.style.overflow = '';
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);

        const mobileLinks = menuOverlay.querySelectorAll('.mobile-nav-item');
        mobileLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        menuOverlay.addEventListener('click', function(e) {
            if (e.target === menuOverlay) {
                closeMenu();
            }
        });
    })();
</script>
