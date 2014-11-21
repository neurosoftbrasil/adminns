<?php
// ESTATICA
class Helper {
    public static function dbToDate($string) {
        $tmp = $string != ""?$string:"00-00";
        $tmp = explode("-",$tmp);
        return count($tmp)==3?$tmp[2]."/".$tmp[1]."/".$tmp[0]:"";
    }
    public static function timestampToDate($string,$hours=false) {
        if(!$string) return "00/00/0000";
        $tmp = explode(" ",$string);
        $date = explode("-",$tmp[0]);
        $hour = count($tmp)>1?explode(":",$tmp[1]):array("00","00");
        $hour = " ".$hour[0].":".$hour[1];
        if(!$hours) {
            $hour = "";
        }
        return $date[2]."/".$date[1]."/".$date[0].$hour;
    }
    public static function formatValor($str) {
        $num = $str;
        $num = explode(".",$num);
        $number = "";
        
        if(count($num)>1) {
            $number .= ",".str_pad($num[1],2,"0");
        } else {
            $number .= ",00";
        }
        $tmp = "";
        if(count($num)>1) {
            $num = $num[0];
        }
        $counter = 0;
        for($i=strlen($num)-1;$i>=0;$i--) {
            $tmp = $num{$i}.$tmp;
            $counter++;
            if($counter == 3 && $counter != strlen($num)) {
                $counter = 0;
                $tmp = ".".$tmp;
            }
        }
        $tmp .= $number;
        return "R$ " . $tmp;
    }
    public static function formatDocumento($str) {
        $doc = str_replace(".","",trim($str));
        $doc = str_replace("-","",$doc);
        $doc = str_replace("/","",$doc);
        $ret = "";
        switch(strlen($doc)) {
            case 11:
                $ret .= substr($doc,0,3).".";
                $ret .= substr($doc,4,3).".";
                $ret .= substr($doc,7,3)."-";
                $ret .= substr($doc,9,2);
            break;
            case 14:
                $ret .= substr($doc,0,2).".";
                $ret .= substr($doc,2,3).".";
                $ret .= substr($doc,6,3)."/";
                $ret .= substr($doc,9,3)."-";
                $ret .= substr($doc,12,2);
            break;
            default:
                $ret = $str;
            break;
        }
        return $ret;
    }
    public static function dateToTimestamp($string,$fillHours) {
        $date = explode("-",$string);
        $hour = "";
        if($fillHours) {
            $hour .= " 00:00:00";
        }
        return $date[2]."-".$date[1]."-".$date[0].$hour;
    }
    public static function prepareForLike($str) {
        $tmp = explode(" ",$str);
        return implode("%",$tmp);
    }
    public static function controllerName($str) {
        return ucwords($str)."Controller";
    }
    public static function css($str,$params=array()) {
        $cssPath = VIEW_DIR."stylesheets/".$str.".css";
        echo "<link rel='stylesheet' type='text/css' href='/".APP_DIR.$cssPath."' ";
        echo self::printParams($params);
        echo "/>\n";
    }
    public static function js($str,$params=array()) {
        $jsPath = VIEW_DIR."javascripts/".$str.".js";
        echo "<script type='text/javascript' src='/".APP_DIR."$jsPath' ";
        echo self::printParams($params);
        echo "></script>\n";
    }
    public static function printParams($arr) {
        $html = "";
        foreach($arr as $key => $value) {
            if(gettype($value)=='string') {
                $html .= $key."='".$value."' ";
            }
        }
        return $html;
    }
    public static function link($str="") {
        return "/".APP_DIR."$str";
    }
}
