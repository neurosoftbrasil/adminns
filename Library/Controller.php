<?php

class Controller {
    private $sets = array();
    
    public function layout() {
        foreach($this->sets as $key => $value) $$key = $value;
        
        if(!Request::get('service')) {
            include(VIEW_DIR."header.php");
            echo "\n";
        } else {
            if(Config::$environment=='production') {
                header('Content-Type: application/json');
            }
        }
        
        $path = VIEW_DIR.ucwords(Request::get('controller'))."/".Request::get('method').".php";
        if(file_exists($path)) {
            include($path);
        }
        if(!Request::get('service')) {
            include(VIEW_DIR."footer.php");
        }
    }
    public function index() {}
    
    public function set($key,$value) {
        $this->sets[$key] = $value;
    }
}
