<?php
declare(strict_types=1);

session_start();

const DB_HOST = '127.0.0.1';
const DB_PORT = '3306';
const DB_NAME = 'probademo';
const DB_USER = 'root';
const DB_PASS = '';

const ADMIN_LOGIN = 'Conf2027';
const ADMIN_PASSWORD = 'Demo77';

spl_autoload_register(function (string $class): void {
    $path = __DIR__ . '/../classes/' . $class . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});