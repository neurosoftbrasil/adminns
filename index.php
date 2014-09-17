<?php

ob_start(); // php streaming
session_start(); // inicia sessao

define('APP_DIR','adminns/');
define('CONTROLLER_DIR','App/Controller/');
define('VIEW_DIR','Layout/');

include('App/App.php');

// Classe de Aplicação geral
global $app;
$app = new App();
$app->run();

global $db;

// TESTES DE CODIGO:
