<?php 
// 1. Core Logic First (Critical for redirects)
require '../includes/auth_check.php'; 
require '../includes/db.php';

// Handle Super Admin Toggle logic BEFORE any output starts
if(isset($_GET['toggle_super_admin'])) {
    // Check if the actual account role is super_admin before allowing the toggle
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') { // Adjust 'Admin' to your specific DB role
        $_SESSION['super_admin_mode'] = !($_SESSION['super_admin_mode'] ?? false);
    }
    header("Location: dashboard.php");
    exit();
}

// 2. Now include the header (which contains HTML output)
include '../includes/header.php'; 

$isSuperAdmin = $_SESSION['super_admin_mode'] ?? false;

// Define Modules
$modules = [
    ['id' => 'finances', 'name' => 'Finances', 'icon' => '$', 'color' => 'from-cyan-500 to-blue-600', 'path' => 'finances.php', 'desc' => 'Manage tithes & expenses'],
    ['id' => 'welfare', 'name' => 'Welfare', 'icon' => '♥', 'color' => 'from-pink-500 to-rose-600', 'path' => 'welfare.php', 'desc' => 'Track member care'],
    ['id' => 'events', 'name' => 'Events', 'icon' => '📅', 'color' => 'from-violet-500 to-purple-600', 'path' => 'events.php', 'desc' => 'Organize programs'],
    ['id' => 'attendance', 'name' => 'Attendance', 'icon' => '👥', 'color' => 'from-emerald-500 to-green-600', 'path' => 'attendance.php', 'desc' => 'Monitor services'],
    ['id' => 'contentHub', 'name' => 'Content Hub', 'icon' => '📖', 'color' => 'from-orange-500 to-amber-600', 'path' => 'content_hub.php', 'desc' => 'Sermons & Media'],
    ['id' => 'ministry', 'name' => 'Ministries', 'icon' => '✝', 'color' => 'from-indigo-500 to-blue-700', 'path' => 'ministries.php', 'desc' => 'Coordinate teams'],
    ['id' => 'sundaySchool', 'name' => 'Sunday School', 'icon' => '🎓', 'color' => 'from-teal-500 to-cyan-600', 'path' => 'sunday_school.php', 'desc' => 'Youth programs'],
    ['id' => 'discipleship', 'name' => 'Discipleship', 'icon' => '✨', 'color' => 'from-fuchsia-500 to-pink-600', 'path' => 'discipleship.php', 'desc' => 'Spiritual growth'],
    ['id' => 'Administration', 'name' => 'Administration', 'icon' => '✨', 'color' => 'from-fuchsia-500 to-pink-600', 'path' => 'administration.php', 'desc' => 'Admin']
];
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 2.5rem; color: #ef4444; margin-bottom: 10px; font-weight: bold;">
                ChMES <span style="color: #4f27c9; font-family:Arial, sans-serif; font-size: 1.5rem;">Church Management & Engagement System</span>
            </h1>
            <p style="color: #64748b; font-size: 1.1rem;">Empowering ministry through innovation</p>
        </div>

        <div style="background: <?= $isSuperAdmin ? 'rgba(239, 68, 68, 0.1)' : 'rgba(255,255,255,0.05)' ?>; padding: 15px 25px; border-radius: 20px; border: 1px solid <?= $isSuperAdmin ? '#ef4444' : 'rgba(255,255,255,0.1)' ?>; text-align: center; transition: 0.3s;">
            <p style="font-size: 0.7rem; color: #94a3b8; margin-bottom: 5px; letter-spacing: 0.1em;">ADMIN ACCESS</p>
            <a href="?toggle_super_admin=1" style="text-decoration: none; font-weight: bold; color: <?= $isSuperAdmin ? '#ef4444' : '#4b5563' ?>;">
                <?= $isSuperAdmin ? '🛡️ SUPER ADMIN ACTIVE' : '🛡️ ACTIVATE SUPER MODE' ?>
            </a>
        </div>
    </div>

    <?php if($isSuperAdmin): ?>
    <div style="background: linear-gradient(to right, rgba(239, 68, 68, 0.2), transparent); border-left: 4px solid #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 2rem; color: #ef4444; display: flex; align-items: center; gap: 10px; font-weight: 600;">
        <span>🚀</span> Super Admin Mode: Advanced configurations and restrictions bypassed.
    </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
        <?php foreach($modules as $m): ?>
        <a href="<?= $m['path'] ?>" style="text-decoration: none; color: inherit;">
            <div class="module-card" style="position: relative; background: white; border: 1px solid #e2e8f0; padding: 35px; border-radius: 28px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); height: 100%; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                
                <div style="width: 60px; height: 60px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: #1e293b; margin-bottom: 25px;">
                    <?= $m['icon'] ?>
                </div>

                <h3 style="color: #1e293b; margin-bottom: 10px; font-size: 1.4rem; font-weight: 700;"><?= $m['name'] ?></h3>
                <p style="color: #64748b; font-size: 0.95rem; line-height: 1.6;"><?= $m['desc'] ?></p>

                <?php if($isSuperAdmin): ?>
                    <div style="position: absolute; top: 25px; right: 25px; width: 12px; height: 12px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 15px rgba(239, 68, 68, 0.5);"></div>
                <?php endif; ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
.module-card:hover {
    transform: translateY(-8px);
    border-color: #ef4444 !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
.module-card:hover h3 {
    color: #ef4444 !important;
}
</style>

<?php include '../includes/footer.php'; ?>