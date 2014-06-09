<?php
class Application_Model_DbTable_Concepto extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "Concepto";
	public $_rowClass = "Application_Model_Concepto";
	public $_primary = array('COD_CONCEPTO');
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor";
        public $_nombre = "Concepto";
} ?>