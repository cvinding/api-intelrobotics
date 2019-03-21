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
                    "Webmaster"
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

    public function createNews(string $title, string $description, int $internal, string $webDomain, string $author) {

    }


}