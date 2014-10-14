<div class="modal-header"><h2>Editar contato</h2></div>  
<div class="modal-body">
    <?
        $ident = Request::value("ident");
        
        global $db;
        $query = "select * from contato c where c.id = $ident";
        $contact = $db->query($query,true);
        
        foreach($contact as $key=>$value) {
            $$key = $value;
        }
        
        FormHelper::create('formContato');
        ?>
            <div class="row">
                <div class="col-md-12">
        <?
        FormHelper::input('nome','Nome <b>*</b>',$nome,array(
            'placeholder'=>'Digite o nome do contato'
        ));
        ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
        <?
        FormHelper::input('cargo',"Cargo",$cargo,array(
            'placeholder'=>'Digite o cargo'
        ));
        ?>
                </div>
                <div class="col-md-4">
        <?
        FormHelper::input('aniversario',"Aniversário",Helper::dbToDate($aniversario),array(
            'placeholder'=>'00/00/0000'
        ));
        ?>
                </div>
            </div>
        <div class="row">
            <div class="col-md-12">
                <?
                    FormHelper::selectFromTable('contato_tipo.id', 'descricao', 'Tipo de contato <b>*</b>', $contato_tipo_id);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>E-mail <strong>*</strong></label>
                <div id="email">
        <?
            $emails = "select * from contato_email where contato_id = $ident";
            $emails = $db->query($emails);
            for($i=0;$i<count($emails);$i++) {
                ?>
                    <div class="form-group email_group email_<?=$i?>">
                    <input id="email<?=$i?>" class="form-control inputTypeAhead" type="text" onblur="App.Contato.SalvarEmail(this)" name="email<?=$i?>" value="<?=$emails[$i]['email']?>" placeholder="Digite o e-mail">
                <?
            }
        ?>
                        </div>
                </div>
            </div>
        </div>
        <div class="row marginbottom">
            <div class="col-md-12">
                <button type="button" onclick="App.Contato.AdicionaEmail()" class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-plus'></span> Adicionar e-mail</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>Telefone <strong>*</strong></label>
                <div id="telefone">
                    <?
                        $tels = "select * from contato_telefone where contato_id = $ident";
                        $tels = $db->query($tels);
                        for($i=0;$i<count($tels);$i++) {
                    ?>
                    <div class="form-group telefone_group telefone_<?=$i?>">
                        <input id="telefone<?=$i?>" class="form-control inputTypeAhead" type="text" onblur="App.Contato.SalvarTelefone(this)" name="telefone<?=$i?>" value="<?=$tels[$i]['telefone']?>" placeholder="+55 (00) 0000-00000">
                    </div>                        
                 <?
            }
        ?>
                </div>
            </div>
        </div>
        <div class="row marginbottom">
            <div class="col-md-12">
                <button type="button" onclick="App.Contato.AdicionaTelefone()" class='btn btn-primary btn-sm'><span class='glyphicon glyphicon-plus'></span> Adicionar telefone</button>
            </div>
        </div>
    <?
        $ends = json_decode($_COOKIE['cliente']);
    ?>
        <div class="row marginbottom">
            <div class="col-md-12">
                <div class="form-group aniversario_group">
                    <label for="cliente_endereco_id">Endereço do contato</label>
                
                    <select class="form-control " name='cliente_endereco_id' id='cliente_endereco_id'><?
        $counter = 0;
        foreach($ends->enderecos as $e) {
            ?><option value="<?=$counter?>"><?= $e->logradouro.", ".$e->numero." - ".$e->cidade."/".$e->estado?></option><?
            $counter++;
        }
        ?>          </select>
            </div>
        </div><?
        FormHelper::end(false);
    ?>
</div>
<div class="modal-footer">  
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
    <button type="button" class="btn btn-primary" onclick="App.Contato.Salvar()">Salvar</button>
</div>
<script type="text/javascript">
    $("#formContato")[0].reset();
</script>