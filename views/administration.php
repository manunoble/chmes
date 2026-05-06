<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Fetch Quick Admin Stats
try {
    $total_staff = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    // We check if the table exists to avoid errors before your SQL migration
    $tableCheck = $db->query("SHOW TABLES LIKE 'pastoral_records'")->rowCount();
    $total_baptisms = ($tableCheck > 0) ? $db->query("SELECT COUNT(*) FROM pastoral_records WHERE event_type = 'Baptism'")->fetchColumn() : 0;
    
    $logCheck = $db->query("SHOW TABLES LIKE 'system_logs'")->rowCount();
    $recent_logs = ($logCheck > 0) ? $db->query("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 5")->fetchAll() : [];
} catch (PDOException $e) {
    $total_staff = 0;
    $total_baptisms = 0;
    $recent_logs = [];
}
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 0; letter-spacing: -1px;">
                Admin <span style="color: #fff;">Center</span>
            </h1>
            <p style="color: #94a3b8; font-size: 1.1rem; margin-top: 5px;">Governance, pastoral records, and system oversight.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="reports.php" class="action-btn-outline">📊 Global Report</a>
            <a href="settings.php" class="action-btn-red">⚙️ System Settings</a>
        </div>
    </header>

    <!-- Welcome & Identity Card -->
    <div class="glass-panel" style="margin-bottom: 25px; border-left: 4px solid #ef4444;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #ef4444;">
                <i data-lucide="user-check"></i>
            </div>
            <div>
                <h4 style="margin: 0; color: #fff;">Session Identity: <?= htmlspecialchars($_SESSION['full_name']) ?></h4>
                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Access Level: <span style="color: #ef4444; font-weight: 700;"><?= htmlspecialchars($_SESSION['role']) ?></span></p>
            </div>
        </div>
    </div>

    <!-- Admin Quick Actions Grid -->
    <div class="admin-grid">
        
        <!-- Pastoral Office -->
        <div class="admin-card">
            <div class="card-icon blue-gradient"><i data-lucide="scroll-text"></i></div>
            <div class="card-content">
                <h3>Pastoral Office</h3>
                <p>Manage Baptisms, Marriages, and Dedications.</p>
                <div class="card-footer">
                    <a href="pastoral_records.php" class="link-btn">Enter Office →</a>
                </div>
            </div>
        </div>

        <!-- Staff & HR -->
        <div class="admin-card">
            <div class="card-icon purple-gradient"><i data-lucide="users-2"></i></div>
            <div class="card-content">
                <h3>Staff & HR</h3>
                <p>Manage <?= $total_staff ?> registered users and staff roles.</p>
                <div class="card-footer">
                    <a href="staff_directory.php" class="link-btn">Manage Staff →</a>
                </div>
            </div>
        </div>

        <!-- Church Policies -->
        <div class="admin-card">
            <div class="card-icon green-gradient"><i data-lucide="shield-check"></i></div>
            <div class="card-content">
                <h3>Policy Hub</h3>
                <p>Church constitution and official documents.</p>
                <div class="card-footer">
                    <a href="church_policies.php" class="link-btn">View Policies →</a>
                </div>
            </div>
        </div>

        <!-- Inventory / Assets -->
        <div class="admin-card">
            <div class="card-icon orange-gradient"><i data-lucide="package"></i></div>
            <div class="card-content">
                <h3>Inventory</h3>
                <p>Track hardware (vMix, Vitron TV, Laptops).</p>
                <div class="card-footer">
                    <a href="assets.php" class="link-btn">Audit Assets →</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Audit Trail Section -->
    <div class="log-section glass-panel" style="margin-top: 30px;">
        <div class="section-header">
            <h3>Recent System Activity</h3>
            <span class="status-pill active">Audit Trail</span>
        </div>
        <table class="log-table">
            <thead>
                <tr>
                    <th>Operator</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                    <th style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($recent_logs)): ?>
                    <tr><td colspan="4" style="text-align:center; padding:20px; color:#64748b;">Waiting for activity logs...</td></tr>
                <?php else: ?>
                    <?php foreach($recent_logs as $log): ?>
                    <tr>
                        <td style="color: #fff; font-weight: 600;"><?= htmlspecialchars($log['username']) ?></td>
                        <td style="color: #94a3b8;"><?= htmlspecialchars($log['action']) ?></td>
                        <td style="color: #64748b; font-size: 0.8rem;"><?= $log['created_at'] ?></td>
                        <td style="text-align: right;"><span class="success-dot"></span> Recorded</td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* --- ADMINISTRATION STYLES --- */
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }

.admin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }

.admin-card {
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 24px;
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    transition: 0.3s ease;
}

.admin-card:hover { transform: translateY(-5px); border-color: rgba(239, 68, 68, 0.3); }

.card-icon { width: 60px; height: 60px; border-radius: 16px; display: flex; align-items: center; justify-content: center; color: #fff; }

.blue-gradient { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.purple-gradient { background: linear-gradient(135deg, #a855f7, #7e22ce); }
.green-gradient { background: linear-gradient(135deg, #10b981, #047857); }
.orange-gradient { background: linear-gradient(135deg, #f59e0b, #b45309); }

.card-content h3 { margin: 0; font-size: 1.2rem; font-weight: 700; }
.card-content p { color: #94a3b8; font-size: 0.85rem; margin-top: 8px; }

.link-btn { text-decoration: none; color: #ef4444; font-weight: 700; font-size: 0.85rem; }

.glass-panel { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 24px; }
.log-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
.log-table th { text-align: left; font-size: 0.7rem; color: #64748b; text-transform: uppercase; padding: 10px; }
.log-table td { padding: 15px 10px; border-bottom: 1px solid rgba(255,255,255,0.03); }

.success-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block; margin-right: 5px; box-shadow: 0 0 8px #10b981; }

.action-btn-red { background: #ef4444; color: #fff; padding: 12px 20px; border-radius: 12px; font-weight: 700; text-decoration: none; }
.action-btn-outline { border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; }
</style>

<?php include '../includes/footer.php'; ?>