<?php
namespace CONTROLLER;

class EndpointController {

    public function getEndpoint(string $request){

        if(strlen($request) <= 0){
            http_response_code(404);

            die(json_encode(["message" => "Endpoint is not specified!"]));
        }

        $file = "../".$request.".php";

        if(!file_exists($file)){
            http_response_code(404);

            die(json_encode(["message" => "Endpoint does not exist!"]));
        }

        http_response_code(200);

        require $file;
    }

}