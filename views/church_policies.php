<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch all policies
$policies = $db->query("SELECT * FROM church_policies ORDER BY category ASC, policy_title ASC")->fetchAll();
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <a href="administration.php" class="back-link">← Back to Admin</a>
            <h1 style="font-size: 2.5rem; color: #10b981; font-weight: 800; margin: 10px 0 0 0;">
                Policy <span style="color: #fff;">Hub</span>
            </h1>
            <p style="color: #94a3b8;">Official governance and compliance documents.</p>
        </div>
        <a href="upload_policy.php" class="action-btn-green">+ Upload Policy</a>
    </header>

    <div class="policy-grid">
        <?php foreach($policies as $p): ?>
        <div class="policy-card">
            <div class="policy-icon">
                <i data-lucide="file-text"></i>
            </div>
            <div class="policy-info">
                <span class="category-tag"><?= $p['category'] ?></span>
                <h3><?= htmlspecialchars($p['policy_title']) ?></h3>
                <div class="meta">
                    <span>v<?= $p['version_number'] ?></span> • 
                    <span>Updated: <?= date('M d, Y', strtotime($p['last_updated'])) ?></span>
                </div>
            </div>
            <div class="policy-actions">
                <a href="../uploads/policies/<?= $p['file_path'] ?>" target="_blank" class="view-btn">View PDF</a>
                <a href="../api/delete_policy.php?id=<?= $p['id'] ?>" class="delete-icon" onclick="return confirm('Delete policy?')">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(empty($policies)): ?>
            <div class="empty-state">
                <i data-lucide="shield-alert" style="width: 48px; height: 48px; color: #1e293b; margin-bottom: 15px;"></i>
                <p>No official policies uploaded yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; }
.action-btn-green { background: #10b981; color: #fff; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-weight: 700; transition: 0.3s; }
.action-btn-green:hover { background: #fff; color: #10b981; }

.policy-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }

.policy-card {
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 20px;
    padding: 25px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    transition: 0.3s;
}
.policy-card:hover { border-color: #10b981; transform: translateY(-5px); }

.policy-icon { color: #10b981; }
.category-tag { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 1px; color: #10b981; font-weight: 800; }
.policy-info h3 { margin: 5px 0; font-size: 1.1rem; color: #fff; }
.policy-info .meta { font-size: 0.8rem; color: #64748b; }

.policy-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 10px; }
.view-btn { background: rgba(255,255,255,0.05); color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; }
.view-btn:hover { background: #fff; color: #0f172a; }
.delete-icon { color: #64748b; }
.delete-icon:hover { color: #ef4444; }

.empty-state { grid-column: 1 / -1; text-align: center; padding: 100px; background: rgba(30, 41, 59, 0.2); border-radius: 32px; border: 2px dashed rgba(255,255,255,0.05); }
</style>

<?php include '../includes/footer.php'; ?>