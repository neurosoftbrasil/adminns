<?php

class ClienteController extends SecureController {
    public function index() {
        
    }
    public function inserir() {
        
    }
    public function editar() {
        
    }
    public function endereco() {
        ?>
        <div class="modal-header">Inserir endereço</div>  
        <div class="modal-body">  
        <?
            FormHelper::create('formEndereco');
            FormHelper::input('logradouro',"Logradouro <b>*</b>",$logradouro,array(
                'placeholder'=>'Digite o logradouro (Rua, Av, Tv, BR)'
            ));
        ?>
            </div>            
        <?
            FormHelper::end();
        ?>
        </div>  
        <div class="modal-footer">  
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Salvar</button>
        </div> 
        <?
    }
}