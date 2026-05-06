<?php 
include '../includes/header.php'; 

// 1. SAFETY CHECK: Force-load the database
if (!isset($db)) {
    $db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
    require_once $db_path;
}

// 2. Get Ministry ID
$ministry_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // 3. Fetch Ministry Details
    $mStmt = $db->prepare("SELECT name FROM ministries WHERE id = ?");
    $mStmt->execute([$ministry_id]);
    $ministry = $mStmt->fetch();

    if (!$ministry) { die("<div class='p-8 text-red-500'>Ministry not found.</div>"); }

    // 4. Fetch Roster Members
    $query = "SELECT u.full_name, u.email, mm.role, mm.joined_at 
              FROM ministry_members mm
              JOIN users u ON mm.user_id = u.id
              WHERE mm.ministry_id = ?
              ORDER BY mm.role DESC, u.full_name ASC";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$ministry_id]);
    $members = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: #3b82f6; margin-bottom: 0.5rem;"><?= htmlspecialchars($ministry['name']) ?> <span style="color: #a78bfa;">Roster</span></h1>
            <p style="color: #64748b;">Current active volunteers and assigned roles.</p>
        </div>
        <button onclick="window.history.back()" style="padding: 10px 20px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 10px; cursor: pointer; font-weight: 600;">
            ← Back to Hub
        </button>
    </div>

    <div style="background: white; border-radius: 15px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; text-align: left; color: #64748b; font-size: 0.85rem;">
                    <th style="padding: 15px 20px;">Member Name</th>
                    <th style="padding: 15px 20px;">Email Address</th>
                    <th style="padding: 15px 20px;">Role</th>
                    <th style="padding: 15px 20px;">Date Joined</th>
                    <th style="padding: 15px 20px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody style="color: #475569;">
                <?php if (empty($members)): ?>
                    <tr><td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">No members assigned to this ministry yet.</td></tr>
                <?php else: ?>
                    <?php foreach($members as $m): ?>
                    <tr style="border-top: 1px solid #f1f5f9; hover: background: #fbfcfe;">
                        <td style="padding: 15px 20px; font-weight: 600; color: #1e293b;"><?= htmlspecialchars($m['full_name']) ?></td>
                        <td style="padding: 15px 20px;"><?= htmlspecialchars($m['email']) ?></td>
                        <td style="padding: 15px 20px;">
                            <span style="background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                                <?= $m['role'] ?>
                            </span>
                        </td>
                        <td style="padding: 15px 20px; font-size: 0.85rem; color: #94a3b8;"><?= date('M j, Y', strtotime($m['joined_at'])) ?></td>
                        <td style="padding: 15px 20px; text-align: right;">
                            <button style="color: #ef4444; border: none; background: none; cursor: pointer; font-size: 0.85rem; font-weight: 600;">Remove</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>