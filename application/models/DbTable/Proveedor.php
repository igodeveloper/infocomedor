<?php
class Application_Model_DbTable_Proveedor extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR"; // Esquema
	protected $_name = "PROVEEDOR"; // Nombre de la tabla
	public $_rowClass = "Application_Model_Proveedor";
	public $_primary = array('COD_PROVEEDOR'); // Primary Key
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR"; // Esquema
        public $_nombre = "PROVEEDOR";    // Nombre de la tabla
        public $_foreignkey = array();
        
} ?>
