<?php
require_once __DIR__ . '/config/config.php';
$title = 'Конференции.РФ';
require __DIR__ . '/partials_header.php';
?>
<section class="hero">
    <div>
        <span class="eyebrow">Портал бронирования</span>
        <h1>Помещения для Всероссийских конференций</h1>
        <p>Выберите аудиторию, коворкинг или кинозал, укажите дату и способ оплаты.</p>
        <div class="actions">
            <?php if (Auth::isUser()): ?>
                <a class="btn" href="new_application.php">Создать заявку</a>
                <a class="btn btn-secondary" href="applications.php">Мои заявки</a>
            <?php else: ?>
                <a class="btn" href="register.php">Зарегистрироваться</a>
                <a class="btn btn-secondary" href="login.php">Войти</a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php require __DIR__ . '/partials_footer.php'; ?>
