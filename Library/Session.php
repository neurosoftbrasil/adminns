<?

class Session {

    const VISUALIZAR = 1;
    const INSERIR = 2;
    const EDITAR = 3;
    const EXCLUIR = 4;

    public static $permissions;

    public static function get($str) {
        $prop = $_SESSION[self::getId()][$str];
        return isset($prop) ? $prop : false;
    }

    public static function password($str) {
        return sha1(Config::getToken() . md5($str));
    }

    public static function destroy() {
        session_destroy();
    }

    public static function token($str) {
        return sha1(sha1(Config::getToken()) . $str);
    }

    public static function hasPermission($module, $level) {
        if (!self::$permissions) {
            global $db;
            $id = $_SESSION[Session::getId()]['id'];
            $query = "select u.id,m.name,m.permission,um.level from user u, user_module um, module m where ";
            $query .= "u.id = um.user_id and m.id = um.module_id and u.id = $id and u.deleted=0 order by m.id desc";
            $perms = $db->query($query);
            $tmp = array();
            foreach ($perms as $p) {
                $tmp[$p['permission']] = array('level' => $p['level'], 'name' => $p['name']);
            }
            self::$permissions = $tmp;
        }
        return isset(self::$permissions[$module]['level']) && self::$permissions[$module]['level'] >= $level;
    }

    public static function getId() {
        return sha1(Config::getToken() . date('Y-m-d'));
    }

    public static function isLogged() {
        if (isset($_SESSION[self::getId()])) {
            return true;
        }
        return false;
    }

    public static function getUserByToken($str) {
        global $db;
        $query = "select id,email,name,token,active from user where token='$str' and active=1 and deleted=0";
        
        $u = $db->query($query, true);
        
        if (count($u) > 0) {
            $u = (object) $u;
            $session = array(
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'token' => $u->token
            );
            $_SESSION[self::getId()] = $session;
            return true;
        } else {
            return false;
        }
    }

    public static function auth($email, $passwd) {
        $ret = array();
        $erros = array();
        if ($email && $passwd) {
            if (!preg_match(FormHelper::EMAIL, $email)) {
                $err = array();
                $err['field'] = "email";
                $err['message'] = "Digite um <strong>E-mail</strong> válido.";
                array_push($erros, $err);
            }
            if (!preg_match(FormHelper::NOT_EMPTY, $passwd)) {
                $err = array();
                $err['field'] = "password";
                $err['message'] = "Digite uma <strong>Senha</strong> para logar.";
                array_push($erros, $err);
            }
            if (count($erros) == 0) {
                global $db;
                $session_id = Session::getId();
                $passwd = Session::password($passwd);
                $query = "select id,name,email,token from user where email='$email' and password='$passwd' and active=1 and deleted=0";

                $user = $db->query($query, true, PDO::FETCH_CLASS);
                if (count($user) > 0) {
                    $session = array(
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'token' => $user->token
                    );
                    $_SESSION[$session_id] = $session;
                    $query = "update user set lastlogin='" . date('Y-m-d H:i:s') . "' where id=" . $user->id;
                    $db->query($query);
                    $ret['status'] = 'success';
                    $ret['message'] = 'Login realizado, a página será redirecionada.';
                    $ret['redirect'] = "/" . APP_DIR . "home/index";
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