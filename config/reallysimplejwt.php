<?php
namespace CONFIG;

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
