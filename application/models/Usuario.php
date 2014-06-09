<?php
class Application_Model_Usuario extends ZendExt_Db_Table_Row_Abstract {
	protected $_tableClass = "Application_Model_DbTable_Usuario";
	protected $_cod_Usuario = null;
        protected $_nombre_Usuario = null;
	protected $_apellido_Usuario = null;
	protected $_password_Usuario = null;

	public $_data = null;

	public function getCod_Usuario(){
		return $this->_cod_Usuario; // codigo Usuario
	}
        public function getNombre_Usuario(){
		return $this->_nombre_Usuario; // descripcion Usuario
	}
	public function getApellido_Usuario(){
		return $this->_apellido_Usuario; // ruc Usuario
	}
	public function getPassword_Usuario(){
		return $this->_password_Usuario; // ruc Usuario
	}



        public function setCod_Usuario($_cod_Usuario){
		$this->_cod_Usuario = $_cod_Usuario;
	}

        public function setNombre_Usuario($_nombre_Usuario){
		$this->_nombre_Usuario = $_nombre_Usuario;
	}

        public function setApellido_Usuario($_apellido_Usuario){
		$this->_apellido_Usuario = $_apellido_Usuario;
	}
        public function setPassword_Usuario($_password_Usuario){
		$this->_password_Usuario = $_password_Usuario;
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
			'COD_USUARIO' => $this->_cod_Usuario,
			'USUARIO_NOMBRE' => $this->_nombre_Usuario,
			'USUARIO_APELLIDO' => $this->_apellido_Usuario,
			'USUARIO_PASSWORD' => $this->_password_Usuario);
			

    }
    public function setFromArray(array $data){
    	foreach (array('cod_Usuario', 'nombre_Usuario', 'apellido_Usuario', 'password_Usuario') as  $property)
                {
                    if (isset($data[strtoupper($property)]))
                    {
                            $this->{'_'. $property} = $data[strtoupper($property)];
                    }
                }
    }
} ?>
