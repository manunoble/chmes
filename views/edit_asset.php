<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Get the Asset ID from the URL
if (!isset($_GET['id'])) {
    header("Location: assets.php");
    exit();
}

$id = intval($_GET['id']);

// 2. Fetch current asset details
$stmt = $db->prepare("SELECT * FROM church_assets WHERE id = ?");
$stmt->execute([$id]);
$asset = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$asset) {
    die("Asset record not found.");
}
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="assets.php" class="back-link">← Back to Inventory</a>
        <h1 style="font-size: 2.5rem; color: #f59e0b; font-weight: 800; margin: 15px 0 5px 0;">
            Update <span style="color: #fff;">Asset Info</span>
        </h1>
        <p style="color: #94a3b8;">Modify details or update the status for: <strong><?= htmlspecialchars($asset['asset_name']) ?></strong></p>
    </header>

    <div class="glass-form-container">
        <form action="../api/update_asset.php" method="POST" class="modern-form">
            <!-- Hidden ID field -->
            <input type="hidden" name="id" value="<?= $asset['id'] ?>">
            
            <div class="form-group">
                <label>Asset Name</label>
                <input type="text" name="asset_name" class="modern-input" value="<?= htmlspecialchars($asset['asset_name']) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="modern-input" required>
                        <option value="Electronics/IT" <?= $asset['category'] == 'Electronics/IT' ? 'selected' : '' ?>>Electronics/IT</option>
                        <option value="Furniture" <?= $asset['category'] == 'Furniture' ? 'selected' : '' ?>>Furniture</option>
                        <option value="Musical Instruments" <?= $asset['category'] == 'Musical Instruments' ? 'selected' : '' ?>>Musical Instruments</option>
                        <option value="Kitchen/Appliances" <?= $asset['category'] == 'Kitchen/Appliances' ? 'selected' : '' ?>>Kitchen/Appliances</option>
                        <option value="Other" <?= $asset['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="modern-input">
                        <option value="Functional" <?= $asset['status'] == 'Functional' ? 'selected' : '' ?>>Functional</option>
                        <option value="Maintenance" <?= $asset['status'] == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
                        <option value="Damaged" <?= $asset['status'] == 'Damaged' ? 'selected' : '' ?>>Damaged</option>
                        <option value="Retired" <?= $asset['status'] == 'Retired' ? 'selected' : '' ?>>Retired / Disposed</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Serial Number</label>
                    <input type="text" name="serial_number" class="modern-input" value="<?= htmlspecialchars($asset['serial_number'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Assigned To</label>
                    <input type="text" name="assigned_to" class="modern-input" value="<?= htmlspecialchars($asset['assigned_to'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Maintenance/Audit Notes</label>
                <textarea name="notes" class="modern-input" rows="3"><?= htmlspecialchars($asset['notes'] ?? '') ?></textarea>
                <small style="color: #64748b; margin-top: 5px; display: block;">Record any repairs, software updates, or physical condition changes.</small>
            </div>

            <div style="display: flex; gap: 15px; align-items: center; margin-top: 10px;">
                <button type="submit" class="submit-btn orange-glow-btn" style="flex: 2;">
                    💾 Update Asset Records
                </button>
                <div style="flex: 1; text-align: center; background: rgba(255,255,255,0.03); padding: 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                    <label style="font-size: 0.6rem; text-transform: uppercase; color: #64748b; display: block;">Last Audit</label>
                    <span style="font-size: 0.85rem; color: #fff; font-weight: 700;"><?= $asset['last_audit_date'] ?? 'Never' ?></span>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
/* Reusing the established Administration Styles */
.main-content { background: #0f172a; min-height: 100vh; padding: 60px 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
.back-link:hover { color: #f59e0b; }

.glass-form-container {
    max-width: 800px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    border-top: 4px solid #f59e0b;
}

.modern-form { display: flex; flex-direction: column; gap: 25px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group label { display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 10px; font-weight: 700; text-transform: uppercase; }

.modern-input {
    width: 100%;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 14px 18px;
    border-radius: 14px;
    color: #fff;
    outline: none;
}
.modern-input:focus { border-color: #f59e0b; }

.submit-btn { 
    background: #f59e0b; 
    color: white; 
    border: none; 
    padding: 18px; 
    border-radius: 16px; 
    font-weight: 800; 
    cursor: pointer; 
    transition: 0.3s;
}
.submit-btn:hover { background: #fff; color: #f59e0b; transform: translateY(-3px); }
.orange-glow-btn { box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4); }
</style>

<?php include '../includes/footer.php'; ?>