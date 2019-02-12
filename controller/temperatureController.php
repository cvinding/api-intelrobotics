<?php
namespace CONTROLLER;

/**
 * Class TemperatureController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * //TODO: description needed
 */
class TemperatureController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * TemperatureController constructor.
     */
    public function __construct() {
        parent::__construct(true);
    }

    /**
     * @param int $temperature
     * @param int $format
     * @param int $humid
     */
    public function addTemperature(int $temperature, int $format, int $humid) {
        exit(json_encode(["message" => "New temperature: {$temperature} has been inserted as {$format}. New humid: {$humid}", "status" => true]));
    }

    public function getDefaultTemperature() {
        exit(json_encode(["temperature" => ""]));
    }


}