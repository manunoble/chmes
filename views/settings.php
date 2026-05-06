<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch Branding and User Data
$brandStmt = $db->query("SELECT * FROM system_settings WHERE id = 1");
$branding = $brandStmt->fetch();

$activeTab = $_GET['tab'] ?? 'church';

$tabs = [
    ['id' => 'church', 'label' => 'Church Info', 'icon' => '⛪'],
    ['id' => 'profile', 'label' => 'My Account', 'icon' => '👤'],
    ['id' => 'roles', 'label' => 'Roles & Permissions', 'icon' => '🛡️'],
    ['id' => 'appearance', 'label' => 'Appearance', 'icon' => '🎨'],
    ['id' => 'backup', 'label' => 'System Backup', 'icon' => '💾'],
];
?>

<div class="main-content">
    <header style="margin-bottom: 40px;">
        <h1 style="color: #ef4444; font-size: 2.5rem; font-weight: 800; margin: 0;">
            <?= htmlspecialchars($branding['church_name'] ?? 'CHIMES Church') ?>
        </h1>
        <p style="color: #64748b; font-weight: 500;">System Settings & Identity Management</p>
    </header>

    <div style="display: grid; grid-template-columns: 280px 1fr; gap: 30px;">
        <aside>
            <div class="glass-panel" style="padding: 15px; position: sticky; top: 20px;">
                <?php foreach($tabs as $tab): 
                    $isActive = ($activeTab === $tab['id']);
                ?>
                    <a href="?tab=<?= $tab['id'] ?>" class="nav-item <?= $isActive ? 'active' : '' ?>">
                        <span style="font-size: 1.2rem;"><?= $tab['icon'] ?></span>
                        <span><?= $tab['label'] ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </aside>

        <main class="glass-panel" style="padding: 40px; min-height: 600px;">
            
            <?php if($activeTab === 'church'): ?>
                <h2 class="section-title">Church Branding</h2>
                <form action="../api/update_branding.php" method="POST" enctype="multipart/form-data" class="settings-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Church Name</label>
                            <input type="text" name="church_name" value="<?= htmlspecialchars($branding['church_name'] ?? '') ?>" class="settings-input">
                        </div>
                        <div class="form-group">
                            <label>Change Logo</label>
                            <input type="file" name="church_logo_file" accept="image/*" class="settings-input" style="padding: 8px;">
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding: 20px; background: #fef2f2; border-radius: 16px; border: 1px solid #fee2e2; display: flex; align-items: center; gap: 20px;">
                        <div style="text-align: center;">
                            <p style="font-size: 0.75rem; color: #ef4444; font-weight: 700; margin-bottom: 8px; text-transform: uppercase;">Current Logo</p>
                            <img src="<?= $branding['church_logo'] ?>" style="height: 80px; width: 80px; object-fit: cover; border-radius: 12px; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        </div>
                        <div>
                            <p style="color: #7f1d1d; font-weight: 600; margin-bottom: 4px;">Logo Specifications</p>
                            <ul style="font-size: 0.85rem; color: #991b1b; padding-left: 20px;">
                                <li>Recommended: PNG or JPG</li>
                                <li>Square aspect ratio works best</li>
                                <li>Max file size: 2MB</li>
                            </ul>
                        </div>
                    </div>

                    <button type="submit" class="btn-gradient" style="margin-top: 25px; background: linear-gradient(to right, #ef4444, #b91c1c);">
                        Save Identity Changes
                    </button>
                </form>

            <?php elseif($activeTab === 'profile'): 
                $uStmt = $db->prepare("SELECT full_name, email, role FROM users WHERE id = ?");
                $uStmt->execute([$_SESSION['user_id']]);
                $user = $uStmt->fetch();
            ?>
                <h2 class="section-title">My Account</h2>
                <form action="../api/update_profile.php" method="POST" class="settings-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="settings-input">
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="settings-input">
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                        <h3 style="font-size: 1.1rem; color: #1e293b; margin-bottom: 15px;">Change Password</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="settings-input">
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="settings-input">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-gradient" style="background: #3b82f6; margin-top: 20px;">Update Profile</button>
                </form>

            <?php elseif($activeTab === 'roles'): 
                $allUsers = $db->query("SELECT id, full_name, email, role FROM users ORDER BY role ASC")->fetchAll();
            ?>
                <h2 class="section-title">Roles & Permissions</h2>
                <div style="overflow-x: auto; background: #fff; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #f8fafc; color: #94a3b8; font-size: 0.75rem; text-transform: uppercase;">
                                <th style="padding: 15px 20px;">User Details</th>
                                <th style="padding: 15px 20px;">Status</th>
                                <th style="padding: 15px 20px; text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($allUsers as $u): ?>
                            <tr style="border-top: 1px solid #f1f5f9;">
                                <td style="padding: 15px 20px;">
                                    <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($u['full_name']) ?></div>
                                    <div style="font-size: 0.8rem; color: #64748b;"><?= htmlspecialchars($u['email']) ?></div>
                                </td>
                                <td style="padding: 15px 20px;">
                                    <span style="padding: 5px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; 
                                        <?= $u['role'] === 'Admin' ? 'background: #fee2e2; color: #ef4444;' : 'background: #f1f5f9; color: #64748b;' ?>">
                                        <?= strtoupper($u['role']) ?>
                                    </span>
                                </td>
                                <td style="padding: 15px 20px; text-align: right;">
                                    <form action="../api/toggle_role.php" method="POST" style="margin: 0;">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <input type="hidden" name="current_role" value="<?= $u['role'] ?>">
                                        <button type="submit" style="background: none; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 8px; color: #3b82f6; cursor: pointer; font-size: 0.75rem; font-weight: 600;">
                                            Make <?= $u['role'] === 'Admin' ? 'Staff' : 'Admin' ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif($activeTab === 'appearance'): ?>
                <h2 class="section-title">System Theme</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="theme-option active" style="border-color: #ef4444; background: #fef2f2;">
                        <div style="width: 50px; height: 50px; background: #0f172a; border-radius: 50%; margin: 0 auto 15px; border: 3px solid white;"></div>
                        <h4 style="margin: 0;">Midnight (Dark)</h4>
                        <p style="font-size: 0.75rem; color: #64748b;">Current Default</p>
                    </div>
                    <div class="theme-option" style="opacity: 0.6; cursor: not-allowed;">
                        <div style="width: 50px; height: 50px; background: #ffffff; border-radius: 50%; margin: 0 auto 15px; border: 3px solid #e2e8f0;"></div>
                        <h4 style="margin: 0;">Daylight (Light)</h4>
                        <p style="font-size: 0.75rem; color: #64748b;">Coming Soon</p>
                    </div>
                </div>

            <?php elseif($activeTab === 'backup'): ?>
    <h2 class="section-title">Data Maintenance</h2>
    <div class="backup-card">
        <div style="font-size: 4rem; margin-bottom: 20px;">💾</div>
        <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 10px;">Full Database Export</h3>
        <p style="color: #64748b; max-width: 450px; margin: 0 auto 30px; line-height: 1.6;">
            Download a complete SQL backup of all church records, including membership, financial history, and system settings to keep your data safe.
        </p>
        <button onclick="window.location.href='../api/export_backup.php'" class="btn-gradient" style="background: #1e293b; padding: 16px 40px;">
            Generate Backup (.SQL)
        </button>
    </div>
