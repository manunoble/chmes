<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Fetch High-Level Analytics
try {
    // Member Growth
    $total_members = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
    $new_this_month = $db->query("SELECT COUNT(*) FROM members WHERE MONTH(created_at) = MONTH(CURRENT_DATE())")->fetchColumn();

    // Attendance Average (Last 30 Days)
    $avg_attendance = $db->query("SELECT AVG(count) FROM attendance WHERE attendance_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

    // Pastoral Office Activity
    $pastoral_count = $db->query("SELECT COUNT(*) FROM pastoral_records")->fetchColumn();

    // Financial Pulse (Placeholder if finances table isn't full yet)
    // $total_revenue = $db->query("SELECT SUM(amount) FROM church_finances WHERE type = 'Income'")->fetchColumn();

} catch (PDOException $e) {
    // Handle cases where tables might not exist yet
    $total_members = $new_this_month = $avg_attendance = $pastoral_count = 0;
}
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <a href="administration.php" class="back-link">← Back to Admin</a>
            <h1 style="font-size: 2.5rem; color: #f59e0b; font-weight: 800; margin: 10px 0 0 0;">
                Reporting <span style="color: #fff;">Engine</span>
            </h1>
            <p style="color: #94a3b8;">Real-time organizational analytics and data trends.</p>
        </div>
        <button onclick="window.print()" class="action-btn-gold">🖨️ Export PDF Report</button>
    </header>

    <div class="report-stats">
        <div class="report-card">
            <span class="label">Total Congregation</span>
            <div class="value"><?= number_format($total_members) ?></div>
            <span class="trend pos">+ <?= $new_this_month ?> this month</span>
        </div>
        <div class="report-card">
            <span class="label">Avg. Attendance</span>
            <div class="value"><?= round($avg_attendance) ?></div>
            <span class="trend">Last 30 Days</span>
        </div>
        <div class="report-card">
            <span class="label">Pastoral Records</span>
            <div class="value"><?= $pastoral_count ?></div>
            <span class="trend">Life Events Logged</span>
        </div>
        <div class="report-card">
            <span class="label">System Users</span>
            <div class="value"><?= $db->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></div>
            <span class="trend">Active Staff</span>
        </div>
    </div>

    <div class="report-grid" style="margin-top: 30px; display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
        <div class="glass-panel">
            <h3 style="margin-bottom: 20px; color: #fff;">Membership Distribution</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php
                // Example: Logic to see how many members in each category
                $dist = $db->query("SELECT category, COUNT(*) as count FROM members GROUP BY category")->fetchAll();
                foreach($dist as $d):
                    $percentage = ($total_members > 0) ? ($d['count'] / $total_members) * 100 : 0;
                ?>
                <div class="progress-item">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.85rem;">
                        <span style="color: #94a3b8;"><?= $d['category'] ?></span>
                        <span style="color: #fff; font-weight: 700;"><?= $d['count'] ?></span>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: <?= $percentage ?>%; background: #f59e0b;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="glass-panel">
    <h3 style="margin-bottom: 20px; color: #fff;">Membership Distribution</h3>
    <div style="display: flex; flex-direction: column; gap: 15px;">
        <?php
        // We use COALESCE to handle members who don't have a category assigned yet
        $dist_query = "SELECT COALESCE(category, 'Uncategorized') as cat, COUNT(*) as count 
                       FROM members 
                       GROUP BY cat";
        $dist = $db->query($dist_query)->fetchAll();
        
        foreach($dist as $d):
            $percentage = ($total_members > 0) ? ($d['count'] / $total_members) * 100 : 0;
        ?>
        <div class="progress-item">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.85rem;">
                <span style="color: #94a3b8;"><?= htmlspecialchars($d['cat']) ?></span>
                <span style="color: #fff; font-weight: 700;"><?= $d['count'] ?></span>
            </div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: <?= $percentage ?>%; background: #f59e0b;"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; }
.action-btn-gold { background: #f59e0b; color: #fff; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; cursor: pointer; }

/* Reporting Cards */
.report-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
.report-card { background: rgba(30, 41, 59, 0.4); padding: 30px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.05); }
.report-card .label { font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; letter-spacing: 1px; }
.report-card .value { font-size: 2.5rem; font-weight: 800; color: #fff; margin: 10px 0; }
.report-card .trend { font-size: 0.8rem; color: #94a3b8; }
.report-card .trend.pos { color: #10b981; font-weight: 600; }

/* Progress Bars */
.progress-bar-bg { background: rgba(255,255,255,0.05); height: 8px; border-radius: 10px; overflow: hidden; }
.progress-bar-fill { height: 100%; border-radius: 10px; transition: 1s ease-in-out; }

.glass-panel { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255,255,255,0.05); padding: 30px; border-radius: 28px; }

/* Printing Logic */
@media print {
    aside, .back-link, .action-btn-gold { display: none !important; }
    main { margin-left: 0 !important; width: 100% !important; }
    .report-card { border: 1px solid #ccc !important; color: #000 !important; }
    .main-content { background: #fff !important; color: #000 !important; }
}
</style>

<?php include '../includes/footer.php'; ?>