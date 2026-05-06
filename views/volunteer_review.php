<?php 
// 1. Include the header for layout and session
include '../includes/header.php'; 

// 2. SAFETY CHECK: Force-load the database if $db is null
if (!isset($db)) {
    $db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
    if (file_exists($db_path)) {
        require_once $db_path;
    } else {
        die("Critical Error: Database connection file not found at " . $db_path);
    }
}

// 3. Fetch Pending Applications using the now-guaranteed $db variable
try {
    $query = "SELECT va.*, u.full_name, m.name as ministry_name 
              FROM volunteer_applications va
              JOIN users u ON va.user_id = u.id
              JOIN ministries m ON va.ministry_id = m.id
              WHERE va.status = 'pending'";
    
    $pending = $db->query($query)->fetchAll();
} catch (PDOException $e) {
    // If the tables don't exist yet, this will give you a clear error
    die("Database Error: " . $e->getMessage());
}
?>

<div class="main-content">
    <h2 class="text-2xl font-bold mb-6 text-slate-800">Pending Applications</h2>

    <?php if(empty($pending)): ?>
        <p class="text-slate-500">No new applications at this time.</p>
    <?php else: ?>
        <div class="grid gap-4">
            <?php foreach($pending as $app): ?>
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-lg"><?= $app['full_name'] ?></h4>
                        <p class="text-blue-600 font-medium">Applying for: <?= $app['ministry_name'] ?></p>
                        <p class="text-slate-600 mt-2"><strong>Skills:</strong> <?= htmlspecialchars($app['skill_set']) ?></p>
                    </div>
                    
                    <form action="../api/process_application.php" method="POST" class="space-y-3">
                        <input type="hidden" name="app_id" value="<?= $app['id'] ?>">
                        <input type="text" name="training" placeholder="Assign training (e.g. Media 101)" class="p-2 border rounded-lg text-sm block">
                        <div class="flex gap-2">
                            <button name="action" value="denied" class="bg-slate-100 text-slate-600 px-4 py-2 rounded-lg text-sm">Deny</button>
                            <button name="action" value="approved" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">Approve & Join</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>