<?php
namespace CONFIG;

/**
 * Token configurations
 * NO SECRETS IN THIS CONFIG
 */
return [

    // Claims set to true is required for our tokens
    "CLAIMS" => [

        // Issuer
        "iss" => "www.indeklima-api.local",

        // Subject
        "sub" => "User Authorization Token",

        // Audience
        "aud" => "www.indeklima-api.local",

        // Expires, e.g. time() + 3600 for 1 hour
        "exp" => [
            "required" => true,
            "default" => function (int $offset) {
                return time() + $offset;
            }
        ],

        // Not before, e.g. time() + 3600 for 1 hour
        "nbf" => [
            "required" => true,
            "default" => function (int $offset) {
                return time() + $offset;
            }
        ],

        // Issued at, e.g. time()
        "iat" => [
            "required" => true,
            "default" => function () {
                return time();
            }
        ],

        // JWT id, needs to be unique so maybe a db primary key?
        "jti" => [
            "required" => true
        ],
    ]

];