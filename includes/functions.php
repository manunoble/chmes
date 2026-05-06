<?php
require_once __DIR__ . '/../config/constants.php';

// Format numbers as currency
function formatMoney($amount) {
    return CURRENCY . number_format($amount, 2);
}

// Clean user input to prevent XSS (Cross-Site Scripting)
function clean($data) {
    return htmlspecialchars(strip_tags($data));
}

// Get initials for profile avatars if no image exists
function getInitials($name) {
    $words = explode(" ", $name);
    $initials = "";
    foreach ($words as $w) {
        $initials .= $w[0];
    }
    return strtoupper(substr($initials, 0, 2));
}
?>