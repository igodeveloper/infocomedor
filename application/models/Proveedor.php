<?php
class Application_Model_Proveedor extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Proveedor";
	protected $_cod_Proveedor= null;
        protected $_desc_Proveedor= null;
        protected $_ruc_Proveedor= null;
	protected $_dir_Proveedor= null;
	protected $_tel_Proveedor= null;
	protected $_cont_nom_Proveedor= null;
        protected $_email_Proveedor= null;
        protected $_limite_credito_Proveedor= null;
        public $_data = null;

	public function getCod_Proveedor(){
		return $this->_cod_Proveedor;
	}
        public function getDesc_Proveedor(){
		return $this->_desc_Proveedor;
	}
        public function getRuc_Proveedor(){
		return $this->_ruc_Proveedor;
        }
	public function getDir_Proveedor(){
		return $this->_dir_Proveedor;
	}
	public function getTel_Proveedor(){
		return $this->_tel_Proveedor;
 	}
        public function getCont_nom_Proveedor(){
		return $this->_cont_nom_Proveedor;
	}
        public function getEmail_Proveedor(){
		return $this->_email_Proveedor;
	}
        public function getLimite_credito_Proveedor(){
		return $this->_limite_credito_Proveedor;
	}

        public function setCod_Proveedor($_cod_Proveedor){
		$this->_cod_Proveedor = $_cod_Proveedor;
	}

        public function setDesc_Proveedor($_ds_Proveedor){
		$this->_desc_Proveedor = $_ds_Proveedor;
	}

        public function setRuc_Proveedor($_ruc_Proveedor){
		$this->_ruc_Proveedor = $_ruc_Proveedor;
	}

        public function setDir_Proveedor($_dir_Proveedor){
		$this->_dir_Proveedor= $_dir_Proveedor;
	}

        public function setTel_Proveedor($_tel_Proveedor){
		$this->_tel_Proveedor = $_tel_Proveedor;
	}

        public function setCont_nom_Proveedor($_cont_nom_Proveedor){
		$this->_cont_nom_Proveedor = $_cont_nom_Proveedor;
	}
        public function setEmail_Proveedor($_email_Proveedor){
		$this->_email_Proveedor = $_email_Proveedor;
	}
        public function setLimite_credito_Proveedor($_limite_credito_Proveedor){
		$this->_limite_credito_Proveedor = $_limite_credito_Proveedor;
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
			'COD_PROVEEDOR' => $this->_cod_Proveedor,
			'PROVEEDOR_NOMBRE' => $this->_desc_Proveedor,
			'PROVEEDOR_RUC' => $this->_ruc_Proveedor,
			'PROVEEDOR_DIRECCION' => $this->_dir_Proveedor,
                        'PROVEEDOR_TELEFONO' => $this->_tel_Proveedor,
			'PROVEEDOR_CONTACTO' => $this->_cont_nom_Proveedor,
			'PROVEEDOR_EMAIL' => $this->_email_Proveedor,
			'PROVEEDOR_LIMITE_CREDITO' => $this->_limite_credito_Proveedor);
}
    public function setFromArray(array $data) {
    	foreach (array('COD_PROVEEDOR', 'PROVEEDOR_NOMBRE','PROVEEDOR_RUC', 'PROVEEDOR_DIRECCION', 
                       'PROVEEDOR_TELEFONO', 'PROVEEDOR_CONTACTO','PROVEEDOR_EMAIL','PROVEEDOR_LIMITE_CREDITO') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
