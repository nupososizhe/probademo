<?php
require_once __DIR__ . '/config/config.php';
Auth::requireUser();

$db = (new Database())->pdo();
$appRepo = new ApplicationRepository($db);
$apps = $appRepo->getForUser(Auth::userId());

$title = 'Мои заявки';
require __DIR__ . '/partials_header.php';
?>
<section class="page-head">
    <div>
        <span class="eyebrow">Личный кабинет</span>
        <h1>Мои заявки</h1>
    </div>
    <a class="btn" href="new_application.php">Создать заявку</a>
</section>

<?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert">
        <?= htmlspecialchars($_SESSION['flash']) ?>
        <?php unset($_SESSION['flash']); ?>
    </div>
<?php endif; ?>

<?php if (!$apps): ?>
    <div class="empty">У вас пока нет заявок.</div>
<?php endif; ?>

<div class="cards">
    <?php foreach ($apps as $app): ?>
        <article class="card">
            <div class="card-top">
                <div>
                    <span class="badge"><?= htmlspecialchars($app['room_type']) ?></span>
                    <h2><?= htmlspecialchars($app['room_name']) ?></h2>
                </div>
                <span class="status"><?= htmlspecialchars($app['status']) ?></span>
            </div>

            <dl>
                <div><dt>Дата</dt><dd><?= htmlspecialchars($app['conference_date']) ?></dd></div>
                <div><dt>Оплата</dt><dd><?= htmlspecialchars($app['payment_method']) ?></dd></div>
                <div><dt>Создана</dt><dd><?= htmlspecialchars($app['created_at']) ?></dd></div>
            </dl>

            <?php if ($app['review_id']): ?>
                <div class="review">
                    <strong>Ваш отзыв: <?= (int)$app['rating'] ?>/5</strong>
                    <p><?= nl2br(htmlspecialchars($app['comment'])) ?></p>
                </div>
            <?php elseif ($app['status'] === 'Завершено'): ?>
                <a class="btn btn-secondary" href="review.php?id=<?= (int)$app['id'] ?>">
                    Оставить отзыв
                </a>
            <?php else: ?>
                <p class="muted">Отзыв станет доступен после завершения мероприятия.</p>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>
<?php require __DIR__ . '/partials_footer.php'; ?>
