<?php
namespace MODEL;


class IntraModel extends Model {

    private $intraPages = [
        "news" => false,
        "users" => ["Administration_SG"],
        "webnews" => ["IT_SG"],
        "addwebnews" => ["IT_SG"],
        "webproducts" => ["IT_SG"],
        "addwebproducts" => ["IT_SG"],
        "webabout" => ["IT_SG"]
    ];


    public function getIntraPages() : array {
        return $this->intraPages;
    }

    public function getMenu(array $permissions) : array {
        var_dump($permissions);die;
    }

}