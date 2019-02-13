<?php
namespace CONFIG;

/**
 * Database configurations
 */
return [

    "HOSTNAME" => "localhost",

    "DATABASE" => "indeklima_api",

    "CHARSET" => "utf8mb4",

    "USERNAME" => "phpmyadmin",

    "PASSWORD" => "admin",

    "OPTIONS" => [
        // Useful for debugging, more error messages
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,

        // Always fetch associate arrays
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,

        // Secure prepared statements
        \PDO::ATTR_EMULATE_PREPARES => false,
    ]

];