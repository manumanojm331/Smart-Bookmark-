<?php
require_once __DIR__ . '/../config/database.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserBookmarks($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function addBookmark($pdo, $user_id, $title, $url) {
    $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, title, url) VALUES (?, ?, ?)");
    return $stmt->execute([$user_id, $title, $url]);
}

function deleteBookmark($pdo, $bookmark_id, $user_id) {
    $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE id = ? AND user_id = ?");
    return $stmt->execute([$bookmark_id, $user_id]);
}
?>
