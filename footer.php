<?php
$footerSettings = function_exists('get_contact_settings') ? get_contact_settings() : [];
$footerMail = (!empty($footerSettings['mail']['channel_value'])) ? $footerSettings['mail']['channel_value'] : CONTACT_EMAIL;
$footerMailActive = isset($footerSettings['mail']) ? ($footerSettings['mail']['status'] == 1) : true;
$footerIg = $footerSettings['instagram'] ?? null;
$footerTiktok = $footerSettings['tiktok'] ?? null;
$footerPhone = $footerSettings['phone'] ?? null;
?>
<style>
    footer.skymun-footer {
        background: linear-gradient(180deg, rgba(0, 7, 46, 0.5) 0%, rgba(0, 4, 26, 0.98) 100%);
        border-top: 1px solid rgba(51, 163, 255, 0.1);
        padding: 60px 0 35px;
        position: relative;
        z-index: 2;
    }

    .footer-layout {
        display: grid;
        grid-template-columns: 1.2fr 0.9fr 1.2fr;
        gap: 40px;
        align-items: center;
        margin-bottom: 45px;
    }

    .footer-left {
        text-align: left;
    }

    .footer-brand-name {
        font-family: var(--font-logo);
        font-size: 1.7rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #ffffff;
        margin-bottom: 6px;
        text-shadow: 0 0 16px rgba(51, 163, 255, 0.3);
    }

    .footer-tagline {
        font-size: 0.98rem;
        color: var(--color-primary);
        font-weight: 500;
        letter-spacing: 1.2px;
        margin-bottom: 12px;
    }

    .footer-statement {
        color: var(--color-text-muted);
        font-size: 0.92rem;
        line-height: 1.65;
        max-width: 360px;
    }

    .footer-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .footer-logo-img {
        max-height: 160px;
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 0 24px rgba(51, 163, 255, 0.45));
        transition: transform 0.4s var(--ease-smooth), filter 0.4s var(--ease-smooth);
    }

    .footer-logo-img:hover {
        transform: scale(1.05);
        filter: drop-shadow(0 0 32px rgba(51, 163, 255, 0.7));
    }

    .footer-right {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
    }

    .footer-label {
        font-size: 0.8rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(248, 250, 252, 0.5);
        margin-bottom: 4px;
        font-weight: 600;
    }

    .footer-contact-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--color-text-muted);
        font-size: 0.95rem;
        transition: color 0.25s ease, transform 0.25s ease;
    }

    .footer-contact-link i {
        color: var(--color-primary);
        font-size: 0.95rem;
    }

    .footer-contact-link:hover {
        color: #ffffff;
        transform: translateX(-3px);
    }

    .footer-social-row {
        display: flex;
        gap: 12px;
        margin-top: 6px;
    }

    .footer-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(51, 163, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(248, 250, 252, 0.7);
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .footer-icon-btn:hover {
        background: rgba(51, 163, 255, 0.15);
        color: #ffffff;
        border-color: var(--color-primary);
        box-shadow: 0 0 12px rgba(51, 163, 255, 0.4);
        transform: translateY(-2px);
    }

    .footer-subbar {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        padding-top: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        color: var(--color-text-subtle);
        font-size: 0.85rem;
        letter-spacing: 0.4px;
    }

    .footer-subbar a:hover {
        color: var(--color-primary);
    }

    .designer-signature-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .designer-signature-text {
        font-size: 0.78rem;
        color: rgba(248, 250, 252, 0.45);
        letter-spacing: 0.3px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .designer-trigger {
        background: transparent;
        border: none;
        padding: 0;
        margin: 0;
        cursor: pointer;
        font-family: inherit;
        font-size: inherit;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: rgba(248, 250, 252, 0.85);
        transition: color 0.25s ease;
        text-decoration: none;
    }

    .designer-name {
        font-weight: 500;
        position: relative;
        transition: color 0.25s ease, text-shadow 0.25s ease;
    }

    .designer-trigger:hover .designer-name,
    .designer-trigger.is-active .designer-name {
        color: #ffffff;
        text-shadow: 0 0 10px rgba(51, 163, 255, 0.5);
    }

    .designer-arrow {
        font-size: 0.82rem;
        line-height: 1;
        opacity: 0;
        transform: translate(-3px, 3px);
        transition: opacity 0.25s ease, transform 0.25s var(--ease-smooth), color 0.25s ease;
        color: var(--color-primary);
        display: inline-block;
    }

    .designer-trigger:hover .designer-arrow,
    .designer-trigger.is-active .designer-arrow {
        opacity: 1;
        transform: translate(0, 0);
    }

    .designer-card-popover {
        position: absolute;
        bottom: calc(100% + 14px);
        left: 50%;
        transform: translateX(-50%) translateY(-8px);
        width: 270px;
        max-width: calc(100vw - 32px);
        box-sizing: border-box;
        background: rgba(4, 9, 38, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(51, 163, 255, 0.28);
        border-radius: 14px;
        box-shadow: 0 18px 50px rgba(0, 4, 26, 0.8), 0 0 25px rgba(51, 163, 255, 0.15);
        padding: 22px 20px 18px;
        z-index: 1000;
        text-align: left;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.3s var(--ease-smooth), transform 0.3s var(--ease-smooth), visibility 0.3s var(--ease-smooth);
    }

    .designer-card-popover.is-open {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }

    .designer-popover-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2px;
    }

    .designer-popover-title {
        font-family: var(--font-main);
        font-size: 1.05rem;
        font-weight: 600;
        color: #ffffff;
        letter-spacing: 0.3px;
        line-height: 1.2;
    }

    .designer-popover-close {
        background: transparent;
        border: none;
        color: rgba(248, 250, 252, 0.45);
        font-size: 1.3rem;
        line-height: 1;
        cursor: pointer;
        padding: 0 2px;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .designer-popover-close:hover {
        color: #ffffff;
        transform: scale(1.15);
    }

    .designer-popover-subtitle {
        font-family: var(--font-logo);
        font-size: 0.74rem;
        color: var(--color-primary);
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 3px;
    }

    .designer-popover-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
        margin: 14px 0 12px;
    }

    .designer-popover-links {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .designer-popover-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        color: rgba(248, 250, 252, 0.75);
        text-decoration: none;
        font-size: 0.88rem;
        letter-spacing: 0.3px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        transition: color 0.2s ease, padding-left 0.2s ease;
    }

    .designer-popover-link:last-child {
        border-bottom: none;
    }

    .designer-popover-link span.link-arrow {
        font-size: 0.92rem;
        color: rgba(248, 250, 252, 0.4);
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .designer-popover-link:hover {
        color: #ffffff;
        padding-left: 3px;
    }

    .designer-popover-link:hover span.link-arrow {
        color: var(--color-primary);
        transform: translate(2px, -2px);
    }

    .designer-popover-note {
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 0.76rem;
        color: rgba(248, 250, 252, 0.42);
        font-style: italic;
        letter-spacing: 0.4px;
        text-align: center;
    }

    @media (max-width: 900px) {
        .footer-layout {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 32px;
        }

        .footer-left, .footer-right {
            text-align: center;
            align-items: center;
        }

        .footer-statement {
            margin: 0 auto;
        }

        .footer-subbar {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<footer class="skymun-footer">
    <div class="container">
        <div class="footer-layout">
            <div class="footer-left">
                <div class="footer-brand-name"><?= htmlspecialchars(SITE_NAME) ?></div>
                <div class="footer-tagline"><?= htmlspecialchars(SITE_MOTTO) ?></div>
                <p class="footer-statement">
                    Empowering tomorrow's leaders through multilateral diplomacy, structured debate, and visionary global problem-solving.
                </p>
            </div>

            <div class="footer-center">
                <img src="logo-transparent.png" alt="<?= htmlspecialchars(SITE_NAME) ?> Transparent Logo" class="footer-logo-img">
            </div>

            <div class="footer-right">
                <div class="footer-label">Inquiries & Contact</div>
                <?php if ($footerMailActive): ?>
                    <a href="mailto:<?= htmlspecialchars($footerMail) ?>" class="footer-contact-link">
                        <i class="fa-solid fa-envelope"></i>
                        <span><?= htmlspecialchars($footerMail) ?></span>
                    </a>
                <?php endif; ?>
                <div class="footer-contact-link">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span><?= date('F d, Y', strtotime(CONFERENCE_DATE)) ?></span>
                </div>
                <div class="footer-social-row">
                    <?php if (!empty($footerIg['status'])): ?>
                        <a href="<?= htmlspecialchars($footerIg['channel_value']) ?>" target="_blank" rel="noopener noreferrer" class="footer-icon-btn" aria-label="Instagram" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($footerTiktok['status'])): ?>
                        <a href="<?= htmlspecialchars($footerTiktok['channel_value']) ?>" target="_blank" rel="noopener noreferrer" class="footer-icon-btn" aria-label="TikTok" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($footerPhone['status'])): ?>
                        <a href="contact.php" class="footer-icon-btn" aria-label="Phone Directory" title="Phone Directory / Support"><i class="fa-solid fa-phone"></i></a>
                    <?php endif; ?>
                    <?php if ($footerMailActive): ?>
                        <a href="mailto:<?= htmlspecialchars($footerMail) ?>" class="footer-icon-btn" aria-label="E-Mail" title="E-Mail"><i class="fa-solid fa-envelope"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="footer-subbar">
            <div>
                &copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?>. All rights reserved.
            </div>

            <div class="designer-signature-wrap">
                <span class="designer-signature-text">
                    Site designed and developed by
                    <button type="button" class="designer-trigger" id="designerCardTrigger" aria-label="Open designer contact card">
                        <span class="designer-name">Eymen Çapoğlu</span>
                        <span class="designer-arrow">&#x2197;</span>
                    </button>
                </span>

                <div class="designer-card-popover" id="designerCardPopover" role="dialog" aria-hidden="true">
                    <div class="designer-popover-header">
                        <div class="designer-popover-title">Eymen Çapoğlu</div>
                        <button type="button" class="designer-popover-close" id="designerCardClose" aria-label="Close">&times;</button>
                    </div>
                    <div class="designer-popover-subtitle">Design &amp; Development</div>
                    
                    <div class="designer-popover-divider"></div>

                    <div class="designer-popover-links">
                        <a href="https://github.com" target="_blank" rel="noopener noreferrer" class="designer-popover-link">
                            <span>GitHub</span>
                            <span class="link-arrow">&#x2197;</span>
                        </a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="designer-popover-link">
                            <span>LinkedIn</span>
                            <span class="link-arrow">&#x2197;</span>
                        </a>
                        <a href="https://instagram.com/skymun" target="_blank" rel="noopener noreferrer" class="designer-popover-link">
                            <span>Instagram</span>
                            <span class="link-arrow">&#x2197;</span>
                        </a>
                        <a href="mailto:info@skymun.org" class="designer-popover-link">
                            <span>Email</span>
                            <span class="link-arrow">&#x2197;</span>
                        </a>
                    </div>

                    <div class="designer-popover-note">
                        Available for creative work
                    </div>
                </div>
            </div>

            <div>
                Diplomacy &middot; Leadership &middot; Global Consensus
            </div>
        </div>
    </div>
</footer>

<script>
    (function() {
        const trigger = document.getElementById('designerCardTrigger');
        const popover = document.getElementById('designerCardPopover');
        const closeBtn = document.getElementById('designerCardClose');

        if (!trigger || !popover) return;

        function togglePopover(e) {
            e.stopPropagation();
            const isOpen = popover.classList.contains('is-open');
            if (isOpen) {
                closePopover();
            } else {
                openPopover();
            }
        }

        function openPopover() {
            popover.classList.add('is-open');
            trigger.classList.add('is-active');
            popover.setAttribute('aria-hidden', 'false');
        }

        function closePopover() {
            popover.classList.remove('is-open');
            trigger.classList.remove('is-active');
            popover.setAttribute('aria-hidden', 'true');
        }

        trigger.addEventListener('click', togglePopover);
        if (closeBtn) closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            closePopover();
        });

        document.addEventListener('click', function(e) {
            if (!popover.contains(e.target) && !trigger.contains(e.target)) {
                closePopover();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && popover.classList.contains('is-open')) {
                closePopover();
            }
        });
    })();
</script>

</body>
</html>
