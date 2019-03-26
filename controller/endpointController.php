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
     * getEndpoint() is used for checking if the request is correctly send and setup, before then returning an endpoint
     * @param string $request
     */
    public function getEndpoint(string $request) {
        // Check if request method is OPTIONS and send a 200 http response code
        // Used for keeping the Preflighted requests in check ;)
        //TODO: do something else with the Preflighted requests
        if($_SERVER['REQUEST_METHOD'] === "OPTIONS"){
            http_response_code(200);
            exit();
        }

        // Check if the request method is valid
        if(array_key_exists($_SERVER["REQUEST_METHOD"], $this->getValidRequestMethods()) === false){
            $this->exitResponse(400, "Illegal request method, only GET or POST is allowed");
        }

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

        // Get the controller
        $controller = $this->getController($controllerName);

        // Get all HTTP headers
        $headers = $this->getRequestHeaders();

        // Get which endpoint we have to call
        $action = (isset($rawRequest[2]) && strlen($rawRequest[2]) > 0) ? $rawRequest[2] : "index";

        // All the GET and POST parameters
        $parameters = $this->getParameters($rawRequest, isset($rawRequest[3]));

        // Check if the method exists and how many parameters the method takes if any
        $args = $this->getMethodParameters($controller, $action);

        // Check endpoint settings if endpoint is not "index"
        if($action !== "index") {
            // Get the endpoint's settings
            $endpointSettings = $controller->getEndpointSettings();

            // Check if the settings exists (they should)
            if(array_key_exists($action, $endpointSettings)){

                $settings = $endpointSettings[$action];
                $token = NULL;

                // Check the REQUEST_METHOD_LEVEL
                $this->checkRequestMethodLevel($settings["REQUEST_METHOD_LEVEL"]);

                // Check if a token is needed for the endpoint
                if($settings["TOKEN"]) {
                    $token = $this->checkToken($headers);
                }

                // Check if a token is needed AND permissions is not set to false
                if($settings["TOKEN"] && isset($settings["PERMISSIONS"])){
                    $this->checkPermissions($token, $settings["PERMISSIONS"]);
                }

            }else {
                exit($this->exitResponse(500, "Endpoint settings configured incorrect"));
            }

        }

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
     * @return string
     */
    private function checkToken(array $headers) : string {
        try {
            /**
             * @var \MODEL\AuthModel $auth
             */
            $auth = $this->getModel("AuthModel");

        } catch (\Exception $exception) {
            exit($exception);
        }

        // Check if token isset
        if(!isset($headers["HTTP_AUTHORIZATION"])) {
            $this->exitResponse(400, "Missing authorization token");
        }

        // Separate the "Bearer" part and the "Token" part of the Authorization header
        $authorization = explode(" ", $headers["HTTP_AUTHORIZATION"]);

        try {
            // Check if token is valid
            if(!$auth->validateToken($authorization[1])){
                $this->exitResponse(403, "The endpoint you are trying to access is restricted");
            }

        } catch (\Exception $exception){
            $this->exitResponse(403, "Invalid token used");
        }

        return $authorization[1];
    }

    /**
     * checkPermissions() is used to check if the token has all required permissions
     * @param string $token
     * @param array $permissions
     */
    private function checkPermissions(string $token, array $permissions) {
        try {
            /**
             * @var \MODEL\AuthModel $auth
             */
            $auth = $this->getModel("AuthModel");

        } catch (\Exception $exception) {
            exit($exception);
        }

        // Get the token claims
        $claims = $auth->getTokenClaim($token, "security_groups");

        var_dump($claims);

        foreach($permissions as $permission) {
            // If any of the claims match any of the endpoints permission, give them access
            if(array_search($permission, $claims) !== false) {
                return;
            }
        }

        // Return forbidden code if the user has no valid permissions
        exit($this->exitResponse(403));
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
        $decodedParameters = json_decode($data, true);

        // Check if data was valid JSON
        if(json_last_error() === JSON_ERROR_NONE){
            return $decodedParameters;
        }

        // Return $_POST if raw JSON data is invalid
        return $_POST;
    }

    /**
     * getRequestHeaders() is used for returning all the request headers
     * @return array
     */
    private function getRequestHeaders() : array {
        $headers = [];

        foreach($_SERVER as $key => $value) {
            if(strpos($key, 'HTTP') !== false) {
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    /**
     * setRequestMethodLevel() is used for setting a security level to the endpoint.
     * This enables you to disable a request method that is only meant for reading data for example GET.
     * @param int $level
     */
    private function checkRequestMethodLevel(int $level = 0) {
        // Check if the request method is allowed in this endpoint and if not tell the user
        if($this->getValidRequestMethods()[$_SERVER["REQUEST_METHOD"]] < $level){
            $this->exitResponse(400, "{$_SERVER["REQUEST_METHOD"]} requests is not allowed on this endpoint");
        }
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