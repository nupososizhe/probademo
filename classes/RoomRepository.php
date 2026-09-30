<?php
declare(strict_types=1);

class RoomRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo->query(
            'SELECT * FROM rooms ORDER BY type, name'
        )->fetchAll();
    }

    public function exists(int $id): bool
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM rooms WHERE id = ?');
        $stmt->execute([$id]);
        return (int)$stmt->fetchColumn() === 1;
    }
}
