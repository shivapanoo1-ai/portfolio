<?php
session_start();
require 'admin/db.php';
 

$userName = $_SESSION['admin_name'] ?? ($_SESSION['admin_username'] ?? 'User');
$userRole = $_SESSION['admin_role'] ?? 'user';

/* ============================================================
   EMAIL CONFIG
   ============================================================ */
define('MAIL_TO',       'shivapanoo1@gmail.com');
define('MAIL_TO_NAME',  'Shiwa Panoo');
define('MAIL_FROM',     'shivapanoo1@gmail.com');
define('MAIL_FROM_NAME','Shiwa Portfolio');
define('SMTP_HOST',     'smtp.gmail.com');
define('SMTP_PORT',     587);
define('SMTP_USER',     'shivapanoo1@gmail.com');
define('SMTP_PASS',     'bjeh rcvb rurl hldk');   // ← your 16-char app password (no spaces)

/* ============================================================
   SEND EMAIL — PHPMailer + SMTP, with mail() fallback
   ============================================================ */
function sendContactEmail($name, $email, $subject, $message) {
    $emailSubject = "New Contact Message from {$name}";
    $emailBody = "
        <div style='font-family:Inter,Arial,sans-serif;max-width:600px;margin:0 auto;background:#f8fafc;border-radius:12px;overflow:hidden;'>
            <div style='background:linear-gradient(135deg,#00a8ff,#00d4ff);padding:24px 28px;color:#fff;'>
                <h2 style='margin:0;font-size:20px;font-weight:800;'>📬 New Contact Message</h2>
                <p style='margin:6px 0 0 0;font-size:13px;opacity:.9;'>Received from your portfolio website</p>
            </div>
            <div style='padding:26px 28px;background:#fff;'>
                <table style='width:100%;border-collapse:collapse;font-size:14px;'>
                    <tr>
                        <td style='padding:10px 0;color:#64748b;font-weight:600;width:120px;'>Name:</td>
                        <td style='padding:10px 0;color:#0f172a;font-weight:700;'>" . htmlspecialchars($name) . "</td>
                    </tr>
                    <tr>
                        <td style='padding:10px 0;color:#64748b;font-weight:600;'>Email:</td>
                        <td style='padding:10px 0;color:#00a8ff;font-weight:600;'>
                            <a href='mailto:" . htmlspecialchars($email) . "' style='color:#00a8ff;text-decoration:none;'>" . htmlspecialchars($email) . "</a>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding:10px 0;color:#64748b;font-weight:600;'>Subject:</td>
                        <td style='padding:10px 0;color:#0f172a;'>" . htmlspecialchars($subject ?: 'No subject') . "</td>
                    </tr>
                    <tr>
                        <td style='padding:10px 0;color:#64748b;font-weight:600;vertical-align:top;'>Message:</td>
                        <td style='padding:10px 0;color:#334155;line-height:1.7;'>" . nl2br(htmlspecialchars($message)) . "</td>
                    </tr>
                    <tr>
                        <td style='padding:10px 0;color:#64748b;font-weight:600;'>Received:</td>
                        <td style='padding:10px 0;color:#0f172a;font-size:13px;'>" . date('d M Y, h:i A') . "</td>
                    </tr>
                </table>
                <div style='margin-top:24px;padding-top:20px;border-top:1px solid #e2e8f0;'>
                    <a href='mailto:" . htmlspecialchars($email) . "' style='display:inline-block;background:linear-gradient(135deg,#00a8ff,#00d4ff);color:#fff;padding:11px 24px;border-radius:8px;text-decoration:none;font-weight:700;font-size:14px;'>
                        ✉️ Reply to {$name}
                    </a>
                </div>
            </div>
            <div style='background:#f1f5f9;padding:16px 28px;text-align:center;font-size:12px;color:#64748b;'>
                Sent from <strong style='color:#00a8ff;'>Shiwa Portfolio</strong> contact form
            </div>
        </div>
    ";

    // Auto-reply to sender
    $autoReplySubject = "Thanks for contacting Shiwa Panoo!";
    $autoReplyBody = "
        <div style='font-family:Inter,Arial,sans-serif;max-width:600px;margin:0 auto;background:#f8fafc;border-radius:12px;overflow:hidden;'>
            <div style='background:linear-gradient(135deg,#031427,#0a2a4a);padding:30px 28px;color:#fff;text-align:center;'>
                <h1 style='margin:0;font-size:22px;font-weight:800;'>Thanks, " . htmlspecialchars($name) . "! 👋</h1>
                <p style='margin:10px 0 0 0;font-size:14px;color:#9fb3c8;'>I've received your message and will reply within 24 hours.</p>
            </div>
            <div style='padding:26px 28px;background:#fff;'>
                <p style='color:#334155;font-size:14.5px;line-height:1.7;margin:0 0 16px 0;'>
                    Hi <strong>" . htmlspecialchars($name) . "</strong>,
                </p>
                <p style='color:#475569;font-size:14px;line-height:1.7;margin:0 0 20px 0;'>
                    Thank you for reaching out through my portfolio website. I've received your message and I'll get back to you as soon as possible — usually within 24 hours.
                </p>
                <div style='background:#f0f9ff;border-left:4px solid #00a8ff;padding:16px 20px;border-radius:8px;margin-bottom:20px;'>
                    <p style='margin:0 0 8px 0;font-size:12px;color:#64748b;text-transform:uppercase;font-weight:700;letter-spacing:.5px;'>Your Message</p>
                    <p style='margin:0;color:#334155;font-size:14px;line-height:1.7;'>" . nl2br(htmlspecialchars($message)) . "</p>
                </div>
                <p style='color:#475569;font-size:14px;line-height:1.7;margin:0 0 6px 0;'>Best regards,</p>
                <p style='color:#0f172a;font-size:15px;font-weight:800;margin:0;'>Shiwa Panoo</p>
                <p style='color:#64748b;font-size:13px;margin:2px 0 0 0;'>Full Stack Developer · Ghotki, Sindh, Pakistan</p>
            </div>
            <div style='background:#f1f5f9;padding:16px 28px;text-align:center;font-size:12px;color:#64748b;'>
                <a href='https://wa.me/923183299283' style='color:#00a8ff;text-decoration:none;font-weight:600;'>WhatsApp</a> ·
                <a href='mailto:shivapanoo1@gmail.com' style='color:#00a8ff;text-decoration:none;font-weight:600;'>Email</a>
            </div>
        </div>
    ";

    /* ---------- Load PHPMailer ---------- */
    $phpmailerFiles = [
        __DIR__ . '/PHPMailer/src/PHPMailer.php',
        __DIR__ . '/PHPMailer/src/SMTP.php',
        __DIR__ . '/PHPMailer/src/Exception.php'
    ];
    $phpmailerAvailable = false;

    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
        $phpmailerAvailable = class_exists('PHPMailer\\PHPMailer\\PHPMailer');
    } elseif (file_exists($phpmailerFiles[0])) {
        require_once $phpmailerFiles[0];
        require_once $phpmailerFiles[1];
        require_once $phpmailerFiles[2];
        $phpmailerAvailable = class_exists('PHPMailer\\PHPMailer\\PHPMailer');
    }

    if ($phpmailerAvailable) {
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            // Send to admin
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress(MAIL_TO, MAIL_TO_NAME);
            $mail->addReplyTo($email, $name);
            $mail->isHTML(true);
            $mail->Subject = $emailSubject;
            $mail->Body    = $emailBody;
            $mail->AltBody = "New message from {$name} ({$email}):\n\n{$message}";
            $mail->send();

            // Auto-reply to user
            $mail->clearAddresses();
            $mail->clearReplyTos();
            $mail->addAddress($email, $name);
            $mail->addReplyTo(MAIL_TO, MAIL_TO_NAME);
            $mail->Subject = $autoReplySubject;
            $mail->Body    = $autoReplyBody;
            $mail->AltBody = "Hi {$name},\n\nThank you for contacting Shiwa Panoo. I'll reply within 24 hours.\n\nYour message: {$message}\n\nBest regards,\nShiwa Panoo";
            $mail->send();

            return true;
        } catch (Exception $e) {
            // Silent fail — will fall back to mail()
        }
    }

    /* ---------- Fallback: PHP mail() ---------- */
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $sent1 = @mail(MAIL_TO, $emailSubject, $emailBody, $headers);

    $headers2  = "MIME-Version: 1.0\r\n";
    $headers2 .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers2 .= "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
    $headers2 .= "Reply-To: " . MAIL_TO . "\r\n";
    @mail($email, $autoReplySubject, $autoReplyBody, $headers2);

    return $sent1;
}

