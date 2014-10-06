<h2>Inserir contato</h2>
<div class="row marginbottom">
    <div class="col-md-12">
        <a href="/<?=APP_DIR?>service/contato/inserir" data-toggle="modal" data-target="#remoteModal" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Adicionar contato</a> 
    </div>
</div>
<div class="row well-sm">
    <ul id="enderecosLista"></ul>
</div>
<div id="errorMessage" class="errorMessage well-sm alert-danger marginbottom hide"></div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-default btn-lg" onclick="location.href='/<?=APP_DIR?>cliente'"><span class='glyphicon glyphicon-chevron-left'></span> Voltar</button>
        <button type="button" class="btn btn-primary btn-lg" onclick="App.Cliente.SalvarEndereco()">Adicionar contato <span class='glyphicon glyphicon-chevron-right'></span></button>
    </div>
</div>
<script type="text/javascript">
    App.Endereco.Rendenizar();
</script>