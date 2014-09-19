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
    FormHelper::checkbox('active',"Ativo");
    ?><h4>Permissões</h4><?
    FormHelper::select('module','user',array(
        'level'=>array(
            'Nenhum',
            'Visualizar',
            'Inserir',
            'Editar',
            'Excluir'
         )
    ));
    FormHelper::startGroup();
    FormHelper::submitAjax("Salvar","salvar",array('class'=>'btn-primary'));
    FormHelper::endGroup();
    
    FormHelper::end();