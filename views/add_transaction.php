<?php include '../includes/header.php'; ?>

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-slate-200">
    <h2 class="text-2xl font-bold mb-6">Record New Transaction</h2>
    
    <form action="../api/save_transaction.php" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-700">Category</label>
            <input type="text" name="type" class="modern-input p-2.5 border rounded-lg">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-slate-700">Amount</label>
            <input type="number" step="0.01" name="amount" required class="w-full p-2.5 border rounded-lg" placeholder="0.00">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Date</label>
            <input type="date" name="transaction_date" value="<?= date('Y-m-d') ?>" class="w-full p-2.5 border rounded-lg">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Description/Member Name</label>
            <input type="text" name="description" class="w-full p-2.5 border rounded-lg">
        </div>

        <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
            Save Transaction
        </button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>