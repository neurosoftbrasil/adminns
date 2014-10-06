<h2>Inserir cliente</h2>
<?
$nome = Request::value('nome');
$documento = Request::value('documento');
$site = Request::value('site');

FormHelper::create('formCliente','endereco');
FormHelper::input('nome','Nome <strong>*</strong>',$nome,array(
    'placeholder'=>'Digite o nome do cliente'
));
FormHelper::input('documento',"Documento (CPF/CNPJ) <strong>*</strong>",$documento,array(
    'placeholder'=>'Digite o documento',
    'onkeyup'=>'App.Util.FormatarDocumento(this)'
));
FormHelper::input('site',"Site <strong>*</strong>",$site,array(
    'placeholder'=>'www.site.com'
));
?>
<div class="well-sm alert-danger hide">
    Há problemas no formulário
</div>
<?
FormHelper::submit('formClienteSubmit', "Adicionar endereços <span class='glyphicon glyphicon-chevron-right'></span> ", array(
    'class'=>'btn-primary btn-lg',
    'onclick'=>'App.Cliente.Submit()',
    'type'=>'button'
));
?>
<script type="text/javascript">
    App.Cliente.Recuperar();
</script>