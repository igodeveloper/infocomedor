<?php
class Application_Model_DbTable_Sucursal extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor"; // Esquema
	protected $_name = "Sucursal"; // Nombre de la tabla
	public $_rowClass = "Application_Model_Sucursal";
	public $_primary = array('COD_SUCURSAL'); // Primary Key
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor"; // Esquema
        public $_nombre = "Sucursal";    // Nombre de la tabla
} ?>