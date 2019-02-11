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
            "addTemperature",
            "getManuallySetTemperature",
            "setManuallySetTemperature"
        ]);
    }

    public function endpoint(string $endpoint) : bool {

        return true;
    }


    public function addTemperature() {

    }


}