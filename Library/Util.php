<?php

// ESTATICA
class Util {
    public static function prints($str,$dying = false) {
        echo "<pre>";
        print_r($str);
        echo "</pre>";
        if($dying) die('DEAD');
    }
    public static function dumps($str,$dying = false) {
        echo "<pre>";
        var_dump($str);
        echo "</pre>";
        if($dying) die('DEAD');
    }
    public static function gerarNumeroPedido($num) {
        global $db;
        $query = "select pedido_status_id as status from pedido where id = ".$num;
        $status = $db->query($query,true);
        $char = $status['status']>2?"P":"O";
        return $char.str_pad($num,11,"0",STR_PAD_LEFT);
    }
    public static function gerarNumeroPedidoAntigo($num) {
        global $dbo;
        $query = "select numero,status from pedido where ped_id = ".$num;
        $status = $dbo->query($query,true);
        $char = $status['status']=='ORÇAMENTO'?"O":"P";
        return $char.str_pad($status['numero'],11,"0",STR_PAD_LEFT);
    }
}