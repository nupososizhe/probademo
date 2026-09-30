<?php
require_once __DIR__ . '/config/config.php';

$db = (new Database())->pdo();
$users = new UserRepository($db);

$errors = [];
$values = [
    'login' => '',
    'full_name' => '',
    'phone' => '',
    'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['login'] = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $values['full_name'] = trim($_POST['full_name'] ?? '');
    $values['phone'] = trim($_POST['phone'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');

    if (!Validator::login($values['login'])) {
        $errors['login'] = 'Логин: только латиница и цифры, минимум 6 символов.';
    } elseif ($users->findByLogin($values['login'])) {
        $errors['login'] = 'Такой логин уже существует.';
    }

    if (!Validator::password($password)) {
        $errors['password'] = 'Пароль должен содержать минимум 8 символов.';
    }

    if (!Validator::fullName($values['full_name'])) {
        $errors['full_name'] = 'ФИО: только кириллица и пробелы.';
    }

    if (!Validator::phone($values['phone'])) {
        $errors['phone'] = 'Формат телефона: 8(XXX)XXX-XX-XX.';
    }

    if (!Validator::email($values['email'])) {
        $errors['email'] = 'Введите корректный адрес электронной почты.';
    }

    if (!$errors) {
        $userId = $users->create(
            $values['login'],
            $password,
            $values['full_name'],
            $values['phone'],
            $values['email']
        );

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $values['full_name'];

        header('Location: applications.php');
        exit;
    }
}

$title = 'Регистрация';
require __DIR__ . '/partials_header.php';
?>
<section class="form-card">
    <h1>Регистрация</h1>

    <form method="post" novalidate>
        <label>
            Логин
            <input name="login" required minlength="6"
                   pattern="[A-Za-z0-9]+"
                   value="<?= htmlspecialchars($values['login']) ?>">
            <?php if (isset($errors['login'])): ?>
                <span class="error"><?= htmlspecialchars($errors['login']) ?></span>
            <?php endif; ?>
        </label>

        <label>
            Пароль
            <input type="password" name="password" required minlength="8">
            <?php if (isset($errors['password'])): ?>
                <span class="error"><?= htmlspecialchars($errors['password']) ?></span>
            <?php endif; ?>
        </label>

        <label>
            ФИО
            <input name="full_name" required
                   value="<?= htmlspecialchars($values['full_name']) ?>">
            <?php if (isset($errors['full_name'])): ?>
                <span class="error"><?= htmlspecialchars($errors['full_name']) ?></span>
            <?php endif; ?>
        </label>

        <label>
            Телефон
            <input name="phone" required placeholder="8(999)123-45-67"
                   value="<?= htmlspecialchars($values['phone']) ?>">
            <?php if (isset($errors['phone'])): ?>
                <span class="error"><?= htmlspecialchars($errors['phone']) ?></span>
            <?php endif; ?>
        </label>

        <label>
            Электронная почта
            <input type="email" name="email" required
                   value="<?= htmlspecialchars($values['email']) ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="error"><?= htmlspecialchars($errors['email']) ?></span>
            <?php endif; ?>
        </label>

        <button class="btn" type="submit">Зарегистрироваться</button>
    </form>

    <p class="muted">Уже зарегистрированы? <a href="login.php">Авторизация</a></p>
</section>
<?php require __DIR__ . '/partials_footer.php'; ?>
