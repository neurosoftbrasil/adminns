<?php
// ESTATICA
class Helper {
    public static function timestampToDate($string,$hours='false') {
        $tmp = explode(" ",$string);
        $date = explode("-",$tmp[0]);
        $hour = explode(":",$tmp[1]);
        $hour = " - ".$hour[0].":".$hour[1];
        if(!$hours) {
            $hour = "";
        }
        return $date[2]."/".$date[1]."/".$date[0].$hour;
    }
    public static function dateToTimestamp($string,$fillHours) {
        $date = explode("-",$string);
        $hour = "";
        if($fillHours) {
            $hour .= " 00:00:00";
        }
        return $date[2]."-".$date[1]."-".$date[0].$hour;
    }
    public static function controllerName($str) {
        return ucwords($str)."Controller";
    }
    public static function css($str,$params) {
        $cssPath = VIEW_DIR.Config::$cssPath."/".$str.".css";
        echo "<link rel='stylesheet' type='text/css' href='/".APP_DIR.$cssPath."' ";
        self::printParams($params);
        echo "/>\n";
    }
    public static function javascript($str,$params) {
        $jsPath = VIEW_DIR.Config::$jsPath."/".$str.".js";
        echo "<script type='text/javascript' src='/".APP_DIR."$jsPath' ";
        self::printParams($params);
        echo "></script>\n";
    }
    public static function printParams($arr) {
        foreach($arr as $key => $value) {
            echo $key."='".$value."' ";
        }
    }
}
