<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Fetch Stats from content_hub table using $db
try {
    $stats_query = $db->query("SELECT 
        COUNT(CASE WHEN content_type = 'sermon' THEN 1 END) as total_sermons,
        COUNT(CASE WHEN content_type = 'audio' THEN 1 END) as total_music,
        COUNT(CASE WHEN content_type = 'document' THEN 1 END) as total_resources,
        SUM(views) as total_views 
        FROM content_hub");
    $stats = $stats_query->fetch(PDO::FETCH_ASSOC);

    // 2. Fetch Recent Sermons
    $sermons = $db->query("SELECT * FROM content_hub WHERE content_type = 'sermon' ORDER BY published_date DESC LIMIT 3")->fetchAll();

    // 3. Fetch Music & Resources
    $music = $db->query("SELECT * FROM content_hub WHERE content_type = 'audio' ORDER BY created_at DESC LIMIT 4")->fetchAll();
    $resources = $db->query("SELECT * FROM content_hub WHERE content_type = 'document' ORDER BY created_at DESC LIMIT 4")->fetchAll();

} catch (PDOException $e) {
    $stats = ['total_sermons' => 0, 'total_music' => 0, 'total_resources' => 0, 'total_views' => 0];
    $sermons = $music = $resources = [];
}
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 0; letter-spacing: -1px;">
                Content <span style="color: #fff;">Hub</span>
            </h1>
            <p style="color: #94a3b8; font-size: 1.1rem; margin-top: 5px;">Digital library for sermons, media, and resources.</p>
        </div>
        <a href="upload_content.php" class="action-btn-red" style="text-decoration: none;">+ Upload New Media</a>
    </header>

    <?php if(isset($_GET['success'])): ?>
        <div style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 15px; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.2); margin-bottom: 2rem;">
            <?= $_GET['success'] == 'deleted' ? '✅ Content permanently removed.' : '✅ Upload successful!' ?>
        </div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card pink-glow">
            <h3>Total Sermons</h3>
            <p class="stat-number"><?= $stats['total_sermons'] ?? 0 ?></p>
            <span class="stat-label">Video & Outlines</span>
        </div>
        <div class="stat-card purple-glow">
            <h3>Music Tracks</h3>
            <p class="stat-number"><?= $stats['total_music'] ?? 0 ?></p>
            <span class="stat-label">Audio Library</span>
        </div>
        <div class="stat-card blue-glow">
            <h3>Resources</h3>
            <p class="stat-number"><?= $stats['total_resources'] ?? 0 ?></p>
            <span class="stat-label">Study Materials</span>
        </div>
        <div class="stat-card green-glow">
            <h3>Total Views</h3>
            <p class="stat-number"><?= number_format($stats['total_views'] ?? 0) ?></p>
            <span class="stat-label">Engagement</span>
        </div>
    </div>

    <div class="hub-grid">
        <div class="hub-section glass-panel">
            <div class="section-header">
                <h3>Recent Sermons</h3>
                <a href="all_sermons.php" class="view-all">View All</a>
            </div>
            
            <div class="sermon-list">
                <?php foreach($sermons as $s): ?>
                <div class="sermon-item">
                    <div class="play-icon">▶</div>
                    <div class="sermon-info">
                        <h4><?= htmlspecialchars($s['title']) ?></h4>
                        <p>Speaker: <?= htmlspecialchars($s['speaker']) ?></p>
                        <small><?= date('M d, Y', strtotime($s['published_date'])) ?> • <?= $s['views'] ?> Views</small>
                    </div>
                    <div class="sermon-actions">
                        <button class="hub-btn mini">Watch</button>
                        <a href="../api/delete_content.php?id=<?= $s['id'] ?>" 
                           class="hub-btn icon-btn delete-trigger" 
                           onclick="return confirm('Permanently delete this sermon and its file?')">
                           🗑️
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="sidebar-grid">
            <div class="hub-section glass-panel">
                <h3 style="color: #a855f7; margin-bottom: 1.5rem;">Music Library</h3>
                <?php foreach($music as $m): ?>
                <div class="media-row">
                    <div class="media-title">
                        <span><?= htmlspecialchars($m['title']) ?></span>
                        <small><?= htmlspecialchars($m['speaker']) ?></small>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="media-meta">↓ <?= $m['downloads'] ?></span>
                        <a href="../api/delete_content.php?id=<?= $m['id'] ?>" 
                           style="color: #ef4444; font-size: 0.8rem; text-decoration: none;"
                           onclick="return confirm('Delete this audio track?')">✕</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="hub-section glass-panel" style="margin-top: 20px;">
                <h3 style="color: #06b6d4; margin-bottom: 1.5rem;">Study Resources</h3>
                <?php foreach($resources as $r): ?>
                <div class="media-row">
                    <div class="media-title">
                        <span><?= htmlspecialchars($r['title']) ?></span>
                        <span class="type-tag">PDF</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span class="media-meta">↓ <?= $r['downloads'] ?></span>
                        <a href="../api/delete_content.php?id=<?= $r['id'] ?>" 
                           style="color: #ef4444; font-size: 0.8rem; text-decoration: none;"
                           onclick="return confirm('Delete this resource?')">✕</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* --- CONTENT HUB DARK THEME --- */
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 3rem; }
.stat-card { background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255,255,255,0.1); padding: 25px; border-radius: 20px; backdrop-filter: blur(10px); }
.stat-card h3 { font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; margin: 0; }
.stat-number { font-size: 2.2rem; font-weight: 800; margin: 10px 0; }
.stat-label { font-size: 0.7rem; color: #64748b; }

.pink-glow { border-bottom: 3px solid #f43f5e; }
.purple-glow { border-bottom: 3px solid #a855f7; }
.blue-glow { border-bottom: 3px solid #3b82f6; }
.green-glow { border-bottom: 3px solid #10b981; }

/* Layout */
.hub-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 25px; }
.glass-panel { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255,255,255,0.05); padding: 25px; border-radius: 24px; }

/* Sermon List */
.section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.view-all { color: #ef4444; text-decoration: none; font-size: 0.85rem; font-weight: 600; }
.sermon-item { 
    display: flex; align-items: center; gap: 15px; 
    padding: 15px; background: rgba(255,255,255,0.03); 
    border-radius: 15px; margin-bottom: 12px; border: 1px solid rgba(255,255,255,0.03);
    transition: 0.3s;
}
.sermon-item:hover { background: rgba(255,255,255,0.06); transform: translateX(5px); }

.play-icon { 
    width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444, #b91c1c); 
    border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem;
}

.sermon-info h4 { margin: 0; font-size: 1rem; color: #fff; }
.sermon-info p { margin: 4px 0; font-size: 0.85rem; color: #94a3b8; }
.sermon-info small { font-size: 0.7rem; color: #64748b; }

/* Buttons & Actions */
.action-btn-red { background: #ef4444; color: #fff; padding: 12px 24px; border-radius: 12px; font-weight: 700; border: none; }
.hub-btn { background: rgba(255,255,255,0.1); border: none; color: #fff; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 0.8rem; }
.hub-btn.mini { background: #ef4444; margin-right: 5px; }

.delete-trigger {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #ef4444;
    text-decoration: none;
    padding: 8px 10px;
}
.delete-trigger:hover {
    background: #ef4444;
    color: #fff;
}

/* Media Rows */
.media-row { 
    display: flex; justify-content: space-between; align-items: center; 
    padding: 12px 0; border-bottom: 1px solid rgba(255,255,255,0.05);
}
.media-title { display: flex; flex-direction: column; }
.media-title span { font-size: 0.9rem; color: #e2e8f0; font-weight: 600; }
.media-title small { font-size: 0.75rem; color: #64748b; }
.media-meta { font-size: 0.75rem; color: #94a3b8; }
.type-tag { font-size: 0.65rem; background: rgba(6, 182, 212, 0.2); color: #06b6d4; padding: 2px 6px; border-radius: 4px; margin-top: 4px; display: inline-block; width: fit-content; }
</style>

<?php include '../includes/footer.php'; ?>