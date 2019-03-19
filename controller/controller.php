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
     * A variable for holding the request method used for accessing the endpoint
     * @var string $requestMethod
     */
    private $requestMethod;

    /**
     * Important variable for checking if the endpoint is secured with a token
     * @var bool $useToken
     */
    private $useToken;


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


    public function __construct(array $endpointSettings = []) {

        if(empty($endpointSettings) && (get_class($this) !== "CONTROLLER\EndpointController" && get_class($this) !== "CONTROLLER\Controller")) {

            try {
                $class = new \ReflectionClass(get_class($this));
                $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);


                foreach($methods as $method) {
                    var_dump($method);
                }


            } catch (\Exception $exception) {
                exit($exception);
            }

        }


        foreach($endpointSettings as $endpoint => $settings) {

            foreach($settings as $name => $setting) {

                var_dump($name);
                var_dump($setting);
            }

        }


        //$this->useToken = $useToken;
        $this->requestMethod = $_SERVER["REQUEST_METHOD"];
    }

    /**
     * index() is used for listing the available endpoints
     */
    public function index() {
        $this->setRequestMethodLevel();

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

            $temp["name"] = $method->name;

            // Loop through the methods parameters and find each parameter type, if any
            foreach ($method->getParameters() as $key => $parameter) {
                $temp["parameters"][$parameter->name] = ($parameter->getType() !== NULL) ? $parameter->getType()->getName() : "not specified";
            }

            // Push the temp. variable into the temp. array
            array_push($tempArray, $temp);
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
     * setRequestMethodLevel() is used for setting a security level to the endpoint.
     * This enables you to disable a request method that is only meant for reading data for example GET.
     * @param int $level
     */
    protected function setRequestMethodLevel(int $level = 0) {
        // Check if the request method is allowed in this endpoint and if not tell the user
        if($this->validRequestMethods[$this->requestMethod] < $level){
            $this->exitResponse(400, "{$this->requestMethod} requests is not allowed on this endpoint");
        }
    }

    /**
     * getRequestMethod() returns the request method used
     * @return string
     */
    protected function getRequestMethod() : string {
        return $this->requestMethod;
    }

    /**
     * getValidRequestMethods() returns all valid request methods and the request methods security level
     * @return array
     */
    protected function getValidRequestMethods() : array {
        return $this->validRequestMethods;
    }

    /**
     * useToken() returns bool, true if a token is needed to access the endpoint
     * @return bool
     */
    protected function useToken() : bool {
        return $this->useToken;
    }

    /**
     * A standard function for seeing which controller you're working with
     * @return string
     */
    public function __toString() : string {
        return "Controller name: ".get_class($this);
    }

}