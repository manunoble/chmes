<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

$search = $_GET['search'] ?? '';
$today = date('Y-m-d');

// 1. Fetch Stats
try {
    $total_today = $db->prepare("SELECT COUNT(*) FROM attendance WHERE service_date = ?");
    $total_today->execute([$today]);
    $count = $total_today->fetchColumn();
} catch (PDOException $e) { $count = 0; }

// 2. Fetch Records
try {
    if (!empty($search)) {
        $stmt = $db->prepare("SELECT * FROM attendance WHERE member_name LIKE ? OR service_type LIKE ? ORDER BY service_date DESC");
        $stmt->execute(["%$search%", "%$search%"]);
    } else {
        $stmt = $db->query("SELECT * FROM attendance ORDER BY created_at DESC LIMIT 15");
    }
    $recent_attendance = $stmt->fetchAll();
} catch (PDOException $e) { $recent_attendance = []; }
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 0; letter-spacing: -1px;">
                Attendance <span style="color: #fff;">Tracking</span>
            </h1>
            <p style="color: #94a3b8; font-size: 1.1rem; margin-top: 5px;">Monitor service participation and growth trends.</p>
        </div>

        <form method="GET" class="search-box">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search records...">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="stats-grid">
        <div class="stat-card red-glow">
            <h3>Total Today</h3>
            <p class="stat-number"><?= $count ?></p>
            <span class="stat-label">Live Update</span>
        </div>
        <div class="stat-card blue-glow">
            <h3>Avg. Attendance</h3>
            <p class="stat-number">485</p>
            <span class="stat-label">+12% from last month</span>
        </div>
        <div class="stat-card purple-glow">
            <h3 style="margin-bottom: 20px;">Quick Actions</h3>
            <a href="mark_attendance.php" class="action-btn">+ Mark Attendance</a>
            <a href="add_member.php" class="sub-link">Register New Member</a>
        </div>
    </div>

    <div class="attendance-container">
        <div class="table-header">
            <h3>Recent Participation</h3>
            <span class="badge"><?= count($recent_attendance) ?> Records Shown</span>
        </div>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Service Type</th>
                    <th>Date</th>
                    <th style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($recent_attendance)): ?>
                    <tr><td colspan="4" style="text-align:center; padding: 50px; color: #64748b;">No records found.</td></tr>
                <?php else: ?>
                    <?php foreach($recent_attendance as $row): ?>
                    <tr>
                        <td class="member-name"><?= htmlspecialchars($row['member_name']) ?></td>
                        <td><?= htmlspecialchars($row['service_type']) ?></td>
                        <td style="color: #94a3b8;"><?= date('D, M d Y', strtotime($row['service_date'])) ?></td>
                        <td style="text-align: right;">
                            <span class="status-badge">PRESENT</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* --- DARK SYSTEM STYLING --- */
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }

/* Search Box */
.search-box { background: rgba(255,255,255,0.05); padding: 8px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); display: flex; gap: 10px; }
.search-box input { background: transparent; border: none; padding: 10px 15px; color: #fff; outline: none; width: 250px; }
.search-box button { background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 700; transition: 0.3s; }
.search-box button:hover { background: #dc2626; transform: translateY(-2px); }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 3rem; }
.stat-card { background: rgba(30, 41, 59, 0.7); border: 1px solid rgba(255,255,255,0.1); padding: 30px; border-radius: 24px; backdrop-filter: blur(10px); }
.stat-card h3 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin: 0; }
.stat-number { font-size: 3rem; font-weight: 800; margin: 15px 0; color: #fff; }
.stat-label { font-size: 0.75rem; color: #10b981; font-weight: 600; }

/* Glow Effects */
.red-glow { border-bottom: 4px solid #ef4444; box-shadow: 0 10px 30px -10px rgba(239, 68, 68, 0.2); }
.blue-glow { border-bottom: 4px solid #3b82f6; box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.2); }
.purple-glow { border-bottom: 4px solid #a855f7; }

/* Table Section */
.attendance-container { background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; overflow: hidden; }
.table-header { padding: 25px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
.badge { background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }

.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { background: rgba(0,0,0,0.2); padding: 15px 25px; text-align: left; font-size: 0.75rem; color: #64748b; text-transform: uppercase; }
.modern-table td { padding: 18px 25px; border-bottom: 1px solid rgba(255,255,255,0.05); }
.member-name { font-weight: 700; color: #fff; }

/* Buttons & Badges */
.action-btn { display: block; background: #ef4444; color: white; text-align: center; padding: 12px; border-radius: 12px; text-decoration: none; font-weight: 700; margin-bottom: 10px; transition: 0.3s; }
.action-btn:hover { background: #fff; color: #ef4444; transform: scale(1.02); }
.sub-link { display: block; text-align: center; color: #94a3b8; text-decoration: none; font-size: 0.8rem; }
.status-badge { background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 5px 12px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; border: 1px solid rgba(16, 185, 129, 0.2); }
</style>

<?php include '../includes/footer.php'; ?>