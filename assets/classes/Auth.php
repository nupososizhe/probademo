<?php
declare(strict_types=1);

class Auth
{
    public static function userId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }

    public static function userName(): ?string
    {
        return $_SESSION['user_name'] ?? null;
    }

    public static function isUser(): bool
    {
        return self::userId() !== null;
    }

    public static function isAdmin(): bool
    {
        return ($_SESSION['is_admin'] ?? false) === true;
    }

    public static function requireUser(): void
    {
        if (!self::isUser()) {
            header('Location: login.php');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header('Location: admin_login.php');
            exit;
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
    }
}
