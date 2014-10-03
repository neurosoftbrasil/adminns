<h2>Inserir cliente</h2>
<?
$nome = Request::value('nome');
$documento = Request::value('documento');
FormHelper::create('formCliente');
FormHelper::input('nome','Nome',$nome,array(
    'placeholder'=>'Digite o nome do cliente'
));
FormHelper::input('documento',"Documento (CPF/CNPJ)",$documento,array(
    'placeholder'=>'Digite o documento'
));
?>
    <div class="row marginbottom">
        <div class="col-md-12">
            <a href="/<?=APP_DIR?>service/endereco/inserir" data-toggle="modal" data-target="#remoteModal" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span> Adicionar endereço</a> 
        </div>
    </div>
<?
FormHelper::end();
