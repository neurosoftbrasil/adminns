<?php
class SecureController extends AppController {
    public function __construct() {
        $token = Request::value('token');
        $session_id = Session::getId();
        if(!Session::isLogged() && isset($token) && !Session::getUserByToken($token)) {
            Session::destroy();
            if(Request::get('service')=="1") {
                $j = array();
                $j['status'] = "danger";
                $j['message'] = "Você não está logado ou seu login expirou.";
                $j['redirect'] = "/".APP_DIR."login";
                echo json_encode($j);
                die();
            } else {
                Router::redirect('login');
            }
        }
    }
}