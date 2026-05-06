<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch all members to populate the dropdown
$members = $db->query("SELECT id, full_name FROM members ORDER BY full_name ASC")->fetchAll();
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="pastoral_records.php" class="back-link">← Back to Records</a>
        <h1 style="font-size: 2.5rem; color: #3b82f6; font-weight: 800; margin: 15px 0 5px 0;">
            Log Pastoral <span style="color: #fff;">Event</span>
        </h1>
        <p style="color: #94a3b8;">Record significant life milestones for church members.</p>
    </header>

    <div class="glass-form-container">
        <!-- CRITICAL: enctype is needed for certificate uploads -->
        <form action="../api/save_pastoral_record.php" method="POST" enctype="multipart/form-data" class="modern-form">
            
            <div class="form-group">
                <label>Select Member</label>
                <select name="member_id" class="modern-input" required>
                    <option value="" disabled selected>-- Choose Member --</option>
                    <?php foreach($members as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['full_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Event Type</label>
                    <select name="event_type" class="modern-input" required>
                        <option value="Baptism">Baptism</option>
                        <option value="Marriage">Marriage</option>
                        <option value="Dedication">Child Dedication</option>
                        <option value="Funeral">Funeral / Memorial</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Event Date</label>
                    <input type="date" name="event_date" class="modern-input" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Officiant (Pastor/Minister)</label>
                    <input type="text" name="officiant_name" class="modern-input" placeholder="Name of presiding minister" required>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" name="location" class="modern-input" placeholder="Main Sanctuary / Specific Venue">
                </div>
            </div>

            <div class="form-group">
                <label>Witnesses (Optional)</label>
                <textarea name="witness_names" class="modern-input" rows="2" placeholder="List witnesses or godparents..."></textarea>
            </div>

            <div class="form-group">
                <label>Upload Certificate (PDF or Image)</label>
                <input type="file" name="certificate_file" class="modern-input" style="padding: 10px;">
                <small style="color: #64748b; margin-top: 5px; display: block;">Upload a digital scan of the official document.</small>
            </div>

            <button type="submit" class="submit-btn blue-glow-btn">
                💾 Save Pastoral Record
            </button>
        </form>
    </div>
</div>

<style>
/* Reusing your high-end form styles */
.main-content { background: #0f172a; min-height: 100vh; padding: 60px 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
.back-link:hover { color: #3b82f6; }

.glass-form-container {
    max-width: 800px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    border-top: 4px solid #3b82f6;
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
.modern-input:focus { border-color: #3b82f6; }

.submit-btn { 
    background: #3b82f6; 
    color: white; 
    border: none; 
    padding: 18px; 
    border-radius: 16px; 
    font-weight: 800; 
    cursor: pointer; 
    transition: 0.3s;
    margin-top: 10px;
}
.submit-btn:hover { background: #fff; color: #3b82f6; transform: translateY(-3px); }
.blue-glow-btn { box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4); }
</style>

<?php include '../includes/footer.php'; ?>