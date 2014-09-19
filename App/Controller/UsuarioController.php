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
        $j = array();
        $j['status'] = 'success';
        $j['message'] = 'Usuário salvo com êxito.';
        //$j['redirect'] = "";
        echo json_encode($j);
    } 
}