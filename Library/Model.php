<?php

class Model {
    private $fields = array();
    private $table;
    
    public function __construct() {}
    public function setTable($table) {
        $this->table = $table;
    }
    public function load($id=false) {
        global $db;
        $query = "select * from ".$this->table." ";
        $result;
        if($id) {
            $query .= "where id=".$id;
            $result = $db->query($query,true);
        } else {
            $result = $db->query($query);
        }
        $this->fields = $result;
        return $result;
    }
}
