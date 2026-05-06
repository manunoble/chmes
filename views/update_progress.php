<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// Fetch programs to populate the selector
$programs = $db->query("SELECT id, name, current_progress, theme_color FROM discipleship_programs WHERE status != 'Completed'")->fetchAll();
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="discipleship.php" class="back-link">
            <span>←</span> Back to Dashboard
        </a>
        <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 15px 0 5px 0; letter-spacing: -1px;">
            Update <span style="color: #fff;">Progress</span>
        </h1>
        <p style="color: #94a3b8; font-size: 1.1rem;">Record the spiritual journey milestones of your groups.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/update_program_logic.php" method="POST" class="modern-form">
            
            <div class="form-group">
                <label>Select Program</label>
                <div class="input-wrapper">
                    <select name="program_id" id="program_select" class="modern-input" required onchange="updateSlider(this)">
                        <option value="" disabled selected>-- Choose a Program --</option>
                        <?php foreach($programs as $p): ?>
                            <option value="<?= $p['id'] ?>" data-progress="<?= $p['current_progress'] ?>">
                                <?= htmlspecialchars($p['name']) ?> (Currently <?= $p['current_progress'] ?>%)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-top: 10px;">
                <label>Adjust Progress Percentage</label>
                <div class="range-container">
                    <input type="range" name="new_progress" id="progress_slider" min="0" max="100" value="0" class="modern-range">
                    <div class="percentage-display">
                        <span id="percentage_label">0%</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Status Update</label>
                <select name="status" class="modern-input">
                    <option value="In Progress">In Progress</option>
                    <option value="Active">Active</option>
                    <option value="Completed">Completed / Graduation</option>
                </select>
            </div>

            <button type="submit" class="submit-btn red-glow-btn">
                💾 Save Progress Update
            </button>
        </form>
    </div>
</div>

<script>
const slider = document.getElementById('progress_slider');
const label = document.getElementById('percentage_label');

slider.oninput = function() {
    label.innerHTML = this.value + "%";
}

function updateSlider(select) {
    const selectedOption = select.options[select.selectedIndex];
    const currentProgress = selectedOption.getAttribute('data-progress');
    slider.value = currentProgress;
    label.innerHTML = currentProgress + "%";
}
</script>

<style>
/* --- DARK THEME STYLING --- */
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
}

.modern-input:focus {
    border-color: #ef4444;
    background: rgba(15, 23, 42, 0.9);
}

/* --- RANGE SLIDER STYLING --- */
.range-container {
    display: flex;
    align-items: center;
    gap: 25px;
    background: rgba(0,0,0,0.2);
    padding: 20px;
    border-radius: 18px;
    border: 1px solid rgba(255,255,255,0.05);
}

.modern-range {
    -webkit-appearance: none;
    flex: 1;
    height: 10px;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
    outline: none;
}

/* Slider Thumb (The circle) */
.modern-range::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 24px;
    height: 24px;
    background: #ef4444;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 0 15px rgba(239, 68, 68, 0.6);
    border: 3px solid #fff;
    transition: 0.2s;
}

.modern-range::-webkit-slider-thumb:hover {
    transform: scale(1.2);
}

.percentage-display {
    min-width: 70px;
    text-align: center;
}

#percentage_label {
    font-size: 1.8rem;
    font-weight: 900;
    color: #ef4444;
    text-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
}

/* --- BUTTON STYLING --- */
.submit-btn {
    border: none;
    padding: 20px;
    border-radius: 18px;
    color: white;
    font-weight: 800;
    font-size: 1.1rem;
    cursor: pointer;
    transition: 0.4s;
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
}

option { background: #1e293b; color: #fff; }
</style>

<?php include '../includes/footer.php'; ?>