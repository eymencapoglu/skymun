<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$committee = get_committee_by_id($id);

if (!$committee) {
    header('Location: committees.php');
    exit;
}


$academicMembers = [];
for ($i = 1; $i <= 3; $i++) {
    $name = trim($committee["member{$i}_name"] ?? '');
    $title = trim($committee["member{$i}_title"] ?? '');
    $image = trim($committee["member{$i}_image"] ?? '');
    if (!empty($name)) {
        $academicMembers[] = [
            'name' => $name,
            'title' => $title,
            'image' => $image
        ];
    }
}
?>

<style>
    .committee-detail-section {
        padding: 120px 0 100px;
        position: relative;
        z-index: 2;
    }

    .back-nav-bar {
        margin-bottom: 30px;
    }

    .btn-return-committees {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--color-primary);
        font-size: 0.92rem;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: transform 0.25s ease, color 0.25s ease;
    }

    .btn-return-committees:hover {
        color: #ffffff;
        transform: translateX(-4px);
    }

    .committee-grid-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 36px;
        align-items: start;
    }

    .committee-content-panel {
        background: rgba(0, 7, 46, 0.75);
        border: 1px solid rgba(51, 163, 255, 0.16);
        border-radius: 20px;
        padding: 40px;
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        box-shadow: 0 15px 35px rgba(0, 4, 26, 0.5);
    }

    .committee-headline {
        font-size: 2.1rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 24px;
        position: relative;
        display: inline-block;
        padding-bottom: 8px;
    }

    .committee-headline::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #33a3ff, transparent);
    }

    .committee-topic-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #f1f5f9;
        margin-bottom: 24px;
        line-height: 1.5;
        letter-spacing: 0.3px;
    }

    .committee-subhead {
        font-size: 1.05rem;
        font-weight: 600;
        color: #e2e8f0;
        margin-bottom: 12px;
    }

    .committee-description-text {
        color: var(--color-text-muted);
        font-size: 0.98rem;
        line-height: 1.8;
        white-space: pre-line;
        margin-bottom: 35px;
    }

    .guide-download-box {
        padding-top: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .btn-study-guide {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 28px;
        background: linear-gradient(135deg, rgba(51, 163, 255, 0.2) 0%, rgba(29, 11, 121, 0.5) 100%);
        border: 1px solid rgba(51, 163, 255, 0.4);
        border-radius: 30px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.95rem;
        letter-spacing: 0.8px;
        box-shadow: 0 0 20px rgba(51, 163, 255, 0.25);
        transition: all 0.3s var(--ease-smooth);
        text-decoration: none;
    }

    .btn-study-guide:hover {
        background: linear-gradient(135deg, #33a3ff 0%, #1d0b79 100%);
        box-shadow: 0 0 30px rgba(51, 163, 255, 0.6);
        border-color: #33a3ff;
        transform: translateY(-2px);
        color: #ffffff;
    }

    .academic-team-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .academic-team-headline {
        font-size: 1.85rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 4px;
    }

    .team-member-card {
        background: linear-gradient(160deg, rgba(29, 11, 121, 0.28) 0%, rgba(0, 7, 46, 0.8) 100%);
        border: 1px solid rgba(51, 163, 255, 0.16);
        border-radius: 18px;
        padding: 30px 20px;
        text-align: center;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        box-shadow: 0 10px 25px rgba(0, 4, 26, 0.4);
        transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .team-member-card:hover {
        transform: translateY(-4px);
        border-color: rgba(51, 163, 255, 0.4);
    }

    .member-avatar-wrap {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin: 0 auto 16px;
        overflow: hidden;
        border: 2px solid rgba(51, 163, 255, 0.4);
        background: rgba(0, 7, 46, 0.6);
        box-shadow: 0 0 16px rgba(51, 163, 255, 0.25);
    }

    .member-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .member-avatar-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(51, 163, 255, 0.5);
        font-size: 2.2rem;
    }

    .member-full-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
    }

    .member-title-capsule {
        display: inline-block;
        padding: 8px 20px;
        background: rgba(0, 7, 46, 0.85);
        border: 1px solid rgba(51, 163, 255, 0.25);
        border-radius: 999px;
        font-size: 0.78rem;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: #94a3b8;
        font-weight: 600;
    }

    @media (max-width: 960px) {
        .committee-grid-layout {
            grid-template-columns: 1fr;
        }

        .committee-detail-section {
            padding-top: 100px;
        }

        .committee-content-panel {
            padding: 26px;
        }
    }
</style>

<section class="committee-detail-section">
    <div class="container">
        <div class="back-nav-bar">
            <a href="committees.php" class="btn-return-committees">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Committees</span>
            </a>
        </div>

        <div class="committee-grid-layout">
            <div class="committee-content-panel">
                <h1 class="committee-headline">About the Committee</h1>

                <?php if (!empty($committee['agenda'])): ?>
                    <div class="committee-topic-title">
                        <?= htmlspecialchars($committee['agenda']) ?>
                    </div>
                <?php endif; ?>

                <div class="committee-subhead">Overview:</div>

                <div class="committee-description-text"><?= htmlspecialchars($committee['description']) ?></div>

                <?php if (!empty($committee['study_guide']) && file_exists(__DIR__ . '/' . $committee['study_guide'])): ?>
                    <div class="guide-download-box">
                        <div>
                            <div style="font-weight:600;color:#ffffff;font-size:0.95rem;">Study Guide Available</div>
                            <div style="color:#94a3b8;font-size:0.85rem;margin-top:2px;">Prepare for sessions with the official research guide.</div>
                        </div>
                        <a href="<?= htmlspecialchars($committee['study_guide']) ?>" download class="btn-study-guide">
                            <i class="fa-solid fa-cloud-arrow-down"></i>
                            <span>Download Study Guide</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="academic-team-sidebar">
                <h2 class="academic-team-headline">Academic Team</h2>

                <?php if (!empty($academicMembers)): ?>
                    <?php foreach ($academicMembers as $member): ?>
                        <div class="team-member-card">
                            <div class="member-avatar-wrap">
                                <?php if (!empty($member['image']) && file_exists(__DIR__ . '/' . $member['image'])): ?>
                                    <img src="<?= htmlspecialchars($member['image']) ?>" alt="<?= htmlspecialchars($member['name']) ?>" class="member-avatar-img">
                                <?php else: ?>
                                    <div class="member-avatar-placeholder">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="member-full-name"><?= htmlspecialchars($member['name']) ?></div>

                            <?php if (!empty($member['title'])): ?>
                                <div class="member-title-capsule"><?= htmlspecialchars($member['title']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="team-member-card" style="color:#94a3b8;font-size:0.9rem;">
                        Academic team members will be announced soon.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/footer.php';
?>
