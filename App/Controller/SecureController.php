<?php
class SecureController extends AppController {
    public function __construct() {
        $token = Request::value('token');
        if(isset($token) && Session::getUserByToken($token)) {
            
        } else {
            //Session::destroy();
            //Router::redirect('login');
        }
    }
}