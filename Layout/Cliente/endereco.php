<h2>Inserir endereço</h2>
<?
    $ident = Request::value('ident');
?>
<div class="row marginbottom">
    <div class="col-md-12">
        <a href="/<?=APP_DIR?>service/endereco/inserir" data-toggle="modal" data-target="#remoteModal" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Adicionar endereço</a> 
    </div>
</div>
<div class="row well-sm">
    <ul id="enderecosLista"></ul>
</div>
<div id="errorMessage" class="errorMessage well-sm alert-danger marginbottom hide"></div>
<div class="row">
    <div class="col-md-12">
        <?
            $voltar = '/'.APP_DIR.'cliente/inserir';
            if($ident) {
                $voltar = '/'.APP_DIR.'cliente/editar/'.$ident;
            }
        ?>
        <button type="button" class="btn btn-default btn-lg" onclick="location.href='<?=$voltar?>'"><span class='glyphicon glyphicon-chevron-left'></span> Voltar</button>
        <button type="button" class="btn btn-primary btn-lg" onclick="App.Cliente.SalvarEndereco(<?=$ident?>)">Adicionar contato <span class='glyphicon glyphicon-chevron-right'></span></button>
    </div>
</div>
<script type="text/javascript">
    <?
        if($ident) {
            global $db;
            $cliente = $db->query("select nome as 'Nome',documento as 'Documento',site as 'Site' from cliente where id=".$ident,true);
            $query = "select e.logradouro,e.numero,e.bairro,e.cep,e.cliente_endereco_tipo_id,e.referencia,e.complemento,(select nome from cidade where id=e.cidade_id) as cidade,(select uf from estado where id=e.estado_id) as estado from cliente_endereco e,cliente_endereco_tipo t where e.cliente_endereco_tipo_id = t.id and cliente_id=".$ident;
            $ed = $db->query($query);
            $cliente['enderecos'] = $ed;
            $cliente['contatos'] = array();
    ?>
        App.Util.EscreverCookie('cliente',<?= json_encode($cliente);?>);
    <?}?>
    App.Endereco.Rendenizar();
</script>