<?php

class SuporteController extends SecureController {

    public function index() {
        
    }

    public function editar() {
        
    }

    public static function retornasuporte() {
        
    }

    public static function listachamados() {
        global $db;
        global $dbo;

        $q = "select * from suporte where finalizado='" . Request::value('finalizados') . "'";
        $sup = $db->query($q);

        foreach ($sup as $s) {
            $pi = "select ip_ped_id from pedido_item where ip_id=" . $s['produto_pedido_id'];
            $pi = $dbo->query($pi, true);
            $pe = "select cliente_id,data,numero from pedido where ped_id=" . $pi['ip_ped_id'];
            $pe = $dbo->query($pe, true);
            $en = "select serie_nf,num_nf,data_emissao from entrada where num_ped=" . $pe['numero'];
            $en = $dbo->query($en, true);
            $pr = "select p.garantia,p.nome,(select descricao from fabricante f where f.id=p.fabricante_id) as fabricante from produto p where id=" . $s['produto_id'];
            $pr = $db->query($pr, true);
            SuporteController::printarPedido($s,$pi,$pe,$en,$pr);
        }
    }
    public static function printarPedido($suporte,$pedido_item,$pedido,$entrada,$produto) {
        $s = $suporte;
        $pi = $pedido_item;
        $pe = $pedido;
        $en = $entrada;
        $pr = $produto;
        ?><table class="table">
                <thead>
                    <tr>
                        <th>Id.</th>
                        <th>N. Pedido</th>
                        <th class="mobile-half">Data</th>
                        <th>N.F.</th>
                        <th class="mobile-half">Data N.F.</th>
                        <th class="mobile-min">Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="/adminns/suporte/index/<?= $pe['cliente_id'] ?>/?pedido=<?= $pi['ip_ped_id'] ?>"><?= $pi['ip_ped_id'] ?></a>
                        </td>
                        <td>
                            <a href="/adminns/suporte/index/<?= $pe['cliente_id'] ?>/?pedido=<?= $pi['ip_ped_id'] ?>">P00000000064</a>
                        </td>
                        <td class="mobile-half"><?= Helper::timestampToDate($pe['data']); ?></td>
                        <td>
                            <a target="_blank" href="http://www.neurosoft.com.br/admns/print_entrada_visualizacao.php?ent_id=<?= $en['num_nf'] ?>"><?= $en['num_nf'] ?>/<?= $en['serie_nf'] ?></a>
                        </td>
                        <td class="mobile-half"><?= Helper::timestampToDate($en['data_emissao']) ?></td>
                        <td class="mobile-min"><?= Helper::formatValor($s['valor']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <table class="table inside" style="background:#f3f3f3">
                                <thead>
                                    <tr>
                                        <th style="width:50%">Produto</th>
                                        <th class="mobile-min" style="width:20%">Garantia</th>
                                        <th class="mobile-half">Fabricante</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?
                                            echo $pr['nome'];
                                            ?>
                                        </td>
                                        <td class="mobile-min" style="width:20%">
                                            <?= $pr['garantia'] == "" ? "Sem informação" : $pr['garantia']; ?>
                                        </td>
                                        <td class="mobile-half">
                                            <?= $pr['fabricante']; ?>
                                        </td>
                                        <td>
                                            <a class="button button-sm" onclick="App.Suporte.New('<?= $s['produto_pedido_id'] ?>', '<?= $s['produto_id'] ?>', '<?= $pe['cliente_id'] ?>')">Novo chamado</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">
                                            <?=
                                              SuporteController::suporteProduto($s['produto_pedido_id'],$s['produto_id'])
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table><?
    }
    public function buscarserie() {
        global $dbo;
        $serie = Request::value('serie');
        $q = "select codigo as id,codigo as value from pedido_item i,pedido p where i.ip_ped_id = p.ped_id and p.cliente_id != 7709 and lower(i.codigo) like '".strtolower($serie)."%' limit 20";
        $q = $dbo->query($q);
        $res = array("results"=>$q);
        echo json_encode($res);

    }
    public static function salvar() {
        global $db;
        $ident = Request::value('ident');
        $suporte_categoria_id = Request::value('suporte_categoria_id');
        $suporte_status_id = Request::value('suporte_status_id');
        $contato_id = Request::value('contato_id');
        $produto_pedido = Request::value('pedido');
        $produto = Request::value('produto');
        $valor = Request::value('valor');
        $custo = Request::value('custo');
        $finalizar = Request::value('finalizar');
        $erros = array();
        if ($suporte_categoria_id == "0") {
            array_push($erros, array(
                'suporte_categoria_id' => 'Selecione uma categoria'
            ));
        }
        if ($suporte_status_id == "0") {
            array_push($erros, array(
                'suporte_status_id' => 'Selecione um status'
            ));
        }
        if ($contato_id == "0") {
            array_push($erros, array(
                'contato_id' => 'Selecione um contato'
            ));
        }
        if (count($erros) > 0) {
            $ret = array(
                'status' => 'error',
                'message' => 'Existem erros no formulário',
                'details' => json_encode($erros)
            );
            echo json_encode($ret);
            die();
        }
        if ($ident) {
            $query = "update suporte set ";
            $query .= "suporte_categoria_id='" . Request::value('suporte_categoria_id') . "', ";
            $query .= "suporte_status_id='" . Request::value('suporte_status_id') . "', ";
            $query .= "contato_id='" . Request::value('contato_id') . "', ";
            $query .= "user_id='" . Session::get('id') . "', ";
            $query .= "valor='" . Request::value('valor') . "',";
            $query .= "custo='" . Request::value('custo') . "' ";
            $query .= ", finalizado='".$finalizar."' ";
            $query .= "where id=" . $ident;
            $db->query($query);
        } else {
            $query = "insert into suporte (
                produto_id,
                produto_pedido_id,
                contato_id,
                suporte_status_id,
                ranking,
                user_id,
                suporte_categoria_id,
                custo,
                valor
            ) values (
                '$produto',
                '$produto_pedido',
                '$contato_id',
                '$suporte_status_id',
                '-1',
                '" . Session::get('id') . "',
                '$suporte_categoria_id',
                '$custo',
                '$valor'
            )";
            $ident = $db->query($query);
        }
        $obs = Request::value('observacao');
        if ($obs) {
            $query = "insert into suporte_observacao (observacao,user_id,suporte_id) values (
                    '" . Request::value('observacao') . "',
                    '" . Session::get('id') . "',
                    '" . $ident . "'
                )";
            $db->query($query);
        }
        $ret = array();
        $ret['status'] = "success";
        $ret['message'] = "O suporte foi gravado com êxito.";

