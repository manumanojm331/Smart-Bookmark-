<?php
require_once 'includes/auth.php';

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    
    $oauth2 = new Google\Service\Oauth2($client);
    $userInfo = $oauth2->userinfo->get();
    
    authenticateUser($pdo, [
        'id' => $userInfo->id,
        'email' => $userInfo->email,
        'name' => $userInfo->name
    ]);
    
    header('Location: index.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>
