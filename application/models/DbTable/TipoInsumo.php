<?php
class Application_Model_DbTable_TipoInsumo extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "TIPO_INSUMO";
	public $_rowClass = "Application_Model_TipoInsumo";
	public $_primary = array('COD_TIPO_INSUMO');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "TIPO_INSUMO";
} ?>