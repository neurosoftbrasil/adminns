<?php
class UsuarioController extends SecureController {
    public function index() {
        Helper::js('App.Usuario');
        if(!Session::hasPermission('usuario',Session::EXCLUIR)) Router::redirect('home');
    }
    public function editar() {
        if(!Session::hasPermission('usuario',Session::EXCLUIR)) Router::redirect('home');
    }
    public function inserir() {
        if(!Session::hasPermission('usuario',Session::EXCLUIR)) Router::redirect('home');
    }
    public function resetarsenha() {
        if(!Session::hasPermission('usuario',Session::EXCLUIR)) Router::redirect('home');
        
        $ident = Request::get('ident');
        global $db;
        $query = "update user set password='".Session::password("neurosoft")."',token='".Session::token("neurosoft")."' where id=".$ident;
        $db->query($query);
        Router::redirect("usuario");
    }
    public function perfil() {
        global $db;
        
        
    }
    public function excluir() {
        if(!Session::hasPermission('usuario',Session::EXCLUIR)) Router::redirect('home');
        
        $id = Request::get('ident');
        global $db;
        $db->query("update user set active=0,deleted=1 where id=".$id);
        Router::redirect('usuario');
    }
    public function salvar() {
        if(!Session::hasPermission('usuario',Session::EXCLUIR)) Router::redirect('home');
        
        $cols = array();
        $values = array();
        $ident = Request::get('ident');
        $ident = $ident=="0"?false:$ident;
        $j = array(); // resposta
        
        global $db;
        
        $exists = $db->query("select * from user where email='".Request::post('email')."'");
        
        if(!$ident && count($exists)>0) {
            $j['status'] = 'danger';
            $j['message'] = 'O usuário já existe';
            echo json_encode($j);
            return;
        }
        
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
        
        if(!$ident) {
            // neurosoft
            array_push($cols,"password");
            array_push($values,Session::password("neurosoft"));
            // neurosoft
            array_push($cols,"token");
            array_push($values,Session::token("neurosoft"));
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
            
            $j['status'] = 'success';
            $j['message'] = 'Usuário salvo com êxito.';
            
            $returningId = $ident?"":"editar/".$result;
            
            $j['redirect'] = "/".APP_DIR."usuario/".$returningId;
        } else {
            $j['status'] = 'danger';
            $j['message'] = 'O sistema não pode salvar o usuário. Contate o administrador.';
        }
        echo json_encode($j);
    } 
    public function layout() {
        if(!Request::get('service')) {
            include(VIEW_DIR."header.php");
            echo "\n";
        } else {
            if(Config::$environment=='production') {
                header('Content-Type: application/json');
            }
        }
        $page = Request::get('method')=="inserir"?"editar":Request::get('method');
        $path = VIEW_DIR.ucwords(Request::get('controller'))."/".$page.".tpl";
        
        if(file_exists($path)) {
            include($path);
        }
        if(!Request::get('service')) {
            include(VIEW_DIR."footer.php");
        }
    }
}