<h2>Pedidos</h2>
<?
    FormHelper::button('novoPedido', 'Novo Pedido', array(
        'onclick'=>'location.href="/'.APP_DIR.'pedido/inserir"',
        'class'=>'btn-primary btn-lg'
    ));
?>
<table class="table">
    <thead>
        <tr>
            <th>Protocolo</th>
            <th>Cliente</th>
            <th>Tipo</th>
            <th>Nota</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>

