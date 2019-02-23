<?php declare(strict_types=1);
namespace PUBLIC_HTML;

// Error debugging made easier ;)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Require the autoloader class
require_once "../autoloader.php";

// Allow all our own website to see the API content
$origin = (isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : false;

if($origin) {
    $allowed_domains = require "../config/access_list.php";

    if (in_array($origin, $allowed_domains)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    }
}

// All content is JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: Authorization, Content-Type");

// Start the autoloader class
\Autoloader::register();




// Start the API
$endpoint = new \CONTROLLER\EndpointController();
$endpoint->getEndpoint($_GET['endpoint']);