<? echo Helper::js("App.Suporte");

global $db;
?>
<div class="sidebar left">
    <div class="label btn-square red perfil">Meu Perfil</div>
</div>
<style type="text/css">
    @media screen and (max-width: 520px) {
        .btn-square.perfil:before {
            content:'';
        }
    }
</style>
<div class="content">
<?
    global $db;
    
    $u = $db->getResult('user','*','id='.$id,true);
    
    FormHelper::create('formUsuario');
    ?>
        <div class="form-group name_group">
            <label for="name">Nome</label><br/> <?=$u['name']?>
        </div>
    <?
    FormHelper::input('email',"E-mail",$u['email'],array(
        'placeholder'=>'Digite o e-mail',
        'style'=>'max-width:400px',
        'validation'=>array(
            'regex'=>FormHelper::EMAIL,
            'message'=>'Digite um <strong>E-mail</strong> válido.'
        )
    ));
    FormHelper::password('senha','Senha','',array(
        'placeholder'=>'Preencha se quiser alterar a senha',
        'style'=>'max-width:400px'
    ));
    FormHelper::password('novasenha','Nova senha','',array(
        'placeholder'=>'Digite a senha nova',
        'style'=>'max-width:400px'
    ));
    FormHelper::password('repetir','Repita a nova senha','',array(
        'placeholder'=>'Repita a senha nova',
        'style'=>'max-width:400px'
    ));
    ?><br/><?
    FormHelper::startGroup();
    FormHelper::submitAjax("Salvar","salvar/".$u['id'],array('class'=>'button'));
    FormHelper::endGroup();
    
    FormHelper::end();
?>
<script type="text/javascript">
    
</script>
</div>