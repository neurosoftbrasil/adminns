<?php
include('App/App.php');

// Classe de Aplicação geral
global $app;
$app = new App();
$app->run();

global $db;
Util::prints($db->query('select * from admin'));