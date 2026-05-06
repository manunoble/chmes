<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="staff_directory.php" class="back-link">← Back to Directory</a>
        <h1 style="font-size: 2.5rem; color: #a855f7; font-weight: 800; margin: 15px 0 5px 0;">
            Register <span style="color: #fff;">New Staff</span>
        </h1>
        <p style="color: #94a3b8;">Create a new user account with specific administrative roles.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/save_staff.php" method="POST" class="modern-form">
            
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="modern-input" placeholder="e.g. John Doe" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="modern-input" placeholder="Login username" required>
                </div>
                <div class="form-group">
                    <label>Temporary Password</label>
                    <input type="password" name="password" class="modern-input" placeholder="Min. 8 characters" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>System Role</label>
                    <select name="role" class="modern-input" required>
                        <option value="Staff">Staff (Standard Access)</option>
                        <option value="Admin">Admin (Full Control)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" class="modern-input">
                        <option value="Pastoral">Pastoral</option>
                        <option value="Media/IT">Media & IT</option>
                        <option value="Finance">Finance</option>
                        <option value="Welfare">Welfare</option>
                        <option value="Ushers">Ushers/Protocol</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="modern-input" placeholder="e.g. 0712 345 678">
                </div>
                <div class="form-group">
                    <label>Joined Date</label>
                    <input type="date" name="joined_date" class="modern-input" value="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <button type="submit" class="submit-btn purple-glow-btn">
                ✅ Create Staff Account
            </button>
        </form>
    </div>
</div>

<style>
/* HR/Staff Purple Themed Styles */
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