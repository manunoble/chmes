<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="assets.php" class="back-link">← Back to Inventory</a>
        <h1 style="font-size: 2.5rem; color: #f59e0b; font-weight: 800; margin: 15px 0 5px 0;">
            Register <span style="color: #fff;">New Asset</span>
        </h1>
        <p style="color: #94a3b8;">Log new hardware, electronics, or church property into the system.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/save_asset.php" method="POST" class="modern-form">
            
            <div class="form-group">
                <label>Asset Name</label>
                <input type="text" name="asset_name" class="modern-input" placeholder="e.g. Vitron 55' Smart TV or vMix PC" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="modern-input" required>
                        <option value="Electronics/IT">Electronics/IT</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Musical Instruments">Musical Instruments</option>
                        <option value="Kitchen/Appliances">Kitchen/Appliances</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Serial Number / Tag</label>
                    <input type="text" name="serial_number" class="modern-input" placeholder="e.g. VTR-990234X">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Purchase Date</label>
                    <input type="date" name="purchase_date" class="modern-input" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label>Purchase Cost (KSh)</label>
                    <input type="number" step="0.01" name="purchase_cost" class="modern-input" placeholder="0.00">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Assigned To / Location</label>
                    <input type="text" name="assigned_to" class="modern-input" placeholder="e.g. Media Booth / Main Sanctuary">
                </div>
                <div class="form-group">
                    <label>Initial Status</label>
                    <select name="status" class="modern-input">
                        <option value="Functional">Functional</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Damaged">Damaged</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Additional Notes</label>
                <textarea name="notes" class="modern-input" rows="3" placeholder="Warranty info, specific model details, or accessories included..."></textarea>
            </div>

            <button type="submit" class="submit-btn orange-glow-btn">
                💾 Save to Inventory
            </button>
        </form>
    </div>
</div>

<style>
/* Inventory Orange Themed Styles */
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
    transition: 0.3s;
}
.modern-input:focus { border-color: #f59e0b; background: rgba(15, 23, 42, 0.8); }

.submit-btn { 
    background: #f59e0b; 
    color: white; 
    border: none; 
    padding: 18px; 
    border-radius: 16px; 
    font-weight: 800; 
    cursor: pointer; 
    transition: 0.3s;
    margin-top: 10px;
}
.submit-btn:hover { background: #fff; color: #f59e0b; transform: translateY(-3px); }
.orange-glow-btn { box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.4); }
</style>

<?php include '../includes/footer.php'; ?>