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
        $query  = "select protocolo,suporte_cliente.cliente_id, suporte_cliente.nome,cpf,p.num_nf,p.data_nf,ss.nome as status from (select c.*,s.protocolo,s.status,s.pedido from suporte s, clientes c where c.cliente_id = s.cid $qclientes) as suporte_cliente ".($qpedido!=""?"inner":"left")." join pedido p on (suporte_cliente.pedido = p.ped_id $qpedido) left join suporte_status ss on (suporte_cliente.status = ss.status_id)";
        $query .= " limit 100";
        
        $results = $dbo->query($query);
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
