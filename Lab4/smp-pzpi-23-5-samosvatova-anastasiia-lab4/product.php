<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productIds = $_POST['product_id'] ?? [];
    $quantities = $_POST['quantity'] ?? [];

    foreach ($productIds as $index => $productId) {
        $qty = (int)($quantities[$index] ?? 0);
        $productId = (int)$productId;

        if ($qty > 0) {
           
            $stmt = $pdo->prepare("INSERT INTO cart (product_id, quantity) VALUES (?, ?)");
            $stmt->execute([$productId, $qty]);
        }
    }
    header('Location: ?page=cart');
    exit;
}


$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

$countStmt = $pdo->query("SELECT COUNT(*) FROM cart");
$cartCount = $countStmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Catalog</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<main>
<h2>Products</h2>

<form method="post">
  <div class="products-list">
    <?php foreach ($products as $product): ?>
    <div class="product-item">
      <div><?= htmlspecialchars($product['name']) ?> — $<?= number_format($product['cost'], 2) ?></div>
      <input type="hidden" name="product_id[]" value="<?= $product['id'] ?>">
      <input type="number" name="quantity[]" min="0" value="0">
    </div>
    <?php endforeach; ?>
  </div>
  <button type="submit">Add Selected to Cart</button>
</form>

</main>

</body>
</html>
