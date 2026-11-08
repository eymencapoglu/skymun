<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/header.php';

$contactSettings = get_contact_settings();
$activePhoneContacts = get_all_contact_phones(true);

$ig = $contactSettings['instagram'] ?? ['channel_value' => '#', 'status' => 1];
$mail = $contactSettings['mail'] ?? ['channel_value' => 'info@example.com', 'status' => 1];
$phone = $contactSettings['phone'] ?? ['channel_value' => 'Direct Contacts', 'status' => 0];
$tiktok = $contactSettings['tiktok'] ?? ['channel_value' => '#', 'status' => 1];

function clean_handle($url, $default) {
    if (empty($url) || $url === '#') return $default;
    $parsed = parse_url($url, PHP_URL_PATH);
    if ($parsed) {
        $trimmed = trim($parsed, '/');
        if (!empty($trimmed)) {
            return (strpos($trimmed, '@') === 0) ? $trimmed : '@' . $trimmed;
        }
    }
    return $default;
}

$igHandle = clean_handle($ig['channel_value'], '@munconference');
$tiktokHandle = clean_handle($tiktok['channel_value'], '@munconference');
?>

<style>
    .contact-hero-section {
        padding: 130px 0 45px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .contact-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: var(--font-logo);
        font-size: 0.8rem;
        letter-spacing: 2px;
        color: var(--color-primary);
        text-transform: uppercase;
        margin-bottom: 14px;
        background: rgba(51, 163, 255, 0.08);
        border: 1px solid rgba(51, 163, 255, 0.2);
        padding: 6px 16px;
        border-radius: 20px;
    }

    .contact-main-title {
        font-family: var(--font-logo);
        font-size: clamp(2.2rem, 4.5vw, 3.2rem);
        font-weight: 700;
        letter-spacing: 2.5px;
        color: #ffffff;
        margin-bottom: 12px;
        text-shadow: 0 0 20px rgba(51, 163, 255, 0.25);
    }

    .contact-subtitle {
        color: var(--color-text-muted);
        font-size: 1.15rem;
        letter-spacing: 0.5px;
        max-width: 540px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .contact-wrapper {
        padding: 30px 0 130px;
        position: relative;
        z-index: 2;
    }

    .contact-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
        max-width: 1040px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .contact-functional-card {
        background: rgba(0, 7, 46, 0.4);
        border: 1px solid rgba(51, 163, 255, 0.16);
        border-radius: 12px;
        padding: 32px 28px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 140px;
        text-decoration: none;
        color: #ffffff;
        position: relative;
        cursor: pointer;
        box-shadow: none;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        transition: border-color 0.3s ease, background 0.3s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .contact-functional-card:hover {
        border-color: rgba(255, 255, 255, 0.35);
        background: rgba(4, 12, 54, 0.65);
        transform: translateY(-3px);
    }

    .contact-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
    }

    .contact-card-label {
        font-family: var(--font-logo);
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(248, 250, 252, 0.7);
        transition: color 0.3s ease;
    }

    .contact-functional-card:hover .contact-card-label {
        color: #ffffff;
    }

    .contact-card-arrow {
        font-size: 1.25rem;
        color: rgba(248, 250, 252, 0.45);
        line-height: 1;
        display: inline-block;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), color 0.3s ease;
    }

    .contact-functional-card:hover .contact-card-arrow {
        transform: translate(3px, -3px);
        color: #ffffff;
    }

    .contact-card-value {
        font-family: var(--font-main);
        font-size: 1.15rem;
        font-weight: 500;
        color: #ffffff;
        letter-spacing: 0.2px;
        line-height: 1.35;
        word-break: break-word;
    }

    .phone-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 4, 26, 0.82);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .phone-modal-overlay.is-active {
        opacity: 1;
        visibility: visible;
    }

    .phone-modal-sheet {
        background: rgba(0, 7, 46, 0.96);
        border: 1px solid rgba(51, 163, 255, 0.22);
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0, 4, 26, 0.8);
        width: 100%;
        max-width: 440px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 34px 30px 24px;
        transform: scale(0.95);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .phone-modal-overlay.is-active .phone-modal-sheet {
        transform: scale(1);
    }

    .bottom-sheet-handle {
        display: none;
    }

    .modal-team-header {
        margin-bottom: 24px;
        text-align: left;
    }

    .modal-team-title {
        font-family: var(--font-logo);
        font-size: 1.15rem;
        letter-spacing: 2px;
        color: #ffffff;
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .modal-team-sub {
        font-family: var(--font-main);
        font-size: 0.92rem;
        color: var(--color-text-muted);
        line-height: 1.45;
    }

    .modal-team-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 48vh;
        overflow-y: auto;
        padding-right: 4px;
    }

    .modal-team-list::-webkit-scrollbar {
        width: 4px;
    }
    .modal-team-list::-webkit-scrollbar-thumb {
        background: rgba(51, 163, 255, 0.25);
        border-radius: 2px;
    }

    .modal-member-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 18px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(51, 163, 255, 0.12);
        text-decoration: none;
        color: #ffffff;
        transition: all 0.25s ease;
    }

    .modal-member-row:hover {
        background: rgba(51, 163, 255, 0.08);
        border-color: rgba(51, 163, 255, 0.35);
        transform: translateX(3px);
    }

    .modal-member-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .modal-member-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #ffffff;
        letter-spacing: 0.3px;
    }

    .modal-member-name {
        font-size: 0.8rem;
        color: var(--color-primary);
        letter-spacing: 0.4px;
    }

    .modal-member-arrow {
        font-size: 1.15rem;
        color: rgba(248, 250, 252, 0.45);
        line-height: 1;
        transition: transform 0.25s ease, color 0.25s ease;
    }

    .modal-member-row:hover .modal-member-arrow {
        transform: translate(3px, -3px);
        color: #ffffff;
    }

    .modal-close-wrap {
        text-align: center;
        margin-top: 24px;
        padding-top: 14px;
        border-top: 1px solid rgba(51, 163, 255, 0.1);
    }

    .modal-close-text-btn {
        background: transparent;
        border: none;
        font-family: var(--font-logo);
        font-size: 0.82rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(248, 250, 252, 0.55);
        cursor: pointer;
        padding: 8px 18px;
        transition: color 0.25s ease;
    }

    .modal-close-text-btn:hover {
        color: #ffffff;
    }

    .phone-empty-state {
        text-align: center;
        padding: 30px 10px;
        color: #94a3b8;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .contact-hero-section {
            padding: 110px 0 30px;
        }

        .contact-wrapper {
            padding: 20px 0 90px;
        }

        .contact-container {
            grid-template-columns: 1fr;
            gap: 14px;
            padding: 0 16px;
        }

        .contact-functional-card {
            padding: 26px 22px;
            min-height: auto;
        }

        .phone-modal-overlay {
            align-items: flex-end;
            padding: 0;
        }

        .phone-modal-sheet {
            max-width: 100%;
            border-radius: 20px 20px 0 0;
            border-bottom: none;
            padding: 14px 22px calc(24px + env(safe-area-inset-bottom, 16px));
            transform: translateY(100%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .phone-modal-overlay.is-active .phone-modal-sheet {
            transform: translateY(0);
        }

        .bottom-sheet-handle {
            display: block;
            width: 38px;
            height: 4px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.25);
            margin: 0 auto 18px;
        }
    }
