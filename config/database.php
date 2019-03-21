<?php
namespace CONFIG;

/**
 * Database configurations
 */
return [

    "HOSTNAME" => "localhost",

    "PORT" => 3306,

    "DATABASE" => "intelrobotics_api",

    "CHARSET" => "utf8mb4",

    "USERNAME" => "root",

    "PASSWORD" => "",

    "OPTIONS" => [
        // Useful for debugging, more error messages
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,

        // Always fetch associate arrays
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,

        // Secure prepared statements
        \PDO::ATTR_EMULATE_PREPARES => false,
    ]

];