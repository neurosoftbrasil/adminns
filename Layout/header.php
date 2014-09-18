<html>
    <head>
        <title><?= Config::$title; ?></title>
        <meta charset="utf8"/>
        <?= Helper::css("bootstrap.min", array('media' => 'all')); ?>
        <?= Helper::css("bootstrap-theme.min", array('media' => 'all')); ?>
        <?= Helper::css("All", array('media' => 'all')); ?>
        <?= Helper::js("jquery-1.11.1.min"); ?>
        <?= Helper::js("bootstrap.min"); ?>
        <?= Helper::js("App"); ?>
    </head>
    <body>
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
                        <? if(Session::hasPermission('usuario',Session::VISUALIZAR)) { ?>
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
                            <span class="logado"><small>Você está logado como <strong><?=$_SESSION['Neurosoft']['name']?></strong></small></span>
                            <a href="<?=Helper::link('session/logout')?>" style="float:right">Sair</a>
                        </li>
                    </ul>
                    <? } ?>
                </nav>
            </div>
        </header>
        <div id="content" role="content">