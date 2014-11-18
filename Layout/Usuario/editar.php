<?Helper::js('App.Usuario'); ?>
<div class="sidebar left">
    <div class="label btn-square usuarios">Usuários</div>
</div>
<style type="text/css">
    @media screen and (max-width: 520px) {
        .btn-square.usuarios:before {
            content:'';
        }
    }
</style>
<div class="content">
<div class="panel-default">
<?
    global $db;
    $id = Request::get('ident');
    if($id) {
        $u = $db->getResult('user','*','id='.$id,true);
    } else {
        $u = array("name"=>"","email"=>"","active"=>"","id"=>0);
    }
    
    FormHelper::create('formUsuario');
    FormHelper::input('name', "Nome",$u['name'],array(
        'placeholder'=>'Digite o nome',
        'style'=>'max-width:400px',
        'validation'=>array(
            'regex'=>FormHelper::NOT_EMPTY,
            'message'=>'Preencha o <strong>Nome</strong>.'
        )
    ));
    FormHelper::input('email',"E-mail",$u['email'],array(
        'placeholder'=>'Digite o e-mail',
        'style'=>'max-width:400px',
        'validation'=>array(
            'regex'=>FormHelper::EMAIL,
            'message'=>'Digite um <strong>E-mail</strong> válido.'
        )
    ));
    FormHelper::checkbox('active',"Ativo",$u['active']);
    
    // ligação many to many para user_module
    
    $options = array('Nenhum','Visualizar','Incluir','Editar','Excluir');
    
    if($id) {
    ?><h4>Permissões</h4>
    <div class="form-group <?=$m['permission']?>_group">
        <?
        $modules = $db->query('select id,name,permission from module');
        $levels = $db->query("select level from user_module where user_id=".$u['id']);
        if(count($levels)==0) {
            $levels = array();
            foreach($modules as $m) { array_push($levels,0); }
        }
        $counter = 0;
        foreach($modules as $m) {
            ?>
                
                    <label for="user_module-<?=$m['id']?>" style="width:150px"><h5><?=$m['name']?></h5></label>
                    <select id="user_module-<?=$m['id']?>" name="user_module-<?=$m['id']?>">
                        <?
                            for($i=0;$i<count($options);$i++) {
                                ?>
                                    <option value="user_id=<?=$u['id']?>&module_id=<?=$m['id']?>&level=<?=$i?>" <?=$levels[$counter]['level']==$i?"selected":"";?>><?=$options[$i]?></option>
                                <?
                            }
                        ?>
                    </select><br/>
                
            <?
            $counter++;
        }
        
    ?>
    </div>
    <?
  
    }
    FormHelper::startGroup();
    if(
            !$id && Session::hasPermission('usuario',Session::INSERIR) || 
            Session::hasPermission('usuario',Session::EDITAR)
    ) {
        FormHelper::submitAjax("Salvar","salvar/".$u['id'],array('class'=>'button button-md'));
    }
    if($id && Session::hasPermission('usuario',Session::EXCLUIR)) {
        FormHelper::button("excluir","Excluir",array(
        'style'=>'margin-left:10px',
        'onclick'=>'App.Usuario.Excluir("'.$u["name"].'",'.$u['id'].")"
        ));
    }
    FormHelper::button('cancelar',"Cancelar",array(
        'style'=>'margin-left:10px',
        'onclick'=>"location.href=\"/".APP_DIR."usuario\""
    ));
    FormHelper::endGroup();
    FormHelper::end();
    
    ?>
    <script type="text/javascript">
        App.Usuario.Excluir = function(nome, id) {
            if (App.Modal.Show("Excluir usuário", 'Deseja realmente excluir o usuário <strong>' + nome + '</strong>?', "Excluir", function() {
                location.href = '<?="/".APP_DIR."service/usuario/excluir/"?>' + id;
            }));
        }
    </script>
    
</div></div>