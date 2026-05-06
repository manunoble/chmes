<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="mark_attendance.php" class="back-link">
            <span style="font-size: 1.2rem;">←</span> Back to Marking Attendance
        </a>
        <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 15px 0 5px 0; letter-spacing: -1px;">
            Register <span style="color: #fff;">New Member</span>
        </h1>
        <p style="color: #94a3b8; font-size: 1.1rem;">Expand the church community records.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/save_member.php" method="POST" class="modern-form">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter first and last name" class="modern-input" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="email@example.com" class="modern-input">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="e.g. 0712345678" class="modern-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Membership Status</label>
                    <select name="status" class="modern-input">
                        <option value="Active">Full Member</option>
                        <option value="Visitor">Visitor / Newcomer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date Joined</label>
                    <input type="date" name="membership_date" value="<?= date('Y-m-d') ?>" class="modern-input">
                </div>
            </div>

            <button type="submit" class="submit-btn red-glow-btn">
                ✅ Confirm Registration
            </button>
        </form>
    </div>
</div>

<style>
/* --- UNIFIED DARK THEME STYLING --- */
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
    max-width: 750px;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    border-left: 6px solid #ef4444; /* Brand accent */
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

/* Modern Inputs */
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
    border-