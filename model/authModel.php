<?php
namespace MODEL;

/**
 * All these require statements is for the library made by Rob Waller,
 * please take a look in the '../libs/reallysimplejwt/' folder for licensing and a README about the library he has supplied.
 */
// Traits
require_once("../libs/reallysimplejwt/src/Helper/JsonEncoder.php");

// Interfaces
require_once("../libs/reallysimplejwt/src/Interfaces/EncodeInterface.php");

// Classes
require_once("../libs/reallysimplejwt/src/Token.php");
require_once("../libs/reallysimplejwt/src/Build.php");
require_once("../libs/reallysimplejwt/src/Encode.php");
require_once("../libs/reallysimplejwt/src/Jwt.php");
require_once("../libs/reallysimplejwt/src/Parse.php");
require_once("../libs/reallysimplejwt/src/Parsed.php");
require_once("../libs/reallysimplejwt/src/Validate.php");


/**
 * Class AuthModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * //TODO: description needed
 */
class AuthModel extends Model implements \MODEL\_IMPLEMENTS\Model {

    public function createJWT() {

        $db = new \DATABASE\Database();

        $db->query("SELECT * FROM temperature");


        // Secret signing key TOP SECRET DO NOT SHARE THE KEY
        $secret = require "../config/secret.php";

        // How many seconds before being
        $notBefore = 10;

        // Expires after 1 hour
        $expiration = 3600 + $notBefore;

        // Custom config
        $config = [
            "iss" => "www.indeklima-api.local",     // Issuer
            "sub" => "User Authorization Token",    // Subject
            "aud" => "www.indeklima-api.local",     // Audience
            "exp" => time() + $expiration,          // Expires
            "nbf" => time() + $notBefore,           // Not usable before
            "iat" => time(),                        // Issued at
            "jti" => ""                             // JWT id
        ];


        $userId = 12;


        //$token = \ReallySimpleJWT\Token::create($userId, $secret, $expiration, $issuer);

        //$token = \ReallySimpleJWT\Token::customPayload($config, $secret);




        return 0; //$token;
    }

}