<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

// 1. Fetch Dynamic Programs and Calculate Stats
try {
    $stmt = $db->query("SELECT * FROM discipleship_programs ORDER BY created_at DESC");
    $programs = $stmt->fetchAll();
    
    $total_participants = array_sum(array_column($programs, 'target_participants'));
    $active_count = count($programs);

    $avg_stmt = $db->query("SELECT AVG(current_progress) FROM discipleship_programs");
    $maturity_rate = round($avg_stmt->fetchColumn() ?? 0);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<div class="main-content">
    <header style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
        <div>
            <h1 style="font-size: 2.5rem; color: #ef4444; font-weight: 800; margin: 0; letter-spacing: -1px;">
                Discipleship <span style="color: #fff;">Tracking</span>
            </h1>
            <p style="color: #94a3b8; font-size: 1.1rem; margin-top: 5px;">Cultivating spiritual maturity and leadership.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="update_progress.php" class="action-btn-outline">📊 Update Progress</a>
            <a href="add_program.php" class="action-btn-red" style="text-decoration: none;">+ Start New Program</a>
        </div>
    </header>

    <div class="stats-grid">
        <div class="stat-card pink-glow">
            <h3>Active Programs</h3>
            <p class="stat-number"><?= $active_count ?></p>
            <span class="stat-label">Currently Running</span>
        </div>
        <div class="stat-card purple-glow">
            <h3>Total Disciples</h3>
            <p class="stat-number"><?= $total_participants ?></p>
            <span class="stat-label">Registered Enrollees</span>
        </div>
        <div class="stat-card green-glow">
            <h3>Maturity Rate</h3>
            <p class="stat-number"><?= $maturity_rate ?>%</p>
            <span class="stat-label">Avg. System Progress</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
        <?php if(empty($programs)): ?>
            <div class="stat-card" style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <p style="color: #64748b;">No discipleship programs found. Start one to begin tracking.</p>
            </div>
        <?php else: ?>
            <?php foreach($programs as $p): ?>
            <div class="program-card">
                <div class="program-top" style="border-left: 4px solid <?= $p['theme_color'] ?>;">
                    <h4 style="margin: 0; font-size: 1.25rem; color: #fff;"><?= htmlspecialchars($p['name']) ?></h4>
                    <span class="duration-tag"><?= htmlspecialchars($p['duration']) ?></span>
                </div>
                
                <div class="program-body">
                    <div class="mentor-row">
                        <span><i style="color: <?= $p['theme_color'] ?>; margin-right: 5px;">👤</i> Mentor: <b style="color: #fff;"><?= htmlspecialchars($p['mentor_name']) ?></b></span>
                        <span><i style="color: <?= $p['theme_color'] ?>; margin-right: 5px;">👥</i> Enrolled: <b style="color: #fff;"><?= $p['target_participants'] ?></b></span>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress-bar" style="width: <?= $p['current_progress'] ?>%; background: <?= $p['theme_color'] ?>; box-shadow: 0 0 15px <?= $p['theme_color'] ?>66;"></div>
                    </div>
                    
                    <div class="progress-text">
                        <span style="color: <?= $p['theme_color'] ?>; font-weight: 800;"><?= $p['current_progress'] ?>% Complete</span>
                        <span class="status-pill" style="background: <?= $p['theme_color'] ?>22; color: <?= $p['theme_color'] ?>;"><?= $p['status'] ?></span>
                    </div>

                    <div class="card-action-row">
                        <a href="view_participants.php?id=<?= $p['id'] ?>" class="card-btn-outline" style="border-color: <?= $p['theme_color'] ?>66; color: <?= $p['theme_color'] ?>;">
                             Roster
                        </a>
                        <a href="enroll_member.php?program_id=<?= $p['id'] ?>" class="card-btn-filled" style="background: <?= $p['theme_color'] ?>;">
                             Enroll
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
/* --- DARK SYSTEM STYLING --- */
.main-content { background: #0f172a; min-height: 100vh; padding: 40px; color: #fff; font-family: 'Inter', sans-serif; }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 3rem; }
.stat-card { background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255,255,255,0.1); padding: 30px; border-radius: 24px; backdrop-filter: blur(10px); }
.stat-card h3 { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin: 0; }
.stat-number { font-size: 3rem; font-weight: 800; margin: 15px 0; color: #fff; }
.stat-label { font-size: 0.75rem; color: #94a3b8; }

/* Glow Accents */
.pink-glow { border-bottom: 4px solid #d946ef; box-shadow: 0 10px 30px -10px rgba(217, 70, 239, 0.2); }
.purple-glow { border-bottom: 4px solid #a855f7; box-shadow: 0 10px 30px -10px rgba(168, 85, 247, 0.2); }
.green-glow { border-bottom: 4px solid #10b981; box-shadow: 0 10px 30px -10px rgba(16, 185, 129, 0.2); }

/* Program Cards */
.program-card { background: rgba(30, 41, 59, 0.4); border: 1px solid rgba(255,255,255,0.05); border-radius: 24px; overflow: hidden; transition: 0.3s ease; }
.program-card:hover { transform: translateY(-5px); border-color: rgba(255,255,255,0.15); background: rgba(30, 41, 59, 0.6); }

.program-top { padding: 20px 25px; background: rgba(0,0,0,0.2); display: flex; justify-content: space-between; align-items: center; }
.duration-tag { background: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 8px; font-size: 0.75rem; color: #cbd5e1; }

.program-body { padding: 25px; }
.mentor-row { display: flex; justify-content: space-between; font-size: 0.85rem; color: #94a3b8; margin-bottom: 25px; }

/* Progress UI */
.progress-container { height: 12px; background: rgba(255,255,255,0.05); border-radius: 20px; overflow: hidden; margin-bottom: 12px; }
.progress-bar { height: 100%; border-radius: 20px; transition: width 1s ease-in-out; }
.progress-text { display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; margin-bottom: 20px; }
.status-pill { padding: 4px 10px; border-radius: 20px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }

/* --- CARD BUTTONS --- */
.card-action-row { display: flex; gap: 10px; margin-top: 10px; }

.card-btn-outline {
    flex: 1;
    text-align: center;
    padding: 10px;
    border: 1px solid;
    border-radius: 12px;
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 700;
    transition: 0.3s;
    background: transparent;
}
.card-btn-outline:hover { background: rgba(255, 255, 255, 0.05); transform: translateY(-2px); }

.card-btn-filled {
    flex: 1;
    text-align: center;
    padding: 10px;
    border-radius: 12px;
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 700;
    color: white;
    transition: 0.3s;
}
.card-btn-filled:hover { filter: brightness(1.2); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0,0,0,0.2); }

/* --- HEADER BUTTONS --- */
.action-btn-red { background: #ef4444; color: #fff; padding: 12px 24px; border-radius: 12px; font-weight: 700; transition: 0.3s; border: none; cursor: pointer; }
.action-btn-red:hover { background: #fff; color: #ef4444; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(239, 68, 68, 0.2); }

.action-btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #fff; padding: 12px 24px; border-radius: 12px; font-weight: 700; transition: 0.3s; text-decoration: none; }
.action-btn-outline:hover { border-color: #fff; background: rgba(255,255,255,0.05); }
</style>

<?php include '../includes/footer.php'; ?>