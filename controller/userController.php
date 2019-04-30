<?php
namespace CONTROLLER;

/**
 * Class UserController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 */
class UserController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    public function __construct() {
        parent::__construct([
            "searchUserList" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    "Administration_SG"
                ]
            ]
        ]);
    }

    public function searchUserList(string $searchInput) {

       // exit(json_encode(["search_result" => [["fullname" => "Tobias Larsen", "social_security_number" => "0909679999", "country" => "Denmark", "department" => "IT"]], "status" => true]));

        try {
            /**
             * @var \MODEL\UserModel $intra
             */
            $user = $this->getModel("UserModel");


            $output = $user->getLDAPUserInfo($searchInput);

            exit(json_encode(["search_result" => $output, "status" => true]));


        } catch (\Exception $exception) {
            $this->exitResponse(500, "Something unexpected occurred, unable to search user list");
        }

    }


}
