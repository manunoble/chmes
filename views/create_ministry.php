<?php 
include '../includes/header.php'; 

// Safety Check for DB
if (!isset($db)) {
    $db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
    require_once $db_path;
}

// Fetch all users to populate the Lead and Assistant dropdowns
$users = $db->query("SELECT id, full_name FROM users ORDER BY full_name ASC")->fetchAll();
?>

<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Create New <span class="text-blue-600">Ministry</span></h1>
        <p class="text-slate-500">Establish a new department and assign leadership.</p>
    </div>

    <form action="../api/save_ministry.php" method="POST" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Ministry Name</label>
                    <input type="text" name="name" required placeholder="e.g. Praise & Worship" 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Ministry Lead</label>
                    <select name="lead_id" class="w-full p-2.5 border border-slate-300 rounded-lg">
                        <option value="">-- Select Lead --</option>
                        <?php foreach($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= $user['full_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Assistant Lead</label>
                    <select name="assistant_id" class="w-full p-2.5 border border-slate-300 rounded-lg">
                        <option value="">-- Select Assistant --</option>
                        <?php foreach($users as $user): ?>
                            <option value="<?= $user['id'] ?>"><?= $user['full_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Meeting Schedule</label>
                    <input type="text" name="meeting_schedule" placeholder="e.g. Every Saturday at 4 PM" 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Service Responsibilities</label>
                    <textarea name="responsibilities" rows="4" placeholder="List the main tasks..." 
                        class="w-full p-2.5 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
            <textarea name="description" rows="2" class="w-full p-2.5 border border-slate-300 rounded-lg outline-none"></textarea>
        </div>

        <div class="flex justify-end gap-4 pt-4 border-t">
            <a href="ministries.php" class="px-6 py-2.5 text-slate-600 font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                Create Ministry
            </button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>