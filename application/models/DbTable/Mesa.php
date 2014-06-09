<?php
class Application_Model_DbTable_Mesa extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "Mesa";
	public $_rowClass = "Application_Model_Mesa";
	public $_primary = array('COD_MESA');
	public $_primary_auto = TRUE;
	public $_esquema = "infocomedor";
        public $_nombre = "Mesa";
} ?>