        $cl = $db->query('select cliente_id from contato where id=' . $contato_id, true);
        $cl = $cl['cliente_id'];
        $ret['redirect'] = "/" . APP_DIR . "suporte/index/" . $cl;
        echo json_encode($ret);
    }

    public static function suporteProduto($ppid, $num) {
        global $db;
        $qsuporte = "select 
                            id,
                            produto_pedido_id,
                            contato_id,
                            (select nome from contato where id=contato_id) as contato,
                            suporte_status_id,
                            (select descricao from suporte_status where id=suporte_status_id) as status,
                            ranking,
                            user_id,
                            (select name from user where id=user_id) as atendente,
                            suporte_categoria_id,
                            (select nome from suporte_categoria where id=suporte_categoria_id) as categoria,
                            datahora,custo,valor,finalizado
                        from suporte where produto_pedido_id=" . $ppid . " and produto_id=" . $num;
        $qsuporte = $db->query($qsuporte);

        $html = "";
        if (count($qsuporte) > 0) {
            ?><tr>
                <td colspan='4'>
                    <table class='table inside'>
                        <thead>
                            <tr>
                                <th style="width:20%">Nome</th>
                                <th style="width:20%" class='mobile-half'>Categoria</th>
                                <th style="width:20%" class='mobile'>Status</th>
                                <th style="width:10%">Data</th>
                                <th style="width:10%">Atendente</th>
                                <th style="width:10%" class='mobile-half'>Situação</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody><?
                            foreach ($qsuporte as $s) {
                                ?><tr>
                                    <td><?= $s['contato'] ?></td>
                                    <td class='mobile-half'><?= $s['categoria'] ?></td>
                                    <td class='mobile'><?= $s['status'] ?></td>
                                    <td><?= Helper::timestampToDate($s['datahora'], true) ?></td>
                                    <td><?= $s['atendente'] ?></td>
                                    <td class='mobile-half'><?= $s['finalizado'] == "1" ? "Finalizado" : "Aberto"; ?></td>
                                    <td><a href='javascript:void(0)' onclick='App.Suporte.View("<?= $s['id'] ?>", "<?= $num ?>", "<?= $s['contato_id'] ?>")'>Visualizar</a></td>
                                </tr><?
                            }
                            ?>
                        </tbody>
                    </table>
                </td>
            </tr>
            <?
        }
    }

}
