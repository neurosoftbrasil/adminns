<?php
include("App/Model/Pedido.php");

class PedidoController extends SecureController {
    public static function inserir() {
        $pedidoLista = new Pedido();
    }
    public static function editar() {
        
    }
    public static function buscar() {
            global $db;
            $condicao = "";
            $pesquisa = Request::value('pesquisa');
            $pedidos = "select *,p.id as pedido_id,(select descricao from pedido_status where p.id = pedido_status.id) as pedido_status from cliente c, pedido p where c.id = p.cliente_id limit 50";
            if(preg_match("/^[0-9]+$/",$pesquisa)) {
                $pedidos = "select *,p.id as pedido_id,(select descricao from pedido_status where p.id = pedido_status.id) as pedido_status from cliente c, pedido p, pedido_notafiscal n where c.id = p.cliente_id and n.numero like '$pesquisa%' limit 50";
            } else if(trim($pedidos) != ""){
                $pesquisa = Helper::prepareForLike($pesquisa);
                $pedidos = "select *,p.id as pedido_id,(select descricao from pedido_status where p.id = pedido_status.id) as pedido_status from cliente c, pedido p where c.id = p.cliente_id and c.nome like '%$pesquisa%' limit 50";
            }
            $pedidos = $db->query($pedidos);
            
            foreach($pedidos as $p) {
                $query = "select numero from pedido_notafiscal where pedido_id = ".$p['pedido_id'];
                $nf = $db->query($query,true);
        ?>
        <tr>
            <td><?=$p['pedido_id']?></td>
            <td><?=$p['nome']." - ".Helper::formatDocumento($p['documento'])?></td>
            <td><?=$p['pedido_status']?></td>
            <td><?=$nf['numero']?></td>
            <td><?=Helper::formatValor($p['valor'])?></td>
            <td><?=Helper::timestampToDate($p['data'])?></td>
        </tr>
        <?
            }
            if(count($pedidos)==0) {
                ?><tr>
                    <td colspan="6">Não há resultados.</td>
                </tr><?
            }
    }
    public static function buscarcliente() {
        global $db;
        $ret = array();
        $query = "select id,nome as value from cliente where lower(nome) like '%".strtolower(Request::value('cliente'))."%' limit 10";
        $clientes = $db->query($query);
        for($i=0;$i<count($clientes);$i++) {
            $query = "select logradouro,numero,complemento,bairro,cep,(select nome from cidade where id=ce.cidade_id) as cidade, (select uf from cidade where id=ce.cidade_id) as estado from cliente_endereco ce where cliente_id=".$clientes[$i]['id'];
            $ends = "";
            $enderecos = $db->query($query);
            $clientes[$i]['enderecos'] = array();
            if(count($enderecos)>0) {
                $clientes[$i]['enderecos'] = $enderecos;
            }
        }
        $ret['results'] = $clientes;
        echo json_encode($ret);
    }
    public static function buscarproduto() {
        global $db;
        $ret = array();
        $produto = Request::value('produto');
        
        $query = "select id as id,concat(codigo,' - ',nome) as value, preco from produto where ";
        if(preg_match("/\d+/",$produto)) {
            $query .= "codigo like '".$produto."%'";
        } else {
            $query .= "lower(nome) like '%".$produto."%'";
        }
        $query .= " limit 10";
        $produtos = $db->query($query);
        $ret['results'] = $produtos;
        echo json_encode($ret);
    }
    public static function calculafrete() {
        $url  = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx?';
        $params = array();
        array_push($params,'nCdEmpresa=09059270');
        array_push($params,'sDsSenha=08172474');
        array_push($params,'sCepOrigem=79032480');
        array_push($params,'sCepOrigem=');
        print_r($url);
    }
}
