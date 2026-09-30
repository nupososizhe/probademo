<?php
declare(strict_types=1);

class ReviewRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(int $applicationId, int $userId, int $rating, string $comment): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reviews (application_id, user_id, rating, comment)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$applicationId, $userId, $rating, $comment]);
    }
}
