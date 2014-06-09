<?php
class Application_Model_Empleado extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Empleado";
	protected $_cod_Empleado = null;
        protected $_nombre_Empleado = null;
	protected $_apellido_Empleado = null;
	protected $_documento_Empleado = null;
	protected $_dir_Empleado = null;
	protected $_tel_Empleado = null;

	public $_data = null;

	public function getCod_Empleado(){
		return $this->_cod_Empleado; // codigo Empleado
	}
        public function getNombre_Empleado(){
		return $this->_nombre_Empleado; // descripcion Empleado
	}
	public function getApellido_Empleado(){
		return $this->_apellido_Empleado; // ruc Empleado
	}
	public function getDocumento_Empleado(){
		return $this->_documento_Empleado; // ruc Empleado
	}
	public function getDir_Empleado(){
		return $this->_dir_Empleado; // direccion Empleado
	}
	public function getTel_Empleado(){
		return $this->_tel_Empleado; // telefono Empleado
 	}
      

        public function setCod_Empleado($_cod_Empleado){
		$this->_cod_Empleado = $_cod_Empleado;
	}

        public function setNombre_Empleado($_nombre_Empleado){
		$this->_nombre_Empleado = $_nombre_Empleado;
	}

        public function setApellido_Empleado($_apellido_Empleado){
		$this->_apellido_Empleado = $_apellido_Empleado;
	}
        public function setDocumento_Empleado($_documento_Empleado){
		$this->_documento_Empleado = $_documento_Empleado;
	}

        public function setDir_Empleado($_dir_Empleado){
		$this->_dir_Empleado= $_dir_Empleado;
	}

        public function setTel_Empleado($_tel_Empleado){
		$this->_tel_Empleado = $_tel_Empleado;
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
			'COD_EMPLEADO' => $this->_cod_Empleado,
			'EMPLEADO_NOMBRE' => $this->_nombre_Empleado,
			'EMPLEADO_APELLIDO' => $this->_apellido_Empleado,
			'EMPLEADO_NRO_DOC' => $this->_documento_Empleado,
			'EMPLEADO_DIRECCION' => $this->_dir_Empleado,
			'EMPLEADO_TELEFONO' => $this->_tel_Empleado);
			
    }
    public function setFromArray(array $data){
    	foreach (array('cod_Empleado', 'nombre_Empleado', 'apellido_Empleado', 'documento_Empleado', 'dir_Empleado', 'tel_Empleado') as  $property)
                {
                    if (isset($data[strtoupper($property)]))
                    {
                            $this->{'_'. $property} = $data[strtoupper($property)];
                    }
                }
    }
} ?>
