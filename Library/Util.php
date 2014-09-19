<?php

// ESTATICA
class Util {
    public static function prints($str,$dying = false) {
        echo "<pre>";
        print_r($str);
        echo "</pre>";
        if($dying) die('DEAD');
    }
    public static function dumps($str,$dying = false) {
        echo "<pre>";
        var_dump($str);
        echo "</pre>";
        if($dying) die('DEAD');
    }
}