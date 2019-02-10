<?php
namespace CONTROLLER;

class EndpointController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    public function getEndpoint(string $request){

        // Check if the $request variable is empty
        if(strlen($request) <= 0){
            //TODO: brug en anden HTTP kode?
            http_response_code(400);

            die(json_encode(["message" => "Endpoint is not specified!"]));
        }

        $endpoint = "../{$request}.php";

        $endpointParts = explode("/", $endpoint);

        // Check if the $endpoint is pointing to the correct directory with all the endpoints
        if($endpointParts[1] !== "api"){
            //TODO: brug en anden HTTP kode?
            http_response_code(404);

            die(json_encode(["message" => "Endpoint does not exist!"]));
        }

        // Check if the requested endpoint exists
        if(!file_exists($endpoint)){
            http_response_code(404);

            die(json_encode(["message" => "Endpoint does not exist!"]));
        }

        http_response_code(200);

        // Set the endpoint controller if it exists
        $this->setController($endpointParts);

        require $endpoint;
    }

    private function setController(array $endpoint) : bool {

        array_splice($endpoint,0,2);

        $controller = "CONTROLLER\\";

        for($i = 0; $i < sizeof($endpoint) - 1; $i++) {
            $controller .= ucfirst($endpoint[$i]);
        }

        //$controller .= ucfirst($endpoint[sizeof($endpoint) - 1]);

        var_dump($controller);

        //TODO: set a controller for the requested endpoint

        return true;
    }

}