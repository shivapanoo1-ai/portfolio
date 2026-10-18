<?php
session_start();
require 'admin/db.php';

/* ---------- LOGOUT (only relevant if admin logged in) ---------- */
if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: index.php");
    exit;
}

/* ---------- AUTH CHECK (optional — site is public) ---------- */
$isLoggedIn = isset($_SESSION['admin_id']);
$isAdmin    = $isLoggedIn && (($_SESSION['admin_role'] ?? '') === 'admin');

/* ---------- CURRENT USER (only meaningful if logged in) ---------- */
$userName = $_SESSION['admin_name'] ?? ($_SESSION['admin_username'] ?? 'Admin');
$userRole = $_SESSION['admin_role'] ?? 'guest';

/* ---------- DATA FETCH ---------- */
$heroStats       = fetchAll($pdo, "SELECT * FROM stats WHERE section='hero' ORDER BY display_order");
$communityStats  = fetchAll($pdo, "SELECT * FROM community_stats ORDER BY display_order");
$services        = fetchAll($pdo, "SELECT * FROM services ORDER BY display_order");
$portfolioItems  = fetchAll($pdo, "SELECT * FROM portfolio ORDER BY display_order");
$teamMembers     = fetchAll($pdo, "SELECT * FROM team ORDER BY display_order");
$platforms       = fetchAll($pdo, "SELECT * FROM platforms ORDER BY display_order");
$testimonials    = fetchAll($pdo, "SELECT * FROM testimonials ORDER BY display_order");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Shiwa - Full Stack Developer & Graphic Designer from Pakistan">
    <title>Shiwa - Full Stack Developer</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ============ RESET & VARIABLES ============ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --primary-blue: #00a8ff;
            --primary-dark: #008ecc;
            --text-white: #ffffff;
            --text-light: #cbd5e1;
            --text-dark: #0f172a;
            --text-body: #475569;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
            --orbit-radius: 160px;
            --nav-height: 90px;
        }

        /* ============ HIDE SCROLLBAR GLOBALLY ============ */
        html, body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        html::-webkit-scrollbar,
        body::-webkit-scrollbar,
        *::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
            scroll-padding-top: 90px;
        }

        body {
            background-image: url('images/jj.png');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-color: #031427;
            color: var(--text-white);
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
            line-height: 1.6;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* ============ NAVIGATION ============ */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 5%;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        /* ===== TOP LEFT TEXT LOGO ===== */
        .text-logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--text-white);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
        }

        .text-logo span {
            color: var(--primary-blue);
            text-shadow: 0 0 15px rgba(0, 168, 255, 0.8);
        }

        .text-logo:hover {
            transform: scale(1.05);
        }

        nav.scrolled .text-logo {
            color: var(--text-dark);
            text-shadow: none;
        }
        nav.scrolled .text-logo span {
            text-shadow: 0 0 12px rgba(0, 168, 255, 0.5);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
            align-items: center;
            margin-left: auto;
        }

        .nav-links a {
            color: var(--text-white);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.6);
            position: relative;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary-blue);
        }

        .nav-links a:not(.nav-dashboard):not(.nav-user):not(.btn-quote)::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-blue);
            transition: width 0.3s ease;
        }
        .nav-links a:not(.nav-dashboard):not(.nav-user):not(.btn-quote):hover::after,
        .nav-links a.active:not(.nav-dashboard):not(.nav-user):not(.btn-quote)::after {
            width: 100%;
        }

        .nav-dashboard {
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
            background: rgba(0, 168, 255, 0.15);
            border: 1px solid rgba(0, 168, 255, 0.5);
            padding: 6px 14px !important;
            border-radius: 50px;
            color: #00d4ff !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }
        .nav-dashboard:hover {
            background: rgba(0, 168, 255, 0.3) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 168, 255, 0.4);
        }

        .nav-user {
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
            background: rgba(0, 168, 255, 0.12);
            border: 1px solid rgba(0, 168, 255, 0.4);
            padding: 6px 14px !important;
            border-radius: 50px;
            color: var(--primary-blue) !important;
            font-weight: 600 !important;
            transition: all 0.3s ease !important;
        }
        .nav-user:hover {
            background: rgba(239, 68, 68, 0.15) !important;
            border-color: rgba(239, 68, 68, 0.5);
            color: #f87171 !important;
            transform: translateY(-2px);
        }

        .btn-quote {
            background-color: var(--primary-blue);
            color: var(--text-white);
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0, 168, 255, 0.4);
        }

        .btn-quote:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 168, 255, 0.6);
        }

        .nav-cta-li {
            list-style: none;
            display: flex;
            align-items: center;
        }

        nav.scrolled {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            position: fixed;
        }

        nav.scrolled .nav-links a {
            color: var(--text-dark);
            text-shadow: none;
        }
        nav.scrolled .nav-links a:hover,
        nav.scrolled .nav-links a.active { color: var(--primary-blue); }

        nav.scrolled .nav-dashboard {
            color: var(--primary-dark) !important;
            background: rgba(0, 168, 255, 0.1);
        }
        nav.scrolled .nav-user {
            color: var(--primary-dark) !important;
            background: rgba(0, 168, 255, 0.08);
        }
        nav.scrolled .nav-user:hover {
            color: #dc2626 !important;
            background: rgba(239, 68, 68, 0.1);
        }

        .menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-white);
            font-size: 22px;
            cursor: pointer;
            padding: 8px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            line-height: 1;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }

        nav.scrolled .menu-toggle { color: var(--text-dark); }

        .menu-toggle:hover { background: rgba(255, 255, 255, 0.1); }
        nav.scrolled .menu-toggle:hover { background: rgba(0, 0, 0, 0.05); }

        /* ============ HERO SECTION ============ */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 80px 5% 100px;
            background: transparent;
            position: relative;
            gap: 40px;
        }

        .hero-left {
            flex: 1;
            max-width: 640px;
            z-index: 10;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 168, 255, 0.2);
            border: 1px solid rgba(0, 168, 255, 0.6);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            color: #00d4ff;
            margin-bottom: 24px;
            backdrop-filter: blur(15px);
            margin-top: 25px;
            font-weight: 600;
            text-shadow: 0 0 10px rgba(0, 168, 255, 0.5);
            box-shadow: 0 0 20px rgba(0, 168, 255, 0.3);
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            background-color: #00ff88;
            border-radius: 50%;
            box-shadow: 0 0 12px #00ff88, 0 0 20px #00ff88;
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.15); }
        }

        /* ============ HERO SLIDER ============ */
        .hero-slider {
            position: relative;
            margin-bottom: 26px;
            overflow: visible;
            background: none;
            border: none;
            border-radius: 0;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            padding: 0;
            min-height: auto;
            animation: none;
        }

        .hero-slider::before { display: none; content: none; }

        .hero-slider .slide {
            display: none;
            position: relative;
        }

        .hero-slider .slide.active {
            display: block;
            animation: heroFadeIn 0.55s ease both;
        }

        .hero-slider .slide h1 {
            font-size: 60px;
            font-weight: 900;
            line-height: 1.08;
            margin-bottom: 18px;
            color: #ffffff;
            letter-spacing: -1.5px;
            text-shadow:
                0 2px 20px rgba(0, 0, 0, 0.9),
                0 4px 30px rgba(0, 0, 0, 0.7);
            animation: heroTextIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .hero-slider .slide h1 span {
            color: var(--primary-blue);
            text-shadow: 0 0 35px rgba(0, 168, 255, 0.7);
        }

        .hero-slider .slide p {
            font-size: 16px;
            line-height: 1.75;
            color: #cbd5e1;
            text-shadow: 0 1px 10px rgba(0, 0, 0, 0.9);
            margin-bottom: 0;
            font-weight: 400;
            animation: heroTextIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.12s both;
        }

        @keyframes heroFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes heroTextIn {
            from { opacity: 0; transform: translateY(30px) scale(0.98); filter: blur(5px); }
            to   { opacity: 1; transform: translateY(0) scale(1);       filter: blur(0); }
        }

        /* ============ SLIDER BULLETS ============ */
        .slider-dots {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 10px;
            margin-top: 22px;
            margin-bottom: 8px;
            padding: 0;
            height: auto;
            visibility: visible;
            overflow: visible;
        }

        .slider-dots button {
            width: 40px;
            height: 4px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.25);
            border: none;
            cursor: pointer;
            padding: 0;
            position: relative;
            overflow: hidden;
            transition: background 0.3s ease;
            outline: none;
        }

        .slider-dots button:hover {
            background: rgba(255, 255, 255, 0.45);
        }

        .slider-dots button.active {
            background: rgba(255, 255, 255, 0.45);
        }

        .slider-dots button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 0%;
            background: var(--primary-blue);
            border-radius: 4px;
            transition: width 0s;
        }

        .slider-dots button.active::after {
            animation: dotProgress 2s linear forwards;
        }

        @keyframes dotProgress {
            from { width: 0%; }
            to   { width: 100%; }
        }

        .hero-left h1 {
            font-size: 52px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 22px;
            color: #ffffff;
            text-shadow:
                0 0 30px rgba(0, 0, 0, 1),
                0 2px 15px rgba(0, 0, 0, 0.95),
                0 4px 25px rgba(0, 0, 0, 0.85);
            letter-spacing: -0.5px;
        }

        .hero-left h1 span { color: var(--primary-blue); }

        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #e2e8f0;
            font-weight: 500;
            margin-top: 12px;
        }

        .feature-item i {
            color: var(--primary-blue);
            font-size: 10px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: var(--primary-blue);
            color: #ffffff;
            padding: 15px 34px;
            border-radius: 6px;
            text-decoration: none;
            margin-top: 80px;
            font-size: 16px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(0, 168, 255, 0.35);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow:
                0 10px 35px rgba(0, 168, 255, 0.8),
                0 0 60px rgba(0, 168, 255, 0.5);
        }

        /* ============ ORBITING ANIMATION ============ */
        .hero-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            min-height: 400px;
        }

        .animation-container {
            position: relative;
            width: 400px;
            height: 400px;
            max-width: 100%;
        }

        .floating-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -25px;
            margin-left: -25px;
            width: 55px;
            height: 55px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            color: black;
            animation: orbit 15s linear infinite;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25),
                        0 0 18px rgba(0, 168, 255, 0.35);
        }

        @keyframes orbit {
            0%   { transform: rotate(0deg) translateX(var(--orbit-radius)) rotate(0deg); }
            100% { transform: rotate(360deg) translateX(var(--orbit-radius)) rotate(-360deg); }
        }

        .icon-1 { animation-delay: 0s;        color: #f39c12; }
        .icon-2 { animation-delay: -1.6667s;  color: #e74c3c; }
        .icon-3 { animation-delay: -3.3333s;  color: #9b59b6; }
        .icon-4 { animation-delay: -5s;       color: #2ecc71; }
        .icon-5 { animation-delay: -6.6667s;  color: #3498db; }
        .icon-6 { animation-delay: -8.3333s;  color: #e67e22; }
        .icon-7 { animation-delay: -10s;      color: #1abc9c; }
        .icon-8 { animation-delay: -11.6667s; color: #f1c40f; }
        .icon-9 { animation-delay: -13.3333s; color: #ff6b9d; }

        /* ============================================================
           ANALOG CLOCK + WORLD IMAGE (100% clear)
           ============================================================ */

        .hero-right .center-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px;
            height: 150px;
            border: 3px solid rgba(0, 168, 255, 0.8);
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: none;
            box-shadow:
                0 0 25px rgba(0, 168, 255, 0.6),
                0 0 60px rgba(0, 168, 255, 0.4);
            z-index: 10;
            animation: none;
        }

        .clock-face {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: #ffffff;
            overflow: hidden;
        }

        /* ===== WORLD IMAGE — 100% CLEAR & VISIBLE ===== */
        .clock-bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 50%;
            opacity: 1;
            filter: none;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* Subtle ticks overlay — very light so image stays visible */
        .clock-face::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: repeating-conic-gradient(
                from 0deg,
                rgba(15, 23, 42, 0.85) 0deg 2deg,
                transparent 2deg 30deg
            );
            -webkit-mask: radial-gradient(circle, transparent 78%, #000 79%, #000 94%, transparent 95%);
                    mask: radial-gradient(circle, transparent 78%, #000 79%, #000 94%, transparent 95%);
            pointer-events: none;
            z-index: 2;
        }

        .hand {
            position: absolute;
            bottom: 50%;
            left: 50%;
            transform-origin: bottom center;
            transform: translateX(-50%) rotate(0deg);
            border-radius: 10px;
            will-change: transform;
            z-index: 3;
        }

        .hour-hand {
            width: 6%;
            height: 28%;
            background: linear-gradient(to top, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.9), 0 0 3px rgba(0, 0, 0, 0.7);
        }

        .minute-hand {
            width: 4%;
            height: 40%;
            background: linear-gradient(to top, #0f172a 0%, #334155 100%);
            box-shadow: 0 0 5px rgba(255, 255, 255, 0.9), 0 0 3px rgba(0, 0, 0, 0.7);
        }

        .second-hand {
            width: 1.8%;
            height: 46%;
            background: linear-gradient(to top, #ef4444 0%, #ff7a7a 100%);
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.9), 0 0 8px rgba(239, 68, 68, 0.9);
        }

        .clock-pin {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 9%;
            height: 9%;
            background: radial-gradient(circle at 35% 35%, #ff8080, #ef4444 70%, #b91c1c);
            border-radius: 50%;
            box-shadow:
                0 0 8px rgba(239, 68, 68, 0.9),
                0 0 15px rgba(239, 68, 68, 0.5),
                0 0 0 2px rgba(255, 255, 255, 0.9);
            z-index: 5;
        }

        .clock-pin::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 45%;
            height: 45%;
            background: #ffffff;
            border-radius: 50%;
        }

        /* ============ STATS WRAPPER ============ */
        .stats-wrapper {
            background-color: #ffffff;
            color: #031427;
            position: relative;
            padding-bottom: 50px;
            margin-top: -1px;
        }

        .custom-shape-divider-bottom {
            position: absolute;
            top: -1px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .custom-shape-divider-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }

        .custom-shape-divider-bottom .shape-fill { fill: #ffffff; }

        .stats-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
            padding: 80px 20px 20px;
        }

        .stat-item {
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-item h2 {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .stat-item p {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ============ ABOUT SECTION ============ */
        .about-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 140px 5% 80px;
            position: relative;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 60%);
        }

        .about-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 70px;
            max-width: 1200px;
            width: 100%;
            flex-wrap: wrap;
            margin-top: -90px;
            
        }

        .about-image-wrapper {
            flex: 1;
            min-width: 300px;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .about-image-frame {
            position: relative;
            width: 380px;
            height: 480px;
            max-width: 100%;
            border-radius: 20px;
            overflow: hidden;
            border: 4px solid #ffffff;
            box-shadow:
                0 10px 40px rgba(0, 168, 255, 0.2),
                0 20px 60px rgba(0, 0, 0, 0.1);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .about-image-frame:hover {
            transform: translateY(-8px);
            box-shadow:
                0 15px 50px rgba(0, 168, 255, 0.35),
                0 25px 70px rgba(0, 0, 0, 0.15);
        }

        .about-image-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-image-wrapper::before {
            content: '';
            position: absolute;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.25), transparent 70%);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            filter: blur(40px);
        }

        .image-badge {
            position: absolute;
            bottom: 30px;
            right: -20px;
            background: #ffffff;
            border: 1px solid var(--border-light);
            padding: 14px 22px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .image-badge i { font-size: 26px; color: var(--primary-blue); }

        .image-badge .badge-info h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .image-badge .badge-info p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .about-content { flex: 1; min-width: 300px; }

        .about-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 168, 255, 0.1);
            border: 1px solid rgba(0, 168, 255, 0.3);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            color: var(--primary-blue);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .about-content h2 {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .about-content h2 span { color: var(--primary-blue); }

        .about-content .subtitle {
            font-size: 16px;
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 22px;
        }

        .about-content p {
            color: var(--text-body);
            font-size: 15.5px;
            line-height: 1.75;
            margin-bottom: 18px;
        }

        .about-info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px 24px;
            margin: 26px 0 30px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: var(--text-body);
        }

        .info-item i {
            color: var(--primary-blue);
            font-size: 14px;
            width: 18px;
            text-align: center;
        }

        .info-item strong {
            color: var(--text-dark);
            font-weight: 600;
            margin-right: 4px;
        }

        .skills-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 32px;
        }

        .skill-tag {
            background: rgba(0, 168, 255, 0.08);
            border: 1px solid rgba(0, 168, 255, 0.25);
            color: var(--primary-blue);
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .skill-tag:hover {
            background: rgba(0, 168, 255, 0.2);
            transform: translateY(-2px);
        }

        .about-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;

        }
        /* ===== About section buttons – force SAME size ===== */
        .about-buttons .btn-primary,
        .about-buttons .btn-outline {
            margin-top: 0;
            padding: 0 34px;
            height: 54px;
            min-width: 210px;
            font-size: 15.5px;
            font-weight: 700;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            line-height: 1;
            box-sizing: border-box;
            text-decoration: none;
        }

        /* keep the outline look but same dimensions */
        .about-buttons .btn-outline {
            background: transparent;
            color: var(--text-dark);
            border: 1px solid var(--border-light);
            box-shadow: none;
        }

        .about-buttons .btn-outline:hover {
            background: rgba(0, 168, 255, 0.08);
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        /* ============ SECTION HEADERS ============ */
        .services-section,
        .portfolio-section,
        .community-section {
            min-height: 100vh;
            padding: 140px 5% 80px;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 60%);
            position: relative;
        
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 60px;
            margin-top: -90px;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(0, 168, 255, 0.1);
            border: 1px solid rgba(0, 168, 255, 0.3);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            color: var(--primary-blue);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .section-header h1 {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 16px;
            color: var(--text-dark);
        }

        .section-header h1 span { color: var(--primary-blue); }

        .section-header p {
            color: var(--text-body);
            font-size: 16px;
            line-height: 1.7;
        }

        /* ============ SERVICES ============ */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .service-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 18px;
            padding: 36px 30px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: linear-gradient(90deg, var(--primary-blue), #00d4ff);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 50px rgba(0, 168, 255, 0.15);
            border-color: rgba(0, 168, 255, 0.3);
        }

        .service-card:hover::before { transform: scaleX(1); }

        .service-icon {
            width: 68px;
            height: 68px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(0, 168, 255, 0.15), rgba(0, 212, 255, 0.08));
            border: 1px solid rgba(0, 168, 255, 0.25);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            color: var(--primary-blue);
            margin-bottom: 24px;
            transition: all 0.4s ease;
        }

        .service-card:hover .service-icon {
            background: linear-gradient(135deg, var(--primary-blue), #00d4ff);
            color: #ffffff;
            transform: rotate(-5deg) scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 168, 255, 0.4);
        }

        .service-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--text-dark);
        }

        .service-card p {
            color: var(--text-body);
            font-size: 14.5px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .service-features { list-style: none; margin-bottom: 24px; }

        .service-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .service-features li i { color: var(--primary-blue); font-size: 12px; }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: gap 0.3s ease;
        }

        .service-link:hover { gap: 14px; }

        /* ============ PORTFOLIO ============ */
        .filter-buttons {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 50px;
        }

        .filter-btn {
            background: #ffffff;
            border: 1px solid var(--border-light);
            color: var(--text-body);
            padding: 10px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .filter-btn:hover {
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        .filter-btn.active {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(0, 168, 255, 0.4);
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .portfolio-item {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid var(--border-light);
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .portfolio-item.hide { display: none; }

        .portfolio-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px rgba(0, 168, 255, 0.2);
            border-color: rgba(0, 168, 255, 0.3);
        }

        .portfolio-image {
            position: relative;
            width: 100%;
            height: 260px;
            overflow: hidden;
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
        }

        .portfolio-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .portfolio-item:hover .portfolio-image img { transform: scale(1.1); }

        .portfolio-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(180deg, rgba(3, 20, 39, 0.4) 0%, rgba(3, 20, 39, 0.95) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 30px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .portfolio-item:hover .portfolio-overlay { opacity: 1; }

        .portfolio-overlay .category-tag {
            display: inline-block;
            background: var(--primary-blue);
            color: #ffffff;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
            align-self: flex-start;
        }

        .portfolio-overlay h3 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .portfolio-overlay p {
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .view-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            align-self: flex-start;
        }

        .view-btn i { color: var(--primary-blue); font-size: 12px; }

        .portfolio-info {
            padding: 22px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .portfolio-info h3 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .portfolio-item:hover .portfolio-info h3 { color: var(--primary-blue); }

        .portfolio-info .tech { font-size: 13px; color: var(--text-muted); }

        .portfolio-info .arrow-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(0, 168, 255, 0.1);
            color: var(--primary-blue);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .portfolio-item:hover .arrow-icon {
            background: var(--primary-blue);
            color: #ffffff;
            transform: rotate(-45deg);
        }

        /* ============ COMMUNITY STATS ============ */
        .community-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto 100px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 30px 24px;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 50%;
            transform: translateX(-50%);
            width: 0; height: 3px;
            background: linear-gradient(90deg, var(--primary-blue), #00d4ff);
            transition: width 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0, 168, 255, 0.15);
            border-color: rgba(0, 168, 255, 0.3);
        }

        .stat-card:hover::before { width: 60%; }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(0, 168, 255, 0.15), rgba(0, 212, 255, 0.08));
            border: 1px solid rgba(0, 168, 255, 0.25);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            color: var(--primary-blue);
            margin: 0 auto 18px;
            transition: all 0.4s ease;
        }

        .stat-card:hover .stat-icon {
            background: linear-gradient(135deg, var(--primary-blue), #00d4ff);
            color: #ffffff;
            transform: rotate(-5deg) scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 168, 255, 0.4);
        }

        .stat-card h3 {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .stat-card h3 span { color: var(--primary-blue); }

        .stat-card p {
            font-size: 13.5px;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ============ SUB HEADER ============ */
        .sub-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .sub-header h2 {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .sub-header h2 span { color: var(--primary-blue); }

        .sub-header p {
            color: var(--text-body);
            font-size: 15px;
            line-height: 1.7;
        }

        /* ============ TEAM SECTION ============ */
        .team-section { max-width: 1200px; margin: 0 auto 100px; }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 26px;
        }

        .team-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 18px;
            overflow: hidden;
            text-align: center;
            transition: all 0.4s ease;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 55px rgba(0, 168, 255, 0.18);
            border-color: rgba(0, 168, 255, 0.3);
        }

        .team-image {
            position: relative;
            width: 100%;
            height: 300px;
            overflow: hidden;
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
        }

        .team-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .team-card:hover .team-image img { transform: scale(1.1); }

        .team-social {
            position: absolute;
            bottom: 0; left: 0;
            width: 100%;
            padding: 14px;
            background: linear-gradient(180deg, transparent, rgba(3, 20, 39, 0.85));
            display: flex;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease;
        }

        .team-card:hover .team-social {
            opacity: 1;
            transform: translateY(0);
        }

        .team-social a {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .team-social a:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            transform: translateY(-3px) scale(1.1);
        }

        .team-info { padding: 20px 16px 24px; }

        .team-info h4 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
            transition: color 0.3s;
        }

        .team-card:hover .team-info h4 { color: var(--primary-blue); }

        .team-info p {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ============ PLATFORMS ============ */
        .platforms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            max-width: 1100px;
            margin: 0 auto 100px;
        }

        .platform-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 30px 26px;
            display: flex;
            align-items: center;
            gap: 18px;
            text-decoration: none;
            transition: all 0.4s ease;
        }

        .platform-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0, 168, 255, 0.15);
            border-color: rgba(0, 168, 255, 0.3);
        }

        .platform-icon {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 26px;
            color: #ffffff;
            flex-shrink: 0;
            transition: transform 0.4s ease;
        }

        .platform-card:hover .platform-icon { transform: scale(1.1) rotate(-5deg); }

        .platform-icon.discord   { background: linear-gradient(135deg, #5865F2, #7289DA); }
        .platform-icon.github    { background: linear-gradient(135deg, #24292e, #404448); }
        .platform-icon.twitter   { background: linear-gradient(135deg, #1DA1F2, #0d8ddb); }
        .platform-icon.youtube   { background: linear-gradient(135deg, #FF0000, #cc0000); }
        .platform-icon.linkedin  { background: linear-gradient(135deg, #0A66C2, #004182); }
        .platform-icon.instagram { background: linear-gradient(135deg, #f09433, #dc2743, #bc1888); }

        .platform-info h4 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .platform-info p { font-size: 13.5px; color: var(--text-muted); }

        .platform-info .member-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--primary-blue);
        }

        /* ============ TESTIMONIALS ============ */
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 26px;
            max-width: 1100px;
            margin: 0 auto 100px;
        }

        .testimonial-card {
            background: #ffffff;
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 32px 28px;
            position: relative;
            transition: all 0.4s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0, 168, 255, 0.15);
            border-color: rgba(0, 168, 255, 0.3);
        }

        .quote-icon {
            position: absolute;
            top: 22px;
            right: 26px;
            font-size: 42px;
            color: rgba(0, 168, 255, 0.15);
            line-height: 1;
        }

        .testimonial-rating {
            color: #fbbf24;
            font-size: 14px;
            margin-bottom: 16px;
            display: flex;
            gap: 3px;
        }

        .testimonial-text {
            color: var(--text-body);
            font-size: 14.5px;
            line-height: 1.75;
            margin-bottom: 22px;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 14px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
        }

        .author-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), #00d4ff);
            display: flex;
            justify-content: center;
            align-items: center;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .author-info h5 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 2px;
        }

        .author-info p { font-size: 12.5px; color: var(--text-muted); }

        /* ============ CTA SECTION ============ */
        .cta-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, #031427 0%, #0a2a4a 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.3), transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
        }

        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.2), transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
        }

        .cta-container {
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .cta-container h2 {
            font-size: 40px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .cta-container h2 span { color: var(--primary-blue); }

        .cta-container p {
            color: #94a3b8;
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: var(--primary-blue);
            color: #ffffff;
            padding: 15px 36px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 168, 255, 0.4);
        }

        .btn-cta:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 168, 255, 0.6);
        }

        /* ============ FOOTER ============ */
        .footer {
            background: #031427;
            padding: 30px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer p { color: #94a3b8; font-size: 14px; }

        /* ============================================================
           RESPONSIVE - TABLET (<= 992px)
           ============================================================ */
        @media (max-width: 992px) {

            nav { padding: 10px 5%; }

            .text-logo { font-size: 20px; letter-spacing: 1.5px; }

            .menu-toggle { display: flex; }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                flex-direction: column;
                gap: 0;
                background: rgba(255, 255, 255, 0.99);
                backdrop-filter: blur(10px);
                padding: 6px 5% 18px;
                box-shadow: 0 14px 35px rgba(0, 0, 0, 0.15);
                border-top: 1px solid var(--border-light);
                align-items: stretch;
                max-height: calc(100vh - 76px);
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                margin-left: 0;
            }

            .nav-links.open { display: flex; }

            .nav-links li {
                padding: 0;
                border-bottom: 1px solid var(--border-light);
                list-style: none;
            }

            .nav-links li:last-child { border-bottom: none; }

            .nav-links a:not(.btn-quote) {
                color: var(--text-dark) !important;
                font-size: 15px;
                font-weight: 600;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 14px 6px;
                text-shadow: none;
                width: 100%;
            }

            .nav-links a::after { display: none !important; }

            .nav-dashboard,
            .nav-user {
                background: rgba(0, 168, 255, 0.08) !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 14px 6px !important;
                display: flex !important;
                width: 100%;
                justify-content: center;
                transform: none !important;
                box-shadow: none !important;
            }

            .nav-cta-li {
                padding: 16px 0 0 !important;
                border-bottom: none !important;
            }

            .nav-links .btn-quote {
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
                padding: 13px 20px;
                font-size: 14.5px;
                color: #ffffff !important;
            }

            .hero {
                flex-direction: column;
                text-align: center;
                padding: 130px 5% 70px;
                gap: 50px;
                min-height: auto;
            }

            .hero-left { max-width: 100%; }

            .badge { margin: 0 auto 22px; }

            .hero-slider {
                padding: 0;
                min-height: auto;
                margin-bottom: 24px;
            }

            .hero-slider .slide h1 { font-size: 42px; letter-spacing: -1px; }
            .hero-slider .slide p { font-size: 15px; }

            .features { justify-content: center; }
            .feature-item { margin-top: 0; }

            .hero-right { min-height: auto; }

            .about-container {
                flex-direction: column;
                gap: 50px;
                text-align: center;
            }

            .about-content h2 { font-size: 34px; }
            .about-badge { margin: 0 auto 20px; }
            .about-info-grid { justify-items: center; text-align: left; }
            .skills-tags { justify-content: center; }
            .about-buttons { justify-content: center; }

            .about-image-frame {
                width: 320px;
                height: 400px;
            }

            .image-badge { right: 0; }

            .section-header h1 { font-size: 34px; }
            .sub-header h2 { font-size: 26px; }
            .cta-container h2 { font-size: 30px; }

            .team-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 22px;
            }

            .slider-dots {
                justify-content: center;
            }
        }

        /* ============================================================
           RESPONSIVE - MOBILE (<= 768px)
           ============================================================ */
        @media (max-width: 768px) {

            .hero {
                padding: 115px 5% 60px;
                gap: 45px;
            }

            .hero-slider { padding: 0; min-height: auto; margin-bottom: 22px; }
            .hero-slider .slide h1 { font-size: 32px; line-height: 1.15; }
            .hero-slider .slide p { font-size: 14px; line-height: 1.7; }

            .animation-container {
                width: 320px;
                height: 320px;
            }

            :root { --orbit-radius: 128px; }

            .center-logo { width: 120px; height: 120px; }

            .floating-icon {
                width: 52px;
                height: 52px;
                font-size: 18px;
                margin-top: -26px;
                margin-left: -26px;
            }

            .team-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }

            .team-image { height: 200px; }
            .team-info h4 { font-size: 15px; }

            .portfolio-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }

            .services-grid { grid-template-columns: 1fr; }

            .slider-dots {
                justify-content: center;
            }
        }

        /* ============================================================
           RESPONSIVE - SMALL MOBILE (<= 480px)
           ============================================================ */
        @media (max-width: 480px) {

            :root { --orbit-radius: 100px; }

            nav { padding: 10px 4%; }

            .text-logo { font-size: 16px; letter-spacing: 1px; }

            .menu-toggle { font-size: 20px; padding: 7px 9px; }

            .nav-links { padding: 4px 4% 16px; }

            .nav-links a:not(.btn-quote) { font-size: 14.5px; padding: 13px 6px; }

            .hero {
                padding: 100px 5% 55px;
                gap: 38px;
            }

            .badge {
                font-size: 11.5px;
                padding: 7px 14px;
                margin-bottom: 18px;
            }

            .hero-slider {
                padding: 0;
                min-height: auto;
                margin-bottom: 20px;
                border-radius: 0;
                border-left: none;
            }

            .hero-slider .slide h1 {
                font-size: 26px;
                line-height: 1.2;
                letter-spacing: -0.5px;
                margin-bottom: 12px;
            }

            .hero-slider .slide p {
                font-size: 13px;
                line-height: 1.65;
            }

            .features {
                gap: 10px 16px;
                margin-bottom: 18px;
            }

            .feature-item {
                font-size: 12.5px;
                margin-top: 0;
            }

            .btn-primary {
                padding: 13px 24px;
                font-size: 14px;
            }

            .animation-container {
                width: 270px;
                height: 270px;
            }

            .center-logo {
                width: 100px;
                height: 100px;
                border-width: 2px;
            }

            .floating-icon {
                width: 44px;
                height: 44px;
                font-size: 15px;
                margin-top: -22px;
                margin-left: -22px;
            }

            .stats-container {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 24px 10px;
                padding: 60px 16px 20px;
            }

            .stat-item { flex: 0 0 45%; }

            .stat-item h2 { font-size: 26px; }
            .stat-item p { font-size: 12.5px; }

            .about-section,
            .services-section,
            .portfolio-section,
            .community-section {
                padding: 100px 4% 50px;
            }

            .about-image-frame {
                width: 260px;
                height: 320px;
            }

            .image-badge {
                bottom: 15px;
                right: -5px;
                padding: 10px 16px;
            }

            .image-badge i { font-size: 20px; }
            .image-badge .badge-info h4 { font-size: 15px; }

            .about-content h2 { font-size: 26px; }
            .about-content p { font-size: 14.5px; }
            .about-info-grid { grid-template-columns: 1fr; }
            .info-item { font-size: 13px; }

            .about-buttons { width: 100%; }
            .about-buttons .btn-primary,
            .about-buttons .btn-outline {
                flex: 1 1 100%;
                justify-content: center;
            }

            .section-header { margin-bottom: 40px; }
            .section-header h1 { font-size: 26px; }
            .section-header p { font-size: 14px; }

            .sub-header h2 { font-size: 22px; }
            .sub-header p { font-size: 13.5px; }

            .filter-buttons { gap: 8px; margin-bottom: 35px; }
            .filter-btn { padding: 8px 16px; font-size: 12.5px; }

            .portfolio-grid,
            .services-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .portfolio-image { height: 220px; }

            .community-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
                margin-bottom: 70px;
            }

            .stat-card { padding: 20px 14px; }
            .stat-card h3 { font-size: 24px; }
            .stat-card p { font-size: 11.5px; }
            .stat-icon { width: 48px; height: 48px; font-size: 18px; }

            .team-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .team-image { height: 150px; }

            .team-info { padding: 12px 8px 16px; }
            .team-info h4 { font-size: 13px; }
            .team-info p { font-size: 11px; }

            .team-social a {
                width: 26px;
                height: 26px;
                font-size: 10px;
            }

            .platform-card {
                padding: 20px 16px;
                gap: 14px;
            }

            .platform-icon {
                width: 48px;
                height: 48px;
                font-size: 20px;
            }

            .platform-info h4 { font-size: 15px; }
            .platform-info p { font-size: 12px; }

            .testimonial-card { padding: 24px 20px; }
            .testimonial-text { font-size: 13.5px; }
            .quote-icon { font-size: 34px; }

            .cta-section { padding: 60px 5%; }
            .cta-container h2 { font-size: 22px; }
            .cta-container p { font-size: 13.5px; margin-bottom: 24px; }

            .btn-cta {
                padding: 12px 24px;
                font-size: 14px;
            }

            .footer p { font-size: 12.5px; }

            .slider-dots button {
                width: 30px;
                height: 3px;
            }
        }

        /* ============ VERY SMALL (<= 360px) ============ */
        @media (max-width: 360px) {
            .hero-slider .slide h1 { font-size: 22px; }
            .animation-container { width: 240px; height: 240px; }
            .center-logo { width: 88px; height: 88px; }
            .floating-icon {
                width: 38px;
                height: 38px;
                font-size: 13px;
                margin-top: -19px;
                margin-left: -19px;
            }
            :root { --orbit-radius: 88px; }
            .team-grid { grid-template-columns: 1fr; }
            .community-stats { grid-template-columns: 1fr; }
            .stat-item { flex: 0 0 100%; }
            .text-logo { font-size: 15px; letter-spacing: 1px; }
            .slider-dots button { width: 24px; height: 3px; }
        }

        /* ============ ANIMATIONS ============ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav id="mainNav">
        <!-- ===== TOP LEFT TEXT LOGO ===== -->
        <div class="text-logo">S H <span>I W A</span></div>

        <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#portfolio">Portoflio</a></li>
            <li><a href="#community">Community</a></li>

            <?php if ($isAdmin): ?>
                <!-- ✅ SIRF ADMIN ko dikhega -->
                <li>
                    <a href="admin/dashboard.php" class="nav-dashboard">
                        <i class="fas fa-gauge-high"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="?logout=1" class="nav-user"
                       onclick="return confirm('Are you sure you want to logout?');">
                        <i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($userName) ?>
                    </a>
                </li>
            <?php endif; ?>

            <!-- ✅ Sabko dikhega -->
            <li class="nav-cta-li">
                <a href="contact.php" class="btn-quote">
                    <i class="fas fa-paper-plane"></i> Hire Me
                </a>
            </li>
        </ul>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="home">
        <div class="hero-left">
            <div class="badge">
                <span class="badge-dot"></span> Available for New Projects
            </div>

            <div class="hero-slider" id="heroSlider">
                <div class="slide active">
                    <h1>Full Stack Developer <br><span>You Can Trust</span></h1>
                    <p>I help businesses build web solutions that scale. From complex web applications to e-commerce with full reponsive, grow your Businsess</p>
                </div>
                <div class="slide">
                    <h1>Custom Web & <br><span>App Development</span></h1>
                    <p>From concept to deployment — I build tailored web applications that perfectly fit your business. Modern stack, clean architecture, with performance.</p>
                </div>
                <div class="slide">
                    <h1>E-Commerce <br><span>Solutions</span></h1>
                    <p>Launch a powerful online store with secure payments, fast checkout, and inventory management. Built to convert visitors into loyal customers.</p>
                </div>
                <div class="slide">
                    <h1>WordPress &amp; <br><span>CMS Development</span></h1>
                    <p>Custom WordPress themes, plugins, and headless CMS solutions. Fully responsive, SEO-optimized, and you to manage without touching code.</p>
                </div>
                <div class="slide">
                    <h1>REST API &amp; <br><span>Backend Systems</span></h1>
                    <p>Scalable backend architecture and secure APIs that power your apps. Built with Node.js, PHP, and databases tuned for real-world performance.</p>
                </div>
                <div class="slide">
                    <h1>UI/UX &amp; <br><span>Graphic Design</span></h1>
                    <p>Beautiful, modern interfaces that users love. From wireframes to pixel-perfect designs — I craft digital experiences that convert and delight.</p>
                </div>
            </div>

            <div class="slider-dots" id="sliderDots"></div>

            <div class="features">
                <div class="feature-item">
                    <i class="fas fa-circle"></i> 5+ Years Experience
                </div>
                <div class="feature-item">
                    <i class="fas fa-circle"></i> 50+ Projects Delivered
                </div>
                <div class="feature-item">
                    <i class="fas fa-circle"></i> Clean &amp; Scalable Code
                </div>
            </div>

            <a href="contact.php" class="btn-primary">
                <i class="fas fa-paper-plane"></i> Discuss Your Project
            </a>
        </div>

        <div class="hero-right">
            <div class="animation-container">
                <!-- ===== ANALOG CLOCK + WORLD IMAGE (100% clear) ===== -->
                <div class="center-logo">
                    <div class="clock-face">
                        <!-- World image fully visible -->
                        <img src="" alt="World" class="clock-bg-image">

                        <div class="hand hour-hand" id="hourHand"></div>
                        <div class="hand minute-hand" id="minuteHand"></div>
                        <div class="hand second-hand" id="secondHand"></div>
                        <div class="clock-pin"></div>
                    </div>
                </div>

                <div class="floating-icon icon-1"><i class="fa-brands fa-java"></i></div>
                <div class="floating-icon icon-2"><i class="fa-brands fa-python"></i></div>
                <div class="floating-icon icon-3"><i class="fa-brands fa-phoenix-framework"></i></div>
                <div class="floating-icon icon-4"><i class="fa-brands fa-php"></i></div>
                <div class="floating-icon icon-5"><i class="fas fa-database"></i></div>
                <div class="floating-icon icon-6"><i class="fas fa-code"></i></div>
                <div class="floating-icon icon-7"><i class="fa-brands fa-js"></i></div>
                <div class="floating-icon icon-8"><i class="fa-brands fa-react"></i></div>
                <div class="floating-icon icon-9"><i class="fa-brands fa-node-js"></i></div>
            </div>
        </div>
    </section>

    <!-- STATS WRAPPER -->
    <div class="stats-wrapper">
        <div class="custom-shape-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" opacity=".25" class="shape-fill"></path>
                <path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" opacity=".5" class="shape-fill"></path>
                <path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" class="shape-fill"></path>
            </svg>
        </div>

        <div class="stats-container">
            <?php if (!empty($heroStats)): ?>
                <?php foreach ($heroStats as $stat): ?>
                    <div class="stat-item">
                        <h2><?= htmlspecialchars($stat['value']) ?></h2>
                        <p><?= htmlspecialchars($stat['label']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="stat-item"><h2>5+</h2><p>Years Experience</p></div>
                <div class="stat-item"><h2>50+</h2><p>Projects Delivered</p></div>
                <div class="stat-item"><h2>98%</h2><p>Client Satisfaction</p></div>
                <div class="stat-item"><h2>8hrs</h2><p>Response Time</p></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ABOUT SECTION -->
    <section class="about-section" id="about">
        <div class="about-container">
            <div class="about-image-wrapper">
                <div class="about-image-frame">
                    <img src="shiwa.png" alt="Shiwa - Full Stack Developer">
                </div>
                <div class="image-badge">
                    <i class="fas fa-award"></i>
                    <div class="badge-info">
                        <h4>5+ Years</h4>
                        <p>Experience</p>
                    </div>
                </div>
            </div>

            <div class="about-content">
                <div class="about-badge">
                    <i class="fas fa-user"></i> About Me
                </div>
                <h2>Hi, I'm <span>Shiwa</span></h2>
                <p class="subtitle">Full Stack Developer</p>

                <p>I'm a passionate full stack developer with over 5 years of experience building high-performance web applications and custom Web solutions. I specialize in turning complex business requirements into clean, scalable, and user-friendly digital products.</p>
                <p>From startups to enterprise clients, I've helped 50+ businesses launch their online presence with secure, fast, and maintainable code. I'm also an active contributor to the open-source community and love sharing my knowledge with fellow developers.</p>

                <div class="about-info-grid">
                    <div class="info-item"><i class="fas fa-user"></i><span><strong>Name:</strong> Shiwa</span></div>
                    <div class="info-item"><i class="fas fa-briefcase"></i><span><strong>Role:</strong> Full Stack Developer</span></div>
                    <div class="info-item"><i class="fas fa-map-marker-alt"></i><span><strong>Location:</strong> Pakistan</span></div>
                    <div class="info-item"><i class="fas fa-envelope"></i><span><strong>Email:</strong> shivapanoo1@gmail.com</span></div>
                    <div class="info-item"><i class="fas fa-circle-check"></i><span><strong>Status:</strong> Available for Work</span></div>
                    <div class="info-item"><i class="fas fa-language"></i><span><strong>Languages:</strong> English, Urdu</span></div>
                </div>

                <div class="skills-tags">
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">JavaScript</span>
                    <span class="skill-tag">React</span>
                    <span class="skill-tag">Node.js</span>
                    <span class="skill-tag">Laravel</span>
                    <span class="skill-tag">REST API</span>
                    <span class="skill-tag">WordPress</span>
                    <span class="skill-tag">HTML5</span>
                    <span class="skill-tag">CSS3</span>
                    <span class="skill-tag">Tailwind CSS</span>
                    <span class="skill-tag">Bootstrap</span>
                    <span class="skill-tag">Vue.js</span>
                    <span class="skill-tag">MongoDB</span>
                    <span class="skill-tag">Python</span>
                    <span class="skill-tag">Git &amp; GitHub</span>
                    <span class="skill-tag">Figma</span>
                </div>

                <div class="about-buttons">
                    <a href="contact.php" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Hire Me
                    </a>
                    <a href="cv.php" class="btn-outline">
                        <i class="fas fa-download"></i> Download CV
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section class="services-section" id="services">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-cogs"></i> What I Offer
            </div>
            <h1>My <span>Services</span></h1>
            <p>From custom web development to complete Web solutions, I provide end-to-end digital services to help your business grow online.</p>
        </div>

        <div class="services-grid">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <?php
                        $features = fetchAll($pdo, "SELECT feature FROM service_features WHERE service_id = " . (int)$service['id']);
                    ?>
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fab <?= htmlspecialchars($service['icon']) ?>"></i>
                        </div>
                        <h3><?= htmlspecialchars($service['title']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <?php if (!empty($features)): ?>
                            <ul class="service-features">
                                <?php foreach ($features as $f): ?>
                                    <li><i class="fas fa-check"></i> <?= htmlspecialchars($f['feature']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <a href="contact.php" class="service-link">
                            Learn More <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- PORTFOLIO SECTION -->
    <section class="portfolio-section" id="portfolio">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-briefcase"></i> Recent Work
            </div>
            <h1>My <span>Portfolio</span></h1>
            <p>A curated selection of projects I've built from custom web applications to full-scale e-commerce platforms and WordPress solutions.</p>
        </div>

        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All Projects</button>
            <button class="filter-btn" data-filter="web">Web Apps</button>
            <button class="filter-btn" data-filter="wordpress">WordPress</button>
            <button class="filter-btn" data-filter="ecommerce">E-Commerce</button>
            <button class="filter-btn" data-filter="design">UI/UX</button>
        </div>

        <div class="portfolio-grid">
            <?php if (!empty($portfolioItems)): ?>
                <?php foreach ($portfolioItems as $p): ?>
                    <div class="portfolio-item" data-category="<?= htmlspecialchars($p['category']) ?>">
                        <div class="portfolio-image">
                            <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                            <div class="portfolio-overlay">
                                <span class="category-tag"><?= ucfirst(htmlspecialchars($p['category'])) ?></span>
                                <h3><?= htmlspecialchars($p['title']) ?></h3>
                                <p><?= htmlspecialchars($p['description']) ?></p>
                                <a href="<?= htmlspecialchars($p['project_link']) ?>" class="view-btn">
                                    View Project <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="portfolio-info">
                            <div>
                                <h3><?= htmlspecialchars($p['title']) ?></h3>
                                <div class="tech"><?= htmlspecialchars($p['tech']) ?></div>
                            </div>
                            <div class="arrow-icon"><i class="fas fa-arrow-right"></i></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- COMMUNITY SECTION -->
    <section class="community-section" id="community">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-users"></i> Join the Community
            </div>
            <h1>Be Part of My <span>Community</span></h1>
            <p>Connect with developers, designers, and creators from around the world. Learn, share, and grow together in a supportive environment.</p>
        </div>

        <div class="community-stats">
            <?php foreach ($communityStats as $cs): ?>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas <?= htmlspecialchars($cs['icon']) ?>"></i>
                    </div>
                    <h3><?= htmlspecialchars($cs['value']) ?></h3>
                    <p><?= htmlspecialchars($cs['label']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- TEAM SECTION -->
        <div class="team-section">
            <div class="sub-header">
                <h2>Meet Our <span>Team</span></h2>
                <p>The talented people behind the community always ready to help, share, and build together.</p>
            </div>

            <div class="team-grid">
                <?php foreach ($teamMembers as $member): ?>
                    <div class="team-card">
                        <div class="team-image">
                            <img src="<?= htmlspecialchars($member['image']) ?>" alt="<?= htmlspecialchars($member['name']) ?>">
                            <div class="team-social">
                                <a href="<?= htmlspecialchars($member['linkedin']) ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                <a href="<?= htmlspecialchars($member['github']) ?>" target="_blank"><i class="fab fa-github"></i></a>
                                <a href="<?= htmlspecialchars($member['twitter']) ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                        <div class="team-info">
                            <h4><?= htmlspecialchars($member['name']) ?></h4>
                            <p><?= htmlspecialchars($member['role']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- PLATFORMS -->
        <div class="sub-header">
            <h2>Connect With Me <span>Everywhere</span></h2>
            <p>Join me on your favorite platform ask questions, share ideas, or just say hello!</p>
        </div>

        <div class="platforms-grid">
            <?php foreach ($platforms as $pl): ?>
                <a href="<?= htmlspecialchars($pl['link']) ?>" class="platform-card" target="_blank">
                    <div class="platform-icon <?= htmlspecialchars($pl['platform_key']) ?>">
                        <i class="fab <?= htmlspecialchars($pl['icon']) ?>"></i>
                    </div>
                    <div class="platform-info">
                        <h4><?= htmlspecialchars($pl['name']) ?></h4>
                        <p><?= htmlspecialchars($pl['description']) ?></p>
                        <span class="member-count">
                            <i class="fas fa-user-group"></i> <?= htmlspecialchars($pl['member_count']) ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- TESTIMONIALS -->
        <div class="sub-header">
            <h2>What My <span>Community</span> Says</h2>
            <p>Real feedback from people who've worked with me or been part of the journey.</p>
        </div>

        <div class="testimonials-grid">
            <?php foreach ($testimonials as $t): ?>
                <div class="testimonial-card">
                    <i class="fas fa-quote-right quote-icon"></i>
                    <div class="testimonial-rating">
                        <?php for ($i = 0; $i < (int)$t['rating']; $i++): ?>
                            <i class="fas fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="testimonial-text">"<?= htmlspecialchars($t['text']) ?>"</p>
                    <div class="testimonial-author">
                        <div class="author-avatar"><?= htmlspecialchars($t['avatar_initial']) ?></div>
                        <div class="author-info">
                            <h5><?= htmlspecialchars($t['name']) ?></h5>
                            <p><?= htmlspecialchars($t['role']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- ===== 3 EXTRA STATIC REVIEWS ===== -->
            <div class="testimonial-card">
                <i class="fas fa-quote-right quo te-icon"></i>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Shiwa delivered our e-commerce platform ahead of schedule and the code quality was outstanding. Communication was clear and every deadline was met. Highly recommended!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar">AR</div>
                    <div class="author-info">
                        <h5>Ahmed Raza</h5>
                        <p>Founder, ShopMate</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fas fa-quote-right quote-icon"></i>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"Working with Shiwa was a game-changer for our startup. He understood our vision immediately and built a scalable backend that handles thousands of users smoothly."</p>
                <div class="testimonial-author">
                    <div class="author-avatar">SK</div>
                    <div class="author-info">
                        <h5>Sara Khan</h5>
                        <p>CEO, TechNova</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fas fa-quote-right quote-icon"></i>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">"The WordPress site Shiwa built for us is beautiful, fast, and incredibly easy to manage. Our traffic has doubled since launch. Couldn't be happier with the results!"</p>
                <div class="testimonial-author">
                    <div class="author-avatar">MH</div>
                    <div class="author-info">
                        <h5>Michael Hassan</h5>
                        <p>Marketing Lead, BrightCo</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="cta-container">
            <h2>Ready to Start Your <span>Project?</span></h2>
            <p>Let's bring your ideas to life. Get in touch today and let's discuss how I can help you achieve your digital goals.</p>
            <a href="contact.php" class="btn-cta">
                <i class="fas fa-paper-plane"></i> Get In Touch
            </a>
        </div>
    </section>

    <!-- FOOTER -->
    <div class="footer">
        <p>&copy; <?= date('Y') ?> Shiwa. All rights reserved. | Designed with <i class="fas fa-heart" style="color: #00a8ff;"></i> by Shiwa</p>
    </div>

    <script>
        // ============ NAVBAR SCROLL ============
        const mainNav = document.getElementById('mainNav');
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                mainNav.classList.add('scrolled');
            } else {
                mainNav.classList.remove('scrolled');
            }
        });

        // ============ MOBILE MENU TOGGLE ============
        function closeMenu() {
            navLinks.classList.remove('open');
            const icon = menuToggle.querySelector('i');
            if (icon) icon.className = 'fas fa-bars';
        }

        menuToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            navLinks.classList.toggle('open');
            const icon = menuToggle.querySelector('i');
            if (navLinks.classList.contains('open')) {
                icon.className = 'fas fa-times';
            } else {
                icon.className = 'fas fa-bars';
            }
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => { closeMenu(); });
        });

        document.addEventListener('click', (e) => {
            if (navLinks.classList.contains('open') &&
                !navLinks.contains(e.target) &&
                !menuToggle.contains(e.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeMenu();
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) closeMenu();
        });

        // ============ ACTIVE LINK ON SCROLL (Scroll Spy) ============
        const sections = document.querySelectorAll('section[id]');
        const navAnchors = document.querySelectorAll('.nav-links a[href^="#"]');

        function setActiveLink() {
            let current = 'home';
            const scrollPos = window.scrollY + 130;

            sections.forEach(section => {
                if (scrollPos >= section.offsetTop) {
                    current = section.getAttribute('id');
                }
            });

            navAnchors.forEach(a => {
                a.classList.remove('active');
                if (a.getAttribute('href') === '#' + current) {
                    a.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', setActiveLink);
        window.addEventListener('load', setActiveLink);

        // ============ SMOOTH SCROLL FOR ANCHOR LINKS ============
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                if (targetId === '#' || targetId.length <= 1) return;
                const target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    const navHeight = mainNav.offsetHeight || 80;
                    const top = target.getBoundingClientRect().top + window.scrollY - navHeight + 10;
                    window.scrollTo({ top: top, behavior: 'smooth' });
                }
            });
        });

        // ============ PORTFOLIO FILTER ============
        const filterBtns = document.querySelectorAll('.filter-btn');
        const portfolioItems = document.querySelectorAll('.portfolio-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const filter = btn.dataset.filter;

                portfolioItems.forEach((item, index) => {
                    if (filter === 'all' || item.dataset.category === filter) {
                        item.classList.remove('hide');
                        item.style.animation = 'none';
                        setTimeout(() => {
                            item.style.animation = `fadeInUp 0.5s ease forwards`;
                            item.style.animationDelay = `${index * 0.05}s`;
                        }, 10);
                    } else {
                        item.classList.add('hide');
                    }
                });
            });
        });

        // ============ FADE-IN ON SCROLL ============
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, index * 60);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.service-card, .stat-card, .team-card, .platform-card, .testimonial-card, .portfolio-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // ============ STATS HOVER ============
        document.querySelectorAll('.stat-item').forEach(item => {
            item.addEventListener('mouseenter', () => {
                item.style.transform = 'translateY(-5px)';
            });
            item.addEventListener('mouseleave', () => {
                item.style.transform = 'translateY(0)';
            });
        });

        // ============ HERO SLIDER ============
        (function () {
            const slider  = document.getElementById('heroSlider');
            const dotsBox = document.getElementById('sliderDots');
            if (!slider || !dotsBox) return;

            const slides = slider.querySelectorAll('.slide');
            if (slides.length < 2) return;

            let current = 0;
            let timer   = null;

            slides.forEach((_, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('aria-label', 'Slide ' + (i + 1));
                if (i === 0) btn.classList.add('active');
                btn.addEventListener('click', () => goTo(i));
                dotsBox.appendChild(btn);
            });
            const dots = dotsBox.querySelectorAll('button');

            function goTo(index) {
                slides[current].classList.remove('active');
                dots[current].classList.remove('active');

                current = (index + slides.length) % slides.length;

                void slides[current].offsetWidth;

                slides[current].classList.add('active');
                dots[current].classList.add('active');

                restart();
            }

            function next() { goTo(current + 1); }

            function restart() {
                clearInterval(timer);
                timer = setInterval(next, 2000);
            }

            restart();

            slider.addEventListener('mouseenter', () => clearInterval(timer));
            slider.addEventListener('mouseleave', restart);

            dotsBox.addEventListener('mouseenter', () => clearInterval(timer));
            dotsBox.addEventListener('mouseleave', restart);
        })();

        // ============ ANALOG CLOCK (uses YOUR PC's local time) ============
        (function () {
            const hourHand   = document.getElementById('hourHand');
            const minuteHand = document.getElementById('minuteHand');
            const secondHand = document.getElementById('secondHand');
            if (!hourHand || !minuteHand || !secondHand) return;

            function tick() {
                const now = new Date();
                const s = now.getSeconds();
                const m = now.getMinutes();
                const h = now.getHours();

                const secDeg  = s * 6;
                const minDeg  = m * 6 + s * 0.1;
                const hourDeg = (h % 12) * 30 + m * 0.5;

                secondHand.style.transform = `translateX(-50%) rotate(${secDeg}deg)`;
                minuteHand.style.transform = `translateX(-50%) rotate(${minDeg}deg)`;
                hourHand.style.transform   = `translateX(-50%) rotate(${hourDeg}deg)`;
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>
</body>
</html>