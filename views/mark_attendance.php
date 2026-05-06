<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch members for the dropdown
$members = $db->query("SELECT id, full_name FROM members ORDER BY full_name ASC")->fetchAll();
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="attendance.php" class="back-link">
            <span style="font-size: 1.2rem;">←</span> Back to Dashboard
        </a>
        <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 15px 0 5px 0; letter-spacing: -1px;">
            Mark <span style="color: #fff;">Attendance</span>
        </h1>
        <p style="color: #94a3b8; font-size: 1.1rem;">Log participation for today's service.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/save_attendance.php" method="POST" class="modern-form">
            
            <div class="form-group">
                <label>Select Member</label>
                <div class="input-wrapper">
                    <select name="member_name" class="modern-input" required>
                        <option value="" disabled selected>-- Choose Member --</option>
                        <?php foreach($members as $m): ?>
                            <option value="<?= htmlspecialchars($m['full_name']) ?>"><?= htmlspecialchars($m['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="input-hint">Not in list? <a href="add_member.php" style="color: #ef4444; text-decoration: none; font-weight: 600;">Add them here</a></p>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Service Type</label>
                    <select name="service_type" class="modern-input">
                        <option value="Sunday 1st Service">Sunday 1st Service</option>
                        <option value="Sunday 2nd Service">Sunday 2nd Service</option>
                        <option value="Mid-week Service">Mid-week Service</option>
                        <option value="Youth Fellowship">Youth Fellowship</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Service Date</label>
                    <input type="date" name="service_date" value="<?= date('Y-m-d') ?>" class="modern-input">
                </div>
            </div>

            <button type="submit" class="submit-btn red-glow-btn">
                🚀 Confirm Attendance
            </button>
        </form>
    </div>
</div>

<style>
/* --- DARK MODE FORM STYLING --- */
.main-content { 
    background: #0f172a; 
    min-height: 100vh; 
    padding: 60px 40px; 
    color: #fff; 
}

.back-link {
    color: #64748b;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    transition: 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.back-link:hover { color: #ef4444; }

/* Form Container */
.glass-form-container {
    max-width: 650px