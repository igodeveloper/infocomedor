<?php
class Application_Model_TipoInsumo extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_TipoInsumo";
	protected $_desc_tipo_insumo = null;
	protected $_cod_tipo_insumo = null;
	protected $_iso_tipo_insumo = null;
	public $_data = null;
	public function getDesc_tipo_insumo(){
		return $this->_desc_tipo_insumo;
	}
	public function getCod_tipo_insumo(){
		return $this->_cod_tipo_insumo;
	}
	public function getIso_tipo_insumo(){
		return $this->_iso_tipo_insumo;
	}
	public function setDesc_tipo_insumo($_ds_tipoinsumo){
		$this->_desc_tipo_insumo = $_ds_tipoinsumo;
	}
	public function setCod_tipo_insumo($_cod_tipoinsumo){
		$this->_cod_tipo_insumo = $_cod_tipoinsumo;
	}
	public function setIso_tipo_insumo($_iso_tipoinsumo){
		$this->_iso_tipo_insumo = $_iso_tipoinsumo;
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
			'COD_TIPO_INSUMO' => $this->_cod_tipo_insumo,
			'TIPO_INSUMO_DESCRIPCION' => $this->_desc_tipo_insumo,
			'TIPO_INSUMO_ISO' => $this->_iso_tipo_insumo);
}
    public function setFromArray(array $data) {
    	foreach (array('cod_tipo_insumo', 'desc_tipo_insumo', 'iso_tipo_insumo') as  $property) {
    		if (isset($data[strtoupper($property)])) {
     			$this->{'_'. $property} = $data[strtoupper($property)];
    		}
    	}
    }
} ?>