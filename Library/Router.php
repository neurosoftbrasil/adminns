<?

class Router {
	private $route;

	public function __construct() {
		$this->route = (object) array();
		// Controller name
		$controller = Request::get('controller')?Request::get('controller'):'home';
		$this->route->controller = $controller;

		$method = Request::get('method')?Request::get('method'):'index';
		$this->route->method = $method;

		if(Request::get('ident') !== NULL) {
			$this->route->ident = Request::get('ident');
		}
		if(Request::getOptions() !== NULL) {
			$this->route->options = Request::getOptions();
		}
		var_dump($this->route);
	}

	public function load() {
		
	}
}