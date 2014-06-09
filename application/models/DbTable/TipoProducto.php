<?php
class Application_Model_DbTable_TipoProducto extends ZendExt_Db_Table_Abstract {
	protected $_schema = "INFOCOMEDOR";
	protected $_name = "TIPO_PRODUCTO";
	public $_rowClass = "Application_Model_TipoProducto";
	public $_primary = array('COD_TIPO_PRODUCTO');
	public $_primary_auto = TRUE;
	public $_esquema = "INFOCOMEDOR";
        public $_nombre = "TIPO_PRODUCTO";
} ?>