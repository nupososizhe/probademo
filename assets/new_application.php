<?php
require_once __DIR__ . '/config/config.php';
Auth::requireUser();

$db = (new Database())->pdo();
$roomsRepo = new RoomRepository($db);
$appRepo = new ApplicationRepository($db);

$rooms = $roomsRepo->all();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = (int)($_POST['room_id'] ?? 0);
    $conferenceDate = trim($_POST['conference_date'] ?? '');
    $paymentMethod = trim($_POST['payment_method'] ?? '');

    if (!$roomsRepo->exists($roomId)) {
        $errors['room_id'] = 'Выберите помещение.';
    }

    if (!Validator::conferenceDate($conferenceDate)) {
        $errors['conference_date'] = 'Введите сегодняшнюю или будущую дату.';
    }

    $allowedPayments = ['Очное посещение', 'СБП'];
    if (!in_array($paymentMethod, $allowedPayments, true)) {
        $errors['payment_method'] = 'Выберите способ оплаты.';
    }

    if (!$errors) {
        $appRepo->create(
            Auth::userId(),
            $roomId,
            $conferenceDate,
            $paymentMethod
        );

        $_SESSION['flash'] = 'Заявка отправлена администратору.';
        header('Location: applications.php');
        exit;
    }
}

$title = 'Новая заявка';
require __DIR__ . '/partials_header.php';
?>
<section class="form-card">
    <h1>Новая заявка</h1>

    <form method="post">
        <label>
            Помещение
            <select name="room_id" required>
                <option value="">Выберите помещение</option>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?= (int)$room['id'] ?>"
                        <?= ((int)($_POST['room_id'] ?? 0) === (int)$room['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($room['type'] . ' — ' . $room['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['room_id'])): ?>
                <span class="error"><?= htmlspecialchars($errors['room_id']) ?></span>
            <?php endif; ?>
        </label>

        <label>
            Дата начала конференции
            <input type="date" name="conference_date" required
                   min="<?= date('Y-m-d') ?>"
                   value="<?= htmlspecialchars($_POST['conference_date'] ?? '') ?>">
            <?php if (isset($errors['conference_date'])): ?>
                <span class="error"><?= htmlspecialchars($errors['conference_date']) ?></span>
            <?php endif; ?>
        </label>

        <label>
            Способ оплаты
            <select name="payment_method" required>
                <option value="">Выберите способ оплаты</option>
                <option value="Очное посещение"
                    <?= (($_POST['payment_method'] ?? '') === 'Очное посещение') ? 'selected' : '' ?>>
                    При очном посещении
                </option>
                <option value="СБП"
                    <?= (($_POST['payment_method'] ?? '') === 'СБП') ? 'selected' : '' ?>>
                    Переводом по системе СБП
                </option>
            </select>
            <?php if (isset($errors['payment_method'])): ?>
                <span class="error"><?= htmlspecialchars($errors['payment_method']) ?></span>
            <?php endif; ?>
        </label>

        <button class="btn" type="submit">Отправить</button>
    </form>
</section>
<?php require __DIR__ . '/partials_footer.php'; ?>
