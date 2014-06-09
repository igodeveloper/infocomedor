<?php
class Application_Model_DbTable_Usuario extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor"; // Esquema
	protected $_name = "Usuario"; // Nombre de la tabla
	public $_rowClass = "Application_Model_Usuario";
	public $_primary = array('COD_USUARIO'); // Primary Key
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor"; // Esquema
        public $_nombre = "Usuario";    // Nombre de la tabla
} ?>