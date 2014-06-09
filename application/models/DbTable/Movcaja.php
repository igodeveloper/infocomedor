<?php 
class Application_Model_DbTable_Movcaja extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "mov_caja";
	public $_rowClass = "Application_Model_Movcaja";
	public $_primary = array('cod_mov_caja');
	public $_primary_auto = FALSE;
	public $_esquema = "infocomedor";
	public $_nombre = "mov_caja";
	public $_foreignkey = array();
} ?>