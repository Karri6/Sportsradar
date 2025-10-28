<?php

// Database configuration from environment variables
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'sportscalendar');
define('DB_USER', getenv('DB_USER') ?: 'username');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'password');
define('DB_CHARSET', 'utf8mb4');

// Application settings
define('SITE_NAME', 'Sports Calendar');
define('DATE_FORMAT', 'd.m.Y');
define('TIME_FORMAT', 'H:i');
date_default_timezone_set('Europe/Vienna');

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>