<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Character encoding & compatibility --}}
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Sistem Informasi KIP Kuliah UNSIKA sedang dalam pemeliharaan. Kami akan segera kembali.">
    <meta name="author" content="FORMADIKIP UNSIKA">

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:site_name"   content="KIP UNSIKA">
    <meta property="og:title"       content="Maintenance — KIP UNSIKA">
    <meta property="og:description" content="Sistem Informasi KIP Kuliah UNSIKA sedang dalam pemeliharaan. Kami akan segera kembali.">
    <meta property="og:image"       content="{{ asset('images/og-image.png') }}">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt"    content="KIP UNSIKA — Maintenance">
    <meta property="og:locale"       content="id_ID">

    {{-- Twitter / X Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="Maintenance — KIP UNSIKA">
    <meta name="twitter:description" content="Sistem Informasi KIP Kuliah UNSIKA sedang dalam pemeliharaan. Kami akan segera kembali.">
    <meta name="twitter:image"       content="{{ asset('images/og-image.png') }}">

    {{-- PWA / Mobile Browser --}}
    <meta name="theme-color" content="#1a1d2e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="KIP UNSIKA">

    {{-- Favicon & Icons --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/og-image.png') }}">

    <title>Maintenance — KIP UNSIKA | Pusat Informasi KIP Kuliah</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* =========================================
           CSS Variables — Konsisten dengan app.scss
        ========================================= */
        :root {
            --primary:   #e84545;
            --dark:      #1a1d2e;
            --dark-light: #252840;
            --secondary: #2c3e50;
            --success:   #059669;
            --font-heading: 'Raleway', sans-serif;
            --font-body:    'Open Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-body);
            background: var(--dark);
            color: #fff;
            min-height: 100vh;
            min-height: 100dvh; /* Dynamic viewport height for mobile browsers */
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%; /* Prevent font scaling in Safari */
            -ms-text-size-adjust: 100%;
        }

        /* ---- Background animated ---- */
        .maintenance-bg {
            position: fixed;
            /* Fallback for browsers not supporting inset */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            background: -webkit-linear-gradient(315deg, var(--dark) 0%, var(--dark-light) 100%);
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark-light) 100%);
            overflow: hidden;
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            -webkit-filter: blur(80px);
            filter: blur(80px);
            opacity: 0.18;
            /* GPU acceleration for Safari */
            -webkit-transform: translateZ(0);
            transform: translateZ(0);
            will-change: transform;
            -webkit-animation: floatOrb 12s ease-in-out infinite alternate;
            animation: floatOrb 12s ease-in-out infinite alternate;
        }
        .orb-1 {
            width: 500px; height: 500px;
            background: var(--primary);
            top: -150px; left: -150px;
            -webkit-animation-delay: 0s;
            animation-delay: 0s;
        }
        .orb-2 {
            width: 400px; height: 400px;
            background: #5865f2;
            bottom: -100px; right: -100px;
            -webkit-animation-delay: -4s;
            animation-delay: -4s;
        }
        .orb-3 {
            width: 300px; height: 300px;
            background: var(--primary);
            top: 50%; left: 60%;
            -webkit-animation-delay: -8s;
            animation-delay: -8s;
        }

        @-webkit-keyframes floatOrb {
            0%   { -webkit-transform: translate(0, 0) scale(1) translateZ(0); transform: translate(0, 0) scale(1) translateZ(0); }
            100% { -webkit-transform: translate(30px, 20px) scale(1.08) translateZ(0); transform: translate(30px, 20px) scale(1.08) translateZ(0); }
        }
        @keyframes floatOrb {
            0%   { -webkit-transform: translate(0, 0) scale(1) translateZ(0); transform: translate(0, 0) scale(1) translateZ(0); }
            100% { -webkit-transform: translate(30px, 20px) scale(1.08) translateZ(0); transform: translate(30px, 20px) scale(1.08) translateZ(0); }
        }

        /* Grid overlay */
        .grid-overlay {
            position: absolute;
            /* Fallback for 'inset' shorthand */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                -webkit-linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                -webkit-linear-gradient(left, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ---- Wrapper content ---- */
        .maintenance-wrapper {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        /* ---- Brand / Logo Area ---- */
        .brand-area {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 3rem;
            animation: fadeSlideDown 0.7s ease both;
        }

        .brand-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            box-shadow: 0 8px 24px rgba(232, 69, 69, 0.4);
        }

        .brand-name {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
        }

        /* ---- Status badge ---- */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(232, 69, 69, 0.15);
            border: 1px solid rgba(232, 69, 69, 0.3);
            color: #ff7676;
            padding: 0.4rem 1.1rem;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 2rem;
            animation: fadeSlideDown 0.7s 0.1s ease both;
        }

        .status-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--primary);
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.2; }
        }

        /* ---- Gear illustration ---- */
        .gear-container {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 2.5rem;
            animation: fadeSlideDown 0.7s 0.2s ease both;
        }

        .gear-ring {
            width: 100%; height: 100%;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            border: 2px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gear-icon {
            font-size: 4rem;
            color: var(--primary);
            animation: spin 8s linear infinite;
            display: block;
        }

        .gear-icon-small {
            position: absolute;
            top: 0; right: 0;
            font-size: 1.8rem;
            color: rgba(255,255,255,0.3);
            animation: spin 5s linear infinite reverse;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ---- Main heading ---- */
        .maintenance-title {
            font-family: var(--font-heading);
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
            animation: fadeSlideDown 0.7s 0.3s ease both;
        }

        .maintenance-title span {
            color: var(--primary);
        }

        .maintenance-subtitle {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.65);
            max-width: 520px;
            line-height: 1.7;
            margin: 0 auto 3rem;
            animation: fadeSlideDown 0.7s 0.4s ease both;
        }

        /* ---- Countdown ---- */
        .countdown-section {
            margin-bottom: 3.5rem;
            animation: fadeSlideDown 0.7s 0.5s ease both;
        }

        .countdown-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.4);
            margin-bottom: 1.25rem;
        }

        .countdown-grid {
            display: flex;
            gap: 1.25rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            min-width: 90px;
            /* backdrop-filter with prefix and fallback */
            background-color: rgba(37, 40, 64, 0.75); /* Fallback for Firefox */
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            -webkit-transition: border-color 0.3s;
            transition: border-color 0.3s;
        }

        .countdown-item:hover {
            border-color: rgba(232,69,69,0.4);
        }

        .countdown-number {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
            display: block;
        }

        .countdown-unit {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.4);
            margin-top: 0.4rem;
            display: block;
        }

        /* ---- Progress bar ---- */
        .progress-section {
            max-width: 480px;
            width: 100%;
            margin: 0 auto 3.5rem;
            animation: fadeSlideDown 0.7s 0.6s ease both;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .progress-text {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
        }

        .progress-pct {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary);
        }

        .progress-track {
            height: 6px;
            border-radius: 100px;
            background: rgba(255,255,255,0.08);
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 100px;
            background: linear-gradient(90deg, var(--primary), #ff7676);
            width: 75%;
            animation: progressGrow 2s ease 1s both;
            box-shadow: 0 0 12px rgba(232,69,69,0.5);
        }

        @keyframes progressGrow {
            from { width: 0%; }
            to   { width: 75%; }
        }

        /* ---- Info cards ---- */
        .info-cards {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 700px;
            margin: 0 auto 3rem;
            animation: fadeSlideDown 0.7s 0.7s ease both;
        }

        .info-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            -webkit-box-flex: 1;
            -ms-flex: 1;
            flex: 1;
            min-width: 180px;
            text-align: left;
            /* backdrop-filter with prefix and fallback */
            background-color: rgba(37, 40, 64, 0.7); /* Fallback for Firefox */
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            -webkit-transition: all 0.3s ease;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            background: rgba(255,255,255,0.07);
            border-color: rgba(232,69,69,0.3);
            transform: translateY(-3px);
        }

        .info-card-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            background: rgba(232,69,69,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
        }

        .info-card-title {
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            color: rgba(255,255,255,0.9);
        }

        .info-card-text {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.45);
            line-height: 1.5;
        }

        /* ---- Contact / Social ---- */
        .contact-section {
            animation: fadeSlideDown 0.7s 0.8s ease both;
        }

        .contact-text {
            font-size: 0.875rem;
            color: rgba(255,255,255,0.4);
            margin-bottom: 1rem;
        }

        .social-links {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .social-link {
            width: 42px; height: 42px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(232,69,69,0.35);
        }

        /* ---- Notify form ---- */
        .notify-form {
            display: flex;
            gap: 0.5rem;
            max-width: 420px;
            margin: 0 auto 2.5rem;
            animation: fadeSlideDown 0.7s 0.75s ease both;
        }

        .notify-input {
            flex: 1;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 0.7rem 1rem;
            color: #fff;
            font-size: 0.9rem;
            font-family: var(--font-body);
            outline: none;
            transition: border-color 0.3s;
        }

        .notify-input::placeholder { color: rgba(255,255,255,0.3); }
        .notify-input:focus { border-color: var(--primary); }

        .notify-btn {
            background: var(--primary);
            border: none;
            border-radius: 8px;
            padding: 0.7rem 1.25rem;
            color: #fff;
            font-weight: 600;
            font-size: 0.875rem;
            font-family: var(--font-body);
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
        }

        .notify-btn:hover {
            background: #c93535;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(232,69,69,0.35);
        }

        /* ---- Footer strip ---- */
        .maintenance-footer {
            position: relative;
            z-index: 2;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 1.25rem 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.25);
        }

        .maintenance-footer a {
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            transition: color 0.2s;
        }

        .maintenance-footer a:hover { color: var(--primary); }

        /* ---- Animations ---- */
        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-18px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ---- Responsive ---- */
        /* Extra small: smartphone kecil < 400px */
        @media (max-width: 400px) {
            .maintenance-title { font-size: 1.6rem; }
            .countdown-number  { font-size: 1.75rem; }
            .countdown-item    { min-width: 60px; padding: 0.75rem 0.6rem; }
            .countdown-grid    { gap: 0.5rem; }
            .info-card         { min-width: 100%; }
            .notify-form       { -webkit-box-orient: vertical; -webkit-box-direction: normal; -ms-flex-direction: column; flex-direction: column; }
            .gear-container    { width: 90px; height: 90px; }
            .gear-icon         { font-size: 2.6rem; }
            .brand-name        { font-size: 1.3rem; }
        }

        /* Small: smartphone standard < 600px */
        @media (max-width: 600px) {
            .maintenance-title { font-size: 2rem; }
            .countdown-number  { font-size: 2rem; }
            .countdown-item    { min-width: 72px; padding: 1rem; }
            .info-card         { min-width: 140px; }
            .notify-form       { -webkit-box-orient: vertical; -webkit-box-direction: normal; -ms-flex-direction: column; flex-direction: column; }
            .gear-container    { width: 110px; height: 110px; }
            .gear-icon         { font-size: 3.2rem; }
        }

        /* Medium: tablet < 768px */
        @media (max-width: 768px) {
            .maintenance-wrapper { padding: 1.5rem 1rem; }
            .info-cards { gap: 0.75rem; }
            .info-card  { min-width: 160px; }
            .countdown-grid { gap: 1rem; }
        }

        /* ---- Notify success state ---- */
        .notify-success {
            display: none;
            align-items: center;
            gap: 0.5rem;
            background: rgba(5, 150, 105, 0.15);
            border: 1px solid rgba(5, 150, 105, 0.3);
            color: #34d399;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-size: 0.875rem;
            max-width: 420px;
            margin: 0 auto 2.5rem;
            animation: fadeSlideDown 0.4s ease;
        }
        .notify-success.show { display: flex; }
    </style>
