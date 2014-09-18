<?
class Session {
	const VISUALIZAR = 1;
	const INSERIR = 2;
	const EDITAR = 3;
	const EXCLUIR = 4;

	public static function password($str) {
		return sha1(Config::getToken().md5($str));
	}
	public static function destroy() {
		session_destroy();
		Router::redirect('login');
	}
	public static function token($str) {
		return sha1(sha1(Config::getToken()).$str);
	}
	public static function auth($login,$password) {
		$passwd = self::password($password);
	}
	public static function hasPermission($module,$level) {
		return true;
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
		return true;
	}
}