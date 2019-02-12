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

    protected $useToken; 

    protected $endpoints;

    /**
     * Controller constructor.
     * @param bool $useToken
     */
    public function __construct(bool $useToken = false){
        $this->useToken = $useToken;
    }

    /**
     * index() is used for listing the available endpoints
     */
    public function index() {
        exit(json_encode(["Endpoints" => $this->getEndpoints(), "status" => true]));
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
     * @return array
     * @throws \ReflectionException
     */
    protected function getEndpoints() : array {
        $class = new \ReflectionClass(get_class($this));
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

        $returnArray = [];

        foreach($methods as $method){

            $temp = [];

            if(strpos($method->name, "__") !== false || $method->name === "index") {
                continue;
            }

            $temp["name"] = $method->name;

            foreach ($method->getParameters() as $key => $parameter) {
                $temp["parameter"]["$parameter->name"] = $parameter->getType()->getName();
            }



           // $temp["parameters"] = $parameters;

            //$temp["type"] = $parameters[0]->getType();


/*            foreach($parameters as $parameter) {
                $temp["parameters"][] = $parameter->name;
            }
*/

            array_push($returnArray, $temp);
        }

        return $returnArray;
    }

    /**
     *
     * @return string
     */
    public function __toString() : string {
        return "Controller name: ".get_class($this);
    }

}