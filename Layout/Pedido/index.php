<h2>Pedidos</h2>
<?
    FormHelper::button('novoPedido', 'Novo Pedido', array(
        'onclick'=>'location.href="/'.APP_DIR.'pedido/inserir"',
        'class'=>'btn-primary btn-lg'
    ));
?>
<div class="row" style="clear:both">
    <div class="col-md-12">
        <label for="buscarPedido">Pesquisa</label>
        <input class="form-control buscarCliente" placeholder="Digite número do pedido ou documento do cliente" type="text" id="buscarCliente" name="buscarCliente" onkeyup="App.Cliente.Buscar()"/>
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
            <th>Tipo</th>
            <th>Nota</th>
            <th>Valor</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody id="listaPedidos">
        <?
            global $db;

            $pedidos = $db->query('select *,p.id as pedido_id,(select descricao from pedido_status where p.id = pedido_status.id) as pedido_status from cliente c, pedido p where c.id = p.cliente_id limit 50');
            foreach($pedidos as $p) {
                $query = "select numero from pedido_notafiscal where pedido_id = ".$p['pedido_id'];
                $nf = $db->query($query,true);
                ?>
        <tr>
            <td><?=$p['id']?></td>
            <td><?=$p['nome']." - ".Helper::formatDocumento($p['documento'])?></td>
            <td><?=$p['pedido_status']?></td>
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
