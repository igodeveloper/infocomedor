<?php 
class Application_Model_DbTable_Tipomovimiento extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "tipo_movimiento";
	public $_rowClass = "Application_Model_Tipomovimiento";
	public $_primary = array('cod_tipo_mov');
	public $_primary_auto = FALSE;
	public $_esquema = "infocomedor";
	public $_nombre = "tipo_movimiento";
	public $_foreignkey = array();
} ?>