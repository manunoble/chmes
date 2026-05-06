<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="church_policies.php" class="back-link">← Back to Policy Hub</a>
        <h1 style="font-size: 2.5rem; color: #10b981; font-weight: 800; margin: 15px 0 5px 0;">
            Upload <span style="color: #fff;">Policy</span>
        </h1>
        <p style="color: #94a3b8;">Publish official governance or operational documents to the system.</p>
    </header>

    <div class="glass-form-container">
        <!-- CRITICAL: enctype="multipart/form-data" is required for file uploads -->
        <form action="../api/save_policy.php" method="POST" enctype="multipart/form-data" class="modern-form">
            
            <div class="form-group">
                <label>Policy Title</label>
                <input type="text" name="policy_title" class="modern-input" placeholder="e.g. Financial Management Bylaws" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="modern-input" required>
                        <option value="Governance">Governance</option>
                        <option value="Financial">Financial</option>
                        <option value="HR">HR & Staffing</option>
                        <option value="Safeguarding">Safeguarding / Security</option>
                        <option value="Operations">Operations</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Version Number</label>
                    <input type="text" name="version_number" class="modern-input" placeholder="e.g. 1.2" value="1.0" required>
                </div>
            </div>

            <div class="form-group">
                <label>Select PDF Document</label>
                <div class="file-upload-wrapper">
                    <input type="file" name="policy_file" id="policy_file" accept=".pdf" class="modern-input" style="padding: 10px;" required>
                </div>
                <small style="color: #64748b; margin-top: 8px; display: block;">Only PDF documents are accepted for official policies.</small>
            </div>

            <button type="submit" class="submit-btn green-glow-btn">
                📤 Publish Policy Document
            </button>
        </form>
    </div>
</div>

<style>
/* Policy Hub Green Themed Styles */
.main-content { background: #0f172a; min-height: 100vh; padding: 60px 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
.back-link:hover { color: #10b981; }

.glass-form-container {
    max-width: 800px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    border-top: 4px solid #10b981;
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
.modern-input:focus { border-color: #10b981; background: rgba(15, 23, 42, 0.8); }

.submit-btn { 
    background: #10b981; 
    color: white; 
    border: none; 
    padding: 18px; 
    border-radius: 16px; 
    font-weight: 800; 
    cursor: pointer; 
    transition: 0.3s;
    margin-top: 10px;
}
.submit-btn:hover { background: #fff; color: #10b981; transform: translateY(-3px); }
.green-glow-btn { box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4); }
</style>

<?php include '../includes/footer.php'; ?>