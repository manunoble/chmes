<?php
// ... DB connection logic ...
if(isset($_POST['action'])) {
    $id = $_POST['app_id'];
    $status = $_POST['action']; // 'approved' or 'denied'
    $training = $_POST['training'];

    $sql = "UPDATE volunteer_applications SET status = ?, training_assigned = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$status, $training, $id]);

    // If approved, automatically add them to the ministry_members table
    if($status === 'approved') {
        $app = $db->query("SELECT user_id, ministry_id FROM volunteer_applications WHERE id = $id")->fetch();
        $db->prepare("INSERT INTO ministry_members (user_id, ministry_id) VALUES (?, ?)")
           ->execute([$app['user_id'], $app['ministry_id']]);
    }
    header("Location: ../views/ministries.php?success=1");
}