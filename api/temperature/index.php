<?php
namespace API\TEMPERATURE;

/**
 * $controller \CONTROLLER\TemperatureController
 * $endpoint
 */

$endpointList = $controller->getEndpoints();

$endpoint = $controller->endpoint($endpoint);







echo json_encode(["available" => $endpointList]);



