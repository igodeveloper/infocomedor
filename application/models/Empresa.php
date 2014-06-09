<?php
class Application_Model_Empresa extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Empresa";
	protected $_cod_Empresa = null;
        protected $_desc_Empresa = null;
	protected $_ruc_Empresa = null;
	protected $_dir_Empresa = null;
	protected $_tel_Empresa = null;
	protected $_cont_nom_Empresa = null;
	public $_data = null;

	public function getCod_Empresa(){
		return $this->_cod_Empresa; // codigo empresa
	}
        public function getDesc_Empresa(){
		return $this->_desc_Empresa; // descripcion empresa
	}
	public function getRuc_Empresa(){
		return $this->_ruc_Empresa; // ruc empresa
	}
	public function getDir_Empresa(){
		return $this->_dir_Empresa; // direccion empresa
	}
	public function getTel_Empresa(){
		return $this->_tel_Empresa; // telefono empresa
 	}
        public function getCont_nom__Empresa(){
		return $this->_cont_nom_Empresa; // nombre del contacto empresa
	}
        
        public function setCod_Empresa($_cod_Empresa){
		$this->_cod_Empresa = $_cod_Empresa;
	}
        
        public function setDesc_Empresa($_ds_Empresa){
		$this->_desc_Empresa = $_ds_Empresa;
	}
	
        public function setRuc_Empresa($_ruc_Empresa){
		$this->_ruc_Empresa = $_ruc_Empresa;
	}
        
        public function setDir_Empresa($_dir_Empresa){
		$this->_dir_Empresa= $_dir_Empresa;
	}

        public function setTel_Empresa($_tel_Empresa){
		$this->_tel_Empresa = $_tel_Empresa;
	}

        public function setCont_nom_Empresa($_cont_nom_Empresa){
		$this->_cont_nom_Empresa = $_cont_nom_Empresa;
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
			'COD_EMPRESA' => $this->_cod_Empresa,
			'DES_EMPRESA' => $this->_desc_Empresa,
			'EMP_RUC' => $this->_ruc_Empresa,
			'EMP_DIRECCION' => $this->_dir_Empresa,
			'EMP_TELEFONO' => $this->_tel_Empresa,
			'EMP_NOMBRE_CONTAC' => $this->_cont_nom_Empresa);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_Empresa', 'desc_Empresa', 'ruc_Empresa', 'dir_Empresa', 'tel_Empresa', 'cont_nom_Empresa') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
