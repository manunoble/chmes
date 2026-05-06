<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 

$program_id = $_GET['id'] ?? null;

if (!$program_id) {
    header("Location: discipleship.php");
    exit();
}

try {
    // 1. Fetch Program Details
    $pStmt = $db->prepare("SELECT * FROM discipleship_programs WHERE id = ?");
    $pStmt->execute([$program_id]);
    $program = $pStmt->fetch();

    // 2. Fetch Enrolled Members (Added m.id as member_id for the delete link)
    $sql = "SELECT m.id as member_id, m.full_name, m.email, m.phone, e.enrollment_date 
            FROM members m
            JOIN discipleship_enrollments e ON m.id = e.member_id
            WHERE e.program_id = ?
            ORDER BY m.full_name ASC";
            
    $stmt = $db->prepare($sql);
    $stmt->execute([$program_id]);
    $participants = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<div class="main-content">
    <header style="margin-bottom: 3rem;">
        <a href="discipleship.php" class="back-link">← Back to Dashboard</a>
        <h1 style="font-size: 2.5rem; color: <?= $program['theme_color'] ?>; font-weight: 800; margin: 15px 0 5px 0;">
            <?= htmlspecialchars($program['name']) ?> <span style="color: #fff;">Roster</span>
        </h1>
        <p style="color: #94a3b8;">Mentor: <b><?= htmlspecialchars($program['mentor_name']) ?></b> | Total: <?= count($participants) ?> Participants</p>
    </header>

    <?php if(isset($_GET['success'])): ?>
        <div class="success-alert">✅ Member successfully removed from program.</div>
    <?php endif; ?>

    <div class="attendance-container">
        <div class="table-header" style="border-bottom: 2px solid <?= $program['theme_color'] ?>;">
            <h3>Enrolled Members</h3>
            <a href="enroll_member.php?program_id=<?= $program_id ?>" class="badge" style="background: <?= $program['theme_color'] ?>22; color: <?= $program['theme_color'] ?>; text-decoration: none;">+ Enroll Another</a>
        </div>
        
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Contact Info</th>
                    <th>Joined</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($participants)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 50px; color: #64748b;">No members enrolled in this program yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($participants as $p): ?>
                    <tr>
                        <td class="member-name"><?= htmlspecialchars($p['full_name']) ?></td>
                        <td>
                            <div style="font-size: 0.9rem; color: #fff;"><?= htmlspecialchars($p['phone'] ?: 'No Phone') ?></div>
                            <div style="font-size: 0.75rem; color: #64748b;"><?= htmlspecialchars($p['email']) ?></div>
                        </td>
                        <td style="color: #94a3b8; font-size: 0.85rem;">
                            <?= date('M d, Y', strtotime($p['enrollment_date'])) ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="../api/remove_participant.php?member_id=<?= $p['member_id'] ?>&program_id=<?= $program_id ?>" 
                               class="remove-btn" 
                               onclick="return confirm('Are you sure you want to remove this member from the program?')">
                                Remove
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.main-content { background: #0f172a; min-height: 100vh; padding: 60px 40px; color: #fff; }
.back-link { color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
.back-link:hover { color: #ef4444; }

.attendance-container { background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(255,255,255,0.1); border-radius: 24px; overflow: hidden; }
.table-header { padding: 25px; display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.2); }
.modern-table { width: 100%; border-collapse: collapse; }
.modern-table th { padding: 15px 25px; text-align: left; font-size: 0.75rem; color: #64748b; text-transform: uppercase; }
.modern-table td { padding: 18px 25px; border-bottom: 1px solid rgba(255,255,255,0.05); }
.member-name { font-weight: 700; color: #fff; }

.success-alert {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid rgba(16, 185, 129, 0.2);
    margin-bottom: 20px;
}

.remove-btn {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 700;
    border: 1px solid rgba(239, 68, 68, 0.2);
    transition: 0.3s;
}

.remove-btn:hover {
    background: #ef4444;
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}
</style>

<?php include '../includes/footer.php'; ?>