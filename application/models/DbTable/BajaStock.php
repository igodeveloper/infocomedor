<?php 
class Application_Model_DbTable_BajaStock extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "baja_stock";
	public $_rowClass = "Application_Model_BajaStock";
	public $_primary = array('cod_baja_stock');
	public $_primary_auto = FALSE;
	public $_esquema = "infocomedor";
	public $_nombre = "baja_stock";
	public $_foreignkey = array();
} ?>