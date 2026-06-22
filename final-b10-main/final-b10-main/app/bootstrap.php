<?php
declare(strict_types=1);

date_default_timezone_set('Asia/Taipei');

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

if (!defined('APP_NAME')) {
    define('APP_NAME', '西式甜點商場');
}

session_name('dessert_mall');
session_start();

require_once APP_ROOT . '/app/helpers.php';

init_storage();