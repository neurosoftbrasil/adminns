<h2>Clientes</h2>
<div class="row" style="clear:both">
    <div class="col-md-12">
        <label for="buscarEmpresa">Pesquisa</label>
        <input class="form-control buscarCliente" placeholder="Digite o nome da empresa, CPF ou CNPJ" type="text" id="buscarCliente" name="buscarCliente" onkeyup="App.Cliente.Buscar()"/>
    </div>
</div>
<br/>
<div class="row" style="clear:both">
    
<div class="col-md-12">
    <div class="panel panel-default">
    <table class="table">
        <thead>
            <tr>
                <th>Cliente</th>
                <th class="mobile-min" style="width:20%">Nome do Contato</th>
                <th class="mobile-half" style="width:20%">Documento</th>
                <th class="mobile-half" style="width:20%">Email</th>
                <th class="mobile" style="width:20%">Telefone</th>
            </tr> 
        </thead>
        <tbody id="listaCliente">
            <?
                global $db;
                $clientes = "select distinct c.id,c.documento, c.nome, cc.nome as nomecontato, ct.telefone, ce.email from cliente c, contato cc, contato_telefone ct, contato_email ce where c.id = cc.cliente_id and c.id = ct.cliente_id and c.id = ce.cliente_id limit 50";
                $clientes = $db->query($clientes);
                foreach($clientes as $c) {
                    ?>
                <tr>
                    <td><a href='<?='/'.APP_DIR."cliente/editar/".$c['id'];?>'><?=$c['nome']?></a></td>
                    <td><?=$c['nomecontato']?></td>
                    <td><?=$c['documento']?></td>
                    <td><?=$c['email']?></td>
                    <td><?=$c['telefone']?></td>
                </tr>
                    <?
                }
            ?>
        </tbody>
    </table>
        </div>
</div>
</div>