<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

require_once '../includes/functions.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$bookmarks = getUserBookmarks($pdo, $_SESSION['user_id']);
echo json_encode($bookmarks);
?>
