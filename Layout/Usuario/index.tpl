<h1>Usuários</h1>
<div class="panel panel-default">
    <table class="table">
        <thead>
            <tr>
                <th style="width:5%">ID</th>
                <th style="width:25%" class="mobile-half">E-mail</th>
                <th style="width:25%">Nome</th>
                <th style="width:10%">Ativo</th>
                <th style="width:200px" class="mobile">Acessado</th>
                <th style="width:200px">Opções</th>
            </tr>
        </thead>
        <tbody>
            <?
            global $db;

            $users = $db->getResult('user','*','id<>'.$_SESSION[Session::getId()]['id']);

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
                <td class="mobile-half"><?=$u['email']?></td>
                <td><?=$u['name']?></td>
                <td><?=$u['active']==1?"Sim":"Não";?></td>
                <td class="mobile"><?=Helper::timestampToDate($u['lastlogin'])?></td>
                <td><button type="button" class="btn btn-default btn-sm" onclick="location.href='<?=Helper::link('usuario/editar/'.$u['id'])?>'">
                        <span class="glyphicon glyphicon-pencil"></span>
                        Editar
                    </button>
                </td>
            </tr>
            <?
            }
            }?>
        </tbody>
    </table>
</div>