<?php
namespace CONTROLLER;

/**
 * Class EndpointController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * The EndpointController class is most unique controller.
 * EndpointController is one of the only controllers to not be a controller for an endpoint.
 * This class handles which endpoints the user sees, also checks tokens, parameters etc.
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

        // Check if the request method is valid
        if(array_key_exists($this->getRequestMethod(), $this->getValidRequestMethods()) === false){
            $this->exitResponse(400, "Illegal request method, only GET or POST is allowed");
        }

        // Name of the controller/endpoint
        $controllerName = $rawRequest[1];

        // Get the controller
        $controller = $this->getController($controllerName);

        // Get all HTTP headers
        $headers = apache_request_headers();

        // Check if the $controller is secured behind tokens
        if($controller->useToken()) {
            $this->checkToken($headers);
        }

        // Which endpoint/method we have to call
        $action = (isset($rawRequest[2]) && strlen($rawRequest[2]) > 0) ? $rawRequest[2] : "index";

        // All the GET and POST parameters
        $parameters = $this->getParameters($rawRequest, isset($rawRequest[3]));

        // Check if the method exists and how many parameters the method takes if any
        $args = $this->getMethodParameters($controller, $action);

        // If the parameters send does not match the number it takes send an exitCode
        // Else if the number of required arguments is 0, forget that any parameters where sent
        if(sizeof($parameters) < sizeof($args)){
            $this->exitResponse(400, "Missing request arguments");
        }elseif(sizeof($args) === 0) {
            $parameters = [];
        }

        // Call the endpoint
        call_user_func_array([$controller, $action], $parameters);

        // Send a 200 HTTP response
        http_response_code(200);
    }

    /**
     * checkToken() is used to check if the 'Authorization' header is set and
     * @param array $headers
     */
    private function checkToken(array $headers) {
        try {
            /**
             * @var \MODEL\AuthModel $auth
             */
            $auth = $this->getModel("AuthModel");

        } catch (\Exception $exception) {
            exit($exception);
        }

        // Check if token isset
        if(!isset($headers["Authorization"])) {
            $this->exitResponse(400, "Missing authorization token");
        }



        $authorization = explode(" ", $headers["Authorization"]);

        var_dump($authorization);

        try {
            // Check if token is valid
            if(!$auth->validateToken($authorization[1])){
                $this->exitResponse(403, "The endpoint you are trying to access is restricted");
            }

        } catch (\Exception $exception){
            $this->exitResponse(403, "Invalid token used");
        }
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

        // Check if REQUEST_METHOD is GET and if there is supplied GET parameters
        if($this->getRequestMethod() === "GET" && $isGET){

            $parameters = [];
            for($i = 3; $i < sizeof($request); $i++) {
                array_push($parameters, $request[$i]);
            }

            return $parameters;
        }

        // Get raw JSON data
        $data = file_get_contents("php://input");

        // Decode JSON data
        $decodedParameters = json_decode($data, true);

        // Check if data was valid JSON
        if(json_last_error() === JSON_ERROR_NONE){
            return $decodedParameters;
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
    private function getMethodParameters(\CONTROLLER\Controller $controller, string $method) : array {
        try {
            $method = new \ReflectionMethod($controller, $method);

        } catch (\Exception $exception) {
            $this->exitResponse(404);
        }

        // Return the parameters
        return $method->getParameters();
    }

}