<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'credential.php';
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (isset($users[$login]) && $users[$login] === $password) {
        $_SESSION['user'] = $login;
        header('Location: main.php?page=home');
        exit;
    } else {
        $error = "Invalid login or password.";
    }
}
?>

<h2>Login</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="post">
    <label>Login:
        <input type="text" name="login" required>
    </label>
    <br>
    <label>Password:
        <input type="password" name="password" required>
    </label>
    <br>
    <button type="submit">Log In</button>
</form>

