<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Fetch Stats
$total_members = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
$active_members = $db->query("SELECT COUNT(*) FROM members WHERE status = 'Active'")->fetchColumn();
$visitors = $db->query("SELECT COUNT(*) FROM members WHERE status = 'Visitor'")->fetchColumn();

// 2. Handle Search
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $db->prepare("SELECT * FROM members WHERE full_name LIKE ? OR phone LIKE ? ORDER BY full_name ASC");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $db->query("SELECT * FROM members ORDER BY full_name ASC");
}
$members = $stmt->fetchAll();
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h1 style="color: #ef4444; font-weight: 800; font-size: 2.5rem;">Member <span style="color: #1e293b;">Directory</span></h1>
            <p style="color: #64748b;">Manage and support your church community.</p>
        </div>
        <a href="add_member.php" class="btn-gradient" style="background: #ef4444; text-decoration: none; padding: 12px 25px;">
            + Add New Member
        </a>
    </header>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 2.5rem;">
        <div class="glass-panel" style="padding: 20px; text-align: center; border-bottom: 4px solid #3b82f6;">
            <p style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Total Registered</p>
            <h2 style="font-size: 2rem; color: #1e293b;"><?= $total_members ?></h2>
        </div>
        <div class="glass-panel" style="padding: 20px; text-align: center; border-bottom: 4px solid #10b981;">
            <p style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Active Members</p>
            <h2 style="font-size: 2rem; color: #10b981;"><?= $active_members ?></h2>
        </div>
        <div class="glass-panel" style="padding: 20px; text-align: center; border-bottom: 4px solid #f59e0b;">
            <p style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; font-weight: 700;">Visitors</p>
            <h2 style="font-size: 2rem; color: #f59e0b;"><?= $visitors ?></h2>
        </div>
    </div>

    <div class="glass-panel" style="padding: 0; overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #e2e8f0;">
            <form method="GET" style="display: flex; gap: 10px;">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Search by name or phone..." 
                       class="settings-input" style="max-width: 400px;">
                <button type="submit" class="btn-gradient" style="background: #1e293b;">Search</button>
            </form>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; text-align: left; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase;">
                    <th style="padding: 15px 25px;">Name</th>
                    <th style="padding: 15px 25px;">Contact</th>
                    <th style="padding: 15px 25px;">Status</th>
                    <th style="padding: 15px 25px;">Joined Date</th>
                    <th style="padding: 15px 25px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($members as $m): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 15px 25px;">
                        <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($m['full_name']) ?></div>
                        <div style="font-size: 0.8rem; color: #94a3b8;"><?= htmlspecialchars($m['email'] ?: 'No Email') ?></div>
                    </td>
                    <td style="padding: 15px 25px; color: #64748b;"><?= htmlspecialchars($m['phone'] ?: '--') ?></td>
                    <td style="padding: 15px 25px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; 
                            <?= $m['status'] === 'Active' ? 'background: #dcfce7; color: #166534;' : 'background: #fef3c7; color: #92400e;' ?>">
                            <?= strtoupper($m['status']) ?>
                        </span>
                    </td>
                    <td style="padding: 15px 25px; color: #64748b;"><?= date('M Y', strtotime($m['membership_date'])) ?></td>
                    <td style="padding: 15px 25px; text-align: right;">
                        <a href="edit_member.php?id=<?= $m['id'] ?>" style="color: #3b82f6; text-decoration: none; font-size: 0.85rem; font-weight: 600;">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>