<?php 
// 1. Setup and Headers
include '../includes/header.php'; 


// Ensure database connection is active
if (!isset($db)) {
    $db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
    if (file_exists($db_path)) {
        require_once $db_path;
    } else {
        die("Critical Error: Database connection file not found.");
    }
}

// 2. Logic & Data Fetching
try {
    // Count total events for the current month
    $countQuery = "SELECT COUNT(*) FROM events WHERE MONTH(event_date) = MONTH(CURRENT_DATE())";
    $totalEvents = $db->query($countQuery)->fetchColumn();

    // Fetch all events
    $query = "SELECT * FROM events ORDER BY event_date ASC";
    $events = $db->query($query)->fetchAll();
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Mapping event types to CSS gradients
$typeGradients = [
    'worship'    => 'linear-gradient(to right, #8b5cf6, #7c3aed)',
    'conference' => 'linear-gradient(to right, #06b6d4, #2563eb)',
    'prayer'     => 'linear-gradient(to right, #f43f5e, #db2777)',
    'outreach'   => 'linear-gradient(to right, #10b981, #059669)',
    'seminar'    => 'linear-gradient(to right, #f59e0b, #d97706)',
];
?>

<div class="main-content">
    
    <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem; max-width: 300px;">
        <p style="color: #64748b; font-size: 0.8rem; margin-bottom: 5px; font-weight: 600; text-transform: uppercase;">Events This Month</p>
        <div style="display: flex; align-items: center; gap: 10px;">
            <i data-lucide="calendar-days" class="text-blue-500"></i>
            <h3 style="color: #1e293b; font-size: 1.8rem; margin: 0; font-weight: 800;"><?= $totalEvents ?></h3>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
        <div>
            <?php if (isset($_GET['success'])): ?>
                <div id="success-toast" style="background: #22c55e; color: white; padding: 15px 25px; border-radius: 12px; margin-bottom: 20px; display: inline-block;">
                    Event created successfully!
                </div>
                <script>
                    setTimeout(() => {
                        const toast = document.getElementById('success-toast');
                        if(toast) {
                            toast.style.transition = 'opacity 0.5s ease';
                            toast.style.opacity = '0';
                            setTimeout(() => toast.remove(), 500);
                        }
                    }, 4000);
                </script>
            <?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?>
    <div id="delete-toast" style="background: #ef4444; color: white; padding: 15px 25px; border-radius: 12px; margin-bottom: 20px; display: inline-block;">
        Event removed successfully.
    </div>
    <script>
        setTimeout(() => { document.getElementById('delete-toast').remove(); }, 3000);
    </script>
<?php endif; ?>
            <h1 style="color: #3b82f6; margin-bottom: 0.5rem;">Event <span style="color: #a78bfa;">Management</span></h1>
            <p style="color: #64748b;">Manage and organize church programs</p>
        </div>
        
        <button onclick="window.location.href='create_event.php'" class="btn-primary" style="background: linear-gradient(to right, #8b5cf6, #7c3aed); color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 600; cursor: pointer;">
            + Create Event
        </button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
        <?php foreach($events as $event): 
            $type = $event['event_type'] ?? 'worship';
            $gradient = $typeGradients[$type] ?? $typeGradients['worship'];
        ?>
        <div class="event-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 15px; overflow: hidden; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
            <div style="background: <?= $gradient ?>; padding: 25px; color: white;">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: bold;"><?= htmlspecialchars($event['title']) ?></h3>
                <p style="margin: 8px 0 0 0; font-size: 0.85rem; opacity: 0.9;"><?= htmlspecialchars($event['description'] ?? '') ?></p>
            </div>

            <div style="padding: 25px; display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; color: #475569; font-size: 0.9rem;">
                    <span>📅</span> <?= date('l, F j, Y', strtotime($event['event_date'])) ?>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; color: #475569; font-size: 0.9rem;">
                    <span>🕒</span> <?= $event['event_time'] ? date('h:i A', strtotime($event['event_time'])) : 'TBD' ?>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; color: #475569; font-size: 0.9rem;">
                    <span>📍</span> <?= htmlspecialchars($event['location'] ?? 'Not Specified') ?>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; color: #475569; font-size: 0.9rem;">
                    <span>👥</span> <?= number_format($event['expected_attendees'] ?? 0) ?> Expected
                </div>

                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    
                <button onclick="window.location.href='edit_event.php?id=<?= $event['id'] ?>'" 
              style="flex: 1; padding: 10px; background: <?= $gradient ?>; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
               Edit
                </button>
                    
                    
    
               <button onclick="confirmDelete(<?= $event['id'] ?>, '<?= addslashes($event['title']) ?>')" 
               style="padding: 10px; background: #fee2e2; color: #ef4444; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Delete
               <i data-lucide="trash-2" style="width: 18px; height: 18px;"></i>
              </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
function confirmDelete(id, title) {
    if (confirm("Are you sure you want to delete '" + title + "'? This action cannot be undone.")) {
        window.location.href = "../api/delete_event.php?id=" + id;
    }
}
</script>

<style>
.event-card:hover {
    border-color: #a78bfa;
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
}
</style>
<?php include '../includes/footer.php'; ?>