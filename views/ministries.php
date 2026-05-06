<?php 
include '../includes/header.php'; 

// Safety Check for DB Connection
if (!isset($db)) {
    $db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
    require_once $db_path;
}

// 1. Fetch All Ministries with Member Counts & Leads
$query = "SELECT m.*, 
          (SELECT COUNT(*) FROM ministry_members WHERE ministry_id = m.id) as member_count,
          u1.full_name as lead_name, u2.full_name as assistant_name
          FROM ministries m
          LEFT JOIN users u1 ON m.lead_id = u1.id
          LEFT JOIN users u2 ON m.assistant_id = u2.id";
$ministries = $db->query($query)->fetchAll();

// 2. Identify Volunteers NOT in any ministry
$unassignedQuery = "SELECT COUNT(*) FROM users WHERE id NOT IN (SELECT user_id FROM ministry_members)";
$unassignedCount = $db->query($unassignedQuery)->fetchColumn();

// 3. Pending Applications for the Badge
$pendingCount = $db->query("SELECT COUNT(*) FROM volunteer_applications WHERE status = 'pending'")->fetchColumn();
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
    <div>
        <h1 style="color: #3b82f6; margin-bottom: 0.5rem;">Ministry <span style="color: #a78bfa;">Hub</span></h1>
        <p style="color: #64748b;">Manage teams, schedules, and volunteer growth.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button onclick="window.location.href='volunteer_review.php'" class="btn-primary" style="background: white; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 12px; font-weight: 600; cursor: pointer;">
            Review Applications
        </button>
        
        <button onclick="window.location.href='create_ministry.php'" class="btn-primary" style="background: linear-gradient(to right, #8b5cf6, #7c3aed); color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px rgba(139, 92, 246, 0.2);">
            + Create Ministry
        </button>
    </div>
</div>
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
    
        <div style="display: flex; gap: 10px;">
            <button onclick="window.location.href='volunteer_review.php'" class="btn-primary" style="background: white; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 12px; font-weight: 600; cursor: pointer; position: relative;">
                Review Applications
                <?php if($pendingCount > 0): ?>
                    <span style="position: absolute; top: -5px; right: -5px; bg: #ef4444; color: white; border-radius: 50%; padding: 2px 7px; font-size: 10px;"><?= $pendingCount ?></span>
                <?php endif; ?>
            </button>
            <button onclick="window.location.href='apply_ministry.php'" class="btn-primary" style="background: linear-gradient(to right, #8b5cf6, #7c3aed); color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 600; cursor: pointer;">
                + Join Ministry
            </button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div style="background: white; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <p style="color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">Unassigned Volunteers</p>
            <h3 style="color: #1e293b; font-size: 1.8rem; margin: 5px 0;"><?= $unassignedCount ?></h3>
            <small style="color: #f59e0b;">Suggest: Invite to join a team</small>
        </div>
        <div style="background: white; padding: 20px; border-radius: 15px; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <p style="color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">Understaffed Teams</p>
            <?php 
                $understaffed = array_filter($ministries, fn($m) => $m['member_count'] < 20);
            ?>
            <h3 style="color: #ef4444; font-size: 1.8rem; margin: 5px 0;"><?= count($understaffed) ?></h3>
            <small style="color: #64748b;">Less than 20 members</small>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
        <?php foreach($ministries as $m): 
            $isUnderstaffed = $m['member_count'] < 20;
        ?>
        <div class="event-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 15px; overflow: hidden; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            
            <div style="background: <?= $m['color_gradient'] ?>; padding: 25px; color: white;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 1.25rem; font-weight: bold;"><?= htmlspecialchars($m['name']) ?></h3>
                    <?php if($isUnderstaffed): ?>
                        <span style="background: rgba(255,255,255,0.2); border: 1px solid white; font-size: 10px; padding: 2px 8px; border-radius: 10px;">UNDERSTAFFED</span>
                    <?php endif; ?>
                </div>
                <p style="margin: 8px 0 0 0; font-size: 0.85rem; opacity: 0.9;"><?= htmlspecialchars($m['description']) ?></p>
            </div>

            <div style="padding: 25px; display: flex; flex-direction: column; gap: 12px;">
                <div style="display: grid; grid-cols-2; gap: 10px;">
                    <div>
                        <p style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase;">Ministry Lead</p>
                        <p style="color: #1e293b; font-weight: 600; font-size: 0.9rem;"><?= $m['lead_name'] ?? 'Vacant' ?></p>
                    </div>
                    <div>
                        <p style="color: #94a3b8; font-size: 0.7rem; text-transform: uppercase;">Assistant Lead</p>
                        <p style="color: #1e293b; font-weight: 600; font-size: 0.9rem;"><?= $m['assistant_name'] ?? 'Vacant' ?></p>
                    </div>
                </div>

                <div style="border-top: 1px solid #f1f5f9; padding-top: 10px;">
                    <p style="color: #475569; font-size: 0.85rem;"><strong>Schedule:</strong> <?= $m['meeting_schedule'] ?></p>
                    <p style="color: #475569; font-size: 0.85rem;"><strong>Responsibility:</strong> <?= htmlspecialchars($m['responsibilities']) ?></p>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <span style="font-size: 0.9rem; font-weight: bold; color: <?= $isUnderstaffed ? '#ef4444' : '#10b981' ?>;">
                        👤 <?= $m['member_count'] ?> Members
                    </span>
                    <button onclick="window.location.href='ministry_roster.php?id=<?= $m['id'] ?>'" 
                     style="padding: 8px 15px; background: #f1f5f9; border-radius: 8px; font-size: 0.85rem; border: none; cursor: pointer; font-weight: 600; color: #475569;">
                     View Roster
</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>