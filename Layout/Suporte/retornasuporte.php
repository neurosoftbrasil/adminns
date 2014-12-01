<?php
global $db;
global $dbo;

$ident = Request::value('cliente');
$pedido = Request::value('pedido');
$produto = Request::value('produto');
$item_id = Request::value('item_id');

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
                (select nf.data from pedido_notafiscal nf where nf.pedido_id = pedido.id) as notafiscaldata,
                0 as dbold
                from pedido where cliente_id=" . $ident . " and pedido_status_id>2";
    if ($pedido) {
        $query .= " and pedido.id=" . $pedido;
    }
    $ped = $db->query($query);
    $query = "select ped_id as id,pe.numero,pe.cliente_id, pe.total as valor, pe.status, pe.data, concat(e.num_nf,'/',e.serie_nf) as notafiscal ,e.data_emissao as notafiscaldata, 1 as dbold from pedido pe, entrada e where pe.status = 'FINALIZADO' and pe.numero = e.num_ped and pe.cliente_id=$ident and e.vlr_ipi>0 and pe.cliente_id != 7424";

    if ($pedido) {
        $query .= " and pe.ped_id=" . $pedido;
    }
    $old = $dbo->query($query);

    if(count($old)==0) {
        $query = "select ped_id as id,pe.numero,pe.cliente_id, pe.total as valor, pe.status, pe.data, 1 as dbold from pedido pe where pe.cliente_id=$ident and pe.cliente_id != '7424'";
        if ($pedido) {
            $query .= " and pe.ped_id=" . $pedido;
        }
        $old = $dbo->query($query);
        if(count($old)>0) {
            $old[0]['notafiscal'] = "Sem nota";
            $old[0]['notafiscaldata'] = "0000-00-00";
        }
    }
    $ped = array_merge($ped,$old);
    $produtos = array();
    foreach ($ped as $p) {
        $newdb = $p['dbold']=="0";
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
                    <td><a href="<?="/".APP_DIR."suporte/index/".$ident?>/?pedido=<?= $p['id'] ?>"><?= $p['id'] ?></a></td>
                    <td><a href="<?="/".APP_DIR."suporte/index/".$ident?>/?pedido=<?= $p['id'] ?>"><?= $newdb ? Util::gerarNumeroPedido($p['id']):Util::gerarNumeroPedidoAntigo($p['id']) ?></a></td>
                    <td class='mobile-half'><?= Helper::timestampToDate($p['data']) ?></td>
                    <?  
                        $nf = array('ent_id'=>0);
                        if(!$newdb) {
                            $nf = $dbo->query("select * from entrada where num_nf=".$p['notafiscal']);
                            if(count($nf)>0) $nf = $nf[0];
                        }
                    ?>
                    <td><? if(isset($p['notafiscal']) && $p['notafiscal'] != "Sem nota") {?><a href="http://www.neurosoft.com.br/admns/print_entrada_visualizacao.php?ent_id=<?=$nf['ent_id']?>" target="_blank"><?}?><?= $p['notafiscal'];?><?if($p['notafiscal'] != "Sem nota"){?></a><?}?></td>
                    <td class='mobile-half'><?= Helper::timestampToDate($p['notafiscaldata']) ?></td>
                    <td class='mobile-min'><?= Helper::formatValor($p['valor']) ?></td>
                </tr>
                <tr>
                    <td colspan='6'>
                        <table class='table inside' style='background:#f3f3f3'>
                            <thead>
                                <tr>
                                    <th style='width:50%'>Produto</th>
                                    <th style='width:20%' class='mobile-min'>Garantia</th>
                                    <th class='mobile-half'>Fabricante</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                if($newdb) {
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
                                } else {
                                    $qprod = "select *, i.ip_id as ppid,p.produto_id as id,kit,nome,'' as garantia,(select fab_descri from fabricantes f where p.fabricante = f.fab_id) as fabricante from pedido_item i,produtos p where ip_ped_id=".$p['id']." and ip_produto_id = p.produto_id";
                                    if($produto) $qprod .= " and produto_id=".$produto." and ip_id = ".$item_id;
                                    $prods = $dbo->query($qprod);
                                }
                                foreach ($prods as $d) {?>
                                    <tr>
                                        <td><?= $d['nome']?></td>
                                        <td style='width:20%' class='mobile-min'><?= ($d['garantia'] != "" ? $d['garantia'] : "Sem informação") ?></td>
                                        <td class='mobile-half'><?= $d['fabricante'] ?></td>
                                        <td><?= ($d['kit'] == 0 || $d['kit']=="Sim" ? "<a class='button button-sm' onclick='App.Suporte.New(\"" . $d['ppid'] . "\", \"" . $d['id'] . "\", \"" . $p['cliente_id'] . "\")'>Novo chamado</a>" : "") ?></td>
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
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
            </table><?
            }
            if (count($ped) == 0) {
                ?>
            <table class='table'>
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
        </tbody>
    </table>
<? if($pedido && !(Request::value('semvolta'))) { ?>
    <a href="/<?=APP_DIR?>suporte/index/<?=$ident?>" class="button button-md">Voltar à lista</a>
<? }