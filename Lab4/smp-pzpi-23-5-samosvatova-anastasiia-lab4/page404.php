<?php
echo "<h2>404 — Page Not Found</h2>";

if (!isset($_SESSION['user'])) {
    echo "<p>You are not authorized. Please <a href='main.php?page=login'>log in</a> to access this page.</p>";
} else {
    echo "<p>The page you are looking for doesn't exist.</p>
          <p><a href='main.php?page=home'>Back to Home</a></p>";
}
?>
