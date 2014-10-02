<h2>Inserir novo pedido</h2>
<?
    Helper::js('jquery.typeahead');
    Helper::js('App.Pedido');
    
    FormHelper::create('formPedido');
    FormHelper::typeAhead('cliente','Cliente <b>*</b>','pedido/buscarcliente',array(
        'class'=>'inputTypeAhead',
        'placeholder'=>'Digite o nome do cliente'
    ));
    FormHelper::selectFromTable("pedido_status.id",'descricao','Situação do pedido <b>*</b>',1);
    ?>
    <h3>Produtos</h3>
    <div class="row">
        <div class="col-md-12">
            <div id="produtos">
                <div style="margin:7px 0 27px 5px">
                    <span class="well-sm alert-danger">O pedido deve conter produtos.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" onclick="App.Pedido.AdicionaProduto()" class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-plus'></span> Adicionar produto</button>
        </div>
    </div>
    <?
    FormHelper::end();

