<?php
// services.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Shiwa</title>
    
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

        /* --- Services Section --- */
        .services-section {
            min-height: 100vh;
            padding: 140px 5% 80px;
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 60%);
            position: relative;
        }

        /* --- Section Header --- */
        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 70px;
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

        /* --- Services Grid --- */
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
            cursor: pointer;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
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

        .service-card:hover::before {
            transform: scaleX(1);
        }

        /* Service Icon */
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

        /* Service Content */
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

        /* Service Features List */
        .service-features {
            list-style: none;
            margin-bottom: 24px;
        }

        .service-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: var(--text-light);
            margin-bottom: 8px;
        }

        .service-features li i {
            color: var(--primary-blue);
            font-size: 12px;
        }

        /* Learn More Link */
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

        .service-link:hover {
            gap: 14px;
        }

        .service-link i {
            font-size: 12px;
        }

        /* --- Process Section --- */
        .process-section {
            padding: 100px 5%;
            background: #ffffff;
            position: relative;
        }

        .process-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .process-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .process-step {
            text-align: center;
            position: relative;
        }

        .process-number {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), #00d4ff);
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(0, 168, 255, 0.35);
            position: relative;
            z-index: 2;
        }

        .process-step h4 {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .process-step p {
            font-size: 14px;
            color: var(--text-body);
            line-height: 1.6;
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

        @media (max-width: 480px) {
            .services-section {
                padding: 120px 5% 60px;
            }

            .section-header h1 {
                font-size: 28px;
            }

            .section-header p {
                font-size: 14.5px;
            }

            .service-card {
                padding: 30px 24px;
            }

            .process-number {
                width: 60px;
                height: 60px;
                font-size: 20px;
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
        <div class="logo"> S H<span> I W A</span></div>
          <ul class="nav-links" id="navLinks">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="portfolio.php">Work</a></li>
            <li><a href="community.php">Community</a></li>
        </ul>
        <a href="contact.php" class="btn-quote">Get Quote</a>
    </nav>

    <!-- Services Section -->
    <section class="services-section">

        <!-- Section Header -->
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-cogs"></i> What I Offer
            </div>
            <h1>My <span>Services</span></h1>
            <p>
                From custom web development to complete Web solutions, I provide end-to-end
                digital services to help your business grow online.
            </p>
        </div>

        <!-- Services Grid -->
        <div class="services-grid">

            <!-- Service 1 -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>Full Stack Web Development</h3>
                <p>Custom web applications built with modern technologies that are fast, secure, and scalable.</p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Frontend & Backend Development</li>
                    <li><i class="fas fa-check"></i> REST API Integration</li>
                    <li><i class="fas fa-check"></i> Database Design & Optimization</li>
                </ul>
                <a href="contact.php" class="service-link">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Service 2 -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fab fa-wordpress"></i>
                </div>
                <h3>WordPress Development</h3>
                <p>Custom WordPress themes, plugins, and full-scale solutions tailored to your business needs.</p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Custom Theme Development</li>
                    <li><i class="fas fa-check"></i> Plugin Development</li>
                    <li><i class="fas fa-check"></i> WooCommerce Stores</li>
                </ul>
                <a href="contact.php" class="service-link">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Service 3 -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h3>UI/UX & Graphic Design</h3>
                <p>Beautiful, user-centered interfaces and graphics that make your brand stand out.</p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Logo & Branding</li>
                    <li><i class="fas fa-check"></i> Website & Mobile UI Design</li>
                    <li><i class="fas fa-check"></i> Prototyping & Wireframing</li>
                </ul>
                <a href="contact.php" class="service-link">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Service 4 -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>E-Commerce Solutions</h3>
                <p>Complete online store setup and optimization to maximize your sales and customer reach.</p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Store Setup & Configuration</li>
                    <li><i class="fas fa-check"></i> Payment Gateway Integration</li>
                    <li><i class="fas fa-check"></i> Speed & SEO Optimization</li>
                </ul>
                <a href="contact.php" class="service-link">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Service 5 -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Responsive Design</h3>
                <p>Mobile-first designs that look and perform beautifully on every device and screen size.</p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Mobile-Friendly Layouts</li>
                    <li><i class="fas fa-check"></i> Cross-Browser Compatibility</li>
                    <li><i class="fas fa-check"></i> Performance Optimization</li>
                </ul>
                <a href="contact.php" class="service-link">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- Service 6 -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-tools"></i>
                </div>
                <h3>Maintenance & Support</h3>
                <p>Ongoing website maintenance, updates, and support to keep everything running smoothly.</p>
                <ul class="service-features">
                    <li><i class="fas fa-check"></i> Regular Updates & Backups</li>
                    <li><i class="fas fa-check"></i> Security Monitoring</li>
                    <li><i class="fas fa-check"></i> Bug Fixes & Improvements</li>
                </ul>
                <a href="contact.php" class="service-link">
                    Learn More <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="process-container">
            <div class="section-header">
                <div class="section-badge">
                    <i class="fas fa-route"></i> How I Work
                </div>
                <h1>My <span>Process</span></h1>
                <p>Simple, transparent, and effective — a clear workflow that keeps you in the loop at every step.</p>
            </div>

            <div class="process-grid">
                <div class="process-step">
                    <div class="process-number">01</div>
                    <h4>Discovery</h4>
                    <p>We discuss your goals, requirements, and vision in detail.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">02</div>
                    <h4>Planning</h4>
                    <p>I create a detailed roadmap with timelines and deliverables.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">03</div>
                    <h4>Development</h4>
                    <p>I build your project using clean, modern, and scalable code.</p>
                </div>
                <div class="process-step">
                    <div class="process-number">04</div>
                    <h4>Delivery</h4>
                    <p>Project launched, tested, and handed over with full support.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-container">
            <h2>Ready to Start Your <span>Project?</span></h2>
            <p>
                Let's bring your ideas to life. Get in touch today and let's discuss
                how I can help you achieve your digital goals.
            </p>
            <a href="contact.php" class="btn-cta">
                <i class="fas fa-paper-plane"></i> Get In Touch
            </a>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Shiwa. All rights reserved. | Designed with <i class="fas fa-heart" style="color: #00a8ff;"></i> by Shiwa</p>
    </div>

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

        // Card animation on scroll (fade-in)
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
                    }, index * 80);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.service-card, .process-step').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>