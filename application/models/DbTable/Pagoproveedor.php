<?php 
class Application_Model_DbTable_Pagoproveedor extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "pago_proveedor";
	public $_rowClass = "Application_Model_Pagoproveedor";
	public $_primary = array('COD_PAGO_PROVEEDOR');
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor";
	public $_nombre = "pago_proveedor";
	public $_foreignkey = array();
} ?>