/* ============================================================
   HANDLE CONTACT FORM
   ============================================================ */
$contact_success = '';
$contact_error   = '';
$formData = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'contact') {
    $formData['name']    = trim($_POST['name'] ?? '');
    $formData['email']   = trim($_POST['email'] ?? '');
    $formData['subject'] = trim($_POST['subject'] ?? '');
    $formData['message'] = trim($_POST['message'] ?? '');

    if ($formData['name'] === '' || $formData['email'] === '' || $formData['message'] === '') {
        $contact_error = "Please fill in all required fields.";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $contact_error = "Please enter a valid email address.";
    } else {
        $dbSaved   = false;
        $emailSent = false;

        /* ---------- 1) SAVE TO DATABASE ---------- */
        try {
            $columns = [];
            try {
                $cstmt = $pdo->query("SHOW COLUMNS FROM messages");
                if ($cstmt) {
                    while ($row = $cstmt->fetch(PDO::FETCH_ASSOC)) $columns[] = $row['Field'];
                }
            } catch (Exception $e) {}

            $fields = ['name', 'email', 'message'];
            $values = [$formData['name'], $formData['email'], $formData['message']];

            if (in_array('subject', $columns, true) && $formData['subject'] !== '') {
                $fields[] = 'subject'; $values[] = $formData['subject'];
            }
            if (in_array('user_id', $columns, true)) {
                $fields[] = 'user_id'; $values[] = $_SESSION['admin_id'];
            }
            if (in_array('created_at', $columns, true)) {
                $fields[] = 'created_at'; $values[] = date('Y-m-d H:i:s');
            }

            $placeholders = implode(',', array_fill(0, count($fields), '?'));
            $cols = '`' . implode('`,`', $fields) . '`';
            $pdo->prepare("INSERT INTO messages ($cols) VALUES ($placeholders)")->execute($values);
            $dbSaved = true;
        } catch (Exception $e) {
            $dbSaved = false;
        }

        /* ---------- 2) SEND EMAIL ---------- */
        try {
            $emailSent = sendContactEmail(
                $formData['name'],
                $formData['email'],
                $formData['subject'],
                $formData['message']
            );
        } catch (Exception $e) {
            $emailSent = false;
        }

        /* ---------- 3) RESULT ---------- */
        if ($dbSaved || $emailSent) {
            $contact_success = "Thank you, {$formData['name']}! Your message has been sent successfully. I'll get back to you within 24 hours.";
            $formData = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
        } else {
            $contact_error = "Sorry, something went wrong. Please try again or email me directly at shivapanoo1@gmail.com";
        }
    }
}

