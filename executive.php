<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/header.php';

$executives = get_all_executives(true);
?>

<style>
    .executives-hero-section {
        padding: 130px 0 45px;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .executives-badge {
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

    .executives-main-title {
        font-family: var(--font-logo);
        font-size: clamp(2.2rem, 4.5vw, 3.2rem);
        font-weight: 700;
        letter-spacing: 2.5px;
        color: #ffffff;
        margin-bottom: 12px;
        text-shadow: 0 0 20px rgba(51, 163, 255, 0.25);
    }

    .executives-subtitle {
        color: var(--color-text-muted);
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }

    .executives-wrapper {
        padding: 30px 0 130px;
        position: relative;
        z-index: 2;
    }

    .executives-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 36px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .editorial-executive-card {
        flex: 0 1 calc(33.333% - 24px);
        max-width: 360px;
        min-width: 290px;
        background: rgba(0, 7, 46, 0.6);
        border: 1px solid rgba(51, 163, 255, 0.14);
        border-radius: 4px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        color: inherit;
        position: relative;
        cursor: pointer;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        transition: transform 0.35s var(--ease-smooth), border-color 0.35s ease, box-shadow 0.35s ease;
    }

    .editorial-executive-card:hover {
        transform: translateY(-4px);
        border-color: rgba(51, 163, 255, 0.45);
        box-shadow: 0 12px 36px rgba(0, 4, 26, 0.7);
    }

    .card-media-box {
        width: 100%;
        height: 250px;
        overflow: hidden;
        position: relative;
        background: linear-gradient(145deg, rgba(29, 11, 121, 0.4) 0%, #00072e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-media-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 30%;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        transform: scale(1);
    }

    .editorial-executive-card:hover .card-media-img {
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

    .editorial-executive-card:hover .card-media-placeholder {
        transform: scale(1.04);
    }

    .card-content-box {
        padding: 24px 22px 24px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        flex: 1;
        border-top: 1px solid rgba(51, 163, 255, 0.08);
    }

    .card-name {
        font-family: var(--font-logo);
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 1.2px;
        margin-bottom: 8px;
        line-height: 1.25;
    }

    .card-title {
        font-size: 0.95rem;
        color: var(--color-primary);
        line-height: 1.45;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .card-zoom-hint {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(0, 7, 46, 0.7);
        border: 1px solid rgba(51, 163, 255, 0.25);
        color: rgba(248, 250, 252, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        opacity: 0;
        transform: scale(0.85);
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .editorial-executive-card:hover .card-zoom-hint {
        opacity: 1;
        transform: scale(1);
    }

    .exec-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 4, 26, 0.85);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        z-index: 3000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.35s var(--ease-smooth), visibility 0.35s var(--ease-smooth);
    }

    .exec-modal-overlay.is-active {
        opacity: 1;
        visibility: visible;
    }

    .exec-modal-card {
        width: 100%;
        max-width: 440px;
        background: rgba(0, 7, 46, 0.95);
        border: 1px solid rgba(51, 163, 255, 0.3);
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 4, 26, 0.8), 0 0 35px rgba(51, 163, 255, 0.2);
        position: relative;
        transform: scale(0.92);
        transition: transform 0.4s var(--ease-smooth);
    }

    .exec-modal-overlay.is-active .exec-modal-card {
        transform: scale(1);
    }

    .exec-modal-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(0, 7, 46, 0.8);
        border: 1px solid rgba(51, 163, 255, 0.3);
        color: #ffffff;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        transition: all 0.25s ease;
    }

    .exec-modal-close:hover {
        background: var(--color-primary);
        color: #00072e;
        transform: rotate(90deg);
    }

    .exec-modal-media {
        width: 100%;
        height: 340px;
        background: #00072e;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .exec-modal-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 30%;
    }

    .exec-modal-placeholder {
        font-size: 4.5rem;
        color: rgba(51, 163, 255, 0.25);
    }

    .exec-modal-body {
        padding: 30px 28px;
        text-align: center;
        border-top: 1px solid rgba(51, 163, 255, 0.15);
        background: linear-gradient(180deg, rgba(0, 7, 46, 0.7) 0%, rgba(0, 4, 26, 0.95) 100%);
    }

    .exec-modal-name {
        font-family: var(--font-logo);
        font-size: 1.65rem;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .exec-modal-title {
        font-size: 1.05rem;
        color: var(--color-primary);
        font-weight: 500;
        letter-spacing: 0.6px;
    }

    @media (max-width: 992px) {
        .editorial-executive-card {
            flex: 0 1 calc(50% - 20px);
            max-width: 360px;
        }
    }

    @media (max-width: 680px) {
        .executives-hero-section {
            padding: 110px 0 35px;
        }

        .executives-wrapper {
            padding: 20px 0 90px;
        }

        .editorial-executive-card {
            flex: 0 1 100%;
            max-width: 360px;
        }

        .card-media-box {
            height: 240px;
        }

        .exec-modal-media {
            height: 280px;
        }
    }
</style>

<section class="executives-hero-section">
    <div class="container">
        <div class="executives-badge">
            <i class="fa-solid fa-users"></i>
            <span>Leadership</span>
        </div>
        <h1 class="executives-main-title">EXECUTIVE TEAM</h1>
        <p class="executives-subtitle">Meet the Secretariat and Executive Board of <?= htmlspecialchars(SITE_NAME) ?>.</p>
    </div>
</section>

<section class="executives-wrapper">
    <div class="container">
        <?php if (empty($executives)): ?>
            <div style="text-align:center;padding:60px 20px;color:#94a3b8;">
                <p>The executive team members will be announced soon. Please check back later.</p>
            </div>
        <?php else: ?>
            <div class="executives-container">
                <?php foreach ($executives as $exec): ?>
                    <?php 
                    $hasImage = (!empty($exec['image']) && file_exists(__DIR__ . '/' . $exec['image']));
                    $imgUrl = $hasImage ? htmlspecialchars($exec['image']) : '';
                    ?>
                    <div class="editorial-executive-card js-exec-card"
                         data-name="<?= htmlspecialchars($exec['name']) ?>"
                         data-title="<?= htmlspecialchars($exec['title']) ?>"
                         data-image="<?= $imgUrl ?>">
                        
                        <div class="card-zoom-hint" title="Büyüt">
                            <i class="fa-solid fa-expand"></i>
                        </div>

                        <div class="card-media-box">
                            <?php if ($hasImage): ?>
                                <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($exec['name']) ?>" class="card-media-img">
                            <?php else: ?>
                                <div class="card-media-placeholder">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="card-content-box">
                            <div class="card-name"><?= htmlspecialchars($exec['name']) ?></div>
                            <div class="card-title"><?= htmlspecialchars($exec['title']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="exec-modal-overlay" id="execModalOverlay">
    <div class="exec-modal-card">
        <button type="button" class="exec-modal-close" id="execModalClose" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="exec-modal-media">
            <img src="" alt="" class="exec-modal-img" id="execModalImg" style="display: none;">
            <div class="exec-modal-placeholder" id="execModalPlaceholder">
                <i class="fa-solid fa-user-tie"></i>
            </div>
        </div>

        <div class="exec-modal-body">
            <div class="exec-modal-name" id="execModalName"></div>
            <div class="exec-modal-title" id="execModalTitle"></div>
        </div>
    </div>
</div>

<script>
    (function() {
        const overlay = document.getElementById('execModalOverlay');
        const closeBtn = document.getElementById('execModalClose');
        const modalImg = document.getElementById('execModalImg');
        const modalPlaceholder = document.getElementById('execModalPlaceholder');
        const modalName = document.getElementById('execModalName');
        const modalTitle = document.getElementById('execModalTitle');

        function openModal(name, title, image) {
            modalName.textContent = name;
            modalTitle.textContent = title;

            if (image) {
                modalImg.src = image;
                modalImg.style.display = 'block';
                modalPlaceholder.style.display = 'none';
            } else {
                modalImg.style.display = 'none';
                modalPlaceholder.style.display = 'flex';
            }

            overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            overlay.classList.remove('is-active');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.js-exec-card').forEach(function(card) {
            card.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const title = this.getAttribute('data-title');
                const image = this.getAttribute('data-image');
                openModal(name, title, image);
            });
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        if (overlay) {
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeModal();
                }
            });
        }

        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-active')) {
                closeModal();
            }
        });
    })();
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
