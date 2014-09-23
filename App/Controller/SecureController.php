<?php
class SecureController extends AppController {
    public function __construct() {
        $token = Request::value('token');
        if(!Session::isLogged() && !Session::getUserByToken($token)) {
            if(Request::get('service')=="1") {
                $j = array();
                $j['status'] = "danger";
                $j['message'] = "Você não está logado ou seu login expirou.";
                $j['redirect'] = "/".APP_DIR."login";
                echo json_encode($j);
            } else {
                Router::redirect('login');
            }
        }
    }
}