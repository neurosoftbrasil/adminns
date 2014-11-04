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
        <script type="text/javascript">
            App.BasePath = "/<?=APP_DIR?>";
            App.SiteKey = "<?=Session::getId()?>";
        </script>
    </head>
    <body>
        <header id="top" class="navbar navbar-static-top bs-docs-nav" role="banner">
            <div class="top">
                <h1>
                    <a href="<?="/".APP_DIR?>"> Neurosoft </a>
                </h1>
                <? if(Session::isLogged()) {?><span class="padding greeting"><span class="mobile-min">Bem-vindo </span><?=Session::get('name')?></span>
                <span class="padding right">
                    <a class="button topright" href="<?=Helper::link('login/logout')?>">Sair</a>
                </span>
                <?}?>
            </div>
            <div class="container">
                <? /*<div class="navbar-header">
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
                        <? if(Session::hasPermission('cliente',Session::VISUALIZAR)) { ?>
                            <li class='<?=Request::get('controller')=="cliente"?'active':'';?>'>
                                <a href="<?=Helper::link('cliente')?>">Cliente</a>
                            </li>
                        <? } ?>
                        <? if(Session::hasPermission('pedido',Session::VISUALIZAR)) { ?>
                            <li class='<?=Request::get('controller')=="pedido"?'active':'';?>'>
                                <a href="<?=Helper::link('pedido')?>">Pedido</a>
                            </li>
                        <? } ?>
                        <? if(Session::hasPermission('suporte',Session::VISUALIZAR)) { ?>
                            <li class='<?=Request::get('controller')=="suporte"?'active':'';?>'>
                                <a href="<?=Helper::link('suporte')?>">Suporte</a>
                            </li>
                        <? } ?>
                        
                        <? if(Session::hasPermission('usuario',Session::EXCLUIR)) { ?>
                            <li class='<?=Request::get('controller')=="usuario"?'active':'';?>'>
                                <a href="<?=Helper::link('usuario')?>">Usuários</a>
                            </li>
                        <? } ?>
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="<?=Helper::link('perfil')?>">Meu Perfil</a>
                        </li>
                        <li>

                            <a?>" class="">Sair</a>
                        </li>
                    </ul>
                    <? } ?>
                </nav>
                */?>
            </div>
        </header>
        <style type="text/css">
        div.top h1 a{
            display:block;
            float:left;
            width:41px;
            height:41px;
            background:url(<?="/".APP_DIR;?>Layout/images/logo.gif) no-repeat;
            text-indent:-999em;
        }
        </style>
        <div id="content" role="content">