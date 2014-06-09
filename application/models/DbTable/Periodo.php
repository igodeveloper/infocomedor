<?php 
class Application_Model_DbTable_Periodo extends ZendExt_Db_Table_Abstract {
	protected $_schema = "infocomedor";
	protected $_name = "periodo";
	public $_rowClass = "Application_Model_Periodo";
	public $_primary = array('COD_PERIODO');
	public $_primary_auto = FALSE;	
	public $_esquema = "infocomedor";
        public $_nombre = "periodo";    
} ?>