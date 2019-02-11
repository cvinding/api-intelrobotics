<?php
namespace CONTROLLER;

/**
 * Class Controller
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * This Controller class is used for inheritance. This Controller is the parent of all other controllers.
 * The class set the controllers model so it is easier to create a new instance of.
 */
class Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    protected $endpoints;

    /**
     * Controller constructor.
     * @param array $endpoints
     */
    public function __construct(array $endpoints = []){
        $this->endpoints = $endpoints;
    }

    /**
     * Used by deprived Controller classes for getting a Model
     * @param string $id
     * @param array $parameters
     * @return object|\MODEL\Model
     * @throws \Exception \ReflectionException
     */
    protected function getModel(string $id, array $parameters = []) {
        $bannedModels = ["MODEL\\Model"];

        // Check if the $id contains 'MODEL\', if it does use the $id else add 'MODEL\' to the $id variable
        //TODO: make this strpos more flexible, maybe use regex
        $model = (strpos($id, 'MODEL\\') !== false) ? $id : "MODEL\\{$id}";

        // Check if this function is trying to call a banned Model
        if(array_search($model,$bannedModels) !== false) {
            Throw new \Exception("{$model} has been banned and cannot be called");
        }

        // Check if the $model exists
        if(!class_exists($model)) {
            Throw new \Exception("{$model} cannot be called because it does not exist");
        }

        // Use reflection to get an instance of the Model
        $class = new \ReflectionClass($model);

        // Check if the called class is deprived from the MODEL\Model class
        if(!isset($class->getParentClass()->name) && $class->getParentClass()->name !== "MODEL\\Model") {
            Throw new \Exception("{$model} class is not a deprived class of MODEL\\Model");
        }

        // Check if $parameters isset and return an instance of the class, with or without parameters
        return (!empty($parameters)) ? $class->newInstanceArgs($parameters) : $class->newInstance();
    }

    /**
     * Get all valid endpoints
     * @return array
     */
    protected function getEndpoints() : array {
        return $this->endpoints;
    }


    public function __toString() : string {
        return "Controller name: ".get_class($this);
    }

}