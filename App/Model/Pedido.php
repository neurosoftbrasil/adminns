<?
class Pedido extends AppModel {
    public $item = array();
    public $pagamento = array();
    public $status = 1;
        
    public function __construct() {
        
    }
    public function load($id = false) {
        $this->setTable('pedido');
        parent::load($id);
    }
    public function adicionarItem() {
        
    }
}