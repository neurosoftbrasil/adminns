<h2>Inserir novo pedido</h2>
<?
    Helper::js('jquery.typeahead');
    Helper::js('App.Pedido');
    
    FormHelper::create('formPedido');
    ?>
    <div class="row marginbottom">
        <div class="col-md-12">
            <a class='btn btn-primary btn-sm' href="/<?=APP_DIR?>cliente/inserir" target='_blank'>
                <span class="glyphicon glyphicon-plus"></span> Adicionar cliente
            </a> 
        </div>
    </div>
    <?
    FormHelper::typeAhead('cliente','Cliente <b>*</b>','pedido/buscarcliente',array(
        'class'=>'inputTypeAhead',
        'placeholder'=>'Digite o nome do cliente'
    ));
    ?>
    <div id='enderecoEscolha'></div>
    <script type='text/javascript'>
        $('#cliente').on('typeahead:selected', function(evt, item) {
            $('#clienteId').val(item.id);
            App.Pedido.Cliente = item;
            App.Pedido.RendenizarEnderecos(item.enderecos);
        });
    </script>
    <?
    FormHelper::selectFromTable("pedido_status.id",'descricao','Situação do pedido <b>*</b>',1);
    ?>
    <label>Produtos</label>
    <div class="row marginbottom">
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
    <div class="row" style="padding:30px 0">
        <div class="col-md-12">
            <div class="col-md-3">
                <legend>Valor</legend>
                <input type="text" id="saldo"  class="valor alert-success" value="0.00" readonly/>
            </div>
            <div class="col-md-3">
                <legend>Desconto (%)</legend>
                <input type="text" id="desconto" class="valor alert-danger" placeholder="0%"  onkeyup="App.Pedido.Descontar(this.value)"/>
            </div>
            <div class="col-md-3">
                <legend>Soma</legend>
                <input type="text" id="montante" class="valor alert-info" value="0.00" readonly/>
            </div>
        </div>
    </div>
    <?
    FormHelper::end(false);

