<?php
namespace CONTROLLER;


class UserController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    public function __construct() {
        parent::__construct([
            "searchPhoneBook" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true
            ],
            "searchUserList" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    "Administration_SG"
                ]
            ]
        ]);
    }


    public function searchPhoneBook(string $searchInput) {
        //TODO: used by everyone to search for user + other work related info
    }

    public function searchUserList(string $searchInput) {
        //TODO: used by HR to search for user + CPR number

        exit(json_encode(["result" => $searchInput, "status" => true]));
    }


}