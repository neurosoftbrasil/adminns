<?
class Request {
	public static $get;
	public static $post;
	public static $option = NULL;

	public static function build() {
		self::$get = (object) $_GET;
		self::$post = (object) $_POST;

		// pegando parametros via :(:+) ou ?(&+)
		$options = self::$get;
		unset($options->controller);
		unset($options->method);
		unset($options->ident);
		if(preg_match("/[:]/",$_SERVER['REDIRECT_URL'])) {
			$opt = array();
			$tmp = explode(":",$options->options);
			foreach($tmp as $t) {
				$o = explode("=",$t);
				$o[1] = isset($o[1])?$o[1]:TRUE;
				$opt[$o[0]] = $o[1];
			}
			$options = (object) $opt;
		} else {
			foreach($options as $key=>$value) {
				if($value == "") {
					$options->{$key} = TRUE;
				}
			}
		}
		self::$option = $options;
	}

	public static function get($str) {
		return isset(self::$get->{$str})?self::$get->{$str}:NULL;
	}
	public static function post($str) {
		return isset(self::$get->{$str})?self::$get->{$str}:NULL;
	}
	public static function getOptions() {
		return isset(self::$option)?self::$option:NULL;
	}
	public static function value($str) {
		if(isset(self::$get->{$str})) {
			return self::$get->{$str};
		} else if(isset(self::$post->{$str})) {
			return self::$post->{$str};
		} else if(isset(self::$option->{$str})) {
			return self::$option->{$str};
		}
		return NULL;
	}
}