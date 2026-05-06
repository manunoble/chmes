<?php 
require '../includes/auth_check.php'; 
require '../includes/db.php';
include '../includes/header.php'; 


// Example Data (Replace with database queries like SELECT SUM(students) FROM classes)
$classes = [
    ['id' => 1, 'name' => 'Beginners Class', 'age' => '3-5 years', 'teacher' => 'Mary Johnson', 'students' => 18, 'color' => 'linear-gradient(to right, #ec4899, #e11d48)', 'lesson' => 'Jesus Loves the Little Children'],
    ['id' => 2, 'name' => 'Primary Class', 'age' => '6-8 years', 'teacher' => 'David Smith', 'students' => 24, 'color' => 'linear-gradient(to right, #f59e0b, #d97706)', 'lesson' => 'The Good Samaritan'],
    ['id' => 3, 'name' => 'Juniors Class', 'age' => '9-11 years', 'teacher' => 'Sarah Williams', 'students' => 20, 'color' => 'linear-gradient(to right, #10b981, #059669)', 'lesson' => 'David and Goliath'],
    ['id' => 4, 'name' => 'Teens Class', 'age' => '12-15 years', 'teacher' => 'Michael Brown', 'students' => 16, 'color' => 'linear-gradient(to right, #06b6d4, #2563eb)', 'lesson' => 'Walking in Faith'],
];

$topPerformers = [
    ['name' => 'Emma Johnson', 'class' => 'Primary', 'score' => 98, 'awards' => 5],
    ['name' => 'Liam Smith', 'class' => 'Juniors', 'score' => 96, 'awards' => 4],
    ['name' => 'Olivia Davis', 'class' => 'Beginners', 'score' => 95, 'awards' => 3],
];

$totalStudents = array_sum(array_column($classes, 'students'));
?>

<div class="main-content">
    <header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="color: #fff;">Sunday School <span style="color: #14b8a6;">Management</span></h1>
            <p style="color: #94a3b8;">Nurturing the next generation in faith.</p>
        </div>
        <button class="btn-primary" style="background: linear-gradient(to right, #14b8a6, #0891b2); border: none; padding: 12px 24px;">+ New Class</button>
    </header>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #14b8a6, #0891b2); color: white; border: none;">
            <p style="font-size: 0.8rem; opacity: 0.8;">Total Classes</p>
            <h2 style="margin: 5px 0;"><?= count($classes) ?></h2>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: white; border: none;">
            <p style="font-size: 0.8rem; opacity: 0.8;">Total Students</p>
            <h2 style="margin: 5px 0;"><?= $totalStudents ?></h2>
        </div>
        <div class="card" style="background: linear-gradient(135deg, #10b981, #047857); color: white; border: none;">
            <p style="font-size: 0.8rem; opacity: 0.8;">Attendance Rate</p>
            <h2 style="margin: 5px 0;">92%</h2>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem;">
        <?php foreach($classes as $c): ?>
        <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; overflow: hidden;">
            <div style="background: <?= $c['color'] ?>; padding: 20px; color: white;">
                <h3 style="margin: 0;"><?= $c['name'] ?></h3>
                <small style="opacity: 0.9;"><?= $c['age'] ?></small>
            </div>
            <div style="padding: 20px; color: #cbd5e1;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <span>Teacher: <strong><?= $c['teacher'] ?></strong></span>
                    <span>Students: <strong><?= $c['students'] ?></strong></span>
                </div>
                <p style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 5px;">Current Lesson:</p>
                <p style="color: white; font-weight: 500;"><?= $c['lesson'] ?></p>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button style="flex: 1; padding: 8px; background: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 6px;">Roster</button>
                    <button style="flex: 1; padding: 8px; background: <?= $c['color'] ?>; color: white; border: none; border-radius: 6px;">Manage</button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="table-wrapper" style="background: rgba(255,255,255,0.03); padding: 25px; border-radius: 15px;">
        <h3 style="color: white; margin-bottom: 20px;">🏆 Top Performers This Month</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; color: #94a3b8; font-size: 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <th style="padding: 10px;">Rank</th>
                    <th style="padding: 10px;">Student</th>
                    <th style="padding: 10px;">Class</th>
                    <th style="padding: 10px;">Score</th>
                    <th style="padding: 10px; text-align: right;">Awards</th>
                </tr>
            </thead>
            <tbody style="color: white;">
                <?php foreach($topPerformers as $index => $s): 
                    // Determine rank colors
                    $rankBg = ['#eab30833', '#94a3b833', '#f9731633'][$index] ?? '#ffffff11';
                    $rankText = ['#facc15', '#cbd5e1', '#fb923c'][$index] ?? '#94a3b8';
                ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="padding: 15px 10px;">
                        <span style="background: <?= $rankBg ?>; color: <?= $rankText ?>; width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold;">
                            <?= $index + 1 ?>
                        </span>
                    </td>
                    <td style="padding: 15px 10px;"><?= $s['name'] ?></td>
                    <td style="padding: 15px 10px; color: #94a3b8;"><?= $s['class'] ?></td>
                    <td style="padding: 15px 10px; color: #4ade80; font-weight: bold;"><?= $s['score'] ?>%</td>
                    <td style="padding: 15px 10px; text-align: right;">🏅 <?= $s['awards'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include '../includes/footer.php'; ?>