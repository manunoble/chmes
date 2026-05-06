<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch all staff members
$staff = $db->query("SELECT id, full_name, username, role, phone, department, status FROM users ORDER BY role ASC, full_name ASC")->fetchAll();
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <a href="administration.php" class="back-link">← Back to Admin</a>
            <h1 style="font-size: 2.5rem; color: #a855f7; font-weight: 800; margin: 10px 0 0 0;">
                Staff <span style="color: #fff;">Directory</span>
            </h1>
        </div>
        <a href="add_staff.php" class="action-btn-purple">+ Add New Staff</a>
    </header>

    <div class="glass-panel">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($staff as $s): ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div class="avatar-sm">
                                <?= substr($s['full_name'], 0, 1) ?>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #fff;"><?= htmlspecialchars($s['full_name']) ?></div>
                                <div style="font-size: 0.75rem; color: #64748b;">@<?= htmlspecialchars($s['username']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="role-pill <?= strtolower($s['role']) ?>">
                            <?= $s['role'] ?>
                        </span>
                    </td>
                    <td style="color: #94a3b8;"><?= $s['department'] ?? 'General' ?></td>
                    <td style="color: #94a3b8; font-size: 0.85rem;"><?= $s['phone'] ?? 'N/A' ?></td>
                    <td>
                        <span class="status-indicator <?= strtolower($s['status']) ?>"></span>
                        <?= $s['status'] ?>
                    </td>
                    <td style="text-align: right;">
                        <a href="edit_staff.php?id=<?= $s['id'] ?>" class="edit-btn">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; }
.action-btn-purple { background: #a855f7; color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; }

.avatar-sm { width: 35px; height: 35px; background: rgba(168, 85, 247, 0.2); color: #a855f7; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem; }

/* Role Styling */
.role-pill { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
.admin { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
.staff { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }

/* Status Styling */
.status-indicator { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px; }
.active { background: #10b981; box-shadow: 0 0 8px #10b981; }
.inactive { background: #64748b; }

.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { text-align: left; color: #64748b; font-size: 0.75rem; padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); text-transform: uppercase; }
.modern-table td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }

.edit-btn { color: #a855f7; text-decoration: none; font-size: 0.85rem; font-weight: 700; }
.edit-btn:hover { color: #fff; }
</style>

<?php include '../includes/footer.php'; ?>