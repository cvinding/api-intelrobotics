<?php
namespace CONTROLLER;

class EndpointController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * EndpointController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Used for getting the endpoint
     * @param string $request
     */
    public function getEndpoint(string $request){

        // Check if the $request variable is empty
        if(strlen($request) <= 0){

            http_response_code(400);

            exit(json_encode(["message" => "Invalid request. Requested endpoint is not specified"]));
        }

        $rawRequest = explode("/", $request);

        // Check if the $rawRequest is at least 2 parts long.
        // 1. part is 'api'
        // 2. part is the controllers name
        if(sizeof($rawRequest) < 2) {
            http_response_code(400);

            exit(json_encode(["message" => "Invalid request. Requested endpoint is missing configuration"]));
        }

        // Check if the 1. part of the request is 'api'
        if($rawRequest[0] !== "api"){
            http_response_code(400);

            exit(json_encode(["message" => "Invalid request. Requested endpoint is configured incorrect"]));
        }

        // Create the path to the endpoint folder
        $controllerPath = "../";

        $loop = ((sizeof($rawRequest) - 1) === 2) ? sizeof($rawRequest) - 1 : sizeof($rawRequest);

        for($i = 0; $i < $loop; $i++) {
            $controllerPath .= $rawRequest[$i]."/";
        }

        // file_exists() for finding directories only works on UNIX systems, because a folder is just a file unlike Windows
        if (!file_exists($controllerPath) && !is_dir($controllerPath)){
            http_response_code(404);

            exit(json_encode(["message" => "Endpoint does not exist"]));
        }

        $endpoint = "{$controllerPath}index.php";

        http_response_code(200);

        // Set the endpoint controller if it exists
        try {
            $controller = $this->setController(explode("/", $endpoint));

        } catch (\Exception $exception) {
            exit($exception);
        }

        require $endpoint;

    }

    /**
     * Used for automatically setting a Controller for a endpoint
     * @param array $endpoint
     * @return object|\CONTROLLER\Controller
     * @throws \Exception \ReflectionException
     */
    private function setController(array $endpoint) {
        $bannedControllers = ["CONTROLLER\\Controller","CONTROLLER\\EndpointController"];

        // Create the Controller from the $endpoint array
        $controller = "CONTROLLER\\".ucfirst($endpoint[sizeof($endpoint) - 2])."Controller";

        // Check if the Controller is banned
        if(array_search($controller,$bannedControllers) !== false) {
            Throw new \Exception("This endpoint is setup with a banned controller. {$controller} can not be used for controlling the endpoints");
        }

        // Check if the $controller exists
        if(!class_exists($controller)) {
            Throw new \Exception("{$controller} cannot be called because it does not exist");
        }

        // Use the ReflectionClass to check and instance the Controller
        $class = new \ReflectionClass($controller);

        // Check if the called class is deprived from the CONTROLLER\Controller class
        if(!isset($class->getParentClass()->name) && $class->getParentClass()->name !== "CONTROLLER\\Controller") {
            Throw new \Exception("{$controller} class is not a deprived class of CONTROLLER\\Controller");
        }

        // Return the Controller instance
        return $class->newInstance();
    }

}