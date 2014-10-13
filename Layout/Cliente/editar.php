<h2>Editar cliente</h2>
<?php
$id = Request::value('ident');
global $db;

$c = $db->query("select * from cliente where id=".$id,true);

FormHelper::create('formCliente','/'.APP_DIR.'cliente/endereco');
FormHelper::input('nome','Nome <strong>*</strong>',$c['nome'],array(
    'placeholder'=>'Digite o nome do cliente'
));
FormHelper::input('documento',"Documento (CPF/CNPJ) <strong>*</strong>",$c['documento'],array(
    'placeholder'=>'Digite o documento',
    'onkeyup'=>'App.Util.FormatarDocumento(this)'
));
FormHelper::input('site',"Site <strong>*</strong>",$c['site'],array(
    'placeholder'=>'www.site.com'
));
?>
<input type="hidden" id="ident" name="ident" value="<?=$c['id']?>"/>
<div class="well-sm alert-danger hide">
    Há problemas no formulário
</div>
<?
FormHelper::submit('formClienteSubmit', "Adicionar endereços <span class='glyphicon glyphicon-chevron-right'></span> ", array(
    'class'=>'btn-primary btn-lg',
    'onclick'=>'App.Cliente.Submit()',
    'type'=>'button'
));

