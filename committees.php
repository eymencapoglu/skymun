<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/header.php';

$committees = get_all_committees(true);
?>

<style>
    .committees-hero-section {
        padding: 130px 0 50px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .committees-main-title {
        font-family: var(--font-logo);
        font-size: 2.6rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #ffffff;
        margin-bottom: 10px;
        text-shadow: 0 0 25px rgba(51, 163, 255, 0.3);
    }

    .committees-subtitle {
        color: var(--color-text-muted);
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }

    .committees-wrapper {
        padding: 30px 0 130px;
        position: relative;
        z-index: 2;
    }

    .committees-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 36px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .editorial-committee-card {
        flex: 0 1 calc(33.333% - 24px);
        max-width: 360px;
        min-width: 290px;
        background: rgba(0, 7, 46, 0.6);
        border: 1px solid rgba(51, 163, 255, 0.16);
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        position: relative;
        cursor: pointer;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        transition: border-color 0.3s ease;
    }

    .editorial-committee-card:hover {
        border-color: rgba(255, 255, 255, 0.35);
    }

    .card-media-box {
        width: 100%;
        height: 250px;
        overflow: hidden;
        position: relative;
        background: linear-gradient(145deg, rgba(29, 11, 121, 0.4) 0%, #00072e 100%);
    }

    .card-media-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        transform: scale(1);
    }

    .editorial-committee-card:hover .card-media-img {
        transform: scale(1.04);
    }

    .card-media-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(51, 163, 255, 0.22);
        font-size: 3.2rem;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .editorial-committee-card:hover .card-media-placeholder {
        transform: scale(1.04);
    }

    .card-content-box {
        padding: 24px 22px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        border-top: 1px solid rgba(51, 163, 255, 0.08);
    }

    .card-text-top {
        margin-bottom: 22px;
    }

    .card-acronym {
        font-family: var(--font-logo);
        font-size: 1.45rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 1.5px;
        margin-bottom: 6px;
        line-height: 1.2;
    }

    .card-fullname {
        font-size: 0.95rem;
        color: var(--color-text-muted);
        line-height: 1.5;
        font-weight: 400;
    }

    .card-action-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }

    .card-arrow {
        font-size: 1.35rem;
        color: rgba(248, 250, 252, 0.65);
        line-height: 1;
        display: inline-block;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), color 0.3s ease;
    }

    .editorial-committee-card:hover .card-arrow {
        transform: translate(5px, -3px);
        color: #ffffff;
    }

    @media (max-width: 960px) {
        .editorial-committee-card {
            flex: 0 1 calc(50% - 20px);
            max-width: 420px;
        }
    }

    @media (max-width: 640px) {
        .committees-hero-section {
            padding-top: 110px;
        }
        .committees-main-title {
            font-size: 2rem;
        }
        .editorial-committee-card {
            flex: 0 1 100%;
            max-width: 100%;
        }
    }
</style>

<section class="committees-hero-section">
    <div class="container">
        <h1 class="committees-main-title">COMMITTEES</h1>
        <p class="committees-subtitle">Explore the committees of <?= htmlspecialchars(SITE_NAME) ?>.</p>
    </div>
</section>

<section class="committees-wrapper">
    <div class="container">
        <?php if (empty($committees)): ?>
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <p>No committees have been published yet. Please check back soon.</p>
            </div>
        <?php else: ?>
            <div class="committees-container">
                <?php foreach ($committees as $com): ?>
                    <?php
                    $acronym = !empty($com['short_name']) ? $com['short_name'] : $com['title'];
                    $fullName = !empty($com['short_name']) ? $com['title'] : ($com['agenda'] ?? '');
                    ?>
                    <a href="committee.php?id=<?= $com['id'] ?>" class="editorial-committee-card">
                        <div class="card-media-box">
                            <?php if (!empty($com['cover_image']) && file_exists(__DIR__ . '/' . $com['cover_image'])): ?>
                                <img src="<?= htmlspecialchars($com['cover_image']) ?>" alt="<?= htmlspecialchars($com['title']) ?>" class="card-media-img">
                            <?php else: ?>
                                <div class="card-media-placeholder">
                                    <i class="fa-solid fa-landmark"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-content-box">
                            <div class="card-text-top">
                                <div class="card-acronym"><?= htmlspecialchars($acronym) ?></div>
                                <div class="card-fullname"><?= htmlspecialchars($fullName) ?></div>
                            </div>
                            <div class="card-action-row">
                                <span class="card-arrow">&#x2197;</span>
                            </div>
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
