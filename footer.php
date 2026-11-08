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
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .designer-signature-text {
        font-family: var(--font-logo);
        font-size: 0.78rem;
        color: rgba(248, 250, 252, 0.45);
        letter-spacing: 1.5px;
        text-transform: uppercase;
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
                        <a href="<?= htmlspecialchars($footerIg['channel_value']) ?>" class="footer-icon-btn" aria-label="Instagram" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($footerTiktok['status'])): ?>
                        <a href="<?= htmlspecialchars($footerTiktok['channel_value']) ?>" class="footer-icon-btn" aria-label="TikTok" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
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
                    Model United Nations Platform
                </span>
            </div>

            <div>
                Diplomacy &middot; Leadership &middot; Global Consensus
            </div>
        </div>
    </div>
</footer>

</body>
</html>
