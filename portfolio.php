<?php
// portfolio.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Shiwa</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- CSS Reset & Variables --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary-blue: #00a8ff;
            --primary-dark: #008ecc;
            --text-dark: #0f172a;
            --text-body: #475569;
            --text-light: #64748b;
            --border-light: #e2e8f0;
            --bg-soft: #f0f9ff;
        }

        body {
            background-color: #ffffff;
            color: var(--text-dark);
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }

        /* --- Navigation --- */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 5%;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            color: var(--text-dark);
        }

        .logo span {
            color: var(--primary-blue);
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary-blue);
        }

        .btn-quote {
            background-color: var(--primary-blue);
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-quote:hover {
            background-color: var(--primary-dark);
        }

        nav.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* --- Portfolio Section --- */
        .portfolio-section {
            min-height: 100vh;
            padding: 140px 5% 80px;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 60%);
            position: relative;
        }

        /* --- Section Header --- */
        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 50px;
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

        .section-badge i {
            font-size: 11px;
        }

        .section-header h1 {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 16px;
            color: var(--text-dark);
        }

        .section-header h1 span {
            color: var(--primary-blue);
        }

        .section-header p {
            color: var(--text-body);
            font-size: 16px;
            line-height: 1.7;
        }

        /* --- Filter Buttons --- */
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

        /* --- Portfolio Grid --- */
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
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .portfolio-item.hide {
            display: none;
        }

        .portfolio-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px rgba(0, 168, 255, 0.2);
            border-color: rgba(0, 168, 255, 0.3);
        }

        /* --- Portfolio Image (FULL SHOW) --- */
        .portfolio-image {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
        }

        .portfolio-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;      /* agar image cut na ho to "contain" kar dein */
            object-position: center;
            display: block;
            transition: transform 0.6s ease;
        }

        .portfolio-item:hover .portfolio-image img {
            transform: scale(1.08);
        }

        /* --- Photo Count Badge --- */
        .photo-count {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 3;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(3, 20, 39, 0.72);
            backdrop-filter: blur(6px);
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 11px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        /* --- Overlay --- */
        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(3, 20, 39, 0.4) 0%, rgba(3, 20, 39, 0.95) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 2;
        }

        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }

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
            transform: translateY(20px);
            transition: transform 0.4s ease 0.1s;
        }

        .portfolio-overlay h3 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 8px;
            transform: translateY(20px);
            transition: transform 0.4s ease 0.15s;
        }

        .portfolio-overlay p {
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 18px;
            transform: translateY(20px);
            transition: transform 0.4s ease 0.2s;
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
            transform: translateY(20px);
            transition: transform 0.4s ease 0.25s, gap 0.3s ease;
        }

        .view-btn i {
            color: var(--primary-blue);
            font-size: 12px;
        }

        .view-btn:hover {
            gap: 14px;
        }

        .portfolio-item:hover .category-tag,
        .portfolio-item:hover h3,
        .portfolio-item:hover p,
        .portfolio-item:hover .view-btn {
            transform: translateY(0);
        }

        /* --- Portfolio Info (bottom section) --- */
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
            transition: color 0.3s;
        }

        .portfolio-item:hover .portfolio-info h3 {
            color: var(--primary-blue);
        }

        .portfolio-info .tech {
            font-size: 13px;
            color: var(--text-light);
        }

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

        /* ============================================================
           LIGHTBOX (GALLERY) — ek click par multiple pictures
           ============================================================ */
        body.no-scroll { overflow: hidden; }

        @keyframes lbFade {
            from { opacity: 0; transform: scale(0.98); }
            to   { opacity: 1; transform: scale(1); }
        }

        .lightbox {
            position: fixed;
            inset: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(3, 20, 39, 0.93);
            backdrop-filter: blur(10px);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .lightbox.open {
            opacity: 1;
            visibility: visible;
        }

        .lb-content {
            width: 100%;
            max-width: 960px;
            max-height: 92vh;
            display: flex;
            flex-direction: column;
            gap: 16px;
            transform: translateY(20px) scale(0.98);
            transition: transform 0.35s ease;
        }

        .lightbox.open .lb-content {
            transform: translateY(0) scale(1);
        }

        .lb-close {
            position: absolute;
            top: 20px;
            right: 24px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.25s ease;
            z-index: 5;
        }

        .lb-close:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
            transform: rotate(90deg);
        }

        .lb-header {
            text-align: center;
            color: #ffffff;
        }

        .lb-header .lb-category {
            display: inline-block;
            background: var(--primary-blue);
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 50px;
            margin-bottom: 10px;
        }

        .lb-header h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .lb-header p {
            color: #94a3b8;
            font-size: 13.5px;
        }

        .lb-stage {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000000;
            border-radius: 14px;
            overflow: hidden;
            min-height: 260px;
        }

        .lb-stage img {
            display: block;
            max-width: 100%;
            max-height: 62vh;
            width: auto;
            height: auto;
            object-fit: contain;
            animation: lbFade 0.35s ease;
        }

        .lb-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(3, 20, 39, 0.6);
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            z-index: 3;
        }

        .lb-nav:hover {
            background: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .lb-prev { left: 14px; }
        .lb-next { right: 14px; }

        .lb-counter {
            position: absolute;
            bottom: 14px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(3, 20, 39, 0.7);
            color: #ffffff;
            font-size: 12.5px;
            font-weight: 600;
            padding: 5px 14px;
            border-radius: 50px;
            backdrop-filter: blur(6px);
        }

        .lb-thumbs {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 4px 2px 8px;
            justify-content: center;
            scrollbar-width: thin;
        }

        .lb-thumbs::-webkit-scrollbar { height: 5px; }
        .lb-thumbs::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50px;
        }

        .lb-thumb {
            width: 76px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            opacity: 0.5;
            border: 2px solid transparent;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .lb-thumb:hover { opacity: 0.85; }
        .lb-thumb.active {
            opacity: 1;
            border-color: var(--primary-blue);
        }

        /* --- CTA Section --- */
        .cta-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, #031427 0%, #0a2a4a 100%);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.3), transparent 70%);
            border-radius: 50%;
            filter: blur(40px);
        }

        .cta-section::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
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

        .cta-container h2 span {
            color: var(--primary-blue);
        }

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

        /* --- Footer --- */
        .footer {
            background: #031427;
            padding: 30px 5%;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer p {
            color: #94a3b8;
            font-size: 14px;
        }

        .footer p a {
            color: var(--primary-blue);
            text-decoration: none;
        }

        /* --- Responsive --- */
        @media (max-width: 992px) {
            .section-header h1 {
                font-size: 34px;
            }

            .nav-links {
                display: none;
            }

            .cta-container h2 {
                font-size: 30px;
            }
        }

        @media (max-width: 600px) {
            .lightbox { padding: 16px; }
            .lb-close { top: 12px; right: 14px; width: 38px; height: 38px; font-size: 15px; }
            .lb-stage img { max-height: 52vh; }
            .lb-nav { width: 38px; height: 38px; }
            .lb-prev { left: 8px; }
            .lb-next { right: 8px; }
            .lb-thumb { width: 60px; height: 46px; }
            .lb-header h3 { font-size: 18px; }
        }

        @media (max-width: 480px) {
            .portfolio-section {
                padding: 120px 5% 60px;
            }

            .section-header h1 {
                font-size: 28px;
            }

            .section-header p {
                font-size: 14.5px;
            }

            .filter-btn {
                padding: 8px 18px;
                font-size: 13px;
            }

            .portfolio-grid {
                grid-template-columns: 1fr;
            }

            .portfolio-image {
                height: 220px;
            }

            .cta-container h2 {
                font-size: 26px;
            }

            .btn-cta {
                padding: 13px 28px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav>
        <div class="logo">S H<span> I W A</span></div>
         <ul class="nav-links" id="navLinks">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="portfolio.php">Work</a></li>
            <li><a href="community.php">Community</a></li>
        </ul>
        <a href="contact.php" class="btn-quote">Get Quote</a>
    </nav>

    <!-- Portfolio Section -->
    <section class="portfolio-section">

        <!-- Section Header -->
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-briefcase"></i> Recent Work
            </div>
            <h1>My <span>Portfolio</span></h1>
            <p>
                A curated selection of projects I've built — from custom web applications
                to full-scale e-commerce platforms and WordPress solutions.
            </p>
        </div>

        <!-- Filter Buttons -->
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All Projects</button>
            <button class="filter-btn" data-filter="web">Web Apps</button>
            <button class="filter-btn" data-filter="wordpress">WordPress</button>
            <button class="filter-btn" data-filter="ecommerce">E-Commerce</button>
            <button class="filter-btn" data-filter="design">UI/UX</button>
        </div>

        <!-- Portfolio Grid -->
        <div class="portfolio-grid">

            <!-- Project 1 -->
            <div class="portfolio-item" data-category="web"
                 data-title="SaaS Dashboard"
                 data-category-label="Web App"
                 data-tech="React · Node.js · MySQL"
                 data-images="project1.png, project1-2.png, project1-3.png">
                <div class="portfolio-image">
                    <img src="project1.png" alt="SaaS Dashboard">
                    <div class="portfolio-overlay">
                        <span class="category-tag">Web App</span>
                        <h3>SaaS Dashboard</h3>
                        <p>A modern analytics dashboard for a SaaS startup with real-time data visualization.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>SaaS Dashboard</h3>
                        <div class="tech">React · Node.js · MySQL</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 2 -->
            <div class="portfolio-item" data-category="ecommerce"
                 data-title="Fashion Store"
                 data-category-label="E-Commerce"
                 data-tech="WordPress · WooCommerce · PHP"
                 data-images="project2.png, project2-2.png, project2-3.png">
                <div class="portfolio-image">
                    <img src="project2.png" alt="E-Commerce Store">
                    <div class="portfolio-overlay">
                        <span class="category-tag">E-Commerce</span>
                        <h3>Fashion Store</h3>
                        <p>Complete WooCommerce store with custom checkout and payment integration.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Fashion Store</h3>
                        <div class="tech">WordPress · WooCommerce · PHP</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 3 -->
            <div class="portfolio-item" data-category="wordpress"
                 data-title="Corporate Website"
                 data-category-label="WordPress"
                 data-tech="WordPress · PHP · MySQL"
                 data-images="project3.png, project3-2.png, project3-3.png">
                <div class="portfolio-image">
                    <img src="project3.png" alt="Corporate Website">
                    <div class="portfolio-overlay">
                        <span class="category-tag">WordPress</span>
                        <h3>Corporate Site</h3>
                        <p>Custom WordPress theme for a multinational consulting firm with multi-language support.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Corporate Website</h3>
                        <div class="tech">WordPress · PHP · MySQL</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 4 -->
            <div class="portfolio-item" data-category="design"
                 data-title="Fintech App UI"
                 data-category-label="UI/UX"
                 data-tech="Figma · Prototyping · UI/UX"
                 data-images="project4.png, project4-2.png, project4-3.png">
                <div class="portfolio-image">
                    <img src="project4.png" alt="Mobile App Design">
                    <div class="portfolio-overlay">
                        <span class="category-tag">UI/UX</span>
                        <h3>Fintech App</h3>
                        <p>A clean, modern mobile banking app design focused on simplicity and accessibility.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Fintech App UI</h3>
                        <div class="tech">Figma · Prototyping · UI/UX</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 5 -->
            <div class="portfolio-item" data-category="web"
                 data-title="Booking Platform"
                 data-category-label="Web App"
                 data-tech="Laravel · Vue.js · MySQL"
                 data-images="project5.png, project5-2.png, project5-3.png">
                <div class="portfolio-image">
                    <img src="project5.png" alt="Booking Platform">
                    <div class="portfolio-overlay">
                        <span class="category-tag">Web App</span>
                        <h3>Booking Platform</h3>
                        <p>Online appointment booking system with calendar sync and email notifications.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Booking Platform</h3>
                        <div class="tech">Laravel · Vue.js · MySQL</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 6 -->
            <div class="portfolio-item" data-category="wordpress"
                 data-title="Restaurant Website"
                 data-category-label="WordPress"
                 data-tech="WordPress · Elementor · PHP"
                 data-images="project6.png, project6-2.png, project6-3.png">
                <div class="portfolio-image">
                    <img src="project6.png" alt="Restaurant Website">
                    <div class="portfolio-overlay">
                        <span class="category-tag">WordPress</span>
                        <h3>Restaurant Site</h3>
                        <p>Elegant restaurant website with online menu, reservation system and gallery.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Restaurant Website</h3>
                        <div class="tech">WordPress · Elementor · PHP</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 7 -->
            <div class="portfolio-item" data-category="ecommerce"
                 data-title="Electronics Store"
                 data-category-label="E-Commerce"
                 data-tech="Shopify · Liquid · JavaScript"
                 data-images="project7.png, project7-2.png, project7-3.png">
                <div class="portfolio-image">
                    <img src="project7.png" alt="Electronics Store">
                    <div class="portfolio-overlay">
                        <span class="category-tag">E-Commerce</span>
                        <h3>Electronics Store</h3>
                        <p>High-performance electronics e-commerce store with advanced product filtering.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Electronics Store</h3>
                        <div class="tech">Shopify · Liquid · JavaScript</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 8 -->
            <div class="portfolio-item" data-category="design"
                 data-title="Brand Identity"
                 data-category-label="UI/UX"
                 data-tech="Illustrator · Figma · Branding"
                 data-images="project8.png, project8-2.png, project8-3.png">
                <div class="portfolio-image">
                    <img src="project8.png" alt="Brand Identity">
                    <div class="portfolio-overlay">
                        <span class="category-tag">UI/UX</span>
                        <h3>Brand Identity</h3>
                        <p>Complete brand identity design including logo, color palette, and typography guide.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Brand Identity</h3>
                        <div class="tech">Illustrator · Figma · Branding</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

            <!-- Project 9 -->
            <div class="portfolio-item" data-category="web"
                 data-title="Portfolio Website"
                 data-category-label="Web App"
                 data-tech="HTML · CSS · JavaScript · PHP"
                 data-images="project9.png, project9-2.png, project9-3.png">
                <div class="portfolio-image">
                    <img src="project9.png" alt="Portfolio Website">
                    <div class="portfolio-overlay">
                        <span class="category-tag">Web App</span>
                        <h3>Portfolio Site</h3>
                        <p>Personal portfolio website for a photographer with gallery and contact form.</p>
                        <a href="#" class="view-btn">View Project <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="portfolio-info">
                    <div>
                        <h3>Portfolio Website</h3>
                        <div class="tech">HTML · CSS · JavaScript · PHP</div>
                    </div>
                    <div class="arrow-icon">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-container">
            <h2>Have a Project in <span>Mind?</span></h2>
            <p>
                Let's turn your idea into a reality. Reach out today and let's discuss
                how I can help bring your vision to life.
            </p>
            <a href="contact.php" class="btn-cta">
                <i class="fas fa-paper-plane"></i> Start a Project
            </a>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Shiwa. All rights reserved. | Designed with <i class="fas fa-heart" style="color: #00a8ff;"></i> by Shiwa</p>
    </div>

    <!-- ================= LIGHTBOX (GALLERY) ================= -->
    <div class="lightbox" id="lightbox">
        <button class="lb-close" id="lbClose" aria-label="Close">
            <i class="fas fa-times"></i>
        </button>

        <div class="lb-content">
            <div class="lb-header">
                <span class="lb-category" id="lbCategory">Web App</span>
                <h3 id="lbTitle">Project Title</h3>
                <p id="lbTech">Tech Stack</p>
            </div>

            <div class="lb-stage">
                <button class="lb-nav lb-prev" id="lbPrev" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <img src="" alt="" id="lbImage">

                <button class="lb-nav lb-next" id="lbNext" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <div class="lb-counter" id="lbCounter">1 / 1</div>
            </div>

            <div class="lb-thumbs" id="lbThumbs"></div>
        </div>
    </div>

    <script>
        /* ---------- Navbar scroll effect ---------- */
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            nav.classList.toggle('scrolled', window.scrollY > 50);
        });

        /* ---------- Portfolio Filter Functionality ---------- */
        const filterBtns = document.querySelectorAll('.filter-btn');
        const portfolioItems = document.querySelectorAll('.portfolio-item');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.dataset.filter;

                portfolioItems.forEach((item, index) => {
                    const show = (filter === 'all' || item.dataset.category === filter);
                    item.classList.toggle('hide', !show);

                    if (show) {
                        item.style.animation = 'none';
                        setTimeout(() => {
                            item.style.animation = `fadeInUp 0.5s ease forwards`;
                            item.style.animationDelay = `${index * 0.05}s`;
                        }, 10);
                    }
                });
            });
        });

        /* ---------- Helper: images list nikaalna ---------- */
        function getImages(item) {
            return (item.dataset.images || '')
                .split(',')
                .map(s => s.trim())
                .filter(Boolean);
        }

        /* ---------- Photo count badge (kitni pictures hain) ---------- */
        portfolioItems.forEach(item => {
            const images = getImages(item);
            if (images.length > 1) {
                const badge = document.createElement('span');
                badge.className = 'photo-count';
                badge.innerHTML = `<i class="fas fa-images"></i> ${images.length}`;
                item.querySelector('.portfolio-image').appendChild(badge);
            }
        });

        /* ---------- Lightbox Logic ---------- */
        const lightbox   = document.getElementById('lightbox');
        const lbImage    = document.getElementById('lbImage');
        const lbThumbs   = document.getElementById('lbThumbs');
        const lbCounter  = document.getElementById('lbCounter');
        const lbTitle    = document.getElementById('lbTitle');
        const lbCategory = document.getElementById('lbCategory');
        const lbTech     = document.getElementById('lbTech');

        let galleryImages = [];
        let galleryIndex  = 0;

        // Har card par click => gallery open
        portfolioItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();   // "View Project" link ko jump hone se rokta hai
                openLightbox(item);
            });
        });

        function openLightbox(item) {
            galleryImages = getImages(item);
            if (!galleryImages.length) return;

            galleryIndex = 0;
            lbTitle.textContent    = item.dataset.title || '';
            lbCategory.textContent = item.dataset.categoryLabel || '';
            lbTech.textContent     = item.dataset.tech || '';

            buildThumbs();
            showImage(0);

            lightbox.classList.add('open');
            document.body.classList.add('no-scroll');
        }

        function buildThumbs() {
            lbThumbs.innerHTML = '';

            if (galleryImages.length < 2) {
                lbThumbs.style.display = 'none';
                return;
            }

            lbThumbs.style.display = 'flex';

            galleryImages.forEach((src, i) => {
                const t = document.createElement('img');
                t.src = src;
                t.alt = `Photo ${i + 1}`;
                t.className = 'lb-thumb';
                t.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showImage(i);
                });
                lbThumbs.appendChild(t);
            });
        }

        function showImage(index) {
            if (!galleryImages.length) return;

            galleryIndex = (index + galleryImages.length) % galleryImages.length;

            lbImage.src = galleryImages[galleryIndex];
            lbImage.alt = `${lbTitle.textContent} - photo ${galleryIndex + 1}`;
            lbCounter.textContent = `${galleryIndex + 1} / ${galleryImages.length}`;

            Array.from(lbThumbs.children).forEach((t, i) => {
                t.classList.toggle('active', i === galleryIndex);
            });

            // Chhota fade animation
            lbImage.style.animation = 'none';
            requestAnimationFrame(() => {
                lbImage.style.animation = 'lbFade 0.35s ease';
            });
        }

        function closeLightbox() {
            lightbox.classList.remove('open');
            document.body.classList.remove('no-scroll');
            lbImage.src = '';
        }

        document.getElementById('lbClose').addEventListener('click', closeLightbox);

        document.getElementById('lbPrev').addEventListener('click', (e) => {
            e.stopPropagation();
            showImage(galleryIndex - 1);
        });

        document.getElementById('lbNext').addEventListener('click', (e) => {
            e.stopPropagation();
            showImage(galleryIndex + 1);
        });

        // Backdrop par click => close
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });

        // Keyboard: ESC close, Arrow keys navigate
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('open')) return;
            if (e.key === 'Escape')      closeLightbox();
            if (e.key === 'ArrowLeft')   showImage(galleryIndex - 1);
            if (e.key === 'ArrowRight')  showImage(galleryIndex + 1);
        });

        // Mobile swipe support
        let touchStartX = 0;
        lightbox.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].clientX;
        }, { passive: true });

        lightbox.addEventListener('touchend', (e) => {
            const dx = e.changedTouches[0].clientX - touchStartX;
            if (Math.abs(dx) > 50) {
                showImage(galleryIndex + (dx < 0 ? 1 : -1));
            }
        }, { passive: true });
    </script>
</body>
</html>