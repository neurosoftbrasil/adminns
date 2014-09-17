<?php

class SessionController extends AppController {
    public function logout() {
        Router::redirect("login");
    }
    public function login() {
        Router::redirect("login");
    }
}