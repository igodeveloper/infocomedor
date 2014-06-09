<?php
class Application_Model_DbTable_Empleado extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor"; // Esquema
	protected $_name = "Empleado"; // Nombre de la tabla
	public $_rowClass = "Application_Model_Empleado";
	public $_primary = array('COD_EMPLEADO'); // Primary Key
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor"; // Esquema
        public $_nombre = "Empleado";    // Nombre de la tabla
} ?>