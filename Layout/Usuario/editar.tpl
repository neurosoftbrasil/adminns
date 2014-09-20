<h1>Editar usuário</h1>
<?
    global $db;
    
    $u = $db->getResult('user','*','id='.Request::get('ident'),true);
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
    
    
    ?><h4>Permissões</h4>
    <div class="form-group <?=$m['permission']?>_group">
        <?
        $modules = $db->query('select id,name,permission from module');
        $levels = $db->query("select level from user_module where user_id=".$u['id']);
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
    
    
    FormHelper::startGroup();
    FormHelper::submitAjax("Salvar","salvar/".$u['id'],array('class'=>'btn-primary'));
    FormHelper::endGroup();
    
    FormHelper::end();