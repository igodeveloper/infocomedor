<?php
class Application_Model_DbTable_FormaPago extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "Forma_Pago";
	public $_rowClass = "Application_Model_FormaPago";
	public $_primary = array('COD_FORMA_PAGO');
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor";
        public $_nombre = "Forma_Pago";
} ?>