<?php
namespace model;

/**
 * Class LocationModel
 * @package model
 * @author Christian Vinding Rasmussen
 */
class LocationModel extends Model implements \MODEL\_IMPLEMENTS\Model {

    /**
     * getRooms() is used for returning all rooms in the database
     * @return array
     */
    public function getRooms() : array {
        // Create DB connection
        $db = new \DATABASE\Database();

        // Select all relevant data
        $data = $db->query("SELECT * FROM room ORDER BY id asc")->fetchArray();

        // Format the data
        $temp = [];
        for($i = 0; $i < sizeof($data); $i++){
            $temp[] = $data[$i]["id"];
        }

        // Return the data
        return $temp;
    }

}