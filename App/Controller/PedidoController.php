<?php
include("App/Model/Pedido.php");

class PedidoController extends SecureController {
    public static function inserir() {
        $pedidoLista = new Pedido();
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
