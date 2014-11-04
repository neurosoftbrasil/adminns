<h2>Pedidos</h2>
<?
    Helper::js('App.Pedido');
    FormHelper::button('novoPedido', 'Novo Pedido', array(
        'onclick'=>'location.href="/'.APP_DIR.'pedido/inserir"',
        'class'=>'btn-primary btn-lg'
    ));
?>
<div class="row" style="clear:both">
    <div class="col-md-12">
        <label for="buscarPedido">Pesquisa</label>
        <input class="form-control buscarPedido" placeholder="Digite número da nota fiscal ou nome do cliente" type="text" id="buscarPedido" name="buscarPedido" onkeyup="App.Pedido.Buscar()"/>
    </div>
</div>
<br/>
<div class="row" style="clear:both">
<div class="col-md-12">
    <div class="panel panel-default">
    <table class="table">
    <thead>
        <tr>
            <th>Protocolo</th>
            <th>Cliente</th>
            <th>Status</th>
            <th>Tipo</th>
            <th>NF</th>
            <th>Valor</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody id="listaPedidos">
        <?
            global $db;

            $pedidos = $db->query('select *,p.id as pedido_id,(select descricao from pedido_status where p.id = pedido_status.id) as pedido_status, (select descricao from pedido_tipo where p.pedido_tipo_id = id) as pedido_tipo from cliente c, pedido p where c.id = p.cliente_id limit 50');
            foreach($pedidos as $p) {
                $query = "select numero from pedido_notafiscal where pedido_id = ".$p['pedido_id'];
                $nf = $db->query($query,true);
                ?>
        <tr>
            <td><?=$p['id']?></td>
            <td><a href='<?="/".APP_DIR."pedido/editar/".$p['id']."/:clienteId=".$p['id']?>'><?=$p['nome']." - ".Helper::formatDocumento($p['documento'])?></a></td>
            <td><?=$p['pedido_status']?></td>
            <td><?=$p['pedido_tipo']?></td>
            <td><?=$nf['numero']?></td>
            <td><?=Helper::formatValor($p['valor'])?></td>
            <td><?=Helper::timestampToDate($p['data'])?></td>
        </tr>
        <?
            }
        ?>
    </tbody>
</table>
    </div>
</div>