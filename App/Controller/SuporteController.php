<?php

class SuporteController extends SecureController {
    public function index() {
        
    }
    public function editar() {
        
    }
    public function search() {
        global $dbo;
        $nome = Request::value('nome');
        $documento = Request::value('documento');
        $notafiscal = Request::value('notafiscal');
        
        $qclientes = "";
        $qpedido = "";
        if($nome) {
            $qclientes .= " and lower(c.nome) like '".strtolower($nome)."%'";
        }
        if($documento) {
            $qclientes .= " and c.cpf like '".$documento."%'";
        }
        if($notafiscal) {
            $qpedido .= " and p.num_nf like '".$notafiscal."%'";
        }
        $query  = "select * from suporte limit 1";
        Util::prints($dbo->query($query));
        $query = "select * from pedido where numero = 397";
        Util::prints($dbo->query($query));
        
        $results = array();
        
        $html = "";
        
        foreach($results as $r) {
            $html .="<tr>";
                $html .= "<td><a href='".Helper::link("suporte/editar/".$r['protocolo'])."'>".$r['protocolo']."</a></td>";
                $html .= "<td><a href='".Helper::link("suporte/editar/".$r['protocolo'])."'>".$r['nome']."</a></td>";
                $html .= "<td>".$r['cpf']."</td>";
                $html .= "<td>".$r['status']."</td>";
                $html .= "<td>".$r['num_nf']."</td>";
                $html .= "<td>".Helper::dbToDate($r['data_nf'])."</td>";
            $html .="</tr>";
        }
        if(count($results)==0) {
            $html = "<tr><td colspan='6'>Não há resultados para esta pesquisa.</td></tr>";
        }
        echo $html;
    }   
}
