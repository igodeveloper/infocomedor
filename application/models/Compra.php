<?php
class Application_Model_Compra extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Compra";
        protected $_cod_proveedor= null;
	protected $_nro_factura_compra = null;
	protected $_fecha_emision_factura = null;
	protected $_fecha_vencimiento_factura = null;
	protected $_monto_total_compra = null;
        protected $_cod_moneda_compra = null;
        protected $_cod_usuario = null;
        protected $_cod_forma_pago = null;
        protected $_control_fiscal= null;
        protected $_estado= null;
        
        
	public $_data = null;

	
        public function getCod_Proveedor(){
		return $this->_cod_proveedor; // descripcion empresa
	}
	public function getNro_Factura_Compra(){
		return $this->_nro_factura_compra; // ruc empresa
	}
	public function getFecha_Emision_Factura(){
		return $this->_fecha_emision_factura; // direccion empresa
	}
	public function getFecha_Vencimiento_Factura(){
		return $this->_fecha_vencimiento_factura; // telefono empresa
 	}
        public function getMonto_Total_Compra(){
		return $this->_monto_total_compra; // nombre del contacto empresa
	}
        public function getCod_Moneda_Compra(){
		return $this->_cod_moneda_compra; 
	}        
                       
        public function getCod_Usuario(){
		return $this->_cod_usuario; 
	}                        
        public function getCod_Forma_Pago(){
		return $this->_cod_forma_pago; 
	}
        public function getControl_Fiscal(){
		return $this->_control_fiscal; 
	}
		public function getEstado(){
		return $this->_estado; 
	}
        public function setCod_Proveedor($_cod_proveedor){
		$this->_cod_proveedor = $_cod_proveedor;
	}
	public function setNro_Factura_Compra($_nro_factura_compra){
		$this->_nro_factura_compra = $_nro_factura_compra;
	}
	public function setFecha_Emision_Factura($_fecha_emision_factura){
		$this->_fecha_emision_factura = $_fecha_emision_factura;
	}
	public function setFecha_Vencimiento_Factura($_fecha_vencimiento_factura){
		$this->_fecha_vencimiento_factura = $_fecha_vencimiento_factura;
 	}
        public function setMonto_Total_Compra($_monto_total_compra){
		$this->_monto_total_compra = $_monto_total_compra;
	}
        public function setCod_Moneda_Compra($_cod_moneda_compra){
		$this->_cod_moneda_compra = $_cod_moneda_compra;
	}                              
        public function setCod_Usuario($_cod_usuario){
		$this->_cod_usuario = $_cod_usuario;
	}
        public function setCod_Forma_Pago($_cod_forma_pago){
		$this->_cod_forma_pago = $_cod_forma_pago;
	}
        public function setControl_Fiscal($_control_fiscal){
		$this->_control_fiscal = $_control_fiscal; 
	}
        public function setEstado($_estado){
		$this->_estado = $_estado; 
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
			
			'COD_PROVEEDOR' => $this->_cod_proveedor,
			'NRO_FACTURA_COMPRA' => $this->_nro_factura_compra,
			'FECHA_EMISION_FACTURA' => $this->_fecha_emision_factura,
			'FECHA_VENCIMIENTO_FACTURA' => $this->_fecha_vencimiento_factura,
			'MONTO_TOTAL_COMPRA' => $this->_monto_total_compra,
			'COD_MONEDA_COMPRA' => $this->_cod_moneda_compra,
                        'COD_FORMA_PAGO' => $this->_cod_forma_pago,
                        'COD_USUARIO' => $this->_cod_usuario,
                        'CONTROL_FISCAL' => $this->_control_fiscal,
		'ESTADO' => $this->_estado
		);
}
    public function setFromArray(array $data) {
    foreach (array(
            'COD_PROVEEDOR', 'NRO_FACTURA_COMPRA', 'FECHA_EMISION_FACTURA', 
            'FECHA_VENCIMIENTO_FACTURA', 'MONTO_TOTAL_COMPRA','COD_MONEDA_COMPRA',
            'COD_FORMA_PAGO','COD_USUARIO','CONTROL_FISCAL','ESTADO') as  $property) {
                if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
