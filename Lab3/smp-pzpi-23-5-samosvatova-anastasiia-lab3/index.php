<?php
session_start();
require_once 'db.php';

$countStmt = $pdo->query("SELECT COUNT(id) FROM cart");
$cartCount = $countStmt->fetchColumn();
if ($cartCount === null) $cartCount = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PHP Shop — Main</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>PHP Shop</header>
<nav>
  <a href="index.php">Home</a>
  <a href="product.php">Catalog</a>
  <a href="cart.php">Basket (<?= $cartCount ?>)</a>
</nav>
<main>
<h1>Welcome to PHP Shop!</h1>
<p>Check our <a href="product.php">product catalog</a> and add items to your basket.</p>
</main>
<footer>© <?= date('Y') ?> PHP Shop.</footer>
</body>
</html>
