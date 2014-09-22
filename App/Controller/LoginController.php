<?php

class LoginController extends AppController {
    public function index() {
    }
    
    public function logout() {
        session_destroy();
        Router::redirect('login');
    }
    
    public function auth() {
        $email  = mysql_real_escape_string(Request::post('email'));
        $passwd = mysql_real_escape_string(Request::post('password'));
        $ret = array();
        $erros = array();
        if($email && $passwd) {
            if(!preg_match(FormHelper::EMAIL,$email)) {
                $err = array();
                $err['field'] = "email";
                $err['message'] = "Digite um <strong>E-mail</strong> válido.";
                array_push($erros,$err);
            }
            if(!preg_match(FormHelper::NOT_EMPTY,$passwd)) {
                $err = array();
                $err['field'] = "password";
                $err['message'] = "Digite uma <strong>Senha</strong> para logar.";
                array_push($erros,$err);
            }
            if(count($erros)==0) {
                global $db;
                $session_id = Session::getId();
                $passwd = Session::password($passwd);
                $query = "select id,name,email,token from user where email='$email' and password='$passwd' and active=1 and deleted=0";
               
                $user = $db->query($query,true,PDO::FETCH_CLASS);
                if(count($user)>0) {
                    $session = array(
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $user->token
                    );
                    $_SESSION[$session_id] = $session;
                    $query = "update user set lastlogin='".date('Y-m-d H:i:s')."' where id=".$user->id;
                    $db->query($query);
                    $ret['status'] = 'success';
                    $ret['message'] = 'Login realizado, a página será redirecionada.';
                    $ret['redirect'] = "/".APP_DIR."home/index";
                } else {
                    Session::destroy();
                    $ret = array();
                    $ret['status'] = 'danger';
                    $ret['message'] = 'Login ou senha inválidos.';
                }
            } else {
                $ret['status'] = 'error';
                $ret['message'] = "Há erros no formulário";
                $ret['details'] = $erros;
            }
            echo json_encode($ret);
        }
    }
}