<?php
namespace MODEL;

// Load the Library "reallysimplejwt"
require_once("../config/reallysimplejwt.php");

/**
 * Class AuthModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * The AuthModel is the model that handles everything from authenticating the user to validating and creating tokens.
 * It is also the only class that uses "reallysimplejwt" which is a JSON Web Token library made by Rob Waller.
 * You can find more information about the library in the libs folder
 */
class AuthModel extends Model implements \MODEL\_IMPLEMENTS\Model {

    /**
     * Describes how long before a token can be used, e.g. 10 seconds
     * @var int $notBefore
     */
    private $notBefore = 1;

    /**
     * Describes how long a token is alive in seconds, e.g. 3600 seconds = 1 hour
     * @var int $expiration
     */
    private $expiration = 3600*5;

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticateUser(string $username, string $password) : bool {
        //TODO: create authenticateUser()


        /*
        $adServer = "ldaps://indeklima.local";

        $ldap = ldap_connect($adServer);

        $ldaprdn = 'MYDN.net' . "\\" . $username;

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($ldap, $ldaprdn, $password);
        */



        return true;
    }

    /**
     * createJWT() create a JSON Web Token
     * @param string $username
     * @return string
     * @throws \Exception
     */
    public function createToken(string $username) : string {
        // Secret signing key TOP SECRET DO NOT SHARE THE KEY
        $secret = require "../config/secret.php";

        // When the token is usable
        $notBefore = $this->notBefore;

        // When the token expires
        $expiration = $this->expiration + $this->notBefore;

        // Random token id
        $tokenID = rand(0, 20000);

        // Custom config
        $config = [
            "iss" => "www.indeklima-api.local",     // Issuer
            "sub" => "User Authorization Token",    // Subject
            "aud" => "www.indeklima-api.local",     // Audience
            "exp" => time() + $expiration,          // Expires
            "nbf" => time() + $notBefore,           // Not usable before
            "iat" => time(),                        // Issued at
            "jti" => $tokenID,                      // JWT id
            "uid" => $username                      // Custom field: User id
        ];

        try {
            // Use the ReallySimpleJWT library to create a token
            $token = \ReallySimpleJWT\Token::customPayload($config, $secret);

        } catch (\Exception $exception) {
            exit($exception);
        }

        // Database class
        $db = new \DATABASE\Database();

        // Insert the token
        /*$rowCount = $db->query("INSERT INTO token (id, token, user_id) VALUES (:id, :token, :user_id)",["id" => $tokenID, "token" => $token, "user_id" => $username])->affectedRows();

        // Check if the token was inserted into the table
        if($rowCount <= 0){
            Throw new \Exception("Token could not be inserted into table");
        }*/

        // Return the token
        return $token;
    }

    /**
     * validateToken() is used for validating the token with the library and also to check if the token is in the db
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    public function validateToken(string $token) : bool {
        // Create DB connection
        $db = new \DATABASE\Database();

        // Secret signing key TOP SECRET DO NOT SHARE THE KEY
        $secret = require "../config/secret.php";

        try {
            // Validate token
            $validToken = \ReallySimpleJWT\Token::validate($token, $secret);

            // Return the payload claims
            $payload = \ReallySimpleJWT\Token::getPayload($token, $secret);

        } catch (\Exception $exception) {
            Throw new \Exception($exception);
        }

        // Get expiration time()
        $expiration = $payload["exp"];

        // Get time() now
        $now = time();

        // Get difference between expiration and now
        $difference = $expiration - $now;

        // Check if the token difference is between 600 seconds (10 minutes) and 0 seconds and refresh the token
        if($difference <= 600 && $difference >= 0){
            //TODO: REFRESH TOKEN
        }else {
            // DELETE the token if expired
          //  $db->query("DELETE FROM token WHERE id = :token_id AND user_id = :user_id", ["token_id" => $payload['jti'], "user_id" => $payload['uid']]);
            //Throw new \Exception("Authorization token expired");
            //return false;
        }

        // SELECT the token id and user_id values from db
        //$data = $db->query("SELECT * FROM token WHERE id = :token_id AND user_id = :user_id", ["token_id" => $payload['jti'], "user_id" => $payload['uid']])->fetchArray();

        /*if(!isset($data) || empty($data)) {
            //return false;
        }*/

        //TODO: fix token store database some errors ???

        // Check if the token id and the user id is the same for the token and the
        /*if($payload["jti"] !== $data[0]["id"] && $payload["uid"] !== $data[0]["user_id"]) {
            //return false;
        }*/

        return $validToken;
    }

    /**
     * generateSecret() used for generating a random secret that
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public function generateSecret(int $length = 12) : string {

        // Check if $length is at least 12
        if($length < 12) {
            Throw new \Exception("Secret must be at least 12 characters long");
        }

        $characters = [
            [ "value" => "abcdefghijklmnopqrstuvwxyz", "used" => 0 ],
            [ "value" => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "used" => 0 ],
            [ "value" => "0123456789", "used" => 0 ],
            [ "value" => "*&!@%^#$", "used" => 0 ],
        ];

        // Initialize Secret
        $secret = "";

        for($i = 0; $i < $length; $i++){

            // Generate a random number from 0 to 3
            $random = rand(0,3);

            // If the character has never been used once, add that character to the $secret string
            if($characters[$random]["used"] === 0) {
                $secret .= $characters[$random]["value"][rand(0, strlen($characters[$random]["value"]) - 1)];
                $characters[$random]["used"]++;
                continue;
            }

            // If all characters has been used at least once, add them to the string
            if($characters[0]["used"] > 0 && $characters[1]["used"] > 0 && $characters[2]["used"] > 0 && $characters[3]["used"] > 0) {
                $secret .= $characters[$random]["value"][rand(0, strlen($characters[$random]["value"]) - 1)];
                $characters[$random]["used"]++;
                continue;
            }

            // Reset this turn
            $i--;
        }

        // Return the $secret string
        return $secret;
    }

}