</style>

<section class="contact-hero-section">
    <div class="container">
        <div class="contact-badge">
            <i class="fa-solid fa-headset"></i>
            <span>Get in Touch</span>
        </div>
        <h1 class="contact-main-title">CONTACT</h1>
        <p class="contact-subtitle">
            Direct communication channels for delegates, advisors, and conference partners.
        </p>
    </div>
</section>

<section class="contact-wrapper">
    <div class="container">
        <div class="contact-container">

            <?php if (!empty($ig['status'])): ?>
                <div class="contact-functional-card">
                    <div class="contact-card-top">
                        <span class="contact-card-label">Instagram</span>
                        <span class="contact-card-arrow" aria-hidden="true">&#x2197;</span>
                    </div>
                    <div class="contact-card-value"><?= htmlspecialchars($igHandle) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($mail['status'])): ?>
                <div class="contact-functional-card">
                    <div class="contact-card-top">
                        <span class="contact-card-label">Email</span>
                        <span class="contact-card-arrow" aria-hidden="true">&#x2197;</span>
                    </div>
                    <div class="contact-card-value"><?= htmlspecialchars($mail['channel_value']) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($tiktok['status'])): ?>
                <div class="contact-functional-card">
                    <div class="contact-card-top">
                        <span class="contact-card-label">TikTok</span>
                        <span class="contact-card-arrow" aria-hidden="true">&#x2197;</span>
                    </div>
                    <div class="contact-card-value"><?= htmlspecialchars($tiktokHandle) ?></div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<div class="phone-modal-overlay" id="phoneModalOverlay" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="phone-modal-sheet">
        <div class="bottom-sheet-handle"></div>

        <div class="modal-team-header">
            <h3 class="modal-team-title">CONTACT THE TEAM</h3>
            <p class="modal-team-sub">Who would you like to reach?</p>
        </div>

        <div class="modal-team-list">
            <?php if (empty($activePhoneContacts)): ?>
                <div class="phone-empty-state">
                    <p>No direct telephone contacts are listed at this moment.<br>Please contact us via official email.</p>
                </div>
            <?php else: ?>
                <?php foreach ($activePhoneContacts as $pc): 
                    $cleanTel = preg_replace('/[^0-9+]/', '', $pc['phone_number']);
                ?>
                    <a href="tel:<?= htmlspecialchars($cleanTel) ?>" class="modal-member-row">
                        <div class="modal-member-info">
                            <span class="modal-member-title"><?= htmlspecialchars($pc['title']) ?></span>
                            <?php if (!empty($pc['name'])): ?>
                                <span class="modal-member-name"><?= htmlspecialchars($pc['name']) ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="modal-member-arrow" aria-hidden="true">&#x2197;</span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="modal-close-wrap">
            <button type="button" class="modal-close-text-btn" id="closePhoneModalBtn">CLOSE &times;</button>
        </div>

    </div>
</div>

<script>
    (function() {
        const openBtn = document.getElementById('openPhoneModalBtn');
        const closeBtn = document.getElementById('closePhoneModalBtn');
        const modal = document.getElementById('phoneModalOverlay');

        function openDirectory() {
            if (!modal) return;
            modal.classList.add('is-active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeDirectory() {
            if (!modal) return;
            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        if (openBtn) {
            openBtn.addEventListener('click', openDirectory);
            openBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openDirectory();
                }
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeDirectory);
        }

        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeDirectory();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                    closeDirectory();
                }
            });
        }
    })();
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
