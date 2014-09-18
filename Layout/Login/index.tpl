<h1>Login</h1>
<?
    FormHelper::create('loginForm');
    FormHelper::input('email',"E-mail",Request::post('email'),array(
        'placeholder'=>'Digite o seu e-mail',
        'style'=>'max-width:400px',
        'validation'=>array(
            'regex'=>FormHelper::EMAIL,
            'message'=>'Digite um <strong>E-mail</strong> válido.'
        )
    ));
    FormHelper::password('password',"Senha",Request::post('password'),array(
        'placeholder'=>'Digite o sua senha',
        'style'=>'max-width:400px',
        'validation'=>array(
            'regex'=>FormHelper::NOT_EMPTY,
            'message'=>'Digite uma <strong>Senha</strong> para logar.'
        )
    ));
    FormHelper::startGroup();
    FormHelper::submitAjax("Enviar","auth",array('class'=>'btn-primary'));
    FormHelper::endGroup();
    FormHelper::end();
?>