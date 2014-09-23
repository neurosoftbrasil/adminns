<?php

class PerfilController extends SecureController {
    public function index() {
        $this->set('id', Session::get("id"));
    }
    public function teste() {
        Util::prints($_SESSION);
    }
    public function salvar() {
        
        $j = array();
        
        $id = Request::get('ident');
        $email = Request::post('email');
        $senha = Request::post('senha');
        $novasenha = Request::post('novasenha');
        $repetir = Request::post('repetir');
        
        $erros = array();
        
        if(!preg_match(FormHelper::EMAIL,$email)) {
            array_push($erros,'email');
            $j['status'] = "danger";
            $j['message'] = "Digite um <strong>E-mail</strong> válido.";
        }
        
        if(isset($senha)) {
            if(strlen($novasenha)<4) {
                array_push($erros,"senha");
                $j['status'] = 'danger';
                $j['message'] = 'A <strong>senha</strong> deve ter ao menos 4 caracteres.';
                $j['focus'] = "#senha";
            } else if($novasenha != $repetir && preg_match(FormHelper::NOT_EMPTY,$novasenha)) {
                array_push($erros,'repetir');
                $j['status'] = 'danger';
                $j['message'] = 'Repita a senha para alterá-la.';
                $j['focus'] = "#repetir";
            } else if(!isset($novasenha)) {
                array_push($erros,'novasenha');
                $j['status'] = 'danger';
                $j['message'] = 'Caso queira alterar uma senha, você deve digitar uma nova.';
                $j['focus'] = "#novasenha";
            }
        }
        
        global $db;
        
        $u = $db->query("select email from user where email='$email'");
        $c = $db->query("select email from user where id=$id and email='$email'");
        $s = $db->query("select email from user where email='$email' and password='".Session::password($senha)."'");
        
        if(isset($senha) && count($s)==0) {
            array_push($erros,"senha");
            $j['status'] = 'danger';
            $j['message'] = 'A <strong>senha</strong> não confere.';
            $j['focus'] = "#senha";
        }
        if(count($c)==0 && count($u)>0){
            array($erros,'email');
            $j['status'] = "danger";
            $j['message'] = "<strong>E-mail</strong> já existe.";
        }
        if(count($erros)==0) {
            global $db;
            
            $cols = array();
            
            array_push($cols,"email='".$email."'");
            if(isset($novasenha)) {
                array_push($cols,"password='".Session::password($novasenha)."'");
                array_push($cols,"token='".Session::token($email.$novasenha)."'");
            }
            
            $query = "update user set ".implode($cols,",")." where id=".$id;
            $db->query($query);
            
            $j['status'] = 'success';
            $j['message'] = 'Usuário alterado com êxito.';
        } else if(!isset($j['status'])){
            $j['status'] = 'success';
            $j['message'] = 'Não houveram alterações.';
        }
        echo json_encode($j);
    }
}