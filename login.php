<?php
require_once __DIR__ . '/config/config.php';

$db = (new Database())->pdo();
$users = new UserRepository($db);

$error = '';
$login = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $users->findByLogin($login);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = 'Неверный логин или пароль.';
    } else {
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        unset($_SESSION['is_admin']);

        header('Location: applications.php');
        exit;
    }
}

$title = 'Авторизация';
require __DIR__ . '/partials_header.php';
?>
<section class="form-card">
    <h1>Авторизация</h1>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>
            Логин
            <input name="login" required value="<?= htmlspecialchars($login) ?>">
        </label>
        <label>
            Пароль
            <input type="password" name="password" required>
        </label>
        <button class="btn" type="submit">Войти</button>
    </form>

    <p class="muted">Еще не зарегистрированы? <a href="register.php">Регистрация</a></p>
</section>
<?php require __DIR__ . '/partials_footer.php'; ?>
