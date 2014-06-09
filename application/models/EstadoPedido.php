<?php
class Application_Model_EstadoPedido extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_EstadoPedido";
	protected $_desc_estado_pedido = null;
	protected $_cod_estado_pedido = null;
	protected $_sigla_estado_pedido = null;
	public $_data = null;
        
	public function getDesc_estado_pedido(){
		return $this->_desc_estado_pedido;
	}
	public function getCod_estado_pedido(){
		return $this->_cod_estado;
	}
	public function getSigla_estado_pedido(){
		return $this->_sigla_estado_pedido;
	}
	public function setDesc_estado_pedido($_ds_estado_pedido){
		$this->_desc_estado_pedido = $_ds_estado_pedido;
	}
	public function setCod_estado_pedido($_cod_estado_pedido){
		$this->_cod_estado_pedido = $_cod_estado_pedido;
	}
	public function setSigla_estado_pedido($_sigla_estado_pedido){
		$this->_sigla_estado_pedido= $_sigla_estado_pedido;
	}
	public function __get($propertyName) {
		$getter = "get" . $propertyName;
		if (!method_exists($this, $getter)) {
    		throw new RuntimeException("Property by name " . $propertyName . " not found as part of this object.");
		}
		return $this->{$getter}();
	}
	public function toArr() {
		return array(
			'COD_ESTADO' => $this->_cod_estado_pedido,
			'DS_ESTADO' => $this->_desc_estado_pedido,
			'SIG_ESTADO' => $this->_sigla_estado_pedido);
}
// No se usa por el momento
    public function setFromArray(array $data) {
    	foreach (array('cod_estado_pedido', 'desc_estado_pedido', 'sigla_estado_pedido') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
