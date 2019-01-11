<?php

class Autoloader {

    public static function register(){
        spl_autoload_register(function ($class) {
            $file = "..".DIRECTORY_SEPARATOR.self::getClassPath($class.".php");
            if (file_exists($file)) {
                require $file;
            }
        });
    }

    private static function getClassPath(string $className) : string {
        $explodedClass = explode(DIRECTORY_SEPARATOR, str_replace("\\", DIRECTORY_SEPARATOR, $className));

        $pathToClass = "";
        for($i = 0; $i < (sizeof($explodedClass) - 1); $i++){
            $pathToClass .= strtolower($explodedClass[$i]).DIRECTORY_SEPARATOR;
        }

        $pathToClass .= lcfirst($explodedClass[sizeof($explodedClass) - 1]);

        return $pathToClass;
    }

}