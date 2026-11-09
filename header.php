<?php
if (!defined('SITE_NAME')) {
    require_once __DIR__ . '/config.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= htmlspecialchars(SITE_NAME) ?> | <?= htmlspecialchars(SITE_MOTTO) ?></title>
    <meta name="description" content="<?= htmlspecialchars(SITE_NAME) ?> - <?= htmlspecialchars(SITE_MOTTO) ?> Model United Nations Conference">
    
    <link rel="icon" type="image/png" href="logo-transparent.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michorama&family=Montserrat:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    
    <style>
        :root {
            --color-primary: #33a3ff;
            --color-navy: #00072e;
            --color-purple: #1d0b79;
            --color-navy-dark: #00041a;
            --color-deep-indigo: #0e0638;
            
            --color-text-main: #f8fafc;
            --color-text-muted: #94a3b8;
            --color-text-subtle: #64748b;
            
            --font-main: 'Montserrat', 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-logo: 'Michorama', sans-serif;
            
            --ease-smooth: cubic-bezier(0.16, 1, 0.3, 1);
        }

        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            font-size: 16px;
        }

        body {
            font-family: var(--font-main);
            color: var(--color-text-main);
            background-color: var(--color-navy);
            background-image: linear-gradient(172deg, 
                #00072e 0%, 
                #00072e 32%, 
                #1d0b79 62%, 
                #00072e 90%, 
                #00041a 100%
            );
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            overflow-x: hidden;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
        }

        .aerospace-bg-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
            opacity: 0.65;
        }

        .aerospace-bg-svg {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .ambient-glow-subtle {
            position: fixed;
            top: 15%;
            right: -10%;
            width: 60vw;
            height: 50vh;
            background: radial-gradient(ellipse at center, rgba(29, 11, 121, 0.22) 0%, transparent 70%);
            filter: blur(120px);
            pointer-events: none;
            z-index: 0;
        }

        ::selection {
            background: var(--color-primary);
            color: var(--color-navy);
        }

        .font-logo {
            font-family: var(--font-logo) !important;
            letter-spacing: 1.5px;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s var(--ease-smooth);
        }
    </style>
</head>
<body>
    <div class="aerospace-bg-canvas" aria-hidden="true">
        <svg class="aerospace-bg-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice">
            <defs>
                <linearGradient id="orbitGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#33a3ff" stop-opacity="0.18" />
                    <stop offset="60%" stop-color="#1d0b79" stop-opacity="0.08" />
                    <stop offset="100%" stop-color="#33a3ff" stop-opacity="0" />
                </linearGradient>
                <linearGradient id="horizonGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#33a3ff" stop-opacity="0" />
                    <stop offset="50%" stop-color="#33a3ff" stop-opacity="0.12" />
                    <stop offset="100%" stop-color="#33a3ff" stop-opacity="0" />
                </linearGradient>
            </defs>
            <ellipse cx="720" cy="450" rx="680" ry="260" fill="none" stroke="url(#orbitGrad)" stroke-width="0.8" stroke-dasharray="4 8" transform="rotate(-14 720 450)" />
            <ellipse cx="720" cy="450" rx="820" ry="320" fill="none" stroke="rgba(51, 163, 255, 0.05)" stroke-width="0.6" transform="rotate(-14 720 450)" />
            <path d="M -200 920 Q 720 540 1640 920" fill="none" stroke="url(#horizonGrad)" stroke-width="1" />
            <circle cx="280" cy="240" r="1.5" fill="#33a3ff" opacity="0.4" />
            <circle cx="280" cy="240" r="4.5" fill="none" stroke="#33a3ff" stroke-width="0.5" opacity="0.25" />
            <circle cx="1180" cy="310" r="1.5" fill="#33a3ff" opacity="0.35" />
            <circle cx="1180" cy="310" r="5" fill="none" stroke="#33a3ff" stroke-width="0.5" opacity="0.2" />
            <circle cx="860" cy="180" r="1.2" fill="#ffffff" opacity="0.4" />
            <circle cx="460" cy="620" r="1.2" fill="#33a3ff" opacity="0.3" />
            <circle cx="980" cy="710" r="1.5" fill="#ffffff" opacity="0.3" />
            <g opacity="0.15" stroke="#33a3ff" stroke-width="0.6">
                <line x1="274" y1="240" x2="286" y2="240" />
                <line x1="280" y1="234" x2="280" y2="246" />
                <line x1="1174" y1="310" x2="1186" y2="310" />
                <line x1="1180" y1="304" x2="1180" y2="316" />
            </g>
        </svg>
    </div>
    
    <div class="ambient-glow-subtle"></div>

    <?php 
    require_once __DIR__ . '/navbar.php'; 
    ?>
