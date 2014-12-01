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
$produto = Request::value('produto');
$numserieid = Request::value('numserieid');
$ip_id = false;

global $db;
global $dbo;

?>
<div class="sidebar left">
    <div class="label btn-square suporte">Suporte</div>
    <div class="infopane">
        <h4>Suportes</h4>
        <?
            $finalizados = $db->query("select count(*) as co from suporte where finalizado='0'",true);
            $finalizados = $finalizados['co'];
            if($finalizados>0) {
                ?><a href="/<?=APP_DIR?>suporte/">Há <strong><?= $finalizados ?></strong> suporte<?=$finalizados>1?"s":""?> aberto<?=$finalizados>1?"s":""?>.</a><br/><?
            } 

            $abertos = $db->query("select count(*) as co from suporte where finalizado='1'",true);
            $abertos = $abertos['co'];
            if($abertos>0) {
                ?><a href="/<?=APP_DIR?>suporte/?finalizados=1">Há <strong><?=$abertos?></strong> suporte<?=$abertos>1?"s":""?> finalizado<?=$abertos>1?"s":""?>.</a><br/><?
            }
            ?>Todos os suportes: <strong><?=$abertos+$finalizados?></strong><?
        ?>
    </div>
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
if($numserieid) {
    $res = "select i.* from pedido_item i, pedido p where codigo='".$numserieid."' and ip_ped_id = ped_id and cliente_id != '3226' and cliente_id != '3550'";
    $res = $dbo->query($res,true);
    if(count($res)==0) {
        $ped = array(
            'ip_ped_id'=>0,
            'ip_produto_id'=>0,
            'ip_id'=>0
        );
    }
    $pedido = $res['ip_ped_id'];
    $produto = $res['ip_produto_id'];
    $ip_id = $res['ip_id'];
    $ped = "select * from pedido p, clientes c where ped_id=".$res['ip_ped_id']." and c.cliente_id = p.cliente_id";
    $ped = $dbo->query($ped,true);
}

?><div class="row">
    <h4>Pesquisa</h4>
    <div class="col-md-4">
        <label for="numSerie"><strong>Número de Série</strong></label><br/>
        <input id="numSerie" class="inputTypeAhead" type="text" placeholder="Número de série" value='' onfocus="$(this).select()"/>
        <input type='hidden' id="numSerieId" name="numSerieId" value=""/>
    </div>
    <div class="col-md-8">
    <div class="cliente_group">
        <label for="cliente"><strong>Cliente</strong></label><br/>
        <input id="cliente" class="inputTypeAhead" type="text" placeholder="Digite o nome do cliente, CNPJ ou CPF" value='<?= $ident?$res['cliente']:""; ?>' onfocus="$(this).select()"/>
        <input type='hidden' id="clienteId" name="clienteId" value=""/>
    </div>  
    <script type="text/javascript">
        $('#numSerie').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            source: function (query, process) {
                return $.get(App.BasePath+'service/suporte/buscarserie?serie=' + query, function (data) {
                    res = JSON.parse(data);
                    return process(res.results);
                });
            }
        });
        $('#numSerie').on('typeahead:selected', function(evt, item) {
            $("#numSerieId").val(item.id);
        });
        $('#numSerie').on('typeahead:selected', function(evt, item) {
            location.href = App.BasePath+'suporte/index/?numserieid='+item.id;
        });
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
        <? if($numserieid && $ped && isset($ped['cliente_id']) && $pedido && $produto) {?>
            $('#numSerieId').val(<?=$ident?>);
            $.post(App.BasePath+'service/suporte/retornasuporte',{'cliente':<?=$ped['cliente_id']?><?
                if($pedido) {?>,'pedido':<? echo $pedido;}
                if($produto) {?>,'produto':<? echo $produto;}
                if($ip_id) {?>,'item_id':<? echo $ip_id;}
                ?>},function(data) {
                $("#vendasProdutos").html(data);
                $("#vendasSalvar").css('display','block');
            });
        <?}?>
        $('#cliente').on('typeahead:selected', function(evt, item) {
            location.href = App.BasePath+'suporte/index/'+item.id;
        });
        <? if(!$ident && !$numserieid) { ?>
            $(window).load(function() {
                <? $fin = Request::value('finalizados')?'1':'0'; ?>
                $.post(App.BasePath+"service/suporte/listachamados",{'finalizados':<?=$fin?>},function(d) {
                    $("#vendasProdutos").html(d);
                });
            });
        <? } ?>
    </script>
    </div>
</div>
<div class="row" id="vendasProdutos">
    <div class='loading'></div>
</div></div>