<?php 
// 1. Setup and Headers
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

// 3. Fetch Summary Data (Automatic Calculations)
$month = date('m');
$year = date('Y');

try {
    // Calculate Totals by Category
    $query = "SELECT type, SUM(amount) as total FROM finances WHERE MONTH(transaction_date) = ? AND YEAR(transaction_date) = ? GROUP BY type";
    $stmt = $db->prepare($query); // This is line 10 - $db is now guaranteed to exist
    $stmt->execute([$month, $year]);
    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    // Annual Summary Query for the Chart
    $annualQuery = "SELECT MONTH(transaction_date) as m, SUM(amount) as total FROM finances WHERE YEAR(transaction_date) = ? GROUP BY MONTH(transaction_date)";
    $annualStmt = $db->prepare($annualQuery);
    $annualStmt->execute([$year]);
    $annualResults = $annualStmt->fetchAll(PDO::FETCH_KEY_PAIR);

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}

// Pre-fill categories (ensure they exist even if 0)
$categories = ['tithe'=>0, 'offering'=>0, 'donation'=>0, 'mission'=>0, 'thanksgiving'=>0, 'expense'=>0];
foreach($results as $type => $total) { $categories[$type] = $total; }

$totalIncome = $categories['tithe'] + $categories['offering'] + $categories['donation'] + $categories['mission'] + $categories['thanksgiving'];
$totalExpenses = abs($categories['expense']);
$netBalance = $totalIncome - $totalExpenses;
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
        <div>
            <h1 style="color: #3b82f6; margin-bottom: 0.5rem;">Financial <span style="color: #a78bfa;">Management</span></h1>
            <p style="color: #64748b;">Automatic calculation of tithes, offerings, and missions.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button onclick="window.location.href='../api/export_pdf.php'" class="btn-primary" style="background: #ef4444; color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 600; cursor: pointer;">
                📄 Export PDF
            </button>
            <button onclick="window.location.href='add_transaction.php'" class="btn-primary" style="background: linear-gradient(to right, #8b5cf6, #7c3aed); color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 600; cursor: pointer;">
                + Add Fund
            </button>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <div style="background: white; padding: 25px; border-radius: 15px; border-left: 5px solid #10b981; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <p style="color: #64748b; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Total Income</p>
            <h2 style="color: #1e293b; font-size: 2rem; margin: 5px 0;">Ksh<?= number_format($totalIncome, 2) ?></h2>
            <small style="color: #10b981;">▲ This Month</small>
        </div>
        <div style="background: white; padding: 25px; border-radius: 15px; border-left: 5px solid #ef4444; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <p style="color: #64748b; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Total Expenses</p>
            <h2 style="color: #1e293b; font-size: 2rem; margin: 5px 0;">Ksh<?= number_format($totalExpenses, 2) ?></h2>
            <small style="color: #ef4444;">▼ Outgoing</small>
        </div>
        <div style="background: white; padding: 25px; border-radius: 15px; border-left: 5px solid #8b5cf6; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
            <p style="color: #64748b; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Net Balance</p>
            <h2 style="color: #1e293b; font-size: 2rem; margin: 5px 0;">Ksh<?= number_format($netBalance, 2) ?></h2>
            <small style="color: #8b5cf6;">Available Funds</small>
        </div>
    </div>

    <div style="background: white; border-radius: 15px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 2.5rem;">
        <h3 style="padding: 20px; color: #1e293b; border-bottom: 1px solid #e2e8f0;">Monthly Fund Breakdown</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; text-align: left; color: #64748b; font-size: 0.85rem;">
                    <th style="padding: 15px 20px;">Fund Category</th>
                    <th style="padding: 15px 20px; text-align: right;">Current Month Total</th>
                </tr>
            </thead>
            <tbody style="color: #475569;">
                <?php foreach(['tithe', 'offering', 'donation', 'mission', 'thanksgiving'] as $cat): ?>
                <tr style="border-top: 1px solid #f1f5f9;">
                    <td style="padding: 15px 20px; font-weight: 500;"><?= ucfirst($cat) ?></td>
                    <td style="padding: 15px 20px; text-align: right; font-weight: bold;">Ksh<?= number_format($categories[$cat], 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php
// Annual Summary Query
$annualQuery = "SELECT MONTH(transaction_date) as m, SUM(amount) as total FROM finances WHERE YEAR(transaction_date) = ? GROUP BY MONTH(transaction_date)";
$stmt = $db->prepare($annualQuery);
$stmt->execute([$year]);
$annualResults = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<div style="background: white; padding: 25px; border-radius: 15px; border: 1px solid #e2e8f0;">
    <h3 style="margin-bottom: 1.5rem;">Annual Financial Performance (<?= $year ?>)</h3>
    <canvas id="annualChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('annualChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Total Revenue',
            data: [<?= implode(',', array_pad($annualResults, 12, 0)) ?>],
            backgroundColor: '#3b82f6',
            borderRadius: 8
        }]
    }
});
</script>
<?php include '../includes/footer.php'; ?>