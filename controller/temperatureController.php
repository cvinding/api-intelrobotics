<?php
namespace CONTROLLER;

/**
 * Class TemperatureController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 */
class TemperatureController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * TemperatureController constructor.
     */
    public function __construct() {
        parent::__construct([
            "addTemperature" => ["newTemperature", "format", "newHumid"],
            "getManuallySetTemperature",
            "setManuallySetTemperature"
        ]);
    }

    /**
     * index() is used for listing the TemperatureController's endpoints
     */
    public function index() {
        exit(json_encode(["Endpoints" => $this->getEndpoints(), "status" => true]));
    }

    /**
     * @param int $newTemperature
     * @param string $format
     * @param int $newHumid
     */
    public function addTemperature(int $newTemperature, string $format, int $newHumid) {
        exit(json_encode(["message" => "New temperature: {$newTemperature} has been inserted as {$format}. New humid: {$newHumid}", "status" => true]));
    }


}