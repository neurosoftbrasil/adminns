<? 
// ESTATICA
class Config {
    /*
    private static $db = array(
        'host'=>'neurosoftbrdb.cloudapp.net',
        'user'=>'neurosoft',
        'passwd'=>'3caras2014',
        'dbname'=>'adminns',
        'port'=>3306
    );
    private static $dbo = array(
        'host'=>'neurosoftbrdb.cloudapp.net',
        'user'=>'neurosoft',
        'passwd'=>'3caras2014',
        'dbname'=>'neurosoft',
        'port'=>3306
    );
    */
    private static $db = array(
        'host'=>'localhost',
        'user'=>'root',
        'passwd'=>'',
        'dbname'=>'adminns',
        'port'=>3306
    );
    private static $dbo = array(
        'host'=>'localhost',
        'user'=>'root',
        'passwd'=>'',
        'dbname'=>'neurosoft',
        'port'=>3306
    );

    public static $title = "Neurosoft";
    
    public static $app = "neurosoft";
    
    public static $environment = "development";
    
    private static $tokenKey = "NeuroSoft|SeñorChang|";
    
    public static function db() {
        return (object) self::$db;
    }
    public static function dbo() {
        return (object) self::$dbo;
    }
    public static function email() {
        return '';
    }
    public static function getToken() {
        return self::$tokenKey;
    }
}