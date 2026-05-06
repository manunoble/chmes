<?php
// 1. Establish the absolute root path
$base_path = dirname(__DIR__);

// 2. Require files
require_once $base_path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'constants.php';
require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'auth_check.php';
require_once $base_path . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Fetch Branding
$brandStmt = $db->query("SELECT church_name, church_logo FROM system_settings WHERE id = 1");
$branding = $brandStmt->fetch();
$display_name = $branding['church_name'] ?? 'CHIMES';
$display_logo = $branding['church_logo'] ?? '../assets/images/default-logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        aside { z-index: 50; }
        @media (min-width: 768px) {
            main { margin-left: 16rem !important; width: calc(100% - 16rem) !important; }
        }
        .main-content, .grid { display: grid; width: 100%; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">
<div class="flex min-h-screen">
    <aside class="w-64 bg-slate-900 text-white hidden md:flex flex-col fixed h-full">
        <div class="sidebar-header flex items-center gap-3 p-6 border-b border-slate-800">
             <img src="<?= $display_logo ?>" alt="Logo" style="width: 35px; height: 35px; border-radius: 6px; object-fit: cover;">
             <h1 class="text-xl font-bold tracking-tight text-white"><?= htmlspecialchars($display_name) ?></h1>
        </div>
        
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="dashboard.php" class="nav-link flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
            </a>
            <a href="attendance.php" class="nav-link flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="users" class="w-5 h-5"></i> Attendance
            </a>
            <a href="finance.php" class="nav-link flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="wallet" class="w-5 h-5"></i> Finance
            </a>
            <a href="discipleship.php" class="nav-link flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="sparkles" class="w-5 h-5"></i> Discipleship
            </a>
            <a href="content_hub.php" class="nav-link flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="book-open" class="w-5 h-5"></i> Content Hub
            </a>
            <a href="events.php" class="nav-link flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="calendar-days" class="w-5 h-5"></i> Events
            </a>
            <a href="events.php" class="nav-link flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="calendar-days" class="w-5 h-5"></i> Administration
            <div class="pt-4 mt-4 border-t border-slate-800">
                <a href="settings.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-800 transition text-slate-400">
                    <i data-lucide="settings" class="w-5 h-5"></i> Settings
                </a>
                <a href="../api/auth_logout.php" class="flex items-center gap-3 p-3 rounded-lg hover:bg-red-900/30 text-red-400 font-bold transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Logout
                </a>
            </div>
        </nav>
    </aside>

    <main class="flex-1">
        <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-medium text-slate-500 uppercase tracking-wider">Welcome back,</h2>
                <span class="text-lg font-bold text-slate-800"><?php echo $_SESSION['full_name']; ?></span>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full uppercase font-black">
                    <?php echo $_SESSION['role']; ?>
                </span>
                
                <div class="group relative">
                    <div class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center font-bold text-white cursor-pointer hover:bg-slate-700 transition">
                        <?php echo isset($_SESSION['full_name']) ? getInitials($_SESSION['full_name']) : '??'; ?>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="p-8">