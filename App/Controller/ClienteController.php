<?php

class ClienteController extends SecureController {

    public function index() {
        
    }

    public function inserir() {
        
    }

    public function editar() {
        
    }

    public function adicionaendereco() {
        
    }

    public function endereco() {
        
    }

    public function buscar() {
        $pesq = Request::value('pesquisa');
        global $db;
        if(preg_match("/[0-9]+/",$pesq)) {
            $condicao = "c.documento like '$pesq%'";
        } else {
            $condicao = "c.nome like '$pesq%'";
        }
        $clientes = "select distinct c.id,c.documento, c.nome, cc.nome as nomecontato, ct.telefone, ce.email from cliente c, contato cc, contato_telefone ct, contato_email ce where c.id = cc.cliente_id and c.id = ct.cliente_id and c.id = ce.cliente_id and $condicao  limit 50";
        $clientes = $db->query($clientes);
        foreach ($clientes as $c) {
            ?>
            <tr>
                <td><a href='<?= '/' . APP_DIR . "cliente/editar/" . $c['id']; ?>'><?= $c['nome'] ?></a></td>
                <td><?= $c['nomecontato'] ?></td>
                <td><?= Helper::formatDocumento($c['documento'])?></td>
                <td><?= $c['email'] ?></td>
                <td><?= $c['telefone'] ?></td>
            </tr>
            <?
        }
        if(count($clientes)==0) {
            echo "<tr><td colspan='4'>Não há resultados.</td></tr>";
        }
    }

    public function contato() {
        
    }

    public function salvar() {
        $p = $_POST;

        $values = array();
        global $db;
        $ident = Request::value('id');

        array_push($values, "'" . $p['Nome'] . "'");
        array_push($values, "'" . $p['Documento'] . "'");
        array_push($values, "'" . $p['Site'] . "'");
        array_push($values, "'3'");

        $values = implode(",", $values);
        $clienteId = "insert into cliente(nome,documento,site,module_id) values (" . $values . ")";

        if ($ident) {
            $clienteId = $ident;
        } else {
            $clienteId = $db->query($clienteId, true);
        }
        if (!isset($p['enderecos'])) {
            $arr = array(
                'status' => 'error',
                'message' => 'Adicione um endereço'
            );
            echo json_encode($arr);
            die();
        }
        $enderecos = $p['enderecos'];
        $enderecoIDs = array();
        $delQuery = "delete from cliente_endereco where cliente_id=" . $clienteId;
        $db->query($delQuery);


        foreach ($enderecos as $e) {
            $cidade_id = $db->query("select id,estado_id from cidade where nome='" . $e['cidade'] . "' and uf='" . $e['estado'] . "'", true);
            $estado_id = $cidade_id['estado_id'];
            $cidade_id = $cidade_id['id'];
            $end = array(
                "'" . $clienteId . "'",
                "'" . $e['logradouro'] . "'",
                "'" . $e['numero'] . "'",
                "'" . $e['cep'] . "'",
                "'" . $e['bairro'] . "'",
                "'" . $e['complemento'] . "'",
                "'" . $e['referencia'] . "'",
                "'" . $e['cliente_endereco_tipo_id'] . "'",
                "'" . $cidade_id . "'",
                "'" . $estado_id . "'"
            );

            $query = $db->query("insert into cliente_endereco (cliente_id,logradouro,numero,cep,bairro,complemento,referencia,cliente_endereco_tipo_id,cidade_id,estado_id) values (" . implode(',', $end) . ")", true);
            array_push($enderecoIDs, $query);
        }
        if (!isset($p['contatos'])) {
            $arr = array(
                'status' => 'error',
                'message' => 'Adicione um contato'
            );
            echo json_encode($arr);
            die();
        }
        $contatos = $p['contatos'];

        $delQuery = "delete from contato_email where cliente_id=" . $clienteId;
        $db->query($delQuery);
        $delQuery = "delete from contato_telefone where cliente_id=" . $clienteId;
        $db->query($delQuery);
        $delQuery = "delete from contato where cliente_id=" . $clienteId;
        $db->query($delQuery);

        foreach ($contatos as $c) {
            $cont = array(
                "'" . $c['nome'] . "'",
                "'" . $c['cargo'] . "'",
                "'" . $c['aniversario'] . "'",
                "'" . $c['contato_tipo_id'] . "'",
                "'" . $clienteId . "'",
                "'" . $enderecoIDs[$c['cliente_endereco_id']] . "'"
            );

            $query = "insert into contato (nome,cargo,aniversario,contato_tipo_id,cliente_id,cliente_endereco_id) values (" . implode(",", $cont) . ")";
            $contatoId = $db->query($query, true);

            foreach ($c['emails'] as $e) {
                $em = array(
                    "'" . $c['contato_tipo_id'] . "'",
                    "'" . $clienteId . "'",
                    "'" . $contatoId . "'",
                    "'" . $e . "'",
                    "'0'"
                );

                $db->query("insert into contato_email (contato_tipo_id,cliente_id,contato_id,email,pessoal) values(" . implode(",", $em) . ")");
            }



            foreach ($c['tels'] as $t) {
                $isCelular = preg_match("/\([0-9]{2}\)\s[8-9][0-9]{3,4}/", $t) || preg_match("/[8-9][0-9]{3,4}/", $t);
                $te = array(
                    "'" . $c['contato_tipo_id'] . "'",
                    "'" . $clienteId . "'",
                    "'" . $contatoId . "'",
                    "'" . $t . "'",
                    "'" . $isCelular . "'"
                );


                $db->query("insert into contato_telefone (contato_tipo_id,cliente_id,contato_id,telefone,celular) values(" . implode(",", $te) . ")");
            }
        }
        $_COOKIE['cliente'] = (object) array();
        $arr = array(
            'status' => 'success',
            'message' => 'salvo com sucesso',
            'redirect' => "/" . APP_DIR . "cliente/"
        );
        echo json_encode($arr);
    }

}