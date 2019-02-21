<?php
namespace MODEL;

class TemperatureModel extends Model implements \MODEL\_IMPLEMENTS\Model {

    //TODO: Create a TemperatureModel


    public function getRoomTemperatureById(int $id) : array {
        $db = new \DATABASE\Database();
        $data = $db->query("SELECT m.room_id id, m.temperature, m.humidity, s.name format FROM temperature m INNER JOIN temperature_format s ON m.temperature_format_id = s.id WHERE m.room_id = ? ", [$id])->fetchArray();

        return $data[0];
    }

}