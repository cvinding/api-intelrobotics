<?php declare(strict_types=1);
namespace PUBLIC_HTML;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once "../autoloader.php";


header('Content-Type: application/json');

\Autoloader::register();

//echo $_GET['endpoint'];

//echo "<br>";


$endpoint = new \CONTROLLER\EndpointController();


//echo add(2,3);
$endpoint->getEndpoint($_GET['endpoint']);
//echo json_encode(['test' => ['lol']]);

//te