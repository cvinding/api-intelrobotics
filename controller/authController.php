<?php
namespace CONTROLLER;
/*
// Interfaces
require_once("../libs/jwt/src/Signer.php");
require_once("../libs/jwt/src/Claim.php");
require_once("../libs/jwt/src/Claim/Validatable.php");

// Parsing classes
require_once("../libs/jwt/src/Parsing/Encoder.php");
require_once("../libs/jwt/src/Parsing/Decoder.php");

// Claim classes
require_once("../libs/jwt/src/Claim/Basic.php");
require_once("../libs/jwt/src/Claim/Factory.php");
require_once("../libs/jwt/src/Claim/EqualsTo.php");
require_once("../libs/jwt/src/Claim/LesserOrEqualsTo.php");
require_once("../libs/jwt/src/Claim/GreaterOrEqualsTo.php");

// Signer classes
require_once("../libs/jwt/src/Signer/BaseSigner.php");
require_once("../libs/jwt/src/Signer/Hmac.php");
require_once("../libs/jwt/src/Signer/Key.php");
require_once("../libs/jwt/src/Signer/Hmac/Sha256.php");

// General classes
require_once("../libs/jwt/src/Token.php");
require_once("../libs/jwt/src/Signature.php");
require_once("../libs/jwt/src/Builder.php");

$signer = new \Lcobucci\JWT\Signer\Hmac\Sha256();

$token = (new \Lcobucci\JWT\Builder())->setIssuer('http://www.indeklima-api.local') // Configures the issuer (iss claim)
->setAudience('http://www.indeklima-api.local') // Configures the audience (aud claim)
->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
->setIssuedAt(time()) // Configures the time that the token was issued (iat claim)
->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
->set('uid', 1) // Configures a new claim, called "uid"
->sign($signer, 'nicememe') // creates a signature using "testing" as key
->getToken();

*/
/**
 * Class AuthController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * //TODO: description needed
 */
class AuthController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * AuthController constructor.
     */
    public function __construct() {
        parent::__construct();
    }

    public function authorize(string $username, string $password) {

        try {
            $model = $this->getModel("AuthModel");
        } catch (\Exception $exception) {
            exit($exception);
        }

        if($username === "test" && $password === "1234") {
            exit(json_encode(["access_token" => $model->createJWT()]));
        }

    }

    public function token(string $access_token) {

        if($access_token === "ACCESS_TOKEN") {
            exit(json_encode(["authorization_token" => "AUTHORIZATION_TOKEN"]));
        }

    }

}