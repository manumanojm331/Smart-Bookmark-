<?php
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Bookmark App</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📚 Smart Bookmark Manager</h1>
            <?php if (isLoggedIn()): ?>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                    <a href="logout.php" class="btn btn-logout">Logout</a>
                </div>
            <?php endif; ?>
        </header>

        <main>
            <?php if (!isLoggedIn()): ?>
                <div class="login-prompt">
                    <h2>Welcome to Smart Bookmark Manager</h2>
                    <p>Please login with Google to start managing your bookmarks</p>
                    <a href="login.php" class="btn btn-google">
                        <img src="https://www.google.com/favicon.ico" alt="Google">
                        Login with Google
                    </a>
                </div>
            <?php else: ?>
                <div class="bookmark-manager">
                    <div class="add-bookmark">
                        <h2>Add New Bookmark</h2>
                        <form id="bookmarkForm">
                            <input type="text" id="title" placeholder="Bookmark Title" required>
                            <input type="url" id="url" placeholder="https://example.com" required>
                            <button type="submit" class="btn btn-add">Add Bookmark</button>
                        </form>
                    </div>

                    <div class="bookmarks-list">
                        <h2>Your Bookmarks</h2>
                        <div id="bookmarksContainer">
                            <!-- Bookmarks will be loaded here -->
                            <p class="loading">Loading bookmarks...</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php if (isLoggedIn()): ?>
    <script>
        const userId = <?php echo $_SESSION['user_id']; ?>;
    </script>
    <script src="js/app.js"></script>
    <?php endif; ?>
</body>
</html>
