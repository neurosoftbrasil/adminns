<?php

class ClienteController extends AppController {
    public function clientes0() {
        global $db;
        global $dbold;
        $u = $db->query("select * from cliente");
        $clientes = $dbold->query('select cliente_id as id, nome, cpf as documento, 3 as module_id from clientes c order by id');
        foreach($clientes as $c) {
            $id = $c['id'];
            if($id>0 && count($db->query("select id from cliente where id=".$id))==0) {
                if(!preg_match("/[.-]/",$c['documento'])) {
                    $len = strlen($c['documento']);
                    $doc = $c['documento'];
                    $ndoc = "";
                    switch($len) {
                        case 11:
                            $ndoc .= substr($doc,0,3).".";
                            $ndoc .= substr($doc,3,3).".";
                            $ndoc .= substr($doc,6,3)."-";
                            $ndoc .= substr($doc,9,2);
                        break;
                    }
                    $c['documento'] = $ndoc;
                }
                $query = "insert into cliente (id,nome,documento,module_id) values ('".implode("','",$c)."')";
                $db->query($query);
            }
        }
    }
    public function pj() {
        global $db;
        global $dbold;
        $u = $db->query("select * from cliente");
        $clientes = $dbold->query('select cliente_pj_id as id, razao as nome, cnpj as documento, 3 as module_id from clientes_pj c order by id limit 50');
        foreach($clientes as $c) {
            $id = $c['id'];
            if($id>0 && $c['nome']!= '' && count($db->query("select id from cliente where id=".$id))==0) {
                if(!preg_match("/[.-]/",$c['documento'])) {
                    $len = strlen($c['documento']);
                    $doc = $c['documento'];
                    $ndoc = "";
                    switch($len) {
                        case 14:
                            $ndoc .= substr($doc,0,2).".";
                            $ndoc .= substr($doc,2,3).".";
                            $ndoc .= substr($doc,5,3)."/";
                            $ndoc .= substr($doc,8,4)."-";
                            $ndoc .= substr($doc,12,2);
                        break;
                    }
                    $c['documento'] = $ndoc;
                }
                $query = "insert into cliente (id,nome,documento,module_id) values ('".implode("','",$c)."')";
                $db->query($query);
                echo $query."<br/>";
            }
        }
    }
}