<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="content_hub.php" class="back-link">← Back to Hub</a>
        <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 15px 0 5px 0;">
            Upload <span style="color: #fff;">Media</span>
        </h1>
        <p style="color: #94a3b8;">Add new sermons, music, or study materials to the digital library.</p>
    </header>

    <div class="glass-form-container">
        <form action="../api/save_content.php" method="POST" enctype="multipart/form-data" class="modern-form">
            
            <div class="form-group">
                <label>Content Title</label>
                <input type="text" name="title" placeholder="e.g. Sunday Morning Service - Hope" class="modern-input" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Speaker / Artist</label>
                    <input type="text" name="speaker" class="modern-input" placeholder="Name of speaker or choir">
                </div>
                <div class="form-group">
                    <label>Content Type</label>
                    <select name="content_type" class="modern-input" required>
                        <option value="sermon">Sermon (Video/Outline)</option>
                        <option value="audio">Music / Audio</option>
                        <option value="document">Study Resource (PDF/Doc)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Publish Date</label>
                    <input type="date" name="published_date" value="<?= date('Y-m-d') ?>" class="modern-input">
                </div>
                <div class="form-group">
                    <label>Upload File</label>
                    <input type="file" name="media_file" class="modern-input" style="padding: 10px;" required>
                    <small style="color: #64748b; margin-top: 5px; display: block;">Max size: 50MB (Adjust in php.ini)</small>
                </div>
            </div>

            <button type="submit" class="submit-btn red-glow-btn">
                🚀 Upload to Hub
            </button>
        </form>
    </div>
</div>

<style>
/* Use the same Dark Form CSS from your discipleship/attendance modules */
.main-content { background: #0f172a; min-height: 100vh; padding: 60px 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
.back-link:hover { color: #ef4444; }

.glass-form-container {
    max-width: 800px;
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    padding: 50px;
    border-radius: 32px;
    backdrop-filter: blur(15px);
    border-top: 4px solid #ef4444;
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
}

.submit-btn { background: #ef4444; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 800; cursor: pointer; transition: 0.3s; }
.submit-btn:hover { background: #fff; color: #ef4444; transform: translateY(-3px); }
</style>

<?php include '../includes/footer.php'; ?>