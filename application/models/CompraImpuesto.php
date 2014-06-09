<?php
class Application_Model_CompraImpuesto extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_CompraImpuesto";
	protected $_nro_factura_compra = null;
        protected $_det_item_impuesto = null;
	protected $_cod_impuesto = null;
        protected $_monto_impuesto = null;
	public $_data = null;


	public function getNro_Factura_Compra(){
		return $this->_nro_factura_compra; // ruc empresa
	}

	public function getDet_Item_Impuesto(){
		return $this->_det_item_impuesto; // direccion empresa
	}        
	public function getCod_Impuesto(){
		return $this->_cod_impuesto; // telefono empresa
 	}
        public function getMonto_Impuesto(){
		return $this->_monto_impuesto; 
	}        
	
	public function setNro_Factura_Compra($_nro_factura_compra){
		$this->_nro_factura_compra = $_nro_factura_compra;
	}
	public function setDet_Item_Impuesto($_det_item_impuesto){
		$this->_det_item_impuesto = $_det_item_impuesto;
	}        
	public function setCod_Impuesto($_cod_impuesto){
		$this->_cod_impuesto = $_cod_impuesto;
 	}
        public function setMonto_Impuesto($_monto_impuesto){
		$this->_monto_impuesto = $_monto_impuesto;
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
			
			'NRO_FACTURA_COMPRA' => $this->_nro_factura_compra,
			
                        'DET_ITEM_IMPUESTO' => $this->_det_item_impuesto,
			'COD_IMPUESTO' => $this->_cod_impuesto,
			'MONTO_IMPUESTO' => $this->_monto_impuesto);
}
    public function setFromArray(array $data) {
    	foreach (array('NRO_FACTURA_COMPRA',  
                       'DET_ITEM_IMPUESTO','COD_IMPUESTO','MONTO_IMPUESTO') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
