<?php

// ESTATICA
class Util {
    public static function prints($str,$dying = false) {
        echo "<div style='border:1px solid #f00;display:block;padding:10px'><pre>";
        print_r($str);
        echo "</pre></div>";
        if($dying) die('DEAD');
    }
    public static function dumps($str,$dying = false) {
        echo "<div style='border:1px solid #f00;display:block;padding:10px'><pre>";
        var_dump($str);
        echo "</pre></div>";
        if($dying) die('DEAD');
    }
}