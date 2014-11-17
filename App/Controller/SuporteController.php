<?php

class SuporteController extends SecureController {
    public function index() {}
    public function editar() {}
    public static function retornasuporte() {}
    public static function salvar() {
        global $db;
        $ident = Request::value('ident');
        $suporte_categoria_id = Request::value('suporte_categoria_id');
        $suporte_status_id = Request::value('suporte_status_id');
        $contato_id = Request::value('contato_id');
        $pedido = Request::value('pedido');
        $produto = Request::value('produto');
        $valor = Request::value('valor');
        $custo = Request::value('custo');
        
        $erros = array();
        
        if($suporte_categoria_id == "0") {
            array_push($erros,array(
                'suporte_categoria_id'=>'Selecione uma categoria'
            ));
        }
        if($suporte_status_id == "0") {
            array_push($erros,array(
                'suporte_status_id'=>'Selecione um status'
            ));
        }
        if($contato_id == "0") {
            array_push($erros,array(
                'contato_id'=>'Selecione um contato'
            ));
        }
        if(count($erros)>0) {
            $ret = array(
                'status'=>'error',
                'message'=>'Existem erros no formulário',
                'details'=>  json_encode($erros)
            );
            echo json_encode($ret);
            die();
        }
        if($ident) {
            $query  = "update suporte set ";
            $query .= "suporte_categoria_id='".Request::value('suporte_categoria_id')."', ";
            $query .= "suporte_status_id='".Request::value('suporte_status_id')."', ";
            $query .= "contato_id='".Request::value('contato_id')."' ";
            $query .= "where id=".$ident;
        } else {
            $ident = "insert into suporte (
                produto_id,
                pedido_id,
                contato_id,
                contato_tipo_id,
                suporte_status_id,
                ranking,
                user_id,
                suporte_categoria_id,
                datahora,
                custo,
                valor
            ) values (
                '$produto',
                '$pedido',
                '$contato_id',
                '1',
                '$suporte_status_id',
                '-1',
                '".Session::getId()."',
                '$suporte_categoria_id',
                '$custo',
                '$valor'
            )";
        }
        
        $obs = Request::value('observacao');
        if($obs) {
                $query = "insert into suporte_observacao (observacao,user_id,suporte_id) values (
                    '".Request::value('observacao')."',
                    '".Session::getId()."',
                    '".$ident."'
                )";
                echo $query;
        }
    }
    public static function suporteProduto($num) {
        global $db;
        $qsuporte = "select 
                            id,
                            produto_id,
                            pedido_id,
                            contato_id,
                            (select nome from contato where id=contato_id) as contato,
                            contato_tipo_id,
                            (select descricao from contato_tipo where id=contato_tipo_id) as contato_tipo,
                            suporte_status_id,
                            (select descricao from suporte_status where id=suporte_status_id) as status,
                            ranking,
                            user_id,
                            (select name from user where id=user_id) as atendente,
                            suporte_categoria_id,
                            (select nome from suporte_categoria where id=suporte_categoria_id) as categoria,
                            datahora,custo,valor
                        from suporte where produto_id=" . $num;
        $qsuporte = $db->query($qsuporte);
        $html = "";
        if (count($qsuporte) > 0) {
            ?><tr>
                                <td colspan='4'>
                                    <table class='table inside'>
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th class='mobile-half'>Categoria</th>
                                                <th class='mobile'>Status</th>
                                                <th>Data</th>
                                                <th>Atendente</th>
                                                <th class='mobile-half'>Ranking</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody><?
            foreach ($qsuporte as $s) {
                ?><tr>
                    <td><?=$s['contato']?></td>
                    <td class='mobile-half'><?= $s['categoria']?></td>
                    <td class='mobile'><?= $s['status']?></td>
                    <td><?= Helper::timestampToDate($s['datahora'],true) ?></td>
                    <td><?= $s['atendente']?></td>
                    <td class='mobile-half'><?= $s['ranking']?></td>
                    <td><a href='javascript:void(0)' onclick='App.Suporte.View("<?=$s['id']?>","<?= $num ?>","<?=$s['contato_id']?>")'>Visualizar</a></td>
                </tr><?
            }
            ?></tbody></table></td></tr><?
        }
    }
}
