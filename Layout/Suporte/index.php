<?
echo Helper::js("bootstrap-dialog");
echo Helper::css("bootstrap-dialog");
echo Helper::js("App.Suporte");
?><style>
    .bootstrap-dialog-body {
        display:block;
    }
</style><?

$ident = Request::value('ident');
$pedido = Request::value('pedido');

global $db;
?>
<div class="sidebar left">
    <div class="label btn-square suporte">Suporte</div>
</div>
<style type="text/css">
    @media screen and (max-width: 520px) {
        .btn-square.suporte:before {
            content:'';
        }
    }
</style>
<div class="content">
<?php
echo Helper::js("jquery.typeahead");

$protocolo = Request::value('ident');
$protocolo = isset($protocolo)?$protocolo:false;
global $db;

$r = array(
    'tipo'=>'',
    'status'=>'',
);

if($ident) {
    $res = $db->query("select concat(nome,' - ',documento) as cliente from cliente where id=".$ident,true);
}

?><div class="row">
    <h4>Pesquisa</h4>
    <div class="col-md-12">
    <div class="cliente_group">
        <label for="cliente"><strong>Cliente *</strong></label><br/>
        <input id="cliente" class="inputTypeAhead" type="text" placeholder="Digite o nome do cliente, CNPJ ou CPF" value='<?= $ident?$res['cliente']:""; ?>' onfocus="$(this).select()"/>
        <input type='hidden' id="clienteId" name="clienteId" value=""/>
    </div>  
    <script type="text/javascript">
        $('#cliente').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            source: function (query, process) {
                return $.get(App.BasePath+'service/pedido/buscarcliente?cliente=' + query, function (data) {
                    res = JSON.parse(data);
                    return process(res.results);
                });
            }
        });
        <? if($ident) {?>
            $('#clienteId').val(<?=$ident?>);
            $.post(App.BasePath+'service/suporte/retornasuporte',{'cliente':<?=$ident?><?
                if($pedido) {
                    ?>,'pedido':<? echo $pedido;
                }
            ?>},function(data) {
                $("#vendasProdutos").html(data);
                $("#vendasSalvar").css('display','block');
            });
        <?}?>
        $('#cliente').on('typeahead:selected', function(evt, item) {
            location.href = App.BasePath+'suporte/index/'+item.id;
        });
    </script>
    </div>
</div>
<div class="row" id="vendasProdutos">
</div></div>