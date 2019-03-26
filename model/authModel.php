<?php
namespace MODEL;

// Load the Library "reallysimplejwt"
require_once "../vendor/autoload.php";

/**
 * Class AuthModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * The AuthModel is the model that handles everything from authenticating the user to validating and creating tokens.
 * It is also the only class that uses "reallysimplejwt" which is a JSON Web Token library made by Rob Waller.
 * You can find more information about the library in the libs folder
 */
class AuthModel extends Model {

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
     * $user is an array for storing the authenticated user's information
     * @var array $user
     */
    private $user = [];

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function authenticateUser(string $username, string $password) : bool {

        //TODO: remove this if using an actual server
        $this->user["SECURITY_GROUPS"] = ["Administration_SG", "IT_SG"];
        return true;

        $hostname = "ldap://ad.intelrobotics.dk";

        $ldapConn = ldap_connect($hostname);

        $ldapRDN = 'AD' . "\\" . $username;

        ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConn, LDAP_OPT_X_TLS_REQUIRE_CERT, 0);
        ldap_set_option($ldapConn, LDAP_OPT_REFERRALS, 0);

        //TODO: create fronter like
        if(!ldap_start_tls($ldapConn)) {
            var_dump("LDAP TLS connection failed");
        }

        $ldapBind = ldap_bind($ldapConn, $ldapRDN, $password);

        if($ldapBind) {

            $ldapBaseDN = "OU=IntelRobotics,DC=ad,DC=intelrobotics,DC=dk";
            $search = "(&(sAMAccountName={$username}))";

            $result = ldap_search($ldapConn, $ldapBaseDN, $search);

            $entries = ldap_get_entries($ldapConn, $result);

            $groups = $entries[0]["memberof"];

            $this->user["SECURITY_GROUPS"] = [];

            for ($i = 0; $i < $groups["count"]; $i++) {
                $this->user["SECURITY_GROUPS"][$i] = explode("=",explode(",",$groups[$i])[0])[1];
            }

        }

        ldap_close($ldapConn);

        return $ldapBind;
    }

    /**
     * createJWT() create a JSON Web Token
     * @param array $claims
     * @return string
     * @throws \Exception
     */
    public function createToken(array $claims) : string {
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
            "iss" => "api.intelrobotics.com",         // Issuer
            "sub" => "User Authorization Token",    // Subject
            "aud" => "api.intelrobotics.com",         // Audience
            "exp" => time() + $expiration,          // Expires
            "nbf" => time() + $notBefore,           // Not usable before
            "iat" => time(),                        // Issued at
            "jti" => $tokenID                      // JWT id
        ];

        $config = array_merge($config, $claims);

        try {
            // Use the ReallySimpleJWT library to create a token
            $token = \ReallySimpleJWT\Token::customPayload($config, $secret);

        } catch (\Exception $exception) {
            exit($exception);
        }

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
        if($difference <= 600 && $difference >= 0){ /*TODO: REFRESH TOKEN*/ }else {}

        return $validToken;
    }

    /**
     * getTokenClaim() is used for returning a specific token claim
     * @param string $token
     * @param string $claim
     * @return string|array
     */
    public function getTokenClaim(string $token, string $claim) {
        // Secret signing key TOP SECRET DO NOT SHARE THE KEY
        $secret = require "../config/secret.php";

        $payload = NULL;

        try {
            $payload = \ReallySimpleJWT\Token::getPayload($token, $secret);

        } catch (\Exception $exception) {
            exit($exception);
        }

        return $payload[$claim];
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

    /**
     * getUser() returns the authenticated user
     * @return array
     */
    public function getUser() : array {
        return $this->user;
    }

}