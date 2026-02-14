<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

function authenticateUser($pdo, $google_user) {
    $google_id = $google_user['id'];
    $email = $google_user['email'];
    $name = $google_user['name'];
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = ? OR email = ?");
    $stmt->execute([$google_id, $email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Update existing user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
    } else {
        // Create new user
        $stmt = $pdo->prepare("INSERT INTO users (google_id, email, name) VALUES (?, ?, ?)");
        $stmt->execute([$google_id, $email, $name]);
        
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
    }
}
?>
