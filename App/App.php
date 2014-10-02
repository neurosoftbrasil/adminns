<?php

include('Library/Util.php');
include('Config.php');
include('Library/Db.php');
include('Library/Request.php');
include('Library/Helper.php');
include('Library/FormHelper.php');
include('Library/Session.php');
include('Library/Router.php');
include('App/Controller/AppController.php');
include('App/Model/AppModel.php');

class App {
    public $db;
    public $dbo;
    public $config;
    public $request;
    public $router;
    
    public function run() {
        // Binder do banco
        $this->db = new Database();
        global $db;
        $db = $this->db;
        
        $this->dbo = new DatabaseOld();
        global $dbo;
        $dbo = $this->dbo;
        
        // Binder dos configs
        $this->config = (object) array(
            'db'=>Config::db(),
            'email'=>Config::email()
        );
        // Binder do request
        Request::build();

        // Binder do router
        $this->router = new Router();
    }
}