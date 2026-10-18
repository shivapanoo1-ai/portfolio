<?php
// user_dashboard.php
session_start();
require 'db.php';

// Check karein ke user login hai ya nahi
if (!isset($_SESSION['user_id'])) {
    header("Location: admin/login.php");
    exit;
}

// Agar admin is page par aa jaye, to usay main dashboard par bhej dein
if ($_SESSION['role'] == 'admin') {
    header("Location: admin/dashboard.php");
    exit;
}

// User ki details database se fetch karein
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Agar user database mein na mile (delete ho gaya ho)
if (!$user) {
    session_destroy();
    header("Location: admin/login.php");
    exit;
}

// Total messages count (sirf dikhane ke liye)
$msg_count = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Kashif Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-dark: #031427;
            --bg-card: #0a2a4a;
            --primary: #00a8ff;
            --text: #ffffff;
            --text-light: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
            --success: #2ecc71;
            --warning: #f39c12;
            --danger: #e74c3c;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        
        body { 
            background: var(--bg-dark); 
            color: var(--text); 
            min-height: 100vh;
            background-image: radial-gradient(circle at 20% 20%, rgba(0, 168, 255, 0.08) 0%, transparent 50%),
                              radial-gradient(circle at 80% 80%, rgba(0, 168, 255, 0.05) 0%, transparent 50%);
        }

        /* Top Navbar */
        .navbar {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }
        .navbar .logo { font-size: 22px; font-weight: 800; }
        .navbar .logo span { color: var(--primary); }
        .navbar .nav-right { display: flex; align-items: center; gap: 20px; }
        .navbar .user-info { display: flex; align-items: center; gap: 10px; }
        .avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #008ecc);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px;
        }
        .btn-logout {
            background: transparent; color: var(--danger);
            border: 1px solid var(--danger); padding: 8px 18px;
            border-radius: 6px; text-decoration: none; font-size: 14px;
            font-weight: 500; transition: 0.3s;
        }
        .btn-logout:hover { background: var(--danger); color: white; }

        /* Main Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #0a2a4a 0%, #134a75 100%);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%; right: -10%;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(0, 168, 255, 0.3) 0%, transparent 70%);
            border-radius: 50%;
        }
        .welcome-banner h1 { font-size: 28px; margin-bottom: 10px; position: relative; }
        .welcome-banner h1 span { color: var(--primary); }
        .welcome-banner p { color: var(--text-light); font-size: 15px; position: relative; }
        .welcome-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(0, 168, 255, 0.15);
            border: 1px solid rgba(0, 168, 255, 0.3);
            color: var(--primary);
            padding: 6px 14px; border-radius: 50px;
            font-size: 13px; margin-bottom: 15px;
        }

        /* Grid Layout */
        .grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 25px;
        }
        @media (max-width: 900px) {
            .grid { grid-template-columns: 1fr; }
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .card h2 {
            font-size: 17px;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid var(--border);
            padding-bottom: 12px;
        }

        /* Profile Info List */
        .info-row {
            display: flex; justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed var(--border);
            font-size: 14px;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: var(--text-light); }
        .info-row .value { font-weight: 600; }
        .role-badge {
            background: rgba(0, 168, 255, 0.15);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Quick Stats Grid */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            display: flex;
            align-items: center;
            gap: 18px;
            transition: 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(0, 168, 255, 0.4);
            box-shadow: 0 10px 25px rgba(0, 168, 255, 0.1);
        }
        .stat-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            background: rgba(0, 168, 255, 0.15);
            color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .stat-icon.green { background: rgba(46, 204, 113, 0.15); color: var(--success); }
        .stat-icon.orange { background: rgba(243, 156, 18, 0.15); color: var(--warning); }
        .stat-icon.purple { background: rgba(155, 89, 182, 0.15); color: #9b59b6; }
        
        .stat-info h3 { font-size: 22px; font-weight: 700; }
        .stat-info p { font-size: 13px; color: var(--text-light); }

        /* Action Buttons */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }
        .action-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            text-decoration: none;
            color: var(--text);
            transition: 0.3s;
        }
        .action-btn:hover {
            background: rgba(0, 168, 255, 0.1);
            border-color: var(--primary);
            transform: translateY(-3px);
        }
        .action-btn i {
            font-size: 24px;
            color: var(--primary);
            margin-bottom: 10px;
            display: block;
        }
        .action-btn span { font-size: 14px; font-weight: 500; }

        /* Welcome Message */
        .empty-state {
            text-align: center;
            padding: 30px 20px;
            color: var(--text-light);
        }
        .empty-state i { font-size: 40px; color: var(--primary); margin-bottom: 15px; opacity: 0.5; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">K<span>ASHIF</span></div>
        <div class="nav-right">
            <div class="user-info">
                <div class="avatar"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
                <div>
                    <div style="font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($user['username']); ?></div>
                    <div style="font-size: 12px; color: var(--text-light);">Member</div>
                </div>
            </div>
            <a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-badge">
                <i class="fas fa-user-circle"></i> User Dashboard
            </div>
            <h1>Welcome back, <span><?php echo htmlspecialchars($user['username']); ?></span>! 👋</h1>
            <p>Yeh aapka personal dashboard hai. Yahan se aap apni profile aur account details dekh sakte hain.</p>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-id-badge"></i></div>
                <div class="stat-info">
                    <h3>#<?php echo str_pad($user['id'], 4, '0', STR_PAD_LEFT); ?></h3>
                    <p>User ID</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-shield-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo ucfirst($user['role']); ?></h3>
                    <p>Account Type</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info">
                    <h3><?php echo date('d M Y', strtotime($user['created_at'])); ?></h3>
                    <p>Member Since</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <h3><?php echo $msg_count; ?></h3>
                    <p>Total Messages</p>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid">
            
            <!-- Left Column: Profile Info -->
            <div>
                <div class="card">
                    <h2><i class="fas fa-user"></i> Profile Information</h2>
                    <div class="info-row">
                        <span class="label">Username</span>
                        <span class="value"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Role</span>
                        <span class="role-badge"><?php echo $user['role']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Account Status</span>
                        <span class="value" style="color: var(--success);">
                            <i class="fas fa-circle" style="font-size: 8px;"></i> Active
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="label">Registered On</span>
                        <span class="value"><?php echo date('d M Y, h:i A', strtotime($user['created_at'])); ?></span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Actions -->
            <div>
                <div class="card">
                    <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                    <div class="action-grid">
                        <a href="index.php" class="action-btn">
                            <i class="fas fa-globe"></i>
                            <span>Visit Website</span>
                        </a>
                        <a href="index.php#contact" class="action-btn">
                            <i class="fas fa-paper-plane"></i>
                            <span>Contact Admin</span>
                        </a>
                        <a href="index.php#services" class="action-btn">
                            <i class="fas fa-cogs"></i>
                            <span>Our Services</span>
                        </a>
                        <a href="index.php#work" class="action-btn">
                            <i class="fas fa-briefcase"></i>
                            <span>View Projects</span>
                        </a>
                    </div>
                </div>

                <div class="card">
                    <h2><i class="fas fa-info-circle"></i> Account Notice</h2>
                    <div class="empty-state">
                        <i class="fas fa-user-shield"></i>
                        <p style="margin-top: 10px; font-size: 14px;">
                            Aap ek normal <strong>User</strong> hain. Agar aapko Admin Dashboard access chahiye, 
                            to please site administrator se rabta karein.
                        </p>
                        <p style="margin-top: 15px; font-size: 13px; color: var(--text-light);">
                            <i class="fas fa-envelope"></i> admin@kashif.com
                        </p>
                    </div>
                </div>
            </div>

        </div>

    </div>

</body>
</html>