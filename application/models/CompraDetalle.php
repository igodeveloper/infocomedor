<?php
class Application_Model_CompraDetalle extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_CompraDetalle";
	protected $_nro_factura_compra = null;
	protected $_det_item_compra = null;
	protected $_cod_producto = null;
	protected $_cantidad_compra = null;
        protected $_monto_compra = null;
	protected $_cod_unidad_medida = null;
	public $_data = null;

	
	public function getNro_Factura_Compra(){
		return $this->_nro_factura_compra; // ruc empresa
	}
	public function getDet_Item_Compra(){
		return $this->_det_item_compra; // direccion empresa
	}
	public function getCod_Producto(){
		return $this->_cod_producto; // telefono empresa
 	}
        public function getCantidad_Compra(){
		return $this->_cantidad_compra; // nombre del contacto empresa
	}
        public function getMonto_Compra(){
		return $this->_monto_compra; 
	}        
        public function geCod_unidad_medida(){
		return $this->_cod_unidad_medida;
	}                
	
	public function setNro_Factura_Compra($_nro_factura_compra){
		$this->_nro_factura_compra = $_nro_factura_compra;
	}
	public function setDet_Item_Compra($_det_item_compra){
		$this->_det_item_compra = $_det_item_compra;
	}
	public function setCod_Producto($_cod_producto){
		$this->_cod_producto = $_cod_producto;
 	}
        public function setCantidad_Compra($_cantidadCompra){
		$this->_cantidad_compra = $_cantidadCompra;
	}
        public function setMonto_Compra($_monto_compra){
		$this->_monto_compra = $_monto_compra;
	}        
        public function setCod_unidad_medida($_cod_unidad_medida){
		$this->_cod_unidad_medida = $_cod_unidad_medida;
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
			'DET_ITEM_COMPRA' => $this->_det_item_compra,
			'COD_PRODUCTO_ITEM' => $this->_cod_producto,
			'CANTIDAD_COMPRA' => $this->_cantidad_compra,
			'MONTO_COMPRA' => $this->_monto_compra,
			'COD_UNIDAD_MEDIDA' => $this->_cod_unidad_medida);
                
}
    public function setFromArray(array $data) {
    	foreach (array('NRO_FACTURA_COMPRA', 'DET_ITEM_COMPRA', 
            'COD_PRODUCTO_ITEM', 'CANTIDAD_COMPRA','MONTO_COMPRA','COD_UNIDAD_MEDIDA') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
