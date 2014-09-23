<?php

class HelpController extends SecureController {
    public static function maketoken() {
        $email = Request::value('email');
        $senha = Request::value('senha');
        echo Session::token($email.$senha);
    }
}