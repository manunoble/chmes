<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Get the Staff ID from the URL
if (!isset($_GET['id'])) {
    header("Location: staff_directory.php");
    exit();
}

$id = intval($_GET['id']);

// 2. Fetch current staff details
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$staff = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$staff) {
    die("Staff member not found.");
}
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="staff_directory.php" class="back-link">← Back to Directory</a>
        <h1 style="font-size: 2.5rem; color: #a855f7; font-weight: 800; margin: 15px 0 5px 0;">
            Edit <span style="color: #fff;">Staff Account</span>
        </h1>
        <p style="color: #94a3b8;">Modify roles, contact info, or account status for <strong><?= htmlspecialchars($staff['full_name']) ?></strong>.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/update_staff.php" method="POST" class="modern-form">
            <!-- Hidden ID field so the API knows which record to update -->
            <input type="hidden" name="id" value="<?= $staff['id'] ?>">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="modern-input" value="<?= htmlspecialchars($staff['full_name']) ?>" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Username (Read-Only)</label>
                    <input type="text" class="modern-input" value="<?= htmlspecialchars($staff['username']) ?>" style="opacity: 0.6; cursor: not-allowed;" readonly>
                </div>
                <div class="form-group">
                    <label>Account Status</label>
                    <select name="status" class="modern-input">
                        <option value="Active" <?= $staff['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $staff['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive (Disable Login)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>System Role</label>
                    <select name="role" class="modern-input">
                        <option value="Staff" <?= $staff['role'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
                        <option value="Admin" <?= $staff['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" class="modern-input">
                        <option value="Pastoral" <?= $staff['department'] == 'Pastoral' ? 'selected' : '' ?>>Pastoral</option>
                        <option value="Media/IT" <?= $staff['department'] == 'Media/IT' ? 'selected' : '' ?>>Media & IT</option>
                        <option value="Finance" <?= $staff['department'] == 'Finance' ? 'selected' : '' ?>>Finance</option>
                        <option value="Welfare" <?= $staff['department'] == 'Welfare' ? 'selected' : '' ?>>Welfare</option>
                        <option value="Ushers" <?= $staff['department'] == 'Ushers' ? 'selected' : '' ?>>Ushers/Protocol</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="modern-input" value="<?= htmlspecialchars($staff['phone'] ?? '') ?>">
            </div>

            <div style="background: rgba(239, 68, 68, 0.1); padding: 20px; border-radius: 16px; border: 1px solid rgba(239, 68, 68, 0.2); margin-top: 10px;">
                <h4 style="color: #ef4444; margin: 0 0 10px 0; font-size: 0.9rem;">Security Notice</h4>
                <p style="color: #94a3b8; font-size: 0.8rem; margin: 0;">Leave the password field blank unless you want to <strong>reset</strong> it.</p>
                <input type="password" name="new_password" class="modern-input" placeholder="New password (optional)" style="margin-top: 15px;">
            </div>

            <button type="submit" class="submit-btn purple-glow-btn">
                💾 Update Staff Records
            </button>
        </form>
    </div>
</div>

<style>
.main-content { background: #0f172a; min-height: 100vh; padding: 60px 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
.back-link:hover { color: #a855f7; }

.glass-form-container {
    max-width: 800px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    border-top: 4px solid #a855f7;
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
.modern-input:focus { border-color: #a855f7; }

.submit-btn { 
    background: #a855f7; 
    color: white; 
    border: none; 
    padding: 18px; 
    border-radius: 16px; 
    font-weight: 800; 
    cursor: pointer; 
    transition: 0.3s;
    margin-top: 10px;
}
.submit-btn:hover { background: #fff; color: #a855f7; transform: translateY(-3px); }
.purple-glow-btn { box-shadow: 0 10px 20px -5px rgba(168, 85, 247, 0.4); }
</style>

<?php include '../includes/footer.php'; ?>