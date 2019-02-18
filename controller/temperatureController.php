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
     * @param float $temperature
     * @param float $humidity
     * @param int $format
     */
    public function addTemperature(float $temperature, float $humidity, int $format = 1) {
        $this->setRequestMethodLevel(1);
        exit(json_encode(["message" => "New temperature: {$temperature} has been inserted as {$format}. New humid: {$humidity}", "status" => true]));
    }

    /**
     *
     */
    public function getDefaultTemperature() {
        $this->setRequestMethodLevel();
        exit(json_encode(["temperature" => 24, "format" => "celsius", "status" => true]));
    }

    /**
     * @param float $temperature
     * @param int $format
     */
    public function setDefaultTemperature(float $temperature, int $format = 1) {
        $this->setRequestMethodLevel(1);
        exit(json_encode(["message" => "You have set a new default temperature", "status" => true]));
    }

}