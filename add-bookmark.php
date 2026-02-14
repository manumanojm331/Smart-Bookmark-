<?php
header('Content-Type: application/json');

require_once '../includes/functions.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title']) || !isset($data['url'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Title and URL are required']);
    exit;
}

$title = trim($data['title']);
$url = trim($data['url']);

if (empty($title) || empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid title or URL']);
    exit;
}

if (addBookmark($pdo, $_SESSION['user_id'], $title, $url)) {
    echo json_encode(['success' => true, 'message' => 'Bookmark added successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to add bookmark']);
}
?>
