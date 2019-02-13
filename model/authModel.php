<?php
namespace MODEL;


require_once("../libs/reallysimplejwt/src/Helper/JsonEncoder.php");
require_once("../libs/reallysimplejwt/src/Interfaces/EncodeInterface.php");

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
 */
class AuthModel extends Model implements \MODEL\_IMPLEMENTS\Model {

    //TODO: create an AuthModel

    public function createJWT() {
        $userId = 12;
        $secret = 'sec!ReT423*&';
        $expiration = time() + 3600;
        $issuer = 'www.indeklima-api.local';

        $token = \ReallySimpleJWT\Token::create($userId, $secret, $expiration, $issuer);






        return $token;
    }

}