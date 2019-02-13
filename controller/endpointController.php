<?php
namespace CONTROLLER;

/**
 * Class EndpointController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * //TODO: Description needed
 */
class EndpointController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * EndpointController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * index() should never be used
     * @deprecated this function is NEVER called
     */
    public function index() {
        exit(json_encode(["message" => "How did you get here? :)", "status" => true]));
    }

    /**
     * getEndpoint() is used for getting the endpoint
     * @param string $request
     */
    public function getEndpoint(string $request) {
        // Check if the $request variable is empty
        if(strlen($request) <= 0){
            $this->exitResponse(400, "Endpoint not specified");
        }

        $rawRequest = explode("/", $request);

        // Check if the 1. part of the request is 'api'
        if($rawRequest[0] !== "api" || !isset($rawRequest[1])){
            $this->exitResponse(400, "Endpoint not specified");
        }

        // Name of the controller/endpoint
        $controllerName = $rawRequest[1];

        // Which method we have to call
        $action = (isset($rawRequest[2]) && strlen($rawRequest[2]) > 0) ? $rawRequest[2] : "index";

        // All the GET and POST parameters
        $parameters = $this->getParameters($rawRequest, isset($rawRequest[3]));

        // Get the controller
        $controller = $this->getController($controllerName);

        // Check if the method exists and how many parameters the method takes if any
        $args = $this->methodParameters($controller, $action);

        // If the parameters send does not match the number it takes send an exitCode
        if(sizeof($parameters) !== sizeof($args)){
            $this->exitResponse(400, "Missing request arguments");
        }

        // Call the endpoint
        call_user_func_array([$controller, $action], $parameters);

        // Send a 200 HTTP response
        http_response_code(200);
    }

    /**
     * getParameters() checks if the REQUEST_METHOD is GET or POST.
     * Then checks if it has been supplied with GET parameters.
     * If there is no GET parameters, assume there is raw JSON data send with a POST request
     * If there is no raw JSON data, return $_POST
     * @param array $request
     * @param bool $isGET
     * @return array
     */
    private function getParameters(array $request = [], bool $isGET = false) : array {
        $validRequestMethods = ["GET", "POST"];

        if(array_search($_SERVER["REQUEST_METHOD"], $validRequestMethods) === false){
            $this->exitResponse(400, "Illegal request method, only GET or POST is allowed");
        }

        // Check if REQUEST_METHOD is GET and if there is supplied GET parameters
        if($_SERVER["REQUEST_METHOD"] === "GET" && $isGET){

            $parameters = [];
            for($i = 3; $i < sizeof($request); $i++) {
                array_push($parameters, $request[$i]);
            }

            return $parameters;
        }

        // Get raw JSON data
        $data = file_get_contents("php://input");

        // Decode JSON data
        $decoded = json_decode($data, true);

        // Check if data was valid JSON
        if(json_last_error() === JSON_ERROR_NONE){
            return $decoded;
        }

        // Return $_POST if raw JSON data is invalid
        return $_POST;
    }

    /**
     * getController() is used for setting the requested endpoint
     * @param string $name
     * @return \CONTROLLER\Controller
     */
    private function getController(string $name) : \CONTROLLER\Controller {
        $bannedControllers = ["CONTROLLER\\Controller","CONTROLLER\\EndpointController"];

        // Create the Controller from the $endpoint array
        $controller = "CONTROLLER\\".ucfirst($name)."Controller";

        // Check if the Controller is banned
        if(array_search($controller,$bannedControllers) !== false) {
            $this->exitResponse(404);
        }

        // Check if the $controller exists
        if(!class_exists($controller)) {
            $this->exitResponse(404);
        }

        // Return the Controller instance
        return new $controller();
    }

    /**
     * methodParameters() checks if the method exists and how many parameters it takes if any
     * @param \CONTROLLER\Controller $controller
     * @param string $method
     * @return \ReflectionParameter[]
     */
    private function methodParameters(\CONTROLLER\Controller $controller, string $method) : array {
        try {
            $method = new \ReflectionMethod($controller, $method);

        } catch (\Exception $exception) {
            $this->exitResponse(404);
        }

        // Return the parameters
        return $method->getParameters();
    }

}