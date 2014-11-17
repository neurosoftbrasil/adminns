<?
class Request {
	public static $get;
	public static $post;
	public static $option = NULL;
        
	public static function build() {
		self::$get = (object) $_GET;
		self::$post = (object) $_POST;
               
                // pegando parametros via :(:+) ou ?(&+)
		$options = clone self::$get;
                
		unset($options->controller);
		unset($options->method);
		unset($options->ident);
                
                if(preg_match("/[:]/",$_SERVER['REQUEST_URI'])) {
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
		self::$option = empty($options)?NULL:$options;
	}
        public static function postEscaped() {
            $bind = array();
            foreach($_POST as $key => $value) {
                $bind[$key] = mysql_real_escape_string($value);
            }
            return $bind;
        }
        public static function getEscaped() {
            $bind = array();
            foreach($_GET as $key => $value) {
                $bind[$key] = mysql_real_escape_string($value);
            }
            return $bind;
        }
	public static function path() {
		return $_SERVER['REQUEST_URI'];
	}
	public static function get($str) {
		return isset(self::$get->{$str}) && trim(self::$get->{$str})!=""?self::$get->{$str}:NULL;
	}
	public static function post($str) {
                return isset(self::$post->{$str}) && trim(self::$post->{$str})!=""?self::$post->{$str}:NULL;
	}
	public static function getOptions() {
		return isset(self::$option)?self::$option:NULL;
	}
	public static function value($str) {
		if(isset(self::$get->{$str}) && trim(self::$get->{$str})!="") {
			return self::$get->{$str};
		} else if(isset(self::$post->{$str}) && trim(self::$post->{$str})!="") {
			return self::$post->{$str};
		} else if(isset(self::$option->{$str}) && trim(self::$option->{$str})!="") {
			return self::$option->{$str};
		}
		return NULL;
	}
}