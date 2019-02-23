<?php
namespace controller;

/**
 * Class LocationController
 * @package controller
 * @author Christian Vinding Rasmussen
 */
class LocationController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * LocationController constructor.
     */
    public function __construct() {
        parent::__construct(true);
    }

    /**
     * getRoom() is an endpoint used for returning a list of all rooms available
     */
    public function getRooms() {
        $this->setRequestMethodLevel();
        try {
            /**
             * @var \MODEL\LocationModel
             */
            $model = $this->getModel("LocationModel");



            exit(json_encode(["rooms" => $model->getRooms(), "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

}