<?php endif; ?>
<style>
/* 1. Layout & Glassmorphism Panels */
.glass-panel { 
    background: #ffffff; 
    border: 1px solid #e2e8f0; 
    border-radius: 24px; 
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
}

.main-content {
    background-color: #f8fafc;
    min-height: 100vh;
}

/* 2. Navigation Sidebar Items */
.nav-item { 
    display: flex; 
    align-items: center; 
    gap: 15px; 
    padding: 14px 20px; 
    border-radius: 12px; 
    text-decoration: none; 
    color: #64748b; 
    font-weight: 600; 
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    margin-bottom: 8px; 
}

.nav-item:hover {
    background: #f1f5f9;
    color: #ef4444;
    transform: translateX(5px);
}

.nav-item.active { 
    background: #ef4444; 
    color: white; 
    box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.3); 
}

/* 3. Section Titles & Forms */
.section-title { 
    font-size: 1.8rem; 
    font-weight: 800; 
    color: #1e293b; 
    margin-bottom: 30px; 
    letter-spacing: -0.025em;
}

.settings-form { display: flex; flex-direction: column; gap: 20px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-group label { 
    display: block; 
    font-size: 0.75rem; 
    font-weight: 700; 
    color: #94a3b8; 
    text-transform: uppercase; 
    margin-bottom: 8px; 
    letter-spacing: 0.05em;
}

/* 4. Inputs & Modern Buttons */
.settings-input { 
    width: 100%; 
    padding: 12px 16px; 
    background: #f8fafc; 
    border: 2px solid #e2e8f0; 
    border-radius: 12px; 
    outline: none; 
    transition: all 0.2s ease;
    font-size: 0.95rem;
}

.settings-input:focus {
    border-color: #ef4444;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

.btn-gradient { 
    color: white; 
    border: none; 
    padding: 14px 25px; 
    border-radius: 12px; 
    font-weight: 700; 
    cursor: pointer; 
    transition: all 0.3s ease; 
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
    box-shadow: 0 8px 12px rgba(0,0,0,0.15);
}

/* 5. Theme Options & Table Styling */
.theme-option { 
    border: 2px solid #e2e8f0; 
    padding: 24px; 
    border-radius: 20px; 
    text-align: center; 
    transition: all 0.3s ease;
    cursor: pointer;
}

.theme-option:hover {
    border-color: #ef4444;
    transform: scale(1.02);
}

.theme-option.active {
    border-color: #ef4444;
    background: #fffafa;
    box-shadow: 0 10px 20px rgba(239, 68, 68, 0.05);
}

/* 6. Roles Table Custom CSS */
tr:hover td {
    background-color: #fcfcfc;
}

.backup-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
    padding: 40px;
    border-radius: 24px;
    text-align: center;
}
</style>
<?php include '../includes/footer.php'; ?>
