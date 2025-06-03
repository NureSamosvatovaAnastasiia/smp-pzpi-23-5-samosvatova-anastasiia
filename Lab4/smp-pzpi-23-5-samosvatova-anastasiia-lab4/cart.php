<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $removeId = (int)$_POST['remove_id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->execute([$removeId]);
    header('Location: ?page=cart');
    exit;
}


$stmt = $pdo->query("SELECT cart.id AS cart_id, products.id AS product_id, products.name, products.cost, cart.quantity
                     FROM cart
                     JOIN products ON cart.product_id = products.id");
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Your Cart</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>Your Basket</header>

<main>
<h2>Cart</h2>

<?php if (!empty($cartItems)): ?>
<table>
<thead>
  <tr>
    <th>Product</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Sum</th>
    <th></th>
  </tr>
</thead>
<tbody>
<?php
$total = 0;
foreach ($cartItems as $item):
    $lineSum = $item['cost'] * $item['quantity'];
    $total += $lineSum;
?>
<tr>
  <td><?= htmlspecialchars($item['name']) ?></td>
  <td>$<?= number_format($item['cost'], 2) ?></td>
  <td><?= $item['quantity'] ?></td>
  <td>$<?= number_format($lineSum, 2) ?></td>
  <td>
    <form method="post" style="display:inline">
      <input type="hidden" name="remove_id" value="<?= $item['cart_id'] ?>">
      <button type="submit">Remove</button>
    </form>
  </td>
</tr>
<?php endforeach; ?>
<tr>
  <th colspan="3">Total</th>
  <th colspan="2">$<?= number_format($total, 2) ?></th>
</tr>
</tbody>
</table>

<?php else: ?>
<p>Your basket is empty. <a href="main.php?page=products">Go to catalog</a></p>

<?php endif; ?>

</main>

</body>
</html>
