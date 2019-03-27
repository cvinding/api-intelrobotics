<?php
namespace CONTROLLER;

/**
 * Class InfoController
 * @package CONTROLLER
 * @author Christian Vinding Rasmussen
 * The InfoController is an endpoint controller for information regarding the external and internal part of our intelrobotics website.
 * Its main purpose is to show information such as news, about section and products and also being able to add, edit and delete the content.
 */
class InfoController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    /**
     * InfoController constructor.
     * Used for setting the endpoint settings
     */
    public function __construct(){
        parent::__construct([
            "getExtNews" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => false
            ],
            "getIntNews" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => true
            ],
            "getNews" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => true
            ],
            "getAbout" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => false
            ],
            "getProducts" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => false
            ],
            "createNews" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    "IT_SG"
                ]
            ],
            "createProduct" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    "IT_SG"
                ]
            ],
            "deleteNews" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    "IT_SG"
                ]
            ],
            "deleteProduct" => [
                "REQUEST_METHOD_LEVEL" => 1,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    "IT_SG"
                ]
            ]
        ]);
    }

    /**
     * getExtNews() is an endpoint for getting all the external website news
     * @param int $titleCount
     * @param int $bodyCount
     * @param int $limit
     * @param string $webDomain
     */
    public function getExtNews(int $titleCount, int $bodyCount, int $limit, string $webDomain) {
        try {
            /**
             * @var \MODEL\InfoModel $model
             */
            $model = $this->getModel("InfoModel");

            $result = $model->getNews($titleCount, $bodyCount, $limit, $webDomain, 0);

            exit(json_encode(["news" => $result, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

    /**
     * getIntNews() is an endpoint for getting all the internal website news
     * @param int $titleCount
     * @param int $bodyCount
     * @param int $limit
     * @param string $webDomain
     */
    public function getIntNews(int $titleCount, int $bodyCount, int $limit, string $webDomain) {
        try {
            /**
             * @var \MODEL\InfoModel $model
             */
            $model = $this->getModel("InfoModel");

            $result = $model->getNews($titleCount, $bodyCount, $limit, $webDomain, 1);

            exit(json_encode(["news" => $result, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

    /**
     * getNews() is used for getting external and internal news
     * @param int $titleCount
     * @param int $bodyCount
     * @param string $webDomain
     */
    public function getNews(int $titleCount, int $bodyCount, string $webDomain) {
        try {
            /**
             * @var \MODEL\InfoModel $model
             */
            $model = $this->getModel("InfoModel");

            $output = [];

            $output["external"] = $model->getNews($titleCount, $bodyCount, 0, $webDomain, 0);
            $output["internal"] = $model->getNews($titleCount, $bodyCount, 0, $webDomain, 1);

            exit(json_encode(["news" => $output, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

    /**
     * getAbout() is an endpoint for getting the about section of the website
     * @param string $webDomain
     */
    public function getAbout(string $webDomain) {
        try {
            /**
             * @var \MODEL\InfoModel $model
             */
            $model = $this->getModel("InfoModel");

            $result = $model->getAbout($webDomain);

            if(!empty($result)) {
                $result = $result[0];
            }

            exit(json_encode(["about" => $result, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

    /**
     * getProducts() is an endpoint for getting all the products for the website
     * @param string $webDomain
     */
    public function getProducts(string $webDomain) {
        try {
            /**
             * @var \MODEL\InfoModel $model
             */
            $model = $this->getModel("InfoModel");

            $result = $model->getProducts($webDomain);

            exit(json_encode(["products" => $result, "status" => true]));

        } catch (\Exception $exception) {
            exit($exception);
        }
    }

    /**
     * createNews() is used for creating news
     * @param string $title
     * @param string $description
     * @param int $internal
     * @param string $webDomain
     */
    public function createNews(string $title, string $description, int $internal, string $webDomain) {
        try {
            /**
             * @var \MODEL\InfoModel $info
             */
            $info = $this->getModel("InfoModel");
            /**
             * @var \MODEL\AuthModel $auth
             */
            $auth = $this->getModel("AuthModel");

            // Get the username from the token
            $author = $auth->getTokenClaim($this->getToken(), "uid");

            // Create the news post
            $result = $info->createNews($title, $description, $internal, $webDomain, $author);

            // If failure
            if(!$result) {
                $this->exitResponse(500, "Unable to create news post");
            }

            exit(json_encode(["message" => "Success! A news post was created", "status" => true]));

        } catch (\Exception $exception) {
            $this->exitResponse(500, "Something unexpected occurred, unable to create news post");
        }
    }

    /**
     * createProduct() is an endpoint used for creating products
     * @param string $title
     * @param string $description
     * @param string $webDomain
     */
    public function createProduct(string $title, string $description, string $webDomain) {
        try {
            /**
             * @var \MODEL\InfoModel $info
             */
            $info = $this->getModel("InfoModel");
            /**
             * @var \MODEL\AuthModel $auth
             */
            $auth = $this->getModel("AuthModel");

            // Get the username from the token
            $author = $auth->getTokenClaim($this->getToken(), "uid");

            // Create the news post
            $result = $info->createProduct($title, $description, $webDomain, $author);

            // If failure
            if(!$result) {
                $this->exitResponse(500, "Unable to create a new product");
            }

            exit(json_encode(["message" => "Success! A new product was created", "status" => true]));

        } catch (\Exception $exception) {
            $this->exitResponse(500, "Something unexpected occurred, unable to a new product");
        }
    }

    public function deleteNews(int $id) {

    }

    public function deleteProduct(int $id) {

    }


}