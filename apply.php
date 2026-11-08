<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/header.php';

$applications = get_all_applications(true);
?>

<style>
    .applications-hero-section {
        padding: 130px 0 45px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .applications-badge {
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

    .applications-main-title {
        font-family: var(--font-logo);
        font-size: clamp(2.2rem, 4.5vw, 3.2rem);
        font-weight: 700;
        letter-spacing: 2.5px;
        color: #ffffff;
        margin-bottom: 12px;
        text-shadow: 0 0 20px rgba(51, 163, 255, 0.25);
    }

    .applications-subtitle {
        color: var(--color-text-muted);
        font-size: 1.15rem;
        letter-spacing: 0.5px;
        max-width: 520px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .applications-wrapper {
        padding: 30px 0 130px;
        position: relative;
        z-index: 2;
    }

    .applications-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 36px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .editorial-apply-card {
        flex: 0 1 calc(33.333% - 24px);
        max-width: 360px;
        min-width: 290px;
        background: rgba(0, 7, 46, 0.72);
        border: 1px solid rgba(51, 163, 255, 0.18);
        border-radius: 12px;
        padding: 32px 28px 26px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: transform 0.4s var(--ease-smooth), border-color 0.4s ease, box-shadow 0.4s ease;
    }

    .editorial-apply-card::before {
        content: '';
        position: absolute;
        top: -30px;
        right: -30px;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(51, 163, 255, 0.12) 0%, transparent 70%);
        pointer-events: none;
        border-radius: 50%;
        transition: transform 0.6s var(--ease-smooth);
    }

    .editorial-apply-card:hover {
        transform: translateY(-5px);
        border-color: rgba(255, 255, 255, 0.75);
        box-shadow: 0 15px 35px rgba(0, 4, 26, 0.8), 0 0 30px rgba(255, 255, 255, 0.22), 0 0 14px rgba(51, 163, 255, 0.4);
    }

    .editorial-apply-card:hover::before {
        transform: scale(1.35);
    }

    .card-top-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .card-theme-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: rgba(51, 163, 255, 0.08);
        border: 1px solid rgba(51, 163, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-primary);
        font-size: 1.05rem;
        transition: transform 0.4s var(--ease-smooth), color 0.3s ease, border-color 0.3s ease;
    }

    .editorial-apply-card:hover .card-theme-icon {
        transform: rotate(-10deg) scale(1.1);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.6);
        box-shadow: 0 0 14px rgba(51, 163, 255, 0.5);
    }

    .card-price-pill {
        display: inline-block;
        padding: 5px 14px;
        background: rgba(29, 11, 121, 0.35);
        border: 1px solid rgba(51, 163, 255, 0.3);
        border-radius: 20px;
        font-family: var(--font-logo);
        font-size: 0.88rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: #ffffff;
        text-shadow: 0 0 10px rgba(51, 163, 255, 0.4);
    }

    .card-apply-title {
        font-family: var(--font-logo);
        font-size: 1.4rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: #ffffff;
        margin-bottom: 12px;
        line-height: 1.35;
        transition: color 0.3s ease;
    }

    .editorial-apply-card:hover .card-apply-title {
        color: #ffffff;
        text-shadow: 0 0 12px rgba(255, 255, 255, 0.4);
    }

    .card-apply-desc {
        color: var(--color-text-muted);
        font-size: 0.95rem;
        line-height: 1.6;
        letter-spacing: 0.3px;
        margin-bottom: 26px;
    }

    .card-action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 18px;
        border-top: 1px solid rgba(51, 163, 255, 0.1);
        transition: border-color 0.3s ease;
    }

    .editorial-apply-card:hover .card-action-bar {
        border-top-color: rgba(255, 255, 255, 0.2);
    }

    .action-text {
        font-family: var(--font-logo);
        font-size: 0.92rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
    }

    .editorial-apply-card:hover .action-text {
        color: var(--color-primary);
    }

    .action-arrow {
        font-size: 1.35rem;
        color: rgba(248, 250, 252, 0.65);
        line-height: 1;
        display: inline-block;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), color 0.3s ease;
    }

    .editorial-apply-card:hover .action-arrow {
        transform: translate(4px, -4px);
        color: #ffffff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
    }

    @media (max-width: 992px) {
        .editorial-apply-card {
            flex: 0 1 calc(50% - 20px);
            max-width: 360px;
        }
    }

    @media (max-width: 680px) {
        .applications-hero-section {
            padding: 110px 0 35px;
        }

        .applications-wrapper {
            padding: 20px 0 90px;
        }

        .editorial-apply-card {
            flex: 0 1 100%;
            max-width: 360px;
        }
    }
</style>

<section class="applications-hero-section">
    <div class="container">
        <div class="applications-badge">
            <i class="fa-solid fa-rocket"></i>
            <span>Registration</span>
        </div>
        <h1 class="applications-main-title">APPLICATIONS</h1>
        <p class="applications-subtitle">Your place at SKYMUN starts here.</p>
    </div>
</section>

<section class="applications-wrapper">
    <div class="container">
        <?php if (empty($applications)): ?>
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <p>Applications will open soon. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="applications-container">
                <?php 
                $icons = ['fa-solid fa-user', 'fa-solid fa-users', 'fa-solid fa-satellite', 'fa-solid fa-award', 'fa-solid fa-feather-pointed'];
                $index = 0;
                foreach ($applications as $app): 
                    $iconClass = $icons[$index % count($icons)];
                    $index++;
                ?>
                    <a href="<?= htmlspecialchars($app['apply_url']) ?>" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="editorial-apply-card">
                        
                        <div>
                            <div class="card-top-meta">
                                <div class="card-theme-icon">
                                    <i class="<?= $iconClass ?>"></i>
                                </div>
                                <?php if (!empty($app['price'])): ?>
                                    <span class="card-price-pill"><?= htmlspecialchars($app['price']) ?></span>
                                <?php endif; ?>
                            </div>

                            <h2 class="card-apply-title"><?= htmlspecialchars($app['title']) ?></h2>

                            <?php if (!empty($app['description'])): ?>
                                <p class="card-apply-desc"><?= htmlspecialchars($app['description']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="card-action-bar">
                            <span class="action-text">APPLY NOW</span>
                            <span class="action-arrow">&#x2197;</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
require_once __DIR__ . '/footer.php';
?>
