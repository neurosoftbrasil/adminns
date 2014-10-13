<h2>Inserir contato</h2>
<div class="row marginbottom">
    <div class="col-md-12">
        <a href="/<?=APP_DIR?>service/contato/inserir" data-toggle="modal" data-target="#remoteModal" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-plus"></span> Adicionar contato</a> 
    </div>
</div>
<div class="row well-sm">
    <ul id="contatosLista"></ul>
</div>
<div id="errorMessage" class="errorMessage well-sm alert-danger marginbottom hide"></div>
<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-default btn-lg" onclick="location.href='/<?=APP_DIR?>cliente/endereco'"><span class='glyphicon glyphicon-chevron-left'></span> Voltar</button>
        <button type="button" class="btn btn-primary btn-lg" onclick="App.Cliente.Salvar()">Salvar Cliente<span class='glyphicon glyphicon-chevron-right'></span></button>
    </div>
</div>
<script type="text/javascript">
<?
      $cliente = json_decode($_COOKIE['cliente']);
      if(isset($cliente->id)) {
          global $db;
          $cliente->contatos = $db->query("select *,'' as emails,'' as tels from contato where cliente_id=".$cliente->id);
          for($i=0;$i<count($cliente->contatos);$i++) {
              $cliente->contatos[$i]['emails'] = $db->query("select email from contato_email where contato_id=".$cliente->contatos[$i]['id'],false,PDO::FETCH_ASSOC,'email');
              $cliente->contatos[$i]['tels'] = $db->query("select telefone from contato_telefone where contato_id=".$cliente->contatos[$i]['id'],false,PDO::FETCH_ASSOC,'telefone');
          }
          ?>
          App.Util.EscreverCookie('cliente',<?= json_encode($cliente)?>);
          <?
      }
    ?>
    App.Contato.Rendenizar();
</script>