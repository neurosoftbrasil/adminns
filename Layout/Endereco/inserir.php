<div class="modal-header"><h2>Inserir endereço</h2></div>  
<div class="modal-body">
    <?
        
        $cep = Request::value('cep');
        $logradouro = Request::value('logradouro');
        $numero = Request::value('numero');
        $bairro = Request::value('bairro');
        $cliente_endereco_tipo_id = Request::value('cliente_endereco_tipo_id');
        $cidade = Request::value('cidade');
        $estado = Request::value('estado');
        
        FormHelper::create('formEndereco');
        ?>
            <div class="row">
                <div class="col-md-12">
                    <?
                        FormHelper::selectFromTable('cliente_endereco_tipo.id', 'descricao', 'Tipo de contato',$cliente_endereco_tipo_id);
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
        <?
        FormHelper::input('cep','CEP',$cep,array(
            'placeholder'=>'Digite o CEP',
            'onkeyup'=>'App.Endereco.BuscarCep(this.value)'
        ));
        ?>
                </div><br/>
                <div id="cepinvalido" class="col-md-5 well-sm alert-danger hide">
                    <span class="glyphicon glyphicon-remove-circle"></span> CEP inválido.
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
        <?
        FormHelper::input('logradouro',"Logradouro",$logradouro,array(
            'placeholder'=>'Digite o nome da Rua, Av. Tv. ou Estrada'
        ));
        ?>
                </div>
                <div class="col-md-5">
        <?
        FormHelper::input('numero',"Número",$numero,array(
            'placeholder'=>'Digite o número',
            'width'=>20
        ));
        ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
        <?
        FormHelper::input('bairro',"Bairro",$bairro,array(
            'placeholder'=>'Digite o bairro'
        ));
        ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-7">
        <?
        FormHelper::input('cidade',"Cidade",$cidade,array(
            'placeholder'=>'Digite a cidade',
        ));
        ?>
                </div>
                <div class="col-md-5">
        <?
        FormHelper::input('estado',"Estado",$cidade,array(
            'placeholder'=>'UF'
        ));
        ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
        <?
        FormHelper::textarea('complemento',"Complemento");
        ?>
                </div>
                
            <?
        FormHelper::textarea('referencia',"Referência / observação");
        ?></div><?
        FormHelper::end(false);
    ?>
</div>
<div class="modal-footer">  
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="App.Endereco.Salvar()">Salvar</button>
</div>
<script type="text/javascript">
    $("#formEndereco")[0].reset();
</script>