</head>

<body>

    {{-- Animated background --}}
    <div class="maintenance-bg" aria-hidden="true">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="grid-overlay"></div>
    </div>

    {{-- Main content --}}
    <main class="maintenance-wrapper" role="main">

        {{-- Brand --}}
        <div class="brand-area" aria-label="KIP UNSIKA">
            <div class="brand-icon" aria-hidden="true">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <span class="brand-name">KIP UNSIKA</span>
        </div>

        {{-- Status badge --}}
        <div class="status-badge" role="status" aria-live="polite">
            <span class="status-dot" aria-hidden="true"></span>
            Sedang Maintenance
        </div>

        {{-- Gear illustration --}}
        <div class="gear-container" aria-hidden="true">
            <div class="gear-ring">
                <i class="bi bi-gear-fill gear-icon"></i>
            </div>
            <i class="bi bi-tools gear-icon-small"></i>
        </div>

        {{-- Heading --}}
        <h1 class="maintenance-title">
            Kami Sedang<br><span>Memperbarui</span> Sistem
        </h1>

        <p class="maintenance-subtitle">
            Sistem Informasi KIP Kuliah UNSIKA sedang dalam proses pemeliharaan dan peningkatan.
            Kami akan kembali online secepatnya. Terima kasih atas kesabaranmu!
        </p>

        {{-- Countdown --}}
        <div class="countdown-section" aria-label="Estimasi waktu kembali">
            <p class="countdown-label">Estimasi Kembali Dalam</p>
            <div class="countdown-grid" id="countdown-grid">
                <div class="countdown-item">
                    <span class="countdown-number" id="cd-hours">00</span>
                    <span class="countdown-unit">Jam</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="cd-minutes">00</span>
                    <span class="countdown-unit">Menit</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-number" id="cd-seconds">00</span>
                    <span class="countdown-unit">Detik</span>
                </div>
            </div>
        </div>

        {{-- Progress bar --}}
        <div class="progress-section" aria-label="Progress maintenance">
            <div class="progress-header">
                <span class="progress-text">Progress Pemeliharaan</span>
                <span class="progress-pct" id="progress-pct">75%</span>
            </div>
            <div class="progress-track" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-fill" id="progress-fill"></div>
            </div>
        </div>

        {{-- Info cards --}}
        <div class="info-cards" role="list">
            <div class="info-card" role="listitem">
                <div class="info-card-icon" aria-hidden="true">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="info-card-title">Peningkatan Keamanan</div>
                <div class="info-card-text">Memperbarui sistem keamanan untuk melindungi data mahasiswa.</div>
            </div>
            <div class="info-card" role="listitem">
                <div class="info-card-icon" aria-hidden="true">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <div class="info-card-title">Optimasi Performa</div>
                <div class="info-card-text">Meningkatkan kecepatan dan responsivitas platform.</div>
            </div>
            <div class="info-card" role="listitem">
                <div class="info-card-icon" aria-hidden="true">
                    <i class="bi bi-stars"></i>
                </div>
                <div class="info-card-title">Fitur Baru</div>
                <div class="info-card-text">Menyiapkan fitur-fitur baru untuk pengalaman yang lebih baik.</div>
            </div>
        </div>

        {{-- Notify form --}}
        <form class="notify-form" id="notify-form" onsubmit="handleNotify(event)" aria-label="Form notifikasi">
            <input
                type="email"
                class="notify-input"
                id="notify-email"
                placeholder="Email kamu untuk notifikasi..."
                aria-label="Alamat email"
                autocomplete="email"
            >
            <button type="submit" class="notify-btn" id="notify-btn">
                <i class="bi bi-bell-fill me-1"></i> Beritahu Saya
            </button>
        </form>

        <div class="notify-success" id="notify-success" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            Kami akan memberitahumu saat sistem kembali online!
        </div>

        {{-- Social / Contact --}}
        <div class="contact-section">
            <p class="contact-text">Ikuti kami di media sosial untuk pembaruan terbaru</p>
            <div class="social-links" role="list">
                <a href="https://www.instagram.com/formadikipunsika/" target="_blank" rel="noopener noreferrer"
                   class="social-link" aria-label="Instagram FORMADIKIP" role="listitem">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://wa.me/6283185132009" target="_blank" rel="noopener noreferrer"
                   class="social-link" aria-label="WhatsApp FORMADIKIP" role="listitem">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="mailto:formadikip@unsika.ac.id"
                   class="social-link" aria-label="Email FORMADIKIP" role="listitem">
                    <i class="bi bi-envelope-fill"></i>
                </a>
            </div>
        </div>

    </main>

    {{-- Footer --}}
    <footer class="maintenance-footer">
        <p>
            &copy; {{ date('Y') }} <strong>KIP UNSIKA</strong> &mdash; Pusat Informasi KIP Kuliah Universitas Singaperbangsa Karawang.
            Dikelola oleh <a href="https://www.instagram.com/formadikipunsika/" target="_blank" rel="noopener noreferrer">FORMADIKIP UNSIKA</a>.
        </p>
    </footer>

    <script>
        // ==========================================
        // Countdown Timer
        // Target: sesuaikan tanggal perkiraan selesai maintenance
        // Format: 'YYYY-MM-DDTHH:MM:SS'
        // ==========================================
        const MAINTENANCE_END = new Date(
            (() => {
                // Default: 6 jam dari sekarang (dapat diubah hardcode)
                const d = new Date();
                d.setHours(d.getHours() + 6);
                return d.toISOString();
            })()
        ).getTime();

        function updateCountdown() {
            const now  = Date.now();
            const diff = Math.max(MAINTENANCE_END - now, 0);

            const hours   = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const pad = n => String(n).padStart(2, '0');

            document.getElementById('cd-hours').textContent   = pad(hours);
            document.getElementById('cd-minutes').textContent = pad(minutes);
            document.getElementById('cd-seconds').textContent = pad(seconds);

            if (diff === 0) {
                // Refresh otomatis saat countdown selesai
                setTimeout(() => window.location.reload(), 3000);
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);

        // ==========================================
        // Notify form handler
        // ==========================================
        function handleNotify(e) {
            e.preventDefault();
            const emailInput = document.getElementById('notify-email');
            const email = emailInput.value.trim();

            if (!email) return;

            // Simulate submission (replace with real AJAX if needed)
            const form    = document.getElementById('notify-form');
            const success = document.getElementById('notify-success');
            const btn     = document.getElementById('notify-btn');

            btn.innerHTML = '<span style="display:inline-flex;align-items:center;gap:0.4rem"><span style="width:14px;height:14px;border:2px solid rgba(255,255,255,0.4);border-top-color:#fff;border-radius:50%;animation:spin 0.6s linear infinite;display:inline-block"></span> Mengirim…</span>';
            btn.disabled = true;

            setTimeout(() => {
                form.style.display = 'none';
                success.classList.add('show');
            }, 1200);
        }

        // Add spin keyframe for button loader
        const style = document.createElement('style');
        style.textContent = `@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }`;
        document.head.appendChild(style);
    </script>
</body>

</html>
