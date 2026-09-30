<?php
declare(strict_types=1);

class UserRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function findByLogin(string $login): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE login = ? LIMIT 1');
        $stmt->execute([$login]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(
        string $login,
        string $password,
        string $fullName,
        string $phone,
        string $email
    ): int {
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (login, password_hash, full_name, phone, email)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $login,
            password_hash($password, PASSWORD_DEFAULT),
            $fullName,
            $phone,
            $email
        ]);

        return (int)$this->pdo->lastInsertId();
    }
}
