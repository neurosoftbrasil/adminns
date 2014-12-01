<?php

class LoginController extends AppController {
    public function index() {
        if(Session::isLogged()) {
            Router::redirect('home');
        }
    }
    
    public function logout() {
        session_destroy();
        Router::redirect('login');
    }
    public function auth() {
        $email  = Request::post('email');
        $passwd = Request::post('password');
        Session::auth($email,$passwd);
    }
}