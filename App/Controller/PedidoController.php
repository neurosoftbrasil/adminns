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
        $ret['results'] = $clientes;
        echo json_encode($ret);
    }
    public static function buscarproduto() {
        global $db;
        $ret = array();
        $produto = Request::value('produto');
        
        $query = "select id as id,concat(codigo,' - ',nome) as value from produto where ";
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
}
