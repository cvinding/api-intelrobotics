<?php
namespace CONTROLLER;


class InfoController extends Controller implements \CONTROLLER\_IMPLEMENTS\Controller {

    public function __construct(){
        parent::__construct([
            "getExtNews" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => false,
                "PERMISSIONS" => [
                    false
                ]
            ],
            "getIntNews" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => true,
                "PERMISSIONS" => [
                    false
                ]
            ],
            "getAbout" => [
                "REQUEST_METHOD_LEVEL" => 0,
                "TOKEN" => false,
                "PERMISSIONS" => [
                    false
                ]
            ]
        ]);
    }

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


}