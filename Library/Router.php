<?

class Router {

    private $route;

    public function __construct() {
        $this->route = (object) array();
        // Controller name
        $controller = Request::get('controller') ? Request::get('controller') : 'home';
        $this->route->controller = $controller;
        Request::$get->controller = $controller;
        
        $method = Request::get('method') ? Request::get('method') : 'index';
        $this->route->method = $method;
        Request::$get->method = $method;
        
        if (Request::get('ident') !== NULL) {
            $this->route->ident = Request::get('ident');
        }
        if (Request::getOptions() !== NULL) {
            $this->route->options = Request::getOptions();
        }
        $this->load();
    }
    
    public function load() {
        $controllerName = ucwords(Helper::controllerName($this->route->controller));
        $controllerPath = CONTROLLER_DIR . $controllerName . ".php";
        $method = $this->route->method;
        include(CONTROLLER_DIR . "SecureController.php");
        if(file_exists($controllerPath)) {
            include($controllerPath);
        } else {
            Router::redirect('home');
        }
        $controller = new $controllerName();
        $controller->$method();
        $controller->layout();
    }
    public function redirect($controller='home',$method='index',$options="") {
        header("Location: /".APP_DIR.$controller."/".$method.$options."");
    }
}