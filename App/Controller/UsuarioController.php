<?php
class UsuarioController extends SecureController {
    public function index() {
        
    }
    public function editar() {
        
    }
    public function deletar() {
        $id = Request::get('ident');
    }
    public function salvar() {
        $cols = array();
        $values = array();
        $ident = Request::get('ident');
        global $db;
        
        foreach($_POST as $key=>$value) {
            if($key == 'active') {
                $value = 1;
            } 
            if(preg_match("/\=/",$value)) {
                $refs = explode("&",$value);
                $rcol = array();
                $rval = array();
                foreach($refs as $ref) {
                    $r = explode("=",$ref);
                    array_push($rcol,$r[0]);
                    array_push($rval,$r[1]);
                }
                
                $query = "delete from user_module where user_id=".$rval[0]." and module_id=".$rval[1].";";
                $db->query($query,true);
                
                $query  = "insert into user_module (".implode(",",$rcol).") values ('".implode("','",$rval)."');";
                $db->query($query);
                
                continue;
            }
            
            array_push($cols,$key);
            array_push($values,$value);
        }
        if(!Request::post('active')) {
            array_push($cols,'active');
            array_push($values,0);
        }
        if($ident) {
            $query  = "update user set ";
            $sets = array();
            for($i=0;$i<count($cols);$i++) {
                array_push($sets,$cols[$i]."='".$values[$i]."'");
            }
            $query .= implode(",",$sets)." where id=".$ident;
        } else {
            $query = "insert into user (".implode(",",$cols).") values ('".implode("','",$values)."');";
        }
        $result = $db->query($query);
        if($result) {
            $j = array();
            $j['status'] = 'success';
            $j['message'] = 'Usuário salvo com êxito.';
            $j['redirect'] = "/".APP_DIR."usuario/";
        } else {
            $j = array();
            $j['status'] = 'danger';
            $j['message'] = 'O sistema não pode salvar o usuário. Contate o administrador.';
        }
        echo json_encode($j);
    } 
}