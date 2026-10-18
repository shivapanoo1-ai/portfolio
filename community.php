<?php
// community.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community - Shiwa</title>
    
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

        /* --- Community Section --- */
        .community-section {
            min-height: 100vh;
            padding: 140px 5% 80px;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 60%);
            position: relative;
        }

        /* --- Section Header --- */
        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 60px;
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

        /* --- Community Stats --- */
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
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-blue), #00d4ff);
            transition: width 0.4s ease;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(0, 168, 255, 0.15);
            border-color: rgba(0, 168, 255, 0.3);
        }

        .stat-card:hover::before {
            width: 60%;
        }

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

        .stat-card h3 span {
            color: var(--primary-blue);
        }

        .stat-card p {
            font-size: 13.5px;
            color: var(--text-light);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* --- Sub Section Header --- */
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

        .sub-header h2 span {
            color: var(--primary-blue);
        }

        .sub-header p {
            color: var(--text-body);
            font-size: 15px;
            line-height: 1.7;
        }

        /* ============================================
           --- TEAM SECTION (12 MEMBERS) ---
           ============================================ */
        .team-section {
            max-width: 1200px;
            margin: 0 auto 100px;
        }

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
            position: relative;
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 55px rgba(0, 168, 255, 0.18);
            border-color: rgba(0, 168, 255, 0.3);
        }

        /* Team Image */
        .team-image {
            position: relative;
            width: 100%;
            height: 230px;
            overflow: hidden;
            background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
        }

        .team-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .team-card:hover .team-image img {
            transform: scale(1.1);
        }

        /* Social Overlay */
        .team-social {
            position: absolute;
            bottom: 0;
            left: 0;
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
            box-shadow: 0 8px 20px rgba(0, 168, 255, 0.5);
        }

        /* Team Info */
        .team-info {
            padding: 20px 16px 24px;
        }

        .team-info h4 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 5px;
            transition: color 0.3s;
        }

        .team-card:hover .team-info h4 {
            color: var(--primary-blue);
        }

        .team-info p {
            font-size: 13px;
            color: var(--text-light);
            font-weight: 500;
        }

        /* --- Social Platforms --- */
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
            position: relative;
            overflow: hidden;
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

        .platform-card:hover .platform-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .platform-icon.discord { background: linear-gradient(135deg, #5865F2, #7289DA); }
        .platform-icon.github { background: linear-gradient(135deg, #24292e, #404448); }
        .platform-icon.twitter { background: linear-gradient(135deg, #1DA1F2, #0d8ddb); }
        .platform-icon.youtube { background: linear-gradient(135deg, #FF0000, #cc0000); }
        .platform-icon.linkedin { background: linear-gradient(135deg, #0A66C2, #004182); }
        .platform-icon.instagram { background: linear-gradient(135deg, #f09433, #dc2743, #bc1888); }

        .platform-info h4 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .platform-info p {
            font-size: 13.5px;
            color: var(--text-light);
        }

        .platform-info .member-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--primary-blue);
        }

        /* --- Testimonials --- */
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

        .testimonial-card .quote-icon {
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
            position: relative;
            z-index: 2;
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

        .author-info p {
            font-size: 12.5px;
            color: var(--text-light);
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
        @media (max-width: 1024px) {
            .team-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 22px;
            }
        }

        @media (max-width: 992px) {
            .section-header h1 {
                font-size: 34px;
            }

            .sub-header h2 {
                font-size: 26px;
            }

            .nav-links {
                display: none;
            }

            .cta-container h2 {
                font-size: 30px;
            }
        }

        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
            }

            .team-image {
                height: 200px;
            }

            .team-info h4 {
                font-size: 15px;
            }
        }

        @media (max-width: 480px) {
            .community-section {
                padding: 120px 5% 60px;
            }

            .section-header h1 {
                font-size: 28px;
            }

            .section-header p {
                font-size: 14.5px;
            }

            .community-stats {
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }

            .stat-card {
                padding: 22px 16px;
            }

            .stat-card h3 {
                font-size: 26px;
            }

            .team-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }

            .team-image {
                height: 160px;
            }

            .team-info {
                padding: 14px 10px 18px;
            }

            .team-info h4 {
                font-size: 13.5px;
            }

            .team-info p {
                font-size: 11.5px;
            }

            .team-social a {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }

            .platform-card {
                padding: 24px 20px;
            }

            .platform-icon {
                width: 52px;
                height: 52px;
                font-size: 22px;
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

    <!-- Community Section -->
    <section class="community-section">

        <!-- Section Header -->
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-users"></i> Join the Community
            </div>
            <h1>Be Part of My <span>Community</span></h1>
            <p>
                Connect with developers, designers, and creators from around the world.
                Learn, share, and grow together in a supportive environment.
            </p>
        </div>

        <!-- Community Stats -->
        <div class="community-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-group"></i>
                </div>
                <h3>12<span>K+</span></h3>
                <p>Members</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>500<span>+</span></h3>
                <p>Discussions</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-code-branch"></i>
                </div>
                <h3>80<span>+</span></h3>
                <p>Projects</p>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <h3>45<span>+</span></h3>
                <p>Countries</p>
            </div>
        </div>

        <!-- ============================================
             TEAM SECTION - 12 MEMBERS
             ============================================ -->
        <div class="team-section">
            <div class="sub-header">
                <h2>Meet Our <span>Team</span></h2>
                <p>The talented people behind the community — always ready to help, share, and build together.</p>
            </div>

            <div class="team-grid">

                <!-- Member 1 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team1.png" alt="Ali Hassan">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Shiwa Panoo</h4>
                        <p>Full Stack Developer</p>
                    </div>
                </div>

                <!-- Member 2 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team2.png" alt="Fatima Noor">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Zameer Ahmed</h4>
                        <p>UI/UX Designer</p>
                    </div>
                </div>

                <!-- Member 3 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team3.png" alt="Bilal Ahmed">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Shiwa Panoo</h4>
                        <p>Backend Engineer</p>
                    </div>
                </div>

                <!-- Member 4 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team4.png" alt="Ayesha Malik">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Ayesha Malik</h4>
                        <p>Frontend Developer</p>
                    </div>
                </div>

                <!-- Member 5 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team5.png" alt="Usman Tariq">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Usman Tariq</h4>
                        <p>WordPress Expert</p>
                    </div>
                </div>

                <!-- Member 6 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team6.png" alt="Zainab Khan">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Zainab Khan</h4>
                        <p>Graphic Designer</p>
                    </div>
                </div>

                <!-- Member 7 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team7.png" alt="Hamza Sheikh">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Hamza Sheikh</h4>
                        <p>Mobile Developer</p>
                    </div>
                </div>

                <!-- Member 8 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team8.png" alt="Maryam Iqbal">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Maryam Iqbal</h4>
                        <p>Content Writer</p>
                    </div>
                </div>

                <!-- Member 9 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team9.png" alt="Saad Rehman">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Saad Rehman</h4>
                        <p>DevOps Engineer</p>
                    </div>
                </div>

                <!-- Member 10 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team10.png" alt="Hira Aslam">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Hira Aslam</h4>
                        <p>SEO Specialist</p>
                    </div>
                </div>

                <!-- Member 11 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team11.png" alt="Omar Farooq">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Omar Farooq</h4>
                        <p>QA Engineer</p>
                    </div>
                </div>

                <!-- Member 12 -->
                <div class="team-card">
                    <div class="team-image">
                        <img src="team12.png" alt="Nida Hussain">
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h4>Nida Hussain</h4>
                        <p>Project Manager</p>
                    </div>
                </div>

            </div>
        </div>
        <!-- ============================================
             TEAM SECTION END
             ============================================ -->

        <!-- Social Platforms Sub Header -->
        <div class="sub-header">
            <h2>Connect With Me <span>Everywhere</span></h2>
            <p>Join me on your favorite platform — ask questions, share ideas, or just say hello!</p>
        </div>

        <!-- Social Platforms Grid -->
        <div class="platforms-grid">

            <a href="#" class="platform-card">
                <div class="platform-icon discord">
                    <i class="fab fa-discord"></i>
                </div>
                <div class="platform-info">
                    <h4>Discord Server</h4>
                    <p>Chat with the community</p>
                    <span class="member-count">
                        <i class="fas fa-user-group"></i> 5,200 members
                    </span>
                </div>
            </a>

            <a href="#" class="platform-card">
                <div class="platform-icon github">
                    <i class="fab fa-github"></i>
                </div>
                <div class="platform-info">
                    <h4>GitHub</h4>
                    <p>Open source projects</p>
                    <span class="member-count">
                        <i class="fas fa-star"></i> 1,800 stars
                    </span>
                </div>
            </a>

            <a href="#" class="platform-card">
                <div class="platform-icon twitter">
                    <i class="fab fa-twitter"></i>
                </div>
                <div class="platform-info">
                    <h4>Twitter / X</h4>
                    <p>Daily dev tips</p>
                    <span class="member-count">
                        <i class="fas fa-user-group"></i> 3,400 followers
                    </span>
                </div>
            </a>

            <a href="#" class="platform-card">
                <div class="platform-icon youtube">
                    <i class="fab fa-youtube"></i>
                </div>
                <div class="platform-info">
                    <h4>YouTube</h4>
                    <p>Tutorials & coding</p>
                    <span class="member-count">
                        <i class="fas fa-play"></i> 220 videos
                    </span>
                </div>
            </a>

            <a href="#" class="platform-card">
                <div class="platform-icon linkedin">
                    <i class="fab fa-linkedin-in"></i>
                </div>
                <div class="platform-info">
                    <h4>LinkedIn</h4>
                    <p>Professional network</p>
                    <span class="member-count">
                        <i class="fas fa-user-group"></i> 2,100 connections
                    </span>
                </div>
            </a>

            <a href="#" class="platform-card">
                <div class="platform-icon instagram">
                    <i class="fab fa-instagram"></i>
                </div>
                <div class="platform-info">
                    <h4>Instagram</h4>
                    <p>Behind the scenes</p>
                    <span class="member-count">
                        <i class="fas fa-user-group"></i> 4,600 followers
                    </span>
                </div>
            </a>

        </div>

        <!-- Testimonials Sub Header -->
        <div class="sub-header">
            <h2>What My <span>Community</span> Says</h2>
            <p>Real feedback from people who've worked with me or been part of the journey.</p>
        </div>

        <!-- Testimonials Grid -->
        <div class="testimonials-grid">

            <div class="testimonial-card">
                <i class="fas fa-quote-right quote-icon"></i>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "Shiwa's Discord community is hands-down the most helpful place I've
                    been to as a developer. I learned more in 3 months here than in a year
                    of tutorials!"
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">A</div>
                    <div class="author-info">
                        <h5>Ahmed Raza</h5>
                        <p>Junior Developer</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fas fa-quote-right quote-icon"></i>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "Working with Shiwa on our project was smooth from start to finish.
                    His code is clean, and his support doesn't end when the project is done."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">S</div>
                    <div class="author-info">
                        <h5>Sarah Khan</h5>
                        <p>Startup Founder</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fas fa-quote-right quote-icon"></i>
                <div class="testimonial-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-text">
                    "The community here feels like family. Whenever I'm stuck on a problem,
                    someone always jumps in to help. Truly invaluable to my growth."
                </p>
                <div class="testimonial-author">
                    <div class="author-avatar">H</div>
                    <div class="author-info">
                        <h5>Hassan Ali</h5>
                        <p>Full Stack Developer</p>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-container">
            <h2>Ready to Join the <span>Community?</span></h2>
            <p>
                Become part of a growing network of passionate developers and creators.
                It's free, it's friendly, and it's waiting for you.
            </p>
            <a href="#" class="btn-cta">
                <i class="fas fa-user-plus"></i> Join Now
            </a>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Shiwa. All rights reserved. | Designed with <i class="fas fa-heart" style="color: #00a8ff;"></i> by Shiwa</p>
    </div>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Fade-in animation on scroll
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

        document.querySelectorAll('.stat-card, .team-card, .platform-card, .testimonial-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>