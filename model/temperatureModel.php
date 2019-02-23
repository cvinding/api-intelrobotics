<?php
namespace MODEL;

/**
 * Class TemperatureModel
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * //TODO: description
 */
class TemperatureModel extends Model implements \MODEL\_IMPLEMENTS\Model {

    /**
     * addTemperature() inserts a new temperature to the temperature table and returns bool
     * @param int $room_id
     * @param float $temperature
     * @param float $humidity
     * @param int $format
     * @return bool
     */
    public function addTemperature(int $room_id, float $temperature, float $humidity, int $format) : bool {
        // Create DB connection
        $db = new \DATABASE\Database();

        // Create an array with all the data
        $entryData =  [
            "id" => $room_id,
            "temperature" => $temperature,
            "humidity" => $humidity,
            "format" => $format
        ];

        // Insert the new temperature data
        $result = $db->query("INSERT INTO temperature (room_id, temperature, humidity, temperature_format_id) VALUES (:id, :temperature, :humidity, :format)", $entryData)->affectedRows();

        // Return true or false, $result is either 0 or 1
        return (bool) $result;
    }

    /**
     * setDefaultTemperature() inserts/updates the default temperature
     * @param float $temperature
     * @param int $format
     * @return bool
     */
    public function setDefaultTemperature(float $temperature, int $format) : bool {
        // Create DB connection
        $db = new \DATABASE\Database();

        // Create an array with all the data
        $entryData =  [
            "temperature" => $temperature,
            "format" => $format
        ];

        $result = $db->query("UPDATE temperature_default SET temperature = :temperature, temperature_format_id = :format WHERE id = 1", $entryData)->affectedRows();

        return (bool) $result;
    }

    /**
     * getDefaultTemperature() returns an array with default temperature and format
     * @return array
     */
    public function getDefaultTemperature() : array {
        // Create DB connection
        $db = new \DATABASE\Database();

        // Select the default temperature
        $data = $db->query("SELECT m.temperature, s.name format FROM temperature_default m INNER JOIN temperature_format s ON m.temperature_format_id = s.id ORDER BY m.id DESC LIMIT 1")->fetchArray();

        // Return the array
        return $data[0];
    }

    /**
     * getRoomTemperatureById() is used for returning a specific room's temperature
     * @param int $id
     * @return array
     */
    public function getRoomTemperatureById(int $id) : array {
        // Create DB connection
        $db = new \DATABASE\Database();

        // Select all relevant data
        $data = $db->query("SELECT m.room_id id, m.temperature, m.humidity, s.name format FROM temperature m INNER JOIN temperature_format s ON m.temperature_format_id = s.id WHERE m.room_id = ? ORDER BY m.id LIMIT 1", [$id])->fetchArray();

        // Return the data
        return (!empty($data)) ? $data[0] : ["id" => $id, "temperature" => "No data", "humidity" => "No data", "format" => "No data"];
    }

}