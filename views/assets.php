<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch all assets
$assets = $db->query("SELECT * FROM church_assets ORDER BY category ASC, asset_name ASC")->fetchAll();
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <a href="administration.php" class="back-link">← Back to Admin</a>
            <h1 style="font-size: 2.5rem; color: #f59e0b; font-weight: 800; margin: 10px 0 0 0;">
                Asset <span style="color: #fff;">Inventory</span>
            </h1>
            <p style="color: #94a3b8;">Tracking church hardware, electronics, and property.</p>
        </div>
        <a href="add_asset.php" class="action-btn-orange">+ Add New Asset</a>
    </header>

    <div class="glass-panel">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Asset Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Last Audit</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($assets as $a): ?>
                <tr>
                    <td>
                        <div style="font-weight: 700; color: #fff;"><?= htmlspecialchars($a['asset_name']) ?></div>
                        <div style="font-size: 0.75rem; color: #64748b;">S/N: <?= htmlspecialchars($a['serial_number'] ?? 'N/A') ?></div>
                    </td>
                    <td style="color: #94a3b8;"><?= $a['category'] ?></td>
                    <td>
                        <span class="status-pill-orange <?= strtolower($a['status']) ?>">
                            <?= $a['status'] ?>
                        </span>
                    </td>
                    <td style="color: #94a3b8;"><?= htmlspecialchars($a['assigned_to'] ?? 'General') ?></td>
                    <td style="color: #64748b; font-size: 0.85rem;"><?= $a['last_audit_date'] ?? 'Never' ?></td>
                    <td style="text-align: right;">
                        <a href="edit_asset.php?id=<?= $a['id'] ?>" class="edit-link">Update</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($assets)): ?>
                    <tr><td colspan="6" style="text-align:center; padding:40px; color:#64748b;">No assets registered in the inventory.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; }
.action-btn-orange { background: #f59e0b; color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; }

.status-pill-orange { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
.functional { background: rgba(16, 185, 129, 0.2); color: #10b981; }
.maintenance { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
.damaged { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { text-align: left; color: #64748b; font-size: 0.75rem; padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); text-transform: uppercase; }
.modern-table td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }

.edit-link { color: #f59e0b; text-decoration: none; font-size: 0.85rem; font-weight: 700; }
.edit-link:hover { color: #fff; }
</style>

<?php include '../includes/footer.php'; ?>