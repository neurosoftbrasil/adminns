<?php

class SessionController extends AppController {
    public function logout() {
    	Session::destroy();
    }
    public function login() {
        Session::destroy();
    }
}