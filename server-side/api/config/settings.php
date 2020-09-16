<?php

// Error reporting for production
//error_reporting(0);
//ini_set('display_errors', '0');

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Settings
$settings = [];

// Database settings
$settings['db'] = [
    'driver' => 'mysql',
    'host' => '172.16.1.29',
    'username' => 'jobsity',
    'database' => 'jobsity',
    'password' => 'jobsity1234',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'flags' => [
        // Turn off persistent connections
        PDO::ATTR_PERSISTENT => false,
        // Enable exceptions
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Emulate prepared statements
        PDO::ATTR_EMULATE_PREPARES => true,
        // Set default fetch mode to array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Set character set
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
    ],
];


// Path settings
$settings['root'] = dirname(__DIR__);
$settings['temp'] = $settings['root'] . '/tmp';
$settings['public'] = $settings['root'] . '/public';


// Powered by <a href="https://www.amdoren.com">Amdoren</a>
$settings['amdoren_apikey'] = "cA6F7w8WdkruAzb2D8HtRwMyjDXyae";

// Powered by Fixer.io
$settings['fixer_apikey'] = "8116664379236ef575cba840ccabbaf8";

// Error Handling Middleware settings
$settings['error'] = [

    // Should be set to false in production
    'display_error_details' => true,

    // Parameter is passed to the default ErrorHandler
    // View in rendered output by enabling the "displayErrorDetails" setting.
    // For the console and unit tests we also disable it
    'log_errors' => true,

    // Display error details in error log
    'log_error_details' => true,
];


$settings['session'] = [
    'name' => 'webapp',
    'cache_expire' => 0,
];

return $settings;


