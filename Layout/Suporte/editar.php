<?
global $db;

$ident = Request::value('ident');
$pedido = Request::value('pedido');
$produto = Request::value('produto');
$cliente = Request::value('cliente');
$contato = Request::value('contato');

$r = array(
    'suporte_categoria_id' => 0,
    'suporte_status_id' => 0,
    'custo'=>"",
    'valor'=>"",
);

if ($ident) {
    $r = "select * from suporte where id=" . $ident;
    $r = $db->query($r, true);
}

FormHelper::create('formSuporte');
?>
<input type="hidden" name="ident" value="<?= $ident ?>"/>
<input type="hidden" name="produto" value="<?= $produto ?>"/>
<input type="hidden" name="pedido" value="<?= $pedido ?>"/>
<label>Produto</label><br/>
<?
$sql = $db->query("select * from produto where id=" . $produto, true);
echo $sql['nome'];
?>
<br/>
<label>Cliente</label><br/>
<?
if ($contato) {
    $sql = "select c.* from contato t, cliente c where t.id=" . $contato . " and t.cliente_id=c.id";
    $sql = $db->query($sql, true);
    echo $sql['nome'];
} else {
    $sql = "select * from cliente where id=" . $cliente;
    $sql = $db->query($sql, true);
    echo $sql['nome'];
}
?>
<br/>
<?
FormHelper::selectFromTable('suporte_categoria.id', 'nome', 'Categoria', $r['suporte_categoria_id'], array(
    'style' => 'width:270px',
    'validation'=>array(
            'regex'=>FormHelper::IS_SELECTED,
            'message'=>'Selecione uma <strong>categoria</strong>.'
    )
));
FormHelper::selectFromTable('suporte_status.id', 'descricao', 'Status', $r['suporte_status_id'], array(
    'style' => 'width:270px',
    'validation'=> array(
            'regex'=>FormHelper::IS_SELECTED,
            'message'=>'Selecione um <strong>status</strong>.'
    )
));
if ($contato) {
    $cont = $db->query("select nome from contato where id=" . $r['contato_id'], true);
    ?>
    <label>Cliente</label>
    <br>
    <?= $cont['nome'] ?>
    <br>
    <?
    //FormHelper::selectFromTable('contato.id','nome','Contato',$r['contato_id'],array(),array('id'=>$r['contato_id']));
} else {
    FormHelper::selectFromTable('contato.id', 'nome', 'Contato', $cliente, array(
        'validation'=> array(
            'regex'=>FormHelper::IS_SELECTED,
            'message'=>'Selecione um <strong>contato</strong>.'
        )
    ), array(
        'cliente_id' => $cliente
    ));
}
?>
<div class="row">
    <div class="col-md-6">
        <? 
        FormHelper::input('valor', 'Valor', $r['valor'], array());
        ?>
    </div>
    <div class="col-md-6">
        <? 
        FormHelper::input('custo', 'Custo', $r['custo'], array());
        ?>
    </div>
</div>
<?
FormHelper::textarea('observacao', 'Observação', '', array());
$obs = array();
if ($ident) {
    $obs = "select 
    observacao,
    datetime,
    (select name from user where o.user_id = id) as usuario
    from suporte_observacao o where suporte_id = " . $ident . " order by datetime desc";
    $obs = $db->query($obs);
}
    FormHelper::submitAjax('Salvar', 'salvar', array(
    'class' => 'button',
    'style' => 'margin:5px'
));
FormHelper::end();
?>
<table class="table">
    <thead>
        <tr>
            <th>Data/Hora</th>
            <th>Observação</th>
            <th class="mobile-min">Usuário</th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($obs as $o) { ?>
            <tr>
                <td><?= Helper::timestampToDate($o['datetime'], true) ?></td>
                <td><?= $o['observacao'] ?></td>
                <td class="mobile-min"><?= $o['usuario'] ?></td>
            </tr>
        <? }
        if (count($obs) == 0) {
            ?>
            <tr>
                <td colspan="3">Ainda não há observações</td>
            </tr>
<? } ?>
    </tbody>
</table>    
</div>