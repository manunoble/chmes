<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 


// Example Data
$stats = [
    ['label' => 'Active Cases', 'value' => 12, 'color' => 'linear-gradient(135deg, #f43f5e, #db2777)'],
    ['label' => 'Completed (Mo)', 'value' => 8, 'color' => 'linear-gradient(135deg, #10b981, #059669)'],
    ['label' => 'Total Distributed', 'value' => '$45,000', 'color' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)'],
    ['label' => 'Members Helped', 'value' => 34, 'color' => 'linear-gradient(135deg, #f59e0b, #d97706)'],
];

$requests = [
    ['id' => 1, 'name' => 'Margaret Thompson', 'need' => 'Medical Assistance', 'status' => 'urgent', 'amount' => 5000, 'phone' => '+1-234-567-8901', 'loc' => 'District A'],
    ['id' => 2, 'name' => 'Robert Williams', 'need' => 'Food Support', 'status' => 'pending', 'amount' => 1500, 'phone' => '+1-234-567-8902', 'loc' => 'District B'],
    ['id' => 3, 'name' => 'Emily Davis', 'need' => 'Rent Assistance', 'status' => 'in-progress', 'amount' => 8000, 'phone' => '+1-234-567-8903', 'loc' => 'District C'],
    ['id' => 5, 'name' => 'Patricia Wilson', 'need' => 'Medical Assistance', 'status' => 'completed', 'amount' => 4500, 'phone' => '+1-234-567-8905', 'loc' => 'District D'],
];

// Helper for status styling
function getStatusStyle($status) {
    switch($status) {
        case 'urgent': return ['bg' => '#ef444422', 'text' => '#f87171', 'border' => '#ef444455'];
        case 'in-progress': return ['bg' => '#3b82f622', 'text' => '#60a5fa', 'border' => '#3b82f655'];
        case 'completed': return ['bg' => '#10b98122', 'text' => '#34d399', 'border' => '#10b98155'];
        default: return ['bg' => '#f59e0b22', 'text' => '#fbbf24', 'border' => '#f59e0b55'];
    }
}
?>

<div class="main-content">
    <header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="color: #fff;">Welfare <span style="color: #f43f5e;">Management</span></h1>
            <p style="color: #94a3b8;">Supporting our community with grace and transparency.</p>
        </div>
        <button class="btn-primary" style="background: linear-gradient(to right, #f43f5e, #e11d48); border: none; padding: 12px 24px;">+ New Request</button>
    </header>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <?php foreach($stats as $s): ?>
        <div class="card" style="background: <?= $s['color'] ?>; color: white; border: none;">
            <p style="font-size: 0.8rem; opacity: 0.8; margin-bottom: 5px;"><?= $s['label'] ?></p>
            <h2 style="font-size: 1.8rem; margin: 0;"><?= $s['value'] ?></h2>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 15px; padding: 25px;">
        <h3 style="color: white; margin-bottom: 20px;">Open Welfare Requests</h3>
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach($requests as $r): 
                $style = getStatusStyle($r['status']);
            ?>
            <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; transition: 0.3s;" onmouseover="this.style.borderColor='#f43f5e88'" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #fb7185, #f43f5e); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            <?= substr($r['name'], 0, 1) ?>
                        </div>
                        <div>
                            <h4 style="color: white; margin: 0; font-size: 1.1rem;"><?= $r['name'] ?></h4>
                            <p style="color: #94a3b8; font-size: 0.85rem; margin: 3px 0 0 0;"><?= $r['need'] ?></p>
                        </div>
                    </div>
                    
                    <div style="text-align: right;">
                        <span style="background: <?= $style['bg'] ?>; color: <?= $style['text'] ?>; border: 1px solid <?= $style['border'] ?>; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase;">
                            <?= str_replace('-', ' ', $r['status']) ?>
                        </span>
                        <h3 style="color: white; margin: 10px 0 0 0;">$<?= number_format($r['amount']) ?></h3>
                    </div>
                </div>

                <div style="margin: 15px 0 15px 65px; display: flex; gap: 30px; color: #64748b; font-size: 0.85rem;">
                    <span>📞 <?= $r['phone'] ?></span>
                    <span>📍 <?= $r['loc'] ?></span>
                </div>

                <div style="margin-left: 65px; display: flex; gap: 10px;">
                    <button style="padding: 8px 16px; background: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; font-size: 0.8rem;">Details</button>
                    <button style="padding: 8px 16px; background: linear-gradient(to right, #f43f5e, #e11d48); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 0.8rem;">Update Status</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>