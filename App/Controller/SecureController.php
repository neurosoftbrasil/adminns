<?php
class SecureController extends AppController {
    public function __construct() {
        $token = Request::value('token');
        $session_id = Session::getId();
        if(!Session::isLogged() && !isset($token) && !Session::getUserByToken($token)) {
            Session::destroy();
            Router::redirect('login');
        }
    }
}