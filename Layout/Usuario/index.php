<?Helper::js('App.Usuario'); ?>
<div class="title">

    <span class="align-right">
        <button id="novoUsuario"
                class="btn btn-default"
                style="margin:6px"
                onclick="javascript:location.href = '<?="/".APP_DIR."usuario/inserir"?>'"
                >
            <span class="glyphicon glyphicon-plus"></span>
            Novo Usuário
        </button>
    </span>
    <h1 class="half">Usuários</h1>
</div>
<div class="panel panel-default">
    <table class="table">
        <thead>
            <tr>
                <th style="width:5%">ID</th>
                <th style="width:25%">Nome</th>
                <th style="width:25%" class="mobile-half">E-mail</th>
                <th style="width:10%" class="mobile-min">Ativo</th>
                <th style="width:200px" class="mobile">Acessado</th>
                <th style="width:200px">Opções</th>
            </tr>
        </thead>
        <tbody>
            <?
            global $db;

            $users = $db->getResult('user','*','id<>'.Session::get('id').' and deleted=0');

            if(count($users)==0) {
            ?>
            <tr>
                <td colspan="5">Não há usuários além de você.</td>
            </tr>
            <?
            } else {
            foreach($users as $u) {
            ?>
            <tr>
                <td><?=$u['id']?></td>
                <td><a href="<?=Helper::link('usuario/editar/'.$u['id'])?>"><?=$u['name']?></a></td>
                <td class="mobile-half"><a href="<?=Helper::link('usuario/editar/'.$u['id'])?>"><?=$u['email']?></a></td>
                <td class="mobile-min"><?=$u['active']==1?"Sim":"Não";?></td>
                <td class="mobile"><?=Helper::timestampToDate($u['lastlogin'])?></td>
                <td><button type="button" class="btn btn-default btn-sm" onclick="App.Usuario.resetarSenha('<?=$u["name"]?>',<?=$u["id"]?>)">
                            <span class="glyphicon glyphicon-pencil"></span>
                        Resetar senha
                    </button>
                </td>
            </tr>
            <?
            }
            }?>
        <script type="text/javascript">
                                    App.Usuario.resetarSenha = function(nome, id) {
                            if (App.Modal.Show("Resetar senha", 'Deseja realmente resetar a senha de <strong>' + nome + '</strong> para "neurosoft"?', "Resetar senha", function() {
                            location.href = '<?="/".APP_DIR."usuario/resetarsenha/"?>' + id;
                            }));
                            }
        </script>
        </tbody>
    </table>
</div>