<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="discipleship.php" class="back-link">
            <span>←</span> Back to Dashboard
        </a>
        <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 15px 0 5px 0; letter-spacing: -1px;">
            New <span style="color: #fff;">Program</span>
        </h1>
        <p style="color: #94a3b8; font-size: 1.1rem;">Initialize a new spiritual growth track for the congregation.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/save_program.php" method="POST" class="modern-form">
            
            <div class="form-group">
                <label>Program Name</label>
                <input type="text" name="name" placeholder="e.g. Foundation Class" class="modern-input" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Mentor / Teacher</label>
                    <input type="text" name="mentor" class="modern-input" placeholder="Name of leader">
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <input type="text" name="duration" class="modern-input" placeholder="e.g. 6 Months">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Theme Color</label>
                    <div class="color-picker-wrapper">
                        <input type="color" name="theme_color" value="#ef4444" class="modern-input color-input">
                        <span style="font-size: 0.7rem; color: #64748b; margin-top: 5px; display: block;">This color will be used for progress bars & glow effects.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Initial Participants</label>
                    <input type="number" name="participants" class="modern-input" value="0" min="0">
                </div>
            </div>

            <button type="submit" class="submit-btn red-glow-btn">
                ✨ Launch Program
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
    max-width: 800px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    border-top: 4px solid #ef4444;
}

.modern-form { display: flex; flex-direction: column; gap: 25px; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

.form-group label {
    display: block;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #94a3b8;
    margin-bottom: 10px;
    font-weight: 700;
}

/* Inputs */
.modern-input {
    width: 100%;
    background: rgba(15, 23, 42, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 14px 18px;
    border-radius: 14px;
    color: #fff;
    font-size: 1rem;
    outline: none;
    transition: 0.3s ease;
}

.modern-input:focus {
    border-color: #ef4444;
    background: rgba(15, 23, 42, 0.9);
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
}

/* Specialized Color Input */
.color-input {
    height: 60px;
    cursor: pointer;
    padding: 8px !important;
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
    margin-top: 15px;
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

/* Webkit specific for color input circle */
::-webkit-color-swatch-wrapper { padding: 0; }
::-webkit-color-swatch { border: none; border-radius: 10px; }
</style>

<?php include '../includes/footer.php'; ?>