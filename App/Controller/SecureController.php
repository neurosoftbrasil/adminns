<?php
class SecureController extends AppController {
    public function __construct() {
        $token = Request::value('token');
        $session_id = Session::getId();
        if(!isset($_SESSION[$session_id]) || !isset($token) && !Session::getUserByToken($token)) {
            Session::destroy();
            Router::redirect('login');
        }
    }
}