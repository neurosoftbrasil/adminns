<? 
// ESTATICA
class Config {
    /*private static $db = array(
        'host'=>'neurosoftbrdb.cloudapp.net',
        'user'=>'neurosoft',
        'passwd'=>'3caras2014',
        'dbname'=>'neurosoft',
        'port'=>3306
    );*/
    private static $db = array(
        'host'=>'localhost',
        'user'=>'root',
        'passwd'=>'',
        'dbname'=>'neurosoft',
        'port'=>3306
    );
    private static $tokenKey = "|Neurosoft|SeñorChang|";
    
    public static function db() {
        return (object) self::$db;
    }
    public static function tokenKey() {
        return self::$tokenKey;
    }
}