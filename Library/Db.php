<?php
// INSTANCIADA || DINAMICA
class Database {
    private $conn;

    public static function token($infos) {
        $s = Config::tokenKey();
        foreach($infos as $i) {
            $s .= sha1($i);
        }
        return sha1($s);
    }
    public function __construct() {
        if(!$this->conn) {
            $info = Config::db();
            foreach($info as $key=>$value) $$key = $value;
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $passwd,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        }
    }
    public function tableInfo($str,$column) {
        $query = "select * from information_schema.columns 
where table = '".Config::db()->dbname."' TABLE_NAME = '".$str."' 
AND COLUMN_NAME = '".$column."_id'";
        $table = $this->query($query);
        return count($table)>0;
    }
    public function getResult($table,$fields="*",$conditions=false,$unique=false) {
        $query = "select $fields from $table ";
        if($conditions) {
            $query .= "where ".$conditions;
        }
        return $this->query($query,$unique);
    }
    public function query($str,$unique = false,$format=PDO::FETCH_ASSOC) {
        preg_match("/(insert)|(update)|(delete)|(select)/", strtolower($str),$operation);
        try {
            switch($operation[0]) {
                case "select":
                    $res = array();
                    $stmt = $this->conn->prepare($str);
                    $stmt->execute();
                    $res = $stmt->fetchAll($format);
                    if($unique && count($res)==1) {
                        return $res[0];
                    }
                    return $res;
                break;
                case "insert":
                    $this->conn->query($str);
                    return $this->conn->lastInsertId();
                break;
                default:
                    return $this->conn->query($str);
                break;
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
class DatabaseOld extends Database {
    private $conn;
    
    public function __construct() {
        if(!$this->conn) {
            $this->conn = new PDO("mysql:host=localhost;dbname=neurosoft;port=3306", 'root', '',array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        }
    }
    public function query($str,$unique = false,$format=PDO::FETCH_ASSOC) {
        preg_match("/(insert)|(update)|(delete)|(select)/", strtolower($str),$operation);
        try {
            switch($operation[0]) {
                case "select":
                    $res = array();
                    $stmt = $this->conn->prepare($str);
                    $stmt->execute();
                    $res = $stmt->fetchAll($format);
                    if($unique && count($res)==1) {
                        return $res[0];
                    }
                    return $res;
                break;
                case "insert":
                    $this->conn->query($str);
                    return $this->conn->lastInsertId();
                break;
                default:
                    return $this->conn->query($str);
                break;
            }
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }
}
global $dbold;
$dbold = new DatabaseOld();