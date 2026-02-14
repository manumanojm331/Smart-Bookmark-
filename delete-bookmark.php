<?php
header('Content-Type: application/json');

require_once '../includes/functions.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['bookmark_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Bookmark ID is required']);
    exit;
}

if (deleteBookmark($pdo, $data['bookmark_id'], $_SESSION['user_id'])) {
    echo json_encode(['success' => true, 'message' => 'Bookmark deleted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete bookmark']);
}
?>
