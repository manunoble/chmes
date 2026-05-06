<?php 
// 1. Include the header (Layout & Session)
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

// 3. Get the Event ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // 4. Fetch the current data for this specific event
    $stmt = $db->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $event = $stmt->fetch();

    if (!$event) {
        die("<div class='p-8 text-red-500 font-bold'>Error: Event not found in database.</div>");
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Event: <span class="text-blue-600"><?= htmlspecialchars($event['title']) ?></span></h1>
    </div>

    <form action="../api/update_event.php" method="POST" class="space-y-6">
        <input type="hidden" name="id" value="<?= $event['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Event Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Event Type</label>
                    <select name="event_type" class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                        <?php 
                        $types = ['worship', 'conference', 'prayer', 'outreach', 'seminar'];
                        foreach($types as $type): ?>
                            <option value="<?= $type ?>" <?= $event['event_type'] == $type ? 'selected' : '' ?>>
                                <?= ucfirst($type) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                        <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required 
                            class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Time</label>
                        <input type="time" name="event_time" value="<?= $event['event_time'] ?>" required 
                            class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Location</label>
                    <input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="5" class="w-full p-2.5 border border-slate-300 rounded-lg outline-none"><?= htmlspecialchars($event['description']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t">
            <a href="events.php" class="px-6 py-2.5 text-slate-600 font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                Update Changes
            </button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>