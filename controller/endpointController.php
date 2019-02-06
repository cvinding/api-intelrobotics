<?php
namespace CONTROLLER;

class EndpointController {

    public function getEndpoint(string $request){

        // Check if the $request variable is empty
        if(strlen($request) <= 0){
            //TODO: brug en anden HTTP kode?
            http_response_code(404);

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
      //  var_dump($request);
//var_dump(strpos($endpoint, '?'));

        if(strpos($endpoint, '?') !== false){
            die( "get GOT!");
        }

        // Check if the requested endpoint exists
        if(!file_exists($endpoint)){
            http_response_code(404);

            die(json_encode(["message" => "Endpoint does not exist!"]));
        }

        http_response_code(200);

        require $endpoint;
    }



}