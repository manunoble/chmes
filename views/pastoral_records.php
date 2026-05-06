<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch all records with member names
$sql = "SELECT p.*, m.full_name 
        FROM pastoral_records p 
        JOIN members m ON p.member_id = m.id 
        ORDER BY p.event_date DESC";
$records = $db->query($sql)->fetchAll();
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <a href="administration.php" class="back-link">← Back to Admin</a>
            <h1 style="font-size: 2.5rem; color: #3b82f6; font-weight: 800; margin: 10px 0 0 0;">
                Pastoral <span style="color: #fff;">Records</span>
            </h1>
        </div>
        <a href="add_pastoral_record.php" class="action-btn-blue">+ Log New Event</a>
    </header>

    <div class="glass-panel">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Member</th>
                    <th>Event Type</th>
                    <th>Date</th>
                    <th>Officiant</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($records as $r): ?>
                <tr>
                    <td class="font-bold"><?= htmlspecialchars($r['full_name']) ?></td>
                    <td>
                        <span class="type-badge <?= strtolower($r['event_type']) ?>">
                            <?= $r['event_type'] ?>
                        </span>
                    </td>
                    <td style="color: #94a3b8;"><?= date('M d, Y', strtotime($r['event_date'])) ?></td>
                    <td>Rev. <?= htmlspecialchars($r['officiant_name']) ?></td>
                    <td style="text-align: right;">
                        <?php if($r['certificate_path']): ?>
                            <a href="../uploads/certificates/<?= $r['certificate_path'] ?>" class="icon-link">📄</a>
                        <?php endif; ?>
                        <a href="../api/delete_pastoral_record.php?id=<?= $r['id'] ?>" class="icon-link delete" onclick="return confirm('Delete this record?')">🗑️</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($records)): ?>
                    <tr><td colspan="5" style="text-align:center; padding:40px; color:#64748b;">No pastoral records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; }
.action-btn-blue { background: #3b82f6; color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; }

/* Badges for different events */
.type-badge { padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; }
.baptism { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
.marriage { background: rgba(168, 85, 247, 0.2); color: #a855f7; }
.dedication { background: rgba(16, 185, 129, 0.2); color: #10b981; }

.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { text-align: left; color: #64748b; font-size: 0.75rem; padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); }
.modern-table td { padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05); }
.icon-link { text-decoration: none; margin-left: 10px; font-size: 1.1rem; }
.icon-link.delete { filter: grayscale(1); transition: 0.3s; }
.icon-link.delete:hover { filter: grayscale(0); }
</style>

<?php include '../includes/footer.php'; ?>