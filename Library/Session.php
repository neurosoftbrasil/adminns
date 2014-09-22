<?
class Session {
	const VISUALIZAR = 1;
	const INSERIR = 2;
	const EDITAR = 3;
	const EXCLUIR = 4;
        
        public static $permissions;
        
        public static function get($str) {
            $prop = $_SESSION[self::getId()][$str];
            return isset($prop)?$prop:false;
        }
	public static function password($str) {
		return sha1(Config::getToken().md5($str));
	}
	public static function destroy() {
		session_destroy();
	}
	public static function token($str) {
		return sha1(sha1(Config::getToken()).$str);
	}
	public static function auth($login,$password) {
		$passwd = self::password($password);
	}
	public static function hasPermission($module,$level) {
                if(!self::$permissions) {
                    global $db;
                    $id = $_SESSION[Session::getId()]['id'];
                    $query  = "select u.id,m.name,m.permission,um.level from user u, user_module um, module m where ";
                    $query .= "u.id = um.user_id and m.id = um.module_id and u.id = $id and u.deleted=0 order by m.id desc";
                    $perms = $db->query($query);
                    $tmp = array();
                    foreach($perms as $p) {
                        $tmp[$p['permission']] = array('level'=>$p['level'],'name'=>$p['name']);
                    }
                    self::$permissions = $tmp;
                }
                return isset(self::$permissions[$module]['level']) && self::$permissions[$module]['level'] >= $level;
	}
	public static function getId() {
		return sha1(Config::getToken().date('Y-m-d'));
	}
	public static function isLogged() {
		if(isset($_SESSION[self::getId()])) {
			return true;
		}
		return false;
	}
	public static function getUserByToken($str) {
		global $db;
                $query = "select id,email,name,active from user where token='$str' and active=1 and deleted=0";
                return count($db->query($query,true))>0;
	}
}