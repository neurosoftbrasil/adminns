<?php

include('Library/Util.php');
include('Config.php');
include('Library/Db.php');
include('Library/Helper.php');

class App {
    public $_db;
    public $_config;
    
    public function run() {
        $this->_db = new Database();
        global $db;
        $db = $this->db();
    }
    public function db() {
        return $this->_db;
    } 
}