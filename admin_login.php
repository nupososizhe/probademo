<?php
require_once __DIR__ . '/config/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === ADMIN_LOGIN && hash_equals(ADMIN_PASSWORD, $password)) {
        $_SESSION['is_admin'] = true;
        unset($_SESSION['user_id'], $_SESSION['user_name']);

        header('Location: admin.php');
        exit;
    }

    $error = 'Неверный логин или пароль администратора.';
}

$title = 'Вход администратора';
require __DIR__ . '/partials_header.php';
?>
<section class="form-card">
    <h1>Вход администратора</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>
            Логин
            <input name="login" required>
        </label>
        <label>
            Пароль
            <input type="password" name="password" required>
        </label>
        <button class="btn" type="submit">Войти</button>
    </form>
</section>
<?php require __DIR__ . '/partials_footer.php'; ?>
