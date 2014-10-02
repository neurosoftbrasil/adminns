<h1>Suporte</h1>
<?
echo Helper::js("App.Suporte");

global $db;
global $dbold;

FormHelper::create('formPesquisa', '', array('method' => 'get'));
?><table class="table clear">
    <tr>
        <td style="width:15%">
            <? FormHelper::input('documento', 'Documento (CPF/CNPJ)', Request::value('documento'), array(
                'placeholder'=>'CPF ou CNPJ',
                'onkeypress'=>'App.Suporte.Send(event)'
            )); ?>
        </td>
        <td style="width:15%">
            <? FormHelper::input('notafiscal', 'N.F.', Request::value('notafiscal'), array(
                'placeholder'=>'Nota fiscal',
                'onkeypress'=>'App.Suporte.Send(event)'
            )); ?>
        </td>
        <td style="width:55%">
            <? FormHelper::input('nome', 'Nome do Cliente', Request::value('nome'), array(
                'placeholder'=>'Nome do cliente',
                'onkeypress'=>'App.Suporte.Send(event)'
            )); ?>
        </td>
        <td>
            <label style="display:block">&nbsp;</label>
            <? FormHelper::button('pesquisar', 'Pesquisar', array(
                'onclick'=>'App.Suporte.Search()',
                'onkeypress'=>'App.Suporte.Send(event)'
            ))?>
        </td>
    </tr>
</table>
<? FormHelper::end(false); ?>
<table class="table">
    <thead>
        <tr>
            <th>Protocolo</th>
            <th style="width:30%">Nome do cliente</th>
            <th>Documento</th>
            <th style="width:30%">Status</th>
            <th>NF</th>
            <th>Data de emissão</th>
        </tr>
    </thead>
    <tbody id="results"></tbody>
</table>
<script type="text/javascript">
    App.Suporte.Search();
</script>