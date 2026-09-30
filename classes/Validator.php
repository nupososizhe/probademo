<?php
declare(strict_types=1);

class Validator
{
    public static function login(string $value): bool
    {
        return (bool)preg_match('/^[A-Za-z0-9]{6,}$/', $value);
    }

    public static function password(string $value): bool
    {
        return mb_strlen($value) >= 8;
    }

    public static function fullName(string $value): bool
    {
        return (bool)preg_match('/^[А-Яа-яЁё ]+$/u', trim($value));
    }

    public static function phone(string $value): bool
    {
        return (bool)preg_match('/^8\(\d{3}\)\d{3}-\d{2}-\d{2}$/', $value);
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function conferenceDate(string $value): bool
    {
        $date = DateTime::createFromFormat('Y-m-d', $value);
        if (!$date || $date->format('Y-m-d') !== $value) {
            return false;
        }
        $today = new DateTime('today');
        return $date >= $today;
    }
}
