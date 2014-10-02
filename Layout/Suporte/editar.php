<h2>Editar suporte</h2>
<?php

$protocolo = Request::value('ident');

global $dbo;

$r = $dbo->query("select *,s.tipo as stipo from suporte s, clientes c where protocolo='$protocolo' and s.cid = c.cliente_id",true);

FormHelper::create('formSuporte');
?><div class="row">
<div class="col-md-4"><?
FormHelper::input('protocolo', "Protocolo",$r['protocolo'],array(
    'disabled'=>'true',
    'style'=>'max-width:400px'
));
FormHelper::selectFromTable('suporte_cats.tipo_id',"Tipo",$r['stipo'],'tipo',array(
    'style'=>'max-width:400px'
));
FormHelper::selectFromTable('suporte_status.status_id',"Status",$r['status'],'status',array(
    'style'=>'max-width:400px'
));
FormHelper::input('nome', "Cliente",$r['nome'],array(
    'disabled'=>'true',
    'style'=>'max-width:400px'
));
FormHelper::input('email',"E-mail",$r['email'],array(
    'style'=>'max-width:400px'
));
$query = 'select * from pedido where ped_id = '.$r['piid'];
$p = $dbo->query($query,true);
?>
</div>
<div class="col-md-4">
<div class="form-group num_nf_group">
<label for="num_nf">Nota fiscal</label>
    <div class="input-group">
        <input id="num_nf" class="form-control " type="text" disabled="true" value="<?=$p['num_nf']?>" name="num_nf">
        <span class="input-group-btn">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
        </span>
        
    </div>
</div>

    <?
FormHelper::input('data_nf',"Data da nota fiscal",Helper::dbToDate($p['data_nf']),array(
    'disabled'=>'true'
));
FormHelper::input('data_atendimento','Data do atendimento',date('d/m/Y', $r['data_atendimento']),array(
    'disabled'=>'true'
));

?>
</div>
<div class="col-md-4"><?
    $query = "select status,(select rastreavel from produtos pr where i.ip_produto_id = pr.produto_id and i.ip_ped_id = p.ped_id) as produto_rastreavel,(select nome from produtos pr where i.ip_produto_id = pr.produto_id and i.ip_ped_id = p.ped_id) as produto_nome from pedido p, pedido_item i where p.numero = ".$r['piid']." and p.ped_id = i.ip_ped_id  ";
    echo $query;
    $items = $dbo->query($query);
    
?>
    <table class="table">
        <thead>
            <tr>
                <th>Status</th>
                <th class="mobile-half">Serial</th>
                <th>Produto</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?
                foreach($items as $i) {
                    ?>
                    <tr>
                        <td style='20%'><?=$i['status']?></td>
                        <td class="mobile-half" style='20%'><?=$i['produto_rastreavel']=="0"?"Não":"Sim!";?></td>
                        <td class="mobile-half" style='20%'><?=$i['produto_nome'];?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" type="button" title="Adicionar problema"><span class='glyphicon glyphicon-search'></span></button>
                        </td>
                    </tr>    
                    <?
                }
            ?>
        </tbody>
    </table>
</div></div>
<div class="row">
    <div class="col-md-12">
        <?
            FormHelper::startGroup();
            FormHelper::submitAjax("Salvar","salvar/".$r['protocolo'],array('class'=>'btn-primary'));
            FormHelper::button("excluir","Excluir",array(
                'class'=>'btn-danger'
            ));
            FormHelper::endGroup();
        ?>
    </div>
</div><?
FormHelper::end();