<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Take One Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
   
    <style>
        :root {
            --admin-bg: #111;
            --admin-card: #1e1e1e;
            --admin-accent: #be1e2d;
            --admin-text: #fff;
            --admin-text-muted: #aaa;
            --sidebar-width: 250px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--admin-bg);
            color: var(--admin-text);
            font-family: 'Roboto', sans-serif;
        }

        /* ===== TOP NAV (mobile) ===== */
        .admin-topbar {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            background: var(--admin-card);
            border-bottom: 1px solid #333;
            z-index: 1000;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        .admin-topbar h3 {
            color: var(--admin-accent);
            font-weight: 800;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .hamburger {
            background: none;
            border: none;
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            padding: 5px;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--admin-card);
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #333;
            display: flex;
            flex-direction: column;
            z-index: 999;
            transition: transform 0.35s ease;
        }
        .sidebar-brand {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid #333;
            flex-shrink: 0;
        }
        .sidebar-brand h3 {
            color: var(--admin-accent);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 18px;
        }
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0;
        }
        .nav-link {
            color: var(--admin-text-muted);
            padding: 14px 25px;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: 0.25s;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-right: 3px solid transparent;
        }
        .nav-link i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: rgba(190, 30, 45, 0.08);
            border-right-color: var(--admin-accent);
        }
        .sidebar-footer {
            border-top: 1px solid #333;
            flex-shrink: 0;
        }

        /* ===== OVERLAY ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 998;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
        }

        /* ===== CARDS ===== */
        .card {
            background: var(--admin-card);
            border: none;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .card-header {
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid #333;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0 !important;
        }
        .card-body { padding: 20px; }

        /* ===== TABLE ===== */
        .table { color: var(--admin-text); }
        .table thead th {
            border-bottom: 2px solid #333;
            color: var(--admin-accent);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .table td {
            border-bottom: 1px solid #292929;
            vertical-align: middle;
        }

        /* ===== BUTTONS ===== */
        .btn-accent {
            background: var(--admin-accent);
            color: #fff;
            border: none;
            border-radius: 6px;
        }
        .btn-accent:hover { background: #a01926; color: #fff; }

        /* ===== BADGES ===== */
        .badge-pending  { background: #f39c12; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .badge-approved { background: #27ae60; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .badge-published{ background: #27ae60; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .badge-draft    { background: #555;     color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 12px; }

        /* ===== FORM CONTROLS ===== */
        .form-control, .form-select {
            background: #2a2a2a;
            border: 1px solid #333;
            color: #fff;
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            background: #333;
            color: #fff;
            border-color: var(--admin-accent);
            box-shadow: none;
        }
        .form-control::placeholder { color: #666; }
        label { color: #aaa; font-size: 13px; margin-bottom: 6px; display: block; }

        /* ===== ALERT ===== */
        .alert-success { background: rgba(39,174,96,0.15); border-color: #27ae60; color: #2ecc71; }
        .alert-danger  { background: rgba(231,76,60,0.15);  border-color: #e74c3c; color: #ff6b6b; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .admin-topbar { display: flex; }
            .sidebar {
                transform: translateX(-100%);
                top: 0;
            }
            .sidebar.open { transform: translateX(0); }
            .main-content {
                margin-left: 0;
                padding: 20px 15px;
                padding-top: 80px; /* space for topbar */
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .table-responsive { overflow-x: auto; }
        }

        @media (max-width: 480px) {
            .main-content { padding: 15px 10px; padding-top: 75px; }
            h3 { font-size: 18px; }
        }
    </style>
</head>
<body>

    <!-- Mobile Top Bar -->
    <div class="admin-topbar">
        <h3>Take One</h3>
        <button class="hamburger" id="sidebar-toggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Overlay -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h3>Take One</h3>
        </div>
        <div class="sidebar-nav">
            <a class="nav-link" href="index.php"><i class="fas fa-th-large"></i> Dashboard</a>
            <a class="nav-link" href="posts.php"><i class="fas fa-newspaper"></i> Blog Posts</a>
            <a class="nav-link" href="categories.php"><i class="fas fa-list"></i> Categories</a>
            <a class="nav-link" href="comments.php"><i class="fas fa-comments"></i> Comments</a>
            <a class="nav-link" href="admins.php"><i class="fas fa-users-cog"></i> Admin Users</a>
            <a class="nav-link" href="../index.php" target="_blank"><i class="fas fa-external-link-alt"></i> View Site</a>
        </div>
        <div class="sidebar-footer">
            <a class="nav-link" style="color: #e74c3c;" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">

    <script>
        const toggle   = document.getElementById('sidebar-toggle');
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }

        toggle.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);
    </script>
