<?php
namespace CONTROLLER;


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

        var_dump($endpoint);

        return true;
    }




}