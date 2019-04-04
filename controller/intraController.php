<?php
namespace CONTROLLER;


class IntraController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    public function __construct() {
        parent::__construct([
            "getMenu" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => true
            ]
        ]);
    }

    public function getMenu() {
        try {
            /**
             * @var \MODEL\IntraModel $intra
             */
            $intra = $this->getModel("IntraModel");

            /**
             * @var \MODEL\AuthModel $auth
             */
            $auth = $this->getModel("AuthModel");

            $permissions = $auth->getTokenClaim($this->getToken(), "security_groups");

            $result = $intra->getMenu($permissions);

            $temp = [];

            foreach($result as $page => $permission) {
                $temp[] = $page;
            }


            exit(json_encode(["menu" => $temp, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

    public function checkAccess() {

    }

}