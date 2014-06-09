<?php 
class Application_Model_DbTable_UnidadMedida extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "UNIDAD_MEDIDA";
	public $_rowClass = "Application_Model_UnidadMedida";
	public $_primary = array('COD_UNIDAD_MEDIDA');
	public $_primary_auto = TRUE;	
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "UNIDAD_MEDIDA";    
} ?>