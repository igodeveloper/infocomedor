<?php 
class Application_Model_DbTable_Impuesto extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "impuesto";
	public $_rowClass = "Application_Model_Impuesto";
	public $_primary = array('COD_IMPUESTO');
	public $_primary_auto = TRUE;	
	public $_esquema = "infocomedor";
        public $_nombre = "impuesto";    
} ?>