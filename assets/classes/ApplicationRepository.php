<?php
declare(strict_types=1);

class ApplicationRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(
        int $userId,
        int $roomId,
        string $conferenceDate,
        string $paymentMethod
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO applications
             (user_id, room_id, conference_date, payment_method, status)
             VALUES (?, ?, ?, ?, "Новая")'
        );
        $stmt->execute([$userId, $roomId, $conferenceDate, $paymentMethod]);
        return (int)$this->pdo->lastInsertId();
    }

    public function getForUser(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.*, r.name AS room_name, r.type AS room_type,
                    rev.id AS review_id, rev.rating, rev.comment
             FROM applications a
             JOIN rooms r ON r.id = a.room_id
             LEFT JOIN reviews rev ON rev.application_id = a.id
             WHERE a.user_id = ?
             ORDER BY a.created_at DESC'
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAll(?string $status = null): array
    {
        $sql =
            'SELECT a.*, u.login, u.full_name, u.phone, u.email,
                    r.name AS room_name, r.type AS room_type
             FROM applications a
             JOIN users u ON u.id = a.user_id
             JOIN rooms r ON r.id = a.room_id';

        $params = [];
        if ($status && in_array($status, ['Новая', 'Мероприятие назначено', 'Завершено'], true)) {
            $sql .= ' WHERE a.status = ?';
            $params[] = $status;
        }

        $sql .= ' ORDER BY a.created_at DESC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $applicationId, string $status): void
    {
        $allowed = ['Новая', 'Мероприятие назначено', 'Завершено'];
        if (!in_array($status, $allowed, true)) {
            throw new InvalidArgumentException('Недопустимый статус.');
        }

        $stmt = $this->pdo->prepare('UPDATE applications SET status = ? WHERE id = ?');
        $stmt->execute([$status, $applicationId]);
    }

    public function userOwnsCompletedApplication(int $applicationId, int $userId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM applications
             WHERE id = ? AND user_id = ? AND status = "Завершено"'
        );
        $stmt->execute([$applicationId, $userId]);
        return (int)$stmt->fetchColumn() === 1;
    }
}
