<?php
class Application_Model_DbTable_Empresa extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor"; // Esquema
	protected $_name = "Empresa"; // Nombre de la tabla
	public $_rowClass = "Application_Model_Empresa";
	public $_primary = array('COD_EMPRESA'); // Primary Key
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor"; // Esquema
        public $_nombre = "Empresa";    // Nombre de la tabla
} ?>