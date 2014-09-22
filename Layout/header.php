<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title><?= Config::$title; ?></title>
        <meta charset="utf8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <?= Helper::css("bootstrap.min", array('media' => 'all')); ?>
        <?= Helper::css("bootstrap-theme.min", array('media' => 'all')); ?>
        <?= Helper::css("All", array('media' => 'all')); ?>
        <?= Helper::js("jquery-1.11.1.min"); ?>
        <?= Helper::js("bootstrap.min"); ?>
        <?= Helper::js("App"); ?>
    </head>
    <body>
        <? if(Session::isLogged()) {?><span class="logado"><small>Você está logado como <strong><?=Session::get('name')?></strong></small></span><?}?>
        <header id="top" class="navbar navbar-static-top bs-docs-nav" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle collapsed" data-target=".bs-navbar-collapse" data-toggle="collapse" type="button">
                        <span class="sr-only">Trocar Navegação</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?=Helper::link()?>">Neurosoft</a>
                </div>
                <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                    <? if(Session::isLogged()) { // dependentes de login?>
                    <ul class="nav navbar-nav">
                        <? if(Session::hasPermission('suporte',Session::VISUALIZAR)) { ?>
                            <li class="active">
                                <a href="<?=Helper::link('suporte')?>">Suporte</a>
                            </li>
                        <? } ?>
                        <? if(Session::hasPermission('usuario',Session::EXCLUIR)) { ?>
                            <li>
                                <a href="<?=Helper::link('usuario')?>">Usuários</a>
                            </li>
                        <? } ?>
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?=Helper::link('perfil')?>">Meu Perfil</a>
                        </li>
                        <li>
                            <a href="<?=Helper::link('login/logout')?>" class="btn-sair">Sair</a>
                        </li>
                    </ul>
                    <? } ?>
                </nav>
            </div>
        </header>
        <style type="text/css">
            header .navbar-brand {
                background:url("/<?=APP_DIR.VIEW_DIR."images/logo_".Config::$app.".png"?>");
            }
        </style>
        <div id="content" role="content">