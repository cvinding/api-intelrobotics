<?php declare(strict_types=1);
namespace PUBLIC_HTML;

// Error debugging made easier ;)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Require the autoloader class
require_once "../autoloader.php";

// All content is JSON
header('Content-Type: application/json');

// Start the autoloader class
\Autoloader::register();

// Start the API
$endpoint = new \CONTROLLER\EndpointController();
$endpoint->getEndpoint($_GET['endpoint']);