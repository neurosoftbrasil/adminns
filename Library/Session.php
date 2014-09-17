<?
class Session {
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
	public static function hasPermission() {
		
	}
}