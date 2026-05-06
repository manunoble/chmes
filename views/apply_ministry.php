<?php include '../includes/header.php'; ?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Volunteer Application</h1>
        <p class="text-slate-500">Select a ministry where you would like to serve with your talents.</p>
    </div>

    <form action="../api/save_application.php" method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Select Ministry</label>
           <select name="ministry_id" required class="w-full p-2.5 border border-slate-300 rounded-lg outline-none">
    <option value="">-- Choose a Team --</option>
    <?php
    try {
        // Ensure $db is available (Safety Check)
        if (!isset($db)) {
            $db_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';
            require_once $db_path;
        }

        $stmt = $db->query("SELECT id, name FROM ministries ORDER BY name ASC");
        $count = 0;
        
        while($row = $stmt->fetch()) {
            $count++;
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }

        if ($count === 0) {
            echo "<option disabled>No ministries found in database</option>";
        }
    } catch (PDOException $e) {
        // This will print the error directly in the dropdown if it fails
        echo "<option disabled>Error: " . $e->getMessage() . "</option>";
    }
    ?>
</select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Your Skill Set</label>
            <textarea name="skill_set" rows="3" required placeholder="e.g., Graphic Design, Guitar, Teaching, First Aid..." 
                class="w-full p-2.5 border border-slate-300 rounded-lg outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            <p class="text-xs text-slate-400 mt-1">This helps leads assign the right service responsibilities.</p>
        </div>

        <div class="flex justify-end gap-4 pt-4">
            <a href="ministries.php" class="px-6 py-2.5 text-slate-600 font-medium">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                Submit Application
            </button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>