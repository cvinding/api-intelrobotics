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
     * @param int $room_id
     * @param float $temperature
     * @param float $humidity
     * @param int $format
     */
    public function addTemperature(int $room_id, float $temperature, float $humidity, int $format = 1) {
        $this->setRequestMethodLevel(1);
        try {

            /**
             * @var \MODEL\TemperatureModel $model
             */
            $model = $this->getModel("TemperatureModel");

            // Add temperature
            $response = $model->addTemperature($room_id, $temperature, $humidity, $format);

            if(!$response) {
                //TODO: maybe another code
                $this->exitResponse(500, "Unable to insert new temperature record");
            }

            exit(json_encode(["message" => "Success! A new temperature record has been added", "status" => true]));


        } catch (\Exception $exception) {
            $this->exitResponse(500, "Unable to insert new temperature record");
        }
    }

    /**
     * @param float $temperature
     * @param int $format
     * //TODO: skulle det være INSERT eller UPDATE?
     */
    public function setDefaultTemperature(float $temperature, int $format = 1) {
        $this->setRequestMethodLevel(1);
        try {
            /**
             * @var \MODEL\TemperatureModel $model
             */
            $model = $this->getModel("TemperatureModel");

            $result = $model->setDefaultTemperature($temperature, $format);

            if(!$result) {
                exit(json_encode(["message" => "The requested temperature is already set", "status" => false]));
            }

            exit(json_encode(["message" => "You have set a new default temperature", "status" => true]));

        } catch (\Exception $exception){
            $this->exitResponse(500,"Unable to set the default temperature");
        }

    }

    /**
     * getDefaultTemperature() is an endpoint for
     */
    public function getDefaultTemperature() {
        $this->setRequestMethodLevel();
        try {
            /**
             * @var \MODEL\TemperatureModel $model
             */
            $model = $this->getModel("TemperatureModel");

            // Get default temperature
            $response = $model->getDefaultTemperature();

            // Set response to true
            $response["status"] = true;

            exit(json_encode($response));

        } catch (\Exception $exception){
            $this->exitResponse(500,"Unable to select the room's temperature");
        }
    }

    /**
     * getRoomTemperature() is an endpoint that returns all the id's of all the available rooms
     * @param int $id
     */
    public function getRoomTemperature(int $id) {
        $this->setRequestMethodLevel();
        try {
            /**
             * @var \MODEL\TemperatureModel $model
             */
            $model = $this->getModel("TemperatureModel");

            exit(json_encode(["room" => $model->getRoomTemperatureById($id), "status" => true]));

        } catch (\Exception $exception){
            $this->exitResponse(500,"Unable to select the room's temperature");
        }
    }

}