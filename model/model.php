<?php
namespace MODEL;

/**
 * Class Model
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * A base class for all models. Maybe useful for something some day :)
 */
class Model {

    public function __toString() : string {
        return "Model name: ".get_class($this);
    }

}