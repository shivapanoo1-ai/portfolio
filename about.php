<?php
// about.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    
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
            --text-dark: #0f172a;
            --text-body: #475569;
            --text-light: #64748b;
            --card-bg: #f8fafc;
            --border-light: #e2e8f0;
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
            background-color: #008ecc;
        }

        /* --- About Section --- */
        .about-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 90px 5% 80px;
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
        }

        /* --- Left Side: Image --- */
        .about-image-wrapper {
            flex: 1;
            min-width: 320px;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .about-image-frame {
            position: relative;
            width: 380px;
            height: 480px;
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
            display: block;
        }

        /* Decorative glow behind image */
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

        /* Floating badge on image */
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

        .image-badge i {
            font-size: 26px;
            color: var(--primary-blue);
        }

        .image-badge .badge-info h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .image-badge .badge-info p {
            font-size: 12px;
            color: var(--text-light);
        }

        /* --- Right Side: Description --- */
        .about-content {
            flex: 1;
            min-width: 320px;
        }

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

        .about-badge i {
            font-size: 11px;
        }

        .about-content h2 {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .about-content h2 span {
            color: var(--primary-blue);
        }

        .about-content .subtitle {
            font-size: 16px;
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 22px;
            letter-spacing: 0.5px;
        }

        .about-content p {
            color: var(--text-body);
            font-size: 15.5px;
            line-height: 1.75;
            margin-bottom: 18px;
        }

        /* --- Info Grid (Details) --- */
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

        /* --- Skills / Tags --- */
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
            box-shadow: 0 5px 15px rgba(0, 168, 255, 0.3);
        }

        /* --- Buttons --- */
        .about-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: var(--primary-blue);
            color: #ffffff;
            padding: 13px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 168, 255, 0.4);
        }

        .btn-primary:hover {
            background-color: #008ecc;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 168, 255, 0.6);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: transparent;
            color: var(--text-dark);
            padding: 13px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            border: 1px solid var(--border-light);
            transition: all 0.3s;
        }

        .btn-outline:hover {
            background: rgba(0, 168, 255, 0.08);
            border-color: var(--primary-blue);
            color: var(--primary-blue);
            transform: translateY(-2px);
        }

        /* --- Navbar Scroll State (Light Theme) --- */
        nav.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        /* --- Responsive Design --- */
        @media (max-width: 992px) {
            .about-container {
                flex-direction: column;
                gap: 50px;
                text-align: center;
            }

            .about-content h2 {
                font-size: 34px;
            }

            .about-badge {
                margin: 0 auto 20px;
            }

            .about-info-grid {
                justify-items: center;
                text-align: left;
            }

            .skills-tags {
                justify-content: center;
            }

            .about-buttons {
                justify-content: center;
            }

            .nav-links {
                display: none;
            }

            .about-image-frame {
                width: 320px;
                height: 400px;
            }

            .image-badge {
                right: 0;
            }
        }

        @media (max-width: 480px) {
            .about-section {
                padding: 120px 5% 60px;
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

            .image-badge i {
                font-size: 20px;
            }

            .image-badge .badge-info h4 {
                font-size: 15px;
            }

            .about-content h2 {
                font-size: 28px;
            }

            .about-info-grid {
                grid-template-columns: 1fr;
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

    <!-- About Section -->
    <section class="about-section">
        <div class="about-container">

            <!-- Left Side: Image -->
            <div class="about-image-wrapper">
                <div class="about-image-frame">
                    <img src="shiwa.png" alt="Shiwa - Full Stack Developer">
                </div>

                <!-- Floating Badge -->
                <div class="image-badge">
                    <i class="fas fa-award"></i>
                    <div class="badge-info">
                        <h4>5+ Years</h4>
                        <p>Experience</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Description -->
            <div class="about-content">
                <div class="about-badge">
                    <i class="fas fa-user"></i> About Me
                </div>

                <h2>Hi, I'm <span>Shiwa</span></h2>
                <p class="subtitle">Full Stack Developer & Graphic Designer</p>

                <p>
                    I'm a passionate full stack developer with over 5 years of experience building
                    high-performance web applications and custom Web solutions. I specialize
                    in turning complex business requirements into clean, scalable, and user-friendly
                    digital products.
                </p>

                <p>
                    From startups to enterprise clients, I've helped 50+ businesses launch their
                    online presence with secure, fast, and maintainable code. I'm also an active
                    contributor to the open-source community and love sharing my knowledge with
                    fellow developers.
                </p>

                <!-- Info Grid -->
                <div class="about-info-grid">
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <span><strong>Name:</strong> Shiwa</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-briefcase"></i>
                        <span><strong>Role:</strong> Full Stack Developer</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong>Location:</strong> Pakistan</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span><strong>Email:</strong> shivapanoo1@gmail.com</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-circle-check"></i>
                        <span><strong>Status:</strong> Available for Work</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-language"></i>
                        <span><strong>Languages:</strong> English, Urdu</span>
                    </div>
                </div>

                <!-- Skills Tags -->
                <div class="skills-tags">                    
                    <span class="skill-tag">PHP</span>
                    <span class="skill-tag">MySQL</span>
                    <span class="skill-tag">JavaScript</span>
                    <span class="skill-tag">React</span>
                    <span class="skill-tag">Node.js</span>
                    <span class="skill-tag">Laravel</span>
                    <span class="skill-tag">REST API</span>
                    <span class="skill-tag">WordPress</span>
                </div>

                <!-- Buttons -->
                <div class="about-buttons">
                    <a href="contact.php" class="btn-primary">
                        <i class="fas fa-paper-plane"></i> Hire Me
                    </a>
                    <a href="#" class="btn-outline">
                        <i class="fas fa-download"></i> Download CV
                    </a>
                </div>
            </div>

        </div>
    </section>

    <script>
        // Navbar scroll effect (light theme)
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>