/* ---------- Platform links ---------- */
$platforms = fetchAll($pdo, "SELECT * FROM platforms ORDER BY display_order LIMIT 6");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="description" content="Contact Shiwa Panoo - Full Stack Developer from Ghotki, Sindh Pakistan">
    <title>Contact - Shiwa Panoo</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ============ RESET & VARIABLES ============ */
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; -webkit-tap-highlight-color:transparent; }
        :root {
            --primary-blue: #00a8ff;
            --primary-dark: #008ecc;
            --text-white: #ffffff;
            --text-dark: #0f172a;
            --text-body: #475569;
            --text-muted: #64748b;
            --border-light: #e2e8f0;
        }
        html { scroll-behavior:smooth; -webkit-text-size-adjust:100%; }
        body {
            background-image: url('images/bk.png');
            background-size: cover; background-position: center center;
            background-repeat: no-repeat; background-attachment: fixed;
            background-color: #031427; color: var(--text-white);
            overflow-x: hidden; position: relative;
            min-height: 100vh; line-height: 1.6;
        }
        img { max-width:100%; height:auto; display:block; }

        /* NAV */
        nav { display:flex; justify-content:space-between; align-items:center; padding:10px 5%; position:absolute; top:0; width:105%; z-index:100; transition:all .3s ease; margin-left:-40px; }
        .logo-wrapper { display:flex; align-items:center; gap:12px; cursor:pointer; flex-shrink:0; }
        .logo-img { width:100px; height:100px; object-fit:contain; animation:spinLogo 8s linear infinite; filter:drop-shadow(0 0 10px rgba(242,247,250,.89)); transition:filter .3s ease; flex-shrink:0; margin-left:-45px; margin-top:-10px; }
        .logo-wrapper:hover .logo-img { filter:drop-shadow(0 0 20px rgba(0,168,255,1)); }
        @keyframes spinLogo { from{transform:rotate(0);} to{transform:rotate(360deg);} }
        .logo { font-size:24px; font-weight:800; letter-spacing:1px; color:var(--text-white); text-shadow:0 2px 10px rgba(0,0,0,.7); white-space:nowrap; }
        .logo span { color:var(--primary-blue); text-shadow:0 0 15px rgba(0,168,255,.6); }
        .nav-links { display:flex; gap:30px; list-style:none; align-items:center; }
        .nav-links a { color:var(--text-white); text-decoration:none; font-size:14px; font-weight:500; transition:color .3s; text-shadow:0 1px 5px rgba(0,0,0,.6); }
        .nav-links a:hover, .nav-links a.active { color:var(--primary-blue); }
        .nav-dashboard { display:inline-flex !important; align-items:center; gap:6px; background:rgba(0,168,255,.15); border:1px solid rgba(0,168,255,.5); padding:6px 14px !important; border-radius:50px; color:#00d4ff !important; font-weight:600 !important; transition:all .3s ease !important; }
        .nav-dashboard:hover { background:rgba(0,168,255,.3) !important; transform:translateY(-2px); box-shadow:0 6px 18px rgba(0,168,255,.4); }
        .nav-user { display:inline-flex !important; align-items:center; gap:6px; background:rgba(0,168,255,.12); border:1px solid rgba(0,168,255,.4); padding:6px 14px !important; border-radius:50px; color:var(--primary-blue) !important; font-weight:600 !important; transition:all .3s ease !important; }
        .nav-user:hover { background:rgba(239,68,68,.15) !important; border-color:rgba(239,68,68,.5); color:#f87171 !important; transform:translateY(-2px); }
        .btn-quote { background-color:var(--primary-blue); color:var(--text-white); padding:10px 24px; border-radius:6px; text-decoration:none; font-size:14px; font-weight:600; transition:all .3s; display:inline-block; box-shadow:0 4px 15px rgba(0,168,255,.4); }
        .btn-quote:hover { background-color:var(--primary-dark); transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,168,255,.6); }
        nav.scrolled { background:rgba(255,255,255,.98); backdrop-filter:blur(10px); box-shadow:0 4px 20px rgba(0,0,0,.08); position:fixed; }
        nav.scrolled .logo { color:var(--text-dark); text-shadow:none; }
        nav.scrolled .logo span { text-shadow:none; }
        nav.scrolled .nav-links a { color:var(--text-dark); text-shadow:none; }
        nav.scrolled .nav-links a:hover, nav.scrolled .nav-links a.active { color:var(--primary-blue); }
        nav.scrolled .nav-dashboard { color:var(--primary-dark) !important; background:rgba(0,168,255,.1); }
        nav.scrolled .nav-user { color:var(--primary-dark) !important; background:rgba(0,168,255,.08); }
        nav.scrolled .nav-user:hover { color:#dc2626 !important; background:rgba(239,68,68,.1); }
        .menu-toggle { display:none; background:transparent; border:none; color:var(--text-white); font-size:24px; cursor:pointer; padding:8px; border-radius:6px; transition:all .3s; }
        nav.scrolled .menu-toggle { color:var(--text-dark); }
        .menu-toggle:hover { background:rgba(255,255,255,.1); }
        nav.scrolled .menu-toggle:hover { background:rgba(0,0,0,.05); }

        /* PAGE HERO */
        .page-hero { padding:20px 5% 50px; text-align:center; position:relative; background:linear-gradient(180deg,rgba(3,20,39,.4) 0%,rgba(3,20,39,.85) 100%),url('images/bk.png') center/cover; }
        .page-hero::before { content:''; position:absolute; top:0; left:0; width:100%; height:100%; background:radial-gradient(circle at 50% 30%,rgba(0,168,255,.25),transparent 60%); pointer-events:none; }
        .page-hero-content { position:relative; z-index:2; max-width:700px; margin:0 auto; }
        .page-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(0,168,255,.2); border:1px solid rgba(0,168,255,.6); padding:8px 18px; border-radius:50px; font-size:13px; color:#00d4ff; margin-bottom:24px; backdrop-filter:blur(15px); font-weight:600; text-shadow:0 0 10px rgba(0,168,255,.5); box-shadow:0 0 20px rgba(0,168,255,.3); }
        .page-hero h1 { font-size:52px; font-weight:800; line-height:1.15; margin-bottom:18px; color:#fff; text-shadow:0 0 30px rgba(0,0,0,1),0 2px 15px rgba(0,0,0,.95),0 4px 25px rgba(0,0,0,.85); letter-spacing:-.5px; }
        .page-hero h1 span { color:var(--primary-blue); }
        .page-hero p { color:#cbd5e1; font-size:16px; line-height:1.7; max-width:560px; margin:0 auto; text-shadow:0 2px 12px rgba(0,0,0,1); }

        /* CONTACT */
        .contact-section { padding:90px 5% 100px; background:linear-gradient(180deg,#f0f9ff 0%,#fff 60%); position:relative; }
        .contact-container { max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1fr 1.4fr; gap:40px; align-items:start; }

        .contact-info { background:linear-gradient(135deg,#031427 0%,#0a2a4a 100%); border-radius:22px; padding:40px 34px; color:#fff; position:relative; overflow:hidden; box-shadow:0 25px 60px rgba(3,20,39,.28); height:fit-content; }
        .contact-info::before { content:''; position:absolute; top:-90px; right:-60px; width:260px; height:260px; background:radial-gradient(circle,rgba(0,168,255,.45),transparent 70%); border-radius:50%; filter:blur(40px); }
        .contact-info::after { content:''; position:absolute; bottom:-110px; left:-70px; width:260px; height:260px; background:radial-gradient(circle,rgba(0,212,255,.3),transparent 70%); border-radius:50%; filter:blur(40px); }
        .contact-info-inner { position:relative; z-index:2; }
        .contact-info h2 { font-size:26px; font-weight:800; margin-bottom:12px; line-height:1.25; }
        .contact-info h2 span { color:var(--primary-blue); }
        .contact-info .info-intro { color:#9fb3c8; font-size:14.5px; line-height:1.7; margin-bottom:32px; }

        .info-item-card { display:flex; align-items:flex-start; gap:16px; padding:16px 18px; background:rgba(255,255,255,.05); border:1px solid rgba(255,255,255,.08); border-radius:14px; margin-bottom:14px; transition:all .35s ease; text-decoration:none; color:inherit; }
        .info-item-card:hover { background:rgba(0,168,255,.12); border-color:rgba(0,168,255,.35); transform:translateX(6px); }
        .info-icon { width:46px; height:46px; border-radius:12px; background:linear-gradient(135deg,var(--primary-blue),#00d4ff); display:flex; align-items:center; justify-content:center; font-size:18px; color:#fff; flex-shrink:0; box-shadow:0 8px 20px rgba(0,168,255,.45); transition:transform .35s ease; }
        .info-item-card:hover .info-icon { transform:rotate(-8deg) scale(1.08); }
        .info-icon.whatsapp { background:linear-gradient(135deg,#25D366,#128C7E); box-shadow:0 8px 20px rgba(37,211,102,.45); }
        .info-icon.location { background:linear-gradient(135deg,#ef4444,#f97316); box-shadow:0 8px 20px rgba(239,68,68,.45); }
        .info-icon.clock    { background:linear-gradient(135deg,#8b5cf6,#a78bfa); box-shadow:0 8px 20px rgba(139,92,246,.45); }
        .info-text h4 { font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#00d4ff; margin-bottom:3px; }
        .info-text p, .info-text a { font-size:14px; color:#e2e8f0; text-decoration:none; line-height:1.5; word-break:break-word; transition:color .3s; }
        .info-text a:hover { color:var(--primary-blue); }
        .social-row { display:flex; gap:10px; margin-top:26px; flex-wrap:wrap; }
        .social-row a { width:42px; height:42px; border-radius:12px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); color:#fff; display:flex; align-items:center; justify-content:center; font-size:16px; text-decoration:none; transition:all .3s ease; }
        .social-row a:hover { background:var(--primary-blue); border-color:var(--primary-blue); transform:translateY(-4px) scale(1.08); box-shadow:0 10px 22px rgba(0,168,255,.5); }

        .contact-form-card { background:#fff; border:1px solid var(--border-light); border-radius:22px; padding:44px 40px; box-shadow:0 20px 50px rgba(15,23,42,.08); position:relative; }
        .contact-form-card::before { content:''; position:absolute; top:0; left:0; width:100%; height:5px; background:linear-gradient(90deg,var(--primary-blue),#00d4ff); border-radius:22px 22px 0 0; }
        .form-header { margin-bottom:30px; }
        .form-header h2 { font-size:28px; font-weight:800; color:var(--text-dark); margin-bottom:8px; line-height:1.25; }
        .form-header h2 span { color:var(--primary-blue); }
        .form-header p { color:var(--text-muted); font-size:14.5px; }

        .contact-form .form-row { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px; }
        .contact-form .form-group { margin-bottom:18px; }
        .contact-form label { display:block; font-size:13px; font-weight:700; color:var(--text-dark); margin-bottom:8px; letter-spacing:.2px; }
        .contact-form label span.req { color:#ef4444; }
        .input-wrapper { position:relative; }
        .input-wrapper > i { position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:14px; pointer-events:none; transition:color .3s; z-index:2; }
        .input-wrapper.textarea-icon > i { top:18px; transform:none; }
        .contact-form input, .contact-form select, .contact-form textarea { width:100%; padding:14px 16px 14px 44px; border:1.5px solid var(--border-light); border-radius:12px; font-size:14px; font-family:'Inter',sans-serif; color:var(--text-dark); background:#f8fafc; transition:all .3s ease; outline:none; }
        .contact-form textarea { resize:vertical; min-height:140px; padding-top:14px; }
        .contact-form input:focus, .contact-form select:focus, .contact-form textarea:focus { border-color:var(--primary-blue); background:#fff; box-shadow:0 0 0 4px rgba(0,168,255,.12); }
        .input-wrapper:focus-within > i { color:var(--primary-blue); }
        .contact-form input::placeholder, .contact-form textarea::placeholder { color:#94a3b8; }
        .contact-submit { width:100%; padding:15px; border:none; border-radius:12px; background:linear-gradient(135deg,var(--primary-blue),#00d4ff); color:#fff; font-size:15px; font-weight:700; font-family:'Inter',sans-serif; cursor:pointer; transition:all .3s ease; display:flex; justify-content:center; align-items:center; gap:10px; box-shadow:0 10px 26px rgba(0,168,255,.4); letter-spacing:.3px; margin-top:8px; }
        .contact-submit:hover { transform:translateY(-3px); box-shadow:0 16px 36px rgba(0,168,255,.55); }
        .contact-submit:active { transform:translateY(-1px) scale(.99); }

        .contact-alert { padding:14px 18px; border-radius:12px; font-size:13.5px; margin-bottom:22px; display:flex; align-items:center; gap:10px; animation:slideDown .4s ease; line-height:1.5; }
        .contact-alert.success { background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.4); color:#0d9668; }
        .contact-alert.error   { background:rgba(239,68,68,.1); border:1px solid rgba(239,68,68,.4); color:#b91c1c; }
        .contact-alert i { font-size:18px; flex-shrink:0; }
        @keyframes slideDown { from{opacity:0; transform:translateY(-10px);} to{opacity:1; transform:translateY(0);} }

        /* CTA */
        .cta-section { padding:80px 5%; background:linear-gradient(135deg,#031427 0%,#0a2a4a 100%); position:relative; overflow:hidden; }
        .cta-section::before { content:''; position:absolute; top:-100px; right:-100px; width:400px; height:400px; background:radial-gradient(circle,rgba(0,168,255,.3),transparent 70%); border-radius:50%; filter:blur(40px); }
        .cta-section::after { content:''; position:absolute; bottom:-100px; left:-100px; width:400px; height:400px; background:radial-gradient(circle,rgba(0,212,255,.2),transparent 70%); border-radius:50%; filter:blur(40px); }
        .cta-container { max-width:900px; margin:0 auto; text-align:center; position:relative; z-index:2; }
        .cta-container h2 { font-size:40px; font-weight:800; color:#fff; margin-bottom:16px; line-height:1.2; }
        .cta-container h2 span { color:var(--primary-blue); }
        .cta-container p { color:#94a3b8; font-size:16px; line-height:1.7; margin-bottom:32px; max-width:600px; margin-left:auto; margin-right:auto; }
        .cta-buttons { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }
        .btn-cta { display:inline-flex; align-items:center; gap:10px; background-color:var(--primary-blue); color:#fff; padding:15px 36px; border-radius:6px; text-decoration:none; font-size:16px; font-weight:600; transition:all .3s; box-shadow:0 4px 15px rgba(0,168,255,.4); }
        .btn-cta:hover { background-color:var(--primary-dark); transform:translateY(-2px); box-shadow:0 8px 25px rgba(0,168,255,.6); }
        .btn-cta-whatsapp { background:linear-gradient(135deg,#25D366,#128C7E); box-shadow:0 4px 15px rgba(37,211,102,.4); }
        .btn-cta-whatsapp:hover { background:linear-gradient(135deg,#1eb955,#0f7367); box-shadow:0 8px 25px rgba(37,211,102,.6); }
        .btn-cta-outline { display:inline-flex; align-items:center; gap:10px; background:rgba(255,255,255,.08); color:#fff; padding:15px 36px; border-radius:6px; text-decoration:none; font-size:16px; font-weight:600; border:1px solid rgba(255,255,255,.2); transition:all .3s; backdrop-filter:blur(8px); }
        .btn-cta-outline:hover { background:rgba(255,255,255,.18); transform:translateY(-2px); }

        .footer { background:#031427; padding:30px 5%; text-align:center; border-top:1px solid rgba(255,255,255,.05); }
        .footer p { color:#94a3b8; font-size:14px; }

        @media (max-width:992px) {
            .menu-toggle { display:block; }
            .nav-links { display:none; position:absolute; top:100%; left:0; width:100%; flex-direction:column; gap:0; background:rgba(255,255,255,.98); backdrop-filter:blur(10px); padding:15px 5%; box-shadow:0 10px 30px rgba(0,0,0,.1); border-top:1px solid var(--border-light); align-items:stretch; }
            .nav-links.open { display:flex; }
            .nav-links li { padding:12px 0; border-bottom:1px solid var(--border-light); }
            .nav-links li:last-child { border-bottom:none; }
            .nav-links a { color:var(--text-dark) !important; font-size:15px; font-weight:600; display:block; text-shadow:none; }
            .nav-dashboard, .nav-user { background:rgba(0,168,255,.08) !important; border:none !important; padding:10px 14px !important; display:flex !important; width:100%; justify-content:center; }
            .btn-quote { padding:9px 18px; font-size:13px; }
            .page-hero { padding:170px 5% 70px; }
            .page-hero h1 { font-size:38px; }
            .contact-container { grid-template-columns:1fr; gap:30px; }
            .contact-info { padding:34px 28px; }
            .contact-form-card { padding:34px 28px; }
            .cta-container h2 { font-size:30px; }
        }
        @media (max-width:768px) { .contact-form .form-row { grid-template-columns:1fr; gap:0; } }
        @media (max-width:480px) {
            nav { padding:14px 4%; }
            .logo-img { width:34px; height:34px; }
            .logo { font-size:18px; }
            .btn-quote { padding:8px 14px; font-size:12px; }
            .page-hero { padding:140px 4% 60px; }
            .page-hero h1 { font-size:28px; }
            .page-hero p { font-size:14px; }
            .contact-section { padding:60px 4% 70px; }
            .contact-info { padding:28px 22px; border-radius:18px; }
            .contact-info h2 { font-size:22px; }
            .info-item-card { padding:14px; gap:12px; }
            .info-icon { width:40px; height:40px; font-size:16px; }
            .contact-form-card { padding:28px 20px; border-radius:18px; }
            .form-header h2 { font-size:22px; }
            .contact-submit { font-size:14px; padding:14px; }
            .cta-container h2 { font-size:22px; }
            .cta-container p { font-size:13.5px; }
            .btn-cta, .btn-cta-outline, .btn-cta-whatsapp { padding:13px 24px; font-size:14px; width:100%; justify-content:center; }
            .footer p { font-size:12.5px; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav id="mainNav">
       
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
            <i class="fas fa-bars"></i>
        </button>

        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="portfolio.php">Work</a></li>
            <li><a href="community.php">Community</a></li>

            <?php if ($userRole === 'admin'): ?>
                <li>
                    <a href="admin/dashboard.php" class="nav-dashboard">
                        <i class="fas fa-gauge-high"></i> Dashboard
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="?logout=1" class="nav-user"
                   onclick="return confirm('Are you sure you want to logout?');">
                    <i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($userName) ?>
                </a>
            </li>
        </ul>
        <a href="contact.php" class="btn-quote" style="background:var(--primary-dark);">Hire Me</a>
    </nav>

    <!-- PAGE HERO -->
    <section class="page-hero">
      
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-section">
        <div class="contact-container">

            <!-- INFO CARD -->
            <aside class="contact-info">
                <div class="contact-info-inner">
                    <h2>Contact <span>Information</span></h2>
                    <p class="info-intro">Let's discuss your project and bring your ideas to life. I'm always available for new opportunities.</p>

                    <a href="mailto:shivapanoo1@gmail.com" class="info-item-card">
                        <div class="info-icon"><i class="fas fa-envelope"></i></div>
                        <div class="info-text">
                            <h4>Email</h4>
                            <p>shivapanoo1@gmail.com</p>
                        </div>
                    </a>

                    <a href="https://wa.me/923183299283?text=Hi%20Shiwa%2C%20I%20want%20to%20discuss%20a%20project" target="_blank" class="info-item-card">
                        <div class="info-icon whatsapp"><i class="fab fa-whatsapp"></i></div>
                        <div class="info-text">
                            <h4>WhatsApp</h4>
                            <p>+92 318 3299283</p>
                        </div>
                    </a>

                    <div class="info-item-card">
                        <div class="info-icon location"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="info-text">
                            <h4>Location</h4>
                            <p>Ghotki, Sindh, Pakistan</p>
                        </div>
                    </div>

                    <div class="info-item-card">
                        <div class="info-icon clock"><i class="fas fa-clock"></i></div>
                        <div class="info-text">
                            <h4>Response Time</h4>
                            <p>Within 8 hours</p>
                        </div>
                    </div>

                    <div class="social-row">
                        <a href="https://wa.me/923183299283" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="mailto:shivapanoo1@gmail.com" title="Email"><i class="fas fa-envelope"></i></a>
                        <a href="#" title="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </aside>

            <!-- FORM CARD -->
            <div class="contact-form-card">
                <div class="form-header">
                    <h2>Send Me a <span>Message</span></h2>
                    <p>Fill out the form and I'll respond within 8 hours.</p>
                </div>

                <?php if ($contact_success): ?>
                    <div class="contact-alert success">
                        <i class="fas fa-check-circle"></i>
                        <span><?= htmlspecialchars($contact_success) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($contact_error): ?>
                    <div class="contact-alert error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= htmlspecialchars($contact_error) ?></span>
                    </div>
                <?php endif; ?>

                <form class="contact-form" method="POST" id="contactForm" novalidate>
                    <input type="hidden" name="action" value="contact">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span class="req">*</span></label>
                            <div class="input-wrapper">
                                <input type="text" name="name" placeholder="John Doe" required
                                       value="<?= htmlspecialchars($formData['name']) ?>">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email Address <span class="req">*</span></label>
                            <div class="input-wrapper">
                                <input type="email" name="email" placeholder="you@example.com" required
                                       value="<?= htmlspecialchars($formData['email']) ?>">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Subject</label>
                        <div class="input-wrapper">
                            <input type="text" name="subject" placeholder="Project inquiry"
                                   value="<?= htmlspecialchars($formData['subject']) ?>">
                            <i class="fas fa-tag"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Message <span class="req">*</span></label>
                        <div class="input-wrapper textarea-icon">
                            <textarea name="message" placeholder="Tell me about your project..." required><?= htmlspecialchars($formData['message']) ?></textarea>
                            <i class="fas fa-comment-dots"></i>
                        </div>
                    </div>

                    <button type="submit" class="contact-submit" id="contactSubmit">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta-section">
        <div class="cta-container">
            <h2>Prefer a Quick <span>Chat?</span></h2>
            <p>Reach out on WhatsApp  I'm usually available and happy to discuss new ideas and opportunities.</p>
            <div class="cta-buttons">
                <a href="https://wa.me/923183299183?text=Hi%20Shiwa%2C%20I%20want%20to%20discuss%20a%20project"
                   target="_blank" class="btn-cta btn-cta-whatsapp">
                    <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                </a>
                <a href="mailto:aapki-email@://gmail.com." class="btn-cta">
                    <i class="fas fa-envelope"></i> Email Me
                </a>
                <a href="index.php" class="btn-cta-outline">
                    <i class="fas fa-house"></i> Back to Home
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <div class="footer">
        <p>&copy; <?= date('Y') ?> Shiwa Panoo. All rights reserved. | Designed with <i class="fas fa-heart" style="color: #00a8ff;"></i> by Shiwa Panoo</p>
    </div>

    <script>
        // ============ NAVBAR SCROLL ============
        const mainNav = document.getElementById('mainNav');
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) mainNav.classList.add('scrolled');
            else mainNav.classList.remove('scrolled');
        });

        // ============ MOBILE MENU TOGGLE ============
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('open');
            const icon = menuToggle.querySelector('i');
            icon.className = navLinks.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
        });
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('open');
                menuToggle.querySelector('i').className = 'fas fa-bars';
            });
        });

        // ============ CONTACT FORM LOADING STATE ============
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                const name    = this.querySelector('[name="name"]').value.trim();
                const email   = this.querySelector('[name="email"]').value.trim();
                const message = this.querySelector('[name="message"]').value.trim();

                if (!name || !email || !message) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                    return false;
                }

                const btn = document.getElementById('contactSubmit');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                btn.disabled = true;
                return true;
            });
        }

        // ============ REVEAL ON SCROLL ============
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, i * 80);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

        document.querySelectorAll('.contact-info, .contact-form-card').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.7s ease, transform 0.7s ease';
            revealObserver.observe(el);
        });

        <?php if ($contact_success || $contact_error): ?>
            document.querySelector('.contact-form-card').scrollIntoView({ behavior:'smooth', block:'center' });
        <?php endif; ?>
    </script>
</body>
</html>