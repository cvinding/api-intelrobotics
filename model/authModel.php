<?php
namespace MODEL;

/**
 * All these require statements is for the library made by Rob Waller,
 * please take a look in the '../libs/reallysimplejwt/' folder for licensing and a README about the library he has supplied.
 */
// Exceptions
require_once("../libs/reallysimplejwt/src/Exception/ValidateException.php");

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
        /*
        $db = new \DATABASE\Database();

        $data = $db->query("SELECT * FROM room")->fetchArray();

        var_dump($data);
        */

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
            "jti" => "t22",                         // JWT id
            "uid" => "u22"
        ];


        var_dump($this->generateSecret(12));


        //$token = \ReallySimpleJWT\Token::create($userId, $secret, $expiration, $issuer);

        try {
            $token = \ReallySimpleJWT\Token::customPayload($config, $secret);
        } catch (\Exception $exception) {
            exit($exception);
        }





        return $token; //$token;
    }

    public function generateSecret(int $length = 12) : string {
        $letters = "abcdefghijklmnopqrstuvwxyz";

        $numbers = "0123456789";

        $specialChars = "*&!@%^#$";

        $characters = [
            [
                "value" => $letters,
                "used" => 0
            ],
            [
                "value" =>strtoupper($letters),
                "used" => 0
            ],
            [
                "value" => $numbers,
                "used" => 0
            ],
            [
                "value" => $specialChars,
                "used" => 0
            ],
        ];


        $secret = "";

        for($i = 0; $i < $length; $i++){

            $random = rand(0,3);

            if($usedOnce[$random] === 0) {
                $secret .= $characters[$random][rand(0, strlen($characters[$random]) - 1)];
                $usedOnce[$random]++;
            }else {

            }

        }



        return $secret;
    }

}