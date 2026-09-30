<?php
declare(strict_types=1);
$title = $title ?? 'Конференции.РФ';
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container nav">
        <a class="brand" href="index.php">Конференции.РФ</a>
        <nav>
            <?php if (Auth::isUser()): ?>
                <a href="applications.php">Мои заявки</a>
                <a href="new_application.php">Новая заявка</a>
                <a href="logout.php">Выйти</a>
            <?php elseif (Auth::isAdmin()): ?>
                <a href="admin.php">Админ-панель</a>
                <a href="logout.php">Выйти</a>
            <?php else: ?>
                <a href="login.php">Войти</a>
                <a href="register.php">Регистрация</a>
                <a href="admin_login.php">Администратор</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
