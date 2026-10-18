<?php
session_start();
require 'admin/db.php';

if (isset($_GET['logout'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: admin/login.php");
    exit;
}

$isLoggedIn = isset($_SESSION['admin_id']);
if (!$isLoggedIn) {
    header("Location: admin/login.php");
    exit;
}

$userName = $_SESSION['admin_name'] ?? ($_SESSION['admin_username'] ?? 'User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shiwa - CV / Resume</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
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
            --dark: #0f172a;
            --body-text: #334155;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        html, body {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        html::-webkit-scrollbar,
        body::-webkit-scrollbar { display: none; }

        body {
            background: #eef2f7;
            color: var(--body-text);
            line-height: 1.6;
            padding: 90px 20px 60px;
            min-height: 100vh;
        }

        img { max-width: 100%; display: block; }

        /* ============ FLOATING ACTION BAR ============ */
        .action-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 14px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: var(--dark);
            padding: 10px 20px;
            border: 1px solid var(--border);
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: rgba(0, 168, 255, 0.08);
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            transform: translateX(-3px);
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-blue);
            color: #ffffff;
            padding: 11px 26px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 168, 255, 0.4);
            font-family: 'Inter', sans-serif;
        }

        .btn-print:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 168, 255, 0.6);
        }

        .btn-print i { font-size: 13px; }

        /* ============ CV WRAPPER (European single-column) ============ */
        .cv-wrapper {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 50px 55px;
        }

        /* ============ HEADER ============ */
        .cv-header {
            display: flex;
            align-items: center;
            gap: 28px;
            padding-bottom: 28px;
            border-bottom: 3px solid var(--primary-blue);
            margin-bottom: 30px;
        }

        .cv-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 3px solid var(--primary-blue);
            box-shadow: 0 0 0 5px rgba(0, 168, 255, 0.12);
        }

        .cv-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cv-header-info {
            flex: 1;
            min-width: 0;
        }

        .cv-name {
            font-size: 36px;
            font-weight: 900;
            color: var(--dark);
            letter-spacing: 1px;
            line-height: 1.1;
            margin-bottom: 4px;
        }

        .cv-name span { color: var(--primary-blue); }

        .cv-title {
            font-size: 15px;
            color: var(--primary-blue);
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .cv-contact-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 22px;
            font-size: 13px;
            color: var(--body-text);
        }

        .cv-contact-row span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .cv-contact-row i {
            color: var(--primary-blue);
            font-size: 13px;
            width: 14px;
            text-align: center;
        }

        /* ============ MAIN SECTION ============ */
        .main-section {
            margin-bottom: 26px;
        }

        .main-section h2 {
            font-size: 15px;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .main-section h2 i {
            color: var(--primary-blue);
            font-size: 15px;
        }

        .about-text {
            font-size: 14px;
            color: var(--body-text);
            line-height: 1.75;
        }

        /* Experience / Education items */
        .timeline-item {
            position: relative;
            padding-left: 24px;
            padding-bottom: 20px;
            border-left: 2px solid var(--border);
            margin-left: 6px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
            border-left-color: transparent;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-blue);
            border: 3px solid #ffffff;
            box-shadow: 0 0 0 2px var(--primary-blue), 0 0 10px rgba(0, 168, 255, 0.5);
        }

        .timeline-date {
            display: inline-block;
            background: rgba(0, 168, 255, 0.1);
            color: var(--primary-blue);
            padding: 3px 11px;
            border-radius: 50px;
            font-size: 11.5px;
            font-weight: 700;
            margin-bottom: 7px;
            letter-spacing: 0.3px;
        }

        .timeline-title {
            font-size: 15.5px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 3px;
        }

        .timeline-subtitle {
            font-size: 13px;
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 7px;
        }

        .timeline-desc {
            font-size: 13.5px;
            color: var(--body-text);
            line-height: 1.7;
        }

        .timeline-desc ul {
            list-style: none;
            padding-left: 0;
            margin-top: 6px;
        }

        .timeline-desc ul li {
            position: relative;
            padding-left: 18px;
            margin-bottom: 5px;
            font-size: 13.5px;
        }

        .timeline-desc ul li::before {
            content: '▸';
            position: absolute;
            left: 0;
            color: var(--primary-blue);
            font-weight: bold;
        }

        /* ============ SKILLS (grid) ============ */
        .skills-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 28px;
        }

        .skill-item {
            margin-bottom: 4px;
        }

        .skill-name {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: var(--dark);
            margin-bottom: 5px;
            font-weight: 600;
        }

        .skill-bar {
            height: 6px;
            background: var(--border);
            border-radius: 4px;
            overflow: hidden;
        }

        .skill-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), #00d4ff);
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(0, 168, 255, 0.5);
        }

        /* ============ LANGUAGES (inline) ============ */
        .languages-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .lang-pill {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(0, 168, 255, 0.08);
            border: 1px solid rgba(0, 168, 255, 0.25);
            color: var(--dark);
            padding: 7px 16px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
        }

        .lang-pill i {
            color: var(--primary-blue);
            font-size: 11px;
        }

        .lang-pill small {
            color: var(--muted);
            font-weight: 500;
            margin-left: 4px;
            font-size: 11.5px;
        }

        /* ============ INTERESTS (inline pills) ============ */
        .interests-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .interest-pill {
            font-size: 12.5px;
            background: #f1f5f9;
            color: var(--body-text);
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 500;
            border: 1px solid var(--border);
        }

        /* ============ PROJECTS ============ */
        .project-card {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-left: 4px solid var(--primary-blue);
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 12px;
            transition: all 0.3s;
        }

        .project-card:hover {
            box-shadow: 0 6px 20px rgba(0, 168, 255, 0.1);
            transform: translateX(3px);
        }

        .project-title {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .project-tag {
            font-size: 10.5px;
            background: var(--primary-blue);
            color: #ffffff;
            padding: 2px 9px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .project-desc {
            font-size: 13px;
            color: var(--body-text);
            line-height: 1.6;
        }

        .project-tech {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tech-pill {
            font-size: 11px;
            background: rgba(0, 168, 255, 0.1);
            color: var(--primary-blue);
            border: 1px solid rgba(0, 168, 255, 0.25);
            padding: 2px 10px;
            border-radius: 4px;
            font-weight: 600;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            body { padding: 100px 14px 40px; }

            .cv-wrapper { padding: 32px 24px; }

            .cv-header {
                flex-direction: column;
                text-align: center;
                gap: 18px;
            }

            .cv-header-info { width: 100%; }

            .cv-contact-row {
                justify-content: center;
                font-size: 12.5px;
            }

            .cv-name { font-size: 28px; }
            .cv-title { font-size: 13px; letter-spacing: 1.8px; }

            .skills-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            body { padding: 100px 8px 40px; }

            .action-bar { padding: 12px 4%; }

            .btn-back, .btn-print {
                font-size: 13px;
                padding: 9px 16px;
            }

            .btn-print span { display: none; }

            .cv-wrapper { padding: 24px 18px; border-radius: 8px; }

            .cv-photo { width: 100px; height: 100px; }

            .cv-name { font-size: 24px; }
            .cv-title { font-size: 12px; }

            .main-section h2 { font-size: 13px; letter-spacing: 1.5px; }

            .timeline-title { font-size: 14px; }
            .timeline-desc { font-size: 12.5px; }
        }

        /* ============================================================
           PRINT STYLES — European A4 (single column)
           ============================================================ */
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            html, body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 210mm;
                height: 297mm;
            }

            .action-bar { display: none !important; }

            .cv-wrapper {
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: 100% !important;
                padding: 14mm 18mm !important;
                min-height: 297mm;
            }

            .cv-header {
                padding-bottom: 18px !important;
                margin-bottom: 22px !important;
                gap: 22px !important;
            }

            .cv-photo {
                width: 90px !important;
                height: 90px !important;
                border-width: 2px !important;
            }

            .cv-name { font-size: 26px !important; }
            .cv-title { font-size: 11px !important; letter-spacing: 2px !important; margin-bottom: 8px !important; }

            .cv-contact-row { font-size: 10.5px !important; gap: 6px 16px !important; }

            .main-section { margin-bottom: 18px !important; }

            .main-section h2 {
                font-size: 11.5px !important;
                letter-spacing: 1.5px !important;
                margin-bottom: 11px !important;
                padding-bottom: 6px !important;
            }

            .main-section h2 i { font-size: 12px !important; }

            .about-text { font-size: 11px !important; line-height: 1.6 !important; }

            .timeline-item { padding-left: 18px !important; padding-bottom: 12px !important; }
            .timeline-item::before { width: 9px !important; height: 9px !important; left: -6px !important; }
            .timeline-date { font-size: 9.5px !important; padding: 2px 8px !important; margin-bottom: 5px !important; }
            .timeline-title { font-size: 12px !important; }
            .timeline-subtitle { font-size: 10.5px !important; margin-bottom: 5px !important; }
            .timeline-desc, .timeline-desc ul li { font-size: 10.5px !important; line-height: 1.5 !important; }

            .skills-grid { gap: 7px 22px !important; }
            .skill-name { font-size: 10px !important; }
            .skill-bar { height: 4px !important; }

            .lang-pill { font-size: 10px !important; padding: 5px 11px !important; }
            .lang-pill small { font-size: 9px !important; }

            .interest-pill { font-size: 10px !important; padding: 4px 10px !important; }

            .project-card { padding: 10px 12px !important; margin-bottom: 8px !important; }
            .project-title { font-size: 11.5px !important; }
            .project-desc { font-size: 10.5px !important; }
            .project-tag, .tech-pill { font-size: 9px !important; padding: 1px 7px !important; }

            .main-section,
            .timeline-item,
            .project-card,
            .skill-item {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            h2, h3 { page-break-after: avoid; }
        }
    </style>
</head>
<body>

    <!-- ============ FLOATING ACTION BAR ============ -->
    <div class="action-bar">
        <a href="index.php" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-file-pdf"></i> <span>Download PDF</span>
        </button>
    </div>

    <!-- ============ CV CONTAINER ============ -->
    <div class="cv-wrapper">

        <!-- HEADER -->
        <header class="cv-header">
            <div class="cv-photo">
                <img src="shiwa.png" alt="Shiwa">
            </div>

            <div class="cv-header-info">
                <h1 class="cv-name">S H <span>I W A</span></h1>
                <p class="cv-title">Full Stack Developer</p>

                <div class="cv-contact-row">
                    <span><i class="fas fa-envelope"></i> shivapanoo1@gmail.com</span>
                    <span><i class="fas fa-phone"></i> +92 318 3299183</span>
                    <span><i class="fas fa-map-marker-alt"></i> Pakistan</span>
                    <span><i class="fas fa-globe"></i> www.shiwa.io</span>
                    <span><i class="fab fa-github"></i> https://github.com/shivapanoo1-ai/Shiwa.git</span>
                    <span><i class="fab fa-linkedin"></i> linkedin.com/in/shiwaPanoo</span>
                </div>
            </div>
        </header>

        <!-- PROFESSIONAL SUMMARY -->
        <section class="main-section">
            <h2><i class="fas fa-user"></i> Professional Summary</h2>
            <p class="about-text">
                Passionate Full Stack Developer with 5+ years of experience designing and building scalable
                web applications, e-commerce platforms, and custom CMS solutions. Skilled in both frontend
                and backend technologies with a strong focus on clean code, performance, and user experience.
                Proven track record of delivering 50+ projects for startups and enterprise clients worldwide.
            </p>
        </section>

        <!-- WORK EXPERIENCE -->
        <section class="main-section">
            <h2><i class="fas fa-briefcase"></i> Work Experience</h2>

            <div class="timeline-item">
                <span class="timeline-date">2022 — Present</span>
                <h3 class="timeline-title">Senior Full Stack Developer</h3>
                <p class="timeline-subtitle">Freelance / Remote</p>
                <div class="timeline-desc">
                    <ul>
                        <li>Designed and delivered 30+ full-stack web applications using PHP, Laravel, React & Node.js</li>
                        <li>Built scalable REST APIs serving 100K+ requests/day with optimized database queries</li>
                        <li>Led e-commerce projects generating over $500K in client revenue</li>
                        <li>Mentored junior developers and conducted code reviews for quality assurance</li>
                    </ul>
                </div>
            </div>

            <div class="timeline-item">
                <span class="timeline-date">2020 — 2022</span>
                <h3 class="timeline-title">Full Stack Developer</h3>
                <p class="timeline-subtitle">TechNova Solutions</p>
                <div class="timeline-desc">
                    <ul>
                        <li>Developed and maintained custom WordPress themes and plugins for enterprise clients</li>
                        <li>Collaborated with design teams to translate Figma mockups into pixel-perfect interfaces</li>
                        <li>Improved site performance by 60% through optimization and caching strategies</li>
                    </ul>
                </div>
            </div>

            <div class="timeline-item">
                <span class="timeline-date">2019 — 2020</span>
                <h3 class="timeline-title">Junior Web Developer</h3>
                <p class="timeline-subtitle">BrightCo Digital</p>
                <div class="timeline-desc">
                    <ul>
                        <li>Built responsive landing pages and marketing websites for SMB clients</li>
                        <li>Integrated payment gateways, CRMs, and third-party APIs</li>
                        <li>Worked with HTML5, CSS3, JavaScript, jQuery, and PHP</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- EDUCATION (only BSCS, no dates) -->
        <section class="main-section">
            <h2><i class="fas fa-graduation-cap"></i> Education</h2>

            <div class="timeline-item">
                <h3 class="timeline-title">BS Computer Science (BSCS)</h3>
                <p class="timeline-subtitle">Shah Abdul Latif University, Ghotki Campus</p>
            </div>
        </section>

        <!-- TECHNICAL SKILLS -->
        <section class="main-section">
            <h2><i class="fas fa-code"></i> Technical Skills</h2>

            <div class="skills-grid">
                <div class="skill-item">
                    <div class="skill-name"><span>HTML5 / CSS3</span><span>95%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:95%"></div></div>
                </div>
                <div class="skill-item">
                    <div class="skill-name"><span>JavaScript</span><span>90%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:90%"></div></div>
                </div>
                <div class="skill-item">
                    <div class="skill-name"><span>PHP / Laravel</span><span>92%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:92%"></div></div>
                </div>
                <div class="skill-item">
                    <div class="skill-name"><span>React / Vue.js</span><span>85%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:85%"></div></div>
                </div>
                <div class="skill-item">
                    <div class="skill-name"><span>Node.js</span><span>82%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:82%"></div></div>
                </div>
                <div class="skill-item">
                    <div class="skill-name"><span>MySQL / MongoDB</span><span>88%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:88%"></div></div>
                </div>
                <div class="skill-item">
                    <div class="skill-name"><span>WordPress</span><span>90%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:90%"></div></div>
                </div>
                <div class="skill-item">
                    <div class="skill-name"><span>UI/UX Design</span><span>80%</span></div>
                    <div class="skill-bar"><div class="skill-fill" style="width:80%"></div></div>
                </div>
            </div>
        </section>

        <!-- FEATURED PROJECTS -->
        <section class="main-section">
            <h2><i class="fas fa-rocket"></i> Featured Projects</h2>

            <div class="project-card">
                <h3 class="project-title">ShopMate E-Commerce <span class="project-tag">Featured</span></h3>
                <p class="project-desc">Full-featured e-commerce platform with cart, checkout, payment integration, admin dashboard, and inventory management.</p>
                <div class="project-tech">
                    <span class="tech-pill">Laravel</span>
                    <span class="tech-pill">MySQL</span>
                    <span class="tech-pill">Stripe</span>
                    <span class="tech-pill">Tailwind</span>
                </div>
            </div>

            <div class="project-card">
                <h3 class="project-title">TechNova Dashboard</h3>
                <p class="project-desc">Real-time analytics dashboard for SaaS products with custom charts, user management, and role-based access control.</p>
                <div class="project-tech">
                    <span class="tech-pill">React</span>
                    <span class="tech-pill">Node.js</span>
                    <span class="tech-pill">MongoDB</span>
                    <span class="tech-pill">Socket.io</span>
                </div>
            </div>

            <div class="project-card">
                <h3 class="project-title">Restaurant Booking System</h3>
                <p class="project-desc">Table reservation platform with real-time availability, SMS notifications, and multi-location support.</p>
                <div class="project-tech">
                    <span class="tech-pill">PHP</span>
                    <span class="tech-pill">MySQL</span>
                    <span class="tech-pill">Twilio API</span>
                </div>
            </div>
        </section>

        <!-- LANGUAGES -->
        <section class="main-section">
            <h2><i class="fas fa-language"></i> Languages</h2>
            <div class="languages-row">
                <span class="lang-pill"><i class="fas fa-check-circle"></i> English <small>Fluent</small></span>
                <span class="lang-pill"><i class="fas fa-check-circle"></i> Urdu <small>Native</small></span>
                <span class="lang-pill"><i class="fas fa-check-circle"></i> Sindhi <small>Native</small></span>
                <span class="lang-pill"><i class="fas fa-check-circle"></i> Punjabi <small>Conversational</small></span>
            </div>
        </section>

        <!-- CERTIFICATIONS -->
        <section class="main-section">
            <h2><i class="fas fa-certificate"></i> Certifications</h2>

            <div class="timeline-item">
                <span class="timeline-date">2023</span>
                <h3 class="timeline-title">Meta Front-End Developer Professional</h3>
                <p class="timeline-subtitle">Coursera / Meta</p>
            </div>

            <div class="timeline-item">
                <span class="timeline-date">2022</span>
                <h3 class="timeline-title">AWS Certified Cloud Practitioner</h3>
                <p class="timeline-subtitle">Amazon Web Services</p>
            </div>
        </section>

        <!-- INTERESTS -->
        <section class="main-section">
            <h2><i class="fas fa-heart"></i> Interests</h2>
            <div class="interests-row">
                <span class="interest-pill">Open Source</span>
                <span class="interest-pill">Tech Blogging</span>
                <span class="interest-pill">UI/UX Design</span>
                <span class="interest-pill">Community Building</span>
            </div>
        </section>

    </div>

</body>
</html>