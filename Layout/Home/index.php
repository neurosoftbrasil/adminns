<div class="modules">
<? if(Session::hasPermission('cliente',Session::VISUALIZAR)) { ?>
    <a class="button btn-square clientes" href="<?=Helper::link('cliente')?>">Clientes</a>
<? } ?>
<? if(Session::hasPermission('venda',Session::VISUALIZAR)) { ?>
	<a href="venda.html" class="button btn-square vendas">Vendas</a>
<? } ?>
<? if(Session::hasPermission('financeiro',Session::VISUALIZAR)) {?>
	<a class="button btn-square financeiro">Financeiro</a>
<?}?>
<? if(Session::hasPermission('estoque',Session::VISUALIZAR)) {?>
	<a href="estoque.html" class="button btn-square estoque">Estoque</a>
<?}?>
<? if(Session::hasPermission('usuario',Session::VISUALIZAR)) {?>
	<a class="button btn-square usuarios" href="<?=Helper::link('usuario')?>">Usuários</a>
<?}?>
<? if(Session::hasPermission('suporte',Session::VISUALIZAR)) {?>
	<a class="button btn-square suporte" href="<?=Helper::link('suporte')?>">Suporte</a>
<?}?>
<? if(Session::hasPermission('relatorio',Session::VISUALIZAR)) {?>
	<a class="button btn-square relatorios">Relatório</a>
<?}?>
<a class="button btn-square red perfil" href="<?=Helper::link('perfil')?>">Meu Perfil</a>
</div>