<?php

class MigracaoController extends SecureController {
    public function produtos() {
        global $db;
        global $dbo;
        $query = "select produto_id as id,produto_id as codigo, descricoes_resumida as descricao, pacote_caixa as unidade, preco_real as preco, nome_nf as nome, name as nome_internacional,visivel,fabricante as fabricante_id,kit,rastreavel,tipo from produtos";
        $old_prods = $dbo->query($query);
        $tipo = array(
                'Produto' => 0,
                'Servico' => 1,
                'Curso' => 2
        );
        $length = 0;
        foreach($old_prods as $p) {
            $query = "select * from produto where id=".$p['id'];
            $new_prods = $db->query($query);
            

            if(count($new_prods)==0) {
                $p['descricao'] = mysql_real_escape_string($p['descricao']);
                $p['tipo'] = $tipo[$p['tipo']];
                $p['kit'] = $p['kit'] == "Não" ? "0" : "1";

                $query = "insert into produto (
                    id,
                    codigo,
                    descricao, unidade, preco,
                    nome,
                    nome_internacional,
                    visivel,
                    fabricante_id,
                    kit,
                    serial,
                    tipo_id
                ) values ('".implode("','",$p)."');";
                $db->query($query);
                echo $query;
                $length++;
            }
        }
        echo "<br/>".$length." produto(s) importado(s).<br/>";
    }

    public function clientes() {
        global $db;
        global $dbo;
        $u = $db->query("select * from cliente");
        $clientes = $dbo->query('select cliente_id as id, nome, cpf as documento, 3 as module_id from clientes c order by id');

        foreach ($clientes as $c) {
            $id = $c['id'];
            if ($id > 0 && count($db->query("select id from cliente where id=" . $id)) == 0) {
                if (!preg_match("/[.-]/", $c['documento'])) {
                    $len = strlen($c['documento']);
                    $doc = $c['documento'];
                    $ndoc = "";
                    switch ($len) {
                        case 11:
                            $ndoc .= substr($doc, 0, 3) . ".";
                            $ndoc .= substr($doc, 3, 3) . ".";
                            $ndoc .= substr($doc, 6, 3) . "-";
                            $ndoc .= substr($doc, 9, 2);
                            break;
                    }
                    $c['documento'] = $ndoc;
                }
                $query = "insert into cliente (id,nome,documento,module_id) values ('" . implode("','", $c) . "')";
                $db->query($query);
            }
        }
    }
    
    public function pj() {
        global $db;
        global $dbo;
        $u = $db->query("select * from cliente");
        $clientes = $dbo->query('select cliente_pj_id as id, razao as nome, cnpj as documento, 3 as module_id from clientes_pj c');

        foreach ($clientes as $c) {
            $id = $c['id'];
            if ($id > 0 && $c['nome'] != '' && count($db->query("select id from cliente where id=" . $id)) == 0) {
                if (!preg_match("/[.-]/", $c['documento'])) {
                    $len = strlen($c['documento']);
                    $doc = $c['documento'];
                    $ndoc = "";
                    switch ($len) {
                        case 14:
                            $ndoc .= substr($doc, 0, 2) . ".";
                            $ndoc .= substr($doc, 2, 3) . ".";
                            $ndoc .= substr($doc, 5, 3) . "/";
                            $ndoc .= substr($doc, 8, 4) . "-";
                            $ndoc .= substr($doc, 12, 2);
                            break;
                    }
                    $c['documento'] = $ndoc;
                }
                $query = "insert into cliente (id,nome,documento,module_id) values ('" . implode("','", $c) . "')";
                $db->query($query);
                echo $query . "<br/>";
            }
        }
    }

    public function contato() {
        global $db;
        global $dbo;

        $q = array();

        array_push($q, "cliente_id");
        array_push($q, "nome");
        array_push($q, "contato1");
        array_push($q, "contato2");
        array_push($q, "email");
        array_push($q, "telefone1 as telefone");

        $query = "select ";
        $query .= implode(",", $q);
        $query1 = $query . ", 1 as cliente_contato_tipo, 1 as padrao, '' as cargo from clientes where cliente_id>0 order by cliente_id";

        $contatos = $dbo->query($query1);

        foreach ($contatos as $c) {
            $c['aniversario'] = '0000-00-00';
            if (!preg_match("/[0-9]/", $c['contato1']) && $c['contato1'] != '') {
                if ($c['contato1'] != "") {
                    $c['nome'] = $c['contato1'];
                }
                $isCelular = preg_match("/[8-9][0-9]{3}(\-)?[0-9]{4}$/", $c['telefone']) ? 1 : 0;

                unset($c['contato1']);
                unset($c['contato2']);

                $query = $db->query("select * from cliente c, contato co where c.id = contato.cliente_id");
                if (count($query) == 0) {
                    $contato = "insert into contato(cliente_id,nome,contato_tipo_id,padrao,cargo,aniversario) values ";
                    $contato .= "(" . $c['cliente_id'] . ",'" . $c['nome'] . "',1,1,'','0000-00-00');";
                    $contato = $db->query($contato);
                    $contato_telefone = "insert into contato_telefone(telefone,celular,contato_id,cliente_id,contato_tipo_id) values ('" . $c['telefone'] . "'," . $isCelular . "," . $contato . "," . $c['cliente_id'] . ",1);";
                    $db->query($contato_telefone);
                    $contato_email = "insert into contato_email(email,pessoal,contato_id,cliente_id,contato_tipo_id) values('" . $c['email'] . "',0," . $contato . "," . $c['cliente_id'] . ",1)";
                    $db->query($contato_email);
                }
            } else {
                //$c['telefone'] = $c['contato1'];
                Util::prints($c);
                $isCelular = preg_match("/[8-9][0-9]{3}(\-)?[0-9]{4}$/", $c['telefone']) ? 1 : 0;
                $contato = "insert into contato(cliente_id,nome,contato_tipo_id,padrao,cargo,aniversario) values ";
                $contato .= "(" . $c['cliente_id'] . ",'" . $c['nome'] . "',1,1,'','0000-00-00');";
                $contato = $db->query($contato);
                $contato_telefone = "insert into contato_telefone(telefone,celular,contato_id,cliente_id,contato_tipo_id) values ('" . $c['telefone'] . "'," . $isCelular . "," . $contato . "," . $c['cliente_id'] . ",1);";
                $db->query($contato_telefone);
                $contato_email = "insert into contato_email(email,pessoal,contato_id,cliente_id,contato_tipo_id) values('" . $c['email'] . "',0," . $contato . "," . $c['cliente_id'] . ",1)";
                $db->query($contato_email);
                //echo $contato."<br/>".$contato_telefone."<br/>".$contato_email;
            }
        }
    }
    

    public function endereco() {
        global $db;
        global $dbo;

        $q = array();

        array_push($q, "cliente_id");
        array_push($q, "endereco as logradouro");
        array_push($q, "endereco_numero as numero");
        array_push($q, "endereco_complementos as complemento");
        array_push($q, "bairro");
        array_push($q, "endereco_ref_entrega as referencia");
        array_push($q, "cep");
        array_push($q, "cidade");
        array_push($q, "estado");

        $query = "select ";
        $query .= implode(",", $q);
        $query1 = $query . ",1 as cliente_endereco_tipo_id from clientes_enderecos order by cliente_id";

        $enderecos = $dbo->query($query1);

        $counter = 50000;

        foreach ($enderecos as $e) {

            if ($counter > 21483) {
                $exists = "select * from cliente_endereco where cliente_id='" . $e['cliente_id'] . "'";
                $exists = $db->query($exists);

                if (count($exists) == 0) {
                    $cep = $e['cep'];
                    $len = strlen($cep);
                    $tmp = "";
                    switch ($len) {
                        case 9:
                            $tmp = substr($cep, 0, 2) . ".";
                            $tmp .= substr($cep, 2, 3) . "-";
                            $tmp .= substr($cep, 6, 3);
                            break;
                        case 8:
                            $tmp = substr($cep, 0, 2) . ".";
                            $tmp .= substr($cep, 2, 3) . "-";
                            $tmp .= substr($cep, 5, 3);
                            break;
                    }
                    if (strlen($tmp) == 10) {
                        $add = "http://apps.widenet.com.br/busca-cep/api/cep.json?code=$tmp";
                        //die($add);
                        $json = file_get_contents($add);
                        $json = json_decode($json);
                        $e['bairro'] = $json->district;
                        $cidade = "select id,nome,uf,estado_id from cidade where nome like '" . $json->city . "' and uf='" . $json->state . "'";
                        $city = $db->query($cidade, true);
                        $e['cidade_id'] = $city['id'];
                        $e['estado_id'] = $city['estado_id'];
                    }
                    if (intval(str_replace("-", "", $e['cep'])) == 0) {
                        $e['cep'] = '';
                    } else {
                        $e['cep'] = $tmp;
                    }
                    unset($e['cidade']);
                    unset($e['estado']);
                    if (trim($e['logradouro'] != '' && $e['cep'] != '')) {
                        $query = "insert into cliente_endereco(cliente_id,logradouro,numero,complemento,bairro,referencia,cep,cliente_endereco_tipo_id,cidade_id,estado_id) values (";
                        $values = array();
                        foreach ($e as $key => $value) {
                            array_push($values, "'" . $value . "'");
                        }
                        $query .= implode(",", $values);
                        $query .= ");";
                        $db->query($query);
                    }
                }
            }
            // $counter++;
        }
    }
    public function arrumarTelefones() {
        global $db;

        $query = "select * from contato_telefone";
        $ct = $db->query($query);
        foreach ($ct as $c) {
            $newt = "";
            $c['telefone'] = trim($c['telefone']);
            if (preg_match("/$[0-9]{2}\-[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0-9]{2}\-\s[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 9, 4);
            } else
            if (preg_match("/^[0-9]{5}\s[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 3, 2) . ") ";
                $newt .= substr($c['telefone'], 6, 4) . "-";
                $newt .= substr($c['telefone'], 11, 4);
            } else
            if (preg_match("/^[0-9]{2}\s\-[0-9]{4}\.[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 9, 4);
            } else
            if (preg_match("/^[0-9]{3}\s[0-9]{6}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 5, 2) . ") ";
                $newt .= substr($c['telefone'], 6, 4) . "-";
                $newt .= substr($c['telefone'], 11, 4);
            } else
            if (preg_match("/^[0-9]{3}\-[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 9, 4);
            } else
            if (preg_match("/^[0-9]{10}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 2, 4) . "-";
                $newt .= substr($c['telefone'], 6, 4);
            } else
            if (preg_match("/^\([0-9]{2}\)\s[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 5, 4) . "-";
                $newt .= substr($c['telefone'], 10, 4);
            } else
            if (preg_match("/^\([0-9]{2}\)\s[0-9]{4}\s[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 5, 4) . "-";
                $newt .= substr($c['telefone'], 10, 4);
            } else
            if (preg_match("/^[0-9]{2}\s[0-9]{8}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 7, 4);
            } else
            if (preg_match("/^[0-9]{2}\-[0-9]{8}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 7, 4);
            } else
            if (preg_match("/^\([0-9]{2}\)\s[0-9]{5}\-[0-9]{3}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 5, 4) . "-";
                $newt .= substr($c['telefone'], 9, 1);
                $newt .= substr($c['telefone'], 11, 3);
            } else
            if (preg_match("/^\([0-9]{2}\)\s[0-9]{8}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 5, 4) . "-";
                $newt .= substr($c['telefone'], 9, 4);
            } else
            if (preg_match("/^[0-9]{2}\s[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0-9]{3}\s[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 9, 4);
            } else
            if (preg_match("/^[0-9]{2}\s[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 8, 2);
                $newt .= substr($c['telefone'], 11, 2);
            } else
            if (preg_match("/^\([0-9]{2}\)[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 9, 4);
            } else
            if (preg_match("/^[0-9]{2}\)[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0-9]{2}\s[0-9]{4}\s[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0-9]{2}\-[0-9]{4}\-[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0][0-9]{10}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 7, 4);
            } else
            if (preg_match("/^21-3523 6599$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 3, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0-9]{8}$/", $c['telefone'])) {
                $newt .= substr($c['telefone'], 0, 4) . "-";
                $newt .= substr($c['telefone'], 4, 4);
            } else
            if (preg_match("/^\([0-9]{2}\)[0-9]{8}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0-9]{2}\-\s[0-9]{4}\s[0-9]{4}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 0, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 9, 4);
            } else
            if (preg_match("/^[0][0-9]{2}\-[0-9]{8}$/", $c['telefone'])) {
                $newt .= "(" . substr($c['telefone'], 1, 2) . ") ";
                $newt .= substr($c['telefone'], 4, 4) . "-";
                $newt .= substr($c['telefone'], 8, 4);
            } else
            if (preg_match("/^[0-9]{4}\s[0-9]{4}$/", $c['telefone'])) {
                $newt .= substr($c['telefone'], 0, 4) . "-";
                $newt .= substr($c['telefone'], 5, 4);
            } else {
                $newt = $c['telefone'];
            }

            $c['telefone'] = $newt;

            $query = "update contato_telefone set telefone='" . $c['telefone'] . "' where id=" . $c['id'];
            $db->query($query);
        }
    }
}