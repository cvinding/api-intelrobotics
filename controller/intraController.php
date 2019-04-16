<?php
namespace CONTROLLER;

/**
 * Class IntraController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * IntraController is a controller for interacting with the IntraModel
 */
class IntraController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    public function __construct() {
        parent::__construct([
            "getMenu" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => true
            ],
            "checkAccess" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => true
            ]
        ]);
    }

    /**
     * getMenu() is an endpoint for returning all the available webpages accessible by the user
     */
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

            exit(json_encode(["menu" => $result, "status" => true]));

        } catch (\Exception $exception) {
            //exit($exception);
            $this->exitResponse(500, "Something unexpected occurred, unable to get menu");
        }
    }

    /**
     * checkAccess() is an endpoint for checking if the user can see a specific webpage
     * @param string $webPage
     */
    public function checkAccess(string $webPage) {
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

            $result = $intra->checkAccess($webPage, $permissions);

            exit(json_encode(["accessible" => $result, "status" => true]));

        } catch (\Exception $exception) {
            //exit($exception);
            $this->exitResponse(500, "Something unexpected occurred, unable to check access for this webpage");
        }
    }

}