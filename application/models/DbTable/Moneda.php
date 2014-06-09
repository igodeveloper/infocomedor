<?php
class Application_Model_DbTable_Moneda extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "MONEDA";
	public $_rowClass = "Application_Model_Moneda";
	public $_primary = array('COD_MONEDA');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "MONEDA";
} ?>
