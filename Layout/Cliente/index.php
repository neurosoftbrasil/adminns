<h2 style="float:left">Clientes</h2>
<div class="row" style="float:right;padding-top:20px">
    <button type='button' class='btn btn-primary ' onclick='location.href="/<?=APP_DIR?>cliente/inserir"' >Novo cliente</button>
</div>
<div class="row" style="clear:both">
    <div class="col-md-12">
        <label for="buscarCliente">Pesquisa</label>
        <input class="form-control buscarCliente" type="text" id="buscarCliente" name="buscarCliente" onkeyup="App.Cliente.Buscar()"/>
    </div>
</div>
<div class="row">
<div class="col-md-12">
    <table class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th class="mobile-min" style="width:30%">Nome do Contato</th>
                <th class="mobile-half" style="width:20%">Email</th>
                <th class="mobile" style="width:20%">Telefone</th>
            </tr>
        </thead>
        <tbody id="listaCliente">
            <?
                global $db;
                $clientes = "select distinct c.id, c.nome, cc.nome as nomecontato, ct.telefone, ce.email from cliente c, contato cc, contato_telefone ct, contato_email ce where c.id = cc.cliente_id and c.id = ct.cliente_id and c.id = ce.cliente_id limit 50";
                $clientes = $db->query($clientes);
                foreach($clientes as $c) {
                    ?>
                <tr>
                    <td><a href='<?='/'.APP_DIR."cliente/editar/".$c['id'];?>'><?=$c['nome']?></a></td>
                    <td class="mobile-min"><?=$c['nomecontato']?></td>
                    <td class="mobile-half"><?=$c['email']?></td>
                    <td class="mobile"><?=$c['telefone']?></td>
                </tr>
                    <?
                }
            ?>
        </tbody>
    </table>
</div>
