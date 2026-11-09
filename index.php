<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/header.php';
?>

<style>
    .hero-space-section {
        min-height: 45vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding-top: 80px;
    }

    .hero-space-inner {
        text-align: center;
        padding: 30px 20px;
        position: relative;
        z-index: 2;
    }

    
    .hero-subtle-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 22px;
        background: rgba(51, 163, 255, 0.04);
        border: 1px solid rgba(51, 163, 255, 0.16);
        border-radius: 999px;
        color: rgba(248, 250, 252, 0.85);
        font-size: 0.82rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .hero-subtle-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--color-primary);
        box-shadow: 0 0 8px var(--color-primary);
    }

    .trajectory-countdown-section {
        padding: 20px 0 90px;
        position: relative;
        z-index: 2;
    }

    .trajectory-hud-grid {
        display: grid;
        grid-template-columns: 1.45fr 1fr;
        gap: 22px;
        max-width: 1040px;
        margin: 0 auto;
        align-items: stretch;
    }

    .hud-console-card {
        background: rgba(0, 7, 46, 0.4);
        border: 1px solid rgba(51, 163, 255, 0.16);
        border-radius: 12px;
        padding: 28px 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        transition: border-color 0.3s ease, background 0.3s ease, transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .hud-console-card:hover {
        border-color: rgba(255, 255, 255, 0.35);
        background: rgba(4, 12, 54, 0.65);
        transform: translateY(-3px);
    }

    .hud-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(51, 163, 255, 0.1);
    }

    .hud-card-tag {
        font-family: var(--font-logo);
        font-size: 0.76rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--color-primary);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .hud-radar-pulse {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 8px #22c55e;
        animation: radarBlink 2s ease-in-out infinite;
    }

    @keyframes radarBlink {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.35; transform: scale(0.85); }
    }

    .hud-status-chip {
        font-family: var(--font-logo);
        font-size: 0.7rem;
        letter-spacing: 1.5px;
        padding: 3px 9px;
        border-radius: 4px;
        background: rgba(51, 163, 255, 0.1);
        border: 1px solid rgba(51, 163, 255, 0.25);
        color: #ffffff;
        text-transform: uppercase;
    }

    .mission-clock-row {
        display: inline-flex;
        align-items: baseline;
        justify-content: center;
        gap: 12px;
        margin-bottom: 20px;
        width: 100%;
    }

    .t-minus-prefix {
        font-family: var(--font-logo);
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--color-primary);
        letter-spacing: 1.5px;
        opacity: 0.9;
        text-shadow: 0 0 16px rgba(51, 163, 255, 0.4);
        user-select: none;
        align-self: flex-start;
        margin-top: 4px;
    }

    .time-unit-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 62px;
    }

    .time-digits {
        font-family: var(--font-logo);
        font-size: 2.7rem;
        font-weight: 400;
        line-height: 1;
        color: #ffffff;
        letter-spacing: 1px;
        font-variant-numeric: tabular-nums;
        text-shadow: 0 0 20px rgba(51, 163, 255, 0.35);
        margin-bottom: 6px;
    }

    .time-label {
        font-size: 0.68rem;
        letter-spacing: 2.5px;
        text-transform: uppercase;
        color: rgba(248, 250, 252, 0.55);
        font-weight: 500;
    }

    .time-colon {
        font-family: var(--font-logo);
        font-size: 1.8rem;
        color: rgba(51, 163, 255, 0.4);
        line-height: 1;
        user-select: none;
        margin-top: 6px;
    }

    .hud-destination-body {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .hud-label-tiny {
        font-size: 0.68rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(248, 250, 252, 0.45);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .hud-dest-main {
        font-family: var(--font-logo);
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: #ffffff;
        line-height: 1.2;
    }

    .hud-dest-route {
        font-size: 0.84rem;
        color: rgba(248, 250, 252, 0.6);
        margin-top: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .hud-coord-row {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(51, 163, 255, 0.12);
        border-radius: 6px;
        font-family: var(--font-logo);
        font-size: 0.78rem;
        letter-spacing: 1px;
        color: var(--color-primary);
        margin-top: 8px;
        width: fit-content;
    }

    .hud-compass-icon {
        font-size: 0.82rem;
        color: #38bdf8;
        transform: rotate(-45deg);
        display: inline-block;
        transition: transform 0.4s ease;
    }

    .hud-console-card:hover .hud-compass-icon {
        transform: rotate(-15deg);
    }

    .hud-datetime-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding-top: 14px;
        border-top: 1px solid rgba(51, 163, 255, 0.08);
    }

    .hud-date-val {
        font-family: var(--font-logo);
        font-size: 0.95rem;
        font-weight: 600;
        letter-spacing: 1px;
        color: #ffffff;
    }

    .hud-time-val {
        font-family: var(--font-logo);
        font-size: 0.88rem;
        letter-spacing: 1px;
        color: var(--color-primary);
    }

    .hud-footer-distance {
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid rgba(51, 163, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hud-dist-arrow {
        color: var(--color-primary);
        font-size: 1.15rem;
        transition: transform 0.3s ease;
    }

    .hud-console-card:hover .hud-dist-arrow {
        transform: translateX(4px);
    }

    .hud-dist-val {
        font-family: var(--font-logo);
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        color: #ffffff;
    }

    .hud-dist-sub {
        font-size: 0.72rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: rgba(248, 250, 252, 0.45);
        font-weight: 500;
        margin-left: 4px;
    }

    .flight-timeline-card {
        position: relative;
        padding: 0 10px 10px;
        width: 100%;
        max-width: 820px;
        margin: 0 auto;
    }

    .timeline-endpoints {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        padding: 0 2px;
    }

    .endpoint-item {
        font-family: var(--font-logo);
        font-size: 0.85rem;
        letter-spacing: 2px;
        color: rgba(248, 250, 252, 0.7);
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .endpoint-item.skymun-target {
        color: var(--color-primary);
        text-shadow: 0 0 12px rgba(51, 163, 255, 0.5);
    }

    .endpoint-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
    }

    .endpoint-item.skymun-target .endpoint-dot {
        background: var(--color-primary);
        box-shadow: 0 0 8px var(--color-primary);
    }

    .trajectory-rail-wrap {
        position: relative;
        width: 100%;
        height: 24px;
        display: flex;
        align-items: center;
    }

    .trajectory-rail-base {
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: linear-gradient(90deg, 
            rgba(51, 163, 255, 0.15) 0%, 
            rgba(51, 163, 255, 0.25) 50%, 
            rgba(51, 163, 255, 0.15) 100%
        );
        transform: translateY(-50%);
    }

    .trajectory-rail-stream {
        position: absolute;
        left: 0;
        top: 50%;
        height: 1.5px;
        background: linear-gradient(90deg, 
            rgba(51, 163, 255, 0.05) 0%, 
            rgba(51, 163, 255, 0.45) 45%, 
            rgba(51, 163, 255, 0.85) 85%, 
            #ffffff 100%
        );
        background-size: 200% 100%;
        animation: softContinuousStream 5s linear infinite;
        box-shadow: 0 0 10px rgba(51, 163, 255, 0.5);
        transform: translateY(-50%);
        width: 15%;
        transition: width 0.6s linear;
    }

    @keyframes softContinuousStream {
        0% { background-position: 0% 0; }
        100% { background-position: -200% 0; }
    }

    .trajectory-aircraft-beacon {
        position: absolute;
        top: 50%;
        left: 15%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: default;
        z-index: 5;
        transition: left 0.6s linear;
    }

    .aircraft-icon-wrap {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        filter: drop-shadow(0 0 8px rgba(51, 163, 255, 0.7));
        animation: aircraftFloat 3s ease-in-out infinite;
        transform: rotate(90deg);
    }

    .aircraft-icon-wrap svg {
        width: 100%;
        height: 100%;
        fill: #ffffff;
    }

    @keyframes aircraftFloat {
        0%, 100% {
            transform: rotate(90deg) translateX(0);
        }
        50% {
            transform: rotate(90deg) translateX(1px);
        }
    }

    .jetstream-exhaust {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 1.5px;
        background: linear-gradient(270deg, rgba(255, 255, 255, 0.8) 0%, rgba(51, 163, 255, 0.35) 60%, transparent 100%);
        filter: blur(0.5px);
        pointer-events: none;
    }

    .aircraft-callout {
        position: absolute;
        top: 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        white-space: nowrap;
        pointer-events: none;
    }

    .callout-tick {
        width: 1px;
        height: 8px;
        background: linear-gradient(180deg, var(--color-primary), transparent);
        margin-bottom: 3px;
    }

    .callout-tag {
        font-family: var(--font-logo);
        font-size: 0.65rem;
        letter-spacing: 2px;
        color: var(--color-primary);
        text-transform: uppercase;
        font-weight: 600;
        text-shadow: 0 0 10px rgba(51, 163, 255, 0.6);
    }

    .faq-section {
        padding: 80px 0 130px;
        position: relative;
        z-index: 2;
    }

    .faq-container {
        max-width: 820px;
        margin: 0 auto;
    }

    .faq-header {
        margin-bottom: 50px;
        text-align: left;
    }

    .faq-title {
        font-family: var(--font-logo);
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: #ffffff;
        margin-bottom: 8px;
        text-shadow: 0 0 20px rgba(51, 163, 255, 0.25);
    }

    .faq-subtitle {
        font-size: 1.05rem;
        color: var(--color-text-muted);
        letter-spacing: 0.5px;
        font-weight: 400;
    }

    .faq-list {
        display: flex;
        flex-direction: column;
    }

    .faq-item {
        border-bottom: 1px solid rgba(51, 163, 255, 0.12);
        transition: border-color 0.3s ease;
    }

    .faq-item:first-child {
        border-top: 1px solid rgba(51, 163, 255, 0.12);
    }

    .faq-item.is-open {
        border-bottom-color: rgba(51, 163, 255, 0.3);
    }

    .faq-trigger {
        width: 100%;
        background: transparent;
        border: none;
        padding: 22px 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        text-align: left;
        color: #ffffff;
        transition: color 0.25s ease;
    }

    .faq-trigger:hover {
        color: var(--color-primary);
    }

    .faq-trigger-left {
        display: flex;
        align-items: baseline;
        gap: 16px;
    }

    .faq-deg-index {
        font-family: var(--font-logo);
        font-size: 0.88rem;
        color: var(--color-primary);
        letter-spacing: 1px;
        opacity: 0.8;
        min-width: 32px;
    }

    .faq-question {
        font-size: 1.12rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        color: #f1f5f9;
        transition: color 0.25s ease;
    }

    .faq-trigger:hover .faq-question,
    .faq-item.is-open .faq-question {
        color: #ffffff;
    }

    .faq-arrow {
        font-size: 1.25rem;
        color: rgba(248, 250, 252, 0.6);
        display: inline-block;
        line-height: 1;
        transition: transform 0.35s var(--ease-smooth), color 0.3s ease;
        margin-left: 16px;
    }

    .faq-item.is-open .faq-arrow {
        transform: rotate(90deg);
        color: var(--color-primary);
    }

    .faq-answer-drawer {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.55s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
        opacity: 0;
    }

    .faq-item.is-open .faq-answer-drawer {
        grid-template-rows: 1fr;
        opacity: 1;
    }

    .faq-answer-drawer > div {
        overflow: hidden;
    }

    .faq-bracket-frame {
        position: relative;
        padding-left: 24px;
        margin-left: 48px;
        margin-top: 6px;
        margin-bottom: 24px;
        border-left: 1px solid rgba(51, 163, 255, 0.35);
        transform: translateY(-6px);
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .faq-item.is-open .faq-bracket-frame {
        transform: translateY(0);
    }

    .faq-bracket-degree {
        position: absolute;
        top: -10px;
        left: -4px;
        font-family: var(--font-logo);
        font-size: 0.85rem;
        color: var(--color-primary);
        line-height: 1;
        opacity: 0.9;
        user-select: none;
    }

    .faq-answer-text {
        color: var(--color-text-muted);
        font-size: 0.96rem;
        line-height: 1.7;
        max-width: 680px;
        padding-bottom: 12px;
    }

    .faq-bracket-foot {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 140px;
        height: 1px;
        background: linear-gradient(90deg, rgba(51, 163, 255, 0.35), transparent);
    }

    @media (max-width: 868px) {
        .trajectory-hud-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .hud-console-card {
            padding: 24px 20px;
        }

        .hero-space-section {
            min-height: 38vh;
            padding-top: 70px;
        }

        .mission-clock-row {
            gap: 8px;
            margin-bottom: 16px;
        }

        .t-minus-prefix {
            font-size: 1.4rem;
            margin-top: 2px;
        }

        .time-digits {
            font-size: 2.1rem;
            margin-bottom: 4px;
        }

        .time-unit-col {
            min-width: 48px;
        }

        .time-label {
            font-size: 0.62rem;
            letter-spacing: 1.5px;
        }

        .time-colon {
            font-size: 1.4rem;
            margin-top: 4px;
        }

        .flight-timeline-card {
            padding: 0 0 10px;
        }

        .endpoint-item {
            font-size: 0.75rem;
            letter-spacing: 1.5px;
        }

        .faq-section {
            padding: 60px 0 100px;
        }

        .faq-title {
            font-size: 1.7rem;
        }

        .faq-subtitle {
            font-size: 0.95rem;
        }

        .faq-question {
            font-size: 1rem;
        }

        .faq-bracket-frame {
            margin-left: 20px;
            padding-left: 18px;
        }
    }

    @media (max-width: 440px) {
        .time-digits {
            font-size: 1.85rem;
        }
        .time-unit-col {
            min-width: 45px;
        }
        .t-minus-prefix {
            font-size: 1.25rem;
        }
        .faq-trigger-left {
            gap: 10px;
        }
        .faq-deg-index {
            min-width: 24px;
            font-size: 0.8rem;
        }
    }
</style>

<section class="hero-space-section">
    <div class="hero-space-inner">
        <div class="hero-subtle-badge">
            <span class="dot"></span>
            <span><?= htmlspecialchars(SITE_NAME) ?> 2026 &bull; <?= htmlspecialchars(SITE_MOTTO) ?></span>
        </div>
    </div>
</section>

<section class="trajectory-countdown-section">
    <div class="container">
        <div class="trajectory-hud-grid">
            <div class="hud-console-card">
                <div>
                    <div class="hud-card-header">
                        <span class="hud-card-tag">
                            <span class="hud-radar-pulse"></span>
                            MISSION TELEMETRY
                        </span>
                        <span class="hud-status-chip">T− CLOCK</span>
                    </div>

                    <div class="mission-clock-row">
                        <span class="t-minus-prefix">T−</span>

                        <div class="time-unit-col">
                            <span class="time-digits" id="cdDays">00</span>
                            <span class="time-label">DAYS</span>
                        </div>

                        <span class="time-colon">:</span>

                        <div class="time-unit-col">
                            <span class="time-digits" id="cdHours">00</span>
                            <span class="time-label">HOURS</span>
                        </div>

                        <span class="time-colon">:</span>

                        <div class="time-unit-col">
                            <span class="time-digits" id="cdMinutes">00</span>
                            <span class="time-label">MINUTES</span>
                        </div>

                        <span class="time-colon">:</span>

                        <div class="time-unit-col">
                            <span class="time-digits" id="cdSeconds">00</span>
                            <span class="time-label">SEC</span>
                        </div>
                    </div>
                </div>

                <div class="flight-timeline-card">
                    <div class="timeline-endpoints">
                        <div class="endpoint-item">
                            <span class="endpoint-dot"></span>
                            <span>NOW</span>
                        </div>
                        <div class="endpoint-item skymun-target">
                            <span><?= htmlspecialchars(SITE_NAME) ?></span>
                            <span class="endpoint-dot"></span>
                        </div>
                    </div>

                    <div class="trajectory-rail-wrap">
                        <div class="trajectory-rail-base"></div>
                        <div class="trajectory-rail-stream" id="trajStream"></div>
                        <div class="trajectory-aircraft-beacon" id="trajAircraft">
                            <span class="jetstream-exhaust"></span>
                            <div class="aircraft-icon-wrap" title="Flight Progress">
                                <svg viewBox="0 0 24 24">
                                    <path d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"/>
                                </svg>
                            </div>
                            <div class="aircraft-callout">
                                <span class="callout-tick"></span>
                                <span class="callout-tag">TODAY</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hud-console-card">
                <div>
                    <div class="hud-card-header">
                        <span class="hud-card-tag">
                            <i class="fa-solid fa-crosshairs" style="color:var(--color-primary);"></i>
                            DESTINATION LOCKED
                        </span>
                        <span class="hud-status-chip">NEXT POINT</span>
                    </div>

                    <div class="hud-destination-body">
                        <div>
                            <div class="hud-label-tiny">DESTINATION</div>
                            <div class="hud-dest-main">MUNICH &bull; MUC</div>
                            <div class="hud-dest-route">
                                <span>Istanbul</span>
                                <i class="fa-solid fa-arrow-right-long" style="font-size:0.75rem;color:var(--color-primary);"></i>
                                <span>Munich</span>
                            </div>
                            <div class="hud-coord-row">
                                <i class="fa-solid fa-location-arrow hud-compass-icon"></i>
                                <span>48.3538&deg; N &bull; 11.7861&deg; E</span>
                            </div>
                        </div>

                        <div class="hud-datetime-grid">
                            <div>
                                <div class="hud-label-tiny">CONFERENCE DATE</div>
                                <div class="hud-date-val"><?= strtoupper(date('d M Y', strtotime(CONFERENCE_DATE))) ?></div>
                            </div>
                            <div>
                                <div class="hud-label-tiny">SCHEDULED TIME</div>
                                <div class="hud-time-val"><?= date('H:i:s', strtotime(CONFERENCE_DATE)) ?> UTC</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hud-footer-distance">
                    <span class="hud-dist-arrow">&rarr;</span>
                    <span class="hud-dist-val" id="hudDistanceKm">482 km</span>
                    <span class="hud-dist-sub">TO WAYPOINT</span>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="faq-section">
    <div class="container">
        <div class="faq-container">
            <div class="faq-header">
                <h2 class="faq-title">Frequently Asked Questions</h2>
                <p class="faq-subtitle">A few things you might want to know before taking off.</p>
            </div>

            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-trigger" type="button" aria-expanded="false">
                        <div class="faq-trigger-left">
                            <span class="faq-deg-index">01°</span>
                            <span class="faq-question">What is MUN?</span>
                        </div>
                        <span class="faq-arrow" aria-hidden="true">&#x2197;</span>
                    </button>
                    <div class="faq-answer-drawer">
                        <div class="faq-bracket-frame">
                            <span class="faq-bracket-degree">&deg;</span>
                            <p class="faq-answer-text">
                                MUN is the abbreviation for Model United Nations. These conferences simulate UN meetings with committees that tackle global issues. Students attend as delegates representing countries, organizations, or individuals to propose and negotiate solutions. Before the conference, they research and prepare position papers. During sessions, they debate, amend, and vote on draft resolutions. Outstanding delegates may receive awards for performance and diplomacy.
                            </p>
                            <div class="faq-bracket-foot"></div>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-trigger" type="button" aria-expanded="false">
                        <div class="faq-trigger-left">
                            <span class="faq-deg-index">02°</span>
                            <span class="faq-question">How do I apply?</span>
                        </div>
                        <span class="faq-arrow" aria-hidden="true">&#x2197;</span>
                    </button>
                    <div class="faq-answer-drawer">
                        <div class="faq-bracket-frame">
                            <span class="faq-bracket-degree">&deg;</span>
                            <p class="faq-answer-text">
                                Just click "Apply" on our home page.
                            </p>
                            <div class="faq-bracket-foot"></div>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-trigger" type="button" aria-expanded="false">
                        <div class="faq-trigger-left">
                            <span class="faq-deg-index">03°</span>
                            <span class="faq-question">What is your procedure?</span>
                        </div>
                        <span class="faq-arrow" aria-hidden="true">&#x2197;</span>
                    </button>
                    <div class="faq-answer-drawer">
                        <div class="faq-bracket-frame">
                            <span class="faq-bracket-degree">&deg;</span>
                            <p class="faq-answer-text">
                                We follow Harvard procedure.
                            </p>
                            <div class="faq-bracket-foot"></div>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-trigger" type="button" aria-expanded="false">
                        <div class="faq-trigger-left">
                            <span class="faq-deg-index">04°</span>
                            <span class="faq-question">What is your dress code?</span>
                        </div>
                        <span class="faq-arrow" aria-hidden="true">&#x2197;</span>
                    </button>
                    <div class="faq-answer-drawer">
                        <div class="faq-bracket-frame">
                            <span class="faq-bracket-degree">&deg;</span>
                            <p class="faq-answer-text">
                                Our dress code is Western Business Attire.
                            </p>
                            <div class="faq-bracket-foot"></div>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-trigger" type="button" aria-expanded="false">
                        <div class="faq-trigger-left">
                            <span class="faq-deg-index">05°</span>
                            <span class="faq-question">Will food be provided during the conference?</span>
                        </div>
                        <span class="faq-arrow" aria-hidden="true">&#x2197;</span>
                    </button>
                    <div class="faq-answer-drawer">
                        <div class="faq-bracket-frame">
                            <span class="faq-bracket-degree">&deg;</span>
                            <p class="faq-answer-text">
                                Yes, meals and snacks during coffee breaks will be provided throughout the conference.
                            </p>
                            <div class="faq-bracket-foot"></div>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-trigger" type="button" aria-expanded="false">
                        <div class="faq-trigger-left">
                            <span class="faq-deg-index">06°</span>
                            <span class="faq-question">Can we change our countries/committees?</span>
                        </div>
                        <span class="faq-arrow" aria-hidden="true">&#x2197;</span>
                    </button>
                    <div class="faq-answer-drawer">
                        <div class="faq-bracket-frame">
                            <span class="faq-bracket-degree">&deg;</span>
                            <p class="faq-answer-text">
                                Allocations are decided by our academic team after careful evaluation, so changes are not possible.
                            </p>
                            <div class="faq-bracket-foot"></div>
                        </div>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-trigger" type="button" aria-expanded="false">
                        <div class="faq-trigger-left">
                            <span class="faq-deg-index">07°</span>
                            <span class="faq-question">Will participants receive certificates?</span>
                        </div>
                        <span class="faq-arrow" aria-hidden="true">&#x2197;</span>
                    </button>
                    <div class="faq-answer-drawer">
                        <div class="faq-bracket-frame">
                            <span class="faq-bracket-degree">&deg;</span>
                            <p class="faq-answer-text">
                                Yes, all delegates who attend the required number of sessions will receive certificates.
                            </p>
                            <div class="faq-bracket-foot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (function() {
        const targetTimestamp = new Date("<?= date('Y-m-d\TH:i:s', strtotime(CONFERENCE_DATE)) ?>").getTime();
        const campaignDays = 100;
        const startTimestamp = targetTimestamp - (campaignDays * 24 * 60 * 60 * 1000);

        const elDays = document.getElementById('cdDays');
        const elHours = document.getElementById('cdHours');
        const elMinutes = document.getElementById('cdMinutes');
        const elSeconds = document.getElementById('cdSeconds');
        const elStream = document.getElementById('trajStream');
        const elAircraft = document.getElementById('trajAircraft');

        function formatTwoDigits(num) {
            return num < 10 ? '0' + num : num;
        }

        function updateTelemetry() {
            const now = new Date().getTime();
            const remaining = targetTimestamp - now;

            if (remaining <= 0) {
                if (elDays) elDays.textContent = '00';
                if (elHours) elHours.textContent = '00';
                if (elMinutes) elMinutes.textContent = '00';
                if (elSeconds) elSeconds.textContent = '00';
                if (elStream) elStream.style.width = '100%';
                if (elAircraft) elAircraft.style.left = '100%';
                return;
            }

            const days = Math.floor(remaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

            if (elDays) elDays.textContent = formatTwoDigits(days);
            if (elHours) elHours.textContent = formatTwoDigits(hours);
            if (elMinutes) elMinutes.textContent = formatTwoDigits(minutes);
            if (elSeconds) elSeconds.textContent = formatTwoDigits(seconds);

            const totalDuration = targetTimestamp - startTimestamp;
            const elapsed = now - startTimestamp;
            let progress = (elapsed / totalDuration) * 100;

            if (progress < 2) progress = 2;
            if (progress > 100) progress = 100;

            if (elStream) elStream.style.width = progress.toFixed(3) + '%';
            if (elAircraft) elAircraft.style.left = progress.toFixed(3) + '%';

            const totalFlightKm = 1930;
            const remainingKm = Math.max(0, Math.round(totalFlightKm * (1 - (progress / 100))));
            const elDistance = document.getElementById('hudDistanceKm');
            if (elDistance) {
                elDistance.textContent = (remaining <= 0) ? '0 km' : remainingKm + ' km';
            }
        }

        updateTelemetry();
        setInterval(updateTelemetry, 1000);

        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const trigger = item.querySelector('.faq-trigger');
            if (trigger) {
                trigger.addEventListener('click', () => {
                    const isOpen = item.classList.contains('is-open');
                    
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('is-open');
                            const otherTrigger = otherItem.querySelector('.faq-trigger');
                            if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
                        }
                    });

                    if (isOpen) {
                        item.classList.remove('is-open');
                        trigger.setAttribute('aria-expanded', 'false');
                    } else {
                        item.classList.add('is-open');
                        trigger.setAttribute('aria-expanded', 'true');
                    }
                });
            }
        });
    })();
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
