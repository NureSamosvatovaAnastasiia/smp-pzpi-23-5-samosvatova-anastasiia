<?php
$cartCount = 0;
if (isset($_SESSION['user'])) {
    $stmt = $pdo->prepare("SELECT COUNT(id) as total FROM cart");
    $stmt->execute();
    $cartCount = $stmt->fetchColumn() ?: 0;
}
?>
<header>
  <h1>Web-магазин</h1>
  <nav>
    <a href="main.php?page=home">Home</a>
    <a href="main.php?page=products">Catalog</a>
    <a href="main.php?page=cart">Basket (<?= $cartCount ?>)</a>
    <?php if (isset($_SESSION['user'])): ?>
      <a href="main.php?page=profile">Profile</a>
      <a href="main.php?page=logout">Logout</a>
    <?php else: ?>
      <a href="main.php?page=login">Login</a>
    <?php endif; ?>
  </nav>
</header>
