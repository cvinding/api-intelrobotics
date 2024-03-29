<?php
namespace CONTROLLER;

/**
 * Class Controller
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * This Controller class is used for inheritance. This Controller is the parent of all other controllers.
 * A Controller is a class where all public methods, except a few, are API endpoints.
 * This class helps the other controllers with common methods and a default index().
 */
class Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * An array for all of the endpoint settings
     * @var array $endpointSettings
     */
    private $endpointSettings;

    /**
     * An array of all the valid request methods and the security level.
     * 0 being the lowest security level and 1 being the highest.
     * @var array $validRequestMethods
     */
    private $validRequestMethods = [
        "GET" => 0,
        "POST" => 1
    ];

    /**
     * Last used token
     * @var string $token
     */
    private $token = NULL;

    /**
     * Controller constructor. Used for setting endpoint settings
     * @param array $endpointSettings
     */
    public function __construct(array $endpointSettings = []) {
        // If endpoint settings is empty and the controllers name is NOT CONTROLLER\EndpointController OR CONTROLLER\Controller, create a standard set of settings
        if(empty($endpointSettings) && get_class($this) !== "CONTROLLER\EndpointController" && get_class($this) !== "CONTROLLER\Controller") {
            try {
                // Get all public methods from the calling class
                $class = new \ReflectionClass(get_class($this));
                $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

                foreach($methods as $method) {
                    // Check that they belong to calling class and NOT CONTROLLER\Controller
                    if($method->getDeclaringClass()->getName() === "CONTROLLER\Controller") {
                        continue;
                    }

                    // Check that the method is NOT a magic method
                    if(strpos($method->name, "__") !== false) {
                        continue;
                    }

                    // Create the settings, these settings is unsafe and it is best if the endpoint has its own settings
                    $endpointSettings[$method->getName()] = [
                        "REQUEST_METHOD_LEVEL" => 0,
                        "TOKEN" => false,
                        "PERMISSIONS" => [
                            false
                        ]
                    ];
                }

            } catch (\Exception $exception) {
                exit($exception);
            }

        }

        // Set endpoint settings
        $this->endpointSettings = $endpointSettings;
    }

    /**
     * index() is used for listing the available endpoints
     */
    public function index() {
        try {
            exit(json_encode(["endpoints" => $this->getEndpointList(), "status" => true]));

        } catch (\Exception $exception) {
            $this->exitResponse(500, "Index can not be shown");
        }
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
        if(array_search($model, $bannedModels) !== false) {
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
     * getEndpointList() returns an array of all the available endpoints this controller can offer
     * @return array
     * @throws \ReflectionException
     */
    protected function getEndpointList() : array {
        // Reflect the deprived class and get all PUBLIC methods
        $class = new \ReflectionClass(get_class($this));
        $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);

        $settings = $this->endpointSettings;

        // Create a temporary array
        $tempArray = [];

        // Loop through each PUBLIC method
        foreach($methods as $method){

            // Create a temporary variable
            $temp = [];

            // Check if method name starts with '__' OR method name is 'index' and then filter them out
            // We don't care about the magic function and we also don't care about the index endpoint, because we're already there
            if(strpos($method->name, "__") === 0 || $method->name === "index") {
                continue;
            }

            foreach($settings[$method->name] as $setting => $value) {

                // Check if setting is REQUEST_METHOD_LEVEL and translate the numbers to requests
                if($setting === "REQUEST_METHOD_LEVEL") {
                    $validRequestMethods = $this->validRequestMethods;

                    $requests = "";

                    foreach($validRequestMethods as $name => $level) {
                        if($value <= $level){
                            $requests .= $name.", ";
                        }
                    }

                    $temp["request_methods"] = rtrim($requests, ", ");

                    continue;
                }

                // Set setting equal the value
                $temp[strtolower($setting)] = $value;
            }

            // Check if setting permissions isset and set it to false
            if(!isset($settings[$method->name]["PERMISSIONS"])){
                $temp["permissions"] = false;
            }

            // Loop through the methods parameters and find each parameter type, if any
            foreach ($method->getParameters() as $key => $parameter) {
                $temp["parameters"][$parameter->name] = ($parameter->getType() !== NULL) ? $parameter->getType()->getName() : "not specified";
            }

            // Push the temp. variable into the temp. array
            //array_push($tempArray, $temp);
            $tempArray[$method->name] = $temp;
        }

        // Return the list of endpoints
        return $tempArray;
    }

    /**
     * exitResponse() is used for sending a HTTP response code and exiting with a JSON message
     * @param int $code
     * @param string $message
     */
    protected function exitResponse(int $code, string $message = "") {
        // Standard HTTP responses
        $responses = [
            400 => ["message" => "Error 400: Invalid request", "status" => false],
            403 => ["message" => "Error 403: Forbidden", "status" => false],
            404 => ["message" => "Error 404: Endpoint not found", "status" => false],
            500 => ["message" => "Error 500: Internal server error", "status" => false],
        ];

        // Check if the $code is valid in our array
        if(array_key_exists($code, $responses) !== false) {

            // Set HTTP response code
            http_response_code($code);

            // Exit with a JSON message
            if($message !== ""){
                exit(json_encode(["message" => "{$responses[$code]["message"]}. {$message}", "status" => $responses[$code]['status']]));
            }

            // Exit with standard message
            exit(json_encode($responses[$code]));
        }

        // Send a 500 HTTP error
        $this->exitResponse(500, "Error code and message is setup incorrect");
    }

    /**
     * getEndpointSettings() returns the endpoint settings
     * @return array
     */
    protected function getEndpointSettings() : array {
        return $this->endpointSettings;
    }

    /**
     * getValidRequestMethods() return the valid request methods and their request level
     * @return array
     */
    protected function getValidRequestMethods() : array {
        return $this->validRequestMethods;
    }

    /**
     * setToken() is used to set the currently used token if there are any token used
     * @param string $token
     */
    protected function setToken(string $token) {
        $this->token = $token;
    }

    /**
     * getToken() returns the used token if there are any tokens used
     * @return string
     */
    protected function getToken() : string {
        return $this->token;
    }

    /**
     * A standard function for seeing which controller you're working with
     * @return string
     */
    public function __toString() : string {
        return "Controller name: ".get_class($this);
    }

}