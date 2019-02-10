<?php
namespace MODEL;

/**
 * Class Model
 * @package MODEL
 * @author Christian Vinding Rasmussen
 * TODO: description needed
 */
class Model implements \MODEL\_IMPLEMENTS\Model {

    public function __toString() : string {
        return "Model name: ".get_class($this);
    }

}