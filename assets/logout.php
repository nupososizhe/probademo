<?php
require_once __DIR__ . '/config/config.php';
Auth::logout();
header('Location: index.php');
exit;
