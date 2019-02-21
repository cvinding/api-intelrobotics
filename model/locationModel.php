<?php
namespace model;


class LocationModel extends Model implements \MODEL\_IMPLEMENTS\Model {

    /**
     * @return array
     */
    public function getRooms() : array {
        $db = new \DATABASE\Database();
        $data = $db->query("SELECT * FROM room ORDER BY id")->fetchArray();

        $temp = [];

        for($i = 0; $i < sizeof($data); $i++){
            $temp[] = $data[$i]["id"];
        }

        return $temp;
    }

}