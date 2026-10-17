<?php
require __DIR__.'/../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare(
        'SELECT id, username, password_hash FROM admins WHERE username = ?'
    );

    $stmt->execute([trim($_POST['username'] ?? '')]);
    $a = $stmt->fetch();

    if ($a && ($_POST['password'] ?? '') === $a['password_hash']) {
        session_regenerate_id(true);

        $_SESSION['admin_id'] = $a['id'];
        $_SESSION['admin_name'] = $a['username'];

        header('Location: categories.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>

<!doctype html>
<html>
<body>
    <h1>Admin Login</h1>

    <p style="color:red">
        <?= h($error) ?>
    </p>

    <form method="post">
        <label>
            Username
            <input name="username" required>
        </label>
        <br>

        <label>
            Password
            <input type="password" name="password" required>
        </label>
        <br>

        <button type="submit">Login</button>
    </form>
</body>
</html>