<?php
class Application_Model_DbTable_Compra extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "COMPRA";
	public $_rowClass = "Application_Model_Compra";
	public $_primary = array('NRO_FACTURA_COMPRA');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "COMPRA";
        public $_foreignkey = array();
} ?>
