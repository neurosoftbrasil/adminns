<?php

class Controller {
    public function layout() {
        if(!Request::get('service')) {
            include(VIEW_DIR."header.php");
            echo "\n";
        } else {
            if(Config::$environment=='production') {
                header('Content-Type: application/json');
            }
        }
        
        $path = VIEW_DIR.ucwords(Request::get('controller'))."/".Request::get('method').".tpl";
        
        if(file_exists($path)) {
            include($path);
        }
        if(!Request::get('service')) {
            include(VIEW_DIR."footer.php");
        }
    }
}
