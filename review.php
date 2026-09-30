<?php
require_once __DIR__ . '/config/config.php';
Auth::requireUser();

$db = (new Database())->pdo();
$appRepo = new ApplicationRepository($db);
$reviewRepo = new ReviewRepository($db);

$applicationId = (int)($_GET['id'] ?? $_POST['application_id'] ?? 0);

if (!$appRepo->userOwnsCompletedApplication($applicationId, Auth::userId())) {
    http_response_code(403);
    exit('Отзыв можно оставить только к своей завершенной заявке.');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $errors['rating'] = 'Оценка должна быть от 1 до 5.';
    }

    if ($comment === '') {
        $errors['comment'] = 'Введите текст отзыва.';
    }

    if (!$errors) {
        try {
            $reviewRepo->create($applicationId, Auth::userId(), $rating, $comment);
            $_SESSION['flash'] = 'Отзыв сохранен.';
            header('Location: applications.php');
            exit;
        } catch (PDOException $e) {
            $errors['common'] = 'Отзыв для этой заявки уже существует.';
        }
    }
}

$title = 'Оставить отзыв';
require __DIR__ . '/partials_header.php';
?>
<section class="form-card">
    <h1>Оставить отзыв</h1>

    <?php if (isset($errors['common'])): ?>
        <div class="alert alert-error"><?= htmlspecialchars($errors['common']) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="application_id" value="<?= $applicationId ?>">

        <label>
            Оценка
            <select name="rating" required>
                <option value="">Выберите оценку</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
            <?php if (isset($errors['rating'])): ?>
                <span class="error"><?= htmlspecialchars($errors['rating']) ?></span>
            <?php endif; ?>
        </label>

        <label>
            Комментарий
            <textarea name="comment" rows="5" required><?= htmlspecialchars($_POST['comment'] ?? '') ?></textarea>
            <?php if (isset($errors['comment'])): ?>
                <span class="error"><?= htmlspecialchars($errors['comment']) ?></span>
            <?php endif; ?>
        </label>

        <button class="btn" type="submit">Сохранить отзыв</button>
    </form>
</section>
<?php require __DIR__ . '/partials_footer.php'; ?>
