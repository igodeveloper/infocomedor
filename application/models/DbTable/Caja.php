<?php 
class Application_Model_DbTable_Caja extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "caja";
	public $_rowClass = "Application_Model_Caja";
	public $_primary = array('cod_caja');
	public $_primary_auto = FALSE;
	public $_esquema = "infocomedor";
	public $_nombre = "caja";
	public $_foreignkey = array();
} ?>