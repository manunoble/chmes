<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Check if a specific program was clicked from the dashboard
$preselected_program = $_GET['program_id'] ?? null;

// 2. Fetch Active Programs and Members
$programs = $db->query("SELECT id, name FROM discipleship_programs WHERE status != 'Completed'")->fetchAll();
$members = $db->query("SELECT id, full_name FROM members ORDER BY full_name ASC")->fetchAll();
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="discipleship.php" class="back-link">
            <span>←</span> Back to Dashboard
        </a>
        <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 15px 0 5px 0; letter-spacing: -1px;">
            Program <span style="color: #fff;">Enrollment</span>
        </h1>
        <p style="color: #94a3b8; font-size: 1.1rem;">Assign members to specific discipleship tracks.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/save_enrollment.php" method="POST" class="modern-form">
            
            <div class="form-group">
                <label>Target Program</label>
                <div class="input-wrapper">
                    <select name="program_id" class="modern-input" required>
                        <option value="" disabled <?= !$preselected_program ? 'selected' : '' ?>>-- Select a Program --</option>
                        <?php foreach($programs as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($preselected_program == $p['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Select Member to Enroll</label>
                <div class="input-wrapper">
                    <select name="member_id" class="modern-input" required>
                        <option value="" disabled selected>-- Select a Member --</option>
                        <?php foreach($members as $m): ?>
                            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <button type="submit" class="submit-btn red-glow-btn">
                ➕ Add Member to Program
            </button>
        </form>
    </div>
</div>

<style>
/* --- DARK THEME FORM CSS --- */
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
    max-width: 650px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    border-top: 4px solid #ef4444;
}

.modern-form { display: flex; flex-direction: column; gap: 30px; }

.form-group label {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-bottom: 12px;
    font-weight: 700;
}

/* Modern Inputs */
.modern-input {
    width: 100%;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 16px 20px;
    border-radius: 14px;
    color: #fff;
    font-size: 1rem;
    outline: none;
    transition: 0.3s ease;
    cursor: pointer;
}

.modern-input:focus {
    border-color: #ef4444;
    background: rgba(15, 23, 42, 0.9);
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

/* Style for the options inside dropdown */
option {
    background: #1e293b;
    color: #fff;
}

/* Submit Button */
.submit-btn {
    border: none;
    padding: 20px;
    border-radius: 18px;
    color: white;
    font-weight: 800;
    font-size: 1.1rem;
    cursor: pointer;
    transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 10px;
}

.red-glow-btn {
    background: #ef4444;
    box-shadow: 0 10px 20px -5px rgba(239, 68, 68, 0.4);
}

.red-glow-btn:hover {
    transform: translateY(-3px);
    background: #fff;
    color: #ef4444;
    box-shadow: 0 15px 25px -5px rgba(239, 68, 68, 0.6);
}
</style>

<?php include '../includes/footer.php'; ?>