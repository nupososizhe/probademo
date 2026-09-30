<?php
require_once __DIR__ . '/config/config.php';
Auth::requireAdmin();

$db = (new Database())->pdo();
$appRepo = new ApplicationRepository($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicationId = (int)($_POST['application_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    try {
        $appRepo->updateStatus($applicationId, $status);
        $_SESSION['flash'] = 'Статус обновлен.';
    } catch (Throwable $e) {
        $_SESSION['flash'] = 'Не удалось обновить статус.';
    }

    $redirectStatus = urlencode($_GET['status'] ?? '');
    header('Location: admin.php' . ($redirectStatus ? '?status=' . $redirectStatus : ''));
    exit;
}

$filter = trim($_GET['status'] ?? '');
$applications = $appRepo->getAll($filter ?: null);

$title = 'Панель администратора';
require __DIR__ . '/partials_header.php';
?>
<section class="page-head">
    <div>
        <span class="eyebrow">Администрирование</span>
        <h1>Все заявки</h1>
    </div>
</section>

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert">
        <?= htmlspecialchars($_SESSION['flash']) ?>
        <?php unset($_SESSION['flash']); ?>
    </div>
<?php endif; ?>

<form class="filter" method="get">
    <label>
        Фильтр по статусу
        <select name="status" onchange="this.form.submit()">
            <option value="">Все</option>
            <?php foreach (['Новая', 'Мероприятие назначено', 'Завершено'] as $status): ?>
                <option value="<?= htmlspecialchars($status) ?>"
                    <?= $filter === $status ? 'selected' : '' ?>>
                    <?= htmlspecialchars($status) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</form>

<div class="cards">
    <?php foreach ($applications as $app): ?>
        <article class="card">
            <div class="card-top">
                <div>
                    <span class="badge"><?= htmlspecialchars($app['room_type']) ?></span>
                    <h2><?= htmlspecialchars($app['room_name']) ?></h2>
                </div>
                <span class="status"><?= htmlspecialchars($app['status']) ?></span>
            </div>

            <dl>
                <div><dt>Пользователь</dt><dd><?= htmlspecialchars($app['full_name']) ?> (<?= htmlspecialchars($app['login']) ?>)</dd></div>
                <div><dt>Телефон</dt><dd><?= htmlspecialchars($app['phone']) ?></dd></div>
                <div><dt>Email</dt><dd><?= htmlspecialchars($app['email']) ?></dd></div>
                <div><dt>Дата</dt><dd><?= htmlspecialchars($app['conference_date']) ?></dd></div>
                <div><dt>Оплата</dt><dd><?= htmlspecialchars($app['payment_method']) ?></dd></div>
            </dl>

            <form method="post" class="status-form">
                <input type="hidden" name="application_id" value="<?= (int)$app['id'] ?>">
                <select name="status">
                    <?php foreach (['Новая', 'Мероприятие назначено', 'Завершено'] as $status): ?>
                        <option value="<?= htmlspecialchars($status) ?>"
                            <?= $app['status'] === $status ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="btn btn-small" type="submit">Сохранить</button>
            </form>
        </article>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/partials_footer.php'; ?>
