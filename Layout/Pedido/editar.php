<h2>Inserir novo pedido</h2>
<?
    global $db;
    
    Helper::js('jquery.typeahead');
    Helper::js('App.Pedido');
    
    FormHelper::create('formPedido');
    
    $ident = Request::value('ident');
    
    $p = "select p.*,f.descricao as pedido_formapagamento,s.descricao as pedido_status, c.nome, c.documento from pedido p, pedido_formapagamento f, pedido_status s, cliente c where f.id = p.pedido_formapagamento_id and s.id = p.pedido_status_id and c.id = p.cliente_id and p.id = ".$ident;
    $p = $db->query($p,true);
    ?>
    <script type="text/javascript">
    </script>
    <?
    
    FormHelper::typeAhead('cliente','Cliente <b>*</b>','pedido/buscarcliente',array(
        'class'=>'inputTypeAhead',
        'placeholder'=>'Digite o nome do cliente',
        'value'=>$p['nome']
    ));
    
    $query = "select e.*,c.nome as cidade,c.uf as estado from cliente_endereco e, cidade c where cliente_id=".$p['cliente_id']." and e.cidade_id = c.id";
    $ends = $db->query($query);
    $query = "select * from contato c,contato_email e, contato_telefone t, contato_tipo y where c.cliente_id=".$p['cliente_id']." and c.contato_tipo_id = y.id and c.cliente_endereco_id = e.id and t.contato_id = c.id and e.contato_id = e.id";
    $conts = $db->query($query);
    $query = "select pepr.id,pepr.produto_id,pepr.pedido_id, pr.nome, pr.codigo from pedido pe, produto pr, pedido_produto pepr where pepr.pedido_id = pe.id and pr.id = pepr.produto_id";
    $prods = $db->query($query);
    
    ?>
    <div id='enderecoEscolha'></div>
    <script type='text/javascript'>
        $('#cliente').on('typeahead:selected', function(evt, item) {
            $('#clienteId').val(item.id);
            App.Pedido.Cliente = item;
            App.Pedido.RendenizarEnderecos(item.enderecos,undefined);
        });
    </script>
    <div id="contatoEscolha"></div>
    
    <?
    FormHelper::selectFromTable("pedido_status.id",'descricao','Situação do pedido <b>*</b>',$p['pedido_status_id']);
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
            <button type="button" onclick="App.Pedido.AdicionaProduto()" class='btn btn-primary btn-sm'>
                <span class='glyphicon glyphicon-plus'></span> 
                Adicionar produto
            </button>
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
    
    ?>  
    
<script type="text/javascript">
          var Cliente = App.Util.LerCookie('cliente');
          console.log(Cliente);
    <?
        if(count($ends)>0) {
    ?>
            Cliente.enderecos = <?=json_encode($ends);?>;
            Cliente.enderecoId = <?=$p['cliente_endereco_id']?>;
            App.Util.EscreverCookie('cliente',Cliente);
    <?
        }
        if(count($conts)>0) {
    ?>
            Cliente.contatos = <?=json_encode($conts);?>;
            Cliente.contatoId = <?=$p['contato_id']?>;
            App.Util.EscreverCookie('cliente',Cliente);
    <?
        }
    ?>
    App.Pedido.AdicionaProduto(<?= json_encode($prods); ?>);
    App.Pedido.RendenizarContatos(Cliente.contatos);
    App.Pedido.RendenizarEnderecos(Cliente.enderecos,<?=$p['cliente_endereco_id']?>);
</script>
