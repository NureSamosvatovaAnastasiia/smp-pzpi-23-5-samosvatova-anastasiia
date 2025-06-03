<?php
session_destroy();
header('Location: main.php?page=login');
exit;
?>
