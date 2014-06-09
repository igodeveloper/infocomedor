<?php
class Application_Model_Sucursal extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Sucursal";
	protected $_cod_Sucursal= null;
        protected $_desc_Sucursal= null;

	protected $_dir_Sucursal= null;
	protected $_tel_Sucursal= null;
	protected $_cont_nom_Sucursal= null;
	public $_data = null;

	public function getCod_Sucursal(){
		return $this->_cod_Sucursal; // codigo Sucursal
	}
        public function getDesc_Sucursal(){
		return $this->_desc_Sucursal; // descripcion Sucursal
	}

	public function getDir_Sucursal(){
		return $this->_dir_Sucursal; // direccion Sucursal
	}
	public function getTel_Sucursal(){
		return $this->_tel_Sucursal; // telefono Sucursal
 	}
        public function getCont_nom__Sucursal(){
		return $this->_cont_nom_Sucursal; // nombre del contacto Sucursal
	}

        public function setCod_Sucursal($_cod_Sucursal){
		$this->_cod_Sucursal = $_cod_Sucursal;
	}

        public function setDesc_Sucursal($_ds_Sucursal){
		$this->_desc_Sucursal = $_ds_Sucursal;
	}

        
        public function setDir_Sucursal($_dir_Sucursal){
		$this->_dir_Sucursal= $_dir_Sucursal;
	}

        public function setTel_Sucursal($_tel_Sucursal){
		$this->_tel_Sucursal = $_tel_Sucursal;
	}

        public function setCont_nom_Sucursal($_cont_nom_Sucursal){
		$this->_cont_nom_Sucursal = $_cont_nom_Sucursal;
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
			'COD_SUCURSAL' => $this->_cod_Sucursal,
			'DES_SUCURSAL' => $this->_desc_Sucursal,
			
			'SUC_DIRECCION' => $this->_dir_Sucursal,
			'SUC_TELEFONO' => $this->_tel_Sucursal,
			'SUC_NOMBRE_ENCARGADO' => $this->_cont_nom_Sucursal);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_Sucursal', 'desc_Sucursal', 'dir_Sucursal', 'tel_Sucursal', 'cont_nom_Sucursal') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>
