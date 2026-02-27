<?php
session_start();
require_once '../includes/db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Take One</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: #0d0d0d;
            display: flex;
            font-family: 'Roboto', 'Segoe UI', sans-serif;
        }

        /* ===== LEFT PANEL ===== */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #0d0d0d 0%, #1a0608 60%, #2d0a0d 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 50px 60px;
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute;
            top: -200px; left: -200px;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(190,30,45,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -150px; right: -150px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(190,30,45,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .brand-logo {
            position: relative;
            z-index: 1;
        }
        .brand-logo .logo-text {
            font-size: 26px;
            font-weight: 800;
            color: #be1e2d;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .brand-logo .logo-sub {
            font-size: 11px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 4px;
        }
        .left-headline {
            position: relative;
            z-index: 1;
        }
        .left-headline h1 {
            font-size: 52px;
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        .left-headline h1 span {
            color: #be1e2d;
        }
        .left-headline p {
            color: #666;
            font-size: 16px;
            line-height: 1.7;
            max-width: 380px;
        }
        .left-footer {
            position: relative;
            z-index: 1;
        }
        .back-to-site {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #555;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.3s;
        }
        .back-to-site:hover {
            color: #be1e2d;
        }
        .back-to-site i {
            transition: transform 0.3s;
        }
        .back-to-site:hover i {
            transform: translateX(-4px);
        }

        /* ===== RIGHT PANEL ===== */
        .login-right {
            width: 480px;
            flex-shrink: 0;
            background: #161616;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 50px;
            border-left: 1px solid #222;
        }
        .login-form-wrap {
            width: 100%;
            max-width: 380px;
        }
        .login-form-wrap .form-title {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 8px;
        }
        .login-form-wrap .form-subtitle {
            color: #555;
            font-size: 14px;
            margin-bottom: 40px;
        }

        /* Error Box */
        .error-box {
            background: rgba(190, 30, 45, 0.1);
            border: 1px solid rgba(190, 30, 45, 0.3);
            border-radius: 10px;
            padding: 14px 20px;
            color: #ff6b6b;
            font-size: 14px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Input Groups */
        .input-group-wrap {
            margin-bottom: 20px;
        }
        .input-group-wrap label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .input-field {
            position: relative;
        }
        /* Left icon — direct child only, does NOT affect nested toggle icon */
        .input-field > i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #444;
            font-size: 15px;
            pointer-events: none;
            transition: color 0.3s;
            z-index: 2;
        }
        .input-field input {
            width: 100%;
            height: 56px;
            background: #1e1e1e;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            color: #fff;
            font-size: 15px;
            padding: 0 50px 0 50px;
            transition: all 0.3s;
            outline: none;
        }
        .input-field input:focus {
            border-color: #be1e2d;
            background: #222;
            box-shadow: 0 0 0 3px rgba(190, 30, 45, 0.1);
        }
        .input-field input:focus ~ i {
            color: #be1e2d;
        }
        .input-field input::placeholder { color: #444; }

        /* Toggle Password */
        /* Right toggle — isolated from left icon styles */
        .toggle-pwd {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #444;
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
        }
        .toggle-pwd:hover { color: #be1e2d; }
        .toggle-pwd i { pointer-events: none; }

        /* Submit Button */
        .login-btn {
            width: 100%;
            height: 56px;
            background: linear-gradient(135deg, #be1e2d, #8a1520);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.4s;
            margin-top: 10px;
            position: relative;
            overflow: hidden;
        }
        .login-btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0);
            transition: 0.3s;
        }
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(190, 30, 45, 0.35);
        }
        .login-btn:hover::after { background: rgba(255,255,255,0.05); }
        .login-btn:active { transform: translateY(0); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .login-left { display: none; }
            .login-right {
                width: 100%;
                border-left: none;
                padding: 40px 25px;
            }
            body { background: #161616; }
        }
    </style>
</head>
<body>

    <!-- Left Decorative Panel -->
    <div class="login-left">
        <div class="brand-logo">
            <div class="logo-text">Take One</div>
            <div class="logo-sub">Admin Portal</div>
        </div>

        <div class="left-headline">
            <h1>Crafting<br>Talent. <span>Stories.</span><br>Impact.</h1>
            <p>Manage your blog content, curate stories, moderate community responses, and keep your creative ecosystem thriving — all from one place.</p>
        </div>

        <div class="left-footer">
            <a href="../index.php" class="back-to-site">
                <i class="fas fa-arrow-left"></i>
                Back to Website
            </a>
        </div>
    </div>

    <!-- Right Login Panel -->
    <div class="login-right">
        <div class="login-form-wrap">
            <h2 class="form-title">Welcome back.</h2>
            <p class="form-subtitle">Sign in to access the Take One admin dashboard.</p>

            <?php if ($error): ?>
            <div class="error-box">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="input-group-wrap">
                    <label for="username">Username</label>
                    <div class="input-field">
                        <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="off" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                        <i class="fas fa-user field-icon"></i>
                    </div>
                </div>

                <div class="input-group-wrap">
                    <label for="password">Password</label>
                    <div class="input-field">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required style="padding-right: 55px;">
                        <i class="fas fa-lock field-icon"></i>
                        <span class="toggle-pwd" onclick="togglePwd()" id="toggle-icon" title="Show/hide password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="login-btn">Sign In</button>
            </form>

            <div style="text-align: center; margin-top: 30px;">
                <a href="../index.php" style="color: #444; font-size: 13px; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#be1e2d'" onmouseout="this.style.color='#444'">
                    <i class="fas fa-globe" style="margin-right: 6px;"></i> Return to Take One Website
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePwd() {
            const pwd  = document.getElementById('password');
            const icon = document.querySelector('#toggle-icon i');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                pwd.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }
    </script>
</body>
</html>
