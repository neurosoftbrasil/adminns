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
}
