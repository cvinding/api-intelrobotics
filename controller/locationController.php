<?php
namespace controller;


class LocationController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * LocationController constructor.
     */
    public function __construct() {
        parent::__construct(true);
    }

    /**
     * //TODO: comment
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