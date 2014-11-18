<?php
global $db;
$ident = Request::value('cliente');
$pedido = Request::value('pedido');

$ped = false;
$html = "";

if ($ident) {
    $query = "select id,data,soma,valor,desconto,
                pedido_formapagamento_id,
                (select descricao from pedido_formapagamento where pedido_formapagamento_id=id) as pedido_formapagamento,
                pedido_status_id,
                cliente_id,
                (select documento from cliente where id=cliente_id) as cliente_documento,
                pedido_tipo_id,
                (select descricao from pedido_tipo where id=pedido_tipo_id) as pedido_tipo,
                (select numero from pedido_notafiscal nf where nf.pedido_id = pedido.id) as notafiscal,
                (select nf.data from pedido_notafiscal nf where nf.pedido_id = pedido.id) as notafiscaldata
                from pedido where cliente_id=" . $ident . " and pedido_status_id>2";
    if ($pedido) {
        $query .= " and pedido.id=" . $pedido;
    }
    $ped = $db->query($query);
    $produtos = array();

    foreach ($ped as $p) {
        ?><table class='table'>
            <thead>
                <tr>
                    <th>Id.</th>
                    <th>N. Pedido</th>
                    <th class='mobile-half'>Data</th>
                    <th>N.F.</th>
                    <th class='mobile-half'>Data N.F.</th>
                    <th class='mobile-min'>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= Util::gerarNumeroPedido($p['id']) ?></td>
                    <td class='mobile-half'><?= Helper::timestampToDate($p['data']) ?></td>
                    <td><?= $p['notafiscal'] ?></td>
                    <td class='mobile-half'><?= Helper::timestampToDate($p['notafiscaldata']) ?></td>
                    <td class='mobile-min'><?= Helper::formatValor($p['valor']) ?></td>
                </tr>
                <tr><td colspan='6'><table class='table inside' style='background:#f3f3f3'>
                            <thead>
                                <tr>
                                    <th style='width:50%'>Produto</th>
                                    <th style='width:20%' class='mobile-min'>Garantia</th>
                                    <th class='mobile-half'>Fabricante</th>
                                    <th></th>
                                </tr>
                            </thead><tbody>
                                <?
                                $qprod = "select 
                                    p.id,
                                    p.nome,
                                    p.garantia,
                                    p.kit,
                                    p.fabricante_id,
                                    (select f.descricao from fabricante f where fabricante_id = f.id) as fabricante,
                                    pp.id as ppid
                                    from produto p, 
                                    produto_pedido pp 
                                    where pp.pedido_id=" . $p['id'] . " and p.id = pp.produto_id";
                                
                                $prods = $db->query($qprod);
                                foreach ($prods as $d) {
                                    ?>
                                    <tr>
                                        <td> <?= ($d['kit'] > 0 ? "Kit " : "") . $d['nome'] ?></td>
                                        <td style='width:20%' class='mobile-min'><?= ($d['garantia'] != "" ? $d['garantia'] : "Sem informação") ?></td>
                                        <td class='mobile-half'><?= $d['fabricante'] ?></td>
                                        <td><?= ($d['kit'] == 0 ? "<a class='button button-sm' onclick='App.Suporte.New(\"" . $d['ppid'] . "\", \"" . $d['id'] . "\", \"" . $p['cliente_id'] . "\")'>Novo chamado</a>" : "") ?></td>
                                    </tr>
                                    <?
                                    if ($d['kit'] > 0) {
                                        $qkit = "select 
                                            p.id,
                                            p.nome,
                                            p.garantia,
                                            (select f.descricao from fabricante f where p.fabricante_id = f.id) as fabricante 
                                            from produto p where p.produto_id = ".$d['id'];
                                        $qkit = $db->query($qkit);
                                        
                                        foreach ($qkit as $k) {
                                            ?><tr>
                                                <td> &bull; <?= $k['nome']; ?></td>
                                                <td style='width:20%' class='mobile-min'><?= $k['garantia']==""?"Sem informação":$k['garantia']; ?></td>
                                                <td class='mobile-half'><?= $k['fabricante']; ?></td>
                                                <td><a class='button button-sm' onclick='App.Suporte.New("<?= $d['ppid'] ?>", "<?= $k['id'] ?>", "<?= $p['cliente_id'] ?>")'>Novo chamado</a></td>
                                            </tr><?
                                            echo SuporteController::suporteProduto($d['ppid'],$k['id']);
                                        }
                                    } else {
                                        echo SuporteController::suporteProduto($d['ppid'],$d['id']);
                                    }
                                }
                                ?></tbody></table></td></tr><?
            }
            if (count($ped) == 0) {
                ?><table class='table'>
                <thead>
                    <tr>
                        <th>Id.</th>
                        <th>N. Pedido</th>
                        <th class='mobile-half'>Data</th>
                        <th>N.F.</th>
                        <th class='mobile-half'>Data N.F.</th>
                        <th class='mobile-min'>Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan='6'>Não há pedidos registrados no nome deste cliente</td>
                    </tr><?
                }
            }
            ?>
        </tbody></table>
