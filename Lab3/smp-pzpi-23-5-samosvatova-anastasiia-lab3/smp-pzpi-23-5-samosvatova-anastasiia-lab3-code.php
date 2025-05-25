<?php
session_start();

$itemsList = [
    1 => ['name' => 'Avocado', 'cost' => 1.50],
    2 => ['name' => 'Almond Milk', 'cost' => 3.20],
    3 => ['name' => 'Greek Yogurt', 'cost' => 2.00],
    4 => ['name' => 'Organic Honey', 'cost' => 5.75],
    5 => ['name' => 'Black Coffee', 'cost' => 2.80],
    6 => ['name' => 'Green Tea', 'cost' => 2.10],
    7 => ['name' => 'Blueberry Muffin', 'cost' => 1.90],
    8 => ['name' => 'Fresh Orange Juice', 'cost' => 3.50],
    9 => ['name' => 'Dark Chocolate', 'cost' => 4.25],
    10 => ['name' => 'Sparkling Water', 'cost' => 1.10],
];


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

function addToCart($productId, $amount) {
    if ($amount <= 0) return;
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] += $amount;
    } else {
        $_SESSION['cart'][$productId] = $amount;
    }
}

function removeFromCart($productId) {
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_items']) && is_array($_POST['qtys'])) {
        foreach ($_POST['qtys'] as $pid => $qty) {
            $pid = (int)$pid;
            $qty = (int)$qty;
            if (array_key_exists($pid, $itemsList)) {
                addToCart($pid, $qty);
            }
        }
        header('Location: ?view=cart');
        exit;
    }
}

if (isset($_GET['remove'])) {
    $removeId = (int)$_GET['remove'];
    removeFromCart($removeId);
    header('Location: ?view=cart');
    exit;
}

$view = $_GET['view'] ?? 'home';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Simple Shop</title>
<style>
  body {
    margin: 0; padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #1f1f1f;
    color: #eee;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }
  header, footer {
    background: #111;
    padding: 20px;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: 2px;
    box-shadow: 0 2px 10px #000;
  }
  nav {
    display: flex;
    justify-content: center;
    gap: 30px;
    background: #222;
    padding: 15px 0;
  }
  nav a {
    color: #aaa;
    text-decoration: none;
    font-weight: 600;
    font-size: 18px;
  }
  nav a.active, nav a:hover {
    color: #4caf50;
    text-decoration: underline;
  }
  main {
    max-width: 800px;
    margin: 30px auto;
    background: #2c2c2c;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px #000;
    flex-grow: 1;
  }
  h1,h2 {
    text-align: center;
    margin-bottom: 30px;
  }
  /* Catalog vertical list */
  .products-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .product-item {
    background: #3a3a3a;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 0 8px #111;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .product-info {
    display: flex;
    flex-direction: column;
  }
  .product-name {
    font-weight: 700;
    margin-right: 10px;
    font-size: 20px;
  }
  .product-price {
    font-size: 18px;
    color: #8fbc8f;
  }
  .product-qty {
    width: 60px;
    padding: 6px;
    font-size: 16px;
    border-radius: 6px;
    border: none;
    margin-right: 15px;
  }
  .add-button {
    background-color: #4caf50;
    border: none;
    padding: 10px 20px;
    color: #eee;
    font-weight: 700;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.25s ease;
  }
  .add-button:hover {
    background-color: #369636;
  }
  /* Cart table */
  table {
    width: 100%;
    border-collapse: collapse;
    background: #3a3a3a;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 10px #000;
  }
  th, td {
    padding: 15px 10px;
    text-align: center;
  }
  th {
    background: #4caf50;
    color: #fff;
  }
  tr:nth-child(even) {
    background: #2c2c2c;
  }
  a.remove-link {
    color: #f44336;
    font-weight: 700;
    text-decoration: none;
  }
  a.remove-link:hover {
    text-decoration: underline;
  }
  .empty-cart {
    text-align: center;
    font-size: 22px;
    padding: 60px 0;
    color: #bbb;
  }
</style>
</head>
<body>

<header>Simple PHP Shop</header>
<nav>
  <a href="?view=home" class="<?= $view === 'home' ? 'active' : '' ?>">Home</a>
  <a href="?view=products" class="<?= $view === 'products' ? 'active' : '' ?>">Catalog</a>
  <a href="?view=cart" class="<?= $view === 'cart' ? 'active' : '' ?>">Basket (<?= array_sum($_SESSION['cart']) ?>)</a>
</nav>

<main>

<?php if ($view === 'home'): ?>
    <h1>Welcome to Simple PHP Shop!</h1>
    <p style="text-align:center; font-size: 18px; color:#aaa;">Check out our <a href="?view=products" style="color:#4caf50;">product catalog</a> and add items to your basket!</p>

<?php elseif ($view === 'products'): ?>
    <h2>Product Catalog</h2>
    <form method="post" action="?view=products">
        <div class="products-list">
        <?php foreach ($itemsList as $id => $product): ?>
            <div class="product-item">
                <div class="product-info">
                    <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
                    <div class="product-price">$<?= number_format($product['cost'], 2) ?></div>
                </div>
                <div>
                    <input type="number" name="qtys[<?= $id ?>]" class="product-qty" min="0" value="0" aria-label="Quantity for <?= htmlspecialchars($product['name']) ?>" />
                    <button type="submit" name="add_items" class="add-button" value="1">Add</button>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </form>

<?php elseif ($view === 'cart'): ?>
    <h2>Your Basket</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Sum</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $totalSum = 0;
                foreach ($_SESSION['cart'] as $pid => $qty):
                    $prod = $itemsList[$pid];
                    $lineTotal = $prod['cost'] * $qty;
                    $totalSum += $lineTotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($prod['name']) ?></td>
                    <td>$<?= number_format($prod['cost'], 2) ?></td>
                    <td><?= $qty ?></td>
                    <td>$<?= number_format($lineTotal, 2) ?></td>
                    <td><a href="?view=cart&remove=<?= $pid ?>" class="remove-link" title="Remove <?= htmlspecialchars($prod['name']) ?>">X</a></td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <th colspan="3" style="text-align:right;">Total:</th>
                    <th colspan="2">$<?= number_format($totalSum, 2) ?></th>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty-cart">Your basket is empty. Visit the <a href="?view=products" style="color:#4caf50;">catalog</a> to add items.</p>
    <?php endif; ?>

<?php else: ?>
    <h1>404 — Not Found</h1>
    <p style="text-align:center;"><a href="?view=home" style="color:#4caf50;">Return Home</a></p>
<?php endif; ?>

</main>

<footer>© <?= date('Y') ?> PHP Shop. All rights reserved.</footer>

</body>
</html>
